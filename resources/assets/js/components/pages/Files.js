import React, {Component} from 'react';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

import ListFile from '../ListFile';

export default class Files extends Component{
    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            login: document.head.querySelector('meta[name="basic-auth"]').content,
            files: [],
            finishLoading: false
        };

        //this.setFiles = this.setFiles.bind(this);
    }

    setFiles(files) {
        console.log('setFiles', files);
        this.setState({files});
    }

    componentDidMount() {
        axios.get('api/file/' + this.state.login).then((response) => {
            if(response.status === 200){
                this.setState({files: response.data, finishLoading: true})                                
            }
        }).catch(function (error) {
            Swal('ไม่สามารถดึงข้อมูลได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error')
            console.log(error)
        });
    }

    render(){
        return(
            <div className="d-flex h-100 p-5 mt-3">
                {
                    (this.state.finishLoading) ? 
                        <ListFile files={this.state.files} setting={{
                            shareSettingBtn: true,
                            removeAllBtn: true,
                            searchBox: true,
                            actionBtn: true

                        }}/>
                    :
                        <div className="d-flex flex-column align-self-center p-3">
                            <h1>Loading <FontAwesomeIcon icon={["fas", "sync"]} spin className="ml-1"/></h1>
                        </div>
                }
            </div>
        )
    }
}