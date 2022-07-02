import React from "react";
import Dropdown from "react-bootstrap/Dropdown";

class BookReview extends React.Component {
  render() {
    return (
      <>
        <div className="col-md-12">
          <div className="col-md-12">
            <div className="custumer-review">
              <p className="header">
                Custumer Review{" "}
                <a className="starfilterby">(Filter By 5 Star)</a>
              </p>
            </div>
            <p className="rating_star">4.6 Star</p>
            <span>
              <a>(3.134)</a> <a className="star-count">5 Star (200) </a>
              <span> | </span> <a className="star-count">4 Star (100) </a>
              <span> | </span> <a className="star-count">3 Star (20) </a>
              <span> | </span> <a className="star-count">2 Star (10) </a>
              <span> | </span> <a className="star-count">1 Star (5) </a>
            </span>
          </div>
        </div>

        <div className="col-md-12 showing-btn">
          <div className="col-md-3">
            <p className="number-showing"> Showing 1 - 12 of 3134 reviews</p>
          </div>
          <div className="col-md-9 button-sort-review ">
            <Dropdown>
              <Dropdown.Toggle variant="success" id="dropdown-basic">
                Sort by on sale
              </Dropdown.Toggle>

              <Dropdown.Menu>
                <Dropdown.Item href="#/action-1">Action</Dropdown.Item>
                <Dropdown.Item href="#/action-2">Another action</Dropdown.Item>
                <Dropdown.Item href="#/action-3">Something else</Dropdown.Item>
              </Dropdown.Menu>
            </Dropdown>

            <Dropdown className="btn-showing">
              <Dropdown.Toggle variant="success" id="dropdown-basic">
                Show 20
              </Dropdown.Toggle>

              <Dropdown.Menu>
                <Dropdown.Item href="#/action-1">Action</Dropdown.Item>
                <Dropdown.Item href="#/action-2">Another action</Dropdown.Item>
                <Dropdown.Item href="#/action-3">Something else</Dropdown.Item>
              </Dropdown.Menu>
            </Dropdown>
          </div>
        </div>

        <div className="col-md-12">
          <div className="col-md-8">
            <hr />
            <h4>
              Review title |<a className="star"> 5 Star</a>
            </h4>
            <p className="content-review">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque euismod, nisl eget consectetur sagittis, nisl nisi
              consectetur nisl, euismod nisl nisi euismod nisl.
            </p>
            <p className="date-review">Month - date - year</p>
          </div>
          <div className="col-md-8">
            <hr />
            <h4>
              Amazing Story I Love It |<a className="star"> 5 Star</a>
            </h4>
            <p className="content-review">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque euismod, nisl eget consectetur sagittis, nisl nisi
              consectetur nisl, euismod nisl nisi euismod nisl.
            </p>
            <p className="date-review">Month - date - year</p>
          </div>
          <div className="col-md-8">
            <hr />
            <h4>
              Amazing Story I Love It |<a className="star"> 5 Star</a>
            </h4>
            <p className="content-review">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque euismod, nisl eget consectetur sagittis, nisl nisi
              consectetur nisl, euismod nisl nisi euismod nisl.
            </p>
            <p className="date-review">Month - date - year</p>
          </div>
          <div className="col-md-8">
            <hr />
            <h4>
              Amazing Story I Love It |<a className="star"> 5 Star</a>
            </h4>
            <p className="content-review">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque euismod, nisl eget consectetur sagittis, nisl nisi
              consectetur nisl, euismod nisl nisi euismod nisl.
            </p>
            <p className="date-review">Month - date - year</p>
          </div>
          <div className="col-md-8">
            <hr />
            <h4>
              Amazing Story I Love It |<a className="star"> 5 Star</a>
            </h4>
            <p className="content-review">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Pellentesque euismod, nisl eget consectetur sagittis, nisl nisi
              consectetur nisl, euismod nisl nisi euismod nisl.
            </p>
            <p className="date-review">Month - date - year</p>
          </div>

          <ul className="pagination">
            <li className="page-item">
              <a
                className="page-link"
              >
                Previous
              </a>
            </li>
            <li className="page-item">
              <a className="page-link">
                1
              </a>
            </li>
            <li className="page-item">
              <a className="page-link" >
                2
              </a>
            </li>
            <li className="page-item">
              <a className="page-link" >
                3
              </a>
            </li>

            <li className="page-item">
              <a
                className="page-link">
                Next
              </a>
            </li>
          </ul>
        </div>
      </>
    );
  }
}
export default BookReview;
