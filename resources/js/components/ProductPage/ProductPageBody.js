import React from "react";
import "../../../css/app.css";
import { Card, Button } from "react-bootstrap";
import { QuantityPicker } from "react-qty-picker";
import BookReview from "./BookReview";
import axios from "axios";
import Book1 from "../../../assets/bookcover/book1.jpg";
import Book2 from "../../../assets/bookcover/book2.jpg";
import Book3 from "../../../assets/bookcover/book3.jpg";
import Book4 from "../../../assets/bookcover/book4.jpg";
import Book5 from "../../../assets/bookcover/book5.jpg";
import Book6 from "../../../assets/bookcover/book6.jpg";
import Book7 from "../../../assets/bookcover/book7.jpg";
import Book8 from "../../../assets/bookcover/book8.jpg";
import Book9 from "../../../assets/bookcover/book9.jpg";
import Book10 from "../../../assets/bookcover/book10.jpg";
import BookDefault from "../../../assets/images/defaultImg.png";
const obj = {
  book1: Book1
  , book2: Book2
  , book3: Book3
  , book4: Book4
  , book5: Book5
  , book6: Book6
  , book7: Book7
  , book8: Book8
  , book9: Book9
  , book10: Book10
  , null: BookDefault
}

class ProductPageBody extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
    };
  }
  componentDidMount() {
    console.log(window.location.pathname);
    let location = window.location.pathname.split("/");
    let id = location[location.length - 1];
    this.getProductById(id);
  }
  getProductById = (id) => {
    axios
      .get(`http://localhost:8000/api/getBookByIDCustomerReview?id=${id}`)
      .then((res) => {
        const data = res.data;
        this.setState({ data: data });
      })
      .catch((error) => console.log(error));
  };

  render() {
    return (
      <div className="container book_info_container">
        <div className="row">
     
          {this.state.data.map((item,index) => (
            <>
          <h3>{item.category_name}</h3>

            <div className="col-md-12">
            <hr></hr>

            <div className="col-md-12 bookinfo-price">
              <div className="col-md-8 book_info ">
                <div className="img-author">
                  <img src={obj[item.book_cover_photo]}></img>
                  <p className="author-name-product">
                    by Author:<b> {item.author_name}</b>
                  </p>
                </div>

                <div className="book-description">
                  <h5>{item.book_title}</h5>

                  <p>
                    {item.book_summary}
                  </p>
                </div>
              </div>
              <div className="col-md-4 placeOrder">
                <Card style={{ width: "18rem" }}>
                  <Card.Footer>
                    <Card.Title className="card-price">
                      <p>
                        {" "}
                        <del className="del_original_price">{item.book_price}$</del>
                        <span className="product-final-price">{item.final_price}$</span>
                      </p>
                    </Card.Title>
                  </Card.Footer>
                  <Card.Body>
                    <p>Quantity</p>
                    <QuantityPicker min={1} max={8} width="8rem" input />
                    <Button className="btn-add-to-cart">Add to cart</Button>
                  </Card.Body>
                </Card>
              </div>
            </div>
            </div>
            <BookReview />
            </>
          ))}
          
        </div>
      </div>
    )
  }
  }
  
export default ProductPageBody;
