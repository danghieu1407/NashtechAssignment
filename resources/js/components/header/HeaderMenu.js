import React from "react";
import "../../../css/app.css";
import { Navbar } from 'react-bootstrap';
import { Link } from 'react-router-dom'

class HeaderMenu extends React.Component {
  render() {
    return (

        <Navbar className="nav-link" id="nav-link">
            <Navbar.Brand><Link to='/'> Home </Link></Navbar.Brand>
           <Navbar.Brand ><Link to='/ShopPage'> Shop </Link></Navbar.Brand>
            <Navbar.Brand href="#About">About</Navbar.Brand>
            <Navbar.Brand href="#Cart">Cart</Navbar.Brand>
            <Navbar.Brand href="#Sign in">Sign In</Navbar.Brand>

          </Navbar>

    );
  }
}

export default HeaderMenu;