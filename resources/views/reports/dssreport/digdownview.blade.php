<style>
    table{
        border-spacing: 0;
    }
    th, td { white-space: nowrap; }
    table.dataTable {
        clear: both;
        margin: 0 !important;
        background-color: #fff;
        max-width: none !important;
        border-collapse: separate !important;
    }
    div.DTFC_LeftWrapper table.dataTable, div.DTFC_RightWrapper table.dataTable {
        margin-bottom: 15px !important;
        margin-top: -1px !important;
        z-index: 2;
        background-color: #fff;
    }
    div.DTFC_LeftWrapper table.dataTable.no-footer, div.DTFC_RightWrapper table.dataTable.no-footer {
        border-top: 1px solid #eee !important;
        border-bottom: 1px !important;
    }
</style>
<div class="text-bold text-blue">{{$position}}</div>
<br/>
{{--<div class="table table-responsive">--}}
    <table id="lifting" class="table-bordered table dataTable">
        <thead>
            <tr>
                <th rowspan="3" style="vertical-align: middle">
                    Order Date
                </th>
                <th rowspan="3" style="vertical-align: middle">
                    Particulars
                </th>
                @if(isset($memo_structure))
                    @foreach($memo_structure as $category_key=>$category_value)
                        <th colspan="{{ array_sum(array_map("count", $category_value)) * $level }}"
                            style="text-align: center; vertical-align: middle">{{$category_key}}</th>
                    @endforeach
                @endif
            </tr>
            <tr>
                @if(isset($memo_structure))
                    @foreach($memo_structure as $category_key=>$category_value)
                        @foreach($category_value as $brand_key=>$brand_value)
                            <th colspan="{{count($brand_value) * $level}}" style="text-align: center; vertical-align: middle;">{{$brand_key}}</th>
                        @endforeach
                    @endforeach
                @endif
            </tr>
            <tr>
                @if(isset($memo_structure))
                    @foreach($memo_structure as $category_key=>$category_value)
                        @foreach($category_value as $brand_key=>$brand_value)
                            @foreach($brand_value as $sku_key=>$sku_value)
                                <th colspan="{{$level}}" style="text-align: center;vertical-align: middle;">{{$sku_key}}</th>
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
        <?php
            $date_grid = [];
            $month = explode('-', $month);
            $mnth = date_parse($month[0]);
            $year = $month[1];
            for($d=1; $d<=31; $d++){
                $time = mktime(12, 0, 0, $mnth['month'], $d, $year);
                if (date('m', $time) == $mnth['month']){
                    $date = date('Y-m-d', $time);
                    $date_grid[$date] = [];
                }
            }
            $datas = [];
            $intial_targets = $grid_data['target'];
            $till_achvment = [];
//            debug($date_grid);
//            debug($grid_data);
            foreach ($grid_data['tabledata'] as $key => $init){
                $datas[$init['view_type']]['order'] = $grid_data['tabledata'][$key]['order'];
                $datas[$init['view_type']]['achvmnt'] = $grid_data['tabledata'][$key]['achvmnt'];
                $datas[$init['view_type']]['achvmnt_ratio'] = $grid_data['tabledata'][$key]['achvmnt_ratio'];
            }
//            debug($datas);
            $total_datas = array_merge($date_grid, $datas);
//            dd($total_datas);
            $remaining_days = (float)count($date_grid);
            foreach ($intial_targets as $i => $tt){
                $till_achvment[$i][] = 0;
            }

            foreach ($total_datas as $date => $total_data){
                echo '<tr>';
                echo '<td>'.$date.'</td>';
                echo '<td>RDT</td>';
                foreach ($intial_targets as $t => $targets){
                    $tillacv = array_sum($till_achvment[$t]);
                    $rdt = number_format(((((float)$targets - $tillacv) / $remaining_days)) , 2);
                    echo '<td class="text-right">'. $rdt .'</td>';
                }
                $remaining_days--;
                echo '</tr>';
                /*--------------------*/
                echo '<tr>';
                echo '<td>'.$date.'</td>';
                echo '<td>Order</td>';
                $o=0;
                foreach ($intial_targets as $targets){
                    if(isset($total_data['order'][$o]) && !empty($total_data['order'][$o])){
                        echo '<td class="text-right">'.number_format((float)$total_data['order'][$o],2).'</td>';
                    }else{
                        echo '<td class="text-right">0.00</td>';
                    }
                    $o++;
                }
                echo '</tr>';
                /*--------------------*/
                echo '<tr>';
                echo '<td>'.$date.'</td>';
                echo '<td>Sales</td>';
                $a = 0;
                foreach ($intial_targets as $targets){
                    if(isset($total_data['achvmnt'][$a]) && !empty($total_data['achvmnt'][$a])){
                        echo '<td class="text-right">'.number_format((float)$total_data['achvmnt'][$a],2).'</td>';
                    }else{
                        echo '<td class="text-right">0.00</td>';
                    }
                    $a++;
                }
                echo '</tr>';
                /*--------------------*/
                echo '<tr>';
                echo '<td>'.$date.'</td>';
                echo '<td>Cum Ach %</td>';
                $ac = 0;
                foreach ($intial_targets as $targets){
                    if(isset($total_data['achvmnt_ratio'][$ac]) && !empty($total_data['achvmnt_ratio'][$ac])){
                        echo '<td class="text-right">'.number_format((float)$total_data['achvmnt_ratio'][$ac],2).'</td>';
                    }else{
                        echo '<td class="text-right">0.00</td>';
                    }
                    $ac++;
                }
                echo '</tr>';
                /*--------------------*/
            }
        ?>
        </tbody>
    </table>
{{--</div>--}}
<script>
    $('#lifting').dataTable({
        scrollY: "calc(125vh - 380px)",
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 2
        },
        aaSorting: [],
        rowsGroup: [0],
        order: [[ 0, "desc" ]],
        bPaginate: true,
        dom: 'Bfrtip',
        responsive: true,
        "lengthMenu": [[50, 25, 50, 100, -1], [50, 25, 50, 100, "All"]],
        "pageLength": 50,
        buttons: ['excel', 'print', 'pageLength']
    });
</script>