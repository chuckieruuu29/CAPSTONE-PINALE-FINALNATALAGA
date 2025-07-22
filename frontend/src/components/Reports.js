import React from 'react';

const Reports = () => {
  return (
    <div className="container-fluid">
      <div className="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 className="h3 mb-0 text-gray-800">Reports & Analytics</h1>
        <button className="btn btn-primary">
          <i className="fas fa-download fa-sm text-white-50"></i> Export Report
        </button>
      </div>

      <div className="row mb-4">
        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card bg-primary text-white shadow">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                    Sales Reports
                  </div>
                  <div className="h6 mb-0 font-weight-bold">Revenue, Orders, Customers</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-chart-bar fa-2x text-white-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card bg-success text-white shadow">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                    Production Reports
                  </div>
                  <div className="h6 mb-0 font-weight-bold">Efficiency, Output, Quality</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-cogs fa-2x text-white-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card bg-info text-white shadow">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                    Inventory Reports
                  </div>
                  <div className="h6 mb-0 font-weight-bold">Stock, Usage, Costs</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-warehouse fa-2x text-white-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-3 col-md-6 mb-4">
          <div className="card bg-warning text-white shadow">
            <div className="card-body">
              <div className="row no-gutters align-items-center">
                <div className="col mr-2">
                  <div className="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                    Customer Reports
                  </div>
                  <div className="h6 mb-0 font-weight-bold">Analysis, Trends, Satisfaction</div>
                </div>
                <div className="col-auto">
                  <i className="fas fa-users fa-2x text-white-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="card shadow mb-4">
        <div className="card-header py-3">
          <h6 className="m-0 font-weight-bold text-primary">Automated Reporting System</h6>
        </div>
        <div className="card-body">
          <p>Comprehensive reporting and analytics system featuring:</p>
          
          <div className="row">
            <div className="col-md-6">
              <h6><i className="fas fa-chart-line text-primary"></i> Sales & Financial Reports</h6>
              <ul>
                <li>Daily/Monthly sales performance</li>
                <li>Revenue analysis by product category</li>
                <li>Customer order trends</li>
                <li>Profit margin analysis</li>
                <li>Payment and credit tracking</li>
              </ul>
            </div>
            <div className="col-md-6">
              <h6><i className="fas fa-cogs text-success"></i> Production Reports</h6>
              <ul>
                <li>Daily production output tracking</li>
                <li>Worker productivity analysis</li>
                <li>Equipment efficiency metrics</li>
                <li>Quality control statistics</li>
                <li>Production cost analysis</li>
              </ul>
            </div>
          </div>

          <div className="row mt-3">
            <div className="col-md-6">
              <h6><i className="fas fa-warehouse text-info"></i> Inventory & MRP Reports</h6>
              <ul>
                <li>Stock level monitoring</li>
                <li>Material usage trends</li>
                <li>Reorder point optimization</li>
                <li>Storage cost analysis</li>
                <li>Supplier performance metrics</li>
              </ul>
            </div>
            <div className="col-md-6">
              <h6><i className="fas fa-users text-warning"></i> Customer Analytics</h6>
              <ul>
                <li>Customer engagement metrics</li>
                <li>Order fulfillment tracking</li>
                <li>Customer satisfaction analysis</li>
                <li>Market reach assessment</li>
                <li>Customer lifetime value</li>
              </ul>
            </div>
          </div>

          <div className="alert alert-success mt-4">
            <h6><i className="fas fa-download"></i> Export Capabilities</h6>
            <p className="mb-0">All reports can be exported in multiple formats (PDF, Excel, CSV) with automated scheduling for regular delivery to stakeholders.</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Reports;