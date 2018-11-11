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
        <small>Unions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Unions</a></li>
        <li class="active">Unions</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Unions</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('unions.union.create') }}" class="btn btn-success" title="Create New Union">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($unions) == 0)
					<div class="text-center">
						<h4>No Unions Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Name</th>
                            <th>Countries</th>
                            <th>Divisions</th>
                            <th>Districts</th>
                            <th>Upazilas</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Is Active</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($unions as $union)
                        <tr>
                                                        <td>{{ $union->name }}</td>
                            <td>{{ optional($union->country)->name }}</td>
                            <td>{{ optional($union->division)->name }}</td>
                            <td>{{ optional($union->district)->name }}</td>
                            <td>{{ optional($union->upazila)->name }}</td>
                            <td>{{ optional($union->creator)->name }}</td>
                            <td>{{ optional($union->updater)->name }}</td>
                            <td>{{ ($union->is_active) ? 'Yes' : 'No' }}</td>

                            <td>

                                <form method="POST" action="{!! route('unions.union.destroy', $union->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('unions.union.show', $union->id ) }}" class="btn btn-info" title="Show Union">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('unions.union.edit', $union->id ) }}" class="btn btn-primary" title="Edit Union">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Union" onclick="return confirm(&quot;Delete Union?&quot;)">
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
            {!! $unions->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection