@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Distribution House' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('distribution_houses.distribution_house.destroy', $distributionHouse->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('distribution_houses.distribution_house.index') }}" class="btn btn-primary" title="Show All Distribution House">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('distribution_houses.distribution_house.create') }}" class="btn btn-success" title="Create New Distribution House">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('distribution_houses.distribution_house.edit', $distributionHouse->id ) }}" class="btn btn-primary" title="Edit Distribution House">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Distribution House" onclick="return confirm(&quot;Delete Distribution House??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Zones</dt>
            <dd>{{ optional($distributionHouse->zone)->zone_name }}</dd>
            <dt>Regions</dt>
            <dd>{{ optional($distributionHouse->region)->region_name }}</dd>
            <dt>Territories</dt>
            <dd>{{ optional($distributionHouse->territory)->territory_name }}</dd>
            <dt>Market Name</dt>
            <dd>{{ $distributionHouse->market_name }}</dd>
            <dt>Code</dt>
            <dd>{{ $distributionHouse->code }}</dd>
            <dt>Point Name</dt>
            <dd>{{ $distributionHouse->point_name }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection