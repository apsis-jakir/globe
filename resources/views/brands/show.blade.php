@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Brand' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('brands.brand.destroy', $brand->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('brands.brand.index') }}" class="btn btn-primary" title="Show All Brand">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('brands.brand.create') }}" class="btn btn-success" title="Create New Brand">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('brands.brand.edit', $brand->id ) }}" class="btn btn-primary" title="Edit Brand">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Brand" onclick="return confirm(&quot;Delete Brand??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Categories</dt>
            <dd>{{ optional($brand->category)->category_name }}</dd>
            <dt>Brand Name</dt>
            <dd>{{ $brand->brand_name }}</dd>
            <dt>Segment</dt>
            <dd>{{ $brand->segment }}</dd>
            <dt>Description</dt>
            <dd>{{ $brand->description }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($brand->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection