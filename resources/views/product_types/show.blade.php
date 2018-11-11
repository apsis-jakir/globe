@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Product Type' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('product_types.product_type.destroy', $productType->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('product_types.product_type.index') }}" class="btn btn-primary" title="Show All Product Type">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('product_types.product_type.create') }}" class="btn btn-success" title="Create New Product Type">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('product_types.product_type.edit', $productType->id ) }}" class="btn btn-primary" title="Edit Product Type">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Product Type" onclick="return confirm(&quot;Delete Product Type??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Category Name</dt>
            <dd>{{ $productType->category_name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($productType->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection