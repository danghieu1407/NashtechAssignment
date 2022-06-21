import React from "react";
import { Container, Button } from "react-bootstrap";
// import Swiper core and required modules
import { Navigation, Pagination, Scrollbar, A11y } from "swiper";
import { Swiper, SwiperSlide } from "swiper/react";
import {MdPlayArrow} from 'react-icons/md';
import CardBook from "../Card/CardBook";
import bookimage from "../../../assets/bookcover/book1.jpg";
// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";
import "../../../css/app.css";

class OnSale extends React.Component {
  render() {
    return (
    <Container className="container"  fixed>
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

        <SwiperSlide>
          <CardBook/>
        </SwiperSlide>

        <SwiperSlide>
          <CardBook/>
        </SwiperSlide>
        
        <SwiperSlide>
          <CardBook/>
        </SwiperSlide>
        
        <SwiperSlide>
          <CardBook/>
        </SwiperSlide>

        <SwiperSlide>
          <CardBook/>
        </SwiperSlide>
 
      </Swiper>
      </Container>

    );
  }
}

export default OnSale;
