@extends('layouts.app')
@section('content')
<style>
    .tabulator .tabulator-col-title{
        text-align:center;
        vertical-align: middle;
    }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.5.3/js/tabulator.min.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {!!$header_level!!}
        </h1>
        {!! $breadcrumb !!}
    </section>
    @include('grid.search_area_unique')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header" style="overflow: hidden">
                    <span class="advanchedSearchToggle" style="float: right;">
                            <button type="button"
                                    class="btn btn-primary panel-controller"
                                    id="top_search">
                                    <i class="fa fa-search"></i> Advanced Search
                            </button>
                        </span>
                </div>
                <div class="box-body showSearchDataUnique">
                    <div id="example-table"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.search_unique_submit_ajax', function (e) {
                e.preventDefault();
//                $("#example-table").tabulator("destroy");
                var rdata;
                var url = "<?php echo $ajaxUrl; ?>";
                var _token = '<?php echo csrf_token() ?>';
                var error = 0;
                $('.mendatory').each(function(){
                    var val = $(this).val();
                    if(!val){
                        error = 1;
                    }
                });
                if(error){
                    $('.loadingImage').show();
                    $('.showSearchDataUnique').html('<h3 style="color:red; text-align: center">Star(*) marks field are required.</h3>');
                    $('.loadingImage').hide();
                }else{
                    $.ajax({
                        url: url,
                        type: 'POST',
                        //data: $('#grid_list_frm').serialize(),
                        data: $('#grid_list_frm').serialize()+'&_token='+_token,
                        beforeSend: function(){
                            $('.loadingImage').show();
                        },
                        success: function (data) {
                            var dt = JSON.parse(data);
                            rdata = dt.row
                            console.log(dt.col);
                            $("#example-table").tabulator({
                                height:"400px",
                                layout:"fitColumns",
                                placeholder:"No Data Set",
                                paginationSize:10,
                                columns:dt.col
                            });
//                            $("#example-table").tabulator("destroy");
//                            $("#example-table").tabulator("redraw");
                            $("#example-table").tabulator("setData", rdata);
                            $('.loadingImage').hide();
                        }
                    });
                }

            });
        });
        $('.gen').click(function(){
            $("#example-table").tabulator("setData", rdata);
        });
        $('.add').click(function(){
            el = $(this);
            if(el.is(':checked')){
                var coltitle = el.data('name');
                $("#example-table").tabulator("download", "xlsx", "data.xlsx");
            }
        });
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.5.3/css/tabulator.min.css" rel="stylesheet">
    @endsection