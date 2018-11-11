<?php

namespace App\Http\Controllers;

use App\Models\LiftingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Auth;
use DB;
//for excel library
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helper\ExportHelper;

class Lifting extends Controller
{
    function __construct()
    {
        $this->routes = json_decode(Session::get('routes_list'), true);
    }

    public function getView()
    {
        //debug(Auth::user(),1);
        $data['ajaxUrl'] = URL::to('house-lifting-format-search');
        $data['view'] = 'lifting.view';
        $data['header_level'] = 'Lifting';
        //search Option
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'daterange', 'view-report'));
        //View Structure
        $data['level'] = 1;
        $data['type'] = "House";
        $data['level_col_data'] = ['Requested', 'Delivery'];
        $data['memo_structure'] = [];
        $data['position']="";
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Lifting'));
        return view('reports.report', $data);
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

    public function getLiftingSearch(Request $request)
    {
        $post = $request->all();
        //debug($post,1);
        unset($post['_token']);
        $request_data = filter_array($post);
        //debug($request_data,1);
        $view_report = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $report_type = array_key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        //selected memo
        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);

        $zone = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region=array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory =array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house =array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $data['position'] = $this->getStringLocation($zone,$region,$territory,$house);
        //debug($data['position'],1);

        switch (isset($report_type[0]) ? $report_type[0] : '') {
            case 'zone':
                $selected_houses = array_key_exists('id', $request_data) ? $request_data['id'] : [];
                $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, $report_type[0], $data['memo_structure']);
                //debug($data['lifting_list'],1);
                $data['lifting_list_total']= $data['lifting_list']['total'];
                unset($data['lifting_list']['total']);
                $data['level'] = 1;
                $data['type'] = $report_type[0];
                //$data['position']="";
                //return view('reports.lifting.view', $data);
                break;
            case 'region':
                $selected_houses = array_key_exists('id', $request_data) ? $request_data['id'] : [];
                $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, $report_type[0], $data['memo_structure']);
                $total= $data['lifting_list']['total'];
                unset($data['lifting_list']['total']);
                $data['level'] = 1;
                $data['type'] = $report_type[0];
                //return view('reports.lifting.view', $data);
                break;
            case 'territory':
                $selected_houses = array_key_exists('id', $request_data) ? $request_data['id'] : [];
                $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, $report_type[0], $data['memo_structure']);
                $data['lifting_list_total']= $data['lifting_list']['total'];
                unset($data['lifting_list']['total']);
                $data['level'] = 1;
                $data['type'] = $report_type[0];
                //return view('reports.lifting.view', $data);
                break;
            case 'house':
                $selected_houses = array_key_exists('id', $request_data) ? $request_data['id'] : [];
                $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, $report_type[0], $data['memo_structure']);
                //debug($data['lifting_list'],1);
                $data['lifting_list_total']= $data['lifting_list']['total'];
                unset($data['lifting_list']['total']);
                $data['level'] = 1;
                $data['type'] = $report_type[0];
                //return view('reports.lifting.view', $data);
                break;
            case 'date':
                $selected_houses = array_key_exists('id', $request_data) ? $request_data['id'] : [];
                $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, $report_type[0], $data['memo_structure']);
                $data['lifting_list_total']= $data['lifting_list']['total'];
                unset($data['lifting_list']['total']);
                $data['level'] = 1;
                $data['type'] = $report_type[0];
                //return view('reports.lifting.view', $data);
                break;
            default:
                $selected_houses = getHouseFromThisRoutes($this->routes);
                $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, 'house', $data['memo_structure']);
                $data['lifting_list_total']= $data['lifting_list']['total'];
                unset($data['lifting_list']['total']);
                $data['level'] = 1;
                $data['type'] = 'house';
                //return view('reports.lifting.view', $data);
        }
        //debug($data,1);
        $search_type = $post['search_type'][0];
        $data['view_report'] = ucfirst($view_report[0]);
        if($search_type == 'show')
        {
            return view('reports.lifting.view', $data);
        }
        else if($search_type == 'download')
        {
            $filename='lifting-'.Auth::user()->id.'.xlsx';
            $this->export_lifting($data,$filename);
            echo $filename;
        }

    }

    public function getLiftingDateWise($ids, $type, $selected_date_range, $selected_memo)
    {
        $data['ajaxUrl'] = URL::to('house-lifting-format-search');
        $data['view'] = 'lifting.view';
        $data['header_level'] = 'Lifting';
        
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array());

        $selected_houses = [];
        parse_str($ids, $selected_houses);
        parse_str($selected_date_range, $selected_date_range);
        parse_str($selected_memo, $selected_memo);


        $data['position'] = $this->getStringLocation([],[],[],$selected_houses);

        $data['lifting_list'] = LiftingModel::getLifting($selected_houses, $selected_date_range, 'date', $selected_memo,$link=false);
        //debug($data['lifting_list'],1);
        $data['lifting_list_total']= $data['lifting_list']['total'];
        unset($data['lifting_list']['total']);
        $data['level'] = 1;
        $data['type'] = "House";
        $data['level_col_data'] = ['Requested', 'Delivery'];
        $data['memo_structure'] = $selected_memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Lifting'));
        $data['view_report'] = 'Date';
        
        return view('reports.lifting.lifting_details', $data);
        
        //return view('reports.report', $data);

    }

    public function export_lifting($data,$filename)
    {
        //debug($data,1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $number = 0;
        $row = 1;
        $additionalRowColumn = array(
            'addiColumn'=>array('Lifting Info'),
            'lastAddiColumn'=>array('Lifting Amount','Deposit Amount','Balance')
        );

        ExportHelper::get_header_design($number,$row,'Lifting',$sheet);
        ExportHelper::get_column_title($number,$row,$data,2,$sheet,$additionalRowColumn);



        $row++;
        foreach($data['lifting_list'] as $key=>$grids)
        {
            $number = 0;
            $sheet->setCellValue(ExportHelper::get_letter($number).$row, strip_tags($key));
            $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+1))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+1))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $number++;


            foreach(parrentColumnTitleValue($data['view_report'],3)['value'] as $pctv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($number).$row,  $grids[$pctv]);
                $sheet->mergeCells(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+1))->getStyle(ExportHelper::get_letter($number).$row.':'.ExportHelper::get_letter($number).($row+1))
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $number++;
            }

//
//
            $sheet->setCellValue(ExportHelper::get_letter($number).$row, 'Request');
            $reqNumber = $number+1;
            foreach($grids['req'] as $rk=>$rv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($reqNumber++).$row, $rv);
            }


            $sheet->setCellValue(ExportHelper::get_letter($reqNumber).($row), number_format((($grids['amount'])?$grids['amount']:0),2));
            $sheet->mergeCells(ExportHelper::get_letter($reqNumber).$row.':'.ExportHelper::get_letter($reqNumber).($row+1))
                ->getStyle(ExportHelper::get_letter($reqNumber).$row.':'.ExportHelper::get_letter($reqNumber).($row+1))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $reqNumber++;

            $sheet->setCellValue(ExportHelper::get_letter($reqNumber).($row), number_format((($grids['deposit'])?$grids['deposit']:0),2));
            $sheet->mergeCells(ExportHelper::get_letter($reqNumber).$row.':'.ExportHelper::get_letter($reqNumber).($row+1))
                ->getStyle(ExportHelper::get_letter($reqNumber).$row.':'.ExportHelper::get_letter($reqNumber).($row+1))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $reqNumber++;

            $sheet->setCellValue(ExportHelper::get_letter($reqNumber).($row), number_format((($grids['balance'])?$grids['balance']:0),2));
            $sheet->mergeCells(ExportHelper::get_letter($reqNumber).$row.':'.ExportHelper::get_letter($reqNumber).($row+1))
                ->getStyle(ExportHelper::get_letter($reqNumber).$row.':'.ExportHelper::get_letter($reqNumber).($row+1))
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $reqNumber++;



            $sheet->setCellValue(ExportHelper::get_letter($number).($row+1), 'Sales');
            $delNumber = $number+1;
            foreach($grids['del'] as $dk=>$dv)
            {
                $sheet->setCellValue(ExportHelper::get_letter($delNumber++).($row+1), $dv);
            }


//            $sheet->setCellValue(ExportHelper::get_letter($delNumber++).($row+1), number_format((($grids['amount'])?$grids['amount']:0),2));
//            $sheet->setCellValue(ExportHelper::get_letter($delNumber++).($row+1), number_format((($grids['deposit'])?$grids['deposit']:0),2));
//            $sheet->setCellValue(ExportHelper::get_letter($delNumber++).($row+1), number_format((($grids['balance'])?$grids['balance']:0),2));
//
//
//
//            $sheet->setCellValue(ExportHelper::get_letter($number).($row+2), 'Opening');
//            $openingNumber = $number+1;
//            foreach($grids['openning'] as $openning)
//            {
//                $sheet->setCellValue(ExportHelper::get_letter($openingNumber++).($row+2), $openning);
//            }
//
//
//
//            $sheet->setCellValue(ExportHelper::get_letter($number).($row+3), 'Closing');
//            $closingNumber = $number+1;
//            foreach($grids['closing'] as $closing)
//            {
//                $sheet->setCellValue(ExportHelper::get_letter($closingNumber++).($row+3), $closing);
//            }
//
//
//
            $row = $row+2;
        }

        ExportHelper::excelHeader($filename,$spreadsheet);
    }
}
