<table id="dataTableId" class="table-bordered table dataTable">
    <thead>
    <tr>
        @if(isset($date_wise) && $date_wise)
            <th rowspan="4" style="vertical-align: middle">Date</th>
        @else
            <th rowspan="4" style="vertical-align: middle">House Name</th>
        @endif
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}" style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        @if(isset($current_balance) && $current_balance)
            <th rowspan="4" style="vertical-align: middle">Current balance</th>
        @endif
    </tr>
    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    <th colspan="{{count($brand_value) * $level}}" style="text-align: center">{{$brand_key}}</th>
                @endforeach
            @endforeach
        @endif
    </tr>
    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    @foreach($brand_value as $sku_key=>$sku_value)
                        <th colspan="{{$level}}" style="text-align: center">{{$sku_key}}</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>
    <tr>
        @if($level > 1)
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    @foreach($brand_value as $sku_key=>$sku_value)
                        @foreach($level_col_data as $val)
                            <th colspan="1">{{$val}}</th>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>
    </thead>
    <tbody>
    @if(isset($order_vs_sale_primary) && count($order_vs_sale_primary) > 0)
        @foreach($order_vs_sale_primary as $house_key=> $house_info)
                <tr>
                    @if(isset($house_info['additional']['house_id']))
                    <th><a target="_blank" href="{{URL::to('order-vs-sale-primary-date-wise/'.$house_info['additional']['house_id'].'/'.json_encode($post_data))}}"> {{$house_key}} </a></th>
                    @else
                        <th><a> {{$house_key}} </a></th>
                    @endif

                    @foreach($house_info['data'] as $key => $value)
                        @for($i=0;$i<$level;$i++)
                            <td>{{$value[$i]}}</td>
                        @endfor
                    @endforeach
                    @if(isset($current_balance) && $current_balance)
                        <th>{{$house_info['additional']['current_balance']}}</th>
                    @endif


                </tr>
        @endforeach
    @endif
    </tbody>
</table>