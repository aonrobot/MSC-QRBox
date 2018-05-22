import React, {Component} from 'react';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class Genqr extends Component{
    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            login: document.head.querySelector('meta[name="basic-auth"]').content,
            linkValue: '',
            loading: false
        };
    }

    handleChange(event) {
        this.setState({loading: true})
        let link = event.target.value;
        axios.post('api/services/gencustomqrcode', {url : link}).then((response) => {
            console.log(response)
            if(response.data == '') this.setState({linkValue: ''})
            else this.setState({linkValue: 'data:image/png;base64,' + response.data})
            this.setState({loading: false})
        }).catch(function (error) {
            Swal('ไม่สามารถ Genarate QR Code ให้ได้', 'เนื่องจากคุณส่งคำร้องขอมามากเกินไป กรุณากลับมาลองใหม่อีกครั้งในอีก 1 นาที', 'warning')
            this.setState({linkValue: ''})
        });
    }

    render(){
        return(
            <div className="d-flex h-100 p-5 mt-3">
                <div className="d-flex flex-column align-self-center p-3 w-100">
                    <div className="row">
                        <div className="col-md-6">
                            <h4>Genarate QR Code By link <small>แปะลิ้งตรงช่องด้านล่างเพื่อสร้าง QR Code</small></h4><hr/>
                            <div className="input-group input-group-lg">
                                <div className="input-group-prepend">
                                    <span className="input-group-text" id="inputGroup-sizing-lg"><FontAwesomeIcon className="mr-1" icon={["fas", "gavel"]}/> Paste Your Link</span>
                                </div>
                                <input onKeyUp={(e) => {this.handleChange(e)}} type="text" className="form-control" aria-label="Large" placeholder="Paste link here" aria-describedby="inputGroup-sizing-sm"/>
                            </div> 
                        </div>
                        <div className="col-md-5 text-center">
                            {
                                (this.state.loading) ? 
                                    <img src="./images/genQRCode_loading.gif" className="img-fluid" alt="Responsive image" width="128px"/>
                                : 

                                    (this.state.linkValue === '') ? 
                                        <img src="./images/adsHere.png" className="img-fluid" alt="Responsive image"/>
                                        
                                    :
                                        <img src={encodeURI(this.state.linkValue)} className="img-fluid" alt="Responsive image"/>
                            }
                        </div>
                    </div>
                </div>                  
            </div>
        )
    }
}