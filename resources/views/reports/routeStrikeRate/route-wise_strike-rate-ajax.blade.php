<table id="dataTableId" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="4" style="vertical-align: middle">{{ucfirst($view_report)}}</th>
        {!! parrentColumnTitleValue(ucwords($view_report),4)['html'] !!}
        <th rowspan="4" style="vertical-align: middle">Target Outlet</th>
        <th rowspan="4" style="vertical-align: middle">Visited Outlet</th>
        <th rowspan="4" style="vertical-align: middle">Visited Outlet%</th>
        <th rowspan="4" style="vertical-align: middle">Successfull Call</th>
        <th rowspan="4" style="vertical-align: middle">Call Productivity</th>
        <th rowspan="4" style="vertical-align: middle">Bounce Call%</th>
        <th rowspan="4" style="vertical-align: middle">Additional Sale%</th>
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
                        <th colspan="{{$level}}" style="text-align: center">{{$sku_value}}</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>


    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    @foreach($brand_value as $sku_key=>$sku_value)
                        <th style="text-align: center">Productivity</th>
                        <th style="text-align: center">Avg/Memo</th>
                        <th style="text-align: center">Vol/Memo</th>
                        <th style="text-align: center">Portfolio Vol</th>
                        <th style="text-align: center">Val/Call</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>

    </thead>
    <tbody>
        @foreach($grid_data as $k=>$v)
            @php
                $bounce_call = 0;
                $additional_sale = 0;
                $bounce = ($v['order_total_case']-$v['sale_total_case']);
                if($bounce > 0)
                {
                    $bounce_call = (($bounce*100)/$v['order_total_case']);
                }
                else if($bounce < 0)
                {
                    $additional_sale = (((-$bounce)*100)/$v['order_total_case']);
                }
            @endphp
            <tr>
                <td style="background: #fff;">{{$k}}</td>
                @foreach(parrentColumnTitleValue(ucwords($view_report),3)['value'] as $pctv)
                    <td>{{$v['parents'][$pctv]}}</td>
                @endforeach
                <td style="background: #fff;">{{($v['total_outlet'])?$v['total_outlet']:0}}</td>
                <td style="background: #fff;">{{($v['visited_outlet'])?$v['visited_outlet']:0}}</td>
                <td style="background: #fff;">{{$v['total_outlet'] != 0 ? number_format(($v['visited_outlet'] / $v['total_outlet'])*100, 2) : 0}}</td>
                <td style="background: #fff;">{{($v['total_no_of_memo'])?$v['total_no_of_memo']:0}}</td>
                <td style="background: #fff;">{{$v['visited_outlet'] != 0 ? number_format(($v['total_no_of_memo'] / $v['visited_outlet'])*100,2) : 0}}</td>
                <td style="background: #fff;">{{number_format($bounce_call,2)}}</td>
                <td style="background: #fff;">{{number_format($additional_sale,2)}}</td>
                @foreach(getSkuArrayFromMemoStructure($memo_structure) as $sku)
                    @php
                        $indmemo = (isset($v['individual_memo'][$sku])?$v['individual_memo'][$sku]:0);
                        $indquantity = (isset($v['individual_quantity'][$sku])?$v['individual_quantity'][$sku]:0);
                        $indprice = (isset($v['individual_price'][$sku])?$v['individual_price'][$sku]:0);
                    @endphp
                    <td>{{$v['total_no_of_memo'] != 0 ? number_format(($indmemo / $v['total_no_of_memo']) * 100,2) : 0}} </td>
                    <td>{{$v['total_no_of_memo'] != 0 ? number_format(($indmemo / $v['total_no_of_memo']),2) : 0}} </td>
                    <td>{{$indmemo != 0 ? number_format($indquantity / $indmemo,2) : 0}} </td>
                    <td>{{$v['total_no_of_memo'] != 0 ? number_format($indquantity / $v['total_no_of_memo'],2): 0}} </td>
                    <td>{{$v['total_no_of_memo'] != 0 ? number_format($indquantity * $indprice / $v['total_no_of_memo'],2) : 0}} </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>