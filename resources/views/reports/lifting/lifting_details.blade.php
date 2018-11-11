@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                {{$header_level}}
            </h1>
            {!! $breadcrumb !!}
        </section>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body showSearchDataUnique">

                        <div class="text-bold text-blue">{{$position}}</div>

                        @if(count($memo_structure) > 0 && count($lifting_list) > 0)

                            <table id="lifting" class="table-bordered table dataTable">
                                <thead>
                                <tr>
                                    <th rowspan="3" style="vertical-align: middle; min-width: 80px;">{{ucfirst($type)}}</th>
                                    {!! parrentColumnTitleValue($view_report,3)['html'] !!}
                                    <th rowspan="3" style="vertical-align: middle">Lifting Info</th>
                                    @if(isset($memo_structure))
                                        @foreach($memo_structure as $category_key=>$category_value)
                                            <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}"
                                                style="text-align: center">{{$category_key}}</th>
                                        @endforeach
                                    @endif
                                    <th rowspan="3" style="vertical-align: middle">Lifting Amount</th>
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
                                                    <th colspan="{{$level}}" style="text-align: center">{{$sku_value}}</th>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($lifting_list) && count($lifting_list) > 0)
                                    @foreach($lifting_list as $key=> $value)
                                        @if($key!=='')
                                            <tr>
                                                <td style="background: #fff; vertical-align: middle;"><a target="_blank" href="{{$value['link']}}">{{$key}}</a></td>
                                                @foreach(parrentColumnTitleValue($view_report,3)['value'] as $pctv)
                                                    <td style="background: #fff; vertical-align: middle">{{$value[$pctv]}}</td>
                                                @endforeach
                                                <td style="background:#fff;">Request</td>
                                                @foreach($value['req'] as $rk=>$rv)
                                                    <td>{{$rv}}</td>
                                                @endforeach
                                                <td style="vertical-align: middle">{{number_format((($value['amount'])?$value['amount']:0),2)}}</td>
                                                <td style="vertical-align: middle">{{number_format((($value['deposit'])?$value['deposit']:0),2)}}</td>
                                                <td style="vertical-align: middle">{{number_format((($value['balance'])?$value['balance']:0),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td style="background: #fff; vertical-align: middle"><a target="_blank" href="{{$value['link']}}">{{$key}}</a></td>
                                                @foreach(parrentColumnTitleValue($view_report,3)['value'] as $pctv)
                                                    <td style="vertical-align: middle">{{$value[$pctv]}}</td>
                                                @endforeach
                                                <td style="background:#fff;">Delivery</td>
                                                @foreach($value['del'] as $dk=>$dv)
                                                    <td>{{$dv}}</td>
                                                @endforeach
                                                <td style="vertical-align: middle">{{number_format((($value['amount'])?$value['amount']:0),2)}}</td>
                                                <td style="vertical-align: middle">{{number_format((($value['deposit'])?$value['deposit']:0),2)}}</td>
                                                <td style="vertical-align: middle">{{number_format((($value['balance'])?$value['balance']:0),2)}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                @if(isset($lifting_list_total) && count($lifting_list_total) > 0 && ($type == 'zone') && (isset($lifting_list) && count($lifting_list) > 0))
                                    <tr>
                                        <td style="background: #fff;"><b>National/Total</b></td>

                                        <td style="background: #fff;">Request</td>
                                        @foreach($lifting_list_total['req'] as $rk=>$rv)
                                            <td style="background: #fff;">{{$rv}}</td>
                                        @endforeach
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td style="background: #fff;"></td>
                                        <td style="background: #fff;">Delivery</td>
                                        @foreach($lifting_list_total['del'] as $rk=>$rv)
                                            <td style="background: #fff;">{{$rv}}</td>
                                        @endforeach
                                        <td style="vertical-align: middle">{{number_format($lifting_list_total['amount'],2)}}</td>
                                        <td style="vertical-align: middle">{{number_format($lifting_list_total['deposit'],2)}}</td>
                                        <td style="vertical-align: middle">{{number_format($lifting_list_total['balance'],2)}}</td>

                                    </tr>
                                @endif
                                </tfoot>
                            </table>
                        @else
                            <div class="alert alert-danger text-center"><h3><i class="fa fa-exclamation-triangle"></i> No Data Found</h3></div>
                        @endif





                    </div>
                </div>
            </div>
        </div>
        <script>
            //    $('#dataTableIdCustom').dataTable({
            //        scrollY: "calc(125vh - 380px)",
            //        scrollX: true,
            //        scrollCollapse: true,
            //        fixedColumns:   {
            //            leftColumns: 1
            //        },
            //        rowsGroup: [0,34,35,36],
            //        bPaginate: true,
            //        dom: 'Bfrtip',
            //        responsive: true,
            //        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
            //        "pageLength": 50,
            //        buttons: ['excel','print', 'pageLength']
            //    });
            var view_type = '<?php echo @$view_report; ?>';
            var rowsGroup = [0]; // for zone
            var fixedColumn = 2; // for zone
            if(view_type == 'Region')
            {
                rowsGroup = [0,1];
                fixedColumn = 3;
            }
            else if(view_type == 'Territory')
            {
                rowsGroup = [0,1,2];
                fixedColumn = 4;
            }
            else if(view_type == 'House')
            {
                rowsGroup = [0,1,2,3];
                fixedColumn = 5;
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
        </script>
        <style>
            th{background: #fff;}
            th, td { white-space: nowrap; }
            div.dataTables_wrapper {
                width: 100%;
                margin: 0 auto;
            }
            thead th{
                padding:3px 18px;
            }
            .boo-table thead th{
                vertical-align:middle;
            }
            table.dataTable thead th, table.dataTable thead td {
                border-bottom: 0!important;
            }
            .DTFC_LeftBodyWrapper{
                top:-14px !important;
            }

            thead th{
                background: #ccc;
            }

            tfoot {
                display: table-header-group;
            }

            th, td {
                white-space: pre-wrap !important;
            }
        </style>

@endsection