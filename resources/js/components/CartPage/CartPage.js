import React from "react";
import { Table } from "react-bootstrap";
import  Book1 from "../../../../public/images/book1.jpg";
import { QuantityPicker } from "react-qty-picker";
import "../../../css/app.css";
class CartPage extends React.Component {
  render() {
    return (
      <div className="container">
        <h3>Your cart : 3</h3>
        <hr />
        <div className="row">
          <div className="col-md-8">
            <Table bordered hover>
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
                  <td> <img src={Book1}/></td>
                  <td><span>$29..99</span> <br/> <del>$49.99</del></td>
                  <td><QuantityPicker smooth/></td>
                  <td><b>59.99$</b></td>
                </tr>
                
              </tbody>
            </Table>
          </div>
          <div className="col-md-4">
            </div>
        </div>
      </div>
    );
  }
}
export default CartPage;
