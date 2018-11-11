{{--@extends('layouts.app')--}}

{{--@section('content')--}}

  {{--<div class="content-wrapper">--}}
    {{--<section class="content">--}}


      {{-------------------------------------------------------------------------------------------------------}}
      <div class="row">
        <div class="" style="background: #EA6045;margin-left: 29px;text-align: center;color: #fff;font-weight: bold;padding: 5px 0;border-radius: 5px; font-size: 20px;box-shadow: 6px 2px 3px 0 #000"> Productivity Summary Of - {{date('F j, Y')}}</div>
        <div class="col-lg-9">
          <div class="col-md-4">
            <div class="widget target-outlet">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="red-skin"><i class="fa fa-area-chart"></i></span>
                <p><i class="fa  fa-arrow-up up"></i> Target Outlet</p>
                <h3></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="widget visited-outlet">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="sky-skin"><i class="fa fa-usd"></i></span>
                <p><i class="fa  fa-arrow-down down"></i> Visited Outlet</p>
                <h3></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="widget successfull-call">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="purple-skin"><i class="fa fa-bullhorn"></i></span>
                <p><i class="fa  fa-arrow-down down"></i> Success&nbspCall</p>
                <h3></h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="widget strike-rate">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="pink-skin"><i class="fa fa-shopping-cart"></i></span>
                <p><i class="fa  fa-arrow-up up"></i> Strike Rate</p>
                <h3></h3>
              </div>
            </div>
          </div>

          @if((Auth::user()->user_type == 'devlopment') || (Auth::user()->user_type == 'zone') || (Auth::user()->user_type == 'region') || (Auth::user()->user_type == 'territory'))
          <div class="col-md-4">
            <div class="widget no-lifter">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="pink-skin"><i class="fa fa-shopping-cart"></i></span>
                <p><i class="fa  fa-arrow-up up"></i> Non Lifter</p>
                <h3></h3>
              </div>
            </div>
          </div>

          @endif

          <div class="col-md-4">
            <div class="widget no-orders">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="pink-skin"><i class="fa fa-shopping-cart"></i></span>
                <p><i class="fa  fa-arrow-up up"></i> No. Of Order</p>
                <h3></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="widget no-sales">
              <div class="widget-controls">
                <span class="refresh-content"><i class="fa fa-refresh"></i></span>
              </div><!-- Widget Controls -->
              <div class="mini-stats ">
                <span class="pink-skin"><i class="fa fa-shopping-cart"></i></span>
                <p><i class="fa  fa-arrow-up up"></i> No. Of Sales</p>
                <h3></h3>
              </div>
            </div>
          </div>



        </div>

        <div class="col-lg-3 bwproductivity" style="margin-top: 13px;">
          <div class="row bwproductivitytitle" style="">Brand Wise Productivity</div>
            @foreach($brands as $brand)
                <div class="row bwproductivityeachrow dynamic_{{$brand->id}}" id="{{$brand->id}}" name="{{$brand->brand_name}}" style=" ">
                  <img src="{{URL::to('public/img/horijontalloader.gif')}}">
                </div>
              @endforeach
      </div>
<style>


</style>
      {{------------------------------------------------------------------------------------------------------------------------------}}
        <script>
            $(document).ready(function () {
                var _token = '<?php echo csrf_token() ?>';
                $('.bwproductivityeachrow').each(function () {
                    var brand_id = $(this).attr('id');
                    var brand_name = $(this).attr('name');

                    $.ajax({
                        url: "<?php echo URL::to('dashboard-brand-wise-productivity'); ?>",
                        type: "POST",
                        data: {_token:_token,brand_id:brand_id,brand_name:brand_name},
                        success: function(feedback){
                            $('.dynamic_'+brand_id).html(feedback);
                        }
                    });

                });
            });

        </script>
      <script>
          function target_outlet() {
              $(".target-outlet .refresh-content").addClass('fa-spin');
              $(".target-outlet").addClass('loading-wait');
              $('.target-outlet .mini-stats h3').load('dashboard-target-outlet', function(){
                  $(".target-outlet").removeClass('loading-wait');
                  $(".target-outlet .refresh-content").removeClass('fa-spin');
              });
          }
          function visited_outlet() {
              $(".visited-outlet .refresh-content").addClass('fa-spin');
              $(".visited-outlet").addClass('loading-wait');
              $('.visited-outlet .mini-stats h3').load('dashboard-visited-outlet', function(){
                  $(".visited-outlet").removeClass('loading-wait');
                  $(".visited-outlet .refresh-content").removeClass('fa-spin');
              });
          }
          function successfull_call() {
              $(".successfull-call .refresh-content").addClass('fa-spin');
              $(".successfull-call").addClass('loading-wait');
              $('.successfull-call .mini-stats h3').load('dashboard-successfull-call', function(){
                  $(".successfull-call").removeClass('loading-wait');
                  $(".successfull-call .refresh-content").removeClass('fa-spin');
              });
          }
          function no_lifter() {
              $(".no-lifter .refresh-content").addClass('fa-spin');
              $(".no-lifter").addClass('loading-wait');
              $('.no-lifter .mini-stats h3').load('dashboard-no-lifter', function(){
                  $(".no-lifter").removeClass('loading-wait');
                  $(".no-lifter .refresh-content").removeClass('fa-spin');
              });
          }
          function no_orders() {
              $(".no-orders .refresh-content").addClass('fa-spin');
              $(".no-orders").addClass('loading-wait');
              $('.no-orders .mini-stats h3').load('dashboard-no-orders', function(){
                  $(".no-orders").removeClass('loading-wait');
                  $(".no-orders .refresh-content").removeClass('fa-spin');
              });
          }
          function no_sales() {
              $(".no-sales .refresh-content").addClass('fa-spin');
              $(".no-sales").addClass('loading-wait');
              $('.no-sales .mini-stats h3').load('dashboard-no-sales', function(){
                  $(".no-sales").removeClass('loading-wait');
                  $(".no-sales .refresh-content").removeClass('fa-spin');
              });
          }
          function strike_rate() {
              $(".strike-rate .refresh-content").addClass('fa-spin');
              $(".strike-rate").addClass('loading-wait');
              $('.strike-rate .mini-stats h3').load('dashboard-strike-rate', function(){
                  $(".strike-rate").removeClass('loading-wait');
                  $(".strike-rate .refresh-content").removeClass('fa-spin');
              });
          }
          $(document).on('click','.target-outlet .refresh-content',function () {
              target_outlet();
          });
          $(document).on('click','.visited-outlet .refresh-content',function () {
              visited_outlet();
          });
          $(document).on('click','.successfull-call .refresh-content',function () {
              successfull_call();
          });
          $(document).on('click','.no-lifter .refresh-content',function () {
              no_lifter();
          });
          $(document).on('click','.no-orders .refresh-content',function () {
              no_orders();
          });
          $(document).on('click','.no-sales .refresh-content',function () {
              no_sales();
          });
          $(document).on('click','.strike-rate .refresh-content',function () {
              strike_rate();
          });






          $(document).ready(function() {
              target_outlet();
          });
          $(document).ready(function() {
              visited_outlet();
          });
          $(document).ready(function() {
              successfull_call();
          });
          $(document).ready(function() {
              no_lifter();
          });
          $(document).ready(function() {
              no_orders();
          });
          $(document).ready(function() {
              no_sales();
          });
          $(document).ready(function() {
              strike_rate();
          });
      </script>
      <style>
        .widget {
          background: #ffffff none repeat scroll 0 0;
          float: left;
          margin-top: 14px;
          position: relative;
          width: 100%;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
          -ms-border-radius: 5px;
          -o-border-radius: 5px;
          border-radius: 5px;
        }

        .widget-controls {
          padding-right: 10px;
          padding-top: 10px;
          position: absolute;
          right: 0;
          top: 0;
          z-index: 1;
        }

        .widget-controls > span {
          color: #b8b8b8;
          cursor: pointer;
          float: right;
          font-size: 13px;
          line-height: 14px;
          margin-left: 8px;
        }

        .mini-stats {
          background: #ffffff none repeat scroll 0 0;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
          -ms-border-radius: 5px;
          -o-border-radius: 5px;
          border-radius: 5px;
          float: left;
          padding: 25px 15px;
          position: relative;
          width: 100%;
        }

        .mini-stats > span {
          border: 1px solid;
          -webkit-border-radius: 50%;
          -moz-border-radius: 50%;
          -ms-border-radius: 50%;
          -o-border-radius: 50%;
          border-radius: 50%;
          color: #fff;
          float: left;
          font-size: 21px;
          height: 40px;
          line-height: 40px;
          margin-right: 10px;
          text-align: center;
          width: 40px;
        }

        .red-skin {
          background-color: #ff6b6b;
          border-color: #ff6262 !important;
        }

        .mini-stats > p {
          color: #878888;
          display: block;
          font-family: Open sans;
          font-size: 11px;
          line-height: 20px;
          margin: 6px 0 0;
          text-transform: uppercase;
        }

        p {
          font-family: Open sans;
          color: #777777;
          line-height: 26px;
          font-size: 13px;
          letter-spacing: 0.3px;
        }

        .mini-stats > h3 {
          color: #4d575d;
          display: block;
          font-size: 18px;
          margin: 5px 0 0;
        }

        .mini-stats > p > i {
          margin-right: 4px;
        }
        .fa{
          display: contents !important;
        }
        .fa.up {
          color: #5bdd5e;
        }
        .fa.fa-refresh{
          color: #008000;
        }

        .sky-skin {
          background-color: #63d6ff;
          border-color: #28c4fc !important;
        }

        .purple-skin {
          background-color: #6e6eff;
          border-color: #7373ff !important;
        }

        .pink-skin {
          background-color: #f76fff;
          border-color: #f661ff !important;
        }

        .fa-spin {
          -webkit-animation: fa-spin 2s infinite linear;
          animation: fa-spin 2s infinite linear;
        }


        .widget.loading-wait::before {
          opacity: 0.5;
          visibility: visible;
        }

        .widget::before {
          background-color: #ffffff;
          background-image: url({{URL::to('public/img/refreshloader.gif')}});
          background-position: center center;
          background-repeat: no-repeat;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
          -ms-border-radius: 5px;
          -o-border-radius: 5px;
          border-radius: 5px;
          content: "";
          height: 100%;
          left: 0;
          opacity: 0;
          position: absolute;
          top: 0;
          width: 100%;
          z-index: 9;
          -webkit-transition: all 0.4s ease 0s;
          -moz-transition: all 0.4s ease 0s;
          -ms-transition: all 0.4s ease 0s;
          -o-transition: all 0.4s ease 0s;
          transition: all 0.4s ease 0s;
          visibility: hidden;
        }


        .bwproductivity{
          background: #2E3192;
          border-radius:5px;
        }
        .bwproductivitytitle{
          color: #fff;
          text-align: center;
          font-size: 23px;
          border-bottom: 1px solid #fff;
          padding: 5px;
        }
        .bwproductivityeachrow{
          padding: 10px;
          color: #fff;
          border-bottom:1px solid #fff;
        }
        .bwproductivityeachrow:last-child { border-bottom: none; }


        span.brand_name {
          font-weight: bold;
          margin-right: 10px;
        }

        span.brand_cal {
          font-size: 25px;
          font-weight: bold;
          color: aqua;
          margin-right: 8px;
        }
        /*span.arrow_sign {*/
          /*color: red;*/
          /*margin-right: 7px;*/
        /*}*/
        span.arrow_sort_sign_up{
          color: green;
          margin-right: 7px;
        }
        span.arrow_sort_sign_down{
          color: red;
          margin-right: 7px;
        }
        span.sdlw {
          font-size: 10px;
        }

        .dashboardTable td, th{
          font-size: 20px;
          padding: 4px 0px !important;
        }
        .content-wrapper {
          background-color: #ccc !important;
        }

      </style>

    {{--</section>--}}
   {{----}}
  {{--</div>--}}


{{--@stop--}}