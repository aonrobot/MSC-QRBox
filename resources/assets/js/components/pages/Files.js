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

        this.removeFile = this.removeFile.bind(this);
    }

    removeFile(id) {
        //this.pond._pond.removeFile(id);
        let login = this.state.login;
        axios.post('api/file/delete', {id, login}).then((response) => {
            if(response.status === 200){
                let files = this.state.files;
                files = files.filter((el) => (
                    el.fileId != id
                ))
                this.setState({files})
            }
            console.log(this.state.files);            
        }).catch(function (error) {
            Swal('ไม่สามารถลบรูปได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error')
        });
    }

    componentDidMount() {
        axios.get('api/file/' + this.state.login).then((response) => {
            if(response.status === 200){
                console.log(response)
                this.setState({files: response.data, finishLoading: true})                                
            }
        }).catch(function (error) {
            Swal('ไม่สามารถดึงข้อมูลได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error')
            console.log(error)
        });
    }

    render(){
        return(
            <div className="d-flex h-100 pl-5 pt-4 mt-3">
                {
                    (this.state.finishLoading) ? 
                        <ListFile files={this.state.files} shareSettingBtn={true} removeFile={this.removeFile}/> 
                    :
                        <div className="d-flex flex-column align-self-center p-3">
                            <h4>Loading <FontAwesomeIcon icon={["fas", "sync"]} spin className="ml-1"/></h4>
                        </div>
                }
                
            </div>
        )
    }
}