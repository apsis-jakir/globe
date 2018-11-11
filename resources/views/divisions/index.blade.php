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
        <small>Divisions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Divisions</a></li>
        <li class="active">Divisions</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Divisions</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('divisions.division.create') }}" class="btn btn-success" title="Create New Division">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($divisions) == 0)
					<div class="text-center">
						<h4>No Divisions Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Name</th>
                            <th>Countries</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Is Active</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($divisions as $division)
                        <tr>
                                                        <td>{{ $division->name }}</td>
                            <td>{{ optional($division->country)->name }}</td>
                            <td>{{ optional($division->creator)->name }}</td>
                            <td>{{ optional($division->updater)->name }}</td>
                            <td>{{ ($division->is_active) ? 'Yes' : 'No' }}</td>

                            <td>

                                <form method="POST" action="{!! route('divisions.division.destroy', $division->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('divisions.division.show', $division->id ) }}" class="btn btn-info" title="Show Division">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('divisions.division.edit', $division->id ) }}" class="btn btn-primary" title="Edit Division">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Division" onclick="return confirm(&quot;Delete Division?&quot;)">
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
            {!! $divisions->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection