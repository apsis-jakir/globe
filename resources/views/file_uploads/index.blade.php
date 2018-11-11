@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif
	
	<section class="content-header">
      <h1>
        Data Tables
        <small>File Uploads</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">File Uploads</a></li>
        <li class="active">File Uploads</li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">File Uploads</h3>
				</div>
				 <div class="btn-group btn-group-sm pull-right" role="group">
					<a href="{{ route('file_uploads.file_upload.create') }}" class="btn btn-success" title="Create New File Upload">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</div>
				@if(count($fileUploads) == 0)
					<div class="text-center">
						<h4>No File Uploads Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                                                    <th>Images</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($fileUploads as $fileUpload)
                        <tr>
                                                        <td>{{ basename($fileUpload->images) }}</td>

                            <td>

                                <form method="POST" action="{!! route('file_uploads.file_upload.destroy', $fileUpload->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('file_uploads.file_upload.show', $fileUpload->id ) }}" class="btn btn-info" title="Show File Upload">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('file_uploads.file_upload.edit', $fileUpload->id ) }}" class="btn btn-primary" title="Edit File Upload">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete File Upload" onclick="return confirm(&quot;Delete File Upload?&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            {!! $fileUploads->render() !!}
        </div>
        
        @endif
    
    </div>
    </div>
@endsection