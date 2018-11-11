<table id="dataTableIdCustom" class="table-bordered table dataTable">
    <thead>

    <tr>
        <th rowspan="3" style="vertical-align: middle">{{$view_report}}</th>
        {!! parrentColumnTitleValue($view_report,3)['html'] !!}
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                     <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}" style="text-align: center">{{$category_key}}</th>
            @endforeach
        @endif
        <th rowspan="3" style="vertical-align: middle"  align="right">Current Balance</th>

    </tr>
    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                        <th colspan="{{count($brand_value) * $level}}" style="text-align: center">{{$brand_key}}</th>
                @endforeach
            @endforeach
        @endif
    </tr>
    <tr>
        @if(isset($memo_structure))
            @foreach($memo_structure as $category_key=>$category_value)
                @foreach($category_value as $brand_key=>$brand_value)
                    @foreach($brand_value as $sku_key=>$sku_value)
                          <th colspan="{{$level}}" style="text-align: center">{{$sku_value}}</th>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tr>
    </thead>
    <tbody>
            @if(isset($stock_list))
                @foreach($stock_list as $parentkey=> $parent_value)
                    <tr>
                        <td style="background: #fff;"><a target="_blank" href="{{URL::to('stock-memo/'.$parent_value['table_id'].'/'.$config['table'].'/'.$config['field_id'])}}">{{$parentkey}}</a></td>
                        @foreach(parrentColumnTitleValue($view_report,3)['value'] as $pctv)
                            <td>{{$parent_value['parents'][$pctv]}}</td>
                        @endforeach
                        @foreach($SKUList as $short_name_key=>$sku)
                            <td>{{$parent_value['data'][$short_name_key]/$sku['pack_size']}}</td>
                        @endforeach
                        <td  align="right">{{number_format($parent_value['cb'],2)}}</td>
                    </tr>
                 @endforeach
            @endif
    </tbody>
</table>


<script>
    {{----}}





    $('#dataTableIdCustom').dataTable({
        scrollY: "calc(125vh - 380px)",
        ordering: false,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            leftColumns: 1
        },
        rowsGroup: [0],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: [
                { extend: 'pageLength'}
            ]
    });



//    $(document).ready(function() {
//        var xlsBuilder = {
//            filename: 'business-group-sharers-',
//            sheetName: 'business-group-sharers-',
//            customize: function(xlsx) {
//                var sheet = xlsx.xl.worksheets['sheet1.xml'];
//                var downrows = 4;
//                var clRow = $('row', sheet);
//                var msg;
//
//                clRow.each(function() {
//                    var attr = $(this).attr('r');
//                    var ind = parseInt(attr);
//                    ind = ind + downrows;
//                    $(this).attr("r", ind);
//                });
//
//
//                $('row c ', sheet).each(function() {
//                    var attr = $(this).attr('r');
//                    var pre = attr.substring(0, 1);
//                    var ind = parseInt(attr.substring(1, attr.length));
//                    ind = ind + downrows;
//                    $(this).attr("r", pre + ind);
//                });
//
//                function Addrow(index, data) {
//
//                    msg = '<row xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" r="' + index + '">';
//                    for (var i = 0; i < data.length; i++) {
//                        var key = data[i].k;
//                        var value = data[i].v;
//                        msg += '<c t="inlineStr" r="' + key + index + '">';
//                        msg += '<is>';
//                        msg += '<t>' + value + '</t>';
//                        msg += '</is>';
//                        msg += '</c>';
//                    }
//                    msg += '</row>';
//                    return msg;
//                }
//                var r1 = Addrow(1, [{
//                    k: 'A',
//                    v: 'Export Date :'
//                }, {
//                    k: 'B',
//                    v: '10-Jan-2017'
//                }]);
//                var r2 = Addrow(2, [{
//                    k: 'A',
//                    v: 'Account Name :'
//                }, {
//                    k: 'B',
//                    v: 'Melvin'
//                }]);
//                var r3 = Addrow(3, [{
//                    k: 'A',
//                    v: 'Account Id :'
//                }, {
//                    k: 'B',
//                    v: '021456321'
//                }]);
//
//                sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + sheet.childNodes[0].childNodes[1].innerHTML;
//            },
//            exportOptions: {
//                columns: [0, 1, 2, 3]
//            }
//        }
//        $('#dataTableIdCustom').DataTable({
//            dom: 'Bfrtip',
//            buttons: [
//                $.extend(true, {}, xlsBuilder, {
//                    extend: 'excel'
//                })
//            ]
//        });
//    });

</script>