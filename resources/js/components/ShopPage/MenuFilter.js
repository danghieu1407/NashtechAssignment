import React from "react";
import "../../../css/app.css";
import CategoryName from "./CategoryName";
import Author from "./Author";
import RatingReview from "./RatingReview";

class MenuFilter extends React.Component {
  getCategoryName = (category_name) => {
    this.props.getCategoryName(category_name);
  };
  getAuthorName = (author_name) => {
    this.props.getAuthorName(author_name);
  };
  getRatingReview = (rating_review) => {
    this.props.getRatingReview(rating_review);
  }
  render() {
    return (
      <div className="shop-container">
        <ul id="category-tabs">
          <li>
            <b id="filterby">Filter By</b>

            <CategoryName getCategoryName={this.getCategoryName} />
            <Author getAuthorName={this.getAuthorName} />
            <RatingReview getRatingReview={this.getRatingReview} />
          </li>
        </ul>
      </div>
    );
  }
}

export default MenuFilter;
