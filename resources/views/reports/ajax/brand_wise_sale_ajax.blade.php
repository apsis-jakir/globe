<?php /* ?>
<table id="dataTableId" class="table table-bordered table-striped dataTable no-footer">
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle;">Brand Name</th>
            <?php $k =11; ?>
            @foreach($tweelveMonth as $key=>$val)
                <th colspan="2" style="text-align: center">{{$tweelveMonth[$k]}}</th>
                <?php $k--; ?>
            @endforeach
            <th colspan="2" style="text-align: center">AVG.</th>
        </tr>

        <tr>
            @foreach($tweelveMonth as $key=>$val)
                <th>Amount</th>
                <th>Quantity</th>
                <?php $k--; ?>
            @endforeach
                <th>Amount</th>
                <th>Quantity</th>
        </tr>

    </thead>
    <tbody>
        @if(isset($brand_wise_sale) && count($brand_wise_sale) > 0)

            @foreach($brand_wise_sale as $brand_key=> $brand_val)
                <tr>
                    <th>
                        {{$brand_key}}
                    </th>
                    <?php
                        $totalAmount = 0;
                        $totalQuantity = 0;
                    ?>
                    @for($i=0; $i < 12; $i++)
                        <?php
                            $totalAmount = $totalAmount+$brand_val[$i]['amount'];
                            $totalQuantity = $totalQuantity+$brand_val[$i]['quantity'];
                        ?>
                        <td>{{$brand_val[$i]['amount']}}</td>
                        <td>{{$brand_val[$i]['quantity']}}</td>
                    @endfor
                    <td>{{round((($totalAmount)?$totalAmount/12:0),2)}}</td>
                    <td>{{round((($totalQuantity)?$totalQuantity/12:0),2)}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

 <?php */ ?>
@if(!isset($errorMsg))
    <table id="dataTableIdGroup" class="table table-bordered table-striped dataTable no-footer">
        <thead>
        <tr>
            <th style="vertical-align: middle;">Brand Name</th>
            <th style="vertical-align: middle;">Type</th>
            <?php $k =11; ?>
            @foreach($tweelveMonth as $key=>$val)
                <th style="text-align: center">{{$tweelveMonth[$k]}}</th>
                <?php $k--; ?>
            @endforeach
            <th style="text-align: center">Total</th>
            <th style="text-align: center">AVG.</th>
        </tr>


        </thead>
        <tbody>
        @if(isset($brand_wise_sale) && count($brand_wise_sale) > 0)

            @foreach($brand_wise_sale as $brand_key=> $brand_val)
                <?php //dd($brand_val['brand_name']); ?>
            <tr>
                <td style="vertical-align: middle">
                    {{$brand_key}}
                </td>
                <td>Amount</td>
                <?php $totalAmount = 0; ?>
                @for($i=0; $i < 12; $i++)
                    <?php

                    if($brand_val['sdate'] && ($brand_val['sdate'] == ($i+1)))
                    {
                    $totalAmount = ($totalAmount+$brand_val['ta']);
                    ?>
                    <td>{{(($brand_val['sdate'] == ($i+1))?number_format($brand_val['ta'],2):0)}}</td>
                    <?php
                    }
                    else
                    {
                    echo "<td>0</td>";
                    }
                    ?>

                @endfor
                <td>{{$totalAmount}}</td>
                <td>{{round((($totalAmount)?$totalAmount/12:0),2)}}</td>
                </tr>
                <tr>
                    <td>
                        {{$brand_key}}
                    </td>
                    <td style="background:#fff;">Quantity</td>
                    <?php $totalQuantity = 0; ?>
                    @for($i=0; $i < 12; $i++)
                        <?php
                        if($brand_val['sdate'] && ($brand_val['sdate'] == ($i+1)))
                        {
                            $totalQuantity = ($totalQuantity+$brand_val['tq']);
                        ?>
                            <td>{{(($brand_val['sdate'] == ($i+1))?$brand_val['tq']:0)}}</td>
                        <?php
                        }
                        else
                        {
                            echo "<td>0</td>";
                        }
                        ?>

                    @endfor

                    <td>{{$totalQuantity}}</td>
                    <td>{{round((($totalQuantity)?$totalQuantity/12:0),2)}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{--<script>--}}
        {{--$('.top_search').click();--}}
    {{--</script>--}}
@else
    <h3 class="text-center" style="color: red;">{{$errorMsg}}</h3>
@endif


<script>
    $('#dataTableIdGroup').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2
        },
        rowsGroup: [0],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['excel','print', 'pageLength']
    });
</script>
