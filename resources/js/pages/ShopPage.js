import React from "react"
import Header from "../components/Header/Header";
import Footer from "../components/Footer/Footer";
import 'bootstrap/dist/css/bootstrap.min.css';
import ShopPageBody from "../components/ShopPage/ShopPage";

class ShopPage extends React.Component {
  render() {
    return (
      <>
        <Header />
      <ShopPageBody/>
        <Footer />
      </>
    );
  }
}
export default ShopPage;