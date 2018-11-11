@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                Promotions List
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Settings</a></li>
                <li class="active">Promotions</li>
            </ol>
        </section>



        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    {{--<div class="box-header">--}}
                        {{--<h3 class="box-title">Promotions List </h3>--}}
                    {{--</div>--}}
                    <div class="btn-group btn-group-sm pull-right" role="group">
                        <a href="{{URL::to('promotions')}}" class="btn btn-success" title="Create New Sms Inbox">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                    </div>
                    <div class="box-body">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>{{ \Session::get('success') }}</p>
                            </div><br />
                        @endif
                        <table id="example2" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
                            <thead>
                            <tr>
                                <th>Package Short Name</th>
                                <th>Package Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th style="text-align: right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($promotions as $promotion)
                                <tr>
                                    <td>{{$promotion->shortname}}</td>
                                    <td>{{$promotion->pack_name}}</td>
                                    <td>{{date('d-m-Y',strtotime($promotion->start_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($promotion->end_date))}}</td>
                                    <td>
                                        <a onclick="return confirm('Are you sure?')" style="text-decoration: underline;" href="{{URL::to('activeInactive/'.$promotion->id.'/'.$promotion->is_active)}}">{{($promotion->is_active)?'Active':'Inactive'}}</a>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-xs pull-right" role="group">

                                            <a href="{{URL::to('packageDetails/'.$promotion->id)}}" class="btn btn-primary" title="Edit Zone">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a style="color: #fff;" href="{{URL::to('deletePromotions/'.$promotion->id)}}" class="btn btn-danger" title="Remove Promotions">
                                                <i class="fa fa-remove" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>



            </div>
        </div>
        <script>
        </script>
@endsection