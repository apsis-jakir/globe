<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
        <th>House Name</th>
        @if($type == 'primary')
            <th>ASM/RSM</th>
            <th>ASM/RSM Phone</th>
        @elseif($type == 'secondary')
            <th>ASO/SO</th>
            <th>ASO/SO Phone</th>

            <th>Total Outlet</th>
            <th>Visited Outlet</th>
            <th>Visited Outlet%</th>
            <th>Call Productivity%</th>
            <th>Protfollio Volume</th>
            <th>Value Per Call</th>
        @endif

        <th>Total SKU Quantity</th>
        <th>Order Date</th>
        <th>Deposite Amount</th>
        <th>Current Balance</th>
        <th>Order Amount</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->dh_name}}</td>
            <td>{{$order->requester_name}}</td>
            <td>{{$order->requester_phone}}</td>
            @if($type == 'secondary')
                <td>{{$order->total_outlet}}</td>
                <td>{{$order->visited_outlet}}</td>
                <td>{{(($order->visited_outlet/$order->total_outlet)*100).'%'}}</td>
                <td>{{(($order->total_no_of_memo/$order->visited_outlet)*100).'%'}}</td>
                <td>{{number_format(($order->order_total_sku/$order->total_no_of_memo), 2, '.', '')}}</td>
                <td>{{($order->order_amount/$order->total_no_of_memo)}}</td>
            @endif
            <td>{{$order->order_total_sku}}</td>
            <td>{{date('d-m-Y',strtotime($order->created_at))}}</td>
            <td>{{$order->order_da}}</td>
            <td>{{$order->current_balance}}</td>
            <td>{{$order->order_amount}}</td>
            <td>{{$order->order_status}}</td>
            <td>
                @if(($order->order_status == 'Pending') && ($type == 'primary'))
                    <a href="{{URL::to('primary-order-details/'.$type.'/'.$order->id)}}"><i class="fa fa-eye"></i></a>
                @elseif($type == 'secondary')
                    <a href="{{URL::to('primary-order-details/'.$type.'/'.$order->id)}}"><i class="fa fa-eye"></i></a>
                @else
                    <i class="fa fa-eye"></i>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>



