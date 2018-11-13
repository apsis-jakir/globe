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
    {{--<div class="table table-responsive">--}}
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
                        @elseif($view_column == 'route') Route Name
                        @elseif($view_column == 'aso') ASO Name
                        @else Distributor House
                        @endif
                    @endif
                </th>
                {!! parrentColumnTitleValue(ucfirst($view_column),3)['html'] !!}
                <th rowspan="3" style="vertical-align: middle">
                    Particulars
                </th>
                @if(isset($memo_structure))
                    @foreach($memo_structure as $category_key=>$category_value)
                        <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}"
                            style="text-align: center; vertical-align: middle">{{$category_key}}</th>
                    @endforeach
                @endif
                <th class="text-center" rowspan="3" style="vertical-align: middle">Total Case</th>
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
                    <?php 
                    $search_option = json_encode($search_options);
                    ?>
                @foreach($grid_data as $grids)
                    @php($total_target = 0)
                    @php($total_order = 0)
                    @php($total_lifting = 0)
                    @php($total_lifting_ratio = 0)
                    @php($total_achv = 0)
                    @php($total_achv_ratio = 0)
                    <tr>
                        <td style="vertical-align: middle">
                                <a target="_blank" style="margin-bottom: 3px;width: 95px !important;text-align: center;display: block;" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/view/'.$search_option)}}">{!! $grids['view_type'] !!} </a>
                                <br>                                
                                <a target="_blank" style="margin-top: 3px;margin-bottom: 3px;width: 95px;" class="btn btn-bitbucket" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/export/'.$search_option)}}">Export DSS </a>
                        </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                            <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                        @endforeach
                        <td>Target</td>
                        @foreach($grids['target'] as $target)
                            <td class="text-right">{{$target}}</td>
                            @php($total_target += (float)$target)
                        @endforeach
                        <td class="text-right">{{number_format($total_target, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle">
                                <a target="_blank" style="margin-bottom: 3px;width: 95px !important;text-align: center;display: block;" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/view/'.$search_option)}}">{!! $grids['view_type'] !!} </a>
                                <br>                                
                                <a target="_blank" style="margin-top: 3px;margin-bottom: 3px;width: 95px;" class="btn btn-bitbucket" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/export/'.$search_option)}}">Export DSS </a>
                        </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                            <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                        @endforeach
                        <td>Order</td>
                        @foreach($grids['order'] as $order)
                            <td class="text-right">{{$order}}</td>
                            @php($total_order += (float)$order)
                        @endforeach
                        <td class="text-right">{{number_format($total_order, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle">
                                <a target="_blank" style="margin-bottom: 3px;width: 95px !important;text-align: center;display: block;" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/view/'.$search_option)}}">{!! $grids['view_type'] !!} </a>
                                <br>                                
                                <a target="_blank" style="margin-top: 3px;margin-bottom: 3px;width: 95px;" class="btn btn-bitbucket" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/export/'.$search_option)}}">Export DSS </a>
                        </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                            <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                        @endforeach
                        <td>Lifting</td>
                        @foreach($grids['lifting'] as $lifting)
                            <td class="text-right">{{$lifting}}</td>
                            @php($total_lifting += (float)$lifting)
                        @endforeach
                        <td class="text-right">{{number_format($total_lifting, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle">
                                <a target="_blank" style="margin-bottom: 3px;width: 95px !important;text-align: center;display: block;" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/view/'.$search_option)}}">{!! $grids['view_type'] !!} </a>
                                <br>                                
                                <a target="_blank" style="margin-top: 3px;margin-bottom: 3px;width: 95px;" class="btn btn-bitbucket" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/export/'.$search_option)}}">Export DSS </a>
                        </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                            <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                        @endforeach
                        <td>Lifting Ach %</td>
                        @php($la = 1)
                        @foreach($grids['lift_ratio'] as $lift_ration)
                            @if($lift_ration > 0)
                                @php($la++)
                            @endif
                            <td class="text-right">{{$lift_ration}}</td>
                            @php($total_lifting_ratio += (float)$lift_ration)
                        @endforeach
                        <td class="text-right">{{number_format($total_lifting_ratio / $la , 2)}}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle">
                                <a target="_blank" style="margin-bottom: 3px;width: 95px !important;text-align: center;display: block;" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/view/'.$search_option)}}">{!! $grids['view_type'] !!} </a>
                                <br>                                
                                <a target="_blank" style="margin-top: 3px;margin-bottom: 3px;width: 95px;" class="btn btn-bitbucket" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/export/'.$search_option)}}">Export DSS </a>
                        </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                            <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                        @endforeach
                        <td>Sales</td>
                        @foreach($grids['achvmnt'] as $achvmnt)
                            <td class="text-right">{{$achvmnt}}</td>
                            @php($total_achv += (float)$achvmnt)
                        @endforeach
                        <td class="text-right">{{number_format($total_achv, 2)}}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle">
                                <a target="_blank" style="margin-bottom: 3px;width: 95px !important;text-align: center;display: block;" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/view/'.$search_option)}}">{!! $grids['view_type'] !!} </a>
                                <br>                                
                                <a target="_blank" style="margin-top: 3px;margin-bottom: 3px;width: 95px;" class="btn btn-bitbucket" href="{{URL::to('digdownDSSAjax/'.$grids['loctype'].'/'.$grids['locid'].'/export/'.$search_option)}}">Export DSS </a>
                        </td>
                        @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                            <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                        @endforeach
                        <td>Sales Ach %</td>
                        @php($ar = 1)
                        @foreach($grids['achvmnt_ratio'] as $achvmnt_ration)
                            @if($achvmnt_ration > 0)
                                @php($ar++)
                            @endif
                            <td class="text-right">{{$achvmnt_ration}}</td>
                            @php($total_achv_ratio += (float)$achvmnt_ration)
                        @endforeach
                        <td class="text-right">{{number_format($total_achv_ratio / $ar , 2)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    {{--</div>--}}
@else
    <div class="alert alert-info text-center"><h3><i class="fa fa-exclamation-triangle"></i> Please Search for result</h3></div>
@endif
<script>
    var view_type = '<?php echo @$view_column; ?>';
    var rowsGroup = [0]; // for zone
    var fixedColumn = 2; // for zone
    if(view_type == 'region')
    {
        rowsGroup = [0,1];
        fixedColumn = 3;
    }
    else if(view_type == 'territory')
    {
        rowsGroup = [0,1,2];
        fixedColumn = 4;
    }
    else if(view_type == 'house')
    {
        rowsGroup = [0,1,2,3];
        fixedColumn = 5;
    }
    else if((view_type == 'aso') || (view_type == 'route'))
    {
        rowsGroup = [0,1,2,3,4];
        fixedColumn = 6;
    }
    $('#lifting').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: fixedColumn
        },
        rowsGroup: rowsGroup,
        order: [],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['pageLength']
    });
//    $(document).on('click','.digdown', function (e) {

//    $('.digdown').click(function(e){
//        e.preventDefault();
//        var url = "<?php //echo url('digdownDSSAjax');?>";
//        var _token = '<?php //echo csrf_token() ?>';
//        var loctype = $(this).data('loctype');
//        var locid = $(this).data('locid');
//        $.ajax({
//            url: url,
//            type: 'POST',
//            data: $('#grid_list_frm').serialize()
//            +'&_token='+_token
//            +'&loctype='+loctype
//            +'&locid='+locid,
//            beforeSend: function(){ $('.loadingImage').show();},
           // success: function (d) {//
//                $('.showSearchDataUnique').html(d);
//                $('.loadingImage').hide();
//            }
//        });
//    });

</script>