import React from 'react';
import { Link } from 'react-router-dom';

const Header = () => (
  <header>
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <div className="collapse navbar-collapse" id="navbarSupportedContent">
        <ul className="navbar-nav mr-auto">
          <li className="nav-item"><Link to='/' className="nav-link">Home</Link></li>
          <li className="nav-item"><Link to='/upload' className="nav-link">Upload</Link></li>
          <li className="nav-item"><Link to='/pubCharts' className="nav-link">Public Charts</Link></li>
        </ul>
      </div>
    </nav>
  </header>
)

export default Header;
