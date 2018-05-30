import React, {Component} from 'react';

import Util from '../../library/Util';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

//Filepond
import {FilePond, File, registerPlugin } from 'react-filepond';
import FilePondImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilepondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilepondPluginFileValidateType from 'filepond-plugin-file-validate-type';

import GetQRCodeBtn from '../GetQRCodeBtn';
import ListFile from '../ListFile';

registerPlugin(FilePondImagePreview);
registerPlugin(FilepondPluginFileValidateSize);
registerPlugin(FilepondPluginFileValidateType);

export default class Home extends Component{
    constructor(props) {
        super(props);

        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            login: document.head.querySelector('meta[name="basic-auth"]').content,

            files: [],
            countAddFile: 0,
            showListFile: false
        };

        this.uploadUIlabel = 'Drag & Drop ไฟล์ของคุณ <b>(3 ไฟล์)</b> ลงตรงพื้นที่สีเทา หรือ <span class="badge badge-pill badge-primary filepond--label-action"> กด Browse ที่นี่ </span>\
        <br> ไฟล์ที่รองรับ : ไฟล์รูปทุกชนิด, วีดีโอทุกชนิด, เสียงทุกชนิด, PDF, เอกสาร Microsoft Office (doc, xls, ppt), text file (txt, html)';

        this.toggleShowListFile = this.toggleShowListFile.bind(this);
        //this.setFiles = this.setFiles.bind(this);
    }

    componentDidMount() {
        //this.setState({login: document.head.querySelector('meta[name="basic-auth"]').content});
        
    }

    setFiles(files) {
        console.log('updateFiles', files);
        this.setState({files});
    }

    toggleShowListFile(checkGenQRCodeBtn = true) {
        this.setState({countAddFile: 0});     
        this.setState({showListFile: !this.state.showListFile})
    }

    uploadNewFile(){
        this.setState({files: []});
        this.toggleShowListFile();
    }

    handleInit() {

        this.pond._pond.on('processfile', (error, file) => {                  
            let files = this.state.files;
            files.unshift(file)
            this.setState({files})
            //this.pond._pond.removeFile(file.id)
            //this.divShowQRCode.scrollIntoView({ behavior: "smooth", block: "start" });
            this.setState({countAddFile: this.state.countAddFile++});
            console.log(file, this.state.countAddFile)            
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

    handleAddFile(error, file) {
        let countAddFile = this.state.countAddFile;
        countAddFile++;
        this.setState({countAddFile});
    }

    render(){
        console.log('Home render', this.state.files)
        return(
            <div className="d-flex flex-column justify-content-center h-100 mt-5 p-5">
                <div className="d-flex flex-column align-self-center">
                    <div className="title text-center">
                        <FontAwesomeIcon icon={["fas", "qrcode"]} color="#74b9ff" /> QR Box
                    </div>
                    <div className="links text-center">
                        <p className="mb-5">By <span className="color-w-primary">Metrosystems</span> Cop. PCL.</p>
                        
                        { !this.state.showListFile ?
                            
                            <FilePond   allowMultiple={true} 
                                    maxFiles={300}
                                    maxFileSize={'1024MB'}
                                    maxTotalFileSize={'1024MB'}
                                    acceptedFileTypes={
                                        [
                                            'image/*',
                                            'video/mp4',
                                            'audio/*',
                                            'text/plain',
                                            'text/html',  
                                            'application/pdf',
                                            'application/msword',
                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                            'application/vnd.ms-excel',
                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                            'application/vnd.ms-powerpoint',
                                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                            'application/vnd.ms-access'
                                        ]
                                    }
                                    ref={ref => this.pond = ref}
                                    server={{
                                        url: 'api/uploadBox',
                                        process: {
                                            headers: {
                                                'X-CSRF-TOKEN': this.state.token,
                                                'BASIC-AUTH': this.state.login
                                            }
                                        },
                                        revert: {
                                            headers: {
                                                'X-CSRF-TOKEN': this.state.token,
                                                'BASIC-AUTH': this.state.login
                                            }
                                        }
                                    }}
                                    labelIdle={this.uploadUIlabel}
                                    instantUpload={true}
                                    onaddfile={(error, file) => this.handleAddFile(error, file)}
                                    oninit={() => this.handleInit()}
                            ></FilePond>

                        : '' }

                        { (this.state.files.length > 0) && !this.state.showListFile ?

                            <GetQRCodeBtn handleClick={this.toggleShowListFile} login={this.state.login} files={this.state.files} countAddFile={this.state.countAddFile}/>
                        
                        : '' }

                    </div>
                </div>

                { this.state.showListFile ?

                    <div>
                        <div className="text-center">
                            <button className="btn btn-success mb-3 w-50" onClick={() => this.uploadNewFile()}><FontAwesomeIcon icon={["fas", "plus-circle"]} /> Upload New File</button>
                        </div>
                        <ListFile files={this.state.files} setting={{
                                removeAllBtn: true,
                            }}/>
                    </div>

                : ''}

            </div>
        )
    }
}