import React from "react";
import "../../../css/app.css";
import HeaderLogo from "./HeaderLogo";

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
        <HeaderLogo />
     
      </div>
    );
  }
}
export default Header;