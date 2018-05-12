import React from 'react'
import Register from '../components/login/register'
import Signin from '../components/login/signin'

const Home = () => (
  <div>
    <div id="heroBox" className="d-flex justify-content-center"><h1 className="col-4 align-self-center">Giyur: your DATA VISUALIZATION</h1></div>
    <div className="d-flex justify-content-center">
      <Signin />
      <Register />
    </div>
  </div>
)

export default Home
