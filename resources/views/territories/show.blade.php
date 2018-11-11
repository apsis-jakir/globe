@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Territory' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('territories.territory.destroy', $territory->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('territories.territory.index') }}" class="btn btn-primary" title="Show All Territory">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('territories.territory.create') }}" class="btn btn-success" title="Create New Territory">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('territories.territory.edit', $territory->id ) }}" class="btn btn-primary" title="Edit Territory">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Territory" onclick="return confirm(&quot;Delete Territory??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Territory Name</dt>
            <dd>{{ $territory->territory_name }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection