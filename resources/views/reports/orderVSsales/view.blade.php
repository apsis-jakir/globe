<style>
    table{
        border-spacing: 0;
    }
    th, td { white-space: nowrap; }
    table.dataTable {
        clear: both;
        margin: 0 !important;
        background-color: #fff;
        max-width: none !important;
        border-collapse: separate !important;
    }
    div.DTFC_LeftWrapper table.dataTable, div.DTFC_RightWrapper table.dataTable {
        margin-bottom: 15px !important;
        margin-top: -1px !important;
        z-index: 2;
        background-color: #fff;
    }
    div.DTFC_LeftWrapper table.dataTable.no-footer, div.DTFC_RightWrapper table.dataTable.no-footer {
        border-top: 1px solid #eee !important;
        border-bottom: 1px !important;
    }
</style>
@if(!empty($grid_data))
    <div class="text-bold text-blue">{{$position}}</div>
    <br/>
    <table id="lifting" class="table-bordered table dataTable">
        <thead>
        <tr>
            <th rowspan="4" style="vertical-align: middle">
                @if(empty($view_column)) Search-wise Column
                @else
                    @if($view_column == 'house') Distributor House
                    @elseif($view_column == 'date') Order Dated
                    @elseif($view_column == 'region') Region Name
                    @elseif($view_column == 'territory') Territory Name
                    @elseif($view_column == 'zone') Zone Name
                    @else Distributor House
                    @endif
                @endif
            </th>
            {!! parrentColumnTitleValue(ucfirst($view_column),4)['html'] !!}
            @if(isset($memo_structure))
                @foreach($memo_structure as $category_key=>$category_value)
                    <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}"
                        style="text-align: center; vertical-align: middle">{{$category_key}}</th>
                @endforeach
            @endif
        </tr>
        <tr>

            @if(isset($memo_structure))
                @foreach($memo_structure as $category_key=>$category_value)
                    @foreach($category_value as $brand_key=>$brand_value)
                        <th colspan="{{count($brand_value) * $level}}" style="text-align: center; vertical-align: middle;">{{$brand_key}}</th>
                    @endforeach
                @endforeach
            @endif
        </tr>
        <tr>
            @if(isset($memo_structure))
                @foreach($memo_structure as $category_key=>$category_value)
                    @foreach($category_value as $brand_key=>$brand_value)
                        @foreach($brand_value as $sku_key=>$sku_value)
                            <th colspan="{{$level}}" style="text-align: center;vertical-align: middle;">{{$sku_value}}</th>
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
            @foreach($grid_data as $grids)
                @if($grids['view_type'])
                    <tr>
                        <td>{!! $grids['view_type'] !!} </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),4)['value'] as $pctv)
                            <td>{{$grids[$pctv]}}</td>
                        @endforeach
                        @foreach($grids['sku_grid'] as $skugrids)
                            <td>{{$skugrids}}</td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info text-center"><h3><i class="fa fa-exclamation-triangle"></i> Please Search for result</h3></div>
@endif
<script>
    $('#lifting').dataTable({
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
        buttons: ['pageLength']
    });
//    $(document).on('click','.digdown', function (e) {
    $('.digdown').click(function(e){
        e.preventDefault();
        var url = "<?php echo url('digdownOrderSaleListAjax');?>";
        var _token = '<?php echo csrf_token() ?>';
        var loctype = $(this).data('loctype');
        var locid = $(this).data('locid');
        var startdate = $(this).data('startdate');
        var enddate = $(this).data('enddate');
        $.ajax({
            url: url,
            type: 'POST',
            data: $('#grid_list_frm').serialize()
                +'&_token='+_token
                +'&loctype='+loctype
                +'&locid='+locid
                +'&startdate='+startdate
                +'&enddate='+enddate,
            beforeSend: function(){ $('.loadingImage').show();},
            success: function (d) {
                $('.showSearchDataUnique').html(d);
//                myConfiguration();
                $('.loadingImage').hide();

            }
        });
    });
</script>