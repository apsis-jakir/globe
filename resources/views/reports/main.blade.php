@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                {!! $header_level !!}
            </h1>
            {!! $breadcrumb !!}
        </section>

        @include('grid.search_area_unique')

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header" style="overflow: hidden">
                        {{--<span class="box-title" style="float: left;">Stock List</span>--}}
                        <span class="advanchedSearchToggle" style="float: right;">
                            <button
                                    type="button"
                                    class="btn btn-primary panel-controller"
                                    id="top_search">
                                    <i class="fa fa-search"></i> Advanced Search
                            </button>
                        </span>
                    </div>
                    <div class="box-body showSearchDataUnique">
                        {{--@include('reports.ajax.'.$view)--}}
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
        </style>

@endsection