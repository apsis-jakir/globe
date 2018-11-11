<table id="daily_sale_summary" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="3" style="vertical-align: middle">Date</th>
        <th rowspan="3" style="vertical-align: middle">ASO NAME</th>

        <th rowspan="3" style="vertical-align: middle">Par.</th>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}"
                    style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        <th rowspan="3" style="vertical-align: middle">No. of Outlet</th>
        <th rowspan="3" style="vertical-align: middle">Visited Outlet</th>
        <th rowspan="3" style="vertical-align: middle">No. of Memo</th>
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
                        <th style="text-align: center">{{$sku_key}}</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>

    </thead>
    <tbody>
    @if(isset($daily_sale_summary) && count($daily_sale_summary) > 0)
        @foreach($daily_sale_summary as $sale_key=> $sale_details)
            @foreach($sale_details as $s_key => $s_value)
                @php($first=true)
                @php($additional=true)
                @foreach($s_value['data'] as $key=>$value)
                    <tr>
                        @if($first)
                            <td style="vertical-align : middle;text-align:center;">{{$s_key}}</td>
                            <th style="vertical-align : middle;text-align:center;"> {{$sale_key}}</th>
                            @php($first=false)
                        @else
                            <td style="visibility: hidden;"></td>
                            <td style="visibility: hidden;"></td>
                        @endif

                        <th>{{$key}}</th>
                        @foreach($value as $k=>$v)
                            <td>{{$v}}</td>
                        @endforeach
                        @if($additional)
                            <td style="vertical-align : middle;text-align:center;">{{$s_value['additional']['no_of_outlet']}}</td>
                            <td style="vertical-align : middle;text-align:center;">{{$s_value['additional']['visited_outlet']}}</td>
                            <td style="vertical-align : middle;text-align:center;">{{$s_value['additional']['total_memo']}}</td>@php($additional=false)
                        @else
                                <td style="visibility: hidden;"></td>
                                <td style="visibility: hidden;"></td>
                                <td style="visibility: hidden;"></td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    @endif
    </tbody>
</table>

<script>
    $('#daily_sale_summary').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 1
        },
        order: [],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['excel', 'print', 'pageLength']
    });
</script>