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

class LiftingstatementController extends Controller
{
    private $routes;

    public function __construct()
    {
        $this->routes = json_decode(Session::get('routes_list'), true);
        $this->middleware('auth');
        DB::enableQueryLog();
    }

    public function liftingStatement()
    {
        $data['metaTitle'] = 'Globe | Lifting Statement';
        $data['ajaxUrl'] = URL::to('lifting-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'lifting-statement-ajax';
        $data['header_level'] = 'Lifting Statement';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house_single', 'daterange'));
        $data['mendatory'] = searchAreaOption(array('house', 'daterange'));

        $memo = repoStructure();
        $data['memo_structure'] = $memo;

        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Lifting Statement'));
        return view('reports.main', $data);
    }


    public function liftingStatementAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('lifting-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        $post = $request->all();

        unset($post['_token']);
        $request_data = filter_array($post);

        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);


        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);
        $data['daterange'] = $selected_date_range;
        $data['statement'] = Reports::liftingStatementData($selected_houses, $selected_date_range);
        $data['accountStatementHouseInfo'] = Reports::accountStatementHouseInfo($selected_houses, $selected_date_range);

        if($request_data['search_type'][0] == 'show')
        {
            return view('reports.lifting_statement.lifting-statement-ajax', $data);
        }
        else if($request_data['search_type'][0] == 'download')
        {
            $filename='lifting-statement-'.Auth::user()->id.'.xlsx';
            $this->export_lifting_statement($data,$filename);
            echo $filename;
        }
    }


    public function export_lifting_statement($data,$filename) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        ExportHelper::get_header_design($number,$row,'Lifting Statement',$sheet);

        $sheet->setCellValue(ExportHelper::get_letter($number).$row, $data['accountStatementHouseInfo']->point_name .'-'. $data['accountStatementHouseInfo']->code)
            ->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter(10).$row)
            ->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter(10).$row)
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row++;
        $number = 0;
        $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Statement Date : '.$data['daterange'][0])
            ->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter(10).$row)
            ->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter(10).$row)
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row++;
        $number = 0;
        $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Opening Balance : '.$data['accountStatementHouseInfo']->cb)
            ->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter(10).$row)
            ->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter(10).$row)
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row++;
        $number = 0;
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Date');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Deposit Amount');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Lifting Amount');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Balance');

        $row++;

        foreach($data['statement'] as $key=> $val)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $val->sale_date);
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, number_format($val->order_da,2));
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, number_format($val->total_sale_amount,2));
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, number_format($val->house_current_balance,2));
            $row++;
        }
        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
