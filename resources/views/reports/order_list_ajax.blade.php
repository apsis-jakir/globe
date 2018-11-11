<table id="dataTableIdWithoutFixed" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
        <th>House Name</th>
        @if($type == 'primary')
            <th>ASM/RSM</th>
            <th>ASM/RSM Phone</th>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>Deposit Amount</th>
            <th>Current Balance</th>
            <th>Total SKU quantity</th>
            <th>Order Amount</th>
        @elseif($type == 'secondary')
            <th>ASO/SO Name</th>
            <th>ASO/SO Phone</th>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>Total Outlet</th>
            <th>Visited Outlet</th>
            <th>Successful Memo</th>
            <th>Visited Outlet%</th>
            <th>Call Productivity%</th>
            <th>SKU Productivity</th>
            <th>Portfolio Volume</th>
            <th>Value Per Call</th>
            <th>Order Amount</th>
        @endif
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->point_name}}</td>
            <td>{{$order->requester_name}}</td>
            <td>{{$order->requester_phone}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{date('d-m-Y',strtotime($order->order_date))}}</td>
            @if($type == 'secondary')
                <td>{{$order->total_outlet}}</td>
                <td>{{$order->visited_outlet}}</td>
                <td>{{$order->total_no_of_memo}}</td>
                <td>{{number_format((($order->visited_outlet/$order->total_outlet)*100),2).'%'}}</td>
                <td>{{number_format((($order->total_no_of_memo/$order->visited_outlet)*100),2).'%'}}</td>
                <td>{{number_format(($order->order_total_sku/$order->total_no_of_memo),2)}}</td>
                <td>{{number_format(($order->order_total_case/$order->total_no_of_memo), 2, '.', '')}}</td>
                <td>{{number_format(($order->order_amount/$order->total_no_of_memo),2)}}</td>
            @else
                <td align="right">{{number_format($order->order_da,2)}}</td>
                <td align="right">{{($order->order_status == 'Pending')?number_format($order->dhcb,2):number_format($order->house_current_balance,2)}}</td>
                <td>{{$order->order_total_sku}}</td>
            @endif
            <td align="right">{{number_format($order->order_amount,2)}}</td>
            <td>{{$order->order_status}}</td>
            <td>
                <a href="{{URL::to('primary-order-details/'.$type.'/'.$order->id)}}"><i class="fa fa-eye"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>



