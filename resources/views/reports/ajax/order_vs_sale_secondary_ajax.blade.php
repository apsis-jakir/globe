<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer">
    <thead>
    <tr>
        <th rowspan="4" style="vertical-align: middle">House Name</th>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}" style="text-align: center">{{$category_key}}</th>
            @endforeach
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
    @if(isset($order_vs_sale_secondary) && count($order_vs_sale_secondary) > 0)
        @foreach($order_vs_sale_secondary as $house_key=> $house_info)
            <tr>
                <th><a target="_blank" href="{{URL::to('order-vs-sale-secondary-aso/'.$house_info['additional']['house_id'].'/'.json_encode($post_data))}}"> {{$house_key}} </a></th>
                @foreach($house_info['data'] as $key => $value)
                    @for($i=0;$i<$level;$i++)
                        <td>{{$value[$i]}}</td>
                    @endfor
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>