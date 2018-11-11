@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'File Upload' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('file_uploads.file_upload.destroy', $fileUpload->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('file_uploads.file_upload.index') }}" class="btn btn-primary" title="Show All File Upload">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('file_uploads.file_upload.create') }}" class="btn btn-success" title="Create New File Upload">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('file_uploads.file_upload.edit', $fileUpload->id ) }}" class="btn btn-primary" title="Edit File Upload">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete File Upload" onclick="return confirm(&quot;Delete File Upload??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Images</dt>
            <dd>{{ basename($fileUpload->images) }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection