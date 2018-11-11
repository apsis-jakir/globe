@if(isset($pending_orders) && count($pending_orders) > 0)
<table id="dataTableId" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="3" style="vertical-align: middle">{{ucfirst($view_report)}}</th>
        {!! parrentColumnTitleValue(ucfirst($view_report),3)['html'] !!}
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}" style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        <th rowspan="3" style="vertical-align: middle">Total Case</th>
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
                        <th colspan="{{$level}}" style="text-align: center">{{$sku_value}}</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>

    </thead>
    <tbody>
{{--    @if(isset($pending_orders) && count($pending_orders) > 0)--}}
        @foreach($pending_orders as $key => $info)
            <tr>
                <th><a target="_blank" href="{{URL::to('pending-order-details/'.$key.'/'.$info['table_id'].'/'.json_encode($info['data_config']))}}">{{$key}}</a> </th>
                @foreach(parrentColumnTitleValue(ucfirst($view_report),3)['value'] as $pctv)
                    <td>{{$info['parents'][$pctv]}}</td>
                @endforeach
                @php
                    $total = 0;
                @endphp
                @foreach($memo_structure as $category_key=>$category_value)
                    @foreach($category_value as $brand_key=>$brand_value)
                        @foreach($brand_value as $sku_key=>$sku_value)
                            @php
                            if(isset($info['data'][$sku_value])){
                                $total = $total+$info['data'][$sku_value];
                            }
                            @endphp
                            <td>{{(isset($info['data'][$sku_value])?$info['data'][$sku_value]:0)}}</td>
                        @endforeach
                    @endforeach
                @endforeach
                <td>{{$total}}</td>
            </tr>
        @endforeach
    {{--@endif--}}
    </tbody>
</table>
@else
    <div class="alert alert-danger text-center"><h3><i class="fa fa-exclamation-triangle"></i> No Data Found</h3></div>
@endif