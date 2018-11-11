<style>
    table{
        border-spacing: 0;
    }
    th, td { white-space: nowrap; }
    table.dataTable {
        clear: both;
        margin: 0 !important;
        background-color: #fff;
        max-width: none !important;
        border-collapse: separate !important;
    }
    div.DTFC_LeftWrapper table.dataTable, div.DTFC_RightWrapper table.dataTable {
        margin-bottom: 15px !important;
        margin-top: -1px !important;
        z-index: 2;
        background-color: #fff;
    }
    div.DTFC_LeftWrapper table.dataTable.no-footer, div.DTFC_RightWrapper table.dataTable.no-footer {
        border-top: 1px solid #eee !important;
        border-bottom: 1px !important;
    }
</style>
@if(!empty($grid_data))
<table id="rankingtable" class="table-bordered table dataTable">
    <thead>
    <th  style="vertical-align: middle">Serial Number</th>
    <th  style="vertical-align: middle">Employee CODE</th>
    <th  style="vertical-align: middle">Name</th>
    <th style="vertical-align: middle">Designation</th>
    <th  style="vertical-align: middle">Work Area</th>
    {!! parrentColumnTitleValue(ucfirst($view_column),'')['html'] !!}
    <th  style="vertical-align: middle">Last Month Achievement Point</th>
    <th  style="vertical-align: middle">This Month Achievement Point</th>
    </thead>
    <tbody>
        @php($i = 1)
        @foreach($grid_data as $grids)
            <tr>
                <td>{{$i++}}</td>
                <td style="vertical-align: middle">{!! $grids['code'] !!} </td>
                <td style="vertical-align: middle">{!! $grids['view_type'] !!} </td>
                <td style="vertical-align: middle">{!! $grids['designation'] !!} </td>
                <td style="vertical-align: middle">{!! $grids['workplace'] !!} </td>
                @foreach(parrentColumnTitleValue(ucfirst($view_column),3)['value'] as $pctv)
                    <td style="vertical-align: middle">{{$grids[$pctv]}}</td>
                @endforeach
                <td class="text-center">{!! $grids['prev_achv_point'] !!} </td>
                <td class="text-center"><i style="color: #{{$grids['achv_color']}}" class="fa fa-flag"></i>&nbsp;&nbsp;&nbsp;{!! $grids['achv_point'] !!} </td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
    <div class="alert alert-info text-center"><h3><i class="fa fa-exclamation-triangle"></i> Please Search for result</h3></div>
@endif
<script>
    $('#rankingtable').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 3
        },
//        rowsGroup: [0],
        order: [],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['pageLength']
    });
</script>