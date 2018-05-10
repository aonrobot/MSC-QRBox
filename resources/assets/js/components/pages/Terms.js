import React, {Component} from 'react';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class Terms extends Component{
    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            login: document.head.querySelector('meta[name="basic-auth"]').content,
        };
    }

    render(){
        return(
            <div className="d-flex h-100 pl-5 pr-5 pt-4 mt-3">
                <div className="jumbotron w-100 text-center">
                    <h1 className="display-4"><FontAwesomeIcon className="mr-1" icon={["fas", "gavel"]}/> เงื่อนไขการใช้งานและบริการ</h1>
                    <p className="lead">ในการใช้งาน QR-Box มีเงื่อนไขต่างๆในการใช้งานตามด้านล่างนี้</p>
                    <hr className="my-4"/>
                    <div className="alert alert-info text-left w-50 m-auto" role="alert">
                        <b><FontAwesomeIcon className="mr-1" icon={["fas", "location-arrow"]}/> เงื่อนไขในการใช้งาน</b>
                        <ul>
                            <li>สามารถ upload ได้ครั้งละ 3 ไฟล์</li>
                            <li>แต่ละไฟล์ต้องมีขนาดไม่เกิน 1Gb</li>
                            <li>และขนาดไฟล์รวมทั้งหมด 3 ไฟล์ต้องไม่เกิน 1Gb</li>
                        </ul>
                    </div>
                    <p className="lead mt-5">
                        <a className="btn btn-primary btn-lg" href="#" role="button">ศึกษาเพิ่มเติม</a>
                    </p>
                </div>
                       
            </div>
        )
    }
}