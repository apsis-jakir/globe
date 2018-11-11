    <div class="row">
        <div class="col-lg-12 text-center">
            <h3>{{$accountStatementHouseInfo->point_name}} - {{$accountStatementHouseInfo->code}}</h3>
            <h4>Statement Date : {{$daterange[0]}}</h4>
            <h4>Opening Balance : {{$accountStatementHouseInfo->cb}}</h4>
        </div>
    </div>

    <table id="dataTableIdGroup" class="table table-bordered table-striped dataTable no-footer" width="100%">
        <thead>
        {{--<tr>--}}
            {{--<th colspan="4" style="text-align: center">--}}
                {{--<h3>{{$accountStatementHouseInfo->point_name}} - {{$accountStatementHouseInfo->code}}</h3>--}}
                {{--<h2>Statement Date : {{$daterange[0]}}</h2>--}}
                {{--<h2>Opening Balance : {{$accountStatementHouseInfo->cb}}</h2>--}}
            {{--</th>--}}
        {{--</tr>--}}
        <tr>
            <th>Date</th>
            <th>Deposit Amount</th>
            <th>Lifting Amount</th>
            <th>Balance</th>
        </tr>


        </thead>
        <tbody>


            @foreach($statement as $key=> $val)
                <tr>
                    <td>{{$val->sale_date}}</td>
                    <td>{{number_format($val->order_da,2)}}</td>
                    <td>{{number_format($val->total_sale_amount,2)}}</td>
                    <td>{{number_format($val->house_current_balance,2)}}</td>
                </tr>
            @endforeach

        </tbody>
    </table>


<script>
    $('#dataTableIdGroup').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 1
        },
        rowsGroup: [0],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['pageLength']
    });
</script>
