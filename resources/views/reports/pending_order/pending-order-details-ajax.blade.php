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



                        @if(isset($pending_orders_details) && count($pending_orders_details) > 0)
                            <table id="dataTableId" class="table-bordered table dataTable">
                                <thead>
                                <tr>
                                    <th rowspan="3" style="vertical-align: middle">Order Date</th>
                                    <th rowspan="3" style="vertical-align: middle">Order Number</th>
                                    {!! parrentColumnTitleValue('Route',3)['html'] !!}
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
                                @foreach($pending_orders_details as $key => $info)
                                    <tr>
                                        <th>{{$info['order_date']}}</th>
                                        <th>{{$info['order_number']}}</th>
                                        @foreach(parrentColumnTitleValue('Route',3)['value'] as $pctv)
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





                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                myConfiguration();
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
        </style>

@endsection