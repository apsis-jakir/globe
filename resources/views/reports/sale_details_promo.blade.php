@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                Package Details
            </h1>
            {!! $breadcrumb !!}
        </section>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body" style="padding: 10px 40px;">

                        <form data-toggle="validator" role="form" id="order_details_frm" action="{{URL::to('update-promotional-sale')}}" method="post" onkeypress="return event.keyCode != 13;">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div style="border-bottom: 1px solid #ccc">
                                        <h3>{{$details[0]->point_name.' - '.$details[0]->market_name}}</h3>
                                        <h5>ASO/SO Name : {{$details[0]->sender_name}}</h5>
                                        <h5>ASO/SO Phone : {{$details[0]->sender_phone}}</h5>
                                        <h5 style="overflow: hidden;">
                                            <span style="float: left;">Request Date : {{date('d-m-Y',strtotime($details[0]->order_date))}}</span>

                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="hidden" name="id" value="{{$details[0]->id}}">
                                </div>
                            </div>
                            <div class="showMessage"></div>
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                    <th>Package Name</th>
                                    <th>Quantity</th>
                                    <th>Package Wise Memo</th>
                                    </thead>
                                    <tbody>
                                    @foreach($details as $package)
                                        <tr>
                                            <td>{{$package->short_name}}</td>
                                            <td>
                                                <div class="form-group">
                                                <input
                                                        required
                                                        pattern="^[0-9]\d*(\.\d+)?$"
                                                        class="form-control order_quantity"
                                                        style="width: 100px;"
                                                        name="quantity[{{$package->short_name}}]"
                                                        type="text"
                                                        oldValue="{{$package->case}}"
                                                        value="{{$package->case}}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="width: 100px;" required pattern="^[0-9]\d*(\.\d+)?$" class="form-control" type="text" name="memo[{{$package->short_name}}]" value="{{$package->no_of_memo}}">
                                                </div>
                                            </td>
                                        </tr>
                                     @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="col-lg-12 text-right">
                                @if((Auth::user()->user_type == 'devlopment') || (Auth::user()->user_type == 'admin'))
                                    <input class="btn btn-primary" type="submit" value="Save">
                                @endif

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
@endsection