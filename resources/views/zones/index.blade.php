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
        Zone List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Zones</a></li>
        <li class="active">Zones</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				{{--<div class="box-header">--}}
					{{--<h3 class="box-title">Zones</h3>--}}
				{{--</div>--}}
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('zones.zone.create') }}" class="btn btn-success" title="Create New Zone">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($zones) == 0)
					<div class="text-center">
						<h4>No Zones Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Zone Name</th>
                            <th>Number of Region</th>
                            <th>Number of Territory</th>
                            <th>Number of DB House</th>
                            <th>Number of ASO</th>
                            <th>Number of Routes</th>
                        <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($zones as $zone)
                        <tr>
                            <td>{{ $zone->zone_name }}</td>
                            <td>{{$zone->tregion}}</td>
                            <td>{{$zone->tterritory}}</td>
                            <td>{{$zone->tdbhouse}}</td>
                            <td>{{$zone->taso}}</td>
                            <td>{{$zone->aroute}}</td>

                        <td>

                                <form method="POST" action="{!! route('zones.zone.destroy', $zone->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        {{--<a href="{{ route('zones.zone.show', $zone->id ) }}" class="btn btn-info" title="Show Zone">--}}
                                            {{--<span class="glyphicon glyphicon-open" aria-hidden="true"></span>--}}
                                        {{--</a>--}}
                                        <a href="{{ route('zones.zone.edit', $zone->id ) }}" class="btn btn-primary" title="Edit Zone">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Zone" onclick="return confirm(&quot;Delete Zone?&quot;)">
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
            {!! $zones->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection