function myConfiguration()
{
    $('#dataTableId').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            leftColumns: 1
        },

        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['pageLength']
    });

    $('#dataTableIdWithoutFixed').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['pageLength']
    });

    $('#dataTableId_withoutPaginate').dataTable({
        bPaginate: false,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['pageLength']
    });
}


$('.monthpicker').datetimepicker({
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 3,
    minView: 3,
    //setDate:new Date(),
    format: "MM-yyyy",
    viewMode: "months",
    minViewMode: "months",
    forceParse: 0
});

$('.yearpicker').datepicker({
    weekStart: 1,
    autoclose: 1,
    todayBtn:  1,
    todayHighlight: 1,
    minView: 3,
    minViewMode: 'years',
    viewMode: "years",
    format: 'yyyy',
    forceParse: 0
});

$(document).ready(function () {
    $(".date_range").daterangepicker({
        "locale":{
            format:"DD/MM/YYYY"
        },
        "autoApply": true,
        "showDropdowns": true,
        "alwaysShowCalendars": true
    });

    $(".date_range_converted").daterangepicker({
        "locale":{
            format:"YYYY-MM-DD"
        },
        "autoApply": true,
        "showDropdowns": true,
        "alwaysShowCalendars": true
    });

    $(".date_picker_converted").datetimepicker({
        autoclose: 1,
        startView: 2,
        minView: 2,
        format: "dd-mm-yyyy"
    });
});

function confirmation_alert(targetUrl) {
    //alert(targetUrl);
    $("#dialog").dialog({
        buttons : {
            "Confirm" : function() {
                window.location.href = targetUrl;
            },
            "Cancel" : function() {
                $(this).dialog("close");
            }
        }
    });
    $("#dialog").dialog("open");
}


function alertMessage(text) {
    $('.customText').text(text);
    $('#alertError').modal('show');
}

