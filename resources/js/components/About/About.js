import React from "react";

class About extends React.Component {
  render() {
    return (
    <div className="container aboutPageHeader">
        <h3>About us</h3>
          <hr/>
      <div className="container aboutPage">
          
        <div className="row">
      
          <div className="col-md-12">
            <h2 className="about-title">Welcome to Bookworm</h2>
            <p className="about-text">
              {" "}
              Bookworm is an independent New York bookstore and language school
              with locations in Manhattan and Brooklyn. We specialize in travel
              books and language classes.
            </p>
          </div>
          <div className="col-md-6">
            <h2 className="about-title">Our Story</h2>
            <p>
              The name Bookworm was taken from the original name for New York
              International Airport, which was renamed JFK in December 1963.
            </p>
            <br />
            <p>
              {" "}
              Our Manhattan store has just moved to the West Village. Our new
              location is 170 7th Avenue South, at the corner of Perry Street.
            </p>
            <br />
            <p>
              {" "}
              From March 2008 through May 2016, the store was located in the
              Flatiron District.
            </p>
          </div>

          <div className="col-md-6">
            <h2 className="about-title">Our Vision</h2>
            <p>
              One of the last travel bookstores in the country, our Manhattan
              store carries a range of guidebooks (all 10% off) to suit the
              needs and tastes of every traveller and budget.
            </p>
            <br />
            <p>
              We believe that a novel or travelogue can be just as valuable a
              key to a place as any guidebook, and our well-read, well-travelled
              staff is happy to make reading recommendations for any traveller,
              book lover, or gift giver.
            </p>
          </div>
        </div>
      </div>
    </div>
    );
  }
}
export default About;
