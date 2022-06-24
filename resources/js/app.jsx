import React from 'react';
import ReactDOM from 'react-dom';
import Header from './components/Header/Header';
import HomePage from './components/HomePage/HomePage';
import Footer from './components/Footer/Footer';

import 'bootstrap/dist/css/bootstrap.min.css';

ReactDOM.render(
  <div>
  <Header />
  <HomePage />
  <Footer />

  </div>,

  document.getElementById('root')
);