<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Chart extends Model
{
    public static function focusedProductContribution($ids, $short_name, $year)
    {
//        $short_name_result = DB::table('skues')->select('short_name')->where('brands_id',$brand_id)->get()->toArray();
//        $short_name_result = array_column($short_name_result,'short_name');
        $house_ids = getHouseFromThisRoutes($ids);


        $result = DB::table('zones')
            ->select(
                'zones.id',
                'zones.zone_name',
                DB::raw('SUM(sale_details.quantity) as tquantity'))
            ->leftJoin('distribution_houses', 'zones.id', '=', 'distribution_houses.zones_id')
            ->leftJoin('sales', function ($join)use($house_ids,$year){
                $join->on('distribution_houses.id', '=', 'sales.dbid')
                    ->where('sales.sale_type','Secondary')
                    ->where('sales.sale_status','Processed')
                    ->whereIn('sales.dbid',$house_ids)
                    ->where(DB::raw('DATE_FORMAT(sales.sale_date,"%Y")'), $year);
            })
            ->leftJoin('sale_details', function ($join) use($short_name){
                $join->on('sales.id', '=', 'sale_details.sales_id')
                    ->whereIn('sale_details.short_name',$short_name);
            })
            ->groupBy('zones.id','zones.zone_name')->get();
        return $result;
    }

    public static function lineProductivityComparison($ids, $year)
    {
        $house_ids = getHouseFromThisRoutes($ids);
        $result = DB::table('sale_details')
            ->select(DB::raw('DATE_FORMAT(sales.sale_date,"%b %Y") as sdate'),DB::raw('sale_details.price*quantity as tq'))
            ->leftJoin('sales', 'sales.id', '=', 'sale_details.sales_id')
            ->where(DB::raw('DATE_FORMAT(sales.sale_date,"%Y")'), $year)
            ->whereIn('sales.dbid',$house_ids)
            ->groupBy(DB::raw('DATE_FORMAT(sales.sale_date,"%Y-%m")'))
            ->get()->toArray();
        return $result;
    }
}
