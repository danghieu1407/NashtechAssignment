import React from "react";
import CardBook from "../Card/CardBook";
import { Button } from "react-bootstrap";

import axios from "axios";




class FeaturedBooks extends React.Component {
   //change color for button recommended to red
    constructor(props) {
        super(props);
    this.state = {
        recommended: [],
        popular: [],
        data: [],
        isRecommended: true
      }
    }
        

    
        componentDidMount() {
            this.getRecommended();
            }

        getRecommended = () => {
            this.setState({  isRecommended: true });
            if(this.state.recommended.length === 0) {
                axios.get(`http://localhost:8000/api/getTheMostRatingStartsBooks`)
            .then(res => {
              const data = res.data;
              this.setState({ recommeded: data });
              this.setState({ data });
            })
            .catch(error => console.log(error));
            } else {
                this.setState({ data: this.state.recommeded });
            }
        }
        getPopular = () => {
            this.setState({  isRecommended: false });
            if (this.state.popular.length === 0) {
                axios.get(`http://localhost:8000/api/getTheMostReviewBooks`)
                .then(res => {
                    const data = res.data;
                    this.setState({ popular: data });
                    this.setState({ data });
                    }
                )
                .catch(error => console.log(error));
            } else {
                this.setState({ data: this.state.popular });
            }
        }
    render() {
        return (
            <div id="container-featured" className="container">
                <div className="row">
                
                </div>
                <div className="row">
                    <div className="col-md-12"  >
                        <h1 className="Featured-title">Featured Books</h1>
                        <div className="button-featured">
                        <Button variant={this.state.isRecommended ? 'primary' : 'link'}  onClick={this.getRecommended} >Recommended</Button>
                        <Button variant={this.state.isRecommended ? 'link' : 'primary'}
                        onClick={this.getPopular} className="button-popular">Popular</Button>

                        </div>
                    </div>
                </div>
                <div className="row">
                     
                {this.state.data.map((item, idx) => (
                <div className="col-md-3">
                <CardBook id={item.id}  author={item.author_name} title={item.book_title} img={item.book_cover_photo} original_price={item.book_price} final_price={item.final_price} discount_price={item.discount_price}  />
                 </div>
        
                 ))}
                        </div>
                </div>
                        
                   
                
        );
    }
}
export default FeaturedBooks;

