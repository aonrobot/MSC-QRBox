import React, {Component} from 'react';

import FontAwesomeIcon from '@fortawesome/react-fontawesome'

export default class ModalShareSetting extends Component {

    componentDidMount () {
        $('#shareSettingModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var fileId = button.data('fileid')
            var fileName = button.data('filename')             
            var shareLink = button.data('sharelink') 
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

    render(){
        return(
            <div className="modal fade" id="shareSettingModal" tabIndex="-1" role="dialog" aria-labelledby="shareSettingModalLabel" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered" role="document">
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
                                    <h6><span id="labelStatus"></span> Sharing File</h6>
                                    <label className="switch">
                                        <input id={"isChecked"} type="checkbox"/>
                                        <span className="slider round"></span>
                                    </label>
                                </div>
                                <div className="col-md-12" id="shareLinkDiv">
                                    <h6><span id="labelStatus"></span> Share Link</h6>
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
        )
    }
}