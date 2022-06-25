import React from "react";
import FeaturedBooks from "./FeaturedBooks";
import OnSale from "./OnSale";

class HomePageBody extends React.Component {
    render() {
        return (

        <div className="body-homePage">
            <div className="container">
                <OnSale />
                <FeaturedBooks />

            </div>
        </div>
        );
    }
}
export default HomePageBody;