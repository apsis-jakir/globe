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
                    <div class="box-body" style="padding: 10px 40px;">

                        <form id="order_details_frm" action="{{URL::to('update-secondary-order')}}" method="post" onkeypress="return event.keyCode != 13;">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div style="border-bottom: 1px solid #ccc">
                                        <h3>{{$orders_info->point_name.' - '.$orders_info->market_name}}</h3>
                                        <h5>House Phone : {{$orders_info->dh_phone}}</h5>
                                        <h5>ASO/SO Name : {{$orders_info->requester_name}}</h5>
                                        <h5>ASO/SO Phone : {{$orders_info->requester_phone}}</h5>
                                        <h5 style="overflow: hidden; font-weight: bold; color:#00f;">
                                            <span style="float: left;">Request Date : {{date('d-m-Y',strtotime($orders_info->order_date))}}</span>

                                            <span style="float: right;padding-left:10px;">  Successful Memo : {{$orders_info->total_no_of_memo}} </span>
                                            <span style="float: right;padding-left:10px;">  Visited Outlet : {{$orders_info->visited_outlet}} </span>
                                            <span style="float: right;padding-left:10px;">Total Outlet : {{$orders_info->total_outlet}}</span>

                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="hidden" name="asm_rsm_id" value="{{$orders_info->aso_id}}">
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
                                    <th>Quantity</th>
                                    <th>SKU Wise Memo</th>
                                    <th>BCP</th>
                                    </thead>
                                    <tbody>
                                    @php($grand_total = 0)
                                    @php($caseTotal = 0)
                                    @php($convertArrayOrder = collect($orders)->toArray())
                                    @foreach($memo as $cat_key=>$cat_value)
                                        @foreach($cat_value as $sku_key=>$sku_value)
                                            @php($key = array_search($sku_key, array_column($convertArrayOrder, 'short_name')))
                                            @php($key!==false ? $case= $convertArrayOrder[$key]->case : $case =0)
                                            @php($key!==false ? $memo= $convertArrayOrder[$key]->no_of_memo : $case =0)
                                            @php($key!==false ? $sku_memo= $convertArrayOrder[$key]->sku_memo : $sku_memo =0)
                                            @php( $sub_total =  $case * get_case_price($sku_key))
                                            @php($caseTotal += $case)
                                            @php($grand_total += $sub_total)
                                            <tr>
                                                <td>{{$cat_key.' '.$sku_value.'('.$sku_key.')'}}</td>
                                                <td>
                                                    <input type="hidden" name="short_name[]" value="{{$sku_key}}">
                                                    <input
                                                            class="order_quantity"
                                                            style="width: 100px;"
                                                            name="quantity[{{$sku_key}}]"
                                                            type="text"
                                                            pack_size="{{get_pack_size($sku_key)}}"
                                                            oldValue="{{$case}}"
                                                            value="{{$case}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="memo[{{$sku_key}}]" value="{{$memo}}">
                                                </td>
                                                <td>
                                                    {{number_format(($sku_memo/$orders_info->total_no_of_memo)*100,2).'%'}}
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                    @endforeach
<!--                                    <tr>
                                    <th style="text-align: left">Total</th>
                                    <th class="grand_total" style="text-align: left">
                                        {{number_format($grand_total,2)}}
                                    </th>
                                    </tr>-->
                                    
                                    <tr>
                                    <th style="text-align: left">Total</th>
                                    <th class="grand_total" style="text-align: left">
                                        {{$caseTotal}}
                                    </th>
                                    </tr>
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
        <script>
            $(document).ready(function () {
                function packSizeCheck(order_quantity, pack_size) {
                    var decimal = order_quantity.toString().split(".")[1];
                    var result = true;
                    if (decimal) {
                        if (decimal.length > 1) {
                            if (parseInt(decimal) >= pack_size) {
                                result = false;
                            }
                        }
                        else {
                            if ((parseInt(decimal) * 10) >= pack_size) {
                                result = false;
                            }
                        }
                    }
                    return result;
                }


                $(document).on('input', '.order_quantity', function () {
                    var order_quentity = $(this).val();
                    var oldValue = parseFloat($(this).attr('oldValue'));
                    var pack_size = $(this).attr('pack_size');

                    var request_quantity = parseFloat($(this).parent().parent().find('.request_quantity').text());
                    var current_balance = parseFloat($('.current_balance').text().replace(',', ''));
                    var rate = $(this).parent().parent().find('.price_rate').text();
                    var sku = $(this).parent().find('input').first().attr('value');

                    var pValidation = packSizeCheck(order_quentity, pack_size);
                    if (pValidation) {

                        var sub_total = order_quentity * rate;
                        $(this).parent().parent().find('.sub_total').text(sub_total.toFixed(2));

                        var grand_total = 0;
                        $('.order_quantity').each(function () {
                            var get_sub_total = parseFloat($(this).parent().parent().find('.sub_total').text());
                            var get_total_quantity = parseFloat($(this).val());
                            grand_total = grand_total + get_sub_total;
                        });
                        $('.grand_total').text(parseFloat(grand_total).toFixed(2));
                        if (grand_total > current_balance) {
                            var htm = '<div class="alert alert-danger alert-dismissible">';
                            htm += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                            htm += 'You have exceeded the current balance.<br/>';
                            htm += '</div>';
                            $('.showMessage').html(htm);
                        }
                        else {
                            $('.showMessage').html('');
                        }
                    }
                    else {
                        alert('Invalid quantity');
                        $(this).val(oldValue);
                    }

                });
            });
        </script>
@endsection