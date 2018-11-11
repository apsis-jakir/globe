<table id="dataTableIdWithoutFixed" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
        <th>House Name</th>
        <th>Brand Name</th>
        <th>SKU Name</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
    @foreach($current_stocks as $stock)
    <tr>
        <td>{{$stock->market_name}}</td>
        <td>{{$stock->brand_name}}</td>
        <td>{{$stock->sku_name}}</td>
        <td>{{$stock->quantity}}</td>
    </tr>
    @endforeach
    </tbody>
</table>



