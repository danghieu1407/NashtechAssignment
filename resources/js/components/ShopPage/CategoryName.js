import React from "react";
import "../../../css/app.css";
import axios from "axios";



class CategoryName extends React.Component {
    state = {
        data: [],
        current_page : 1,
        per_page : 5,
        total_page : 0,
        category_name: undefined,
        author_name: undefined,
        rating_review: undefined,
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
      getCategory = (category_name) => {
        this.props.getCategoryName(category_name);
      }
  render() {
    return (
        <>
         <ul className="sub-category-tabs">
                <li><b>CategoryName</b></li>
                 {this.state.data.map((item, idx) => (
                <li className={`item ${this.props.current_category === item.category_name ? 'filter_active' : ''}`} onClick={()=>this.getCategory(item.category_name)}>{item.category_name}</li>
                ))}
            </ul>
                    
        </>
    );
  }
}
  export default CategoryName;
