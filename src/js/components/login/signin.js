import React, { Component } from 'react';
import { Redirect } from 'react-router'
import { connect } from 'react-redux';
import Cookies from 'universal-cookie';
import axios from 'axios';
import FormErrors from './formErrors';

const serviceBase = window.location.pathname.split('/dist')[0];

class Signin extends Component {
  constructor(props) {
      super(props)
      console.log('in signin 1', props);
      this.state = {
          username: '',
          password: '',
          redirectToReferrer: false,
          formErrors: {username: '', password: '', submit: ''},
          emailValid: false,
          passwordValid: false,
          formValid: false,
          submit: false
      };
      this.submit = this.submit.bind(this);
  }

  validateField(fieldName, value) {
    let fieldValidationErrors = this.state.formErrors;
    let emailValid = this.state.emailValid;
    let passwordValid = this.state.passwordValid;
    let submit = this.state.submit;
    console.log('in validateField', fieldName, value);
    fieldValidationErrors.submit = '';

    switch(fieldName) {
      case 'username':
        emailValid = value.match(/^([\w.%+-]+)@([\w-]+\.)+([\w]{2,})$/i);
        fieldValidationErrors.username = emailValid ? '' : ' is invalid';
        break;
      case 'password':
        passwordValid = value.length >= 6;
        fieldValidationErrors.password = passwordValid ? '': ' is too short';
        break;
      case 'submit':
        submit = (value == true) ? true : false;
        fieldValidationErrors.submit = submit ? '': ' failed due to connection issues';
        break;
      default:
        break;
    }
    this.setState({formErrors: fieldValidationErrors,
      emailValid: emailValid,
      passwordValid: passwordValid,
      submit: submit
    }, this.validateForm);
  }

  validateForm() {
    this.setState({formValid: this.state.emailValid && this.state.passwordValid});
  }

  handleUserInput (e) {
    const name = e.target.name;
    const value = e.target.value;
    this.setState(
      {[name]: value},
      () => { this.validateField(name, value) });
  }

  setCookie(res){
    var date = new Date();
    date.setTime(date.getTime() + (30 * 60 * 1000)); //30 minutes
    const cookies = new Cookies();
    cookies.set("giyur", "loggedInExp", { expires: date });
    cookies.set('userName', res.data.name);
    cookies.set('userRoles', res.data.roles);
  }
  submit(e){
    e.preventDefault()
    let {username: email, password: pwd} = this.state;
    let data = {'email':email,'pwd':pwd};
    const that = this;
    console.log(data, this.props);
//response.data = {email:"zach@zach.com",name:"zach",roles:"basic"}
that.props.dispatch({ type: 'LOGIN', name: 'zach', role: 'basic'});
return;
    axios.post(serviceBase + '/services/signin', data)
      .then(function (response) {
        console.log(response);
        if(response.status == 200){
          that.props.dispatch({ type: 'LOGIN', name: response.data.name, role: response.data.roles});
          that.setCookie(response);
        } else {
          console.log('login error', response);
          that.validateField('submit',false);
        }
      })
      .catch(function (error) {
        console.log('error loging in', error);
        that.props.dispatch({ type: 'LOGOUT', name: '', role: '' });
        that.validateField('submit',false);
        console.log(that.props);
      });

  }

  render() {
    console.log('after dispatch: ', this.props, this.state);
    const { isLoggedIn } = this.props;

    if (isLoggedIn) {
      console.log('in redirect');
      return <Redirect to={{
            pathname: "/upload",
            state: { isLoggedIn : this.props.isLoggedIn }
          }} />;
    }

    return (
        <div className="col-3">
          <h4>Sign in</h4>
          <form onSubmit={this.submit} id="Signin">
            <div className="form-group">
              <label htmlFor="uname" className="float-left">Username</label>
              <input type="text" className="form-control" placeholder='Username' value={this.state.username} name="username" onChange={this.handleUserInput.bind(this)} required/>
            </div>
            <div className="form-group">
              <label htmlFor="psw" className="float-left">Password</label>
              <input type="password" className="form-control" placeholder='Password' value={this.state.password} name="password" onChange={this.handleUserInput.bind(this)} required/>
            </div>
            <div className="panel panel-default">
             <FormErrors formErrors={this.state.formErrors} />
            </div>
            <button type="submit" className="btn btn-primary float-right" disabled={!this.state.formValid}>Sign in</button>
          </form>
        </div>
    );
  }
}

function mapStateToProps(state) {
  console.log('in mapStateToProps: ', state);
  return {
    isLoggedIn: state.login.isLoggedIn
  };
}

export default connect(mapStateToProps)(Signin);
