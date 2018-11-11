<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
        <th>Sender Name</th>
        <th>Sender Phone</th>
        <th>House</th>
        <th>Sales Type</th>
        <th>Sale Total</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        <tr>
            <td>{{$sale->sender_name}}</td>
            <td>{{$sale->sender_phone}}</td>
            <td>{{$sale->dh_name}}</td>
            <td>{{$sale->sale_type}}</td>
            <td>{{$sale->sale_total_sku}}</td>
            <td>{{$sale->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>



