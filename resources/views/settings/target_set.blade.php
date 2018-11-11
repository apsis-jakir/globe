@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                Set Target
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Settings</a></li>
                <li class="active">Set Target</li>
            </ol>
        </section>



        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        {{--<h3 class="box-title">Set Target</h3>--}}
                    </div>
                    <div class="box-body">
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <p>{{ \Session::get('success') }}</p>
                                </div><br />
                            @endif
                            <form  data-toggle="validator" role="form" method="POST" action="{{URL::to('targetSubmit')}}" accept-charset="UTF-8" id="" class="form-horizontal">
                                @csrf
                                    <input id="target_type" type="hidden" value="{{$type}}" name="target_type">
                                    <input type="hidden" name="edit" value="<?php echo $target_month; ?>">
                                    <div class="col-xs-5">
                                        <div class="form-group ">
                                            <label for="category_name" class="col-md-3 control-label">Target&nbsp;Month<span style="color: #f00;">*</span></label>
                                            <div class="col-md-9">
                                                <input name="target_month" required class="form-control monthpicker" type="text" id="target_month" value="<?php echo ($target_month)?$target_month:date('F-Y'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="form-group ">
                                            <label for="category_name" class="col-md-3 control-label">Base Date<span style="color: #f00;">*</span></label>
                                            <div class="col-md-9">
                                                <input class="form-control date_range" type="text" id="base_date" name="base_date" value="<?php echo (($target_month)?$existingValue[0]->base_date:''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group ">
                                            <div class="col-md-9">
                                                <input class="btn btn-primary" type="button" id="target_process" value="Process">
                                            </div>
                                        </div>
                                    </div>

                                {{--<div class="col-xs-12 target_set_ajax_data_show" style="overflow: scroll; height: calc(100vh - 200px)">--}}
                                    <div class="col-xs-12 target_set_ajax_data_show" style="">

                                        @if($target_month)
                                            @include('settings.target_set_ajax_data_show')
                                        @endif

                                </div>
                                <div class="col-xs-12" style="text-align: right">
                                    <?php
                                        if($target_month)
                                        { ?>
                                            <br/>
                                            <a class="btn btn-danger" href="{{URL::to('targetRemove/'.$type.'/'.$target_month)}}">Remove This Configuration</a>
                                        <?php }
                                    ?>

                                    {{--<input type="submit" value="Confirm" class="btn btn-primary">--}}
                                </div>
                            </form>


                    </div>
                </div>
            </div>
        </div>



        <script src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>

        <script>
            $(document).on('click','#target_process',function(){
                var target_month = $('#target_month').val();
                var base_date = $('#base_date').val();
                var _token = '<?php echo csrf_token() ?>';
                var target_type = $('#target_type').val();
                var targetUrl = baseUrl('targetSet/'+target_type+'/'+target_month);
                if(target_month && base_date)
                {
                    $.ajax({
                        url: "<?php echo URL::to('targetSetProcess'); ?>",
                        type: 'POST',
                        beforeSend: function(){ $('.loadingImage').show();},
                        data: {_token:_token,target_month: target_month, base_date: base_date,target_type:target_type},
                        success: function (data) {
                            if(data == false)
                            {
                                confirmation_alert(targetUrl);
                            }
                            else
                            {
                                $('.target_set_ajax_data_show').html(data);
//                                $('#d_table').DataTable( {
//                                    scrollY: "calc(100vh - 380px)",
//                                    scrollX: true,
//                                    scrollCollapse: true,
//                                    paging: false,
//                                    searching: false,
//                                    ordering: false,
//                                    fixedColumns: {
//                                        leftColumns: 1
//                                    }
//                                } );
                            }
                            $('.loadingImage').hide();
                        }
                    });
                }
                else
                {
                    alertMessage('Star(*) marks field are required.');
                }
            });
            $('#d_table').DataTable( {
                scrollY: "calc(100vh - 380px)",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                searching: false,
                ordering: false,
                fixedColumns: {
                    leftColumns: 1
                }
            } );
        </script>
        <style>
            th{background: #fff;}
            th, td { white-space: nowrap; }
            div.dataTables_wrapper {
                width: 100%;
                margin: 0 auto;
            }
            /*input[type="number"]{*/
                /*padding:0 6px;*/
            /*}*/
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