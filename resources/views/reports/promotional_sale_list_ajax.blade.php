<table id="dataTableIdWithoutFixed" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
            <th>House Name</th>
            <th>ASO/SO Name</th>
            <th>ASO/SO Phone</th>
            <th>Order Date</th>
            <th>Total Outlet</th>
            <th>Visited Outlet</th>
            <th>Successful Memo</th>
            <th>Total Promotional Package</th>
            <th>Status</th>
           <th>Action</th>
    </tr>
    </thead>
    <tbody>
       @foreach($list as $sale_key=>$sale_value)
           <tr>
               <td>{{$sale_value->point_name}}</td>
               <td>{{$sale_value->requester_name}}</td>
               <td>{{$sale_value->requester_phone}}</td>
                <td>{{date('d-m-Y',strtotime($sale_value->order_date))}}</td>
               <td>{{$sale_value->total_outlet}}</td>
               <td>{{$sale_value->visited_outlet}}</td>
               <td>{{$sale_value->successful_memo}}</td>
               <td>{{$sale_value->sale_total_sku}}</td>
               <td>{{$sale_value->sale_status}}</td>
               <td>
                   <a href="{{URL::to('promotional-sale-details/'.$sale_value->sale_id)}}"><i class="fa fa-eye"></i></a>
               </td>
           </tr>
       @endforeach
    </tbody>
</table>





