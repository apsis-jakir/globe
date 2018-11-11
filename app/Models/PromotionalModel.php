<?php
/**
 * Created by PhpStorm.
 * User: shabbir
 * Date: 9/30/2018
 * Time: 1:51 PM
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class PromotionalModel
{
    public static function getPromotionalSale($data=[],$filter=false){
        $query = DB::table('sales');
        $query->select('sales.id as sale_id','distribution_houses.point_name','orders.requester_name','orders.requester_phone',
        'sales.order_date','orders.total_outlet','orders.visited_outlet','orders.total_no_of_memo as successful_memo',
        'sales.sale_total_sku','sales.sale_status'
        );
        $query->where('sale_type','Promotional');
        if($filter){
            $selected_value = getGeographySearchData($data);
            $dateselect = explode(' - ', $data['created_at'][0]);
            $query->where('sales.order_date','>=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[0]))));
            $query->where('sales.order_date','<=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[1]))));
        }
        else{
            $selected_value = getGeographySearchData($data);
            $query->where('sales.order_date','>=',date('Y-m-d',strtotime(str_replace('/','-',date('Y-m-d')))));
            $query->where('sales.order_date','<=',date('Y-m-d',strtotime(str_replace('/','-',date('Y-m-d')))));
        }
        $query->leftJoin('orders', function ($join)  {
            $join->on('orders.aso_id', '=', 'sales.aso_id')
                 ->on('orders.order_date', '=', 'sales.order_date')
                ->where('orders.order_status','Processed');
        });
        $query->leftJoin('distribution_houses','distribution_houses.id','=','sales.dbid');
        $query->whereIn('sales.dbid',$selected_value);
        $result = $query->whereNotIn('sale_status',['Rejected'])->get();
        return $result;
    }

    public static function getDetailsById($id){
        $result = DB::table('sales')
                ->select('sales.*','distribution_houses.point_name','distribution_houses.market_name','sale_details.short_name','sale_details.case','sale_details.no_of_memo')
                ->leftJoin('distribution_houses','distribution_houses.id','=','sales.dbid')
                ->leftJoin('sale_details','sale_details.sales_id','=','sales.id')
                ->where('sales.id','=',$id)
                ->get();
        return $result;

    }

    public static function updateSale($id,$quantity,$memo){
        $package_count =0;
        foreach ($quantity as $key=>$value){
            $package_count=$package_count+($value* $memo[$key]);
            SaleDetail::where('sales_id',$id)->where('short_name',$key)->update(['case'=>$value,'no_of_memo'=>$memo[$key]]);
        }
        Sale::where('id',$id)->update(['sale_total_sku'=>$package_count]);

    }

}