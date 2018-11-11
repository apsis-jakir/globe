@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($district->name) ? $district->name : 'District' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('districts.district.destroy', $district->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('districts.district.index') }}" class="btn btn-primary" title="Show All District">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('districts.district.create') }}" class="btn btn-success" title="Create New District">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('districts.district.edit', $district->id ) }}" class="btn btn-primary" title="Edit District">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete District" onclick="return confirm(&quot;Delete District??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Created At</dt>
            <dd>{{ $district->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $district->updated_at }}</dd>
            <dt>Name</dt>
            <dd>{{ $district->name }}</dd>
            <dt>Divisions</dt>
            <dd>{{ optional($district->division)->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $district->description }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($district->creator)->name }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($district->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($district->is_active) ? 'Yes' : 'No' }}</dd>
            <dt>Countries</dt>
            <dd>{{ optional($district->country)->name }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection