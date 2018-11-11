@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        @if(Session::has('success_message'))
            <div class="alert alert-success">
                <span class="glyphicon glyphicon-ok"></span>
                {!! session('success_message') !!}

                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        @endif

        <section class="content-header">
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Users</a></li>
                <li class="active">User List</li>
            </ol>
        </section>


        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Users</h3>

                        <div class="btn-group btn-group-sm pull-right" role="group">
                            <a href="{{ route('register') }}" class="btn btn-success" title="Create New User">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                    @if(count($users) == 0)
                        <div class="text-center">
                            <h4>No User Available!</h4>
                        </div>
                    @else
                        <div class="box-body">
                            <table id="user_list" class="table  responsive table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact No.</th>
                                    <th>Email</th>
                                    <th>Code</th>
                                    <th>User Name</th>
                                    <th>zone</th>
                                    <th>Region</th>
                                    <th>Territory</th>
                                    <th>DB House</th>
                                    <th>Routes</th>
                                    <th>Route ID</th>
                                    <th>User Type</th>
                                    <th style="text-align: right; width: 71px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name.' '.$user->last_name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->code }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->zname }}</td>
                                        <td>{{ $user->rname }}</td>
                                        <td>{{ $user->tname }}</td>
                                        <td>{{ $user->dname }}</td>
                                        <td>{{ getUsersRoutes($user->id) }}</td>
                                        <td>{{getUsersRoutesId($user->id)}}</td>
                                        <td>{{$user->user_type}}</td>
                                        <td>

                                            <form method="POST" action="{!! route('user.destroy', $user->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                <div class="btn-group btn-group-xs pull-right" role="group">
                                                    <a href="{{ route('user.show', $user->id ) }}" class="btn btn-info"
                                                       title="Show user">
                                                        <span class="glyphicon glyphicon-open"
                                                              aria-hidden="true"></span>
                                                    </a>
                                                    <a href="{{ route('user.edit', $user->id ) }}"
                                                       class="btn btn-primary" title="Edit user">
                                                        <span class="glyphicon glyphicon-pencil"
                                                              aria-hidden="true"></span>
                                                    </a>

                                                    <button type="submit" class="btn btn-danger" title="Delete user"
                                                            onclick="return confirm(&quot;Delete user?&quot;)">
                                                        <span class="glyphicon glyphicon-trash"
                                                              aria-hidden="true"></span>
                                                    </button>
                                                </div>

                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                </div>

                <div class="panel-footer">
                    {!! $users->render() !!}
                </div>

                @endif

            </div>
        </div>
    </div>
    <script>
        $('#user_list').DataTable({
            'paging'      : false,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            "scrollX": true
        })
    </script>
@endsection