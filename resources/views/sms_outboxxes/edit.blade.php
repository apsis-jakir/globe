@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
  
        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ !empty($title) ? $title : 'Sms Outboxx' }}</h4>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('sms_outboxxes.sms_outboxx.index') }}" class="btn btn-primary" title="Show All Sms Outboxx">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('sms_outboxxes.sms_outboxx.create') }}" class="btn btn-success" title="Create New Sms Outboxx">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
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

            <form method="POST" action="{{ route('sms_outboxxes.sms_outboxx.update', $smsOutboxx->id) }}" id="edit_sms_outboxx_form" name="edit_sms_outboxx_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('sms_outboxxes.form', [
                                        'smsOutboxx' => $smsOutboxx,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection