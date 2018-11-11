@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                Ordering Brand And SKU
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Ordering</a></li>
                <li class="active">Brand and SKU</li>
            </ol>
        </section>



        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Ordering</h3>
                    </div>
                    <div class="box-body">
                        <table id="sort" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Brand Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $brand)
                                <tr style="cursor: move;" brands_id="{{$brand->id}}">
                                    <td>{{$brand->brand_name}}</td>
                                    <td><a href="{{URL::to('showSkue/'.$brand->id)}}"> <i class="fa fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>



            </div>
        </div>
        <script>

            var fixHelperModified = function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width())
                    });
                    return $helper;
                },
                updateIndex = function(e, ui) {
                    $('td.index', ui.item.parent()).each(function (i) {
                        $(this).html(i + 1);
                    });
                    var p =($.map($(this).find('tr'), function(el) {
                        return $(el).attr("brands_id") + "_" + $(el).index();
                    }));
                    var _token = '<?php echo csrf_token() ?>';
                    $.ajax({
                        url: "<?php echo URL::to('brand-wise-productivity'); ?>",
                        type: "POST",
                        data: {_token:_token,order:p},
                        success: function(feedback){
                            // $("#test").html(feedback);
                        }
                    });
                };
            $("#sort tbody").sortable({
                helper: fixHelperModified,
                stop: updateIndex
            }).disableSelection();
        </script>
@endsection