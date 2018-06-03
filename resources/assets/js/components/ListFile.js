import React, {Component} from 'react';
import ReactDOMServer from 'react-dom/server';
import { Link } from 'react-router-dom'

import FontAwesomeIcon from '@fortawesome/react-fontawesome';

import Util from '../library/Util';
import ModalShareSetting from './ModalShareSetting';
import ModalChangeFile from './ModalChangeFile';

import 'datatables.net-bs4/js/dataTables.bootstrap4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.css';

import 'datatables.net-responsive-bs4/js/responsive.bootstrap4.min';
import 'datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css';

export default class ListFile extends Component {

    // Why can't use this.state.files ??????
    // Why can't use update state.files ?????? with setState
    //DOMException: Failed to execute 'removeChild' on 'Node': The node to be removed is not a child of this node.

    constructor (props) {
        super(props);
        this.state = {
            token: document.head.querySelector('meta[name="csrf-token"]').content,
            login: document.head.querySelector('meta[name="basic-auth"]').content,
            setting: this.props.setting ? this.props.setting : {},
            rows: []
        }

        this.oTable = null;        
    }

    componentWillMount () {}

    componentWillReceiveProps (nextProps){}

    componentDidMount () {

        let $table = $(this.table);
        this.oTable = $table.DataTable({

            responsive: true,
            columns: [
                {
                    width: "1%",
                    orderable: false,
                    searchable: false
                },
                {
                    title: 'File Name'
                },
                {
                    width: "15%"                    
                },
                {
                    title: '',
                    width: "15%",
                    orderable: false,
                    searchable: false
                }
            ]
        })

        var $searchBox = $(this.searchBox);
        $searchBox.keyup(() => {
            this.oTable.search($searchBox.val()).draw();
        })

    }

    removeFileAPI(id, row, callback){
        console.log(row)
        axios.post('api/file/delete', {id, login: this.state.login}).then((response) => {
            if(response.status === 200 && response.data.fileId !== undefined){
                // Why can't use this.state.files ??????
                let files = this.props.files;
                let delId = response.data.fileId;
                files = (files[0].id == undefined) ? files.filter((el) => (el.fileId != delId)) : files.filter((el) => (el.id != delId));

                // Why can't use update state.files ??????
                // this.setState({files});
                //use oTable.row to remove row instead setFiles
                this.oTable.row(row).remove().draw();
                //this.props.setFiles(files);
                callback()
            }          
        }).catch(function (error) {
            console.log(error)
            Swal('ไม่สามารถลบรูปได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error').then((r) => {
                if(r){
                    location.reload();
                }
            });
        });
    }

    removeFile (id, e) {
        let row = $(e.target).parents('tr')
        Swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                this.removeFileAPI(id, row, () => {
                    Swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                });
            }
        })
    }

    removeFiles (ids, rows) {
		Swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
				for(let index in ids){
                    this.removeFileAPI(ids[index], rows[index], () => {
                        Swal(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                    });
					/*axios.post('api/file/delete', {id : ids[index], login}).then((response) => {
					    if(response.status === 200){
							let files = this.props.files;
							files = files.filter((el) => (
								el.fileId != ids[index]
							))
                            this.oTable.row(rows[index]).remove().draw();
						}          
					}).catch(function (error) {
						console.log(error)                        
						Swal('ไม่สามารถลบรูปได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error').then((r) => {
                            if(r){
                                location.reload();
                            }
                        });
					});*/
				}

                $("input[name=chkBoxAllFile]:checked").prop('checked', false);
                $("input[name=chkBoxFile]:checked").prop('checked', false);

				Swal(
					'Deleted!',
					'Your file has been deleted.',
					'success'
				)
            }
        })
    }

    deleteAll () {
        let checkedIds = [];
        let checkedRows = [];
        $("input[name=chkBoxFile]:checked").each(function(){
            checkedIds.push($(this).val());
            checkedRows.push($(this).parents('tr'));
        });

        if( checkedIds.length <= 0 ) {
            Swal('กรุณาเลือกอย่างน้อย 1 ไฟล์', '', 'error');
        } else {
            this.removeFiles(checkedIds, checkedRows);
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

    checkAllFile () {
        $('input[name=chkBoxFile]').prop('checked', $('input[name=chkBoxAllFile]').prop('checked'));
    }

    render () {

        // Why can't use this.state.files ??????
        // console.log('ListFile render (state files)', this.state.files)
        console.log('ListFile render (props files)', this.props.files, this.props.files.length)

        let table = (
            <table className="table table-bordered display responsive" width="100%" ref={el => this.table = el}>
                <thead className="thead-dark">
                    <tr>
                        <td scope="col">
                            <input type="checkbox" name="chkBoxAllFile" onClick={() => this.checkAllFile()}/>
                        </td>
                        <td scope="col">File Name</td>
                        <td scope="col">Size</td>
                        <td scope="col">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    {
                        // Why can't use this.state.files ??????
                        this.props.files.map((file) => {
                                let fileId = (file.id === undefined) ? file.fileId : file.id;
                                return (
                                    //(file.shareLink === undefined) ? '' :
                                    <tr key={fileId}>
                                        <td>
                                            <input type="checkbox" name="chkBoxFile" value={fileId} />
                                        </td>                                                        
                                        <td>
                                            <a href={"file/" + fileId + '/' + file.filename} target="_blank" src={file.filename}>
                                                {file.filename} 
                                            </a><br/>
                                            <h6>
                                                <small className="mr-2">Updated at : {moment(file.created_at).format('DD/MM/YYYY, h:mm:ss a')}</small>
                                            </h6>
                                        </td>
                                        <td>
                                            { (file.fileSize !== undefined || file.fileSize !== null) ?  Util.capacityUnit(file.fileSize) : ''}
                                        </td>
                                        <td>
                                            {
                                                (this.state.setting.actionBtn) ? 
                                                    <div className="dropdown show">
                                                        <a className="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </a>
                                                        <div className="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                            <h6 className="dropdown-header">File Action</h6>
                                                            <a className="dropdown-item" href={"services/genqrcode/" + file.shareLink + '/' + file.filename} target={"_blank"}><FontAwesomeIcon icon={["fas", "eye"]} /> View QR Code</a>
                                                            <a className="dropdown-item" href={"services/genqrcode/" + file.shareLink + '/' + file.filename} download><FontAwesomeIcon icon={["fas", "download"]} /> Download QR Code</a>  
                                                            <div className="dropdown-divider"></div>
                                                            {
                                                                (this.state.setting.shareSettingBtn) ? 
                                                                    <button className="btn btn-light ml-3 mb-3" data-toggle="modal" data-target="#shareSettingModal" data-fileid={fileId} data-sharelink={file.shareLink} data-filename={file.filename}><FontAwesomeIcon icon={["fas", "cog"]} /> Share File</button>
                                                                : 
                                                                    ''
                                                            }
                                                            <button className="btn btn-light ml-3 mb-3" data-toggle="modal" data-target="#changeFileModal" data-login={file.loginUser}  data-fileid={fileId} data-filename={file.filename}> <FontAwesomeIcon icon={["fas", "exchange-alt"]} /> Change File</button>
                                                            <div className="dropdown-divider"></div>
                                                            <button className="btn btn-danger ml-3" onClick={(e) => this.removeFile(fileId, e)}><FontAwesomeIcon icon={["fas", "trash-alt"]} /> Delete</button>
                                                        </div>
                                                    </div>
                                                :
                                                    <button className="btn btn-danger ml-3" onClick={(e) => this.removeFile(fileId, e)}><FontAwesomeIcon icon={["fas", "trash-alt"]} /> Delete</button>
                                            }
                                            
                                        </td>
                                    </tr>
                                )
                            } 
                        )
                    }
                </tbody>
            </table>
        )

        let listFile = (
            <div className="table-responsive p-1">
                {
                    (this.state.setting.searchBox) ? 
                        <div className="input-group input-group-lg mb-4">
                            <div className="wrapSearchBox">
                                <span className="mr-3"><FontAwesomeIcon className="searchBox mr-2" icon={["fas", "search"]}/> Search File</span>
                                <input type="text" className="searchBox" ref={el => this.searchBox = el}/>   
                            </div>
                        </div>
                    : 
                        ''
                }

                { table }

                {
                    (this.state.setting.removeAllBtn) ? 
                        <button type="button" className="btn btn-danger deleteAll" onClick={ () => this.deleteAll() } >  <FontAwesomeIcon icon={["fas", "trash"]} /> Delete All </button>
                    : 
                        ''
                }
                
            </div>
        )
        
        let showTable = (
            // Why can't use this.state.files ??????
            // Problem is hereeeeeeeeeee can't use props.files
            (this.props.files.length <= 0) ?
                // Why i can't use <div> instead <p> here!! fucking <div>
                <span className="text-left mb-3">
                    <h5><FontAwesomeIcon className="searchBox mr-2" icon={["fas", "blind"]}/> Now. You don't have any file.</h5>
                    <hr/>                                           
                </span>
            :
            
            listFile
        )

        return (
            
            <div className="d-flex flex-column align-self-center p-3 w-100">
                <div className="text-left mb-3">
                    <h3>QRBox File</h3>
                    <hr/>                                           
                </div>

                { showTable }

                <ModalShareSetting/>
                <ModalChangeFile login={this.state.login} token={this.state.token}/>
            </div>
        )
    }
}