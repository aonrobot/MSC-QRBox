import React, {Component} from 'react';
import { Link } from 'react-router-dom'

import FontAwesomeIcon from '@fortawesome/react-fontawesome';

import Util from '../library/Util';

export default class ListFile extends Component {

    constructor(props) {
        super(props);
        console.log('files constructor', this.props.files);        
    }

    componentWillMount(){
        console.log('files componentWillMount', this.props.files);        
    }

    componentDidMount(){
        console.log('files componentDidMount', this.props.files);        
    }

    filePreview(fileId, mimeType, filename = "filename"){
        let mimeTypeCore = mimeType.split('/')[0];
        let mimeTypeSub = mimeType.split('/')[1];
        switch(mimeTypeCore){
            case 'application' :
                return (
                    <div>
                        <object width="100%" height="500px" data={"file/" + fileId}></object>
                        <h5 className="mt-2">
                            <FontAwesomeIcon className="mr-1" icon={["fas", "file"]}/>
                            Click here to view <span className="badge badge-light">{mimeTypeSub}</span> file.                   
                        </h5>
                    </div>
                )
            case 'image' :
                return  <img src={"file/" + fileId} className="img-thumbnail" />
            case 'video' :
                return  (
                    <video>
                        <source src={"file/" + fileId} type="video/mp4"/>
                        Your browser does not support the video tag.
                    </video>  
                )          
        }
    }

    render(){
        return(
            <div className="d-flex flex-column align-self-center p-3">
                <div className="text-left mb-3">
                    <h3><FontAwesomeIcon className="mr-1" icon={["fas", "qrcode"]}/> All your QR Code</h3>
                    <hr/>                                           
                </div>
                {
                    (this.props.uploadNewFile) ? 
                        <button className="btn btn-success btn-lg btn-block mb-3" onClick={() => this.props.toggleShowListFile()}><FontAwesomeIcon icon={["fas", "plus-circle"]} /> Upload New File</button>
                    : ''
                }

                {
                    (this.props.files.length <= 0) ?
                        <div>
                            <h5><FontAwesomeIcon className="mr-1" icon={["far", "file"]}/> Not have any file.</h5>
                            <Link to="/"><FontAwesomeIcon className="mr-1" icon={["fas", "upload"]}/> Click Here To Upload New File</Link>
                        </div>
                    :
                        <div className="table-responsive">
                            <table className="table">
                                <thead className="thead-light">
                                    <tr>
                                        <th scope="col">File Name</th>
                                        <th scope="col" className="w-25">Preview</th>
                                        {/*<th scope="col">Type</th>
                                        <th scope="col">Size</th>*/}
                                        <th scope="col">QR Code</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.props.files.map((file) => {
                                                let fileId = (file.id === undefined) ? file.fileId : file.id;
                                                return (
                                                    <tr key={fileId}>
                                                        <td>{file.filename}</td>
                                                        <td>
                                                            <a href={"file/" + fileId} target="_blank" src={file.filename}>
                                                                {this.filePreview(fileId, file.fileType, file.filename)}
                                                            </a>
                                                        </td>
                                                        {/*<td>{file.fileExtension}</td>
                                                        <td>{Util.capacityUnit(file.fileSize)}</td>*/}
                                                        <td>
                                                            <a className="btn btn-success mb-3" href={"api/services/genqrcode/" + fileId} download><FontAwesomeIcon icon={["fas", "download"]} /> Download QR Code</a>                                                              
                                                            <img src={'api/services/genqrcode/' + fileId} className="d-none d-sm-none d-lg-block img-thumbnail"/>
                                                        </td>
                                                        <td>
                                                            {
                                                                (this.props.shareSettingBtn) ? 
                                                                    <button className="btn btn-info mr-3 mb-3" onClick={() => alert('Share Setting Modal')}><FontAwesomeIcon icon={["fas", "cog"]} /> Share Setting</button>
                                                                : 
                                                                    ''
                                                            }
                                                            <button className="btn btn-danger mr-3 mb-3" onClick={() => this.props.removeFile(fileId)}><FontAwesomeIcon icon={["fas", "trash-alt"]} /> Delete</button>
                                                        </td>
                                                    </tr>
                                                )
                                            } 
                                        )
                                    }
                                </tbody>
                            </table>
                        </div>
                }
                
            </div>
        )
    }
}