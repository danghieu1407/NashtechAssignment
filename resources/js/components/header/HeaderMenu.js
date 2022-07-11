import React from "react";
import "../../../css/app.css";
import { Alert, Navbar } from "react-bootstrap";
import { Link, NavLink } from "react-router-dom";
import { Modal, Button } from "react-bootstrap";
import { InputGroup, FormControl } from "react-bootstrap";
import { FcPortraitMode } from "react-icons/fc";
import axios from "axios";
import { Dropdown, Toggle } from "react-bootstrap";
import Swal from 'sweetalert2/dist/sweetalert2.js'

class HeaderMenu extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      show: false,
      email: "",
      password: "",
      first_name: "",
      last_name: "",
      message: "",
    };
  }
  componentDidMount() {
    this.getName();
  }

  openModal = () => {
    this.setState({ show: true });
    console.log("bam ne");
  };
  onHide = () => {
    this.setState({ show: false });
  };

  submit = async (e) => {
    e.preventDefault();
    this.setState({ show: false, email: e, password: e });
    const data = {
      email: this.state.email,
      password: this.state.password,
      
    };
    const config = {
      headers: {
        message: "",
      },
    };
    let token;
    await axios
      .post("http://localhost:8000/api/login", data, config)
      .then((res) => {
        console.log(res.data);
        localStorage.setItem("token", res.data.token);
        Swal.fire({
          icon: 'success',
          title: 'Success',
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

        this.setState({
          email: "",
          password: "",
        });
      })
      .catch((err) => {
    
        Swal.fire({
          icon: 'error',
          title: err.response.data.message,
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

      });
    console.log("localStorage", localStorage.getItem("token"));
    this.getName();
  };
  getName = () => {
    const config = {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    };
    console.log(config);
    axios.get("http://localhost:8000/api/user", config).then((res) => {
      console.log(res);
      this.setState({
        first_name: res.data.first_name,
        last_name: res.data.last_name,
      });
    

    });
  };

  logOut() {
    const config = {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token").split('|')[1],
      },
    };

    console.log(config);
    axios.get("http://localhost:8000/api/logout", config).then((res) => {
      console.log(res);
      localStorage.removeItem("token");
      window.location.reload();
    })
  .catch((err) => {
    console.log(err);
  });

  }

  render() {
    return (
      <>
       
        <Navbar className="nav-link" id="nav-link">
          <Navbar.Brand>
            <NavLink
              to="/"
              className={({ isActive }) => (isActive ? "link-active" : "link")}
            >
              {" "}
              Home{" "}
            </NavLink>
          </Navbar.Brand>
          <Navbar.Brand>
            <NavLink
              to="/ShopPage"
              className={({ isActive }) => (isActive ? "link-active" : "link")}
            >
            
              Shop
            </NavLink>
          </Navbar.Brand>
          <Navbar.Brand>
            <NavLink
              to="/About"
              className={({ isActive }) => (isActive ? "link-active" : "link")}
            >
              {" "}
              About{" "}
            </NavLink>
          </Navbar.Brand>
          <Navbar.Brand href="#Cart">Cart</Navbar.Brand>
          {/* if  */}
          {!localStorage.getItem("token") ? (
            <Navbar.Brand onClick={() => this.openModal()}>
              Sign In
            </Navbar.Brand>
          ) : (
            <Navbar.Brand>
              {" "}
              <Dropdown>
                <Dropdown.Toggle variant="success" id="dropdown-basic">
                  {this.state.first_name} {this.state.last_name}
                </Dropdown.Toggle>

                <Dropdown.Menu>
                  <Dropdown.Item className="log-out-btn" onSelect={()=>this.logOut()}>Log out</Dropdown.Item>
                </Dropdown.Menu>
              </Dropdown>
            </Navbar.Brand>
          )}
        </Navbar>
        <Modal
          show={this.state.show}
          size="lg"
          aria-labelledby="contained-modal-title-vcenter"
          centered
          
        >
          <Modal.Header closeButton >
            <Modal.Title id="contained-modal-title-vcenter">Login</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <InputGroup className="mb-2 "  >
              <InputGroup.Prepend>
                <InputGroup.Text id="inputGroup-sizing-default" >
                  <div>
                    {" "}
                    <FcPortraitMode /> Email{" "}
                  </div>
                </InputGroup.Text>
              </InputGroup.Prepend>
              <FormControl 
                aria-label="Default"
                aria-describedby="inputGroup-sizing-default"
                onChange={(e) => this.setState({ email: e.target.value })}
                
              />
            </InputGroup>
            <InputGroup className="mb-2">
              <InputGroup.Prepend>
                <InputGroup.Text id="inputGroup-sizing-default">
                  <FcPortraitMode /> Password
                </InputGroup.Text>
              </InputGroup.Prepend>
              <FormControl  type="password"
                aria-label="Default"
                aria-describedby="inputGroup-sizing-default"
                onChange={(e) => this.setState({ password: e.target.value })}
             
              />
            </InputGroup>
          </Modal.Body>
          <Modal.Footer>
            <Button onClick={this.submit} className="btn-login">
              Login
            </Button>
            <Button onClick={this.onHide} variant="danger">
              Close
            </Button>
          </Modal.Footer>
        </Modal>
      </>
    );
  }
}

export default HeaderMenu;
