import React from "react";
import "../../../css/app.css";
import MenuFilter from "./MenuFilter";
import FakeCard from "./FakeCard";


class ShopPageBody extends React.Component {
  render() {
    return (
        <div className="shop-container">
            <div className="container">
            
                <div className="row">
                    <div className="col-md-3">
                        <MenuFilter />
                    </div>
                    <div className="col-md-9">
                        <div className="row">
                            <div className="col-md-3">
                                    <FakeCard />
                            </div>
                            <div className="col-md-3">
                                    <FakeCard />
                            </div>
                            <div className="col-md-3">
                                    <FakeCard />
                            </div>
                            <div className="col-md-3">
                                    <FakeCard />
                            </div>
                            <div className="col-md-3">
                                    <FakeCard />
                            </div>
                            <div className="col-md-3">
                                    <FakeCard />
                     
                             </div>
                        </div>
                        <div className="row">
                            <div className="col-md-12">
                        
                                <ul className="pagination">
                                    <li className="page-item"><a className="page-link" href="#">Previous</a></li>
                                    <li className="page-item"><a className="page-link" href="#">1</a></li>
                                    <li className="page-item"><a className="page-link" href="#">2</a></li>
                                    <li className="page-item"><a className="page-link" href="#">3</a></li>
                                    <li className="page-item"><a className="page-link" href="#">Next</a></li>
                                </ul>
                  
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

    );
  }
}
export default ShopPageBody;