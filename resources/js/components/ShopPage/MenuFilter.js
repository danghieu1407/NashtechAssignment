import React from "react";
import "../../../css/app.css";
import CategoryName from "./CategoryName";
import Author from "./Author";
import RatingReview from "./RatingReview";


class MenuFilter extends React.Component {
  render() {
    return (
      <div className="shop-container">

      <ul id="category-tabs">
        
      <li> 
            <b id='filterby'>Filter By</b>

              <CategoryName />
              <Author />  
              <RatingReview/>

      </li>
  </ul>
  </div>
    );
  }
}

export default MenuFilter;