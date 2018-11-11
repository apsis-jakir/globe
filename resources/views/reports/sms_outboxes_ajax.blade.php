<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example2_info">
    <thead>
    <tr>
        <th>Created Date</th>
        <th>Reciever Number</th>
        <th>SMS Content</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($outboxes as $val)
        <tr>
            <td>{{$val->created}}</td>
            <td>{{$val->sms_reciever_number}}</td>
            <td>{{$val->sms_content}}</td>
            <td>{{$val->status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>



