import React from 'react';

const Production = () => {
  return (
    <div className="container-fluid">
      <div className="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 className="h3 mb-0 text-gray-800">Production Tracking</h1>
        <button className="btn btn-primary">
          <i className="fas fa-plus fa-sm text-white-50"></i> New Production Batch
        </button>
      </div>

      <div className="row mb-4">
        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card border-left-primary shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Active Batches
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">8</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-cogs fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card border-left-success shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Completed Today
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">24</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card border-left-info shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Efficiency Rate
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">87%</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-chart-line fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card border-left-warning shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Behind Schedule
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">3</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-clock fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="card shadow mb-4">
        <div className="card-header py-3">
          <h6 className="m-0 font-weight-bold text-primary">Production Management System</h6>
        </div>
        <div className="card-body">
          <p>Comprehensive production tracking and optimization featuring:</p>
          <ul>
            <li><strong>Real-time Production Monitoring:</strong> Track daily woodcraft production outputs</li>
            <li><strong>Batch Management:</strong> Organize production into manageable batches</li>
            <li><strong>Resource Allocation:</strong> Optimize worker and equipment assignment</li>
            <li><strong>Quality Control:</strong> Monitor production quality and track defects</li>
            <li><strong>Efficiency Analytics:</strong> Measure performance and identify improvements</li>
            <li><strong>Scheduling:</strong> Plan production based on orders and capacity</li>
          </ul>

          <div className="row mt-4">
            <div className="col-md-6">
              <h6>Current Production Schedule</h6>
              <div className="list-group">
                <div className="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong>Dining Table Set</strong><br />
                    <small className="text-muted">Batch #PT-2025-001</small>
                  </div>
                  <span className="badge bg-primary rounded-pill">In Progress</span>
                </div>
                <div className="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong>Oak Kitchen Cabinets</strong><br />
                    <small className="text-muted">Batch #PT-2025-002</small>
                  </div>
                  <span className="badge bg-warning rounded-pill">Scheduled</span>
                </div>
                <div className="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong>Bookshelf Units</strong><br />
                    <small className="text-muted">Batch #PT-2025-003</small>
                  </div>
                  <span className="badge bg-success rounded-pill">Completed</span>
                </div>
              </div>
            </div>
            <div className="col-md-6">
              <h6>Production Alerts</h6>
              <div className="alert alert-warning">
                <strong>Material Shortage:</strong> Oak planks running low for Batch #PT-2025-004
              </div>
              <div className="alert alert-info">
                <strong>Quality Check:</strong> Batch #PT-2025-001 ready for final inspection
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Production;