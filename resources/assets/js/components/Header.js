import React, {Component} from 'react';
import { Link } from "react-router-dom";
import axios from 'axios';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class Header extends Component{
    constructor(props){
        super(props);
        this.state = {
            userInfo : []
        }
    }

    componentDidMount(){
        let login = document.head.querySelector('meta[name="basic-auth"]').content;
        axios.get('api/employee/info/' + login).then(response => {
            this.setState({userInfo : response.data[0]});
            console.log(this.state.userInfo);
        });

        window.addEventListener('beforeunload', this.keepOnPage);
    }
    
    render(){
        return(
            <nav className="navbar navbar-expand-lg navbar-light bg-light">
                <a className="navbar-brand" href="/qrbox"><FontAwesomeIcon icon={["fas", "qrcode"]} color="#74b9ff" /> QR-BOX</a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>

                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">
                        <li className="nav-item mr-2">
                            <Link className="btn btn-outline-success" to="/"><FontAwesomeIcon className="mr-1" icon={["fas", "cloud-upload-alt"]}/> Upload<span className="sr-only">(current)</span></Link>
                        </li>
                        <li className="nav-item">
                            <Link className="btn btn-outline-primary" to="/files"><FontAwesomeIcon className="mr-1" icon={["fas", "file-alt"]}/> My Files</Link>
                        </li>
                    </ul>
                    <div className="mr-3">
                        <button className="btn btn-outline-secondary mr-3" type="button" disabled>Hi, {this.state.userInfo.FullNameEng}</button>
                        <button className="btn btn-outline-danger" type="button">Logout!!</button>
                    </div>
                </div>
            </nav>
        );
    }
} 