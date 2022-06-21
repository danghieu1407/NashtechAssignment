import React from "react";
import "../../../css/app.css";
import {Card} from "react-bootstrap";
import bookimg from "../../../assets/bookcover/book1.jpg";


class CardBook extends React.Component {
  render() {
    return (
      <>
          <Card className="h-100 w-100">
            <Card.Img variant="top" src={bookimg} />
            <Card.Body>
              <Card.Title>Book Title</Card.Title>
              <Card.Text>Book Author</Card.Text>

            </Card.Body>
            <Card.Body>
              <Card.Title className="card-price"><del>original</del><span> Price</span></Card.Title>
            </Card.Body>
          </Card>
        </>
        );
    }
}
export default CardBook;