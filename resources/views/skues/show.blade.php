@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Skue' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('skues.skue.destroy', $skue->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('skues.skue.index') }}" class="btn btn-primary" title="Show All Skue">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('skues.skue.create') }}" class="btn btn-success" title="Create New Skue">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('skues.skue.edit', $skue->id ) }}" class="btn btn-primary" title="Edit Skue">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Skue" onclick="return confirm(&quot;Delete Skue??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Brands</dt>
            <dd>{{ optional($skue->brand)->brand_name }}</dd>
            <dt>Sku Name</dt>
            <dd>{{ $skue->sku_name }}</dd>
            <dt>Short Name</dt>
            <dd>{{ $skue->short_name }}</dd>
            <dt>House Price</dt>
            <dd>{{ $skue->house_price }}</dd>
            <dt>Sell Price</dt>
            <dd>{{ $skue->price }}</dd>
            <dt>Description</dt>
            <dd>{{ $skue->description }}</dd>
            <dt>Ordering</dt>
            <dd>{{ $skue->ordering }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($skue->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection