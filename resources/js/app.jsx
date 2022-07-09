import React from 'react';
import ReactDOM from 'react-dom';
import HomePage from './pages/HomePage';
import ShopPage from './pages/ShopPage';
import ProductPage from './pages/ProductPage';
import About from './pages/About';
import CartPage from './pages/CartPage';

import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';


ReactDOM.render(
    <Router>
        <Routes>
            <Route path="/" element={<HomePage />} />
            <Route path="/ShopPage" element={<ShopPage />} />
            <Route path="/ProductPage/:id" element={<ProductPage />} />
            <Route path="/About" element={<About />} />
            <Route path="/Cart" element={<CartPage />} />

            

    
        </Routes>
    </Router>,

  document.getElementById('root')
);