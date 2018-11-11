@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($division->name) ? $division->name : 'Division' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('divisions.division.destroy', $division->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('divisions.division.index') }}" class="btn btn-primary" title="Show All Division">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('divisions.division.create') }}" class="btn btn-success" title="Create New Division">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('divisions.division.edit', $division->id ) }}" class="btn btn-primary" title="Edit Division">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Division" onclick="return confirm(&quot;Delete Division??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $division->name }}</dd>
            <dt>Countries</dt>
            <dd>{{ optional($division->country)->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $division->description }}</dd>
            <dt>Created At</dt>
            <dd>{{ $division->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $division->updated_at }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($division->creator)->name }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($division->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($division->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection