@extends('admin.admin')

@section('css')
    <style>
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
            width: 100%;
        }
    </style>
@stop

@section('vendor_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle"></i> Add New User
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-light">
                List Of User
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>login</th>
                            <th>role</th>
                            <th>status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $m)
                                <tr>
                                    <td>{{$m->FullNameEng}}</td>                                    
                                    <td>{{$m->loginUser}}</td>
                                    <td class="text-nowrap">{{$m->role}}</td>
                                    <td>{{$m->status}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary editMemberBtn" data-id="{{$m->memberId}}" data-toggle="modal" data-target="#editModal">
                                            <i class="far fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-danger removeMemberBtn" data-id="{{$m->memberId}}">
                                            <i class="far fa-trash-alt"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Member -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel"><i class="fas fa-plus-circle"></i> New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="memberSelect"><b>เลือกพนักงาน</b></label>
                        <select class="form-control employeeList" data-live-search="true" id="memberSelect">
                            @foreach($employees as $e)
                                <option value="{{$e->Login}}" data-tokens="{{$e->EmpCode}}">{{$e->FullNameEng}}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">สามารถค้นหาได้จากรหัสพนักงาน หรือ ชื่อพนักงาน (ภาษาอังกฤษเท่านั้น)</small>
                    </div>
                    <div class="form-group">
                        <label for="roleSelect"><b>Role</b></label>
                        <select class="form-control" id="roleSelect">
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput"><b>Maximum file per once upload</b> <small>จำนวนไฟล์ทั้งหมดที่สามารถ upload ได้ใน 1 ครั้ง</small></label> 
                        <input type="number" class="form-control" id="maxFiles" placeholder="Number" value="3">
                        <small class="form-text text-muted">0 is unlimited</small>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput"><b>Maximum file size per file (Mb)</b> <small>ขนาดของไฟล์ใหย๋ที่สุดที่สามารถ upload ได้</small></label>
                        <input type="number" class="form-control" id="maxFileSize" placeholder="Number" value="1024">
                        <small class="form-text text-muted">0 is unlimited</small>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput"><b>Maximum total file size per once upload (Mb)</b> <small>ขนาดของไฟล์ทั้งหมดต่อการ upload ได้ใน 1 ครั้ง</small></label>
                        <input type="number" class="form-control" id="maxTotalFileSize" placeholder="Number" value="1024">
                        <small class="form-text text-muted">0 is unlimited</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addMemberBtn">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Member -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-plus-circle"></i> Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="memberLabel"><b>คุณ</b></label>
                        <h5 id="memberLabel"></h5>
                    </div>
                    <div class="form-group">
                        <label for="roleSelectEdit"><b>Role</b></label>
                        <select class="form-control" id="roleSelectEdit">
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput"><b>Maximum file per once upload</b> <small>จำนวนไฟล์ทั้งหมดที่สามารถ upload ได้ใน 1 ครั้ง</small></label> 
                        <input type="number" class="form-control" id="maxFilesEdit" placeholder="Number">
                        <small class="form-text text-muted">0 is unlimited</small>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput"><b>Maximum file size per file (Mb)</b> <small>ขนาดของไฟล์ใหย๋ที่สุดที่สามารถ upload ได้</small></label>
                        <input type="number" class="form-control" id="maxFileSizeEdit" placeholder="Number">
                        <small class="form-text text-muted">0 is unlimited</small>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput"><b>Maximum total file size per once upload (Mb)</b> <small>ขนาดของไฟล์ทั้งหมดต่อการ upload ได้ใน 1 ครั้ง</small></label>
                        <input type="number" class="form-control" id="maxTotalFileSizeEdit" placeholder="Number">
                        <small class="form-text text-muted">0 is unlimited</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateMemberBtn">Update</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('vendor_javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@stop

@section('javascript')
<script>
    $(document).ready(function() {

        $('#userTable').DataTable({
            "columns": [
                {
                    "width": "25%"
                },
                null,
                null,
                null,
                {
                    "width": "15%"
                }
            ]
        });

        //Add Member
        $('#addMemberBtn').prop('disabled', true);

        $('#addMemberBtn').click(function(){
            var memberSelect = $('#memberSelect').val()
            var roleSelect = $('#roleSelect').val()
            var maxFiles = $('#maxFiles').val()
            var maxFileSize = $('#maxFileSize').val()
            var maxTotalFileSize = $('#maxTotalFileSize').val()

            $.post('{{asset("api/employee/store")}}', {
                    login : memberSelect,
                    role : roleSelect,
                    maxFiles : maxFiles,
                    maxFileSize : maxFileSize,
                    maxTotalFileSize : maxTotalFileSize
            }).done(function(){
                swal('เพิ่มข้อมูลเรียบร้อยครับ', '', 'success').then(function(r){
                    if(r){
                        location.reload()
                    }
                })
            }).fail(function(){
                swal('พบปัญหา', 'อาจมีปัญหาบางอย่างระหว่างเพิ่มข้อมูล เช่น Internet มีปัญหา', 'error')
            })
        })

        $('.employeeList').selectpicker();
        
        $('.employeeList').on('hidden.bs.select', function (e) {
            $('#addMemberBtn').prop('disabled', false);
        });

        $('#addModal').on('hidden.bs.modal', function (e) {
            $('#addMemberBtn').prop('disabled', true);
        })

        //Update Member
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            
            var loginUser = button.data('id');
            var userInfo = null;

            $.ajax({
                url : '{{asset("api/employee/show")}}/' + loginUser,
                type: "GET",
                async : false,
                dataType : "json",
                error : function(xhr,status,error){
                    swal('พบปัญหา', status, 'error')
                },
                success : function(result){
                    userInfo = result; 
                },
                cache : true
            });

            $('#memberLabel').text(userInfo.FullNameEng);
            $('#roleSelectEdit').val(userInfo.role);
            $('#maxFilesEdit').val(userInfo.maxFiles);
            $('#maxFileSizeEdit').val(userInfo.maxFileSize);
            $('#maxTotalFileSizeEdit').val(userInfo.maxTotalFileSize);

            modal.find('#updateMemberBtn').click(function(){
                var roleSelectEdit = $('#roleSelectEdit').val()
                var maxFilesEdit = $('#maxFilesEdit').val()
                var maxFileSizeEdit = $('#maxFileSizeEdit').val()
                var maxTotalFileSizeEdit = $('#maxTotalFileSizeEdit').val()

                $.post('{{asset("api/employee/update")}}/' + loginUser, {
                        role : roleSelectEdit,
                        maxFiles : maxFilesEdit,
                        maxFileSize : maxFileSizeEdit,
                        maxTotalFileSize : maxTotalFileSizeEdit
                }).done(function(){
                    swal('เพิ่มข้อมูลเรียบร้อยครับ', '', 'success').then(function(r){
                        if(r){
                            location.reload()
                        }
                    })
                }).fail(function(){
                    swal('พบปัญหา', 'อาจมีปัญหาบางอย่างระหว่างเพิ่มข้อมูล เช่น Internet มีปัญหา', 'error')
                })
            })
        })

        //Remove Member
        $('.removeMemberBtn').click(function(){
            var that = $(this);
            $.get('{{asset("api/employee/destroy")}}/' + $(this).data('id')).done(function(){
                swal('ลบข้อมูลเรียบร้อยครับ', '', 'success').then(function(r){
                    if(r){
                        that.closest("tr").remove()
                    }
                })
                
            }).fail(function(){
                swal('พบปัญหา', 'อาจมีปัญหาบางอย่างระหว่างเพิ่มข้อมูล เช่น Internet มีปัญหา', 'error')
            })
        })

    });
</script>
@stop