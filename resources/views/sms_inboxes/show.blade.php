@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'SMS Inbox' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('sms_inboxes.sms_inbox.destroy', $smsInbox->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('sms_inboxes.sms_inbox.index') }}" class="btn btn-primary" title="Show">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('sms_inboxes.sms_inbox.create') }}" class="btn btn-success" title="Create">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('sms_inboxes.sms_inbox.edit', $smsInbox->id ) }}" class="btn btn-primary" title="Edit">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm(&quot;Are you sure you want to delete?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Sender</dt>
            <dd>{{ $smsInbox->sender }}</dd>
            <dt>Sms Content</dt>
            <dd>{{ $smsInbox->sms_content }}</dd>
            <dt>Sms Status</dt>
            <dd>{{ $smsInbox->sms_status }}</dd>
            <dt>Reason</dt>
            <dd>{{ $smsInbox->reason }}</dd>
            <dt>Is Active</dt>
            <dd>{{ ($smsInbox->is_active) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection