import React, {Component} from 'react'
import { Link } from 'react-router-dom';
import { connect } from 'react-redux';

class Header  extends Component {
  constructor(props) {
      super(props)
      this.state = {}
  }
  render() {
    console.log('in header: ', this.props);
    return (
      <header>
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
          <div className="collapse navbar-collapse" id="navbarSupportedContent">
            <ul className="navbar-nav mr-auto">
              <li className="nav-item"><Link to='/' className="nav-link">Home</Link></li>
              <li className="nav-item"><Link to='/upload' className="nav-link">Upload</Link></li>
              <li className="nav-item"><Link to='/pubCharts' className="nav-link">Public Charts</Link></li>
            </ul>
          </div>
          {this.props.uProfile.name ? <div className="pull-right">Hello, {this.props.uProfile.name}</div> : null}
        </nav>
      </header>
    )
  }
}

function mapStateToProps(state) {
  console.log('state: ', state.login.profile);
  return {
    user: state.login,
    uProfile: state.login.profile,
  };
}

export default connect(mapStateToProps)(Header);
