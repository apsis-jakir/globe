<table id="dataTableId" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="4" style="vertical-align: middle">Route Name</th>
        <th rowspan="4">Total Outlet</th>
        <th rowspan="2" colspan="2" style="text-align: center">Visited Outlet</th>
        <th rowspan="4">Successfull Call</th>
        <th rowspan="4">Call Productivity</th>

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
        <th rowspan="2">Visited</th>
        <th rowspan="2">%</th>
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
    @if(isset($route_wise_strike_rate) && count($route_wise_strike_rate) > 0)
        @foreach($route_wise_strike_rate as $route_key=> $route_info)
            <tr>
                <th>{{$route_key}}</th>
                @for($i=0;$i<count($route_info['additional']);$i++)
                    <td>{{$route_info['additional'][$i]}}</td>
                @endfor
                @foreach($route_info['data'] as $key => $value)
                    @for($i=0;$i<$level;$i++)
                        <td>{{$value[$i]}}</td>
                    @endfor
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>