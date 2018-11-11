<table id="dataTableId" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="4" style="vertical-align: middle">House Name</th>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}" style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        <th rowspan="4" style="vertical-align: middle">Sale Amount</th>
        <th rowspan="4" style="vertical-align: middle">Deposit Amount</th>
        <th rowspan="4" style="vertical-align: middle">Balance</th>
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
    @if(isset($house_lifting_list) && count($house_lifting_list) > 0)
        @foreach($house_lifting_list as $house_key=> $house_info)
            <tr>
                <th>{{$house_key}}</th>
                @foreach($house_info['data'] as $key => $value)
                    @for($i=0;$i<$level;$i++)
                        <td>{{$value[$i]}}</td>
                    @endfor
                @endforeach
                <td>{{number_format($house_info['additional']['sale_amount'],2)}}</td>
                <td>{{number_format($house_info['additional']['deposit_amount'],2)}}</td>
                <td>{{number_format($house_info['additional']['current_balance'],2)}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>