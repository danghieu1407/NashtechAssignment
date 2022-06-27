import React from "react";
import "../../../css/app.css";
import axios from "axios";



class CategoryName extends React.Component {
    state = {
        data: []
    }

    componentDidMount() {
        axios.get(`http://localhost:8000/api/getAllCategoryName`)
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
                <li><b>CategoryName</b></li>
                 {this.state.data.map((item, idx) => (
                <li className="item">{item.category_name}</li>
                ))}
            </ul>
                    
        </>
    );
  }
}
  export default CategoryName;
