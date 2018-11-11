@extends('layouts.app')

@section('content')


  <div class="content-wrapper">
    <ul class="nav nav-tabs">
      <li class="active"><a style="font-weight: bold" data-toggle="tab" href="#home">Dashboard</a></li>
      <li><a style="font-weight: bold" data-toggle="tab" href="#menu1">Productivity Summary</a></li>
    </ul>



    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <div class="row" style="background: #fff !important;">
          <div class="col-lg-6">@include('chart/column')</div>
          <div class="col-lg-6">@include('chart/line')</div>
          <div class="col-lg-4">@include('chart/pie',$pieConfig)</div>
          <div class="col-lg-4">@include('chart/pie',$pieFizupConfig)</div>
          <div class="col-lg-4">@include('chart/pie',$pieUroorangeConfig)</div>
          <div class="col-lg-4">@include('chart/pie',$pieMangoliConfig)</div>
          <div class="col-lg-4">@include('chart/pie',$pieUroOrangeeConfig)</div>
          <div class="col-lg-4">@include('chart/pie',$pieOtherConfig)</div>
        </div>
      </div>
      <div id="menu1" class="tab-pane fade">
        <br/>
        @include('home')
      </div>

    </div>






  </div>


@stop