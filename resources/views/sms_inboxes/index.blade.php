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

        @if(Session::has('error_message'))
            <div class="alert alert-danger">
                <span class="glyphicon glyphicon-ok"></span>
                {!! session('error_message') !!}

                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        @endif

        <section class="content-header">
            <h1>
                SMS Inbox
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">SMS Inbox</li>
            </ol>
        </section>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="btn-group btn-group-sm pull-right" role="group">
                        <a href="{{ route('sms_inboxes.sms_inbox.create') }}" class="btn btn-success"
                           title="Create">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                    </div>
                    @if(count($smsInboxes) == 0)
                        <div class="text-center">
                            <h4>No SMS Available!</h4>
                        </div>
                    @else
                        <div class="box-body">
                            <table id="dataTableId" class="table table-bordered table-striped dataTable no-footer">
                                <thead>
                                <tr>
                                    <th><input id="senderPhone" class="form-control" type="text"
                                               placeholder="Search Sender Phone"></th>
                                    <th><input id="smsContent" class="form-control" type="text"
                                               placeholder="Search SMS By Text"></th>
                                    <th id="smsStatus"></th>
                                    <th><input id="datePickerChange" class="form-control" type="date"></th>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sender</th>
                                    <th>SMS&nbsp;Content</th>
                                    <th>SMS&nbsp;Status</th>
                                    <th>SMS&nbsp;Received&nbsp;At</th>
                                    <th>Last&nbsp;Rejected&nbsp;Reason</th>
                                    <th style="width: 100px; text-align: right">Action</th>
                                    {{--<th>Process</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($smsInboxes as $smsInbox)
                                    <tr>
                                        <td>{{ $smsInbox->sender }}</td>
                                        <td>{{ $smsInbox->sms_content }}</td>
                                        <td>{{ $smsInbox->sms_status }}</td>
                                        <td>{{ $smsInbox->sms_received }}</td>
                                        <td>{{ $smsInbox->reason }}</td>
                                        <td>
                                            <form method="POST"
                                                  action="{!! route('sms_inboxes.sms_inbox.destroy', $smsInbox->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                <div class="btn-group btn-group-xs pull-right" role="group">
                                                    <a href="{{ route('sms_inboxes.sms_inbox.show', $smsInbox->id ) }}"
                                                       class="btn btn-info" title="View">
                                                        <span class="glyphicon glyphicon-open"
                                                              aria-hidden="true"></span>
                                                    </a>

                                                    <?php if(in_array($smsInbox->sms_status, ['Active', 'Rejected'])):?>
                                                        <a href="{{ route('sms_inboxes.sms_inbox.edit', $smsInbox->id ) }}"
                                                           class="btn btn-primary" title="Edit">
                                                            <span class="glyphicon glyphicon-pencil"
                                                                  aria-hidden="true"></span>
                                                        </a>

                                                        <button type="submit" class="btn btn-danger"
                                                                title="Delete"
                                                                onclick="return confirm(&quot;Delete Sms Inbox?&quot;)">
                                                            <span class="glyphicon glyphicon-trash"
                                                                  aria-hidden="true"></span>
                                                        </button>


                                                        <a href="{{ route('sms_inboxes.sms_inbox.process', $smsInbox->id ) }}"
                                                           class="btn btn-primary" title="Process">
                                                            <span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
                                                        </a>
                                                    <?php endif;?>
                                                </div>

                                            </form>

                                        </td>
                                        <!--<td>
                                            <?php if(in_array($smsInbox->sms_status, ['Active', 'Rejected'])):?>
                                            <a href="{{ route('sms_inboxes.sms_inbox.process', $smsInbox->id ) }}"
                                               class="btn btn-primary" title="Process Sms Inbox">
                                                <span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
                                            </a>
                                            <?php endif;?>
                                        </td>-->
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div class="panel-footer">
                            {!! $smsInboxes->render() !!}
                        </div>
                </div>


                @endif

            </div>
        </div>
        <script>
            $(document).ready(function () {
                var table = $('#dataTableId').DataTable({
                    'paging': false,
                    "lengthChange": false,
                    "order": [[ 3, "desc" ]],
                    initComplete: function () {
                        this.api().columns(2).every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option value="">All</option></select>')
                                .appendTo($("#smsStatus").empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });
                    }
                });
                $('#senderPhone').on('keyup change', function () {
                    table.column(0).search(this.value).draw();
                });
                $('#datePickerChange').on('keyup change', function () {
                    table.column(3).search(this.value).draw();
                });
                $('#smsContent').on('keyup change', function () {
                    table.column(1).search(this.value).draw();
                });

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