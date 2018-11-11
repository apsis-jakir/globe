@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Smsinbox' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('smsinboxes.smsinbox.destroy', $smsinbox->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('smsinboxes.smsinbox.index') }}" class="btn btn-primary" title="Show All Smsinbox">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('smsinboxes.smsinbox.create') }}" class="btn btn-success" title="Create New Smsinbox">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('smsinboxes.smsinbox.edit', $smsinbox->id ) }}" class="btn btn-primary" title="Edit Smsinbox">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Smsinbox" onclick="return confirm(&quot;Delete Smsinbox??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Sms Inbox Name</dt>
            <dd>{{ $smsinbox->sms_inbox_name }}</dd>
            <dt>Transaction Id</dt>
            <dd>{{ $smsinbox->transactionId }}</dd>
            <dt>Sender</dt>
            <dd>{{ $smsinbox->sender }}</dd>
            <dt>Sms Status</dt>
            <dd>{{ $smsinbox->sms_status }}</dd>
            <dt>Sms Received</dt>
            <dd>{{ $smsinbox->sms_received }}</dd>
            <dt>Replied At</dt>
            <dd>{{ $smsinbox->replied_at }}</dd>
            <dt>Sms Content</dt>
            <dd>{{ $smsinbox->sms_content }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($smsinbox->creator)->name }}</dd>
            <dt>Created At</dt>
            <dd>{{ $smsinbox->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $smsinbox->updated_at }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($smsinbox->updater)->name }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($smsinbox->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection