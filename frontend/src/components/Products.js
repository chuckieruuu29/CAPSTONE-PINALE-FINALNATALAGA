import React from 'react';

const Products = () => {
  return (
    <div className="container-fluid">
      <div className="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 className="h3 mb-0 text-gray-800">Product Management</h1>
        <button className="btn btn-primary">
          <i className="fas fa-plus fa-sm text-white-50"></i> Add New Product
        </button>
      </div>

      <div className="card shadow mb-4">
        <div className="card-header py-3">
          <h6 className="m-0 font-weight-bold text-primary">Woodcraft Products Catalog</h6>
        </div>
        <div className="card-body">
          <p>This module manages the complete product catalog including:</p>
          <ul>
            <li>Product specifications (wood type, dimensions, finish)</li>
            <li>Bill of Materials (BOM) management</li>
            <li>Production time estimates</li>
            <li>Pricing and cost calculations</li>
            <li>Stock level monitoring</li>
            <li>Product categories and variants</li>
          </ul>
          <div className="alert alert-info">
            <strong>Features:</strong> Advanced BOM management, cost calculation, and integration with inventory and production systems.
          </div>
        </div>
      </div>
    </div>
  );
};

export default Products;