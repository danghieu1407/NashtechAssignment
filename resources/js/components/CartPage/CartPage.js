import React from "react";
import { Table } from "react-bootstrap";
import Book1 from "../../../../public/images/book1.jpg";
import { QuantityPicker } from "react-qty-picker";
import { Card, Button } from "react-bootstrap";

import "../../../css/app.css";
class CartPage extends React.Component {
  render() {
    return (
      <div className="container cartpage">
        <h3>Your cart : 3</h3>
        <hr />
        <div className="row">
          <div className="col-md-8">
            <Table className="table-cart" bordered hover>
              <thead>
                <tr>
                  <th>Product </th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    {" "}
                    <img src={Book1} width="150" height="200" />
                  </td>
                  <td>
                    <span>$29..99</span> <br /> <del>$49.99</del>
                  </td>
                  <td>
                    <QuantityPicker smooth />
                  </td>
                  <td>
                    <b>59.99$</b>
                  </td>
                </tr>
              </tbody>
            </Table>
  
            
          </div>
          <div className="col-md-4">
          <Card style={{ width: "18rem" }}>
                  <Card.Header>
                    <Card.Title>
                      <p  className="title-cart">
                        {" "}
              
                        <span>Cart total</span>
                      </p>
                    </Card.Title>
                  </Card.Header>
                  <Card.Body>
                      <h2 className="cart-price"> 55.99$ </h2>
                       <div className="mb-3  text-post-review" >
                    <Button className="btn-add-to-cart" >Place order</Button>
                    </div>
                  </Card.Body>
                </Card>
          </div>
        </div>
      </div>
    );
  }
}
export default CartPage;
