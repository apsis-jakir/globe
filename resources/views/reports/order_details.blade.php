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

                        <form class="form-inline" data-toggle="validator" role="form" id="order_details_frm" action="{{URL::to('primary-sales-create')}}" method="post" onkeypress="return event.keyCode != 13;">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div style="border-bottom: 1px solid #ccc">
                                        <h3>{{$orders_info->point_name.' - '.$orders_info->market_name}}</h3>
                                        <h5>House Phone : {{$orders_info->dh_phone}}</h5>
                                        <h5>ASM/RSM Name : {{$orders_info->requester_name}}</h5>
                                        <h5>ASM/RSM  Phone : {{$orders_info->requester_phone}}</h5>
                                        <h5 style="overflow: hidden;">
                                            <span style="float: left;">Request Date : {{date('d-m-Y',strtotime($orders_info->order_date))}}</span>
                                            <span style="float: right; color: #0000F0; font-weight: bold;">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Deposited Amount :</label>
                                                    <input class="form-control" pattern="^[0-9]\d*(\.\d+)?$" required type="text" id="order_deposit" name="order_da" value="{{$orders_info->order_da}}">
                                                </div>
                                                {{--Deposited Amount : --}}
                                                {{--<span>--}}
                                                    {{--<input pattern="^[1-9]\d*(\.\d+)?$" required type="text" id="order_deposit" name="order_da" value="{{$orders_info->order_da}}">--}}

                                                {{--</span>&nbsp;&nbsp--}}
                                                @if($orders_info->order_status === 'Pending')
                                                Current Balance : <span class="current_balance">{{number_format(($orders_info->current_balance+$orders_info->order_da),2)}}</span>
                                                @else
                                                    Current Balance : <span class="current_balance">{{number_format(($orders_info->current_balance),2)}}</span>
                                                @endif
                                            </span>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="hidden" name="redirect" value="order">
                                    <input type="hidden" name="id" value="0">
                                    <input type="hidden" name="asm_rsm_id" value="{{$orders_info->asm_rsm_id}}">
                                    <input type="hidden" name="sender_name" value="{{$orders_info->requester_name}}">
                                    <input type="hidden" name="sender_phone" value="{{$orders_info->requester_phone}}">
                                    <input type="hidden" name="dh_id" value="{{$orders_info->dbid}}">
                                    <input type="hidden" name="dh_name" value="{{$orders_info->dh_name}}">
                                    <input type="hidden" name="dh_phone" value="{{$orders_info->dh_phone}}">
                                    <input type="hidden" name="order_id" value="{{$orders_info->id}}">
                                    <input type="hidden" name="order_date" value="{{$orders_info->order_date}}">
                                    <input type="hidden" name="current_balance" value="{{$orders_info->current_balance}}">
                                </div>
                            </div>
                            <div class="showMessage"></div>
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                        <th colspan="2" style="text-align: center">Product Details</th>
                                        <th>Order Qty</th>
                                        <th>Sale Qty</th>
                                        <th style="text-align: right">Rate</th>
                                        <th style="text-align: right">Sub Total</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $grand_total = 0;
                                        $sale_total  = 0;
                                        foreach($memo as $k=>$v)
                                        {
                                            $sl = 0;
                                            foreach($v as $vk=>$vv)
                                            {
                                                $convertArrayOrder = collect($orders)->toArray();
                                                $key = array_search($vk, array_column($convertArrayOrder, 'short_name'));
//                                                $sub_total =  sku_pack_quantity(($key!==false ? $convertArrayOrder[$key]->short_name: ''),
//                                                        ($key!==false ? $convertArrayOrder[$key]->case: 0)) * get_sku_price($vk);



                                                $inputValue = ($key!==false ? $convertArrayOrder[$key]->case : 0);
                                                if($orders_info->order_status == 'Processed')
                                                {
                                                    $inputValue = \App\Helper\ReportsHelper::getProcessedOrderSkuSaleQuantity($orders_info->id,$vk);
                                                    $sale_total+= $inputValue;
                                                }


                                                $sub_total =  $inputValue * get_case_price($vk);

                                                $grand_total += $sub_total;


                                                if($sl == 0)
                                                {
                                                    echo '<tr><td rowspan="'.count($v).'" style="text-align: left; vertical-align: middle;">'.$k.'</td><td>'.$vv.'('.$vk.')</td>';
                                                }
                                                else
                                                {
                                                    echo '<tr><td>'.$vv.'('.$vk.')</td>';
                                                }


                                                echo '<td class="request_quantity">'.($key!==false? $convertArrayOrder[$key]->case : 0).'</td>';
                                                echo '<td>
                                                            <input type="hidden" name="short_name[]" value="'.$vk.'">
                                                            <input type="hidden" name="price['.$vk.']" value="'.get_case_price($vk).'">
                                                            <div class="form-group">
                                                                <input
                                                                required
                                                                pattern="^[0-9]\d*(\.\d+)?$"
                                                                '.(($orders_info->order_status != 'Pending')?"readonly":"").'
                                                                class="order_quantity form-control"
                                                                style="width: 100px;"
                                                                name="quantity['.($key!==false ? $convertArrayOrder[$key]->short_name:$vk).']"
                                                                type="text"
                                                                pack_size="'.get_pack_size($vk).'"
                                                                oldValue="'.$inputValue.'"
                                                                value="'.$inputValue.'">
                                                            </div>

                                                    </td>';
                                                echo '<td style="text-align: right" class="price_rate">'.get_case_price($vk).'</td>';
                                                echo '<td style="text-align: right" class="sub_total">'.number_format($sub_total,2).'</td>';
                                                echo '</tr>';
                                                $sl++;
                                            }
                                        }
                                    ?>
                                        <tr>
                                            <th></th>
                                            <th>Total</th>
                                            <th>{{$orders_info->order_total_case}}</th>
                                            <th id="sale_total">{{number_format($sale_total == 0 ? $orders_info->order_total_case : $sale_total ,2)}}</th>
                                            {{--<th class="total_quantity">{{$orders_info->order_total_sku}}</th>--}}
                                            {{--<th class="total_order_quantity">{{$orders_info->order_total_sku}}</th>--}}
                                            <th style="text-align: right"></th>
                                            <th class="grand_total" style="text-align: right">
                                                {{number_format($grand_total,2)}}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12 text-right">
                                @if((Auth::user()->user_type == 'devlopment') || (Auth::user()->user_type == 'admin'))
                                    @if(in_array($orders_info->order_status,['Pending']))
                                        <input class="btn btn-primary" type="submit" value="Process">
                                    @endif
                                @endif

                            </div>
                        </form>
                    </div>


                </div>



            </div>
        </div>
        <script>
            $('#order_details_frm').validator()
            $(document).ready(function () {
                {{--function getPackSizeQuantity($sku,$quantity,output){--}}
                    {{--$data=$.ajax({--}}
                        {{--url:'{{URL::to("get-pack-size_quantity")}}',--}}
                        {{--type:'POST',--}}
                        {{--async: false,--}}
                        {{--data:{--}}
                            {{--"_token": "{{ csrf_token() }}",--}}
                            {{--'sku':$sku,--}}
                            {{--'quantity':$quantity--}}
                        {{--},--}}
                        {{--success:function (data) {--}}
                            {{--return output(data);--}}
                        {{--}--}}
                    {{--});--}}
                {{--}--}}

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


                $(document).on('input','.order_quantity',function () {
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
                            var getSubTotal = $(this).parent().parent().find('.sub_total').text();
                            var get_sub_total = parseFloat(getSubTotal.replace(',',''));
                            var get_total_quantity = parseFloat($(this).val());
                            grand_total = grand_total+get_sub_total;
                        });

                        var total_sale=0;
                        $('.order_quantity').each(function(){
                            var get_sub_total = parseFloat($(this).val());
                            total_sale = total_sale + get_sub_total;
                        });

                        $('#sale_total').text(total_sale);

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