@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($upazila->name) ? $upazila->name : 'Upazila' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('upazilas.upazila.destroy', $upazila->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('upazilas.upazila.index') }}" class="btn btn-primary" title="Show All Upazila">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('upazilas.upazila.create') }}" class="btn btn-success" title="Create New Upazila">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('upazilas.upazila.edit', $upazila->id ) }}" class="btn btn-primary" title="Edit Upazila">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Upazila" onclick="return confirm(&quot;Delete Upazila??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $upazila->name }}</dd>
            <dt>Countries</dt>
            <dd>{{ optional($upazila->country)->name }}</dd>
            <dt>Divisions</dt>
            <dd>{{ optional($upazila->division)->name }}</dd>
            <dt>Districts</dt>
            <dd>{{ optional($upazila->district)->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $upazila->description }}</dd>
            <dt>Created At</dt>
            <dd>{{ $upazila->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $upazila->updated_at }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($upazila->creator)->name }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($upazila->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($upazila->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection