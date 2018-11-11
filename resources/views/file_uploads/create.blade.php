@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <span class="pull-left">
                <h4 class="mt-5 mb-5">Create New File Upload</h4>
            </span>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('file_uploads.file_upload.index') }}" class="btn btn-primary" title="Show All File Upload">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                </a>
            </div>

        </div>

        <div class="panel-body">
        
            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('file_uploads.file_upload.store') }}" accept-charset="UTF-8" id="create_file_upload_form" name="create_file_upload_form" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include ('file_uploads.form', [
                                        'fileUpload' => null,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
                    </div>
                </div>

            </form>

        </div>
    </div>
 </div>
@endsection


