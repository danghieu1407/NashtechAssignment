import React from "react";

import Header from "../components/Header/Header";
import ProductPageBody from "../components/ProductPage/ProductPageBody";
import Footer from "../components/Footer/Footer";

class ProductPage extends React.Component {


  render() {
    return (
      <div>
        <Header />
        <ProductPageBody  />
        <Footer />
      </div>
    );
  }
}
export default ProductPage;