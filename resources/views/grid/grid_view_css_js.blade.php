
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.js"></script>--}}
<script>
    // for excel complex header print
    var _fnGetHeaders = function(dt) {
        var thRows = $(dt.header()[0]).children();
        var numRows = thRows.length;
        var matrix = [];

        // Iterate over each row of the header and add information to matrix.
        for ( var rowIdx = 0;  rowIdx < numRows;  rowIdx++ ) {
            var $row = $(thRows[rowIdx]);

            // Iterate over actual columns specified in this row.
            var $ths = $row.children("th");
            for ( var colIdx = 0;  colIdx < $ths.length;  colIdx++ )
            {
                var $th = $($ths.get(colIdx));
                var colspan = $th.attr("colspan") || 1;
                var rowspan = $th.attr("rowspan") || 1;
                var colCount = 0;

                // ----- add this cell's title to the matrix
                if (matrix[rowIdx] === undefined) {
                    matrix[rowIdx] = [];  // create array for this row
                }
                // find 1st empty cell
                for ( var j = 0;  j < (matrix[rowIdx]).length;  j++, colCount++ ) {
                    if ( matrix[rowIdx][j] === "PLACEHOLDER" ) {
                        break;
                    }
                }
                var myColCount = colCount;
                matrix[rowIdx][colCount++] = $th.text();

                // ----- If title cell has colspan, add empty titles for extra cell width.
                for ( var j = 1;  j < colspan;  j++ ) {
                    matrix[rowIdx][colCount++] = "";
                }

                // ----- If title cell has rowspan, add empty titles for extra cell height.
                for ( var i = 1;  i < rowspan;  i++ ) {
                    var thisRow = rowIdx+i;
                    if ( matrix[thisRow] === undefined ) {
                        matrix[thisRow] = [];
                    }
                    // First add placeholder text for any previous columns.
                    for ( var j = (matrix[thisRow]).length;  j < myColCount;  j++ ) {
                        matrix[thisRow][j] = "PLACEHOLDER";
                    }
                    for ( var j = 0;  j < colspan;  j++ ) {  // and empty for my columns
                        matrix[thisRow][myColCount+j] = "";
                    }
                }
            }
        }

        return matrix;
    };



    $(document).on('click', '#top_search, .top_search', function () {
        $('#search_by').slideToggle();
        $('.advanchedSearchToggle').slideToggle();
    });
    $(document).on('click', '#left_search', function () {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });


    $(document).on('click', '.search_unique_submit', function (e) {
        e.preventDefault();
        var url = "<?php echo $ajaxUrl; ?>";
        var _token = '<?php echo csrf_token() ?>';
        var search_type = $(this).attr('search_type');
        var error = 0;
        $('.mendatory').each(function(){
            var val = $(this).val();
            if(!val)
            {
                error = 1;
            }
        });

        if(error)
        {
            $('.loadingImage').show();
            $('.showSearchDataUnique').html('<h3 style="color:red; text-align: center">Star(*) marked fields are required.</h3>');
            $('.loadingImage').hide();
        }
        else
        {
            $.ajax({
                url: url,
                type: 'POST',
                //data: $('#grid_list_frm').serialize(),
                data: $('#grid_list_frm').serialize()+'&_token='+_token+'&search_type[]='+search_type,
                beforeSend: function(){ $('.loadingImage').show();},
                success: function (d) {
                    //alert(d);
                    if(search_type == 'show')
                    {
                        $('.showSearchDataUnique').html(d);
                        myConfiguration();
                        $('.top_search').click();
                    }
                    else{
                        window.location.href = './public/export/'+d;
                    }
                    $('.loadingImage').hide();
                }
            });
        }

    });
</script>






<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
{{--<script src="{{asset('public/js/buttons.html5.js')}}"></script>--}}
<script src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<style>
    #wrapper {
        padding-left: 0;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }

    #wrapper.toggled {
        padding-left: 250px;
    }

    #sidebar-wrapper {
        z-index: 1000;
        position: absolute;
        left: 260px;
        width: 0;
        height: 100%;
        margin-left: -250px;
        overflow-y: auto;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 250px;
    }

    #page-content-wrapper {
        width: 100%;
        position: absolute;
        padding: 15px;
    }

    #wrapper.toggled #page-content-wrapper {
        position: absolute;
        margin-right: -250px;
    }


    @media(min-width:768px) {
        #wrapper {
            padding-left: 260px;
        }

        #wrapper.toggled {
            padding-left: 0;
        }

        #sidebar-wrapper {
            width: 250px;
        }

        #wrapper.toggled #sidebar-wrapper {
            width: 0;
        }

        #page-content-wrapper {
            padding: 20px;
            position: relative;
        }

        #wrapper.toggled #page-content-wrapper {
            position: relative;
            margin-right: 0;
        }
    }


    /*for grid view search area*/
    .moresearchfield {
        display:none;
        background: #777 none repeat scroll 0 0;
        list-style: outside none none;
        padding: 0;
        position: absolute;
        right: 66px;
        border-radius: 4px;
        /*top: 33px;*/
        width: auto;
        z-index: 99;
    }
    .moresearchfield > li {
        cursor: pointer;
        padding: 2px 36px;
    }

    .moresearchfield > li:hover {
        background: #fff none repeat scroll 0 0;
    }

    .custom_label{
        cursor: pointer;
    }
    .btn-primary{
        background: #C9302C !important;
        border-color: #C9302C !important;
    }

    thead th{
        background: #ccc;
    }

    tfoot {
        display: table-header-group;
    }
</style>