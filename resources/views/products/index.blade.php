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
        <small>Products</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Products</a></li>
        <li class="active">Products</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Products</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('products.product.create') }}" class="btn btn-success" title="Create New Product">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($products) == 0)
					<div class="text-center">
						<h4>No Products Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Key Word</th>
                            <th>Brands</th>
                            <th>Skues</th>
                            {{--<th>Price</th>--}}
                            {{--<th>Quantity</th>--}}
                            {{--<th>Pack Size</th>--}}

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                                                        <td>{{ $product->key_word }}</td>
                            <td>{{ optional($product->brand)->brand_name }}</td>
                            <td>{{ optional($product->skue)->sku_name }}</td>
                            {{--<td>{{ $product->price }}</td>--}}
                            {{--<td>{{ $product->quantity }}</td>--}}
                            {{--<td>{{ $product->pack_size }}</td>--}}

                            <td>

                                <form method="POST" action="{!! route('products.product.destroy', $product->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('products.product.show', $product->id ) }}" class="btn btn-info" title="Show Product">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('products.product.edit', $product->id ) }}" class="btn btn-primary" title="Edit Product">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Product" onclick="return confirm(&quot;Delete Product?&quot;)">
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
            {!! $products->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection