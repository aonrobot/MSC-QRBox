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
        this.removeFiles = this.removeFiles.bind(this);
    }

    removeFiles(ids) {
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
				for(let id of ids){
					let login = this.state.login;
					axios.post('api/file/delete', {id, login}).then((response) => {
					if(response.status === 200){
							let files = this.state.files;
							files = files.filter((el) => (
								el.fileId != id
							))
							this.setState({files})
						}          
					}).catch(function (error) {
						Swal('ไม่สามารถลบรูปได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error')
						return 0;
					});
				}
				Swal(
					'Deleted!',
					'Your file has been deleted.',
					'success'
				)
            }
        })
    }

    removeFile(id) {
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
                let login = this.state.login;
                axios.post('api/file/delete', {id, login}).then((response) => {
                if(response.status === 200){
                        let files = this.state.files;
                        files = files.filter((el) => (
                            el.fileId != id
                        ))
                        this.setState({files})
                        Swal(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }          
                }).catch(function (error) {
                    Swal('ไม่สามารถลบรูปได้', 'กรุณาติดต่อผู้ดูแลระบบได้ที่เบอร์ 78452', 'error')
                });
            }
        })
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
                        <ListFile files={this.state.files} shareSettingBtn={true} removeFile={this.removeFile} removeFiles={this.removeFiles}/> 
                    :
                        <div className="d-flex flex-column align-self-center p-3">
                            <h1>Loading <FontAwesomeIcon icon={["fas", "sync"]} spin className="ml-1"/></h1>
                        </div>
                }
                
            </div>
        )
    }
}