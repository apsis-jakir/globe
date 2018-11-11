<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer">
{{--<table id="" class="" border="1">--}}
    <thead>
    <tr>
        <th rowspan="3" style="vertical-align: middle">Route Name</th>
        <th rowspan="3" style="vertical-align: middle">ASO</th>
        <th rowspan="3" style="vertical-align: middle">DB House</th>
        <th rowspan="3" style="vertical-align: middle">Type</th>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{array_sum(array_map("count", $category_value))}}" style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
    </tr>
    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    <th colspan="{{count($brand_value)}}" style="text-align: center">{{$brand_key}}</th>
                @endforeach
            @endforeach
        @endif
    </tr>
    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    @foreach($brand_value as $sku_key=>$sku_value)
                        <th style="text-align: center">{{$sku_key}}</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>
    {{--<tr>--}}
        {{--@if($level > 1)--}}
            {{--@foreach($memo_structure as $category_key=>$category_value)--}}
                {{--@foreach($category_value as $brand_key=>$brand_value)--}}
                    {{--@foreach($brand_value as $sku_key=>$sku_value)--}}
                        {{--@foreach($level_col_data as $val)--}}
                            {{--<th colspan="1">{{$val}}</th>--}}
                        {{--@endforeach--}}
                    {{--@endforeach--}}
                {{--@endforeach--}}
            {{--@endforeach--}}
        {{--@endif--}}
    {{--</tr>--}}
    </thead>
    <tbody>

    {{--@if(isset($route_wise_performance) && count($route_wise_performance) > 0)--}}
        {{--@foreach($route_wise_performance as $route_key=> $route_info)--}}
            {{--<tr>--}}
                {{--<td style="color:#fff; background: #fff;">{{$route_key}}</td>--}}
                {{--<td style="color:#fff; background: #fff;">{{$route_key}}</td>--}}
                {{--<td style="color:#fff; background: #fff;">{{$route_key}}</td>--}}
                {{--<td>Target</td>--}}
                {{--@foreach($route_info as $key => $value)--}}
                    {{--<td>{{$value[0]}}</td>--}}
                {{--@endforeach--}}
            {{--</tr>--}}

            {{--<tr>--}}
                {{--<td style="background: #fff; border-bottom: #fff !important; border-top: #fff !important;">{{$route_key}}</td>--}}
                {{--<td style="background: #fff; border-bottom: #fff !important; border-top: #fff !important;">{{$route_key}}</td>--}}
                {{--<td style="background: #fff; border-bottom: #fff !important; border-top: #fff !important;">{{$route_key}}</td>--}}
                {{--<td>Sales</td>--}}
                {{--@foreach($route_info as $key => $value)--}}
                    {{--<td>{{$value[1]}}</td>--}}
                {{--@endforeach--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td style="color:#fff; background: #fff; border-bottom: #fff !important; border-top: #fff !important;">{{$route_key}}</td>--}}
                {{--<td style="color:#fff; background: #fff; border-bottom: #fff !important; border-top: #fff !important;">{{$route_key}}</td>--}}
                {{--<td style="color:#fff; background: #fff; border-bottom: #fff !important; border-top: #fff !important;">{{$route_key}}</td>--}}
                {{--<td>Ach%</td>--}}
                {{--@foreach($route_info as $key => $value)--}}
                    {{--<td>{{$value[2]}}</td>--}}
                {{--@endforeach--}}
            {{--</tr>--}}
        {{--@endforeach--}}
    {{--@endif--}}
    <?php //debug($route_wise_performance,1); ?>
    @if(isset($route_wise_performance) && count($route_wise_performance) > 0)
        @foreach($route_wise_performance as $route_key=> $route_info)
            <tr>
                <td style="color:#fff; background: #fff;"><a style="color: #fff;" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['routes_name']}} </a></td>
                <td style="color:#fff; background: #fff;"><a style="color: #fff;" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id']).'/'.$selectedMonths; ?>"> {{$route_info['aso_name']}} </a></td>
                <td style="color:#fff; background: #fff;"><a style="color: #fff;" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id']).'/'.$selectedMonths; ?>"> {{$route_info['db_house']}} </a></td>
                <td>Target</td>
                @foreach($route_info['result'] as $key => $value)
                    <td>{{$route_info['result'][$key][0]}}</td>
                @endforeach
            </tr>

            <tr>
                <td style="background: #fff; border-bottom: #fff !important; border-top: #fff !important;">
                    <a style="color: #0000ff; text-decoration: underline" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['routes_name']}} </a>
                </td>
                <td style="background: #fff; border-bottom: #fff !important; border-top: #fff !important;">
                    <a style="color: #0000ff; text-decoration: underline" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['aso_name']}} </a>
                </td>
                <td style="background: #fff; border-bottom: #fff !important; border-top: #fff !important;">
                    <a style="color: #0000ff; text-decoration: underline" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['db_house']}} </a>
                </td>
                <td>Sales</td>
                @foreach($route_info['result'] as $key => $value)
                    <td>{{$route_info['result'][$key][1]}}</td>
                @endforeach
            </tr>
            <tr>
                <td style="color:#fff; background: #fff; border-bottom: #fff !important; border-top: #fff !important;"><a style="color: #fff;" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['routes_name']}} </a></td>
                <td style="color:#fff; background: #fff; border-bottom: #fff !important; border-top: #fff !important;"><a style="color: #fff;" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['aso_name']}} </a></td>
                <td style="color:#fff; background: #fff; border-bottom: #fff !important; border-top: #fff !important;"><a style="color: #fff;" href="<?php echo URL::to('individual-route-performance/'.$route_info['route_id'].'/'.$selectedMonths); ?>"> {{$route_info['db_house']}} </a></td>
                <td>Ach%</td>
                @foreach($route_info['result'] as $key => $value)
                    <td>{{$route_info['result'][$key][2]}}</td>
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<style>
    div#dataTableId_info {
        display: none;
    }
</style>