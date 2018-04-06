import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore } from 'redux';
import { BrowserRouter as Router } from 'react-router-dom';
import Reducer from './js/reducers';
import App from './js/index';
import 'bootstrap';
import './css/app.scss';

const store = createStore(Reducer);
const root = document.getElementById('app');

ReactDOM.render(
  (<Provider store={store}>
    <Router basename="giyur/dist/">
      <App />
    </Router>
  </Provider>
), root);

module.hot.accept();
