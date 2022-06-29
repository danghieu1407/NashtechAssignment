import React from "react";
import "../../../css/app.css";
import axios from "axios";

class Author extends React.Component {
  state = {
    data: []
  }

  componentDidMount() {
    axios.get(`http://localhost:8000/api/getAllAuthorName`)
      .then(res => {
        const data = res.data;
        this.setState({ data });
        console.log(data);
      })
      .catch(error => console.log(error));
  }
  getAuthorName = (author_name) => {
    this.props.getAuthorName(author_name);
  }

  render() {
    return (
      <>
        <ul className="sub-category-tabs">
          <li><b>Author</b></li>
          {this.state.data.map((item, idx) => (
            <li className="item" onClick={()=> this.getAuthorName(item.author_name)}>{item.author_name}</li>
          ))}
        </ul>
      </>
    );
  }
}

export default Author;