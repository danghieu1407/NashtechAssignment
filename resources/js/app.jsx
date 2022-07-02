import React from 'react';
import ReactDOM from 'react-dom';
import HomePage from './pages/HomePage';
import ShopPage from './pages/ShopPage';
import ProductPage from './pages/ProductPage';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';


ReactDOM.render(
    <Router>
        <Routes>
            <Route path="/" element={<HomePage />} />
            <Route path="/ShopPage" element={<ShopPage />} />
            <Route path="/ProductPage" element={<ProductPage />} />


    
        </Routes>
    </Router>,

  document.getElementById('root')
);