@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($user->name) ? $user->name : 'User' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('users.user.destroy', $user->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('users.user.index') }}" class="btn btn-primary" title="Show All User">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('users.user.create') }}" class="btn btn-success" title="Create New User">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('users.user.edit', $user->id ) }}" class="btn btn-primary" title="Edit User">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete User" onclick="return confirm(&quot;Delete User??&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
            <dt>Password</dt>
            <dd>{{ $user->password }}</dd>
            <dt>Password Key</dt>
            <dd>{{ $user->password_key }}</dd>
            <dt>Password Expire Days</dt>
            <dd>{{ $user->password_expire_days }}</dd>
            <dt>Last Name</dt>
            <dd>{{ $user->last_name }}</dd>
            <dt>Mobile</dt>
            <dd>{{ $user->mobile }}</dd>
            <dt>Home Telephone</dt>
            <dd>{{ $user->home_telephone }}</dd>
            <dt>Username</dt>
            <dd>{{ $user->username }}</dd>
            <dt>Secret Question 1</dt>
            <dd>{{ $user->secret_question_1 }}</dd>
            <dt>Secret Question 2</dt>
            <dd>{{ $user->secret_question_2 }}</dd>
            <dt>Secret Question Ans 1</dt>
            <dd>{{ $user->secret_question_ans_1 }}</dd>
            <dt>Secret Question Ans 2</dt>
            <dd>{{ $user->secret_question_ans_2 }}</dd>
            <dt>Identification Type</dt>
            <dd>{{ optional($user->identificationType)->id }}</dd>
            <dt>Identification Number</dt>
            <dd>{{ $user->identification_number }}</dd>
            <dt>Identification Expire Date</dt>
            <dd>{{ $user->identification_expire_date }}</dd>
            <dt>Date Of Birth</dt>
            <dd>{{ $user->date_of_birth }}</dd>
            <dt>Gender</dt>
            <dd>{{ $user->gender }}</dd>
            <dt>Religion</dt>
            <dd>{{ optional($user->religion)->id }}</dd>
            <dt>Father Name</dt>
            <dd>{{ $user->father_name }}</dd>
            <dt>Father Occupation</dt>
            <dd>{{ optional($user->fatherOccupation)->id }}</dd>
            <dt>Mother Name</dt>
            <dd>{{ $user->mother_name }}</dd>
            <dt>Mother Occupation</dt>
            <dd>{{ optional($user->motherOccupation)->id }}</dd>
            <dt>Bank Account Number</dt>
            <dd>{{ $user->bank_account_number }}</dd>
            <dt>Bank</dt>
            <dd>{{ optional($user->bank)->id }}</dd>
            <dt>Bank Branch</dt>
            <dd>{{ $user->bank_branch }}</dd>
            <dt>Last Login Date Time</dt>
            <dd>{{ $user->last_login_date_time }}</dd>
            <dt>Default Module</dt>
            <dd>{{ optional($user->defaultModule)->id }}</dd>
            <dt>User Image</dt>
            <dd>{{ $user->user_image }}</dd>
            <dt>Address</dt>
            <dd>{{ $user->address }}</dd>
            <dt>Is Reliever</dt>
            <dd>{{ $user->is_reliever }}</dd>
            <dt>Reliever To</dt>
            <dd>{{ $user->reliever_to }}</dd>
            <dt>Reliever Start Datetime</dt>
            <dd>{{ $user->reliever_start_datetime }}</dd>
            <dt>Reliever End Datetime</dt>
            <dd>{{ $user->reliever_end_datetime }}</dd>
            <dt>Line Manager</dt>
            <dd>{{ optional($user->lineManager)->id }}</dd>
            <dt>Designation</dt>
            <dd>{{ optional($user->designation)->id }}</dd>
            <dt>Department</dt>
            <dd>{{ optional($user->department)->name }}</dd>
            <dt>Location</dt>
            <dd>{{ optional($user->location)->id }}</dd>
            <dt>Default Vehicle</dt>
            <dd>{{ optional($user->defaultVehicle)->id }}</dd>
            <dt>Default Url</dt>
            <dd>{{ $user->default_url }}</dd>
            <dt>Default Language</dt>
            <dd>{{ optional($user->defaultLanguage)->id }}</dd>
            <dt>Joining Date</dt>
            <dd>{{ $user->joining_date }}</dd>
            <dt>Emergency Contact Person Name</dt>
            <dd>{{ $user->emergency_contact_person_name }}</dd>
            <dt>Emergency Contact Relation</dt>
            <dd>{{ $user->emergency_contact_relation }}</dd>
            <dt>Emergency Contact Number</dt>
            <dd>{{ $user->emergency_contact_number }}</dd>
            <dt>Remember Token</dt>
            <dd>{{ $user->remember_token }}</dd>
            <dt>Territories</dt>
            <dd>{{ optional($user->territory)->territory_name }}</dd>
            <dt>Distribution Houses</dt>
            <dd>{{ optional($user->distributionHouse)->market_name }}</dd>
            <dt>Rfu1</dt>
            <dd>{{ $user->rfu1 }}</dd>
            <dt>Rfu2</dt>
            <dd>{{ $user->rfu2 }}</dd>
            <dt>Zones</dt>
            <dd>{{ optional($user->zone)->zone_name }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($user->creator)->name }}</dd>
            <dt>Created At</dt>
            <dd>{{ $user->created_at }}</dd>
            <dt>Updated By</dt>
            <dd>{{ optional($user->updater)->name }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $user->updated_at }}</dd>
            <dt>Status</dt>
            <dd>{{ $user->status }}</dd>

        </dl>

    </div>
</div>
</div>

@endsection