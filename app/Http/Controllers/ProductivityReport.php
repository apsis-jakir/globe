<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use reportsHelper;
use App\Models\Menu;
use App\Models\User;

//for excel library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helper\ExportHelper;

class ProductivityReport extends Controller
{
    private $routes;
    public function __construct()
    {
        $this->routes= json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
//        DB::enableQueryLog();
    }

    public function productivityList(){
        $data['ajaxUrl'] = URL::to('productivityListAjax/');
        $data['view'] = 'productivity.view';
        $data['header_level'] = 'Productivity List';
        //search Option
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory','house','aso', 'category', 'brand', 'sku', 'daterange', 'view-report')); //View Structure
        $data['level'] = 1;
//        $data['level_col_data'] = ['Requested', 'Delivery'];
        $data['memo_structure'] = repoStructure();
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Productivity List'));
        return view('reports.report', $data);
    }

    public function digdownProductivityListAjax(Request $request){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);

        $start_date = $request_data['startdate'];
        $end_date = $request_data['enddate'];
        $loctype = $request_data['loctype'];
        $locid = $request_data['locid'];
        $data['level'] = 1;
        $data['level_col_data'] = ['Requested', 'Delivery'];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $custom_search['zone'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['region'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territory'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['aso'] = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['position'] = $this->getStringLocation($custom_search['zone'], $custom_search['region'], $custom_search['territory'], $custom_search['house']);
        $data['grid_data'] = $this->getproductivityData(
            'date',
            [$start_date, $end_date],
            $data['memo_structure'],
            NULL,
            ['loctype'=>$loctype, 'locid'=>$locid]
        );
        $data['view_column'] = 'date';
        return view('reports.productivity.digdowndata', $data);
    }

    public function productivityListAjax(Request $request, $data = []){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);

        $view_report = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $date_range = key_exists('created_at', $request_data) ? explode(' - ', $request_data['created_at'][0]) : [];

        $data['level'] = 1;
        $data['level_col_data'] = ['Requested', 'Delivery'];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $custom_search['zone'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['region'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territory'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['aso'] = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];

        $data['position'] = $this->getStringLocation($custom_search['zone'], $custom_search['region'], $custom_search['territory'], $custom_search['house']);
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
//        debug($data['memo_structure']);
        $data['grid_data'] = $this->getproductivityData($view_report[0], $date_range, $data['memo_structure'], $custom_search);
        $search_type = $post['search_type'][0];
        $data['view_column'] = $view_report[0];
        $data['view_report'] = ucfirst($view_report[0]);
        if($search_type == 'show')
        {
            return view('reports.productivity.view', $data);
        }
        else if($search_type == 'download')
        {
            $filename='productivity-list-'.Auth::user()->id.'.xlsx';
            $this->export_productivity_list($data,$filename);
            echo $filename;
        }
        //return view('reports.productivity.view', $data);

    }

    public function getproductivityData($view_report = '', $date_range = [], $memo_structure = [], $custom_search = [], $digdowndata=[]){
//        debug($view_report);
        if($view_report == 'zone'){
            $orders_table = DB::table('zones');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.zones_id', '=', 'zones.id');
           // $orders_table->leftJoin('sales_geo_records', 'zones.id', '=', 'sales_geo_records.zone_id');
//            $orders_table->leftJoin('orders', function($join) use ($date_range){
//                $join->on('sales_geo_records.order_id', '=', 'orders.id')
//                    ->where('sales_geo_records.sale_id', 0)
//                    ->whereIN('orders.order_status', ['Edited','Processed'])
//                    ->where('orders.order_type', 'Secondary')
//                    ->whereBetween('orders.order_date', $date_range);
//            });

            $orders_table->leftJoin('orders', function($join) use ($date_range){
                $join->on('orders.dbid', '=', 'distribution_houses.id')
                    ->whereIN('orders.order_status', ['Edited','Processed'])
                    ->where('orders.order_type', 'Secondary')
                    ->whereBetween('orders.order_date', $date_range);
            });

            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('zones.id', $custom_search['zone']);
            }
            $groupby_field = 'zones.id';
            $db_field_id = 'zones.id as naming_field_id';
            $db_field = 'zones.zone_name';
            $naming_field_id = 'id';
            $naming_field = 'zone_name';
        }
        else if($view_report == 'region'){
            $orders_table = DB::table('regions');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.regions_id', '=', 'regions.id');
            //$orders_table->leftJoin('sales_geo_records', 'regions.id', '=', 'sales_geo_records.region_id');
            $orders_table->leftJoin('orders', function($join) use ($date_range){
                $join->on('orders.dbid', '=', 'distribution_houses.id')
                    ->whereIN('orders.order_status', ['Edited','Processed'])
                    ->where('orders.order_type', 'Secondary')
                    ->whereBetween('orders.order_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('regions.id', $custom_search['region']);
            }
            $groupby_field = 'regions.id';
            $db_field_id = 'regions.id as naming_field_id';
            $db_field = 'regions.region_name';
            $naming_field_id = 'id';
            $naming_field = 'region_name';
        }
        else if($view_report == 'territory'){
            $orders_table = DB::table('territories');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.territories_id', '=', 'territories.id');
            //$orders_table->leftJoin('sales_geo_records', 'territories.id', '=', 'sales_geo_records.territory_id');
//            $orders_table->leftJoin('orders', function($join) use ($date_range){
//                $join->on('sales_geo_records.order_id', '=', 'orders.id')
//                    ->where('sales_geo_records.sale_id', 0)
//                    ->whereIN('orders.order_status', ['Edited','Processed'])
//                    ->where('orders.order_type', 'Secondary')
//                    ->whereBetween('orders.order_date', $date_range);
//            });
            $orders_table->leftJoin('orders', function($join) use ($date_range){
                $join->on('orders.dbid', '=', 'distribution_houses.id')
                    ->whereIN('orders.order_status', ['Edited','Processed'])
                    ->where('orders.order_type', 'Secondary')
                    ->whereBetween('orders.order_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('territories.id', $custom_search['territory']);
            }
            $groupby_field = 'territories.id';
            $db_field_id = 'territories.id as naming_field_id';
            $db_field = 'territories.territory_name';
            $naming_field = 'territory_name';
        }
        else if($view_report == 'aso'){
            $orders_table = DB::table('users');
            $orders_table->leftJoin('orders', function($join) use ($date_range){
                $join->on('orders.aso_id', '=', 'users.id')
                    ->whereIN('orders.order_status', ['Edited','Processed'])
                    ->where('orders.order_type', 'Secondary')
                    ->whereBetween('orders.order_date', $date_range);
            });

            $orders_table->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'users.distribution_house_id');

            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('users.id', $custom_search['aso']);
            }
            $groupby_field = 'users.id';
            $db_field_id = 'users.id as naming_field_id';
            $db_field = 'users.name';
            $naming_field = 'name';
        }
        else if($view_report == 'date'){
            $orders_table = DB::table('orders');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid');
            if(!empty($digdowndata)){
                $orders_table->join('sales_geo_records', function($join) use ($date_range, $digdowndata){
                    $join->on('sales_geo_records.order_id', '=', 'orders.id')
                        ->whereIN('orders.order_status', ['Edited','Processed'])
                        ->where('sales_geo_records.sale_id', 0)
                        ->where('orders.order_type', 'Secondary')
                        ->whereBetween('orders.order_date', $date_range);
                });
                if($digdowndata['loctype'] == 'zone'){
                    $orders_table->where('sales_geo_records.zone_id', $digdowndata['locid']);
                }
                if($digdowndata['loctype'] == 'region'){
                    $orders_table->where('sales_geo_records.region_id', $digdowndata['locid']);
                }
                if($digdowndata['loctype'] == 'territory'){
                    $orders_table->where('sales_geo_records.territory_id', $digdowndata['locid']);
                }
                if($digdowndata['loctype'] == 'house'){
                    $orders_table->where('sales_geo_records.dbid', $digdowndata['locid']);
                }
            }
            if(!empty($custom_search)){
                $orders_table->join('sales_geo_records', function($join) use ($date_range, $digdowndata){
                    $join->on('sales_geo_records.order_id', '=', 'orders.id')->where('sales_geo_records.sale_id', 0);
                });
                if(!empty($custom_search['zone'])){
                    $orders_table->whereIn('sales_geo_records.zone_id', $custom_search['zone']);
                }
                if(!empty($custom_search['region'])){
                    $orders_table->whereIn('sales_geo_records.region_id', $custom_search['region']);
                }
                if(!empty($custom_search['territory'])){
                    $orders_table->whereIn('sales_geo_records.territory_id', $custom_search['territory']);
                }
                if(!empty($custom_search['house'])){
                    $orders_table->whereIn('sales_geo_records.dbid', $custom_search['house']);
                }
            }

            $orders_table->where('orders.order_type', 'Secondary');
            $orders_table->whereIN('orders.order_status', ['Edited','Processed']);
            $orders_table->whereBetween('orders.order_date', $date_range);

            $groupby_field = 'orders.order_date';
            $db_field_id = 'orders.id as naming_field_id';
            $db_field = 'orders.order_date';
            $naming_field = 'order_date';
        }
        else{
            // for houses
            $orders_table = DB::table('distribution_houses');
            //$orders_table->leftJoin('sales_geo_records', 'distribution_houses.id', '=', 'sales_geo_records.dbid');
            $orders_table->leftJoin('orders', function($join) use ($date_range){
                $join->on('distribution_houses.id', '=', 'orders.dbid')
                    ->where('orders.order_type', 'Secondary')
                    ->whereIN('orders.order_status', ['Edited','Processed'])
                    ->whereBetween('orders.order_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('distribution_houses.id', $custom_search['house']);
            }
            $groupby_field = 'distribution_houses.id';
            $db_field_id = 'distribution_houses.id as naming_field_id';
            $db_field = 'distribution_houses.point_name';
            $naming_field = 'point_name';
        }
        $orders_table->select(
            'distribution_houses.zone_name as zone',
            'distribution_houses.region_name as region',
            'distribution_houses.territory_name as territory',
            'distribution_houses.point_name as house',
            $db_field_id,
            $db_field,
            DB::raw('GROUP_CONCAT(orders.id) as all_order_id'),
            DB::raw('SUM(orders.total_outlet) as stotal_outlet'),
            DB::raw('SUM(orders.visited_outlet) as svisited_outlet'),
            'orders.order_type',
            DB::raw('SUM(orders.total_no_of_memo) as stotal_no_of_memo'),
            DB::raw('SUM(orders.order_total_sku) as sorder_total_sku'),
            DB::raw('SUM(orders.order_amount) as sorder_total_amount'),
            DB::raw('SUM(orders.order_total_case) as sorder_total_case'));
        $orders_table->groupBy($groupby_field);
        $order_data = $orders_table->get();
        //debug($order_data,1);
        $grid_data = [];
        $sku_list = $this->getSKUList($memo_structure);
        foreach($order_data as $key => $orders){
            if($view_report != 'date'){
                $grid_data[$key]['view_type'] = !empty($orders->$naming_field) ?
                    '<a href="javascript:void(0);" class="digdown" 
                            data-loctype="'.$view_report.'" 
                            data-locid="'.$orders->naming_field_id .'" 
                            data-startdate="'.$date_range[0].'" 
                            data-enddate="'.$date_range[1].'">'.$orders->$naming_field.'</a>' : 0;
            }else{
                $grid_data[$key]['view_type'] = !empty($orders->$naming_field) ? $orders->$naming_field : 0;
            }

            $grid_data[$key]['zone'] = $orders->zone;
            $grid_data[$key]['region'] = $orders->region;
            $grid_data[$key]['territory'] = $orders->territory;
            $grid_data[$key]['house'] = $orders->house;
            $grid_data[$key]['total_outlet'] = !empty($orders->stotal_outlet) ? $orders->stotal_outlet : 0;
            $grid_data[$key]['total_order_amount'] = !empty($orders->sorder_total_amount) ? $orders->sorder_total_amount : 0;
            $grid_data[$key]['visited_outlet'] = !empty($orders->svisited_outlet) ? $orders->svisited_outlet : 0;
            $grid_data[$key]['successfull_memo'] = !empty($orders->stotal_no_of_memo) ? $orders->stotal_no_of_memo : 0;
            $grid_data[$key]['visited_ratio'] = $grid_data[$key]['visited_outlet'] != 0 ? number_format((($grid_data[$key]['visited_outlet']/$grid_data[$key]['total_outlet'])*100),2) : 0;
            $grid_data[$key]['call_productivity'] = $grid_data[$key]['visited_outlet'] != 0 ? number_format((($grid_data[$key]['successfull_memo']/$grid_data[$key]['visited_outlet'])*100),2) : 0;


            $all_order_id = explode(',', $orders->all_order_id);
            $orders_details_data = [];
//            DB::enableQueryLog();
            if($orders->all_order_id){
                $orders_details_table = DB::table('order_details');
                $orders_details_table->select(
                    'order_details.short_name',
                    DB::raw('SUM(order_details.quantity) AS squantity'),
                    DB::raw('SUM(order_details.no_of_memo) AS sno_of_memo'));
                $orders_details_table->whereIn('order_details.orders_id', $all_order_id);
                $orders_details_table->groupBy('order_details.short_name');
                $orders_details_data = $orders_details_table->get();
            }
//            debug(DB::getQueryLog());
            $sku_info = [];
            foreach ($orders_details_data as $orders_details){
                $sku_info[$orders_details->short_name]['quantity'] = $orders_details->squantity;
                $sku_info[$orders_details->short_name]['memo_num'] = $orders_details->sno_of_memo;
            }
            $total_sku_quantity = [];
            $pack_quantity = 0;
            $total_sku_memo = [];
            $value_per_call = 0;
            $price_amount = [];
            foreach($sku_list as $short_name => $sku_grid){
                if(isset($sku_info[$short_name])){
                    $grid_data[$key]['sku_grid'][$short_name]['quantity'] = $sku_info[$short_name]['quantity'];
                    $pack_quantity = $grid_data[$key]['sku_grid'][$short_name]['quantity'] / $sku_grid['pack_size'];
                    $total_sku_quantity[] =+ $pack_quantity;

                    $grid_data[$key]['sku_grid'][$short_name]['memo_num'] = $sku_info[$short_name]['memo_num'];
                    $total_sku_memo[] =+ $grid_data[$key]['sku_grid'][$short_name]['memo_num'];



                    $grid_data[$key]['sku_grid'][$short_name]['bcp'] = $grid_data[$key]['successfull_memo'] != 0 ? number_format(($grid_data[$key]['sku_grid'][$short_name]['memo_num'] / $grid_data[$key]['successfull_memo']) * 100,2) : 0;

                    $price_amount[] =+ $sku_grid['price'] * $pack_quantity;
                }else{
                    $grid_data[$key]['sku_grid'][$short_name]['quantity'] = 0;
                    $grid_data[$key]['sku_grid'][$short_name]['memo_num'] = 0;
                    $grid_data[$key]['sku_grid'][$short_name]['bcp'] = 0;
                }
            }
            $grid_data[$key]['sku_productivity'] = $grid_data[$key]['successfull_memo'] != 0 ? number_format((array_sum($total_sku_memo) / $grid_data[$key]['successfull_memo']), 2) : 0;
            $grid_data[$key]['portfolio_volume'] = $grid_data[$key]['successfull_memo'] != 0 ? number_format((array_sum($total_sku_quantity) / $grid_data[$key]['successfull_memo']),2) : 0;
//            $grid_data[$key]['value_per_call'] = $total_sku_memo != 0 ? number_format(($total_sku_quantity/$total_sku_memo),2) : 0;
            $grid_data[$key]['value_per_call'] = array_sum($total_sku_memo) != 0 ? number_format($grid_data[$key]['total_order_amount'] / $grid_data[$key]['successfull_memo'],2) : 0;
            $grid_data[$key]['price_amount'] = number_format(array_sum($price_amount),2);
        }
        return $grid_data;
    }

    public function getSKUList($memo_structure = []){
        $sku_list = [];
        foreach ($memo_structure as $cat_key => $cat_val) {
            $selected_skues = array_flatten($cat_val);
            foreach ($selected_skues as $sku){
                $sku_list[] = $sku;
            }
        }
        $skus = DB::table('skues')
            ->select('id', 'short_name', 'house_price', 'price', 'pack_size')
            ->whereIn('short_name', $sku_list)
            ->get()->toArray();
        $skuinfo = [];
        foreach ($skus as $sku){
            $skuinfo[$sku->short_name]['id'] = $sku->id;
            $skuinfo[$sku->short_name]['house_price'] = $sku->house_price;
            $skuinfo[$sku->short_name]['price'] = $sku->price;
            $skuinfo[$sku->short_name]['pack_size'] = $sku->pack_size;
        }
        $sku_details = [];
        foreach($sku_list as $skues){
            $sku_details[$skues] = $skuinfo[$skues];
        }
        return $sku_details;
    }

    private function getStringLocation($zone,$region,$territory,$house){
//debug($territory,1);
        $position="";
        if(count($zone) < 3 && count($zone) > 0){
            foreach ($zone as $value){
                $position.=getNameZone($value)->zone_name.',';
            }

        }
        else{
            $position.="More than 3 Zone,";
        }
        $position=substr($position,0,-1);
        $position.=" -> ";

        if(count($region) <3 && count($region) > 0){
            foreach ($region as $value){
                $position.=getNameRegion($value)->region_name.',';
            }
        }
        else{
            $position.="More than 3 Region,";
        }
        $position=substr($position,0,-1);
        $position.=" -> ";
        if(count($territory) <3 && count($territory) > 0){
            foreach ($territory as $value){
                $position.=getNameTerritory($value)->territory_name.',';
            }
        }
        else{
            $position.="More than 3 Territory,";
        }
        $position=substr($position,0,-1);
        $position.=" ->";

        if(count($house) < 3 && count($house) > 0){
            foreach ($house as $value){
                $position.=getNameHouse($value)->point_name.',';
            }
        }
        else{
            $position.="More than 3 House,";
        }

        return substr($position,0,-1);
    }


    public function export_productivity_list($data,$filename)
    {
        //debug($data['grid_data'],1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;
        $additionalColumnRow = array(
            'addiColumn'=>array('Total Outlet','Visited Outlet','Successfull Memo','Visited Outlet%')
        );
        ExportHelper::get_header_design($number,$row,'Productivity List',$sheet);
        ExportHelper::get_column_title($number,$row,$data,2,$sheet,$additionalColumnRow);

        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Call Productivity');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $number++;
        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'SKU Productivity');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $number++;
        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Portfolio Volume');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $number++;
        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Value/Call');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $number++;
        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Amount');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row++;

        foreach($data['grid_data'] as $grids)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, strip_tags($grids['view_type']));

            foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids[$pctv]);
            }
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['total_outlet']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['visited_outlet']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['successfull_memo']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['visited_ratio']);

            foreach($grids['sku_grid'] as $skugrids)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $skugrids['bcp']);
            }
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['call_productivity']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['sku_productivity']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['portfolio_volume']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['value_per_call']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['price_amount']);
            $row++;
        }

        //$sheet->freezePane( "E5" );

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
