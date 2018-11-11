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
        SKU List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">SKU's</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				{{--<div class="box-header">--}}
					{{--<h3 class="box-title">SKU's</h3>--}}
				{{--</div>--}}
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('skues.skue.create') }}" class="btn btn-success" title="Create New Skue">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($skues) == 0)
					<div class="text-center">
						<h4>No SKU's Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Brands</th>
                            <th>SKU Name</th>
                            <th>Short Name</th>
                            <th>House Price</th>
                            <th>Sell Price</th>

                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($skues as $skue)
                        <tr>
                            <td>{{ optional($skue->brand)->brand_name }}</td>
                            <td>{{ $skue->sku_name }}</td>
                            <td>{{ $skue->short_name }}</td>
                            <td>{{ $skue->house_price }}</td>
                            <td>{{ $skue->price }}</td>

                            <td>

                                <form method="POST" action="{!! route('skues.skue.destroy', $skue->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        {{--<a href="{{ route('skues.skue.show', $skue->id ) }}" class="btn btn-info" title="Show Skue">--}}
                                            {{--<span class="glyphicon glyphicon-open" aria-hidden="true"></span>--}}
                                        {{--</a>--}}
                                        <a href="{{ route('skues.skue.edit', $skue->id ) }}" class="btn btn-primary" title="Edit Skue">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Skue" onclick="return confirm(&quot;Delete Skue?&quot;)">
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
            {{--{!! $skues->render() !!}--}}
        {{--</div>--}}
        
        @endif
    
    </div>
    </div>
@endsection