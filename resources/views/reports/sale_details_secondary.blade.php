@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                {{$pageTitle}}
            </h1>
            {!! $breadcrumb !!}
        </section>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body" style="padding: 10px 40px;">

                        <form id="order_details_frm" action="{{URL::to('secondary-sales-create')}}" method="post" onkeypress="return event.keyCode != 13;">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div style="border-bottom: 1px solid #ccc">
                                        <h3>{{$sales_info->point_name.' - '.$sales_info->market_name}}</h3>
                                        <h5>House Phone : {{$sales_info->dh_phone}}</h5>
                                        <h5>ASO/SO Name : {{$sales_info->sender_name}}</h5>
                                        <h5>ASO/SO Phone : {{$sales_info->sender_phone}}</h5>

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="hidden" name="id" value="{{$sales_info->id}}">
                                    <input type="hidden" name="aso_id" value="{{$sales_info->aso_id}}">
                                    <input type="hidden" name="route_id" value="{{$sales_info->sale_route_id}}">
                                    <input type="hidden" name="sender_name" value="{{$sales_info->sender_name}}">
                                    <input type="hidden" name="sender_phone" value="{{$sales_info->sender_phone}}">
                                    <input type="hidden" name="dh_id" value="{{$sales_info->dbid}}">
                                    <input type="hidden" name="dh_name" value="{{$sales_info->dh_name}}">
                                    <input type="hidden" name="dh_phone" value="{{$sales_info->dh_phone}}">
                                    <input type="hidden" name="order_id" value="{{$sales_info->order_id}}">
                                    <input type="hidden" name="order_date" value="{{$sales_info->order_date}}">
                                    <input type="hidden" name="current_balance" value="{{$sales_info->current_balance}}">
                                </div>
                            </div>
                            <div class="showMessage"></div>
                            <div class="row">

                                <table class="table table-bordered">
                                    <thead>
                                    <th colspan="2" style="text-align: center">Product Details</th>
                                    <th>Order Quantity</th>
                                    <th>Sale Quantity</th>
                                    <th style="text-align: right">Rate</th>
                                    <th style="text-align: right">Sub Total</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $grand_total = 0;
                                    foreach ($memo as $k => $v) {
                                        $sl = 0;
                                        foreach ($v as $vk => $vv) {
//                                            debug($sales,1);
                                            $convertArrayOrder = collect($sales)->toArray();
                                            $key = array_search($vk, array_column($convertArrayOrder, 'short_name'));
                                            $inputValue = ($key!==false ? $convertArrayOrder[$key]->case : 0);
                                            $sub_total =  $inputValue * get_case_price($vk,false);
                                            $grand_total += $sub_total;
                                            if ($sl == 0) {
                                                echo '<tr><td rowspan="' . count($v) . '" style="text-align: left; vertical-align: middle;">' . $k . '</td><td>' . $vv . '(' . $vk . ')' . '</td>';
                                            } else {
                                                echo '<tr><td>' . $vv . '(' . $vk . ')' . '</td>';
                                            }


                                            echo '<td class="request_quantity">' . ($key !== false ? $convertArrayOrder[$key]->case : 0) . '</td>';
                                            echo '<td>
                                                <input type="hidden" name="short_name[]" value="' . $vk . '">
                                                <input type="hidden" name="price[' . $vk . ']" value="' . get_case_price($vk,false) . '">
                                                <input
                                                    class="order_quantity"
                                                    style="width: 100px;"
                                                    name="quantity[' . ($key !== false ? $convertArrayOrder[$key]->short_name : $vk) . ']"
                                                    type="text"
                                                     pack_size="'.get_pack_size($vk).'"
                                                    oldValue="' . ($key !== false ? $convertArrayOrder[$key]->case : 0) . '"
                                                    value="' . ($key !== false ? $convertArrayOrder[$key]->case : 0) . '"></td>';
                                            echo '<td style="text-align: right" class="price_rate">' . get_case_price($vk,false) . '</td>';
                                            echo '<td style="text-align: right" class="sub_total">' . ($key !== false ? (number_format($sub_total, 2)) : 0) . '</td>';
                                            echo '</tr>';
                                            $sl++;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <th style="text-align: right"></th>
                                        <th>&nbsp;Total</th>
                                        <th>{{$sales_info->sale_total_case}}</th>
                                        <th id="sale_total_case">{{$sales_info->sale_total_case}}</th>
                                        <th>&nbsp;</th>
                                        <th class="grand_total" style="text-align: right">
                                            {{number_format($grand_total,2)}}
                                        </th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12 text-right">
                                @if(in_array($sales_info->sale_status,['Processed']))
                                    <input class="btn btn-primary" type="submit" value="Process">
                                @endif
                            </div>
                        </form>
                    </div>


                </div>


            </div>
        </div>
        <script>
            $(document).ready(function () {
                function packSizeCheck(order_quantity,pack_size)
                {
                    var decimal = order_quantity.toString().split(".")[1];
                    var result = true;
                    if(decimal)
                    {
                        if(decimal.length > 1)
                        {
                            if(parseInt(decimal) >= pack_size)
                            {
                                result = false;
                            }
                        }
                        else
                        {
                            if((parseInt(decimal)*10) >= pack_size)
                            {
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
                    var current_balance = parseFloat($('.current_balance').text().replace(',',''));
                    var rate = $(this).parent().parent().find('.price_rate').text();
                    var sku = $(this).parent().find('input').first().attr('value');

                    var pValidation = packSizeCheck(order_quentity,pack_size);
                    if(pValidation)
                    {

                        var sub_total= order_quentity*rate;
                        $(this).parent().parent().find('.sub_total').text(sub_total.toFixed(2));

                        var grand_total = 0;
                        $('.order_quantity').each(function(){
                            var get_sub_total = parseFloat($(this).parent().parent().find('.sub_total').text());
                            var get_total_quantity = parseFloat($(this).val());
                            grand_total = grand_total+get_sub_total;
                        });

                        var sale_total_case = 0;
                        $('.order_quantity').each(function(){
                            var get_sub_total = parseFloat($(this).val());
                            if(!isNaN(get_sub_total)){
                               sale_total_case = sale_total_case+get_sub_total;
                            }
                        });

                        $('#sale_total_case').text(sale_total_case);

                        $('.grand_total').text(parseFloat(grand_total).toFixed(2));
                        if(grand_total > current_balance)
                        {
                            var htm = '<div class="alert alert-danger alert-dismissible">';
                            htm += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                            htm += 'You have exceeded the current balance.<br/>';
                            htm += '</div>';
                            $('.showMessage').html(htm);
                        }
                        else
                        {
                            $('.showMessage').html('');
                        }
                    }
                    else
                    {
                        alert('Invalid quantity');
                        $(this).val(oldValue);
                    }

                });

                $(document).on('focusin', '#order_deposit', function(){
                    $(this).data('val', $(this).val());
                }).on('change','#order_deposit', function(){
                    var prev = $(this).data('val');
                    var current = $(this).val();
                    var cal=current-prev;
                    var current_balance = parseFloat($('.current_balance').text().replace(',',''));
                    var current_bal= current_balance+cal;
                    $('.current_balance').text(current_bal);
                    $('input[name="current_balance"]').val(current_bal);
                });
            });
        </script>
@endsection