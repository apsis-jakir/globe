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

        @if(isset($house_wise_performance) && count($house_wise_performance) > 0)
            <?php
                $month = unserialize(urldecode($postData));
                //debug($month,1);
            ?>
            @foreach($house_wise_performance as $house_key=> $house_info)
                <tr>
                    <th>
                        <a style="color: #0000ff; text-decoration: underline" href="{{URL::to('route-wise-performence-category/'.$house_info['house_id'].'/'.$postData)}}"> {{$house_key}} </a>&nbsp;&nbsp;
                        <a style="color: #0000ff; text-decoration: underline" href="{{URL::to('individual-route-performance/'.$house_info['house_id'].'/'.((isset($month['month'][0]))?$month['month'][0]:'').'/'.'house')}}">Print</a> </th>
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