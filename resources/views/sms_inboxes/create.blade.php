@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">

        <div class="panel-heading clearfix">
            
            <span class="pull-left">
                <h4 class="mt-5 mb-5">New SMS</h4>
            </span>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('sms_inboxes.sms_inbox.index') }}" class="btn btn-primary" title="Show All SMS Inbox">
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

            <form method="POST" action="{{ route('sms_inboxes.sms_inbox.store') }}" accept-charset="UTF-8" id="create_sms_inbox_form" name="create_sms_inbox_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('sms_inboxes.form', [
                                        'smsInbox' => null,
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


