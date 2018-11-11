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

class PromotionalReport extends Controller {
    private $routes;
    public function __construct(){
        $this->routes= json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
    }

    public function PromotionalReportList(){
        $data['metaTitle'] = 'Globe | Promotional Report';
        $data['ajaxUrl'] = URL::to('PromotionalReportAjax/');
        $data['view'] = 'promotionalreport.view';
        $data['header_level'] = 'Promotional Report';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory', 'aso', 'house','category','brand', 'package', 'sku','daterange', 'view-report')); //View Structure
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $data['memo_structure'] = repoStructure();
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Promotional Report'));
        return view('reports.report', $data);
    }

    public function PromotionalReportAjax(Request $request, $data = []){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);

        $view_report = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $date_range = key_exists('created_at', $request_data) ? explode(' - ', $request_data['created_at'][0]) : [];

        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $custom_search['zone'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['region'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territory'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['aso'] = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $custom_search['package_id'] = array_key_exists('aso_id', $request_data) ? $request_data['packages_id'] : [];

        $data['position'] = $this->getStringLocation($custom_search['zone'], $custom_search['region'], $custom_search['territory'], $custom_search['house']);
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['grid_data'] = $this->getPromotionalReportData($view_report[0], $date_range, $data['memo_structure'], $custom_search);
        $data['view_column'] = $view_report[0];
        $data['view_report'] = ucfirst($view_report[0]);
        $search_type = $post['search_type'][0];
        if($search_type == 'show')
        {
            return view('reports.promotionalreport.view', $data);
        }
        else if($search_type == 'download')
        {
            $filename='promotional-report-'.Auth::user()->id.'.xlsx';
            $this->export_promotional_report($data,$filename);
            echo $filename;
        }

    }

    public function getPromotionalReportData($view_report = '', $date_range = [], $memo_structure = [], $custom_search = [], $digdowndata=[]){
        $package_name = $custom_search['package_id'][0];
        $sku_list = $this->getSKUList($memo_structure);
//        DB::enableQueryLog();
        if($view_report == 'zone') {
            $orders_table = DB::table('zones');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.zones_id', '=', 'zones.id');
            $orders_table->leftJoin('sales_geo_records', function($join) use ($date_range){
                $join->on('zones.id', '=', 'sales_geo_records.zone_id')
                    ->whereNotIn('sales_geo_records.sale_id', [0])
                    ->whereBetween('sales_geo_records.sale_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('zones.id', $custom_search['zone']);
            }
            $db_field_id = 'zones.id as naming_field_id';
            $db_field = 'zones.zone_name as naming_field';
            $groupby_field = 'zones.id';
        }
        else if($view_report == 'region'){
            $orders_table = DB::table('regions');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.regions_id', '=', 'regions.id');
            $orders_table->leftJoin('sales_geo_records', function($join) use ($date_range){
                $join->on('regions.id', '=', 'sales_geo_records.region_id')
                    ->whereNotIn('sales_geo_records.sale_id', [0])
                    ->whereBetween('sales_geo_records.sale_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('regions.id', $custom_search['region']);
            }
            $groupby_field = 'regions.id';
            $db_field_id = 'regions.id as naming_field_id';
            $db_field = 'regions.region_name as naming_field';
        }
        else if($view_report == 'aso'){
            $orders_table = DB::table('users')->where('users.user_type', 'market');
            $orders_table->leftJoin('routes', 'routes.so_aso_user_id', '=', 'users.id');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id');
            $orders_table->leftJoin('sales_geo_records', function($join) use ($date_range){
                $join->on('users.id', '=', 'sales_geo_records.aso_id')
                    ->whereNotIn('sales_geo_records.sale_id', [0])
                    ->whereBetween('sales_geo_records.sale_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('users.id', $custom_search['aso']);
            }
            $groupby_field = 'users.id';
            $db_field_id = 'users.id as naming_field_id';
            $db_field = 'users.name as naming_field';
        }
        else if($view_report == 'territory'){
            $orders_table = DB::table('territories');
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.territories_id', '=', 'territories.id');
            $orders_table->leftJoin('sales_geo_records', function($join) use ($date_range){
                $join->on('territories.id', '=', 'sales_geo_records.territory_id')
                    ->whereNotIn('sales_geo_records.sale_id', [0])
                    ->whereBetween('sales_geo_records.sale_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('territories.id', $custom_search['territory']);
            }
            $groupby_field = 'territories.id';
            $db_field_id = 'territories.id as naming_field_id';
            $db_field = 'territories.territory_name as naming_field';
        }
        else if($view_report == 'date'){
            $orders_table = DB::table('sales_geo_records')
                ->whereNotIn('sales_geo_records.sale_id', [0])
                ->whereBetween('sales_geo_records.sale_date', $date_range);
            $orders_table->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'sales_geo_records.dbid');
            if(!empty($digdowndata)){
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
                if($digdowndata['loctype'] == 'aso'){
                    $orders_table->where('sales_geo_records.aso_id', $digdowndata['locid']);
                }
            }
            $groupby_field = 'sales_geo_records.sale_date';
            $db_field_id = 'sales_geo_records.sale_date as naming_field_id';
            $db_field = 'sales_geo_records.sale_date as naming_field';
        }
        else{
            // for houses
            $orders_table = DB::table('distribution_houses');
            $orders_table->leftJoin('sales_geo_records', function($join) use ($date_range){
                $join->on('distribution_houses.id', '=', 'sales_geo_records.dbid')
                    ->whereNotIn('sales_geo_records.sale_id', [0])
                    ->whereBetween('sales_geo_records.sale_date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('distribution_houses.id', $custom_search['house']);
            }
            $groupby_field = 'distribution_houses.id';
            $db_field_id = 'distribution_houses.id as naming_field_id';
            $db_field = 'distribution_houses.point_name as naming_field';
        }

        $orders_table->leftJoin('sales', function($join){
            $join->on('sales.id', '=', 'sales_geo_records.sale_id')
                ->where('sales.sale_type', 'Promotional');
        });

        $orders_table->leftJoin('sale_details', function($join) use ($package_name){
            $join->on('sales.id', '=', 'sale_details.sales_id')
                ->where('sale_details.short_name', $package_name);
        });

        $orders_table->select(
            'distribution_houses.zone_name as zone',
            'distribution_houses.region_name as region',
            'distribution_houses.territory_name as territory',
            'distribution_houses.point_name as house',
            $db_field_id,
            $db_field,
            DB::raw('SUM(sale_details.case) AS promotion_case'),
            DB::raw('SUM(sale_details.no_of_memo) AS promotion_memo')
        );
        $orders_table->groupBy($groupby_field);
        $order_data = $orders_table->get();
//        debug(DB::getQueryLog());
//        debug($order_data);
        $grid_data = [];
//        DB::enableQueryLog();
        $package_details = self::getPackageDetails($package_name);
//        debug(DB::getQueryLog());
//        dd($package_details);
        $pack_details = json_decode($package_details[$package_name]['pack_details'], true);
        $pack_free_items = json_decode($package_details[$package_name]['pack_free_item'], true);

        $merged_pack = array();
        foreach (array_keys($pack_details + $pack_free_items) as $key) {
            $merged_pack[$key] = (isset($pack_details[$key]) ? $pack_details[$key] : 0)
                + (isset($pack_free_items[$key]) ? $pack_free_items[$key] : 0);
        }
        foreach ($order_data as $key => $orders){
            if($view_report != 'date'){
                $grid_data[$key]['view_type'] = !empty($orders->naming_field) ?
                    '<a href="javascript:void(0);" class="digdown" 
                            data-loctype="'.$view_report.'" 
                            data-locid="'.$orders->naming_field_id .'" 
                            data-startdate="'.$date_range[0].'" 
                            data-enddate="'.$date_range[1].'">'.$orders->naming_field.'</a>' : 0;
            }else{
                $grid_data[$key]['view_type'] = !empty($orders->naming_field) ? $orders->naming_field : 0;
            }

            $grid_data[$key]['zone'] = $orders->zone;
            $grid_data[$key]['region'] = $orders->region;
            $grid_data[$key]['territory'] = $orders->territory;
            $grid_data[$key]['house'] = $orders->house;


            $grid_data[$key]['package'] = !empty($orders->promotion_case) ? $orders->promotion_case : 0;
            $grid_data[$key]['memo'] = !empty($orders->promotion_memo) ? $orders->promotion_memo : 0;

            foreach ($sku_list as $short_name => $skus) {
                $grid_data[$key]['sku_pack'][] = isset($merged_pack[$short_name]) && $merged_pack[$short_name] != 0 ? $merged_pack[$short_name]*$grid_data[$key]['package'] : 0;
            }
        }
//        debug($grid_data);
        return $grid_data;
    }

    /**-----------------------------------------------------**/
    public function digdownPromotionalReportAjax(Request $request){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);

        $start_date = $request_data['startdate'];
        $end_date = $request_data['enddate'];
        $loctype = $request_data['loctype'];
        $locid = $request_data['locid'];
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $custom_search['zone'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['region'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territory'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['aso'] = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $custom_search['package_id'] = array_key_exists('aso_id', $request_data) ? $request_data['packages_id'] : [];
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['position'] = $this->getStringLocation($custom_search['zone'], $custom_search['region'], $custom_search['territory'], $custom_search['house']);
        $data['grid_data'] = $this->getPromotionalReportData(
            'date',
            [$start_date, $end_date],
            $data['memo_structure'],
            $custom_search,
            ['loctype'=>$loctype, 'locid'=>$locid]
        );
        $data['view_column'] = 'date';
        return view('reports.promotionalreport.view', $data);
    }

    /*===================================================*/
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
    private function getStringLocation($zone, $region, $territory, $house){
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
    private function getPackageDetails($package_name = ''){
        $query = DB::table('promotional_package')
            ->select('shortname', 'pack_name', 'pack_free_item', 'pack_details')
            ->where('shortname', $package_name)
            ->get();
        $package_list = [];
        foreach ($query as $item) {
            $package_list[$item->shortname]['pack_details'] = $item->pack_details;
            $package_list[$item->shortname]['pack_free_item'] = $item->pack_free_item;
        }
        return $package_list;
    }

    public function export_promotional_report($data,$filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        $additionalRowColumn = array(
            'addiColumn'=>array('No Of Memo','No Of Package')
        );
        ExportHelper::get_header_design($number,$row,'Promotional Report',$sheet);
        ExportHelper::get_column_title($number,$row,$data,2,$sheet,$additionalRowColumn);
        $row++;
        foreach($data['grid_data'] as $grids)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, strip_tags($grids['view_type']));

            foreach(parrentColumnTitleValue(ucfirst($data['view_report']),3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids[$pctv]);
            }
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['memo']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $grids['package']);
            foreach($grids['sku_pack'] as $sku_pack)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $sku_pack);
            }
            $row++;
        }
        ExportHelper::excelHeader($filename,$spreadsheet);
    }
}
