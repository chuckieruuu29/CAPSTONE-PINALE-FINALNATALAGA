import React from 'react';

const Orders = () => {
  return (
    <div className="container-fluid">
      <div className="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 className="h3 mb-0 text-gray-800">Order Processing</h1>
        <button className="btn btn-primary">
          <i className="fas fa-plus fa-sm text-white-50"></i> Create New Order
        </button>
      </div>

      <div className="row mb-4">
        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card border-left-warning shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Pending Orders
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">8</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-clock fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card border-left-primary shadow h-100 py-2">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    In Production
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">15</div>
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
                    Ready to Ship
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">5</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-shipping-fast fa-2x text-gray-300"></i>
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
                    Delivered
                  </div>
                  <div className="h5 mb-0 font-weight-bold text-gray-800">142</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="card shadow mb-4">
        <div className="card-header py-3">
          <h6 className="m-0 font-weight-bold text-primary">Order Management System</h6>
        </div>
        <div className="card-body">
          <p>Comprehensive order processing system featuring:</p>
          <ul>
            <li>Real-time order tracking from placement to delivery</li>
            <li>Automated order fulfillment workflow</li>
            <li>Customer communication and notifications</li>
            <li>Production scheduling integration</li>
            <li>Shipping and logistics management</li>
            <li>Order analytics and reporting</li>
          </ul>
          <div className="alert alert-success">
            <strong>Integrated Features:</strong> Seamless integration with inventory, production, and customer management systems.
          </div>
        </div>
      </div>
    </div>
  );
};

export default Orders;