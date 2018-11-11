<div class="panel panel-default " id="search_by" style='display:none'>
    <div class="panel-heading" style="overflow: hidden;">
        <span style="float: left;">SEARCH BY</span>
        <span class="top_search" style="float: right; color: #C9302C; cursor: pointer;"><span class="glyphicon glyphicon-remove"></span></span>
    </div>
    <div class="panel-body">
        <form id="grid_list_frm" action="" method="post">
            @include($searching_options)
            <div class="col-lg-12 text-right">

                <input class="btn btn-primary search_unique_submit" type="button" value="Search">
            </div>
        </form>
    </div>
</div>


@include('grid.grid_view_css_js')