<?php

namespace App\Helper;
use DB;
use Auth;

class TargetHelper
{
    public function __construct()
    {
        DB::enableQueryLog();
    }
    public static function getTargetJsonValue($type,$type_id,$target_month,$array) {
        foreach ($array as $key => $val) {
            if (($val->target_type == $type) && ($val->type_id == $type_id) && ($val->target_month == $target_month)) {
                $data['base'] = json_decode($val->base_value,true);
                $data['target'] = json_decode($val->target_value,true);
                return $data;
            }
        }
        return false;
    }

    public static function totalJsonValue($type,$short_name,$target_month,$array)
    {
        $totalBase = 0;
        $totalTarget = 0;
        foreach ($array as $key => $val) {
            if (($val->target_type == $type) && ($val->target_month == $target_month)) {
                $base = json_decode($val->base_value,true);
                $target = json_decode($val->target_value,true);
                $totalBase = $totalBase+$base[$short_name];
                $totalTarget = $totalTarget+$target[$short_name];
            }
        }
        $result = array('base'=>$totalBase,'target'=>$totalTarget);
        return $result;
    }

    public static function totalExistingJsonValue($type,$short_name,$target_month)
    {
        if($type == 'zones')
        {
            $existingTarget = DB::table('targets')
                ->where('target_type','')
                ->where('target_month','')
                ->where('type_id','')->first();
        }
        else if($type == 'regions')
        {
            $existingTarget = DB::table('targets')
                ->where('target_type','zones')
                ->where('target_month',$target_month)
                ->where('type_id',Auth::user()->zones_id)->first();
        }
        else if($type == 'territories')
        {
            $existingTarget = DB::table('targets')
                ->where('target_type','regions')
                ->where('target_month',$target_month)
                ->where('type_id',Auth::user()->regions_id)->first();
            //debug(DB::getQueryLog(),1);
        }
        else if($type == 'house')
        {
            $existingTarget = DB::table('targets')
                ->where('target_type','territories')
                ->where('target_month',$target_month)
                ->where('type_id',Auth::user()->territories_id)->first();
            //debug(DB::getQueryLog(),1);
        }
        else if($type == 'market')
        {
            $existingTarget = DB::table('targets')
                ->where('target_type','house')
                ->where('target_month',$target_month)
                ->where('type_id',Auth::user()->distribution_house_id)->first();
            //debug(DB::getQueryLog(),1);
        }
        else if($type == 'route')
        {
            $existingTarget = DB::table('targets')
                ->where('target_type','house')
                ->where('target_month',$target_month)
                ->where('type_id',Auth::user()->distribution_house_id)->first();
            //debug(DB::getQueryLog(),1);
        }
        $target = json_decode(@$existingTarget->target_value,true);

        $result = array('target'=>(($target)?$target[$short_name]:0));

        return $result;
    }

    public static function getBaseData($geography,$base_date,$target_type)
    {
        //debug($geography,1);
        $geoid = array_column($geography,'id');
        //debug($geoid,1);
        $field_id = '';
        if($target_type == 'zones')
        {
            $field_id = 'zones_id';
        }
        else if($target_type == 'regions')
        {
            $field_id = 'regions_id';
        }
        else if($target_type == 'territories')
        {
            $field_id = 'territories_id';
        }
        else if($target_type == 'house')
        {
            $field_id = 'id';
        }
        else if($target_type == 'route')
        {
            $field_id = 'id';
        }
        else if($target_type == 'market')
        {
            $field_id = 'zones_id';
        }

        $data = DB::table('sale_details');
        $data->select('sale_details.short_name',DB::raw('Sum(sale_details.`case`) AS sale_total_quantity'));
        if($target_type == 'zones' || $target_type == 'regions' || $target_type == 'territories' || $target_type == 'house')
        {
            $data->addSelect('distribution_houses.'.$field_id.' as field_id');
        }
        else if($target_type == 'route')
        {
            $data->addSelect('routes.'.$field_id.' as field_id');
        }
        else if($target_type == 'market')
        {

        }
        $data->leftJoin('sales','sale_details.sales_id','=','sales.id');
        if($target_type == 'zones' || $target_type == 'regions' || $target_type == 'territories' || $target_type == 'house')
        {
            $data->leftJoin('distribution_houses','sales.dbid','=','distribution_houses.id');
        }
        else if($target_type == 'route')
        {
            $data->leftJoin('routes','sales.sale_route_id','=','routes.id');
        }
        else if($target_type == 'market')
        {

        }

        $data->where('sales.sale_type','Secondary');
        $data->where('sales.sale_status','Processed');
        $data->whereBetween('sales.sale_date',$base_date);
        if($target_type == 'zones' || $target_type == 'regions' || $target_type == 'territories' || $target_type == 'house')
        {
            $data->whereIn('distribution_houses.'.$field_id,$geoid);
            $data->groupBy('sale_details.short_name','distribution_houses.'.$field_id);
        }
        else if($target_type == 'route')
        {
            $data->whereIn('routes.'.$field_id,$geoid);
            $data->groupBy('sale_details.short_name','routes.'.$field_id);
        }
        else if($target_type == 'market')
        {

        }


        $result = $data->get()->toArray();
        //debug($result,1);
        $data = array();
        foreach($result as $val)
        {
            $data[$val->field_id][$val->short_name] = $val->sale_total_quantity;
        }
        //debug($data,1);
        return $data;
        //debug($result,1);
    }
}
