import React, {Component} from 'react';
import ReactDOMServer from 'react-dom/server';
import { Link } from 'react-router-dom'

import FontAwesomeIcon from '@fortawesome/react-fontawesome';

import Util from '../library/Util';

import 'datatables.net-bs4/js/dataTables.bootstrap4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.css';

import 'datatables.net-responsive-bs4/js/responsive.bootstrap4.min';
import 'datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css';

export default class ListFile extends Component {

    constructor (props) {
        super(props);
        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            rows: []
        }
    }

    componentWillMount () {
    }

    componentDidMount () {
        let files = this.props.files;
        let data = [];
        files.map((file)=>{
            data.push({file})
        })

        var $table = $(this.table);
        var oTable = $table.DataTable({

            responsive: true,
            columns: [
                {
                    width: "1%",
                    orderable: false,
                    searchable: false
                },
                {
                    title: 'File Name',
                    width: "15%"
                },
                {
                    width: "1%"                    
                },
                {
                    orderable: false,
                    searchable: false
                },
                {
                    title: 'QR Code',
                    width: "20%",
                    orderable: false,
                    searchable: false
                },
                {
                    title: 'Actions',
                    width: "15%",
                    orderable: false,
                    searchable: false
                }
            ]
        })

        var $searchBox = $(this.searchBox);
        $searchBox.keyup(() => {
            oTable.search($searchBox.val()).draw();
        })

        $('#shareSettingModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var fileId = button.data('fileid')
            var fileName = button.data('filename')             
            var shareLink = button.data('shareLinkInput') 
            var modal = $(this)

            let appURL = document.head.querySelector('meta[name="app-url"]').content;
            modal.find('#shareLinkInput').val(appURL + 'share/' + shareLink + '/' + fileName)

            let checked;
            let lableStatus;

            axios.post('api/file/get/isShare', {id : fileId}).then((response) => {
                if(response.status === 200){
                    checked = (response.data.isShare === 1) ? true : false;
                    lableStatus = (checked) ? 'Disable' : 'Enable';
                    modal.find('#isChecked').prop('checked', checked);
                    modal.find('#labelStatus').text(lableStatus);
                    if(checked){
                        modal.find('#shareLinkDiv').show() 
                    }else{
                        modal.find('#shareLinkDiv').hide() 
                    }
                }
            });

            modal.find('#isChecked').click(() => {
                if(checked){
                    axios.post('api/file/unshare', {id : fileId}).then((response) => {
                        if(response.status === 200){
                            Swal('Disable Sharing Success', '','success')
                        }
                    });
                    checked = false;
                    lableStatus = (checked) ? 'Disable' : 'Enable';
                    modal.find('#labelStatus').text(lableStatus);    
                    modal.find('#shareLinkDiv').hide()                
                } else {
                    axios.post('api/file/share', {id : fileId}).then((response) => {
                        if(response.status === 200){
                            Swal('Enable Sharing Success', '','success')
                        }
                    });
                    checked = true;   
                    lableStatus = (checked) ? 'Disable' : 'Enable';
                    modal.find('#labelStatus').text(lableStatus);
                    modal.find('#shareLinkDiv').show()                     
                }
            })
            
        })

        $('#shareSettingModal').on('hidden.bs.modal', function (event) {
            var modal = $(this)
            modal.find('#isChecked').unbind('click');            
        })
    }

    deleteAll () {
        let checkedId = [];
        $("input[name=chkBoxFile]:checked").each(function(){
            checkedId.push($(this).val());
        });

        if( checkedId.length <= 0 ) {
            //Alert Error 'No Item Select'
        } else {
            this.props.removeFiles(checkedId)
        }
    }

    filePreview (fileId, mimeType, filename = "filename") {
        let mimeTypeCore = mimeType.split('/')[0];
        let mimeTypeSub = mimeType.split('/')[1];
        switch(mimeTypeCore){
            case 'application' :
                return (
                    <div>
                        <h5 className="mt-2">
                            <FontAwesomeIcon className="mr-1" icon={["fas", "file"]}/>
                            Click here to view <span className="badge badge-light">{mimeTypeSub}</span> file.                   
                        </h5>
                    </div>
                )
            case 'image' :
                return (
                    <img src={"file/" + fileId} className="img-fluid" />
                )
            case 'video' :
                return  (
                    <video>
                        <source src={"file/" + fileId} type="video/mp4"/>
                        Your browser does not support the video tag.
                    </video>  
                )          
        }
    }

    checkAllFile(){
        $('input[name=chkBoxFile]').prop('checked', $('input[name=chkBoxAllFile]').prop('checked'));
    }

    render(){
        return(
            <div className="d-flex flex-column align-self-center p-3 w-100">
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
                        <div className="table-responsive p-1">
                            <div className="input-group input-group-lg mb-4">
                                <div className="wrapSearchBox">
                                    <span className="mr-3"><FontAwesomeIcon className="searchBox mr-2" icon={["fas", "search"]}/> Search File</span>
                                    <input type="text" className="searchBox" ref={el => this.searchBox = el}/>   
                                </div>
                            </div>
                            <button type="button" className="btn btn-danger" style={{  position: 'absolute', right: '32px' , zIndex: '2' }} onClick={ () => this.deleteAll() } >  <FontAwesomeIcon icon={["fas", "trash"]} /> Delete All </button>
                            <table className="table table-bordered display responsive" width="100%" ref={el => this.table = el}>
                                <thead className="thead-light">
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" name="chkBoxAllFile" onClick={() => this.checkAllFile()}/>
                                        </th>
                                        <th scope="col">File Name</th>
                                        <th scope="col">Size</th>
                                        <th scope="col" className="w-25">Upload Date</th>
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
                                                        <td>
                                                            <input type="checkbox" name="chkBoxFile" value={fileId} />
                                                        </td>                                                        
                                                        <td>
                                                            <a href={"file/" + fileId} target="_blank" src={file.filename}>
                                                                {file.filename}
                                                            </a>
                                                        </td>
                                                        <td>{Util.capacityUnit(file.fileSize)}</td>
                                                        <td>
                                                            {moment(file.created_at).format('DD/MM/YYYY, h:mm:ss a')}
                                                        </td>
                                                        <td>
                                                            <p>
                                                                <a className="btn btn-outline-primary mr-3" href={"services/genqrcode/" + file.shareLink} target={"_blank"}><FontAwesomeIcon icon={["fas", "eye"]} /> View QR Code</a>
                                                                <a className="btn btn-secondary" href={"services/genqrcode/" + file.shareLink} download><FontAwesomeIcon icon={["fas", "download"]} /> Download QR Code</a>                                                            
                                                            </p>                                                            
                                                        </td>
                                                        <td>
                                                            <div className="m-2">
                                                                {
                                                                    (this.props.shareSettingBtn) ? 
                                                                        <button className="btn btn-light mr-3 mb-3" data-toggle="modal" data-target="#shareSettingModal" data-fileid={fileId} data-sharelink={file.shareLink} data-filename={file.filename}><FontAwesomeIcon icon={["fas", "cog"]} /> Share Setting</button>
                                                                    : 
                                                                        ''
                                                                }
                                                                <button className="btn btn-danger mr-3 mb-3" onClick={() => this.props.removeFile(fileId)}><FontAwesomeIcon icon={["fas", "trash-alt"]} /> Delete</button>
                                                            </div>
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

                <div className="modal fade" id="shareSettingModal" tabIndex="-1" role="dialog" aria-labelledby="shareSettingModalLabel" aria-hidden="true">
                    <div className="modal-dialog" role="document">
                        <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="shareSettingModalLabel"><FontAwesomeIcon icon={["fas", "cog"]} /> Share Setting</h5>
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div className="modal-body">
                            <div className="row">
                                <div className="col-md-12">
                                    <h5><span id="labelStatus"></span> Sharing File</h5>
                                    <label className="switch">
                                        <input id={"isChecked"} type="checkbox"/>
                                        <span className="slider round"></span>
                                    </label>
                                </div>
                                <div className="col-md-12" id="shareLinkDiv">
                                    <h5><span id="labelStatus"></span> Share Link</h5>
                                    <div className="input-group">
                                        <div className="input-group-prepend">
                                            <button className="btn btn-secondary" onClick={() => {
                                                this.refs.shareLinkInput.select();
                                                document.execCommand("Copy");
                                                Swal('Copied!!','Copy link to Clipboard success','success')
                                            }} data-toggle="tooltip" data-placement="top" title="Copy Link To Clipboard"><FontAwesomeIcon icon={["fas", "copy"]} /> Copy</button>
                                        </div>
                                        <input type="text" className="form-control" ref="shareLinkInput" id="shareLinkInput" onClick={(event) => {event.target.select()}}/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                            {/*<button type="button" className="btn btn-primary" onClick={() => alert('Share Setting Modal')}>Save changes</button>*/}
                        </div>
                        </div>
                    </div>
                </div>
                
            </div>
        )
    }
}