<?php

namespace App\Http\Controllers;

//use App\Models\Ordering;
use App\Models\DistributionHouse;
use App\Models\Reports;
use App\Models\Routes;
use App\Models\Stocks;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use reportsHelper;
use Symfony\Component\Console\Helper\Helper;
use App\Models\Menu;
use App\Models\User;




//for excel library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helper\ExportHelper;


class CurrentStock extends Controller
{
    private $routes;
    public function __construct()
    {
        $this->routes= json_decode(Session::get('routes_list'),true);
        $this->middleware('auth');
        DB::enableQueryLog();
    }
    public function current_stock(){
//        debug(Auth::user(),1);
        
        $data = $data = DB::table('distribution_houses')->get()->toArray();
        //debug($data,1);


        $data['ajaxUrl'] = URL::to('current-stock-search');
        $data['view'] = 'current_stock_ajax';

        $data['header_level'] = 'Current Stock';
        //$data['view_report'] = 'House';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory','house','category','brand','sku','view-report'));

        $memo = repoStructure();
        $data['memo_structure']= $memo;
        $data['level'] = 1;
        $data['level_col_data'] =[];
        $data['breadcrumb'] = breadcrumb(array('Reports'=>'','active'=>'Current Stock'));
        $data['metaTitle'] = 'Globe | Current Stock';
        return view('reports.main',$data);
    }
    //House Stock Search
    public function currentStockSearch(Request $request){
        $post= $request->all();
        $search_type = $post['search_type'][0];
        unset($post['_token']);
        unset($post['search_type']);
        $request_data = filter_array($post);

        $data['view_report'] = ucwords($post['view_report'][0]);

        //memeo structure
        $categorie_ids =array_key_exists('category_id',$request_data) ? $request_data['category_id'] : [];
        $brand_ids =array_key_exists('brands_id',$request_data) ? $request_data['brands_id'] : [];
        $sku_ids =array_key_exists('skues_id',$request_data) ? $request_data['skues_id'] : [];

        $memo = repoStructure($categorie_ids,$brand_ids,$sku_ids);
        $data['skues'] = skuesFromMemoStructure($categorie_ids,$brand_ids,$sku_ids);
        $data['memo_structure']= $memo;
        //$data['memo_structure1']= $memo;
        $data['level'] = 1;
        $data['level_col_data'] =[];

        //Requested Information
        $zone_ids=array_key_exists('zones_id',$request_data) ? $request_data['zones_id'] : [];
        $region_ids=array_key_exists('regions_id',$request_data) ? $request_data['regions_id'] : [];
        $territory_ids=array_key_exists('territories_id',$request_data) ? $request_data['territories_id'] : [];
        $house_ids=array_key_exists('id',$request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info= Reports::getInfo($zone_ids,$region_ids,$territory_ids,$house_ids);
        $selected_houses = array_unique(array_column($get_info,'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);

        $view_reports = key_exists('view_report',$request_data) ? $request_data['view_report'] : [];
        //dd($asotoroute[0],$route_ids[0]);
		//debug($view_reports,1);
        $data['view_reports'] = $view_reports[0];




        $target_config = ReportsHelper::targetsConfigData($view_reports[0]);
        $data['config'] = $target_config;
       // debug($skues);
        $data['stock_list'] = Reports::getStockInfo($selected_houses,$data['skues'],$data['config']);
        //debug($data['skues'],1);
        $data['SKUList'] = getSKUList($memo);
        //debug($data['SKUList'],1);
        if($search_type == 'show')
        {
            return view('reports.stock.current_stock_ajax',$data);
        }
        else if($search_type == 'download')
        {
            $filename='current-stock-'.Auth::user()->id.'.xlsx';
            $this->export_current_stock($data,$filename);
            echo $filename;
        }

    }


    public function export_current_stock($data,$filename) {
        //debug($data['memo_structure'],1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        ExportHelper::get_header_design($number,$row,'Current Stock',$sheet);
        ExportHelper::get_column_title($number,$row,$data,2,$sheet);

        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Current Balance');
        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row++;

        foreach($data['stock_list'] as $datakey=> $data_value)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $datakey);

            foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $data_value['parents'][$pctv]);
            }

            foreach($data['skues'] as $sku)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  $data_value['data'][$sku]);
            }
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row,  number_format($data_value['cb'],2));
            $row++;
        }

        //$sheet->freezePane( "E5" );

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
