@extends('layouts.app')

@section('content')
    <div class="content-wrapper">


        <section class="content-header">
            <h1>
                SMS Outbox
            </h1>
            {!! $breadcrumb !!}
        </section>

        @include('grid.search_area_unique')


        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header" style="overflow: hidden">
                        <span class="box-title" style="float: left;">SMS Outbox</span>
                        <span class="advanchedSearchToggle" style="float: right;">
                            <button
                                    type="button"
                                    class="btn btn-primary panel-controller"
                                    id="top_search">
                                    <i class="fa fa-search"></i> Advanced Search
                            </button>
                        </span>
                    </div>
                    <div class="box-body showSearchDataUnique" style="overflow: scroll;">
                        @include('reports.sms_outboxes_ajax')
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