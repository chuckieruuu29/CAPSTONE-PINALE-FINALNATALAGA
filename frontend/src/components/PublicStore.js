import React from 'react';

const PublicStore = () => {
  return (
    <div>
      {/* Navigation */}
      <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
        <div className="container">
          <a className="navbar-brand" href="/">
            <i className="fas fa-hammer me-2"></i>
            Unick Enterprises
          </a>
          <div className="navbar-nav ms-auto">
            <a className="nav-link" href="#products">Products</a>
            <a className="nav-link" href="#about">About</a>
            <a className="nav-link" href="#contact">Contact</a>
            <a className="nav-link" href="#track">Track Order</a>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="bg-primary text-white py-5">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-lg-6">
              <h1 className="display-4 fw-bold">Handcrafted Woodwork</h1>
              <p className="lead mb-4">
                Premium quality furniture and woodcraft from Cabuyao City, Laguna. 
                Each piece is carefully crafted with attention to detail and built to last.
              </p>
              <div>
                <button className="btn btn-light btn-lg me-3">
                  <i className="fas fa-shopping-cart me-2"></i>
                  Shop Now
                </button>
                <button className="btn btn-outline-light btn-lg">
                  <i className="fas fa-phone me-2"></i>
                  Get Quote
                </button>
              </div>
            </div>
            <div className="col-lg-6 text-center">
              <div className="bg-light rounded p-4" style={{ height: '300px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <div className="text-dark">
                  <i className="fas fa-hammer fa-5x mb-3"></i>
                  <h5>Craftsmanship Excellence</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="py-5">
        <div className="container">
          <div className="row text-center">
            <div className="col-md-4 mb-4">
              <div className="card h-100 shadow-sm">
                <div className="card-body">
                  <i className="fas fa-tree fa-3x text-success mb-3"></i>
                  <h5>Quality Materials</h5>
                  <p>We use only the finest hardwoods and sustainable materials for our furniture.</p>
                </div>
              </div>
            </div>
            <div className="col-md-4 mb-4">
              <div className="card h-100 shadow-sm">
                <div className="card-body">
                  <i className="fas fa-tools fa-3x text-primary mb-3"></i>
                  <h5>Expert Craftsmanship</h5>
                  <p>Our skilled artisans bring decades of experience to every piece they create.</p>
                </div>
              </div>
            </div>
            <div className="col-md-4 mb-4">
              <div className="card h-100 shadow-sm">
                <div className="card-body">
                  <i className="fas fa-shipping-fast fa-3x text-warning mb-3"></i>
                  <h5>Reliable Delivery</h5>
                  <p>Fast and secure delivery with real-time tracking for your peace of mind.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Product Categories */}
      <section className="py-5 bg-light" id="products">
        <div className="container">
          <div className="text-center mb-5">
            <h2 className="display-5 fw-bold">Our Products</h2>
            <p className="lead">Explore our range of handcrafted furniture and woodwork</p>
          </div>
          
          <div className="row">
            <div className="col-lg-3 col-md-6 mb-4">
              <div className="card shadow-sm h-100">
                <div className="card-img-top bg-secondary d-flex align-items-center justify-content-center" style={{ height: '200px' }}>
                  <i className="fas fa-chair fa-3x text-white"></i>
                </div>
                <div className="card-body text-center">
                  <h5 className="card-title">Dining Sets</h5>
                  <p className="card-text">Elegant dining tables and chairs for your home.</p>
                  <button className="btn btn-primary">View Collection</button>
                </div>
              </div>
            </div>

            <div className="col-lg-3 col-md-6 mb-4">
              <div className="card shadow-sm h-100">
                <div className="card-img-top bg-secondary d-flex align-items-center justify-content-center" style={{ height: '200px' }}>
                  <i className="fas fa-door-open fa-3x text-white"></i>
                </div>
                <div className="card-body text-center">
                  <h5 className="card-title">Kitchen Cabinets</h5>
                  <p className="card-text">Custom kitchen cabinetry designed to fit your space.</p>
                  <button className="btn btn-primary">View Collection</button>
                </div>
              </div>
            </div>

            <div className="col-lg-3 col-md-6 mb-4">
              <div className="card shadow-sm h-100">
                <div className="card-img-top bg-secondary d-flex align-items-center justify-content-center" style={{ height: '200px' }}>
                  <i className="fas fa-book fa-3x text-white"></i>
                </div>
                <div className="card-body text-center">
                  <h5 className="card-title">Storage Solutions</h5>
                  <p className="card-text">Bookcases, wardrobes, and storage furniture.</p>
                  <button className="btn btn-primary">View Collection</button>
                </div>
              </div>
            </div>

            <div className="col-lg-3 col-md-6 mb-4">
              <div className="card shadow-sm h-100">
                <div className="card-img-top bg-secondary d-flex align-items-center justify-content-center" style={{ height: '200px' }}>
                  <i className="fas fa-palette fa-3x text-white"></i>
                </div>
                <div className="card-body text-center">
                  <h5 className="card-title">Decorative Items</h5>
                  <p className="card-text">Handcrafted decorative pieces and artwork.</p>
                  <button className="btn btn-primary">View Collection</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Order Tracking */}
      <section className="py-5" id="track">
        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-6">
              <div className="card shadow">
                <div className="card-body">
                  <h4 className="card-title text-center mb-4">
                    <i className="fas fa-search me-2"></i>
                    Track Your Order
                  </h4>
                  <form>
                    <div className="mb-3">
                      <label className="form-label">Order Number</label>
                      <input type="text" className="form-control" placeholder="Enter your order number" />
                    </div>
                    <div className="mb-3">
                      <label className="form-label">Email Address</label>
                      <input type="email" className="form-control" placeholder="Enter your email address" />
                    </div>
                    <button type="submit" className="btn btn-primary w-100">
                      <i className="fas fa-search me-2"></i>
                      Track Order
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-dark text-white py-5">
        <div className="container">
          <div className="row">
            <div className="col-lg-4 mb-4">
              <h5>Unick Enterprises Inc.</h5>
              <p>Premium woodcraft furniture manufacturer based in Cabuyao City, Laguna. Serving customers with quality handcrafted furniture since our establishment.</p>
            </div>
            <div className="col-lg-4 mb-4">
              <h5>Contact Information</h5>
              <p>
                <i className="fas fa-map-marker-alt me-2"></i>
                Cabuyao City, Laguna, Philippines<br />
                <i className="fas fa-phone me-2"></i>
                +63 (123) 456-7890<br />
                <i className="fas fa-envelope me-2"></i>
                info@unickenterprises.com
              </p>
            </div>
            <div className="col-lg-4 mb-4">
              <h5>Follow Us</h5>
              <div>
                <button className="btn btn-link text-white me-3 p-0" onClick={() => window.open('https://facebook.com', '_blank')}><i className="fab fa-facebook fa-2x"></i></button>
                <button className="btn btn-link text-white me-3 p-0" onClick={() => window.open('https://instagram.com', '_blank')}><i className="fab fa-instagram fa-2x"></i></button>
                <button className="btn btn-link text-white me-3 p-0" onClick={() => window.open('https://twitter.com', '_blank')}><i className="fab fa-twitter fa-2x"></i></button>
              </div>
            </div>
          </div>
          <hr />
          <div className="text-center">
            <p>&copy; 2025 Unick Enterprises Inc. All rights reserved. | Woodcraft Management System</p>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default PublicStore;