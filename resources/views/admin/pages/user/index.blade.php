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
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
                                            <i class="far fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-danger">
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

<!-- Modal -->
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
                        <label for="exampleInputEmail1">MSC Employee</label>
                        <select class="employeeList" data-live-search="true" id="addMemberSelect">
                            @foreach($employees as $e)
                                <option value="{{$e->Login}}" data-tokens="{{$e->EmpCode}}">{{$e->FullNameEng}}</option>
                            @endforeach
                        </select>
                        <small id="emailHelp" class="form-text text-muted">สามารถค้นหาได้จากรหัสพนักงาน หรือ ชื่อพนักงาน (ภาษาอังกฤษเท่านั้น)</small>
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

        $('#addMemberBtn').click(function(){
            var value = $('#addMemberSelect').val()
            $.post('{{asset("api/employee/store")}}', {login : value}, function(result){
                swal(
                    'The Internet?',
                    'That thing is still around?',
                    'question'
                )
            })
        })

        $('.employeeList').selectpicker();
    } );
</script>
@stop