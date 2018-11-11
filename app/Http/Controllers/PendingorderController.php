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

class PendingorderController extends Controller
{
    private $routes;

    public function __construct()
    {
        $this->routes = json_decode(Session::get('routes_list'), true);
        $this->middleware('auth');
        DB::enableQueryLog();
    }

    public function pendingOrder()
    {
        $data['metaTitle'] = 'Globe | Pending Order';
        $data['ajaxUrl'] = URL::to('pending-order-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'pending-order-ajax';
        $data['header_level'] = 'Pending Order 
                    <span class="changeTitleName"></span>
                    <script>$(document).on("click",".search_unique_submit",function(){var Ordersalemode = $(".Ordersalemode").val();$(".changeTitleName").text(Ordersalemode);});</script>';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'daterange', 'view-report','Ordersalemode'));
        //$data['mendatory'] = searchAreaOption(array('zone', 'month'));

        $memo = repoStructure();
        $data['memo_structure'] = $memo;

        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Pending Order'));
        return view('reports.main', $data);
    }

    public function pendingOrderAjax(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        $selected_houses = array_filter($selected_houses);
        $view_reports = key_exists('view_report', $request_data) ? $request_data['view_report'] : [];

        $data['view_report'] = ucfirst($view_reports[0]);
        $ordersalemode = $request_data['Ordersalemode'][0];
        //debug($data['ordersalemode'],1);
        $data['post_data'] = $post;

        if ($view_reports[0] == 'date') {
            $data['config'] = array('type' => 'date', 'table' => 'orders', 'field_name' => 'order_date');
        } else {
            $data['config'] = ReportsHelper::targetsConfigData($view_reports[0]);
        }
        $data['pending_orders'] = Reports::primary_pending_orders($selected_houses, $selected_date_range, $data['config'],$ordersalemode);
        //debug($data['pending_orders'],1);
        if($request_data['search_type'][0] == 'show')
        {
            return view('reports.pending_order.pending-order-ajax', $data);
        }
        else if($request_data['search_type'][0] == 'download')
        {
            $filename='pending-order-'.Auth::user()->id.'.xlsx';
            $this->export_pending_order($data,$filename);
            echo $filename;
        }
    }

    public function pendingOrderDetailsAjax($field_name,$field_id,$config)
    {
        $data['header_level'] = 'Pending Order Details';
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Pending Order Details'));
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $get = json_decode($config,true);
        $data['pending_orders_details'] = Reports::pending_orders_details($field_name,$field_id,$get);
        return view('reports.pending_order.pending-order-details-ajax', $data);
    }


    public function export_pending_order($data,$filename) {
        //debug($data['view_report'],1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;

        ExportHelper::get_header_design($number,$row,'Pending Order',$sheet);

        ExportHelper::get_column_title($number,$row,$data,2,$sheet);

        $sheet->setCellValue(ExportHelper::get_letter($number).'3', 'Total Case');

        $sheet->mergeCells(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')->getStyle(ExportHelper::get_letter($number).'3:'.ExportHelper::get_letter($number).'5')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $row++;

        foreach($data['pending_orders'] as $key => $info)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $key);

            foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number++).$row, $info['parents'][$pctv]);
            }

            $start = 0;



            foreach($data['memo_structure'] as $category_key=>$category_value)
            {
                foreach($category_value as $brand_key=>$brand_value)
                {
                    foreach($brand_value as $sku_key=>$sku_value)
                    {
                        if($start == 0)
                        {
                            $start_index = ExportHelper::get_letter($number).$row;
                        }
                        $sheet->setCellValue(ExportHelper::get_letter($number++).$row, (isset($info['data'][$sku_value])?$info['data'][$sku_value]:0));
                        $start++;
                    }
                }
            }

            $end_index = ExportHelper::get_letter($number).$row;
            $sheet->setCellValue(ExportHelper::get_letter($number++).$row, '=SUM('.$start_index.':'.$end_index.')');
            $row++;
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }

}
