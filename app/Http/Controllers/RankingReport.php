<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use reportsHelper;
use App\Models\Menu;
use App\Models\User;


//for excel library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helper\ExportHelper;

class RankingReport extends Controller {
    private $routes;
    public function __construct(){
        $this->routes = json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
    }

    public function rankingList(){
        $data['metaTitle'] = 'Globe | Ranking Reports';
        $data['ajaxUrl'] = URL::to('RankingAjax/');
        $data['view'] = 'ranking.view';
        $data['header_level'] = 'Ranking Report';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory',
            'house', 'aso', 'month', 'view-report')); //View Structure
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Ranking Report'));
        return view('reports.report', $data);
    }

    public function RankingAjax(Request $request, $data = []){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);
        $view_report = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $custom_search['zones'] = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $custom_search['regions'] = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $custom_search['territories'] = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $custom_search['house'] = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $custom_search['aso'] = array_key_exists('id', $request_data) ? $request_data['aso_id'] : [];
        $custom_search['month'] = array_key_exists('id', $request_data) ? $request_data['month'] : [];

        $data['position'] = $this->getStringLocation($custom_search['zones'], $custom_search['regions'], $custom_search['territories'], $custom_search['house']);
        $data['grid_data'] = $this->getRankingData($view_report[0], $custom_search);
        $data['view_column'] = $view_report[0];

        $data['view_report'] = ucfirst($view_report[0]);
        $search_type = $post['search_type'][0];
        if($search_type == 'show')
        {
            return view('reports.ranking.view', $data);
        }
        else if($search_type == 'download')
        {
            $filename='ranking-report-'.Auth::user()->id.'.xlsx';
            $this->export_ranking_report($data,$filename);
            echo $filename;
        }
    }
    public function getRankingData($view_report = '', $custom_search = []){
        $selected_month = $custom_search['month'];
        $prev_month = date('F-Y', strtotime($selected_month[0]." -1 month"));

        $designations = self::getDesignation();
        DB::enableQueryLog();
        $final_table = DB::table('users');
        if($view_report == 'zone') {
            $user_type = 'zone';
            $location_field = 'users.zones_id';
            $working_field = 'users.zones_id';
            $geo_field = 'sales_geo_records.zone_id';
            $custom_search_mode = 'zones';
            $workplace = self::getWorkingPlace('zones', 'zone_name',$view_report);
        }
        else if($view_report == 'region'){
            $user_type = 'region';
            $location_field = 'users.regions_id';
            $working_field = 'users.regions_id';
            $geo_field = 'sales_geo_records.region_id';
            $custom_search_mode = 'regions';
            $workplace = self::getWorkingPlace('regions', 'region_name',$view_report);
        }
        else if($view_report == 'territory'){
            $user_type = 'territory';
            $location_field = 'users.territories_id';
            $working_field = 'users.territories_id';
            $geo_field = 'sales_geo_records.territory_id';
            $custom_search_mode = 'territories';
            $workplace = self::getWorkingPlace('territories', 'territory_name',$view_report);
        }
        else if($view_report == 'aso'){
            $user_type = 'market';
            $location_field = 'users.id';
            $working_field = 'users.distribution_house_id';
            $geo_field = 'sales_geo_records.aso_id';
            $custom_search_mode = 'aso';
            $workplace = self::getWorkingPlace('distribution_houses', 'market_name',$view_report);
        }
        else{
            $user_type = 'house';
            $location_field = 'users.distribution_house_id';
            $working_field = 'users.distribution_house_id';
            $geo_field = 'sales_geo_records.dbid';
            $custom_search_mode = 'house';
            $workplace = self::getWorkingPlace('distribution_houses', 'point_name',$view_report);
        }
        $final_table->where('user_type', $user_type);

        if(!empty($custom_search[$custom_search_mode])){
            $final_table->whereIn($location_field, $custom_search[$custom_search_mode]);
        }

        $final_table->leftJoin('sales_geo_records', function($join) use ($geo_field, $location_field){
            $join->on($geo_field, $location_field);
        });


        $final_table->leftJoin('distribution_houses','distribution_houses.id','=','sales_geo_records.dbid');


        $final_table->leftJoin('orders as cur_order', function($join) use ($selected_month){
            $join->on('sales_geo_records.order_id', '=', 'cur_order.id')
                ->where('sales_geo_records.sale_id', 0)
                ->where('cur_order.order_type', 'Secondary')
                ->where('cur_order.order_status', 'Processed')
                ->where(DB::raw('DATE_FORMAT(cur_order.order_date, "%M-%Y")'), '=', $selected_month[0]);
        });
        $final_table->leftJoin('orders as prev_order', function($join) use ($prev_month){
            $join->on('sales_geo_records.order_id', '=', 'prev_order.id')
                ->where('sales_geo_records.sale_id', 0)
                ->where('prev_order.order_type', 'Secondary')
                ->where('prev_order.order_status', 'Processed')
                ->where(DB::raw('DATE_FORMAT(prev_order.order_date, "%M-%Y")'), '=', $prev_month);
        });
        $final_table->leftJoin('order_details as order_details_cur', function($join){
            $join->on('cur_order.id', '=', 'order_details_cur.orders_id');
        });
        $final_table->leftJoin('order_details as order_details_prev', function($join){
            $join->on('prev_order.id', '=', 'order_details_prev.orders_id');
        });
        $final_table->leftJoin('sales as sales_cur', function($join) use ($selected_month){
            $join->on('sales_geo_records.sale_id', '=', 'sales_cur.id')
                ->where(DB::raw('DATE_FORMAT(sales_cur.sale_date, "%M-%Y")'), '=', $selected_month[0]);
        });
        $final_table->leftJoin('sales as prev_sales', function($join) use ($prev_month){
            $join->on('sales_geo_records.sale_id', '=', 'prev_sales.id')
                ->where(DB::raw('DATE_FORMAT(prev_sales.sale_date, "%M-%Y")'), '=', $prev_month);
        });
        $final_table->leftJoin('sale_details as sale_details_cur', function($join){
            $join->on('sales_cur.id', '=', 'sale_details_cur.sales_id')
                ->where('sales_cur.sale_type', '=', 'Secondary');
        });
        $final_table->leftJoin('sale_details as sale_details_prev', function($join){
            $join->on('prev_sales.id', '=', 'sale_details_prev.sales_id')
                ->where('prev_sales.sale_type', '=', 'Secondary');
        });
        $locationfield = $working_field." as locationfield";
        $final_table->select(
            'users.id', 'users.code', 'users.name', $locationfield, 'users.designation_id',
            DB::raw("SUM(CASE WHEN order_details_cur.short_name = 'tp' then cur_order.total_outlet else 0 end) total_outlet_num"),
            DB::raw("SUM(CASE WHEN order_details_cur.short_name = 'tp' then cur_order.visited_outlet else 0 end) total_visited_outlet"),
            DB::raw("SUM(CASE WHEN order_details_cur.short_name = 'tp' then cur_order.total_no_of_memo else 0 end) total_successfull_memo"),
            DB::raw("SUM(CASE WHEN order_details_cur.short_name = 'tp' then cur_order.order_total_case else 0 end) total_order_quantity"),
            DB::raw("SUM(CASE WHEN order_details_cur.short_name = 'tp' then cur_order.order_amount else 0 end) total_order_amount"),
            DB::raw("SUM(CASE WHEN order_details_cur.short_name = 'tp' then sales_cur.sale_total_case else 0 end) total_sale_quantity"),
            DB::raw("SUM(CASE WHEN order_details_cur.no_of_memo IS NULL THEN 0 ELSE order_details_cur.no_of_memo END) total_individual_memo"),

            DB::raw("SUM(CASE WHEN order_details_prev.short_name = 'tp' then prev_order.total_outlet else 0 end) total_outlet_num_prev"),
            DB::raw("SUM(CASE WHEN order_details_prev.short_name = 'tp' then prev_order.visited_outlet else 0 end) total_visited_outlet_prev"),
            DB::raw("SUM(CASE WHEN order_details_prev.short_name = 'tp' then prev_order.total_no_of_memo else 0 end) total_successfull_memo_prev"),
            DB::raw("SUM(CASE WHEN order_details_prev.short_name = 'tp' then prev_order.order_total_case else 0 end) total_order_quantity_prev"),
            DB::raw("SUM(CASE WHEN order_details_prev.short_name = 'tp' then prev_order.order_amount else 0 end) total_order_amount_prev"),
            DB::raw("SUM(CASE WHEN sale_details_prev.short_name = 'tp' then prev_sales.sale_total_case else 0 end) total_sale_quantity_prev"),
            DB::raw("SUM(CASE WHEN order_details_prev.no_of_memo IS NULL THEN 0 ELSE order_details_prev.no_of_memo END) total_individual_memo_prev")
        );

        $final_table->groupBy('users.id');
        $order_data = $final_table->get();
        //debug($order_data,1);
//        debug(DB::getQueryLog());
        $grid_data = [];
//        dd($order_data->toArray());
        foreach ($order_data as $key => $orders){
            //debug($orders,1);
            $grid_data[$key]['code'] = !empty($orders->code) ? $orders->code : 0;
            $grid_data[$key]['view_type'] = !empty($orders->name) ? $orders->name : 0;
            $grid_data[$key]['designation'] = isset($designations[$orders->designation_id]) ? $designations[$orders->designation_id] : '';
            $grid_data[$key]['workplace'] = isset($workplace[$orders->locationfield]) ? $workplace[$orders->locationfield]['field_name'] : '';




            $grid_data[$key]['zone'] = isset($workplace[$orders->locationfield]) ? $workplace[$orders->locationfield]['zone_name'] : '';
            $grid_data[$key]['region'] = isset($workplace[$orders->locationfield]) ? $workplace[$orders->locationfield]['region_name'] : '';
            $grid_data[$key]['territory'] = isset($workplace[$orders->locationfield]) ? $workplace[$orders->locationfield]['territory_name'] : '';
            $grid_data[$key]['house'] = isset($workplace[$orders->locationfield]) ? $workplace[$orders->locationfield]['house_name'] : '';





            $emp_data['total_outlet'] = $orders->total_outlet_num;
            $emp_data['total_visited_outlet'] = $orders->total_visited_outlet;
            $emp_data['total_no_of_memo'] = $orders->total_successfull_memo;
            $emp_data['total_order_quantity'] = $orders->total_order_quantity;
            $emp_data['total_order_amount'] = $orders->total_order_amount;
            $emp_data['total_sale_quantity'] = $orders->total_sale_quantity;
            $emp_data['total_individual_sku_quantity'] = $orders->total_individual_memo;
            //debug($emp_data,1);


            $prev_data['total_outlet'] = $orders->total_outlet_num_prev;
            $prev_data['total_visited_outlet'] = $orders->total_visited_outlet_prev;
            $prev_data['total_no_of_memo'] = $orders->total_successfull_memo_prev;
            $prev_data['total_order_quantity'] = $orders->total_order_quantity_prev;
            $prev_data['total_order_amount'] = $orders->total_order_amount_prev;
            $prev_data['total_sale_quantity'] = $orders->total_sale_quantity_prev;
            $prev_data['total_individual_sku_quantity'] = $orders->total_individual_memo_prev;

            $grid_data[$key]['achv_point'] = (($emp_data['total_order_quantity']>0)?self::getRankingAch($emp_data):0);
            $grid_data[$key]['achv_color'] = self::getColor($grid_data[$key]['achv_point']);

            $grid_data[$key]['prev_achv_point'] = (($prev_data['total_order_quantity']>0)?self::getRankingAch($prev_data):0);
            $grid_data[$key]['prev_achv_color'] = self::getColor($grid_data[$key]['prev_achv_point']);
            //dd($emp_data,$prev_data);

        }
        //debug($grid_data[$key]['prev_achv_point'],1);
//        $value_ach = [];
//        foreach ($grid_data as $key => $value) {
//            $value_ach[$key] = $value['achv_point'];
//        }
        array_multisort(array_column($grid_data,'achv_point'), SORT_DESC, $grid_data);
        return $grid_data;
    }

    private static function getRankingAch($data){
        //debug($data,1);
        $rankingConfig = Config::get('rank')['ranking'];
        $visited_outlet_mark = 0;
        $call_productivity_mark = 0;
        $brand_call_productivity = 0;
        $protfolio_volume = 0;
        $value_per_call = 0;
        $bounce_call = 0;
        foreach ($rankingConfig as $key => $value) {
            //debug($key,1);
            switch ($key) {
                case 'v_o':
                    $obtained_marks = $data['total_outlet'] != 0 ? ($data['total_visited_outlet'] / $data['total_outlet']) * 100 : 0;
                    if ($obtained_marks > $rankingConfig['v_o']['required_mark']) {
                        $visited_outlet_mark = $rankingConfig['v_o']['marks'];
                    }else{
                        $visited_outlet_mark = $rankingConfig['v_o']['required_mark'] != 0 ? ($rankingConfig['v_o']['marks'] * $obtained_marks) / $rankingConfig['v_o']['required_mark'] : 0;
                    }

                case 'c_p':
                    $obtained_marks = $data['total_visited_outlet'] != 0 ? ($data['total_no_of_memo'] / $data['total_visited_outlet']) * 100 : 0;
                    if ($obtained_marks > $rankingConfig['c_p']['required_mark']) {
                        $call_productivity_mark = $rankingConfig['c_p']['marks'];
                    }else{
                        $call_productivity_mark = $rankingConfig['c_p']['required_mark'] != 0 ? ($rankingConfig['c_p']['marks'] * $obtained_marks) / $rankingConfig['c_p']['required_mark'] : 0;
                    }
                case 'bcp':
                    $obtained_marks = $data['total_no_of_memo'] != 0 ? $data['total_individual_sku_quantity'] / $data['total_no_of_memo'] : 0;
                    if($obtained_marks > $rankingConfig['bcp']['required_mark']){
                        $brand_call_productivity = $rankingConfig['bcp']['marks'];
                    }else{
                        $brand_call_productivity = $rankingConfig['bcp']['required_mark'] != 0
                            ? ($rankingConfig['bcp']['marks'] * $obtained_marks) / $rankingConfig['bcp']['required_mark']
                            : 0;
                    }
                case 'p_v':
                    //debug($data,1);
                    //dd($data['total_order_quantity'],$data['total_no_of_memo']);
                    $obtained_marks = $data['total_no_of_memo'] != 0 ? ($data['total_order_quantity'] / $data['total_no_of_memo']) : 0;
                    //debug($obtained_marks,1);
                    if($obtained_marks > $rankingConfig['p_v']['required_mark'] ){
                        $protfolio_volume = $rankingConfig['p_v']['marks'] ;
                    }else{

                        $protfolio_volume = $rankingConfig['p_v']['required_mark'] != 0 ? ($rankingConfig['p_v']['marks'] * $obtained_marks) / $rankingConfig['p_v']['required_mark']: 0;
                    }
                case 'v_p_c':
                    $obtained_marks = $data['total_no_of_memo'] != 0 ? $data['total_order_amount'] / $data['total_no_of_memo'] : 0;
                    if($obtained_marks > $rankingConfig['v_p_c']['required_mark'] ){
                        $value_per_call = $rankingConfig['v_p_c']['marks'];
                    }else{
                        $value_per_call = $rankingConfig['v_p_c']['required_mark'] != 0 ? ($rankingConfig['v_p_c']['marks'] * $obtained_marks) / $rankingConfig['v_p_c']['required_mark'] : 0;
                    }
                case 'b_c':
                    $obtained_marks = $data['total_order_quantity'] != 0 ? ($data['total_order_quantity'] - $data['total_sale_quantity']) / $data['total_order_quantity'] : 0;


                    //debug($obtained_marks,1);
                    $bounce_call = 0;
                    if($obtained_marks > 50)
                    {
                        $bounce_call = 0;
                    }
                    else if($obtained_marks > 45 && $obtained_marks <= 50)
                    {
                        $bounce_call = 2;
                    }
                    else if($obtained_marks > 40 && $obtained_marks <= 45)
                    {
                        $bounce_call = 4;
                    }
                    else if($obtained_marks > 35 && $obtained_marks <= 40)
                    {
                        $bounce_call = 6;
                    }
                    else if($obtained_marks > 30 && $obtained_marks <= 35)
                    {
                        $bounce_call = 8;
                    }
                    else if($obtained_marks > 25 && $obtained_marks <= 30)
                    {
                        $bounce_call = 10;
                    }
                    else if($obtained_marks > 20 && $obtained_marks <= 25)
                    {
                        $bounce_call = 12;
                    }
                    else if($obtained_marks > 15 && $obtained_marks <= 20)
                    {
                        $bounce_call = 14;
                    }
                    else if($obtained_marks > 10 && $obtained_marks <= 15)
                    {
                        $bounce_call = 16;
                    }
                    else if($obtained_marks > 5 && $obtained_marks <= 10)
                    {
                        $bounce_call = 18;
                    }
                    else if($obtained_marks >= 0 && $obtained_marks <= 5)
                    {
                        $bounce_call = 20;
                    }


//                    if($obtained_marks > $rankingConfig['b_c']['required_mark']){
//                        $bounce_call = $rankingConfig['b_c']['marks'];
//                    }else{
//                        $bounce_call = $rankingConfig['b_c']['required_mark'] != 0 ? ($rankingConfig['b_c']['marks'] * $obtained_marks) / $rankingConfig['b_c']['required_mark'] : 0;
//                    }




//                    if ($bounce_call < 0) {
//                        $bounce_call = 0;
//                    }
            }
        }
//        $aa['visited_outlet_mark'] = $visited_outlet_mark;
//        $aa['call_productivity_mark'] = $call_productivity_mark;
//        $aa['protfolio_volume'] = $protfolio_volume;
//        $aa['value_per_call'] = $value_per_call;
//        $aa['bounce_call'] = $bounce_call;
//        $aa['brand_call_productivity'] = $brand_call_productivity;
//        dd($aa);
        //dd('visited_outlet_mark - '.$visited_outlet_mark,'call_productivity_mark - '.$call_productivity_mark,'protfolio_volume - '.$protfolio_volume,'value_per_call - '.$value_per_call,'bounce_call - '.$bounce_call,'brand_call_productivity - '.$brand_call_productivity);
        $total = $visited_outlet_mark + $call_productivity_mark + $protfolio_volume + $value_per_call + $bounce_call + $brand_call_productivity;
        //debug($total,1);
        return number_format($total, 2);
    }

    private static function getColor($value){
        switch ($value) {
            case $value >= 80 && $value <= 100:
                return '59E759';
            case $value >= 70 && $value < 80:
                return '009900';
            case  $value >= 60 && $value < 70:
                return 'FFFF00';
            case  $value >= 50 && $value < 60:
                return 'FF9900';
            case   $value < 50:
                return 'FF0000';
        }
    }
    /*===================================================*/
    public function getDesignation(){
        $query = DB::table('designations')->select('id', 'name')->get();
        $data = [];
        foreach ($query as $d){
            $data[$d->id] = $d->name;
        }
        return $data;
    }
    public function getWorkingPlace($table, $name_field,$view_report){

        $query = DB::table($table);
        $query->select($table.'.id', $table.'.'.$name_field,'distribution_houses.zone_name as zone','distribution_houses.region_name as region','distribution_houses.territory_name as territory','distribution_houses.point_name as house');
        if(($view_report == 'zone') || ($view_report == 'region') || ($view_report == 'territory'))
        {
            $query->leftJoin('distribution_houses','distribution_houses.'.$table.'_id','=',$table.'.id');
        }
        $result = $query->get();

        $data = [];
        foreach ($result as $d){
            $data[$d->id]['field_name'] = $d->$name_field;
            $data[$d->id]['zone_name'] = $d->zone;
            $data[$d->id]['region_name'] = $d->region;
            $data[$d->id]['territory_name'] = $d->territory;
            $data[$d->id]['house_name'] = $d->house;
        }
        //debug($data,1);
        return $data;
    }
    /*===================================================*/
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


    public function export_ranking_report($data,$filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;



        ExportHelper::get_header_design($number,$row,'Ranking Report',$sheet);
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Serial Number');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Employee Code');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Name');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Designation');
        $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Work Area');
        ExportHelper::geo_map_excel($number,$row,$row,$data,2,$sheet);
        $number++;
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Last Month Achievement Point');
        $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'This Month Achievement Point');

        $row++;
        $sl = 1;
        foreach($data['grid_data'] as $grids)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $sl);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids['code']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids['view_type']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids['designation']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids['workplace']);
            foreach(parrentColumnTitleValue(ucfirst($data['view_report']),3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids[$pctv]);
            }
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids['prev_achv_point']);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $grids['achv_point']);
            $row++;
            $sl++;
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
