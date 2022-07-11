import React from "react";
import "../../../css/app.css";
import HeaderLogo from "./HeaderLogo";
import { Link } from "react-router-dom";
class Header extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    window.addEventListener("storage",(e) => {
      console.log("Change", e);
   });

    return (
      <div>
        <Link className="link-header" to={"/"}><HeaderLogo /></Link>
     
      </div>
    );
  }
}
export default Header;