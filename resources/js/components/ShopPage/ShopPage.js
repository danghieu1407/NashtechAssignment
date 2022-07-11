import React from "react";
import "../../../css/app.css";
import MenuFilter from "./MenuFilter";
import CardBook from "../Card/CardBook";
import axios from "axios";
import Dropdown from "react-bootstrap/Dropdown";
import DropdownButton from "react-bootstrap/DropdownButton";
import Pagination from "react-js-pagination";
import imgnodata from "../../../assets/images/Nodata2.gif";

class ShopPageBody extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      current_page: 1,
      per_page: 5,
      total_page: 0,
      category: "",
      author: "",
      rating: "",
      sort: "",
      total: 0,
    };
  }
  componentDidMount() {
    this.onClickPerPage();
  }

  onClickPerPage = ({
    page = undefined,
    category = undefined,
    author = undefined,
    rating = undefined,
    sort = undefined,
    per_page = undefined,
  } = {}) => {
    let url = "http://localhost:8000/api/filterBy";
    let array = [];
    if (page) {
      array.push(`page=${page}`);
    }
    if (category) {
      array.push(`category_name=${category}`);
    }
    if (author) {
      array.push(`author_name=${author}`);
    }
    if (rating) {
      array.push(`rating_star=${rating}`);
    }

    if (sort) {
      array.push(`${sort}`);
    }
    if (per_page) {
      array.push(`per_page=${per_page}`);
    }

    for (let i = 0; i < array.length; i++) {
      if (i === 0) {
        url += "?" + array[i];
      } else {
        url += "&" + array[i];
      }
    }

    axios
      .get(url)
      .then((res) => {
        const data = res.data;
        this.setState({ data: data.data });
        this.setState({ current_page: data.current_page });
        this.setState({total: data.total});
      })
      .catch((error) => console.log(error));
  };
  getCategoryName = (category_name) => {
    let author;
    let rating;
    let per_page;
    let sort;

    if (this.state.author) {
      author = this.state.author;
    }
    if (this.state.rating) {
      rating = this.state.rating;
    }

    if (this.state.per_page) {
      per_page = this.state.per_page;
    }
    if (this.state.sort) {
      sort = this.state.sort;
    }


    this.onClickPerPage({
      category: category_name,
      author: author,
      rating: rating,
      per_page: per_page,
      sort: sort,
    });
    this.setState({ category: category_name });
  };
  getAuthorName = (author_name) => {
    let category;
    let rating;
    let per_page;
    let sort;

    if (this.state.category) {
      category = this.state.category;
    }
    if (this.state.rating) {
      rating = this.state.rating;
    }
    if (this.state.per_page) {
      per_page = this.state.per_page;
    }
    if (this.state.sort) {
      sort = this.state.sort;
    }
    
    this.onClickPerPage({
      author: author_name,
      category: category,
      rating: rating,
      per_page: per_page,
      sort: sort,

    });
    this.setState({ author: author_name });
  };
  getRatingReview = (rating) => {
    let category;
    let author;
    let per_page;
    let sort;
    if (this.state.category) {
      category = this.state.category;
    }
    if (this.state.author) {
      author = this.state.author;
    }
    if (this.state.per_page) {
      per_page = this.state.per_page;
    }
    if (this.state.sort) {
      sort = this.state.sort;
    }

    this.onClickPerPage({ rating: rating, category: category, author: author, per_page: per_page, sort: sort });
    this.setState({ rating: rating });
  };

  sortBy = (e) => {
    let category;
    let author;
    let rating;

    if (this.state.category) {
      category = this.state.category;
    }
    if (this.state.author) {
      author = this.state.author;
    }
    if (this.state.rating) {
      rating = this.state.rating;
    }

    this.onClickPerPage({
      sort: e,
      category: category,
      author: author,
      rating: rating,
    });
    this.setState({ sort: e });
  };
  per_page = (e) => {
    let category;
    let author;
    let rating;
    let sort;
    let page;

    if (this.state.category) {
      category = this.state.category;
    }
    if (this.state.author) {
      author = this.state.author;
    }
    if (this.state.rating) {
      rating = this.state.rating;
    }

    if (this.state.sort) {
      sort = this.state.sort;
    }
    if (this.state.page) {
      page = this.state.page;
    }

    this.onClickPerPage({
      per_page: e,
      category: category,
      author: author,
      rating: rating,
      sort: sort,
      page: page,
    });
    this.setState({ per_page: e });
  };
  page = (e) => {
    let category;
    let author;
    let rating;
    let sort;
    let per_page;

    if (this.state.category) {
      category = this.state.category;
    }
    if (this.state.author) {
      author = this.state.author;
    }
    if (this.state.rating) {
      rating = this.state.rating;
    }
    if (this.state.sort) {
      sort = this.state.sort;
    }
    if (this.state.per_page) {
      per_page = this.state.per_page;
    }
    this.onClickPerPage({
      page: e,
      category: category,
      author: author,
      rating: rating,
      sort: sort,
      per_page: per_page,
    });
    this.setState({ current_page: e });
  };

  render() {
    return (
      <div className="shop-container">
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <h2>BOOK</h2>
              <hr></hr>
            </div>
          </div>
          <div className="row">
            <div className="col-md-3">
              <MenuFilter
                getCategoryName={this.getCategoryName}
                getAuthorName={this.getAuthorName}
                getRatingReview={this.getRatingReview}
                category={this.state.category}
                author={this.state.author}
                rating={this.state.rating}
              />
            </div>
            <div className="col-md-9">
              <div className="row">
                <div className="col-12 d-flex justify-content-end mb-3">
                <Dropdown className="mr-3" onSelect={this.sortBy}>
                  <Dropdown.Toggle variant="success" id="dropdown-basic">
                    {this.state.sort
                      ? this.state.sort.split("_").join(" ")
                      : "Sort by on Sale"}
                  </Dropdown.Toggle>

                  <Dropdown.Menu>
                    <Dropdown.Item eventKey="sort_by_on_sale">
                      Sort by on sale
                    </Dropdown.Item>
                    <Dropdown.Item eventKey="sort_by_popularity">
                      Sort by popularity
                    </Dropdown.Item>
                    <Dropdown.Item eventKey="sort_by_price_asc">
                      Sort by price: low to high
                    </Dropdown.Item>
                    <Dropdown.Item eventKey="sort_by_price_desc">
                      Sort by price: high to low
                    </Dropdown.Item>
                  </Dropdown.Menu>
                </Dropdown>
                <Dropdown onSelect={this.per_page}>
                  <Dropdown.Toggle variant="success" id="dropdown-basic">
                    Show {this.state.per_page ? this.state.per_page : "5"}
                  </Dropdown.Toggle>

                  <Dropdown.Menu>
                    <Dropdown.Item eventKey="5">Show 5</Dropdown.Item>
                    <Dropdown.Item eventKey="15">Show 15</Dropdown.Item>
                    <Dropdown.Item eventKey="20">Show 20</Dropdown.Item>
                    <Dropdown.Item eventKey="25">Show 25</Dropdown.Item>
                  </Dropdown.Menu>
                </Dropdown>
                </div>
              </div>
             
              <div className="row">
                {this.state.data.length > 0 ? this.state.data.length > 0 &&
                  this.state.data.map((item, idx) => (
                    <div className="col-md-3">
                      <CardBook
                        id={item.id}
                        author={item.author_name}
                        title={item.book_title}
                        img={item.book_cover_photo}
                        original_price={item.book_price}
                        final_price={item.final_price}
                        discount_price={item.discount_price}
                      />
                    </div>
                  )) : (<img src={imgnodata}/>)}
              </div>
              <div className="row">
                <div className="col-md-12 pagination"></div>
                {this.state.data.length > 0 ? ( <Pagination
                  activePage={this.state.current_page}
                  itemsCountPerPage={this.state.per_page}
                  totalItemsCount={this.state.total}
                  pageRangeDisplayed={3}
                  onChange={this.page.bind(this)}
                  prevPageText="Previous"
                  nextPageText="Next"
                  itemClass="page-item"
                  linkClass="page-link"
                />) : '' }
               
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
export default ShopPageBody;
