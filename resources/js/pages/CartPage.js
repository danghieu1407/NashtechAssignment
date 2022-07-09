import React from "react";

import Header from "../components/Header/Header";
import CartPageBody from "../components/CartPage/CartPage";
import Footer from "../components/Footer/Footer";


class CartPage extends React.Component {
    render() {
      return (
        <div>
          <Header />
          <CartPageBody />
          <Footer />
        </div>
      );
    }
  }
  export default CartPage;