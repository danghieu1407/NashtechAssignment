import React from 'react';
import ReactDOM from 'react-dom';
import Header from './components/Header/Header';
import HomePage from './components/HomePage/OnSale';

import 'bootstrap/dist/css/bootstrap.min.css';

ReactDOM.render(
  <div>
  <Header />
  <HomePage />

  </div>,

  document.getElementById('root')
);