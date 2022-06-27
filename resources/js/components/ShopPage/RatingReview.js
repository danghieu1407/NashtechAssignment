import React from "react";
import "../../../css/app.css";
import axios from "axios";

class RatingReview extends React.Component {
  state = {
    data: []
  }

  componentDidMount() {
    axios.get(`http://localhost:8000/api/getRatingReview`)
      .then(res => {
        const data = res.data;
        this.setState({ data });
        console.log(data);
      })
      .catch(error => console.log(error));
  }
  render() {
    return (
      <>
        <ul className="sub-category-tabs">
          <li><b>Rating Review</b></li>
          {this.state.data.map((item, idx) => (
            <li className="item">{item.rating_star} Star</li>
          ))}
        </ul>
      </>
    );
  }
}

    export default RatingReview;