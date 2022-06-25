import React from "react"
import Header from "../components/Header/Header";
import HomePageBody from "../components/HomePage/HomePage";
import Footer from "../components/Footer/Footer";
import 'bootstrap/dist/css/bootstrap.min.css';

class HomePage extends React.Component {
  render() {
    return (
      <div>
        <Header />
        <HomePageBody />
        <Footer />
      </div>
    );
  }
}
export default HomePage;