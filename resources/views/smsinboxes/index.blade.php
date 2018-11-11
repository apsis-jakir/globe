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
        <small>Smsinboxes</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Smsinboxes</a></li>
        <li class="active">Smsinboxes</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Smsinboxes</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('smsinboxes.smsinbox.create') }}" class="btn btn-success" title="Create New Smsinbox">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($smsinboxes) == 0)
					<div class="text-center">
						<h4>No Smsinboxes Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Sms Inbox Name</th>
                            <th>Transaction Id</th>
                            <th>Sender</th>
                            <th>Sms Status</th>
                            <th>Sms Received</th>
                            <th>Replied At</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($smsinboxes as $smsinbox)
                        <tr>
                                                        <td>{{ $smsinbox->sms_inbox_name }}</td>
                            <td>{{ $smsinbox->transactionId }}</td>
                            <td>{{ $smsinbox->sender }}</td>
                            <td>{{ $smsinbox->sms_status }}</td>
                            <td>{{ $smsinbox->sms_received }}</td>
                            <td>{{ $smsinbox->replied_at }}</td>

                            <td>

                                <form method="POST" action="{!! route('smsinboxes.smsinbox.destroy', $smsinbox->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('smsinboxes.smsinbox.show', $smsinbox->id ) }}" class="btn btn-info" title="Show Smsinbox">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('smsinboxes.smsinbox.edit', $smsinbox->id ) }}" class="btn btn-primary" title="Edit Smsinbox">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Smsinbox" onclick="return confirm(&quot;Delete Smsinbox?&quot;)">
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
            {!! $smsinboxes->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection