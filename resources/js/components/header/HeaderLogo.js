import React from "react";
import logo32 from "../../../assets/images/logo32.png";
import "../../../css/app.css";
import { Navbar } from 'react-bootstrap';
import HeaderMenu from "./HeaderMenu";

class HeaderLogo extends React.Component {
  render() {
    return (
      <div>
      <Navbar bg="" variant="dark" id="navbar" >
      <Navbar.Brand href="#home" id="logoName">
      <img id="logo32"  src={logo32 } width="auto" height= "40px" alt=""/>
      BOOKWORM
      </Navbar.Brand>
      <HeaderMenu/>

      </Navbar>
      </div>
    
    );
  }
}

export default HeaderLogo;
