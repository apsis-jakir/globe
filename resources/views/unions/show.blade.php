@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($union->name) ? $union->name : 'Union' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('unions.union.destroy', $union->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('unions.union.index') }}" class="btn btn-primary" title="Show All Union">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('unions.union.create') }}" class="btn btn-success" title="Create New Union">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('unions.union.edit', $union->id ) }}" class="btn btn-primary" title="Edit Union">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Union" onclick="return confirm(&quot;Delete Union??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $union->name }}</dd>
            <dt>Countries</dt>
            <dd>{{ optional($union->country)->name }}</dd>
            <dt>Divisions</dt>
            <dd>{{ optional($union->division)->name }}</dd>
            <dt>Districts</dt>
            <dd>{{ optional($union->district)->name }}</dd>
            <dt>Upazilas</dt>
            <dd>{{ optional($union->upazila)->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $union->description }}</dd>
            <dt>Created At</dt>
            <dd>{{ $union->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $union->updated_at }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($union->creator)->name }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($union->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($union->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection