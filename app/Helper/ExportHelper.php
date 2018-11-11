<?php

namespace App\Helper;
use DB;
use Auth;
//use excelHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportHelper
{
    public function __construct()
    {
        DB::enableQueryLog();
    }

    public static function excelHeader($filename,$spreadsheet)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new xlsx($spreadsheet);
        //$writer->save('php://output');
        $writer->save("./public/export/".$filename);
    }

    public static function get_header_design($number,&$row,$report_name,$sheet)
    {
        $sheet->setCellValue(self::get_letter($number).$row, 'Globe Soft Drinks Ltd.');
        $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A1:J1')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A1:J1')->getFont()->setSize(18);
        $row++;

        $sheet->setCellValue(self::get_letter($number).$row, $report_name);
        $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A2:J2')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A2:J2')->getFont()->setSize(14);

        $row++;
    }





    /*
     *$number = column number Exm. 0=A,1=B,2=C etc
     * $row = Row number
     * $data = array() - all reports data
     * $mergeCells = maximum rowspan in reports header
     * $sheet = spreadsheet object
     * $additionalRowColumn = if show another column between geo map and sku column or if you show another row at top or bottom reports header
     * exm:
     *      - $additionalRowColumn = array(
     *                                  'addiColumn'=>array('Total Outlet','Visited Outlet'),
     *                                  'topRow'=>array('BCP'),
 *                                      'bottomRow'=>array('a','b'));
     */

    public static function get_column_title(&$number,&$row,$data,$mergeCells,$sheet,$additionalRowColumn=array())
    {
        $rowspan = ($mergeCells+$row);
        $sheet->setCellValue(self::get_letter($number).$row, $data['view_report']);
        $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)->getStyle(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        self::geo_map_excel($number,$row,$rowspan,$data,$mergeCells,$sheet);
//        foreach(parrentColumnTitleValue($data['view_report'],$mergeCells)['value'] as $pctv)
//        {
//            $number++;
//            $sheet->setCellValue(self::get_letter($number).$row, ucfirst($pctv));
//            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)->getStyle(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)
//                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//        }

        $lastColumnRow = $row;
        if(isset($additionalRowColumn['addiColumn']))
        {
            foreach($additionalRowColumn['addiColumn'] as $addColumnName)
            {
                $number++;
                $sheet->setCellValue(self::get_letter($number).$row, $addColumnName);
                $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)->getStyle(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
        $cat_number = $number+1;
        foreach($data['memo_structure'] as $category_key=>$category_value)
        {
            $col = (array_sum(array_map("count", $category_value)) * $data['level']);
            $colspan = ($col+$cat_number);
            $sheet->setCellValue(self::get_letter($cat_number).$row, $category_key);
            $sheet->mergeCells(self::get_letter($cat_number).$row.':'.self::get_letter($colspan-1).$row)->getStyle(self::get_letter($cat_number).$row.':'.self::get_letter($colspan-1).$row)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $cat_number = $cat_number+$col;
        }

        $row++;
        $brand_number = $number+1;
        foreach($data['memo_structure'] as $category_key=>$category_value)
        {
            foreach($category_value as $brand_key=>$brand_value)
            {
                $col = (count($brand_value) * $data['level']);
                $colspan = ($col+$brand_number);
                $sheet->setCellValue(self::get_letter($brand_number).$row, $brand_key);
                $sheet->mergeCells(self::get_letter($brand_number).$row.':'.self::get_letter($colspan-1).$row)->getStyle(self::get_letter($brand_number).$row.':'.self::get_letter($colspan-1).$row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $brand_number = $brand_number+$col;
            }
        }


        $row++;
        $sku_number = $number+1;
        foreach($data['memo_structure'] as $category_key=>$category_value)
        {
            foreach($category_value as $brand_key=>$brand_value)
            {
                foreach($brand_value as $sku_key=>$sku_value)
                {
                    $col = $data['level'];
                    $colspan = ($col+$sku_number);
                    $sheet->setCellValue(self::get_letter($sku_number).$row, $sku_value);
                    $sheet->mergeCells(self::get_letter($sku_number).$row.':'.self::get_letter($colspan-1).$row)->getStyle(self::get_letter($sku_number).$row.':'.self::get_letter($colspan-1).$row)
                        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sku_number = $sku_number+$col;
                }
            }
        }

        if(isset($additionalRowColumn['bottomSkuRow']))
        {
            $row++;
            $sku_number = $number+1;
            foreach($data['memo_structure'] as $category_key=>$category_value)
            {
                foreach($category_value as $brand_key=>$brand_value)
                {
                    foreach($brand_value as $sku_key=>$sku_value)
                    {
                        foreach($additionalRowColumn['bottomSkuRow'] as $arckey=>$arcval)
                        {
                            $sheet->setCellValue(self::get_letter($sku_number).$row, $arcval);
                            $sku_number++;
                        }
                    }
                }
            }
        }
        $number = $sku_number;


        if(isset($additionalRowColumn['lastAddiColumn']))
        {
            foreach($additionalRowColumn['lastAddiColumn'] as $addColumnName)
            {
                $sheet->setCellValue(self::get_letter($number).$lastColumnRow, $addColumnName);
                $sheet->mergeCells(self::get_letter($number).$lastColumnRow.':'.self::get_letter($number).$rowspan)->getStyle(self::get_letter($number).$lastColumnRow.':'.self::get_letter($number).$rowspan)
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $number++;
            }
        }



        $row = $row++;
    }

    public static function geo_map_excel(&$number,&$row,&$rowspan,$data,$mergeCells,$sheet)
    {
        foreach(parrentColumnTitleValue($data['view_report'],$mergeCells)['value'] as $pctv)
        {
            $number++;
            $sheet->setCellValue(self::get_letter($number).$row, ucfirst($pctv));
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)->getStyle(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }
    }

    public static function get_letter($numeric_value)
    {
        $letter = array(
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
            "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
            "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
            "CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
            "DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
            "EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ",
            "FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL","FM","FN","FO","FP","FQ","FR","FS","FT","FU","FV","FW","FX","FY","FZ",
            "GA","GB","GC","GD","GE","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ",
            "HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV","HW","HX","HY","HZ"
        );
        return $letter[$numeric_value];
    }

}
