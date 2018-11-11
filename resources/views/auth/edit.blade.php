@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">General Elements</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default">
            <form method="POST" action="{{ route('user.update',$user->id) }}">
                {{ csrf_field() }}
                <div class="box-header with-border">
                    <h3 class="box-title">Edit</h3>
                    <div class="btn-group btn-group-sm pull-right" role="group">
                        <a href="{{ route('users') }}" class="btn btn-success" title="Create New User">
                            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">{{ __('First Name') }}</label>
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name" value="{{ old('name',optional($user)->name) }}" placeholder="Enter First Name" tabindex="1"
                                       required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="last_name">{{ __('Last Name') }}</label>
                                <input id="last_name" type="text"
                                       class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                       name="last_name" value="{{ old('last_name',optional($user)->last_name) }}" placeholder="Enter Last Name"
                                       tabindex="2" required>

                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="mobile">{{ __('Mobile') }}</label>
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                       name="mobile" maxlength="11" value="{{ old('mobile',optional($user)->mobile) }}" placeholder="Enter Mobile" tabindex="3"
                                       required>
                                @if ($errors->has('mobile'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Religion</label>
                                <select name="religion_id" class="form-control select2" tabindex="4"
                                        style="width: 100%;">
                                    <option value="1">Muslim</option>
                                    <option value="2">Hindsm</option>
                                    <option value="3">Cristian</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control select2" tabindex="5" style="width: 100%;">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date Of Birth:</label>

                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input id="datepicker" type="text"
                                           class="form-control pull-right{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}"
                                           name="date_of_birth" value="{{ old('date_of_birth',optional($user)->date_of_birth) }}"
                                           placeholder="Enter DOB" tabindex="6" required>

                                    @if ($errors->has('date_of_birth'))
                                        <span class="invalid-feedback">
                                    <strong>{{ $errors->first('date_of_birth') }}</strong>
                                </span>
                                    @endif
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email"  value="{{ old('email',optional($user)->email) }}" tabindex="7"
                                       placeholder="Enter Email Address" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label for="username">{{ __('Username') }}</label>
                                <input id="username" type="text"
                                       class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                       name="username"  value="{{ old('username',optional($user)->username) }}" tabindex="8"
                                       placeholder="Enter username" required>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password"  placeholder="Enter Password" tabindex="9" >

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation"  tabindex="10" placeholder="Enter Confirm Password"
                                       >
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <select name="location_id" class="form-control select2" tabindex="11"
                                        style="width: 100%;">
                                    <option value="1">Dhaka</option>
                                    <option value="2">Rajsahi</option>
                                </select>
                                @if ($errors->has('location_id'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('location_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Line Manager</label>
                                <select name="line_manager_id" class="form-control select2" tabindex="12"
                                        style="width: 100%;">
                                    <option>Select Line Manager</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"{{ old('line_manager_id') == $user->line_manager_id ? 'selected' : ''}}>{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('line_manager_id'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('line_manager_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department_id" class="form-control select2" tabindex="13"
                                        style="width: 100%;">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}"{{ old('department_id') == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('department_id'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('department_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Designation</label>
                                <select name="designation_id" class="form-control select2" tabindex="14"
                                        style="width: 100%;">
                                    <option value="">Select designation</option>
                                    @foreach($designations as $desination)
                                        <option value="{{$desination->id}}"{{ old('designation_id') == $desination->id ? 'selected' : ''}}>{{$desination->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('designation_id'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('designation_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Default Module</label>
                                <select name="default_module_id" class="form-control select2" tabindex="15"
                                        style="width: 100%;">
                                    @foreach($allmodules as $md)
                                        <option value="{{$md->id}}"{{ old('default_module_id') == $md->id ? 'selected' : ''}}>{{$md->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('default_module_id'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('default_module_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Module Privilege</label>
                                <select name="user_module_id[]" class="form-control select2 select2-hidden-accessible"
                                        multiple=""  style="width: 100%;" tabindex="-1"
                                        aria-hidden="true">
                                    <option value="" selected>Select One</option>
                                    @foreach($allmodules as $md)
                                        <option value="{{$md->id}}">{{$md->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Level Privilege</label>
                                <select name="user_level_id[]" class="form-control select2 select2-hidden-accessible"
                                        multiple="" style="width: 100%;" tabindex="-1"
                                        aria-hidden="true">
                                    <option value="" selected>Select One</option>
                                    @foreach($user_levels as $levels)
                                        <option value="{{$levels->id}}">{{$levels->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select2" tabindex="4"
                                        style="width: 100%;">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                @if ($errors->has('status'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info">Save And Continue</button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-info">Save And List</button>
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-info">Import From Excel</button>
                </div>
            </form>
        </div>
    </section>
</div>
@stop