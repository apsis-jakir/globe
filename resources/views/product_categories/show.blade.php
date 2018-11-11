@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($productCategory->name) ? $productCategory->name : 'Product Category' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('product_categories.product_category.destroy', $productCategory->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('product_categories.product_category.index') }}" class="btn btn-primary" title="Show All Product Category">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('product_categories.product_category.create') }}" class="btn btn-success" title="Create New Product Category">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('product_categories.product_category.edit', $productCategory->id ) }}" class="btn btn-primary" title="Edit Product Category">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Product Category" onclick="return confirm(&quot;Delete Product Category??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $productCategory->name }}</dd>
            <dt>Product Brands</dt>
            <dd>{{ optional($productCategory->productBrand)->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $productCategory->description }}</dd>
            <dt>Created At</dt>
            <dd>{{ $productCategory->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $productCategory->updated_at }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($productCategory->creator)->name }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($productCategory->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($productCategory->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection