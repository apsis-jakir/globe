<div class="panel panel-default " id="search_by" style='display:none'>
    <div class="panel-heading" style="overflow: hidden;">
        <span style="float: left;">SEARCH BY</span>
        <span class="top_search" style="float: right; color: #C9302C; cursor: pointer;"><span class="glyphicon glyphicon-remove"></span></span>
    </div>
    <div class="panel-body">
        <form data-toggle="validator" role="form" id="grid_list_frm" action="" method="post">
            <div class="12">
                @include($searching_options)
            </div>
            <div class="col-lg-12 text-right">
                <input search_type="download" class="btn btn-primary search_unique_submit" type="button" value="Export">
                @if(isset($view_load))
                    @if($view_load == 0)
                        <input search_type="show" class="btn btn-primary search_unique_submit_ajax" type="submit" value="Search">
                    @endif
                @else
                    <input search_type="show" class="btn btn-primary search_unique_submit" type="submit" value="Search">
                @endif
            </div>
        </form>
    </div>
</div>



@include('grid.grid_view_css_js')
@if(isset($searchAreaOption['show']) && $searchAreaOption['show'] == 1)
    <script>
        $(document).ready(function(){
            $("#top_search").trigger('click');
        });
    </script>
@endif


