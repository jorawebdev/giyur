import React, {Component} from 'react'
import { Switch, Route } from 'react-router-dom'
import { connect } from 'react-redux';
import Home from './pages/Home'
import Upload from './pages/Upload'
import PubCharts from './pages/pubCharts'
import PrivateRoute from './components/login/PrivateRoute'

class Routes extends Component {
  render() {
    return (
      <div>
        <Switch>
          <Route exact path='/' component={Home}/>
          <Route path='/pubCharts' component={PubCharts}/>
          <PrivateRoute path="/upload" component={Upload} />
        </Switch>
      </div>
    );
  }
}

export default Routes;
