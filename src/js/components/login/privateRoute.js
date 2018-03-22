import React, { Component } from 'react';
import { Redirect } from 'react-router';
import { Route } from 'react-router-dom';

const isAuth = (props) => {
  //console.log('bla', props, fakeAuth.isAuthenticated);
  if(props.location.state && props.location.state.isLoggedIn){
    return true;
  } else {
    return false;
  }
}
const PrivateRoute = ({ component: Component, ...rest }) => (
  <Route
    {...rest}
    render={props =>
      isAuth(props) ? (
        <Component {...props} />
      ) : (
        <Redirect
          to={{
            pathname: "/",
            state: { from: props.location }
          }}
        />
      )
    }
  />
);
export default PrivateRoute;
