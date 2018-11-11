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

class ReconciliationReport extends Controller {
    private $routes;
    public function __construct(){
        $this->routes= json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
    }

    public function SalesReconciliationList(){
        $data['metaTitle'] = 'Globe | Sales Reconciliation';
        $data['ajaxUrl'] = URL::to('SalesReconciliationAjax/');
        $data['view'] = 'reconciliation.view';
        $data['header_level'] = 'Sales Reconciliation';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory','house', 'category', 'brand', 'sku', 'daterange', 'view-report')); //View Structure
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $data['memo_structure'] = repoStructure();
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Sales Reconciliation'));
        return view('reports.report', $data);
    }

    public function SalesReconciliationAjax(Request $request, $data = []){
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

        $data['position'] = $this->getStringLocation($custom_search['zone'], $custom_search['region'], $custom_search['territory'], $custom_search['house']);
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['grid_data'] = $this->getReconciliationData($view_report[0], $date_range, $data['memo_structure'], $custom_search);
        $data['view_column'] = $view_report[0];

        $search_type = $post['search_type'][0];
        $data['view_report'] = ucfirst($view_report[0]);
        if($search_type == 'show')
        {
            return view('reports.reconciliation.view', $data);
        }
        else if($search_type == 'download')
        {
            $filename='sales-reconciliation-'.Auth::user()->id.'.xlsx';
            $this->export_sales_reconciliation($data,$filename);
            echo $filename;
        }

    }

    public function getReconciliationData($view_report = '', $date_range = [], $memo_structure = [], $custom_search = [], $digdowndata=[]){
        DB::enableQueryLog();
        $sku_list = $this->getSKUList($memo_structure);
        if($view_report == 'zone') {
            $orders_table = DB::table('zones');
            $orders_table->leftJoin('distribution_houses', function($join) use ($date_range){
                $join->on('zones.id', '=', 'distribution_houses.zones_id');
            });
            $orders_table->leftJoin('stock_ocs', function($join) use ($date_range){
                $join->on('distribution_houses.id', '=', 'stock_ocs.house_id')
                    ->whereBetween('stock_ocs.date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('zones.id', $custom_search['zone']);
            }
            $db_field_id = 'zones.id as naming_field_id';
            $db_field = 'zones.zone_name';
            $naming_field = 'zone_name';
            $groupby_field = 'zones.id';
        }
        else if($view_report == 'region'){
            $orders_table = DB::table('regions');
            $orders_table->leftJoin('distribution_houses', function($join) use ($date_range){
                $join->on('regions.id', '=', 'distribution_houses.regions_id');
            });
            $orders_table->leftJoin('stock_ocs', function($join) use ($date_range){
                $join->on('distribution_houses.id', '=', 'stock_ocs.house_id')
                    ->whereBetween('stock_ocs.date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('regions.id', $custom_search['region']);
            }
            $groupby_field = 'regions.id';
            $db_field_id = 'regions.id as naming_field_id';
            $db_field = 'regions.region_name';
            $naming_field = 'region_name';
        }
        else if($view_report == 'territory'){
            $orders_table = DB::table('territories');
            $orders_table->leftJoin('distribution_houses', function($join) use ($date_range){
                $join->on('territories.id', '=', 'distribution_houses.territories_id');
            });
            $orders_table->leftJoin('stock_ocs', function($join) use ($date_range){
                $join->on('distribution_houses.id', '=', 'stock_ocs.house_id')
                    ->whereBetween('stock_ocs.date', $date_range);
            });
            if(!empty($custom_search[$view_report])){
                $orders_table->whereIn('territories.id', $custom_search['territory']);
            }
            $groupby_field = 'territories.id';
            $db_field_id = 'territories.id as naming_field_id';
            $db_field = 'territories.territory_name';
            $naming_field = 'territory_name';
        }
        else if($view_report == 'date'){
            $orders_table = DB::table('stock_ocs');
            if(!empty($digdowndata)){
                $orders_table->leftJoin('distribution_houses', function($join) use ($date_range){
                    $join->on('distribution_houses.id', '=', 'stock_ocs.house_id');
                });
                if($digdowndata['loctype'] == 'zone'){
                    $orders_table->where('distribution_houses.zones_id', $digdowndata['locid']);
                }
                if($digdowndata['loctype'] == 'region'){
                    $orders_table->where('distribution_houses.regions_id', $digdowndata['locid']);
                }
                if($digdowndata['loctype'] == 'territory'){
                    $orders_table->where('distribution_houses.territories_id', $digdowndata['locid']);
                }
                if($digdowndata['loctype'] == 'house'){
                    $orders_table->where('distribution_houses.id', $digdowndata['locid']);
                }
            }
            else
            {
                $orders_table->leftJoin('distribution_houses','distribution_houses.id','=','stock_ocs.house_id');
            }

            $orders_table->whereBetween('stock_ocs.date', $date_range);
            $groupby_field = 'stock_ocs.date';
            $db_field_id = 'stock_ocs.date';
            $db_field = 'stock_ocs.date';
            $naming_field = 'date';
        }
        else{
            // for houses
            $orders_table = DB::table('distribution_houses');
            $orders_table->leftJoin('stock_ocs', function($join) use ($date_range){
                $join->on('distribution_houses.id', '=', 'stock_ocs.house_id')
                    ->whereBetween('stock_ocs.date', $date_range);
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
            $db_field
        );
        foreach ($sku_list as $short_name => $skus){
            $lifting = "SUM(CASE WHEN short_name = '".$short_name."' THEN lifting ELSE 0 END) AS lifting_".$skus['id'];
            $sale = "SUM(CASE WHEN short_name = '".$short_name."' THEN sale ELSE 0 END) AS sale_".$skus['id'];
            $openning = "SUM(CASE WHEN short_name = '".$short_name."' AND date = '".$date_range[0]."' THEN openning ELSE 0 END) AS openning_".$skus['id'];
            $closing = "SUM(CASE WHEN short_name = '".$short_name."' AND date = '".$date_range[1]."' THEN closing ELSE 0 END) AS closing_".$skus['id'];
            $orders_table->addSelect(DB::raw($lifting));
            $orders_table->addSelect(DB::raw($sale));
            $orders_table->addSelect(DB::raw($openning));
            $orders_table->addSelect(DB::raw($closing));
        }
        $orders_table->groupBy($groupby_field);
        $order_data = $orders_table->get();
        //debug(DB::getQueryLog());
//        debug($order_data);
        $grid_data = [];
        foreach ($order_data as $key => $orders){
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
            foreach ($sku_list as $short_name => $skus) {
                $lifting_field = 'lifting_'.$skus['id'];
                $sale_field = 'sale_'.$skus['id'];
                $openning_field = 'openning_'.$skus['id'];
                $closing_field = 'closing_'.$skus['id'];
                $grid_data[$key]['lifting'][] = number_format($orders->$lifting_field / $skus['pack_size'], 2);
                $grid_data[$key]['sale'][] = number_format($orders->$sale_field / $skus['pack_size'], 2);
                $grid_data[$key]['openning'][] = number_format($orders->$openning_field, 2);
                $grid_data[$key]['closing'][] = number_format($orders->$closing_field / $skus['pack_size'], 2);
            }
        }
//        debug($grid_data);
        return $grid_data;
    }

    /**-----------------------------------------------------**/
    public function digdownReconciliationAjax(Request $request){
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
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['position'] = $this->getStringLocation($custom_search['zone'], $custom_search['region'], $custom_search['territory'], $custom_search['house']);
        $data['grid_data'] = $this->getReconciliationData(
            'date',
            [$start_date, $end_date],
            $data['memo_structure'],
            NULL,
            ['loctype'=>$loctype, 'locid'=>$locid]
        );
        $data['view_column'] = 'date';
        return view('reports.reconciliation.digdown', $data);
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


    public function export_sales_reconciliation($data,$filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        $additionalRowColumn = array(
            'addiColumn'=>array('Particulars')
        );

        ExportHelper::get_header_design($number,$row,'Sales Reconciliation',$sheet);
        ExportHelper::get_column_title($number,$row,$data,2,$sheet,$additionalRowColumn);



        $row++;
        foreach($data['grid_data'] as $grids)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number).$row, strip_tags($grids['view_type']));
            $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+3))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+3))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $number++;


            foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number).$row,  $grids[$pctv]);
                $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+3))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+3))
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $number++;
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Lifting');
            $liftingNumber = $number+1;
            foreach($grids['lifting'] as $lifting)
            {
                $sheet->setCellValue(ExportHelper::get_letter($liftingNumber++).$row, $lifting);
            }


            $sheet->setCellValue(ExportHelper::get_letter($number).($row+1), 'Sales');
            $salesNumber = $number+1;
            foreach($grids['sale'] as $sale)
            {
                $sheet->setCellValue(ExportHelper::get_letter($salesNumber++).($row+1), $sale);
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+2), 'Opening');
            $openingNumber = $number+1;
            foreach($grids['openning'] as $openning)
            {
                $sheet->setCellValue(ExportHelper::get_letter($openingNumber++).($row+2), $openning);
            }



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+3), 'Closing');
            $closingNumber = $number+1;
            foreach($grids['closing'] as $closing)
            {
                $sheet->setCellValue(ExportHelper::get_letter($closingNumber++).($row+3), $closing);
            }



            $row = $row+4;
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
