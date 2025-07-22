<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'schedule_name',
        'scheduled_date',
        'start_time',
        'end_time',
        'planned_quantity',
        'work_station',
        'assigned_worker',
        'shift',
        'status',
        'completion_percentage',
        'notes',
        'required_materials',
        'completion_log'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'completion_percentage' => 'decimal:2',
        'required_materials' => 'array',
        'completion_log' => 'array',
    ];

    // Relationships
    public function productionBatch(): BelongsTo
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByShift($query, $shift)
    {
        return $query->where('shift', $shift);
    }

    public function scopeByWorker($query, $worker)
    {
        return $query->where('assigned_worker', $worker);
    }

    public function scopeByWorkStation($query, $station)
    {
        return $query->where('work_station', $station);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('scheduled_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    // Accessors & Mutators
    public function getDurationHoursAttribute()
    {
        if (!$this->start_time || !$this->end_time) return 0;
        
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        return $end->diffInHours($start);
    }

    public function getIsOverdueAttribute()
    {
        return $this->scheduled_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getScheduleStatusAttribute()
    {
        if ($this->status === 'completed') return 'completed';
        if ($this->is_overdue) return 'overdue';
        if ($this->scheduled_date->isToday()) return 'today';
        if ($this->scheduled_date->isFuture()) return 'scheduled';
        return 'pending';
    }

    // Business Methods
    public function canStart()
    {
        return $this->status === 'scheduled' && 
               $this->scheduled_date->isToday() &&
               $this->checkMaterialAvailability();
    }

    public function startSchedule()
    {
        if (!$this->canStart()) {
            throw new \Exception('Cannot start this schedule');
        }

        $this->status = 'in_progress';
        $this->logProgress(0, 'Schedule started');
        $this->save();

        return $this;
    }

    public function updateProgress($percentage, $notes = null)
    {
        if ($this->status !== 'in_progress') {
            throw new \Exception('Can only update progress for schedules in progress');
        }

        $this->completion_percentage = min(100, max(0, $percentage));
        
        if ($notes) {
            $this->notes = $notes;
        }

        $this->logProgress($percentage, $notes);

        if ($this->completion_percentage >= 100) {
            $this->status = 'completed';
        }

        $this->save();

        return $this;
    }

    public function completeSchedule($actualQuantity = null, $notes = null)
    {
        if ($this->status !== 'in_progress') {
            throw new \Exception('Can only complete schedules in progress');
        }

        $this->status = 'completed';
        $this->completion_percentage = 100;
        
        if ($actualQuantity) {
            $this->logProgress(100, "Completed with {$actualQuantity} units produced");
            
            // Update production batch
            $this->productionBatch->recordProduction($actualQuantity, 0, $notes);
        } else {
            $this->logProgress(100, 'Schedule completed');
        }

        if ($notes) {
            $this->notes = $notes;
        }

        $this->save();

        return $this;
    }

    public function cancelSchedule($reason = null)
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            throw new \Exception('Cannot cancel completed or already cancelled schedule');
        }

        $this->status = 'cancelled';
        $this->logProgress($this->completion_percentage, "Cancelled: " . ($reason ?? 'No reason provided'));
        $this->save();

        return $this;
    }

    public function delaySchedule($newDate, $reason = null)
    {
        if ($this->status === 'completed') {
            throw new \Exception('Cannot delay completed schedule');
        }

        $oldDate = $this->scheduled_date;
        $this->scheduled_date = $newDate;
        $this->status = 'delayed';
        
        $delayReason = $reason ?? 'Schedule delayed';
        $this->logProgress($this->completion_percentage, 
                         "Delayed from {$oldDate->format('Y-m-d')} to {$newDate->format('Y-m-d')}: {$delayReason}");
        
        $this->save();

        return $this;
    }

    protected function logProgress($percentage, $notes = null)
    {
        $log = $this->completion_log ?? [];
        
        $log[] = [
            'timestamp' => now()->toISOString(),
            'percentage' => $percentage,
            'notes' => $notes,
            'worker' => $this->assigned_worker,
            'status' => $this->status
        ];

        $this->completion_log = $log;
    }

    protected function checkMaterialAvailability()
    {
        if (!$this->required_materials) return true;

        foreach ($this->required_materials as $material) {
            $rawMaterial = RawMaterial::find($material['material_id']);
            if (!$rawMaterial || $rawMaterial->current_stock < $material['quantity']) {
                return false;
            }
        }

        return true;
    }

    public function getRequiredMaterialsList()
    {
        if (!$this->productionBatch || !$this->productionBatch->product) {
            return [];
        }

        return $this->productionBatch->product->calculateRequiredMaterials($this->planned_quantity);
    }

    public function estimateCompletionTime()
    {
        if (!$this->productionBatch || !$this->productionBatch->product) {
            return null;
        }

        $productionHours = $this->productionBatch->product->production_time_hours;
        $estimatedHours = ($productionHours * $this->planned_quantity) / 
                         ($this->productionBatch->planned_quantity ?: 1);

        return $this->scheduled_date->copy()
                   ->setTimeFromTimeString($this->start_time)
                   ->addHours($estimatedHours);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($schedule) {
            if (!$schedule->schedule_name) {
                $schedule->schedule_name = "Schedule for " . 
                                         $schedule->productionBatch->batch_number . 
                                         " on " . 
                                         $schedule->scheduled_date->format('Y-m-d');
            }

            if (!$schedule->required_materials && $schedule->productionBatch) {
                $schedule->required_materials = $schedule->getRequiredMaterialsList();
            }
        });
    }
}
