import React from 'react'
import Register from '../components/login/register'
import Signin from '../components/login/signin'

const Home = () => (
  <div>
    <h1>Welcome to Giyur</h1>
    <div className="d-flex justify-content-center">
      <Signin />
      <div className="col-1 align-self-center">or</div>
      <Register />
    </div>
  </div>
)

export default Home
