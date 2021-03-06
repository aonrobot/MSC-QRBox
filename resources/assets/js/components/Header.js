import React, {Component} from 'react';
import { Link } from "react-router-dom";
import axios from 'axios';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class Header extends Component{
    constructor(props){
        super(props);
        this.state = {
            userInfo : [],
            isAdmin : null
        }
    }

    logout(){
        axios.get('logout').then((response) => {
            if(response.status === 200){
                location.reload(true);
            }
        })

    }

    componentDidMount(){

        let login = document.head.querySelector('meta[name="basic-auth"]').content;
        axios.get('api/employee/info/' + login).then(response => {
            this.setState({userInfo : response.data[0]});
        });

        axios.get('api/employee/isAdmin/' + login).then(response => {
            this.setState({isAdmin : response.data});
        });  

        window.addEventListener('beforeunload', this.keepOnPage);
    }
    
    render(){
        console.log('render')        
        return(
            <nav className="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top box-shadow p-3 px-md-4">
                <a className="navbar-brand" href="/qrbox"><FontAwesomeIcon icon={["fas", "qrcode"]} color="#d6d6d6" size="2x"/></a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>

                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">
                        <li className="nav-item mr-2">
                            <Link className="nav-link text-success" to="/"><FontAwesomeIcon className="mr-1" icon={["fas", "cloud-upload-alt"]}/> Upload<span className="sr-only">(current)</span></Link>
                        </li>
                        <li className="nav-item mr-2">
                            <Link className="nav-link text-primary" to="/myfile"><FontAwesomeIcon className="mr-1" icon={["fas", "file-alt"]}/> My Files</Link>
                        </li>
                        <li className="nav-item mr-2">
                            <Link className="nav-link text-primary" to="/genqr"><FontAwesomeIcon className="mr-1" icon={["fas", "qrcode"]}/> QRCode</Link>
                        </li>
                        <li className="nav-item">
                            <Link className="nav-link" to="/terms"><FontAwesomeIcon className="mr-1" icon={["fas", "gavel"]}/> เงื่อนไขการใช้งาน</Link>
                        </li>

                        <li className="nav-text"></li>
                        
                    </ul>
                    <div className="mr-3">
                        <button className="btn btn-outline-secondary mr-3" type="button" disabled>Hi, {this.state.userInfo.FullNameEng}</button>
                        {
                            (this.state.isAdmin) ? 
                                //<li className="nav-item mr-2">
                                    <a className="btn btn-outline-warning mr-3" href="admin" target="_blank"><FontAwesomeIcon icon={["fas", "fire"]}/> Admin Zone</a>
                               // </li>
                            :
                                ''
                        }
                        <button className="btn btn-outline-danger" type="button" onClick={() => this.logout()}>Logout!!</button>
                    </div>
                </div>
            </nav>
        );
    }
} 