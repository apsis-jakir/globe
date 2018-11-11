<table id="dataTableId" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="3" style="vertical-align: middle">{{strtoupper($view_reports)}}</th>
        {!! parrentColumnTitleValue(ucwords($view_reports),3)['html'] !!}
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}" style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        <th rowspan="3" style="vertical-align: middle">Total</th>
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
        @if($view_reports == 'aso')
            @foreach($asoSum['name'] as $k=>$v)
                @php
                    //$skueArray = json_decode($v->target_value,true);
                    $total = 0;
                @endphp
                <tr>
                    <td style="background: #fff;">{{$v}}</td>
                    @foreach(parrentColumnTitleValue(ucwords($view_reports),3)['value'] as $pctv)
                        <td>{{get_target_map_value($pctv,$asoSum['id'][$k],$view_reports)}}</td>
                    @endforeach
                    @foreach(getSkuArrayFromMemoStructure($memo_structure) as $sku)
                        @php
                            //dd($asoSum['jsonVal'][$k],getSkuArrayFromMemoStructure($memo_structure),$asoSum['jsonVal'][$k][$sku]);
                            if(!empty($asoSum['jsonVal'][$k]))
                            {
                                $total = $total+$asoSum['jsonVal'][$k][$sku];
                            }

                        @endphp
                        <td>
                            {{(!empty($asoSum['jsonVal'][$k]))?$asoSum['jsonVal'][$k][$sku]:0}}
                        </td>
                    @endforeach
                    <td>{{$total}}</td>
                </tr>
            @endforeach
        @else
            @foreach($targetStatement as $k=>$v)
                @php
                    $skueArray = json_decode($v->target_value,true);
                    $total = 0;
                @endphp
                <tr>
                    <td style="background: #fff;">{{$v->field_name}}</td>
                    @foreach(parrentColumnTitleValue(ucwords($view_reports),3)['value'] as $pctv)
                        <td>{{get_target_map_value($pctv,$v->id,$view_reports)}}</td>
                    @endforeach

                    @foreach(getSkuArrayFromMemoStructure($memo_structure) as $sku)
                        @php
                        if(isset($skueArray[$sku]))
                        {
                            $total = $total+$skueArray[$sku];
                        }
                        @endphp
                        
                        <td>
                            {{($skueArray != '')?isset($skueArray[$sku])?$skueArray[$sku]:0:0}}
                        </td>
                    @endforeach
                    <td>{{$total}}</td>
                </tr>
            @endforeach
        @endif


    </tbody>
</table>