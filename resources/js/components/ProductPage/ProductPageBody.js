import React from "react";
import "../../../css/app.css";
import BookImg from "../../../assets/images/defaultImg.png";
import {Card,Button} from "react-bootstrap";
import { QuantityPicker } from 'react-qty-picker';
import BookReview from "./BookReview";

class ProductPageBody extends React.Component {
  render() {
    return (
      <div className="container book_info_container">
        <div className="row">
          <h3>Category_name</h3>

          <div className="col-md-12">
            <hr></hr>

            <div className="col-md-12 bookinfo-price">
              <div className="col-md-8 book_info ">
                <div className="img-author">
                  <img src={BookImg}></img>
                  <p className="author-name-product">
                    by Author:<b> Anna Books</b>
                  </p>
                </div>

                <div className="book-description">
                  <h5>Book Title</h5>

                  <p>
                    Book description Lorem ipsum dolor sit amet, consectetur
                    adipiscing elit. Donec euismod, nisl eget consectetur
                    consectetur, nisi nisl aliquet nisi, euismod eget nisl. Book
                    description Lorem ipsum dolor sit amet, consectetur
                    adipiscing elit. Donec euismod, nisl eget consectetur
                    consectetur, nisi nisl aliquet nisi, euismod eget nisl. Book
                    description Lorem ipsum dolor sit amet, consectetur
                    adipiscing elit. Donec euismod, nisl eget consectetur
                    consectetur, nisi nisl aliquet nisi, euismod eget nisl.
                  </p>
                </div>
              </div>
              <div className="col-md-4 placeOrder">
                <Card style={{ width: "18rem" }}>
                  <Card.Footer>
                    <Card.Title className="card-price">
                       <p> <del className="del_original_price">
                            40$
                        </del>
                        <span className="product-final-price">
                            20$
                        </span>
                        </p>
                       
                    </Card.Title>
                  </Card.Footer>
                  <Card.Body>
                    <p>Quantity</p>
                  <QuantityPicker min={1}  max={8}  width='8rem'/>
                  <Button className="btn-add-to-cart">Add to cart</Button>
                  </Card.Body>
                </Card>
              </div>
            </div>
            <BookReview/>

          </div>
        </div>
      </div>
    );
  }
}
export default ProductPageBody;
