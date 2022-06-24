import React from "react";
import "../../../css/app.css";
import { Navbar } from 'react-bootstrap';
import logo64 from "../../../assets/images/logo64.png";


class Footer extends React.Component {
  render() {
    return (
      <div className="footer">
      <Navbar bg="" variant="dark" id="navbar" >
      <Navbar.Brand href="#home" id="logoName">
      <img id="logo32"  src={logo64 } width="auto" height= "64px" alt=""/>
    
      <div className="info-footer">
      <h5>BOOKWORM</h5>
         <p>Etown 1, Level 3,, 364 Cong Hoa Street, Tan Binh District,, Ho Chi Minh City, Thành phố Hồ Chí Minh 736839</p>
          <p>028 3810 6200 </p>
      </div>
      
      </Navbar.Brand>
    `

      </Navbar>
      </div>
    );
  }
}
export default Footer;