import React, { Component } from 'react';
import axios from 'axios';
const serviceBase = window.location.pathname.split('/dist')[0];

class Register extends Component {
  submit(e){
    e.preventDefault()
    const data = {user:{name:'zach',email:'zach@zach.com',password:'test123',roles:'basic'}}; //get from form
    console.log(data);
    axios.post(serviceBase + '/services/insertCustomer', data)
      .then(function (response) {
        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });

  }

  render() {
    return (
        <div className="col-3">
          <h4>Register</h4>
          <form onSubmit={this.submit} id="Register">
            <div className="form-group">
              <label htmlFor="uname" className="float-left">Username</label>
              <input type="text" className="form-control" placeholder="Enter Username" name="uname" required/>
            </div>
            <div className="form-group">
              <label htmlFor="psw" className="float-left">Password</label>
              <input type="password" className="form-control" placeholder="Enter Password" name="psw" required/>
            </div>
            <button type="submit" className="btn btn-primary float-right">Register</button>
          </form>
        </div>
    );
  }
}

export default Register;
