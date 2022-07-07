import React from "react";
import { Container, Button } from "react-bootstrap";
// import Swiper core and required modules
import { Navigation, Pagination, Scrollbar, A11y } from "swiper";
import { Swiper, SwiperSlide } from "swiper/react";
import {MdPlayArrow} from 'react-icons/md';
import CardBook from "../Card/CardBook";
import bookimage from "../../../assets/bookcover/book1.jpg";
import axios from "axios";
// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";
import "../../../css/app.css";

class OnSale extends React.Component {
  state = {
    data: []
  }

    componentDidMount() {
      axios.get(`http://localhost:8000/api/books`)
        .then(res => {
          const data = res.data;
          console.log(data);
          this.setState({ data });
        })
        .catch(error => console.log(error));
    }

  render() {
    return (
    <Container className="container"  fixed>
   <br></br>
        <div>
        <h1>OnSale   <Button className="view-all">View All <MdPlayArrow></MdPlayArrow></Button></h1>
      
        </div> 
      <Swiper
            modules={[Navigation, Pagination, Scrollbar, A11y]}
            spaceBetween={28}
            slidesPerView={4}
            navigation
            loop
            onSlideChange={() => console.log("slide change")}
            onSwiper={(swiper) => console.log(swiper)}
      >
     {this.state.data.map((item, idx) => (
      <SwiperSlide>
          <CardBook id={item.id}  author={item.author_name} title={item.book_title} img={item.book_cover_photo} original_price={item.book_price} final_price={item.final_price} discount_price={item.discount_price}  />
        </SwiperSlide>
        ))}
      </Swiper>
      </Container>

    );
  }
}

export default OnSale;
