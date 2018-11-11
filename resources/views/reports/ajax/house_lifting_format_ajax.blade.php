<table id="dataTableIdCustom" class="table-bordered table dataTable">
    <thead>
    <tr>
        <th rowspan="3" style="vertical-align: middle">House Name</th>

        <th rowspan="3">Lifting Info</th>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}"
                    style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        <th rowspan="3" style="vertical-align: middle">Sale Amount</th>
        <th rowspan="3" style="vertical-align: middle">Deposit Amount</th>
        <th rowspan="3" style="vertical-align: middle">Balance</th>
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

    </thead>
    <tbody>
    @if(isset($house_lifting_list) && count($house_lifting_list) > 0)
        @foreach($house_lifting_list as $house_key=> $house_info)
            @foreach($house_info['data'] as $key => $value)
                <tr>
                    <th>{{$house_key}}</th>
                    <th>{{$key}}</th>
                    @foreach($value as $k=>$v)
                        <td>{{$v}}</td>
                    @endforeach
                    <td>{{number_format($house_info['additional']['sale_amount'],2)}}</td>
                    <td>{{number_format($house_info['additional']['deposit_amount'],2)}}</td>
                    <td>{{number_format($house_info['additional']['current_balance'],2)}}</td>
                </tr>
            @endforeach
        @endforeach
    @endif
    </tbody>
</table>
<script>
    $('#dataTableIdCustom').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            leftColumns: 1
        },
        rowsGroup: [0,34,35,36],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['excel','print', 'pageLength']
    });
</script>