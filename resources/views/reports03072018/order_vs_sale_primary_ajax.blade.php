<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
        <th>order id</th>
        <th>Distributor House</th>
        <th>Sender Name</th>
        <th>Brand</th>
        <th>SKU</th>
        <th>Short Name</th>
        <th>Order Quantity</th>
        <th>Delivery Quantity</th>
        <th>Order Date</th>
        <th>Delivery Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ordervssale as $k=>$val)
        @if($val->quantity > 0)
            <tr>
                <td>{{$val->oid}}</td>
                <td>{{$val->dh_name}}</td>
                <td>{{$val->requester_name}}</td>
                <td>{{$val->brand_name}}</td>
                <td>{{$val->sku_name}}</td>
                <td>{{$val->short_name}}</td>
                <td>{{$val->quantity}}</td>
                <td>{{$val->salequantity}}</td>
                <td>{{$val->order_date}}</td>
                <td>{{$val->sale_date}}</td>
            </tr>
        @endif

    @endforeach
    </tbody>
</table>



