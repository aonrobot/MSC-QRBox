import React, {Component} from 'react';
import axios from 'axios';

import Util from '../../library/Util';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

//Filepond
import {FilePond, File, registerPlugin } from 'react-filepond';
import FilePondImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

registerPlugin(FilePondImagePreview);

export default class Home extends Component{
    constructor(props) {
        super(props);

        this.state = {
            files: [],
            countAddFile: 0,
            uploadFinished: false,
            login: document.head.querySelector('meta[name="basic-auth"]').content
        };
    }

    saveFile(file){
        let login = this.state.login;
        let file_detail = {
            fileExtension : file.fileExtension,
            fileSize : file.fileSize,
            fileType : file.fileType,
            filename : file.filename,
            filenameWithoutExtension : file.filenameWithoutExtension,
            id : file.id,
            serverId : file.serverId
        }
        console.log(JSON.stringify(file_detail))
        axios.post('api/file/store', {login, file : file_detail}).then((response) => {
            console.log(response);
            if(response.status === 200){
                
            }
        })
    }

    removeFile(id, serverId){
        this.pond._pond.removeFile(id);
        let login = this.state.login;
        axios.post('api/file/delete', {serverId, login}).then((response) => {
            console.log(response);
            if(response.status === 200){
                let files = this.state.files;
                files = files.filter((el) => (
                    el.serverId != serverId
                ))
                this.setState({files})
            }
        })
    }

    handleInit() {
        let countProcessFile = 0;
        this.pond._pond.on('processfile', (error, file) => {                  
            let files = this.state.files;
            files.push(file)
            this.setState({files})
            //this.pond._pond.removeFile(file.id)
            this.divShowQRCode.scrollIntoView({ behavior: "smooth", block: "start" });

            console.log(file)
        });
        
        this.pond._pond.on('removefile', (file) => {                       
            let files = this.state.files;
            files = files.filter((el) => (
                el.id != file.id
            ))
            this.setState({files})
        });
    }

    handleProcessing(fieldName, file, metadata, load, error, progress, abort) {
    }

    handleAddFile(error, file){
        let countAddFile = this.state.countAddFile;
        countAddFile++;
        this.setState({countAddFile});
    }

    render(){
        let token = document.head.querySelector('meta[name="csrf-token"]').content;
        let label = 'Drag & Drop ไฟล์ของคุณลงตรงพื้นที่สีเทา หรือ <span class="filepond--label-action"> กด Browse ที่นี่ </span>';
        return(
            <div className="d-flex flex-column justify-content-center h-100 mt-5">
                <div className="d-flex flex-column align-self-center">
                    <div className="title text-center">
                        <FontAwesomeIcon icon={["fas", "qrcode"]} color="#74b9ff" /> QR Box
                    </div>
                    <div className="links text-center">
                        <p className="mb-5">By <span className="color-w-primary">Metrosystems</span> Cop. PCL.</p>
                        
                        { !(this.state.uploadFinished) ?

                            <FilePond   allowMultiple={true} 
                                        maxFiles={3} 
                                        ref={ref => this.pond = ref}
                                        server={{
                                            url: 'api/uploadBox',
                                            process: /*this.handleProcessing.                                   bind(this)*/{
                                                headers: {
                                                    'X-CSRF-TOKEN': token
                                                }
                                            }
                                        }}
                                        labelIdle={label}
                                        instantUpload={true}
                                        onaddfile={(error, file) => this.handleAddFile(error, file)}
                                        oninit={() => this.handleInit()}
                            >
                                   
                            </FilePond>


                        : '' }

                        <button className="btn btn-success mr-3" onClick={() => this.saveFile(file)}>Save</button>                      

                    </div>
                </div>

            { (this.state.files.length > 0) ?

                <div className="d-flex flex-column align-self-center mt-5 p-5" ref={ (el) => this.divShowQRCode = el}>
                    <div className="text-center">
                        All your file and QR Code                                           
                    </div>
                    <table className="table">
                    <thead className="thead-light">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Preview</th>
                            <th scope="col">Type</th>
                            <th scope="col">Size</th>
                            <th scope="col">QR Code</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody> 
                        {
                            this.state.files.map((file) => (
                                <tr key={file.id}>
                                    <td>{file.id}</td>
                                    <td>
                                        <a href={"api/services/temp/" + file.serverId.replace("temp/", "")} target="_blank">
                                            <img src={"api/services/temp/" + file.serverId.replace("temp/", "")} className="w-75 img-thumbnail" />
                                        </a>
                                    </td>
                                    <td>{file.fileExtension}</td>
                                    <td>{Util.capacityUnit(file.fileSize)}</td>
                                    <td>
                                        <img src={'api/services/genqrcode/' + file.serverId.replace("temp/", "")} width="300px"/>
                                    </td>
                                    <td>
                                        <button className="btn btn-danger" onClick={() => this.removeFile(file.id, file.serverId)}>Delete</button>
                                    </td>
                                </tr>
                            ))
                        }
                    </tbody>
                    </table>
                    {/*<button type="button" className="btn btn-primary mt-3 mb-5">Save File</button>*/}
                </div>

            : ''}

            </div>
        )
    }
}