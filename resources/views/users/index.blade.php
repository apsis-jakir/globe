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
      <h1>
        Data Tables
        <small>Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Users</a></li>
        <li class="active">Users</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Users</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('users.user.create') }}" class="btn btn-success" title="Create New User">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($users) == 0)
					<div class="text-center">
						<h4>No Users Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Password Key</th>
                            <th>Password Expire Days</th>
                            <th>Last Name</th>
                            <th>Mobile</th>
                            <th>Home Telephone</th>
                            <th>Username</th>
                            <th>Secret Question 1</th>
                            <th>Secret Question 2</th>
                            <th>Secret Question Ans 1</th>
                            <th>Secret Question Ans 2</th>
                            <th>Identification Type</th>
                            <th>Identification Number</th>
                            <th>Identification Expire Date</th>
                            <th>Date Of Birth</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                                                        <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->password }}</td>
                            <td>{{ $user->password_key }}</td>
                            <td>{{ $user->password_expire_days }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $user->home_telephone }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->secret_question_1 }}</td>
                            <td>{{ $user->secret_question_2 }}</td>
                            <td>{{ $user->secret_question_ans_1 }}</td>
                            <td>{{ $user->secret_question_ans_2 }}</td>
                            <td>{{ optional($user->identificationType)->id }}</td>
                            <td>{{ $user->identification_number }}</td>
                            <td>{{ $user->identification_expire_date }}</td>
                            <td>{{ $user->date_of_birth }}</td>

                            <td>

                                <form method="POST" action="{!! route('users.user.destroy', $user->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('users.user.show', $user->id ) }}" class="btn btn-info" title="Show User">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('users.user.edit', $user->id ) }}" class="btn btn-primary" title="Edit User">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete User" onclick="return confirm(&quot;Delete User?&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
@endsection