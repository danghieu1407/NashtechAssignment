import React from "react";
import "../../../css/app.css";
import { Navbar } from 'react-bootstrap';

class HeaderMenu extends React.Component {
  render() {
    return (

        <Navbar className="nav-link" id="nav-link">
            <Navbar.Brand href="#home">Home</Navbar.Brand>
            <Navbar.Brand href="#Shop">Shop</Navbar.Brand>
            <Navbar.Brand href="#About">About</Navbar.Brand>
            <Navbar.Brand href="#Cart">Cart</Navbar.Brand>
            <Navbar.Brand href="#Sign in">Sign In</Navbar.Brand>

          </Navbar>

    );
  }
}

export default HeaderMenu;