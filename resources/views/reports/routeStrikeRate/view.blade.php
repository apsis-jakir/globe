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
<div class="text-bold text-blue">{{$position}}</div>
<br/>
<div class="table table-responsive">
    <table id="lifting" class="table-bordered table dataTable">
        <thead>
        <tr>
            <th rowspan="3" style="vertical-align: middle">
                @if(empty($view_column)) Search-wise Column
                @else
                    @if($view_column == 'house') Distributor House
                    @elseif($view_column == 'date') Order Dated
                    @elseif($view_column == 'region') Region Name
                    @elseif($view_column == 'territory') Territory Name
                    @elseif($view_column == 'zone') Zone Name
                    @elseif($view_column == 'aso') ASO Name
                    @else Distributor House
                    @endif
                @endif
            </th>
            <th rowspan="3" style="vertical-align: middle">Particulars</th>
            <th rowspan="3" style="vertical-align: middle">Target Outlet</th>
            <th rowspan="2" colspan="2" style="vertical-align: middle" class="text-center">Visited Outlet</th>
            <th rowspan="3" style="vertical-align: middle">Successful Call</th>
            <th rowspan="3" style="vertical-align: middle">Call Productivity</th>
            @if(isset($memo_structure))
                @foreach($memo_structure as $category_key => $category_value)
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
            <th style="vertical-align: middle">Visited</th>
            <th style="vertical-align: middle">%</th>
            @if(isset($memo_structure))
                @foreach($memo_structure as $category_key=>$category_value)
                    @foreach($category_value as $brand_key=>$brand_value)
                        @foreach($brand_value as $sku_key=>$sku_value)
                            <th colspan="{{$level}}" style="text-align: center;vertical-align: middle;">{{$sku_key}}</th>
                        @endforeach
                    @endforeach
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(!empty($grid_data))
            @foreach($grid_data as $grids)
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Productivity</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['sku_productivity'] as $sku_productivity)
                        <td class="text-right">{{$sku_productivity}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Avg/Memo</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['sku_avgmemo'] as $sku_avgmemo)
                        <td class="text-right">{{$sku_avgmemo}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Vol/Memo</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['sku_volmemo'] as $sku_volmemo)
                        <td class="text-right">{{$sku_volmemo}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Portfolio</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['sku_portfolio'] as $sku_portfolio)
                        <td class="text-right">{{$sku_portfolio}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Val/Call</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['sku_val_call'] as $sku_val_call)
                        <td class="text-right">{{$sku_val_call}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Bounce</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['sku_bounce'] as $sku_bounce)
                        <td class="text-right">{{$sku_bounce}}</td>
                    @endforeach
                </tr>
                <tr>
                    <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                    <td>Additional Cell</td>
                    <td style="vertical-align: middle">{!! $grids['target_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['visited_outlet_ratio'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['successfull_memo'] !!} </td>
                    <td style="vertical-align: middle">{!! $grids['call_productivity'] !!} </td>
                    @foreach($grids['add_cell'] as $add_cell)
                        <td class="text-right">{{$add_cell}}</td>
                    @endforeach
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<script>
    $('#lifting').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2
        },
        rowsGroup: [0,2,3,4,5,6],
        order: [],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['excel', 'print', 'pageLength']
    });
//    $(document).on('click','.digdown', function (e) {
    $('.digdown').click(function(e){
        e.preventDefault();
        var url = "<?php echo url('digdownReconciliationAjax');?>";
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