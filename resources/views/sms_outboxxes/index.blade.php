@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif
	
	<section class="content-header">
      <h1>
        SMS Outbox
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">SMS Outbox</a></li>
      </ol>
    </section>
	
	
	
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				 {{--<div class="btn-group btn-group-sm pull-right" role="group">--}}
					{{--<a href="{{ route('sms_outboxxes.sms_outboxx.create') }}" class="btn btn-success" title="Create New Sms Outboxx">--}}
						{{--<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>--}}
					{{--</a>--}}
				{{--</div>--}}
				@if(count($smsOutboxxes) == 0)
					<div class="text-center">
						<h4>No SMS Available!</h4>
					</div>
				@else
				<div class="box-body">
                <table id="dataTableId" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><input class="form-control" id="sms_number" type="text"></th>
                            <th><input class="form-control" id="sms_content" type="text"></th>
                            <th><input class="form-control" id="created" type="date"></th>
                            <th><input class="form-control"  id="sentAt" type="date"></th>
                            <th id="notificationType"></th>
                        </tr>
                        <tr>
                            <th>SMS Receiver Number</th>
                            <th>SMS Content</th>
                            <th>Created</th>
                            <th>Sent Time</th>
                            <th>Notification Type</th>

                        <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($smsOutboxxes as $smsOutboxx)
                        <tr>
                            <td>{{ $smsOutboxx->sms_receiver_number }}</td>
                            <td>{{ $smsOutboxx->sms_content }}</td>
                            <td>{{ $smsOutboxx->created }}</td>
                            <td>{{ $smsOutboxx->sent_datetime }}</td>
                            <td>{{ $smsOutboxx->order_type }}</td>

                            <td>

                                <form method="POST" action="{!! route('sms_outboxxes.sms_outboxx.destroy', $smsOutboxx->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('sms_outboxxes.sms_outboxx.show', $smsOutboxx->id ) }}" class="btn btn-info" title="View">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('sms_outboxxes.sms_outboxx.edit', $smsOutboxx->id ) }}" class="btn btn-primary" title="Edit">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm(&quot;Are you sure you want to delete?&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        
        @endif
    
    </div>
    </div>
        <script>
            $(document).ready(function(){
                var table = $('#dataTableId').DataTable({
                    "lengthChange": false,
                    initComplete: function () {
                        this.api().columns(4).every( function () {
                            var column = this;
                            var select = $('<select class="form-control"><option value="">All</option></select>')
                                .appendTo( $("#notificationType").empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );

                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        } );
                    }
                });

                $('#sms_number').on('keyup change', function(){
                    table.column(0).search(this.value).draw();
                });

                $('#sms_content').on('keyup change', function(){
                    table.column(1).search(this.value).draw();
                });
                $('#created').on('keyup change',function(){
                    table.column(2).search(this.value).draw();
                });
                $('#sentAt').on('keyup change',function(){
                    table.column(3).search(this.value).draw();
                });
//                $('#smsContent').on('keyup change', function(){
//                    table.column(1).search(this.value).draw();
//                });
//
                $(".dataTables_info").hide();



            });
        </script>
        <style>
            .dataTables_wrapper .dataTables_filter {
                float: right;
                text-align: right;
                visibility: hidden;
            }
        </style>
@endsection