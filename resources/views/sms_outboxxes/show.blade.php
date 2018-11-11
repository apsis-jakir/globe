@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Sms Outboxx' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('sms_outboxxes.sms_outboxx.destroy', $smsOutboxx->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('sms_outboxxes.sms_outboxx.index') }}" class="btn btn-primary" title="Show All Sms Outboxx">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('sms_outboxxes.sms_outboxx.create') }}" class="btn btn-success" title="Create New Sms Outboxx">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('sms_outboxxes.sms_outboxx.edit', $smsOutboxx->id ) }}" class="btn btn-primary" title="Edit Sms Outboxx">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Sms Outboxx" onclick="return confirm(&quot;Delete Sms Outboxx??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Sms Receiver Number</dt>
            <dd>{{ $smsOutboxx->sms_receiver_number }}</dd>
            <dt>Sms Content</dt>
            <dd>{{ $smsOutboxx->sms_content }}</dd>
            <dt>Order Type</dt>
            <dd>{{ $smsOutboxx->order_type }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection