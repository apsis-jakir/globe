@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
    @endif
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Profile
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">User</a></li>
                <li class="active">User profile</li>
            </ol>
        </section>

        <!-- Main content -->

        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle"
                                 src="{{URL::to('public/'.Auth::user()->user_image)}}" class="img-circle"
                                 alt="User Image">
                            <h3 class="profile-username text-center"><?php echo Auth::user()->username; ?></h3>

                            <p class="text-muted text-center">Code: <?php echo Auth::user()->code; ?></p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Name</b> <a class="pull-right"><?php echo Auth::user()->name; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="pull-right"><?php echo Auth::user()->email; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Designation</b> <a
                                            class="pull-right"><?php echo $profileData[0]->designation;?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Location</b> <a class="pull-right"><?php echo $profileData[0]->location;?></a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->


                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a class="active" href="#settings" data-toggle="tab">Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form class="form-horizontal" method="POST" action="{{ route('changepassword') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-3 control-label">Old Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" name="current-password" class="form-control"
                                                   id="password" required
                                                   placeholder="Enter old password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-3 control-label">Old Password</label>

                                        <div class="col-sm-9">
                                            <input type="password" name="new-password" class="form-control"
                                                   id="newpassword" required
                                                   placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-3 control-label">Confirm Password</label>

                                        <div class="col-sm-9">
                                            <input type="password" name="new-password_confirmation" class="form-control"
                                                   id="confirmnewpassword" required
                                                   placeholder="Enter confirm password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
@stop