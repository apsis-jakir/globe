<?php

namespace App\Helper;
use App\Models\Routes;
use DB;
use Auth;

class ReportsHelper
{
    public function __construct()
    {
        DB::enableQueryLog();
    }

    public static function order_list_query($type=null,$post,$routes=[])
    {
    
        $houses = Routes::whereIn('id',$routes)->groupBy('distribution_houses_id')->get(['distribution_houses_id'])->toArray();
        $selected_house = array_column($houses,'distribution_houses_id');
        if($post)
        {
            $searchValue = getGeographySearchData($post);
            $dateselect = explode(' - ', $post['created_at'][0]);
        }
        $query = DB::table('orders');
        
        if($type=='secondary')
        {
            $query->select('orders.*','distribution_houses.point_name');
            $query->leftJoin('distribution_houses','distribution_houses.id','=','orders.dbid');
            $query->where('order_type',ucfirst($type));
        }
        else{
            $query->select('orders.*','distribution_houses.current_balance as dhcb','distribution_houses.opening_balance','distribution_houses.point_name','sales.house_current_balance');
            $query->leftJoin('distribution_houses','distribution_houses.id','=','orders.dbid');
            $query->leftJoin('sales', function ($join)  {
                $join->on('sales.asm_rsm_id','=','orders.asm_rsm_id')
                    ->on('sales.order_date','=','orders.order_date')
                    ->where('sales.sale_status','Processed');
            });
            $query->where('order_type',ucfirst($type));
        }

        if($post)
        {
            $query->whereIn('orders.dbid',$searchValue);
            if($post['created_at'])
            {
                $query->where('orders.order_date','>=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[0]))));
                $query->where('orders.order_date','<=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[1]))));
            }
        }
        else
        {
            $query->whereIn('orders.dbid',$selected_house);
        }

        $result = $query->whereNotIn('order_status',['Rejected'])->get();
       // dd($result);
        return $result;
    }


    public static function sales_list_query($type=null,$post)
    {
        if($post)
        {
            $searchValue = getGeographySearchData($post);
            $dateselect = explode(' - ', $post['created_at'][0]);
            //debug($post,1);
        }

        $query = DB::table('sales');
        $query->select('sales.*');
        $query->addSelect('distribution_houses.point_name','distribution_houses.current_balance');
        if($type != 'promotional')
        {
            $query->addSelect('orders.total_outlet','orders.visited_outlet','orders.total_no_of_memo','orders.order_total_sku','orders.order_amount','orders.order_total_case');
            $query->leftJoin('orders','orders.id','=','sales.order_id');
        }
        $query->leftJoin('distribution_houses','distribution_houses.id','=','sales.dbid');

        if(($type == 'primary') || ($type == 'secondary'))
        {
            $query->where('order_type',ucfirst($type));
        }
        else if($type == 'promotional')
        {
            $query->where('sale_type',ucfirst($type));
        }

        if($post)
        {
            $query->whereIn('sales.dbid',$searchValue);
            //debug(ucfirst($type),1);
            if($post['created_at'])
            {
                $query->where('sales.order_date','>=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[0]))));
                $query->where('sales.order_date','<=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[1]))));
            }
        }

        $result = $query->whereNotIn('sale_status',['Rejected'])->get();
        return $result;
    }

    public static function getDistributorCurrentBalance($post)
    {
        $result = DB::table('order_details')->select('short_name','price')->whereIn('short_name',$post['short_name'])->where('orders_id',$post['order_id'])->get();
        $total = 0;
        foreach($result as $k=>$value)
        {
            $total = $total+($post['quantity'][$value->short_name]*$value->price);
        }
        return $total;
    }

    public static function targetsConfigData($view_report)
    {

        $target_type = '';
        $table = '';
        $field_name = '';
        $field_code = '';
        $aso = '';
        if($view_report == 'zone')
        {
            $target_type = 'zones';
            $table = 'zones';
            $field_name = 'zone_name';
            $field_code = 'code';
            $field_id = 'zones_id';
        }
        elseif($view_report == 'region')
        {
            $target_type = 'regions';
            $table = 'regions';
            $field_name = 'region_name';
            $field_code = 'region_code';
            $field_id = 'regions_id';
        }
        elseif($view_report == 'territory')
        {
            $target_type = 'territories';
            $table = 'territories';
            $field_name = 'territory_name';
            $field_code = 'territory_code';
            $field_id = 'territories_id';
        }
        elseif($view_report == 'house')
        {
            $target_type = 'house';
            $table = 'distribution_houses';
            $field_name = 'point_name';
            $field_code = 'code';
            $field_id = 'id';
        }
        elseif($view_report == 'aso')
        {
            $target_type = 'route';
            $table = 'routes';
            $field_name = 'name';
            $field_code = 'code';
            $aso = 'aso';
            $field_id = 'id';
        }
        elseif($view_report == 'route')
        {
            $target_type = 'route';
            $table = 'routes';
            $field_name = 'routes_name';
            $field_code = 'routes_code';
            $field_id = 'id';
        }
        //debug($table,1);
        return array('type'=>$target_type,'table'=>$table,'field_name'=>$field_name,'field_code'=>$field_code,'aso'=>$aso,'field_id'=>$field_id);
    }

    public static function getProcessedOrderSkuSaleQuantity($order_id,$short_name)
    {
        $query = DB::table('sale_details');
        $query->select('sale_details.case');
        $query->leftJoin('sales','sales.id','=','sale_details.sales_id');
        $query->where('sale_details.short_name',$short_name);
        $query->where('sales.order_id',$order_id);
        $result = $query->first();
        if($result)
        {
            return $result->case;
        }
        else
        {
            return 0;
        }
    }
}
