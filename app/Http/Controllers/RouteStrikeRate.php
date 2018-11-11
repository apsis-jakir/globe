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

class RouteStrikeRate extends Controller {
    private $routes;
    public function __construct(){
        $this->routes= json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
    }

    public function RouteStrikeRateList(){
//        $data['ajaxUrl'] = URL::to('RouteStrikeRateAjax/');
//        $data['view_load'] = 0;
//        $data['view'] = 'routeStrikeRate.view';
//        $data['header_level'] = 'Strike Rate';
//        $data['searching_options'] = 'grid.search_elements_all';
//        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory','house', 'route', 'aso', 'category', 'brand', 'sku', 'daterange', 'view-report')); //View Structure
//        $data['position']="";
//        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Strike Rate'));
//        return view('reports.routeStrikeRate.tabulator_strike', $data);


        $data['ajaxUrl'] = URL::to('RouteStrikeRateAjax/');
        $data['view'] = 'routeStrikeRate.route-wise_strike-rate-ajax';
        $data['header_level'] = 'Strike Rate';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory','house', 'route', 'aso', 'category', 'brand', 'sku', 'daterange', 'view-report')); //View Structure
        $data['level'] = 5;
        $data['level_col_data'] = ['Req', 'Del'];
        $data['memo_structure'] = repoStructure();
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Strike Rate'));
        return view('reports.main', $data);
        //return view('reports.report', $data);
    }

    public function RouteStrikeRateAjax(Request $request, $data = []){
        $post = $request->all();
        unset($post['_token']);
        $request_data = array_filter($post);
        //debug($request_data,1);
        $view_report = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $date_range = key_exists('created_at', $request_data) ? explode(' - ', $request_data['created_at'][0]) : [];
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $sku_short_name = skuidToShortName($sku_ids);
        //debug($date_range,1);
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $aso_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $asotoroute = getRoutesIdFromAsoId($aso_ids);
        $route_ids = array_key_exists('route_id', $request_data) ? $request_data['route_id'] : [];
        //debug($route_ids,1);
        //$selected_values = array('zones' => $zone_ids, 'regions' => $region_ids, 'territories' => $territory_ids, 'house' => $house_ids, 'aso' => $asotoroute, 'route' => $route_ids);
        if($view_report[0] == 'aso')
        {
            $target_config = array(
                'type'=>'aso',
                'table'=>'users',
                'field_name'=>'name',
                'field_code'=>'code',
                'aso'=>'aso',
                'field_id'=>'id',
            );
        }
        else
        {
            $target_config = ReportsHelper::targetsConfigData($view_report[0]);
        }

        $data['config'] = $target_config;
        //debug($data['config'],1);
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);

        $data['level'] = 5;
        $data['level_col_data'] = ['Amount', 'Quantity'];


        $data['grid_data'] = $this->getRouteStrikeRateData($route_ids,$sku_short_name,$date_range,$view_report[0],$target_config);
        //$data['grid_data'] = $this->getRouteStrikeRateData($view_report[0], $date_range, $data['memo_structure'], $custom_search);
        //debug($selected_values,1);



        $search_type = $post['search_type'][0];
        $data['view_report'] = ucfirst($view_report[0]);
        if($search_type == 'show')
        {
            return view('reports.routeStrikeRate.route-wise_strike-rate-ajax', $data);
        }
        else if($search_type == 'download')
        {
            $filename='strike-rate-'.Auth::user()->id.'.xlsx';
            $this->export_strike_rate($data,$filename);
            echo $filename;
        }
    }

    public function getRouteStrikeRateData($route_ids,$sku_short_name,$date_range,$view_report,$config)
    {
        $sql = DB::table('routes');
        $sql->select(
            $config['table'].'.'.$config['field_name'].' as field_name',
            'orders.total_outlet',
            'orders.visited_outlet',
            'orders.order_type',
            'orders.total_no_of_memo',
            'orders.order_total_sku',
            'orders.order_total_case',
            'orders.order_da',
            'orders.order_amount',
            'orders.order_status',
            'order_details.short_name',
            'sales.sale_total_case',
            DB::raw('Sum(order_details.case) AS qtotal'),
            DB::raw('Sum(order_details.no_of_memo) AS mtotal'),
            DB::raw('Sum(order_details.case) AS quantitytotal'),
            DB::raw('Sum(order_details.price) AS pricetotal')
        );
        //$sql->leftJoin('orders','orders.route_id','=','routes.id');

        $sql->leftJoin('orders', function ($join) use($date_range) {
            $join->on('orders.route_id', '=', 'routes.id')
                ->whereBetween('orders.order_date',$date_range)
                ->where('orders.order_type', 'Secondary')
                ->whereIN('orders.order_status', ['Edited','Processed']);
        });

        $sql->leftJoin('sales', function ($join) use($date_range) {
            $join->on('sales.order_id', '=', 'orders.id');
            $join->on('sales.sale_route_id','=','orders.route_id')
                ->whereBetween('sales.order_date',$date_range)
                ->where('sales.sale_type', 'Secondary')
                ->whereIN('sales.sale_status', ['Edited','Processed']);
        });

        $sql->leftJoin('order_details','orders.id','=','order_details.orders_id');
        $sql->leftJoin('distribution_houses','distribution_houses.id','=','routes.distribution_houses_id');
        $groupBy = 'orders.order_date'; //if view report date wise
        if(strtolower($view_report) == 'zone')
        {
            $sql->leftJoin('zones','zones.id','=','distribution_houses.zones_id');
            $groupBy = 'distribution_houses.zones_id';
        }
        else if(strtolower($view_report) == 'region')
        {
            $sql->addSelect('zones.zone_name as zname');
            $sql->leftJoin('zones','zones.id','=','distribution_houses.zones_id');
            $sql->leftJoin('regions','regions.id','=','distribution_houses.regions_id');
            $groupBy = 'distribution_houses.regions_id';
        }
        else if(strtolower($view_report) == 'territory')
        {
            $sql->addSelect('zones.zone_name as zname','regions.region_name as rname');
            $sql->leftJoin('zones','zones.id','=','distribution_houses.zones_id');
            $sql->leftJoin('regions','regions.id','=','distribution_houses.regions_id');
            $sql->leftJoin('territories','territories.id','=','distribution_houses.territories_id');
            $groupBy = 'distribution_houses.territories_id';
        }
        else if(strtolower($view_report) == 'house')
        {
            $sql->addSelect('zones.zone_name as zname','regions.region_name as rname','distribution_houses.territory_name as tname');
            $sql->leftJoin('zones','zones.id','=','distribution_houses.zones_id');
            $sql->leftJoin('regions','regions.id','=','distribution_houses.regions_id');
            $sql->leftJoin('territories','territories.id','=','distribution_houses.territories_id');
            $groupBy = 'distribution_houses.id';
        }
        else if(strtolower($view_report) == 'aso')
        {
            $sql->addSelect('zones.zone_name as zname','regions.region_name as rname','distribution_houses.territory_name as tname','distribution_houses.point_name as hname');
            $sql->leftJoin('zones','zones.id','=','distribution_houses.zones_id');
            $sql->leftJoin('regions','regions.id','=','distribution_houses.regions_id');
            $sql->leftJoin('territories','territories.id','=','distribution_houses.territories_id');
            $sql->leftJoin('users','users.id','=','routes.so_aso_user_id');
            $groupBy = 'routes.so_aso_user_id';
        }
        else if(strtolower($view_report) == 'route')
        {
            $sql->addSelect('zones.zone_name as zname','regions.region_name as rname','distribution_houses.territory_name as tname','distribution_houses.point_name as hname');
            $sql->leftJoin('zones','zones.id','=','distribution_houses.zones_id');
            $sql->leftJoin('regions','regions.id','=','distribution_houses.regions_id');
            $sql->leftJoin('territories','territories.id','=','distribution_houses.territories_id');
            $groupBy = 'routes.id';
        }
        $sql->whereIN('routes.id',$route_ids);
        $sql->groupBy('order_details.short_name',$groupBy);
        $result = $sql->get()->toArray();
        //debug($result,1);
        $dataArray = array();
        //$total_outlet = array_sum(array_column($result, 'total_outlet'));
        foreach($result as $k=>$v)
        {
//            if(!isset($dataArray[$v->field_name]))
//            {
//                $dataArray[$v->field_name]['total_outlet'] = 0;
//                $dataArray[$v->field_name]['visited_outlet'] = 0;
//                $dataArray[$v->field_name]['total_no_of_memo']= 0;
//                $dataArray[$v->field_name]['order_da']= 0;
//                $dataArray[$v->field_name]['order_amount']= 0;
//                $dataArray[$v->field_name]['order_total_case']= 0;
//                $dataArray[$v->field_name]['sale_total_case']= 0;
//            }
//            else
//            {
//                $dataArray[$v->field_name]['total_outlet'] += $v->total_outlet;
//                $dataArray[$v->field_name]['visited_outlet'] += $v->visited_outlet;
//                $dataArray[$v->field_name]['total_no_of_memo'] += $v->total_no_of_memo;
//                $dataArray[$v->field_name]['order_da'] += $v->order_da;
//                $dataArray[$v->field_name]['order_amount'] += $v->order_amount;
//                $dataArray[$v->field_name]['order_total_case'] += $v->order_total_case;
//                $dataArray[$v->field_name]['sale_total_case'] += $v->sale_total_case;
//            }
            $dataArray[$v->field_name]['total_outlet'] = $v->total_outlet;
            $dataArray[$v->field_name]['visited_outlet'] = $v->visited_outlet;
            $dataArray[$v->field_name]['total_no_of_memo'] = $v->total_no_of_memo;
            $dataArray[$v->field_name]['order_da'] = $v->order_da;
            $dataArray[$v->field_name]['order_amount'] = $v->order_amount;
            $dataArray[$v->field_name]['order_total_case'] = $v->order_total_case;
            $dataArray[$v->field_name]['sale_total_case'] = $v->sale_total_case;

            if(!isset($dataArray[$v->field_name]['individual_memo']))
            {
                @$dataArray[$v->field_name]['individual_memo'][$v->short_name] = 0;
            }
            else
            {
                @$dataArray[$v->field_name]['individual_memo'][$v->short_name] += $v->mtotal;
            }

            if(!isset($dataArray[$v->field_name]['individual_quantity']))
            {
                @$dataArray[$v->field_name]['individual_quantity'][$v->short_name] = 0;
            }
            else
            {
                @$dataArray[$v->field_name]['individual_quantity'][$v->short_name] += $v->quantitytotal;
            }

            if(!isset($dataArray[$v->field_name]['individual_price']))
            {
                @$dataArray[$v->field_name]['individual_price'][$v->short_name] = 0;
            }
            else
            {
                @$dataArray[$v->field_name]['individual_price'][$v->short_name] += $v->pricetotal;
            }


            $dataArray[$v->field_name]['parents'] = array(
                'zone'=>(isset($v->zname)?$v->zname:''),
                'region'=>(isset($v->rname)?$v->rname:''),
                'territory'=>(isset($v->tname)?$v->tname:''),
                'house'=>(isset($v->hname)?$v->hname:''),
            );

        }
        //debug($dataArray,1);
        return $dataArray;
    }


    public function export_strike_rate($data,$filename) {
        //debug($data['memo_structure'],1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        ExportHelper::get_header_design($number,$row,'Strike Rate',$sheet);
        $additionalRowColumn = array(
            'bottomSkuRow'=>array('Productivity','Avg/Memo','Vol/Memo','Portfolio Vol','Val/Call'),
            'addiColumn'=>array('Target Outlet','Visited Outlet','Visited Outlet%','Successfull Call','Call Productivity','Bounce Call','Additional Sale')
        );
        ExportHelper::get_column_title($number,$row,$data,3,$sheet,$additionalRowColumn);
        $row++;


        foreach($data['grid_data'] as $k=>$v)
        {
            $bounce_call = 0;
            $additional_sale = 0;
            $bounce = ($v['order_total_case']-$v['sale_total_case']);
            if($bounce > 0)
            {
                $bounce_call = (($bounce*100)/$v['order_total_case']);
            }
            else if($bounce < 0)
            {
                $additional_sale = (((-$bounce)*100)/$v['order_total_case']);
            }


            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $k);
            foreach(parrentColumnTitleValue(ucwords($data['view_report']),3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['parents'][$pctv]);
            }

            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, ($v['total_outlet'])?$v['total_outlet']:0);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, ($v['visited_outlet'])?$v['visited_outlet']:0);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['total_outlet'] != 0 ? number_format(($v['visited_outlet'] / $v['total_outlet'])*100, 2) : 0);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, ($v['total_no_of_memo'])?$v['total_no_of_memo']:0);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['visited_outlet'] != 0 ? number_format(($v['total_no_of_memo'] / $v['visited_outlet'])*100,2) : 0);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, number_format($bounce_call,2));
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, number_format($additional_sale,2));

            foreach(getSkuArrayFromMemoStructure($data['memo_structure']) as $sku)
            {
                $indmemo = (isset($v['individual_memo'][$sku])?$v['individual_memo'][$sku]:0);
                $indquantity = (isset($v['individual_quantity'][$sku])?$v['individual_quantity'][$sku]:0);
                $indprice = (isset($v['individual_price'][$sku])?$v['individual_price'][$sku]:0);

                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['total_no_of_memo'] != 0 ? number_format(($indmemo / $v['total_no_of_memo']) * 100,2) : 0);
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['total_no_of_memo'] != 0 ? number_format(($indmemo / $v['total_no_of_memo']),2) : 0);
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $indmemo != 0 ? number_format($indquantity / $indmemo,2) : 0);
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['total_no_of_memo'] != 0 ? number_format($indquantity / $v['total_no_of_memo'],2): 0);
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v['total_no_of_memo'] != 0 ? number_format($indquantity * $indprice / $v['total_no_of_memo'],2) : 0);
            }
            $row++;
        }
        ExportHelper::excelHeader($filename,$spreadsheet);
    }






    /**-----------------------------------------------------**/
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

}
