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
        <small>Product Categories</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Product Categories</a></li>
        <li class="active">Product Categories</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Product Categories</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('product_categories.product_category.create') }}" class="btn btn-success" title="Create New Product Category">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($productCategories) == 0)
					<div class="text-center">
						<h4>No Product Categories Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Name</th>
                            <th>Product Brands</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Is Active</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($productCategories as $productCategory)
                        <tr>
                                                        <td>{{ $productCategory->name }}</td>
                            <td>{{ optional($productCategory->productBrand)->name }}</td>
                            <td>{{ optional($productCategory->creator)->name }}</td>
                            <td>{{ optional($productCategory->updater)->name }}</td>
                            <td>{{ ($productCategory->is_active) ? 'Yes' : 'No' }}</td>

                            <td>

                                <form method="POST" action="{!! route('product_categories.product_category.destroy', $productCategory->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('product_categories.product_category.show', $productCategory->id ) }}" class="btn btn-info" title="Show Product Category">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('product_categories.product_category.edit', $productCategory->id ) }}" class="btn btn-primary" title="Edit Product Category">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Product Category" onclick="return confirm(&quot;Delete Product Category?&quot;)">
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
            {!! $productCategories->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection