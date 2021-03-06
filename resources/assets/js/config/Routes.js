import React from 'react';
import ReactDOM from 'react-dom';

//Font
import fontawesome from '@fortawesome/fontawesome';
import FontAwesomeIcon from '@fortawesome/react-fontawesome';
import regular from '@fortawesome/fontawesome-free-regular';
import brands from '@fortawesome/fontawesome-free-brands';
import solids from '@fortawesome/fontawesome-free-solid';

//Route
import { BrowserRouter as Router, Route, Link } from "react-router-dom";

//Component
import Header from '../components/Header';
import Footer from '../components/Footer';

//Pages
import Home from '../components/pages/Home';
import Files from '../components/pages/Files';
import Genqr from '../components/pages/Genqr';
import Terms from '../components/pages/Terms';

fontawesome.library.add(regular, brands, solids);

const Routes = () => (
    <Router basename={'/qrbox'}>
        <div>
            <Header />
            <Route exact path="/" component={Home} />
            <Route path="/myfile" component={Files} />
            <Route path="/genqr" component={Genqr} />
            <Route path="/terms" component={Terms} />
            {/*<Footer />*/}         
        </div>
    </Router>
);

export default Routes;

if (document.getElementById('main')) {
    ReactDOM.render(<Routes />, document.getElementById('main'));
}