@extends('admin.admin')

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
                                <th>login user</th>
                                <th>role</th>
                                <th>maxFiles</th>
                                <th>maxFileSize</th>
                                <th>maxTotalFileSize</th>
                                <th>status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $m)
                                    <tr>
                                        <td>{{$m->loginUser}}</td>
                                        <td class="text-nowrap">{{$m->role}}</td>
                                        <td>{{$m->maxFiles}} Files</td>
                                        <td>{{$m->maxFileSize}} Mb</td>
                                        <td>{{$m->maxTotalFileSize}} Mb</td>
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
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Add</button>
        </div>
        </div>
    </div>
    </div>

@stop

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "width": "15%"
                    }
                ]
            });
        } );
    </script>
@stop