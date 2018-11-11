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
        Territory List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Territories</a></li>
        <li class="active">Territories</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				{{--<div class="box-header">--}}
					{{--<h3 class="box-title">Territories</h3>--}}
				{{--</div>--}}
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('territories.territory.create') }}" class="btn btn-success" title="Create New Territory">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($territories) == 0)
					<div class="text-center">
						<h4>No Territories Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Territory Name</th>
                            <th>Number of DB House</th>
                            <th>Number of ASO</th>
                            <th>Number of Routes</th>

                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($territories as $territory)
                        <tr>
                            <td>{{ $territory->territory_name }}</td>
                            <td>{{ $territory->tdbhouse }}</td>
                            <td>{{ $territory->taso }}</td>
                            <td>{{ $territory->aroute }}</td>

                            <td>

                                <form method="POST" action="{!! route('territories.territory.destroy', $territory->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        {{--<a href="{{ route('territories.territory.show', $territory->id ) }}" class="btn btn-info" title="Show Territory">--}}
                                            {{--<span class="glyphicon glyphicon-open" aria-hidden="true"></span>--}}
                                        {{--</a>--}}
                                        <a href="{{ route('territories.territory.edit', $territory->id ) }}" class="btn btn-primary" title="Edit Territory">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Territory" onclick="return confirm(&quot;Delete Territory?&quot;)">
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

        {{--<div class="panel-footer">--}}
            {{--{!! $territories->render() !!}--}}
        {{--</div>--}}
        
        @endif
    
    </div>
    </div>
@endsection