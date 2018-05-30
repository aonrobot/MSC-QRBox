import React, {Component} from 'react';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

//Filepond
import {FilePond} from 'react-filepond';

export default class ModalChangeFile extends Component {

    constructor(props) {
        super(props);
    }

    handleInit() {
        this.pond._pond.destroy();
    }

    componentDidMount() {
        $('#changeFileModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var login = button.data('login')
            
            var fileId = button.data('fileid')
            var fileName = button.data('filename')             
            var modal = $(this)

            let changeBtn = modal.find('#changeBtn');
            changeBtn.click(() => {
                var file_data = modal.find('.filepond--wrapper input[type=file]').prop('files');
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                let fd = new FormData();
                fd.append('file_data', file_data[0]);
                fd.append('file_id', fileId);
                fd.append('login', login)
                axios.post('api/file/update/' + fileId, fd, config).then((response) => {
                    if(response.status === 200){
                        Swal('Update Success', '','success').then((r) => {
                            if(r){
                                location.reload();
                            }
                        })
                    }
                }).catch(function (error) {
                    Swal('ไม่สามารถเปลี่ยนไฟล์ได้', 'เนื่องจากคุณไม่มีสิทธิ์ในการเปลี่ยนแปลงไฟล์นี้ กรุณาติดต่อ MIS','error').then((r) => {
                        if(r){
                            location.reload();
                        }
                    });
                });;
            })
                    
        })

        $('#changeFileModal').on('hidden.bs.modal', function (event) {
            var modal = $(this)
            modal.find('#changeBtn').unbind('click');             
        })
    }    

    render(){

        let uploadUIlabel = 'Drag & Drop ไฟล์ของคุณ <b>(3 ไฟล์)</b> ลงตรงพื้นที่สีเทา หรือ <span class="badge badge-pill badge-primary filepond--label-action"> กด Browse ที่นี่ </span>';
        
        return(
            <div className="modal fade" id="changeFileModal" tabIndex="-1" role="dialog" aria-labelledby="changeFileModalLabel" aria-hidden="true">
                <div className="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="changeFileModalLabel"><FontAwesomeIcon icon={["fas", "cog"]} /> Change File</h5>
                            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div className="modal-body">
                            <div className="d-flex flex-column justify-content-center h-100 p-4">
                                <FilePond   maxFiles={300}
                                            maxFileSize={'1024MB'}
                                            maxTotalFileSize={'1024MB'}
                                            acceptedFileTypes={['image/*', 'video/mp4', 'audio/*', 'application/pdf']}
                                            ref={ref => this.pond = ref}
                                            server={{
                                                url: 'api/uploadBox',
                                                process: {
                                                    headers: {
                                                        'X-CSRF-TOKEN': this.props.token,
                                                        'BASIC-AUTH': this.props.login
                                                    }
                                                },
                                                revert: {
                                                    headers: {
                                                        'X-CSRF-TOKEN': this.props.token,
                                                        'BASIC-AUTH': this.props.login
                                                    }
                                                }
                                            }}
                                            labelIdle={uploadUIlabel}
                                            instantUpload={true}
                                            oninit={() => this.handleInit()}
                                ></FilePond>
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" className="btn btn-primary" id="changeBtn">Change</button>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}