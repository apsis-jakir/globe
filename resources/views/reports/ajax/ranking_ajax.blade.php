<table  id="dataTableId" class="table-bordered table dataTable">
    <thead>
        <th  style="vertical-align: middle">Serial Number</th>
        <th  style="vertical-align: middle">Employee ID</th>
        <th  style="vertical-align: middle">Name</th>
        <th style="vertical-align: middle">Designation</th>
        <th  style="vertical-align: middle">Work Area</th>
        <th  style="vertical-align: middle">Zone Name</th>
        <th  style="vertical-align: middle">Last Month Ach %</th>
        <th  style="vertical-align: middle">Ach %</th>
    </thead>
    <tbody>
        @if(isset($ranking) && count($ranking) > 0)
              @php($i=1)
              @foreach($ranking as $key=>$value)
                  <tr bgcolor="{{$value['color']}}">
                      <td>{{$i}}</td>
                      <td>{{$value['e_id']}}</td>
                      <td>{{$value['name']}}</td>
                      <td>{{$value['designation']}}</td>
                      <td>{{$value['market_name']}}</td>
                      <td>{{$value['zone_name']}}</td>
                      <td>{{isset($value['pre_ach']) ? $value['pre_ach'] : 0}}</td>
                      <td>{{$value['ach']}}</td>
                  </tr>
                  @php($i++)
              @endforeach
        @endif

    </tbody>
</table>