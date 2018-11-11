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
        Distributor House List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Distribution Houses</a></li>
        <li class="active">Distribution Houses</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				{{--<div class="box-header">--}}
					{{--<h3 class="box-title">Distribution Houses</h3>--}}
				{{--</div>--}}
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('distribution_houses.distribution_house.create') }}" class="btn btn-success" title="Create New Distribution House">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($distributionHouses) == 0)
					<div class="text-center">
						<h4>No Distribution Houses Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Zones</th>
                            <th>Regions</th>
                            <th>Territories</th>
                            <th>Market Name</th>
                            <th>Code</th>
                            <th>Point Name</th>
                            <th>No. Of ASO</th>
                            <th>NO. Of Routes</th>
                            <th>Current Balance</th>

                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($distributionHouses as $distributionHouse)
                        <tr>
                                                        <td>{{ optional($distributionHouse->zone)->zone_name }}</td>
                            <td>{{ optional($distributionHouse->region)->region_name }}</td>
                            <td>{{ optional($distributionHouse->territory)->territory_name }}</td>
                            <td>{{ $distributionHouse->market_name }}</td>
                            <td>{{ $distributionHouse->code }}</td>
                            <td>{{ $distributionHouse->point_name }}</td>
                            <td>{{ $distributionHouse->taso }}</td>
                            <td>{{ $distributionHouse->aroute }}</td>
                            <td>{{ $distributionHouse->current_balance }}</td>

                            <td>

                                <form method="POST" action="{!! route('distribution_houses.distribution_house.destroy', $distributionHouse->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        {{--<a href="{{ route('distribution_houses.distribution_house.show', $distributionHouse->id ) }}" class="btn btn-info" title="Show Distribution House">--}}
                                            {{--<span class="glyphicon glyphicon-open" aria-hidden="true"></span>--}}
                                        {{--</a>--}}
                                        <a href="{{ route('distribution_houses.distribution_house.edit', $distributionHouse->id ) }}" class="btn btn-primary" title="Edit Distribution House">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Distribution House" onclick="return confirm(&quot;Delete Distribution House?&quot;)">
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
            {{--{!! $distributionHouses->render() !!}--}}
        {{--</div>--}}
        
        @endif
    
    </div>
    </div>
@endsection