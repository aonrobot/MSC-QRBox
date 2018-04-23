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
import Main from '../components/Main';
import Header from '../components/Header';

//Pages
import Home from '../components/pages/Home';
import Files from '../components/pages/Files';

fontawesome.library.add(regular, brands, solids);

const Routes = () => (
    <Router basename={'/qrbox'}>
        <div>
            <Header />
            <Route exact path="/" component={Home} />
            <Route path="/files" component={Files} />
        </div>
    </Router>
);

export default Routes;

if (document.getElementById('main')) {
    ReactDOM.render(<Routes />, document.getElementById('main'));
}