import React from 'react';

const Inventory = () => {
  return (
    <div className="container-fluid">
      <div className="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 className="h3 mb-0 text-gray-800">Inventory Management (MRP)</h1>
        <button className="btn btn-primary">
          <i className="fas fa-plus fa-sm text-white-50"></i> Add Stock
        </button>
      </div>

      <div className="row mb-4">
        <div className="col-lg-4 col-md-6 mb-4">
          <div className="card border-left-success shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Total Inventory Value
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">$45,320</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-dollar-sign fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-4 col-md-6 mb-4">
          <div className="card border-left-warning shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Low Stock Items
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">12</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-4 col-md-6 mb-4">
          <div className="card border-left-danger shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Reorder Required
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">5</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-shopping-cart fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="card shadow mb-4">
        <div className="card-header py-3">
          <h6 className="m-0 font-weight-bold text-primary">Material Requirements Planning (MRP)</h6>
        </div>
        <div className="card-body">
          <p>Advanced inventory management system featuring:</p>
          <ul>
            <li><strong>Raw Materials Tracking:</strong> Wood, hardware, finishes, adhesives</li>
            <li><strong>Finished Products Inventory:</strong> Real-time stock levels</li>
            <li><strong>Automated Reorder Points:</strong> Smart reordering based on usage patterns</li>
            <li><strong>Supplier Management:</strong> Lead times and cost tracking</li>
            <li><strong>Predictive Analytics:</strong> Forecasting material needs</li>
            <li><strong>Cost Analysis:</strong> Storage costs and inventory optimization</li>
          </ul>
          
          <div className="row mt-4">
            <div className="col-md-6">
              <div className="alert alert-warning">
                <h6><i className="fas fa-exclamation-triangle"></i> Reorder Alerts</h6>
                <ul className="mb-0">
                  <li>Oak Wood Planks - 15 units remaining</li>
                  <li>Cabinet Hinges - 8 units remaining</li>
                  <li>Wood Stain (Walnut) - 3 liters remaining</li>
                </ul>
              </div>
            </div>
            <div className="col-md-6">
              <div className="alert alert-info">
                <h6><i className="fas fa-chart-line"></i> Usage Forecast</h6>
                <p className="mb-0">Based on current orders and production schedule, estimated material requirements for next 30 days are calculated automatically.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Inventory;