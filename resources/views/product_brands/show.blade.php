@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($productBrand->name) ? $productBrand->name : 'Product Brand' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('product_brands.product_brand.destroy', $productBrand->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('product_brands.product_brand.index') }}" class="btn btn-primary" title="Show All Product Brand">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('product_brands.product_brand.create') }}" class="btn btn-success" title="Create New Product Brand">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('product_brands.product_brand.edit', $productBrand->id ) }}" class="btn btn-primary" title="Edit Product Brand">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Product Brand" onclick="return confirm(&quot;Delete Product Brand??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $productBrand->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $productBrand->updated_at }}</dd>
            <dt>Name</dt>
            <dd>{{ $productBrand->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $productBrand->description }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($productBrand->creator)->name }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($productBrand->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($productBrand->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection