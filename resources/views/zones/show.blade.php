@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Zone' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('zones.zone.destroy', $zone->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('zones.zone.index') }}" class="btn btn-primary" title="Show All Zone">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('zones.zone.create') }}" class="btn btn-success" title="Create New Zone">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('zones.zone.edit', $zone->id ) }}" class="btn btn-primary" title="Edit Zone">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Zone" onclick="return confirm(&quot;Delete Zone??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Zone Name</dt>
            <dd>{{ $zone->zone_name }}</dd>

        </dl>
        {{--<dl class="dl-horizontal">--}}
            {{--<dt>Number of Region</dt>--}}
            {{--<dd>{{ $zone->zone_name }}</dd>--}}

        {{--</dl>--}}

    </div>
</div>
</div>

@endsection