import React from "react";
import Dropdown from "react-bootstrap/Dropdown";
import axios from "axios";
import nodata from "../../../assets/images/Nodata.gif";
import moment from "moment";
import Pagination from "react-js-pagination";
import PostReview from "./PostReview";

class BookReview extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      review: [],
      dataCountStar: [],
      current_page: 1,
      per_page: 5,
      total_page: 0,
      sort: "",
      id: [],
      total: 0,
    };
  }
  componentDidMount() {
    console.log(window.location.pathname);
    let location = window.location.pathname.split("/");
    let id = location[location.length - 1];
    this.setState({ id: id });
    this.getProductById(id);
    this.getReviewById({ id: id });
    this.countReviewStar(id);
  }
  getProductById = (id) => {
    axios
      .get(`http://localhost:8000/api/getBookByIDCustomerReview?id=${id}`)
      .then((res) => {
        const data = res.data;
     ;

        this.setState({ data: data });
      })
      .catch((error) => console.log(error));
  };
  countReviewStar = (id) => {
    axios

      .get(`http://localhost:8000/api/countReviewStar?id=${id}`)
      .then((res) => {
        const data = res.data;
        this.setState({ dataCountStar: data });
        console.log(this.state.dataCountStar);
      })
      .catch((error) => console.log(error));
  };

  getReviewById = ({
    id = undefined,
    sort = undefined,
    per_page = undefined,
    rating_star = undefined,
    page = undefined,
  } = {}) => {
    let url = `http://localhost:8000/api/getBookReviewByID?id=${id}`;
    let array = [];
    if (page) {
      array.push(`page=${page}`);
    }
    if (rating_star) {
      array.push(`${rating_star}`);
    }
    if (per_page) {
      array.push(`per_page=${per_page}`);
    }
    if (sort) {
      array.push(`${sort}`);
    }
    for (let i = 0; i < array.length; i++) {
      url += "&" + array[i];
    }
    console.log(array);
    axios
      .get(url)
      
      .then((res) => {
        const review = res.data;
        console.log(review);

        this.setState({ review: review.data });
        this.setState({ id: id });
        this.setState({ current_page: review.current_page });
        this.setState({ total: review.total });

      })
      .catch((error) => console.log(error));
  };

  sortBy = (e) => {
    let location = window.location.pathname.split("/");
    let id = location[location.length - 1];
    this.getReviewById({
      id: id,
      sort: e,
    });
    this.setState({ sort: e });
  };
  per_page = (e) => {
    let location = window.location.pathname.split("/");
    let id = location[location.length - 1];
    let sort;
    let page;

    if (this.state.sort) {
      sort = this.state.sort;
    }
    if (this.state.page) {
      page = this.state.page;
    }
    this.getReviewById({
      id: id,
      per_page: e,
      sort: sort,
    });
    this.setState({ per_page: e });
  };
  page = (e) => {
    let sort;
    let per_page;
    let location = window.location.pathname.split("/");
    let id = location[location.length - 1];

    if (this.state.sort) {
      sort = this.state.sort;
    }
    if (this.state.per_page) {
      per_page = this.state.per_page;
    }
    this.getReviewById({
      id: id,
      page: e,
      sort: sort,
      per_page: per_page,
    });
    this.setState({ current_page: e });
  };
  render() {
    return (
      <>
<div className="row">
    <div className="col-md-12">
    </div>
      <div className="col-md-8">

        {this.state.dataCountStar.length === 0 ? (
            <div className="col-md-12 px-0">
              <div className="custumer-review">
                <p className="header">
                  Custumer Review
                  <a className="starfilterby">(Filter By 5 Star)</a>
                </p>
              </div>
              <p className="rating_star">0.0 Star</p>
              <span>
                <a>(0)</a> <a className="star-count">5 Star (0) </a>
                <span> | </span> <a className="star-count">4 Star (0) </a>
                <span> | </span> <a className="star-count">3 Star (0) </a>
                <span> | </span> <a className="star-count">2 Star (0) </a>
                <span> | </span> <a className="star-count">1 Star (0) </a>
              </span>
            </div>
        ) : (
          this.state.dataCountStar.map((item, index) => (
            <>
              <div className="col-md-12 px-0">
   
                  <div className="custumer-review">
                    <p className="header">
                      Custumer Review
                      <a className="starfilterby">(Filter By 5 Star)</a>
                    </p>
                  </div>
                  <p className="rating_star">{item.rating} Star</p>
                  <span>
                    <a
                      onClick={() =>
                        this.getReviewById({
                          id: this.state.id,
                          rating_star: "total",
                        })
                      }
                    >
                      ({item.count_review})
                    </a>{" "}
                    <a
                      className="star-count"
                      onClick={() =>
                        this.getReviewById({
                          id: this.state.id,
                          rating_star: "five_star",
                        })
                      }
                    >
                      5 Star ({item["5_Star"]}){" "}
                    </a>
                    <span> | </span>{" "}
                    <a
                      className="star-count"
                      onClick={() =>
                        this.getReviewById({
                          id: this.state.id,
                          rating_star: "four_star",
                        })
                      }
                    >
                      4 Star ({item["4_Star"]}){" "}
                    </a>
                    <span> | </span>{" "}
                    <a
                      className="star-count"
                      onClick={() =>
                        this.getReviewById({
                          id: this.state.id,
                          rating_star: "three_star",
                        })
                      }
                    >
                      3 Star ({item["3_Star"]}){" "}
                    </a>
                    <span> | </span>{" "}
                    <a
                      className="star-count"
                      onClick={() =>
                        this.getReviewById({
                          id: this.state.id,
                          rating_star: "two_star",
                        })
                      }
                    >
                      2 Star ({item["2_Star"]}){" "}
                    </a>
                    <span> | </span>{" "}
                    <a
                      className="star-count"
                      onClick={() =>
                        this.getReviewById({
                          id: this.state.id,
                          rating_star: "one_star",
                        })
                      }
                    >
                      1 Star ({item["1_Star"]}){" "}
                    </a>
                  </span>

              </div>
              
            </>
          ))
        )}
     

        {this.state.dataCountStar.length === 0 &&
        this.state.review.length === 0 ? (
          ""
        ) : (
          <div className="col-md-12 px-0 showing-btn">
            <div className="col-md-3 px-0">
              <p className="number-showing"> Showing 1 - 12 of 3134 reviews</p>
            </div>
            <div className="col-md-9 px-0 button-sort-review ">
              <Dropdown onSelect={this.sortBy}>
                <Dropdown.Toggle variant="success" id="dropdown-basic">
                  Sort by newest to oldest
                </Dropdown.Toggle>

                <Dropdown.Menu>
                  <Dropdown.Item eventKey="sort_by_date_desc">
                    Sort by newest to oldest
                  </Dropdown.Item>
                  <Dropdown.Item eventKey="sort_by_date_asc">
                    Sort by oldest to newest
                  </Dropdown.Item>
                </Dropdown.Menu>
              </Dropdown>

              <Dropdown onSelect={this.per_page} className="btn-showing">
                <Dropdown.Toggle variant="success" id="dropdown-basic">
                  Show {this.state.per_page}
                </Dropdown.Toggle>

                <Dropdown.Menu>
                  <Dropdown.Item eventKey="5">Show 5 </Dropdown.Item>
                  <Dropdown.Item eventKey="15">Show 15</Dropdown.Item>
                  <Dropdown.Item eventKey="20">Show 20</Dropdown.Item>
                  <Dropdown.Item eventKey="25">Show 25</Dropdown.Item>
                </Dropdown.Menu>
              </Dropdown>
            </div>
          </div>
        )}
        {this.state.review.length === 0 ? (
          <img src={nodata} alt="nodata" className="nodata-img" />
          
        ) : (
        this.state.review.map((item, index) => (
          <>
            <div className="col-md-12 px-0">
                <hr />
                <h4>
                  {item.review_title} |
                  <a className="star">{item.rating_star} Star</a>
                </h4>
                <p className="content-review">{item.review_details}</p>
                <p className="date-review">{moment(item.review_date).format("MMM Do YY")}</p>
            </div>
          </>
        )))}
        </div>
        <div className="col-md-4 postreview">
        <PostReview/>
        </div>
      </div>
     
        {this.state.dataCountStar.length === 0 ? (
          ""
        ) : (
   
          <Pagination
                  activePage={this.state.current_page}
                  itemsCountPerPage={this.state.per_page}
                  totalItemsCount={this.state.total}
                  pageRangeDisplayed={3}
                  onChange={this.page.bind(this)}
                  prevPageText="Previous"
                  nextPageText="Next"
                  itemClass="page-item"
                  linkClass="page-link"
                />
        )}
      </>
      
      
    );
    
    
  }
}
export default BookReview;
