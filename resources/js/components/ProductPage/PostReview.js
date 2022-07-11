import React from "react";
import axios from "axios";
import { Card, Button } from "react-bootstrap";
import "../../../css/app.css";
import Form from 'react-bootstrap/Form'
import success from '../../../assets/images/success.gif'
import Swal from 'sweetalert2/dist/sweetalert2.js'

class PostReview extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      rating: ''
    }
  }

  submitReview = (e) => {
    e.preventDefault();
    let id = window.location.pathname.split("/");
    let idBook = id[id.length - 1];
    
    this.setState({
      title: e,
      detail: e,
      rating: e,
      });

      const data = {
        book_id: idBook,
        review_title: this.state.review_title,
        review_details: this.state.review_details,
        rating_star: this.state.rating_star,
      }


    axios .post("http://localhost:8000/api/createReview", data)
    .then(res => {
    
      let timerInterval
      Swal.fire({
        title: 'Post a review success!',
        html:
          `I will close in <strong></strong> seconds.<br/><br/><img id="img-success" src=${success} />`,
        timer: 5000,
        showConfirmButton: false,
        didOpen: () => {
          const content = Swal.getHtmlContainer()
          const $ = content.querySelector.bind(content)
  
          timerInterval = setInterval(() => {
            Swal.getHtmlContainer().querySelector('strong')
              .textContent = (Swal.getTimerLeft() / 1000)
                .toFixed(0)
          }, 100)
        },
        willClose: () => {
          
          window.location.reload();
        }
      })

    })   .catch(err => {
      console.log(err.response);
    
      if(err.response.data.message.review_title == "Title must be less than 120 characters"){
        Swal.fire({
          icon: 'error',
          title: "Title must be less than 120 characters",
          toast: true,
          position: 'top-right',
          iconColor: 'white',
          customClass: {
            popup: 'colored-toast'
          },
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        })
      }

     else{
        Swal.fire({
          icon: 'error',
          title: "Title and rating star are required",
          toast: true,
          position: 'top-right',
          iconColor: 'white',
          customClass: {
            popup: 'colored-toast'
          },
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        })
      
   
      }
      
    });
  

  }
  render() {
    return (
      <div className="post-review">
        <Card style={{ width: "18rem" }}>
        <Card.Footer className="title-post-review"> Write a Review</Card.Footer>
          
          <Form>
            <Form.Group className="mb-3 text-post-review" >
              <Form.Label>Add a title</Form.Label>
              <Form.Control  type="text" placeholder="" onChange={(e)=> this.setState({review_title: e.target.value})} />
            </Form.Group>

            <Form.Group className="mb-3  text-post-review" >
              <Form.Label>Details please! Your review helps other shoppers</Form.Label>
              <Form.Control as="textarea" rows={3} onChange={(e)=> this.setState({review_details: e.target.value})} />
            </Form.Group>

            <Form.Group className="text-post-review" controlId="exampleForm.ControlSelect1">
              <Form.Label >Select a rating star </Form.Label>
              <Form.Control  as="select" value={this.state.rating_star} onChange={(e)=> this.setState({rating_star: e.target.value})} >
                <option>Please select ...</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </Form.Control>
            </Form.Group>
            <Form.Group className="mb-3  text-post-review" >
            <Button variant="primary" className="btn-postreview" onClick={this.submitReview}>
              Submit
            </Button>
            </Form.Group>

          </Form>
        </Card>
      </div>
    );
  }
}
export default PostReview;
