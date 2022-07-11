import React from "react";
import "../../../css/app.css";
import { Card, Button } from "react-bootstrap";
import { QuantityPicker } from "react-qty-picker";
import BookReview from "./BookReview";
import PostReview from "./PostReview";
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
      cart: [],
      quantity: 0,
      total: 0,

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

 // add to cart
  addToCart = () => {
 
    let cart = this.state.cart;
    let data = this.state.data;
    let book = data[0];
    
    let quantity = this.state.quantity;
    let bookInCart = cart.find((item) => item.id);
    if (bookInCart ) {
      bookInCart.quantity += quantity;
    }
    else {
      book.quantity = quantity;
      cart.push(book);
    }
    localStorage.setItem("cart", JSON.stringify(cart));

 
    //total price
    let total = 0;
    cart.forEach((item) => {
      total += item.quantity * item.price;
    }
    );
    this.setState({ cart: cart });
    this.setState({ total: total });


  }



  render() {
    return (
      <div className="container book_info_container">
             {
              this.state.data.length > 0 &&  <h3>{this.state.data[0].category_name}</h3>
             }
        <div className="row mb-3">
     
          {this.state.data.map((item,index) => (
            <>
            <div className="col-md-8 book_info">
                <div className="img-author">
                  <img src={obj[item.book_cover_photo]} width="320" height="420"></img>
                  <p className="author-name-product">
                    by Author:<b> {item.author_name}</b>
                  </p>
                </div>

                <div className="book-description ml-3">
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
                    <QuantityPicker min={1} max={8} width="8rem" input 
                    onChange={(e) => {
                      this.setState({ quantity: e });
                    }
                    }
                     />
                    <Button className="btn-add-to-cart" onClick={()=>this.addToCart()}>Add to cart</Button>
                  </Card.Body>
                </Card>
              </div>
            </>
          ))}
          
        </div>
        <BookReview />
      </div>
    )
  }
  }
  
export default ProductPageBody;
