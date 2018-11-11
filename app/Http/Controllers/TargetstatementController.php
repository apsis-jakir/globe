<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Reports;
use App\Models\Routes;
use App\Models\Stocks;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use reportsHelper;

//for excel library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helper\ExportHelper;

class TargetstatementController extends Controller
{
    private $routes;

    public function __construct()
    {
        $this->routes = json_decode(Session::get('routes_list'), true);
        $this->middleware('auth');
        DB::enableQueryLog();
    }


    public function targetStatement()
    {
        $data['metaTitle'] = 'Globe | Target Statement';
        $data['ajaxUrl'] = URL::to('target-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'target-statement-ajax';
        $data['header_level'] = 'Target Statement';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'route', 'month', 'view-report'));
        $data['mendatory'] = searchAreaOption(array('zone', 'month'));

        $memo = repoStructure();
        $data['memo_structure'] = $memo;

        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Target Statement'));
        return view('reports.main', $data);
    }


    public function targetStatementAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('target-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        $post = $request->all();

        unset($post['_token']);
        $request_data = filter_array($post);
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);

        $data['level'] = 1;
        $data['level_col_data'] = ['Amount', 'Quantity'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $aso_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $asotoroute = getRoutesIdFromAsoId($aso_ids);
        $route_ids = array_key_exists('route_id', $request_data) ? $request_data['route_id'] : [];
        $selected_month = key_exists('month', $request_data) ? $request_data['month'] : [];
        $view_reports = key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $data['view_reports'] = $view_reports[0];
        $selected_values = array('zones' => $zone_ids, 'regions' => $region_ids, 'territories' => $territory_ids, 'house' => $house_ids, 'aso' => $asotoroute, 'route' => $route_ids);

        $target_config = ReportsHelper::targetsConfigData($view_reports[0]);
        $data['config'] = $target_config;

        $data['targetStatement'] = Reports::targetStatement($target_config, $selected_month[0], $selected_values[$target_config['type']]);
        if ($view_reports[0] == 'aso') {
            $data['asoSum'] = asoSumFromRoute($data['targetStatement']);
        }


        $data['view_report'] = ucfirst($view_reports[0]);

        if($request_data['search_type'][0] == 'show')
        {
            return view('reports.target_statement.target-statement-ajax', $data);
        }
        else if($request_data['search_type'][0] == 'download')
        {
            $filename='target-statement-'.Auth::user()->id.'.xlsx';
            $this->export_target_statement($data,$filename);
            echo $filename;
        }
    }


    public function export_target_statement($data,$filename) {
        //debug($data['memo_structure'],1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        ExportHelper::get_header_design($number,$row,'Target Statement',$sheet);

        ExportHelper::get_column_title($number,$row,$data,2,$sheet);

        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Total');

        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row++;

        if(strtolower($data['view_report']) == 'aso')
        {
            foreach($data['asoSum']['name'] as $k=>$v)
            {
                $number = 0;
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v);
                foreach(parrentColumnTitleValue('Aso',3)['value'] as $pctv)
                {
                    $sheet->setCellValue(ExportHelper::get_letter($number++).$row, get_target_map_value($pctv,$data['asoSum']['id'][$k],strtolower($data['view_report'])));
                }
                $start = 0;
                foreach(getSkuArrayFromMemoStructure($data['memo_structure']) as $sku)
                {
                    if($start == 0)
                    {
                        $start_index = ExportHelper::get_letter($number).$row;
                    }
                    $sheet->setCellValue(ExportHelper::get_letter($number++).$row, (!empty($data['asoSum']['jsonVal'][$k]))?$data['asoSum']['jsonVal'][$k][$sku]:0);
                    $start++;
                }
                $end_index = ExportHelper::get_letter($number).$row;
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, '=SUM('.$start_index.':'.$end_index.')');
                $row++;
            }
        }
        else
        {
            foreach($data['targetStatement'] as $k=>$v)
            {
                $number = 0;
                $skueArray = json_decode($v->target_value,true);
                //$total = 0;
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $v->field_name);
                foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
                {
                    $sheet->setCellValue(ExportHelper::get_letter($number++).$row, get_target_map_value($pctv,$v->id,strtolower($data['view_report'])));
                }
                $start = 0;
                foreach(getSkuArrayFromMemoStructure($data['memo_structure']) as $sku)
                {
                    if($start == 0)
                    {
                        $start_index = ExportHelper::get_letter($number).$row;
                    }
                    //$total = $total+(isset($skueArray[$sku]))?$skueArray[$sku]:0;
                    $sheet->setCellValue(ExportHelper::get_letter($number++).$row, ($skueArray != '')?isset($skueArray[$sku])?$skueArray[$sku]:0:0);
                    $start++;
                }
                $end_index = ExportHelper::get_letter($number).$row;
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, '=SUM('.$start_index.':'.$end_index.')');
                $row++;
            }
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
