@extends('layouts.app')

@section('content')
<div class="content-wrapper">


    <section class="content-header">
        <h1>
            Order List
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Stocks</a></li>
            <li class="active">Current Stocks</li>
        </ol>
    </section>

    @include('grid.search_area_unique')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header" style="overflow: hidden">
                    <span class="box-title" style="float: left;">Stock List</span>
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
                    @include('reports.current_stock_ajax')
                </div>


            </div>



        </div>
    </div>
    <script>
        $(document).ready(function(){
            myConfiguration();
        });
    </script>
    @endsection