import React from "react";
import AboutPage from "../components/About/About";
import Header from "../components/Header/Header";
import Footer from "../components/Footer/Footer";
class About extends React.Component {
  render() {
    return (
      <>
        <Header/>
        <AboutPage />
        <Footer />
      </>
    );
  }
}
export default About;