@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                Order Details
            </h1>
            {!! $breadcrumb !!}
        </section>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    {{--<div class="box-header" style="overflow: hidden">--}}
                        {{--<span class="box-title" style="float: left;">Order Details</span>--}}

                    {{--</div>--}}
                    <div class="box-body" style="padding: 10px 40px;">

                        <form id="order_details_frm" action="{{URL::to('primary-sales-create')}}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div style="border-bottom: 1px solid #ccc">
                                        <h3>{{$orders_info->point_name.' - '.$orders_info->market_name}}</h3>
                                        <h5>House Phone : {{$orders_info->dh_phone}}</h5>
                                        <h5>Requester Name : {{$orders_info->requester_name}}</h5>
                                        <h5>Requester Phone : {{$orders_info->requester_phone}}</h5>
                                        <h5 style="overflow: hidden;">
                                            <span style="float: left;">Requester Date : {{$orders_info->order_date}}</span>
                                            <span style="float: right; color: #0000F0; font-weight: bold;">
                                                Deposited Amount : <span>{{$orders_info->order_da}}</span>&nbsp;&nbsp;
                                                Current Balance : <span class="current_balance">{{$orders_info->current_balance}}</span>
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="hidden" name="asm_rsm_id" value="{{$orders_info->asm_rsm_id}}">
                                    <input type="hidden" name="sender_name" value="{{$orders_info->requester_name}}">
                                    <input type="hidden" name="sender_phone" value="{{$orders_info->requester_phone}}">
                                    <input type="hidden" name="dh_name" value="{{$orders_info->dh_name}}">
                                    <input type="hidden" name="dh_phone" value="{{$orders_info->dh_phone}}">
                                    <input type="hidden" name="order_id" value="{{$orders_info->id}}">
                                    <input type="hidden" name="order_date" value="{{$orders_info->order_date}}">
                                </div>
                            </div>
                            <div class="showMessage"></div>
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>SKU</th>
                                        <th>Request Quantity</th>
                                        <th>Order Quantity</th>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            @if($order->quantity > 0)
                                                <tr>
                                                    <td>{{$order->brand_name.' '.$order->sku_name.'('.$order->short_name.')'}}</td>
                                                    <td class="request_quantity">{{$order->quantity}}</td>
                                                    <td>
                                                        <input type="hidden" name="short_name[]" value="{{$order->short_name}}">
                                                        <input
                                                                class="order_quantity"
                                                                style="width: 100px;"
                                                                name="quantity[{{$order->short_name}}]"
                                                                type="number"
                                                                oldValue="{{$order->quantity}}"
                                                                value="{{$order->quantity}}">
                                                    </td>
                                                </tr>

                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="col-lg-12 text-right">
                                <input class="btn btn-primary" type="submit" value="Save">
                            </div>
                        </form>
                    </div>


                </div>



            </div>
        </div>
        <script>
            $(document).on('input','.order_quantity',function () {
                var order_quentity = $(this).val();
                var oldValue = parseInt($(this).attr('oldValue'));
                var request_quantity = parseInt($(this).parent().parent().find('.request_quantity').text());
                var current_balance = parseInt($('.current_balance').text());
                var t = $(this);
                $.ajax({
                    url:'{{URL::to("check-distribution-balack")}}',
                    type:'POST',
                    data:$('#order_details_frm').serialize(),
                    success:function (data) {
                        var htm = '<div class="alert alert-danger alert-dismissible">';
                            htm += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';


                        var current_value = parseInt(data);
                        if(data > current_balance)
                        {
                            htm += 'You have exit the current balance.<br/>';
                        }
                        if(order_quentity > request_quantity)
                        {
                            htm += 'You can not exit the request quantity.';
                            //t.val(oldValue);
                        }
                        htm += '</div>';
                        $('.showMessage').html(htm);
                    }
                });

            });
        </script>
@endsection