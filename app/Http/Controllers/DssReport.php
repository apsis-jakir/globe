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

class DssReport extends Controller {
    private $routes;
    public function __construct(){
        $this->routes = json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
    }

    public function monthlyAchvList(){
        $data['metaTitle'] = 'Globe | DSS Report';
        $data['ajaxUrl'] = URL::to('monthlyAchvAjax/');
        $data['view'] = 'dssreport.view';
        $data['header_level'] = 'Monthly Achievement & DSS';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory',
            'house', 'route', 'category', 'aso', 'brand', 'sku', 'month', 'view-report')); //View Structure
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $data['memo_structure'] = repoStructure();
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Monthly Achievement & DSS'));
        return view('reports.report', $data);
    }

    public function monthlyAchvAjax(Request $request, $data = []){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);
        $view_report = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $custom_search['zones'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['regions'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territories'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['route'] = array_key_exists('id', $request_data) ? $request_data['route_id'] : [];
        $custom_search['aso'] = array_key_exists('id', $request_data) ? $request_data['aso_id'] : [];
        $custom_search['month'] = array_key_exists('id', $request_data) ? $request_data['month'] : [];

        $data['position'] = $this->getStringLocation($custom_search['zones'], $custom_search['regions'], $custom_search['territories'], $custom_search['house']);
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
//        $data['grid_data'] = $this->getmonthlyAchvData($view_report[0], $data['memo_structure'], $custom_search);
        $data['grid_data'] = $this->getmonthlyAchvData($view_report[0], $data['memo_structure'], $custom_search);
        $data['view_column'] = $view_report[0];
        $data['view_report'] = ucfirst($view_report[0]);
        $search_type = $post['search_type'][0];
        if($search_type == 'show')
        {
            return view('reports.dssreport.view', $data);
        }
        else if($search_type == 'download')
        {
            $filename='monthly-achievement-dss-'.Auth::user()->id.'.xlsx';
            $this->export_monthly_achievement_dss($data,$filename);
            echo $filename;
        }
    }

    private function locationWiseTable($view_report){
        if($view_report == 'zone'){
            $data['mode'] = 'zones';
            $data['search'] = 'zones';
            $data['table'] = ['name' => 'zones', 'id_field' => 'zones.id', 'name_field' => 'zones.zone_name', 'geo_id' => 'sales_geo_records.zone_id'];
            $data['conditionalView'] = 'zones';
        }
        else if($view_report == 'region'){
            $data['mode'] = 'regions';
            $data['search'] = 'regions';
            $data['table'] = ['name' => 'regions', 'id_field' => 'regions.id', 'name_field' => 'regions.region_name', 'geo_id' => 'sales_geo_records.region_id'];
            $data['conditionalView'] = 'regions';
        }
        else if($view_report == 'territory'){
            $data['mode'] = 'territories';
            $data['search'] = 'territories';
            $data['table'] = ['name' => 'territories', 'id_field' => 'territories.id', 'name_field' => 'territories.territory_name', 'geo_id' => 'sales_geo_records.territory_id'];
            $data['conditionalView'] = 'territories';
        }
        else if($view_report == 'route'){
            $data['mode'] = 'route';
            $data['search'] = 'route';
            $data['table'] = ['name' => 'routes', 'id_field' => 'routes.id', 'name_field' => 'routes.routes_name', 'geo_id' => 'sales_geo_records.route_id'];
            $data['conditionalView'] = 'routes';
        }
        else if($view_report == 'aso'){
            $data['mode'] = 'market';
            $data['search'] = 'aso';
            $data['table'] = ['name' => 'users', 'id_field' => 'users.id', 'name_field' => 'users.name', 'geo_id' => 'sales_geo_records.aso_id'];
            $data['conditionalView'] = 'users';
        }
        else {
            $data['mode'] = 'house';
            $data['search'] = 'house';
            $data['table'] = ['name' => 'distribution_houses', 'id_field' => 'distribution_houses.id', 'name_field' => 'distribution_houses.point_name', 'geo_id' => 'sales_geo_records.dbid'];
            $data['conditionalView'] = 'house';
        }
        return $data;
    }

    private function locationOperation($custom_search, $view_report, $report_type = ''){
        $table = [];
        $mode = '';
        $search = '';
        $conditionalView = '';
        $locationWiseTable = self::locationWiseTable($view_report);
        extract($locationWiseTable);
        $orders_table = DB::table($table['name']);

        if($view_report != 'house')
        {
            if($view_report == 'route')
            {
                $orders_table->leftJoin('distribution_houses','distribution_houses.id','=','routes.distribution_houses_id');
            }
            else if($view_report == 'aso')
            {
                $orders_table->leftJoin('routes','routes.so_aso_user_id','=','users.id');
                $orders_table->leftJoin('distribution_houses','distribution_houses.id','=','routes.distribution_houses_id');
            }
            else
            {
                $orders_table->leftJoin('distribution_houses','distribution_houses.'.$table['name'].'_id','=',$table['name'].'.id');
            }
        }
        $orders_table->select(
            'distribution_houses.zone_name as zone',
            'distribution_houses.region_name as region',
            'distribution_houses.territory_name as territory',
            'distribution_houses.point_name as house'
        );

        if($view_report == 'aso'){
            $orders_table->where('user_type', '=', 'market');
        }
        $orders_table->leftJoin('sales_geo_records', function($join) use ($custom_search, $table, $report_type){
            $join->on($table['id_field'], '=', $table['geo_id']);
            if($report_type == 'order'){
                $join->where('sales_geo_records.sale_id', '=',  0);
            }else{
                $join->where('sales_geo_records.sale_id', '<>', 0);
            }
            $join->where(DB::raw('DATE_FORMAT(sales_geo_records.order_date, "%M-%Y")'), '=', $custom_search['month'][0]);
        });
//        if(!empty($custom_search[$view_report])){
        if(!empty($custom_search[$conditionalView])){
            $orders_table->whereIn($table['id_field'], $custom_search[$search]);
        }
        $data['mode'] = $mode;
        $data['search'] = $search;
        $data['table_query'] = $orders_table;
        $data['db_field_id'] = $table['id_field'].' AS naming_field_id';
        $data['db_field'] = $table['name_field'].' AS naming_field';
        $data['groupby_field'] = $table['id_field'];
        return $data;
    }

    public function getmonthlyAchvData($view_report = '', $memo_structure = [], $custom_search = [], $digdowndata=[]){
        $sku_list = $this->getSKUList($memo_structure);
        DB::enableQueryLog();
        //debug($custom_search,1);
        //-----------------------------------
        $order_query = self::locationOperation($custom_search, $view_report, $report_type = 'order');
        //debug($order_query,1);
        $order_query['table_query']->leftJoin('orders', function($join){
            $join->on('sales_geo_records.order_id', '=', 'orders.id')
                ->whereIN('orders.order_status', ['Edited', 'Processed']);
        });
        $order_query['table_query']->leftJoin('order_details', function($join){
            $join->on('orders.id', '=', 'order_details.orders_id');
        });
        $order_query['table_query']->addSelect($order_query['db_field_id'], $order_query['db_field']);

        foreach ($sku_list as $short_name => $skus){
            $order = "SUM(CASE WHEN order_details.short_name = '".$short_name."' THEN order_details.quantity ELSE 0 END) AS order_".$skus['id'];
            $order_query['table_query']->addSelect(DB::raw($order));
        }
        $order_query['table_query']->groupBy($order_query['groupby_field']);
        $order_data = $order_query['table_query']->get()->toArray();

        //-----------------------------------
        $sale_query = self::locationOperation($custom_search, $view_report, $report_type = 'sales');
        $sale_query['table_query']->leftJoin('sales', function($join){
            $join->on('sales_geo_records.sale_id', '=', 'sales.id')
                ->whereIN('sales.sale_status', ['Processed'])
                ->whereIN('sales.sale_type', ['Secondary']);
        });
        $sale_query['table_query']->leftJoin('sale_details', function($join){
            $join->on('sales.id', '=', 'sale_details.sales_id');
        });
        $sale_query['table_query']->select($sale_query['db_field_id'], $sale_query['db_field']);

        foreach ($sku_list as $short_name => $skus){
            $order = "SUM(CASE WHEN sale_details.short_name = '".$short_name."' THEN sale_details.quantity ELSE 0 END) AS achv_".$skus['id'];
            $sale_query['table_query']->addSelect(DB::raw($order));
        }
        $sale_query['table_query']->groupBy($sale_query['groupby_field']);
        $sale_data = $sale_query['table_query']->get()->toArray();
        //-----------------------------------
        $lift_query = self::locationOperation($custom_search, $view_report, $report_type = 'lift');
        $lift_query['table_query']->leftJoin('sales', function($join){
            $join->on('sales_geo_records.sale_id', '=', 'sales.id')
                ->whereIN('sales.sale_status', ['Processed'])
                ->whereIN('sales.sale_type', ['Primary']);
        });
        $lift_query['table_query']->leftJoin('sale_details', function($join){
            $join->on('sales.id', '=', 'sale_details.sales_id');
        });
        $lift_query['table_query']->select($lift_query['db_field_id'], $lift_query['db_field']);

        foreach ($sku_list as $short_name => $skus){
            $order = "SUM(CASE WHEN sale_details.short_name = '".$short_name."' THEN sale_details.quantity ELSE 0 END) AS lift_".$skus['id'];
            $lift_query['table_query']->addSelect(DB::raw($order));
        }
        $lift_query['table_query']->groupBy($lift_query['groupby_field']);
        $lift_data = $lift_query['table_query']->get()->toArray();
        //-----------------------------------
        $getTargets = self::getTargets($custom_search['month'][0], $order_query['mode'], $custom_search[$order_query['search']]);

        $grid_data = [];

        foreach ($order_data as $key => $orders){
            if($view_report != 'date'){
                $grid_data[$key]['view_type'] = !empty($orders->naming_field) ?
                    '<a href="" class="digdown" 
                            data-loctype="'.$view_report.'" 
                            data-locid="'.$orders->naming_field_id .'" data-month="'.$custom_search['month'][0].'">'.$orders->naming_field.'</a>' : 0;
            }else{
                $grid_data[$key]['view_type'] = !empty($orders->naming_field) ? $orders->naming_field : 0;
            }


            $grid_data[$key]['zone'] = $orders->zone;
            $grid_data[$key]['region'] = $orders->region;
            $grid_data[$key]['territory'] = $orders->territory;
            $grid_data[$key]['house'] = $orders->house;


            if(!empty($getTargets[$orders->naming_field_id])){
                $targets = json_decode($getTargets[$orders->naming_field_id], true);
            }else{
                $targets = 0;
            }
            //debug($sku_list,1);
            foreach ($sku_list as $short_name => $skus) {
                //debug(@$short_name);
//                $target = $targets == 0 ? 0 : @$targets[$short_name] / $skus['pack_size'];
                $target = $targets == 0 ? 0 : @$targets[$short_name];
                $order_field = 'order_'.$skus['id'];
                $order_num = $orders->$order_field / $skus['pack_size'];

                $lift_field = 'lift_'.$skus['id'];
                $lift_num = $lift_data[$key]->$lift_field / $skus['pack_size'];

                $achv_field = 'achv_'.$skus['id'];
                $achv_num = $sale_data[$key]->$achv_field / $skus['pack_size'];

                $grid_data[$key]['target'][] = number_format($target, 2);
                $grid_data[$key]['order'][] = number_format($order_num, 2);
                $grid_data[$key]['lifting'][] = number_format($lift_num, 2);
                $grid_data[$key]['lift_ratio'][] = $lift_num != 0 ? number_format(($target / $lift_num ) * 100, 2) : 0;
                $grid_data[$key]['achvmnt'][] = number_format($achv_num, 2);
                $grid_data[$key]['achvmnt_ratio'][] = $achv_num != 0 ? number_format(($target / $achv_num) * 100, 2) : 0;
            }

        }
        //debug($grid_data,1);
        return $grid_data;
    }

    /**-----------------------------------------------------**/
    public function digdownDSSAjax(Request $request){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);
        $loctype = $request_data['loctype'];
        $locid = $request_data['locid'];
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $custom_search['zones'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['regions'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territories'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['route'] = array_key_exists('id', $request_data) ? $request_data['route_id'] : [];
        $custom_search['month'] = array_key_exists('id', $request_data) ? $request_data['month'][0] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['position'] = $this->getStringLocation($custom_search['zones'], $custom_search['regions'], $custom_search['territories'], $custom_search['house']);
        $data['month'] = $custom_search['month'];
        $data['grid_data'] = $this->getDSSData(
            'date',
            $data['memo_structure'],
            NULL,
            ['loctype' => $loctype, 'locid' => $locid, 'month' => $custom_search['month']]
        );
        $data['view_column'] = 'date';
        return view('reports.dssreport.digdownview', $data);
    }

    public function getDSSData($view_report = '', $memo_structure = [], $custom_search = [], $digdowndata=[]){
        $sku_list = $this->getSKUList($memo_structure);
//        DB::enableQueryLog();
        $orders_table = DB::table('orders')
            ->where(DB::raw('DATE_FORMAT(orders.order_date, "%M-%Y")'), '=', $digdowndata['month'])
            ->whereIN('orders.order_status', ['Edited','Processed'])
            ->where('orders.order_type', '=', 'Secondary');

        if($digdowndata['loctype'] == 'zone'){
            $orders_table->where('sales_geo_records.zone_id', $digdowndata['locid']);
        }
        if($digdowndata['loctype'] == 'region'){
            $orders_table->where('sales_geo_records.region_id', $digdowndata['locid']);
        }
        if($digdowndata['loctype'] == 'territory'){
            $orders_table->where('sales_geo_records.territory_id', $digdowndata['locid']);
        }
        if($digdowndata['loctype'] == 'route'){
            $orders_table->where('sales_geo_records.route_id', $digdowndata['locid']);
        }
        if($digdowndata['loctype'] == 'aso'){
            $orders_table->where('sales_geo_records.aso_id', $digdowndata['locid']);
        }
        if($digdowndata['loctype'] == 'house'){
            $orders_table->where('sales_geo_records.dbid', $digdowndata['locid']);
        }
        $groupby_field = 'orders.order_date';
        $db_field_id = 'orders.order_date as naming_field_id';
        $db_field = 'orders.order_date as naming_field';

        $orders_table->leftJoin('sales_geo_records', function($join) use ($digdowndata){
            $join->on('sales_geo_records.order_id', '=', 'orders.id')
                ->where('sales_geo_records.sale_id', 0);
        });
        $orders_table->leftJoin('order_details', function($join){
            $join->on('orders.id', '=', 'order_details.orders_id');
        });
        $orders_table->leftJoin('sales', function($join) use ($digdowndata){
            $join->on('orders.id', '=', 'sales.order_id')
                ->where(DB::raw('DATE_FORMAT(orders.order_date, "%M-%Y")'), '=', $digdowndata['month'])
                ->where('sales.sale_type', '=', 'Secondary')
                ->where('sales.sale_status', '=', 'Processed');
        });
        $orders = "GROUP_CONCAT(DISTINCT `orders`.`id`) orderss";
        $ahvs = "GROUP_CONCAT(DISTINCT `sales`.`id`) saless";

        $orders_table->select(
            $db_field_id,
            $db_field,
            DB::raw($orders),
            DB::raw($ahvs)
        );

        $orders_table->groupBy($groupby_field);
        $orders_table->orderBy($groupby_field, 'asc');
        $order_data = $orders_table->get();
//        debug(DB::getQueryLog());
//        dd($order_data->toArray());
        $mode = '';
        if($digdowndata['loctype'] == 'zone') $mode = 'zones';
        if($digdowndata['loctype'] == 'region') $mode = 'regions';
        if($digdowndata['loctype'] == 'territory') $mode = 'territories';
        $getTargets = $this->getTargets($digdowndata['month'], $mode, [$digdowndata['locid']]);

        if(!empty($getTargets[$digdowndata['locid']])){
            $targets = json_decode($getTargets[$digdowndata['locid']], true);
        }else{
            $targets = 0;
        }
        $grid_data = [];
        foreach ($sku_list as $short_name => $skus) {
            $target = $targets == 0 ? 0 : $targets[$short_name] / $skus['pack_size'];
            $grid_data['target'][$short_name] = $target;
        }
        foreach ($order_data as $key => $orders){
            $order_details_data = [];
            $sale_details_data = [];
            $grid_data['tabledata'][$key]['view_type'] = !empty($orders->naming_field) ? $orders->naming_field : 0;
//            DB::enableQueryLog();
            $order_det_table = DB::table('order_details')->whereIn('order_details.orders_id', explode(',', $orders->orderss));
            $sale_det_table = DB::table('sale_details')->whereIn('sale_details.sales_id', explode(',', $orders->saless));

            foreach ($sku_list as $short_name => $skus_for_grid){
                $raw_order = "SUM(CASE WHEN order_details.short_name ='".$short_name."' THEN order_details.quantity ELSE 0 END) as ord_".$skus_for_grid['id'];
                $raw_sales = "SUM(CASE WHEN sale_details.short_name ='".$short_name."' THEN sale_details.quantity ELSE 0 END) as ach_".$skus_for_grid['id'];
                $order_det_table->addSelect(DB::raw($raw_order));
                $sale_det_table->addSelect(DB::raw($raw_sales));
            }
            $order_details_data = $order_det_table->get()->toArray();
            $sale_details_data = $sale_det_table->get();
//            debug(DB::getQueryLog());
//            debug($order_details_data);

            foreach ($sku_list as $short_name => $skus) {
                $order_field = 'ord_'.$skus['id'];
                $order_num = $order_details_data[0]->$order_field / $skus['pack_size'];
                $achv_field = 'ach_'.$skus['id'];
                $achv_num = $sale_details_data[0]->$achv_field / $skus['pack_size'];
                $grid_data['tabledata'][$key]['order'][] = $order_num;
                $grid_data['tabledata'][$key]['achvmnt'][] = $achv_num;
                $grid_data['tabledata'][$key]['achvmnt_ratio'][] = $target != 0 ? ($achv_num / $target) * 100 : 0;
            }
        }
//        dd($grid_data);
        return $grid_data;
    }
    /*===================================================*/
    public function getTargets($selected_month, $target_type, $type_id){
        $targets = DB::table('targets')
            ->select('type_id', 'target_value')
            ->where('target_type', $target_type)
            ->where('target_month', $selected_month)
            ->whereIn('type_id', $type_id)
            ->get()
            ->toArray();
        $mode_place = [];
        if(!empty($targets)){
            foreach ($targets as $target){
                $mode_place[$target->type_id] = $target->target_value;
            }
        }
        return $mode_place;
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


    public function export_monthly_achievement_dss($data,$filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        $additionalRowColumn = array(
            'addiColumn'=>array('Particulars')
        );

        ExportHelper::get_header_design($number,$row,'Monthly Achievement & DSS',$sheet);
        ExportHelper::get_column_title($number,$row,$data,2,$sheet,$additionalRowColumn);

        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Total');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row++;


        foreach($data['grid_data'] as $grids)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number).$row, strip_tags($grids['view_type']));
            $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+5))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+5))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $number++;


            foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number).$row,  $grids[$pctv]);
                $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+5))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+5))
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $number++;
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Target');
            $targetNumber = $number+1;
            foreach($grids['target'] as $target)
            {
                $sheet->setCellValue(ExportHelper::get_letter($targetNumber++).$row, $target);
            }


            $sheet->setCellValue(ExportHelper::get_letter($number).($row+1), 'Order');
            $orderNumber = $number+1;
            foreach($grids['order'] as $order)
            {
                $sheet->setCellValue(ExportHelper::get_letter($orderNumber++).($row+1), $order);
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+2), 'Lifting');
            $liftingNumber = $number+1;
            foreach($grids['lifting'] as $lifting)
            {
                $sheet->setCellValue(ExportHelper::get_letter($liftingNumber++).($row+2), $lifting);
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+3), 'Lifting Ach%');
            $liftRatioNumber = $number+1;
            foreach($grids['lift_ratio'] as $lift_ratio)
            {
                $sheet->setCellValue(ExportHelper::get_letter($liftRatioNumber++).($row+3), $lift_ratio);
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+4), 'Sales');
            $achvmntNumber = $number+1;
            foreach($grids['achvmnt'] as $achvmnt)
            {
                $sheet->setCellValue(ExportHelper::get_letter($achvmntNumber++).($row+4), $achvmnt);
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+5), 'Sales Ach%');
            $achvmnt_ratioNumber = $number+1;
            foreach($grids['achvmnt_ratio'] as $achvmnt_ratio)
            {
                $sheet->setCellValue(ExportHelper::get_letter($achvmnt_ratioNumber++).($row+5), $achvmnt_ratio);
            }

            $number++;
            $sheet->setCellValue(ExportHelper::get_letter($targetNumber).$row, '=sum('.ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($targetNumber-1).$row.')');
            $sheet->setCellValue(ExportHelper::get_letter($orderNumber).($row+1), '=sum('.ExportHelper::get_letter($number).($row+1).':'.ExportHelper::get_letter($targetNumber-1).($row+1).')');
            $sheet->setCellValue(ExportHelper::get_letter($liftingNumber).($row+2), '=sum('.ExportHelper::get_letter($number).($row+2).':'.ExportHelper::get_letter($targetNumber-1).($row+2).')');
            $sheet->setCellValue(ExportHelper::get_letter($liftRatioNumber).($row+3), '=sum('.ExportHelper::get_letter($number).($row+3).':'.ExportHelper::get_letter($targetNumber-1).($row+3).')');
            $sheet->setCellValue(ExportHelper::get_letter($achvmntNumber).($row+4), '=sum('.ExportHelper::get_letter($number).($row+4).':'.ExportHelper::get_letter($targetNumber-1).($row+4).')');
            $sheet->setCellValue(ExportHelper::get_letter($achvmnt_ratioNumber).($row+5), '=sum('.ExportHelper::get_letter($number).($row+5).':'.ExportHelper::get_letter($targetNumber-1).($row+5).')');

            $row = $row+6;
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
