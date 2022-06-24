import React from "react";
import "../../../css/app.css";
import {Card} from "react-bootstrap";
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

class CardBook extends React.Component {
  render() {
    return (
      <>
          <Card className="card-container h-100 w-100">
            <Card.Img className="card-img" variant="top" src={obj[this.props.img]} />
            <Card.Body>
              <Card.Title className="card-title">{this.props.title}</Card.Title>
              <Card.Text>{this.props.author}</Card.Text>

            </Card.Body>
            <Card.Footer>
              <Card.Title className="card-price"><del className="del_original_price">{this.props.discount_price !==null ? this.props.original_price : 'ad' }$</del><span> {this.props.discount_price}$</span></Card.Title>
            </Card.Footer>
          </Card>
        </>
        );
    }
}
export default CardBook;


