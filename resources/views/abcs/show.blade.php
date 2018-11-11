@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($abc->name) ? $abc->name : 'Abc' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('abcs.abc.destroy', $abc->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('abcs.abc.index') }}" class="btn btn-primary" title="Show All Abc">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('abcs.abc.create') }}" class="btn btn-success" title="Create New Abc">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('abcs.abc.edit', $abc->id ) }}" class="btn btn-primary" title="Edit Abc">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Abc" onclick="return confirm(&quot;Delete Abc??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $abc->name }}</dd>
            <dt>Description</dt>
            <dd>{{ $abc->description }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($abc->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection