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

class BrandwisesalesController extends Controller
{
    private $routes;

    public function __construct()
    {
        $this->routes = json_decode(Session::get('routes_list'), true);
        $this->middleware('auth');
        DB::enableQueryLog();
    }

    public function brandWiseSale()
    {
        $data['metaTitle'] = 'Globe | Brand Wise Sale';
        $data['ajaxUrl'] = URL::to('brand-wise-sale-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'brand_wise_sale_ajax';
        $data['header_level'] = 'Brand Wise Sale';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'route', 'year'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 2;
        $data['level_col_data'] = ['Amount', 'Quantity'];
        $data['tweelveMonth'] = tweelveMonth(date('Y'));


        $data['level_col_data'] = ['Target', 'Sale', 'Ach%'];
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Brand Wise Sale'));
        return view('reports.main', $data);
    }


    public function brandWiseSaleSearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('brand-wise-sale-ajax');
        $data['searching_options'] = 'grid.search_elements_all';


        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);


        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);

        $data['tweelveMonth'] = tweelveMonth($post['year'][0]);

        $data['level'] = 2;
        $data['level_col_data'] = ['Amount', 'Quantity'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $aso_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $route_ids = array_key_exists('route_id', $request_data) ? $request_data['route_id'] : [];


        if (count($route_ids) == 0) {
            if ($aso_ids) {
                $selected_route = getRoutesIdFromAsoId($aso_ids);
            } else {
                $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
                $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
                $selected_houses = array_filter($selected_houses);
                $selected_route = array_column(Reports::getRouteInfoHouse($selected_houses), 'id');
            }

        } else {
            $selected_route = $route_ids;
        }

        $data['brand_wise_sale'] = Reports::brand_wise_sale($selected_route, $data['memo_structure'], $post['year'][0]);


        if($request_data['search_type'][0] == 'show')
        {
            return view('reports.brandwisesales.brand_wise_sale_ajax',$data);
        }
        else if($request_data['search_type'][0] == 'download')
        {
            $filename='brand-wise-sale-'.Auth::user()->id.'.xlsx';
            $this->export_brand_wise_sale($data,$filename);
            echo $filename;
        }
    }


    public function export_brand_wise_sale($data,$filename) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        ExportHelper::get_header_design($number,$row,'Brand Wise Sale',$sheet);

        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Brand Name');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Type');
        $k =11;
        foreach($data['tweelveMonth'] as $key=>$val)
        {
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $data['tweelveMonth'][$k]);
            $k--;
        }
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'Total');
        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, 'AVG.');

        $row++;
        foreach($data['brand_wise_sale'] as $brand_key=> $brand_val)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number).$row, $brand_key);
            $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+1))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+1))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $number++;
            $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Amount');
            $sheet->setCellValue(ExportHelper::get_letter($number).($row+1), 'Quantity');

            $val_number = $number;
            $amt_start = '';
            $amt_start = '';
            $qty_start = '';
            $qty_end = '';
            for($i=0; $i < 12; $i++)
            {
                if($i == 1)
                {
                    $amt_start = ExportHelper::get_letter($val_number).$row;
                    $qty_start = ExportHelper::get_letter($val_number).($row+1);
                }

                $val_number++;
                if($brand_val['sdate'] && ($brand_val['sdate'] == ($i+1)))
                {
                    $sheet->setCellValue(ExportHelper::get_letter($val_number).$row, (($brand_val['sdate'] == ($i+1))?$brand_val['ta']:0));
                    $sheet->setCellValue(ExportHelper::get_letter($val_number).($row+1), (($brand_val['sdate'] == ($i+1))?$brand_val['tq']:0));
                }
                else
                {
                    $sheet->setCellValue(ExportHelper::get_letter($val_number).$row, 0);
                    $sheet->setCellValue(ExportHelper::get_letter($val_number).($row+1), 0);
                }

                if($i == 11)
                {
                    $amt_end = ExportHelper::get_letter($val_number).$row;
                    $qty_end = ExportHelper::get_letter($val_number).($row+1);
                }
            }
            $val_number++;
            $sheet->setCellValue(ExportHelper::get_letter($val_number).$row, '=SUM('.$amt_start.':'.$amt_end.')')
                ->getStyle(ExportHelper::get_letter($val_number).$row)->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->setCellValue(ExportHelper::get_letter($val_number).($row+1), '=SUM('.$qty_start.':'.$qty_end.')')
                ->getStyle(ExportHelper::get_letter($val_number).($row+1))->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $val_number++;
            $sheet->setCellValue(ExportHelper::get_letter($val_number).$row, '=AVERAGE('.$amt_start.':'.$amt_end.')')
                ->getStyle(ExportHelper::get_letter($val_number).$row)->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->setCellValue(ExportHelper::get_letter($val_number).($row+1), '=AVERAGE('.$qty_start.':'.$qty_end.')')
                ->getStyle(ExportHelper::get_letter($val_number).($row+1))->getNumberFormat()
                ->setFormatCode('#,##0.00');


            $row = $row+2;
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
