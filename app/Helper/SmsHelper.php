<?php
define("REPLACE_TIME",48);
if(!function_exists('getPreviousSale')){
    function getPreviousSale($aso_id,$order_date,$order_type='Secondary'){
        $data=\App\Models\Sale::where('aso_id',$aso_id)
            ->where('order_date',$order_date)
            //->where('sale_route_id',$route_id)
            ->where('sale_type',$order_type)
            ->where('created_at','>',\Carbon\Carbon::now()->subHours(REPLACE_TIME)->toDateTimeString())
            ->where('sale_status','Processed')
            ->get();
        return $data;
    }
}

if(!function_exists('getPreviousOrder')){
    function getPreviousOrder($aso_id,$order_date){
        $data=\App\Models\Order::where('aso_id',$aso_id)
            ->where('order_date',$order_date)
            ->where('order_type','Secondary')
            ->where('created_at','>',Carbon::now()->subHours(REPLACE_TIME)->toDateTimeString())
            ->where('order_status','Processed')
            ->get();
        return $data;
    }
}

if(!function_exists('rejectPreviousOrder')){
    function rejectPreviousOrder($aso_id,$order_date){
        $order=\App\Models\Order::where('aso_id',$aso_id)
            ->where('order_date',$order_date)
            ->where('order_type','Secondary')
            ->where('created_at','>',Carbon::now()->subHours(REPLACE_TIME)->toDateTimeString())
            ->update(['order_status'=>'Rejected']);
        return $order;
    }
}

function getPreviousStockById($id,$type='Secondary'){
    $data =DB::table('sales')
        ->select('sales.id as sid','sales.total_sale_amount as sale_total','sale_details.short_name','sale_details.quantity','orders.order_da as previous_da')
        ->leftJoin('sale_details','sale_details.sales_id','=','sales.id')
        ->leftJoin('orders','orders.id','=','sales.order_id');

    $data=$data->where('sales.id',$id)
        ->where('sales.sale_status','Processed')
        ->orderBy('sales.id','DESC')
        ->get();
    $response = [];
    foreach ($data as $value){
        $response['data'][$value->short_name] = $value->quantity;
    }
    if($type==="Primary") {
        isset($data[0]) ? $response['additional']['sales_id'] = $data[0]->sid : [];
        isset($data[0]) ? $response['additional']['sale_total'] = $data[0]->sale_total : [];
        isset($data[0]) ? $response['additional']['previous_da'] = $data[0]->previous_da : [];
    }
    if($type==="Secondary"){
        isset($data[0]) ? $response['additional']['sales_id'] = $data[0]->sid : [];
    }
    return  $response;
}

function getPreviousStockByAsoDate($aso_id,$order_date,$route_id=0,$dh_id=0,$order_type="Secondary"){
    $data =DB::table('sales')
        ->select('sales.id as sid','sales.total_sale_amount as sale_total','sale_details.short_name','sale_details.quantity','orders.order_da as previous_da')
        ->leftJoin('sale_details','sale_details.sales_id','=','sales.id')
        ->leftJoin('orders','orders.id','=','sales.order_id');
    if($order_type==="Secondary"){
        $data=$data->where('sales.aso_id',$aso_id)
            ->where('sales.dbid',$dh_id);
    }
    if($order_type==="Primary"){
        $data=$data->where('sales.asm_rsm_id',$aso_id)
            ->where('sales.dbid',$dh_id);
    }
    $data=$data ->where('sales.order_date',$order_date)
        ->where('sales.sale_status','Processed')
        ->orderBy('sales.id','DESC')
        ->get();
    $response = [];
    foreach ($data as $value){
        $response['data'][$value->short_name] = $value->quantity;
    }
    if($order_type==="Primary") {
        isset($data[0]) ? $response['additional']['sales_id'] = $data[0]->sid : [];
        isset($data[0]) ? $response['additional']['sale_total'] = $data[0]->sale_total : [];
        isset($data[0]) ? $response['additional']['previous_da'] = $data[0]->previous_da : [];
    }
    if($order_type==="Secondary"){
        isset($data[0]) ? $response['additional']['sales_id'] = $data[0]->sid : [];
    }
    return  $response;
}

if(!function_exists('rejectPreviousSale')){
    function rejectPreviousSale($aso_id,$order_date,&$sale_information,$route_id,$sale_type='Secondary'){
        $result=getPreviousStockByAsoDate($aso_id,$order_date,$route_id);
        $sale_information['previous_data'] = isset($result['data']) ? $result['data'] : [] ;
//        $sale=\App\Models\Sale::where('aso_id',$aso_id)
//            ->where('order_date',$order_date)
//            ->where('sale_type',$sale_type)
//            ->where('sale_route_id',$route_id)
//            ->where('created_at','>',\Carbon\Carbon::now()->subHours(REPLACE_TIME)->toDateTimeString())
//            ->update(['sale_status'=>'Rejected']);
        $sale_information['update']=true;
        return $sale_information;
    }
}
if(!function_exists('rejectPreviousPromotion')){
    function rejectPreviousPromotion($aso_id,$order_date,$route_id){
            $sale=\App\Models\Sale::where('aso_id',$aso_id)
            ->where('order_date',$order_date)
            ->where('sale_type','Promotional')
            ->where('sale_route_id',$route_id)
            ->where('created_at','>',\Carbon\Carbon::now()->subHours(REPLACE_TIME)->toDateTimeString())
            ->update(['sale_status'=>'Rejected']);
    }
}

if(!function_exists('modify_stock')){
    function modify_stock($aso_id,$sku_informations,$previous_data=[],$date){
        $distribution_house_info = DB::table('users')
            ->select('users.distribution_house_id')
            ->where('users.id',$aso_id)
            ->first();
        $updated_data=[];
        $previous_value=[];
        if(count($previous_data) > 0){
            $previous_total = 0;
            foreach ($previous_data as $key=>$val){
                //$unit=\App\Models\Skue::where('short_name',$val['short_name'])->first(['price']);
                $unit_price = (float)get_sku_price($key,false);
                //$quantity = sku_pack_quantity($key,$val);
                $quantity = $val;
                if($quantity > 0){
                    //$unit = $unit->toArray();
                    //$total+= $unit['price'] * $val['quantity'];
                    $previous_total+= $unit_price * $quantity;
                    $previous_value[$key] = $val;
                }
            }
            $total = 0;
            foreach ($sku_informations as $val){
                //$unit=\App\Models\Skue::where('short_name',$val['short_name'])->first(['price']);
                $unit_price = (float)$val['price'];
                //$quantity = sku_pack_quantity($val['short_name'],$val["quantity"]);
                $quantity = $val["quantity"];
                if($quantity > 0){
                    //$unit = $unit->toArray();
                    $total+= $unit_price * $quantity;
                    $updated_data[$val['short_name']] = $val["quantity"];
                }
            }
            return stock_update($distribution_house_info->distribution_house_id,$updated_data,$total,$previous_value,$previous_total,$date);
        }
        else{
            $total = 0;
            foreach ($sku_informations as $val){
                //$unit=\App\Models\Skue::where('short_name',$val['short_name'])->first(['price']);
                $unit_price = (float)$val['price'];
                //$quantity = sku_pack_quantity($val['short_name'],$val["quantity"]);
                $quantity = $val["quantity"];
                if($quantity > 0){
                    //$unit = $unit->toArray();
                    $total+= $unit_price * $quantity;
                    $updated_data[$val['short_name']] = $val["quantity"];
                }
            }
            return stock_update($distribution_house_info->distribution_house_id,$updated_data,$total,[],0,$date);
        }
    }
}

if(!function_exists('get_route_info')){
    function get_route_info($id,$user_id=null){
        $data=DB::table('routes')
            ->select('routes.routes_name')
            ->where('routes.id',$id);
        if(!is_null($user_id)){
            $data= $data->where('routes.so_aso_user_id',$user_id);
        }

        $data= $data ->first();
        return $data;
    }
}


if(!function_exists('sendSms')){
    function sendSms($mobile_number = '8801755557265', $sms_content = ""){
        $sms_send_api_url = urlencode($sms_content);
        $operators = array("017","016","015","019","018");
        $mobile_number = trim($mobile_number);
        if(substr($mobile_number, 0, 3) == '880' ){
            $mobile_number = $mobile_number;
        }else if(in_array(substr($mobile_number, 0, 3),$operators)){
            $mobile_number = "88".$mobile_number;
        }
        //echo $sms_send_api_url;
        if( strlen($sms_send_api_url) < 159 ){
            $payload = file_get_contents("http://app.planetgroupbd.com/api/v3/sendsms/plain?user=apsis786&password=apsis1234&sender=SRParcel&GSM=".$mobile_number."&SMSText=".$sms_send_api_url);
        }else{
            $payload = file_get_contents("http://app.planetgroupbd.com/api/v3/sendsms/plain?user=apsis786&password=apsis1234&type=longSMS&sender=SRParcel&GSM=".$mobile_number."&SMSText=".$sms_send_api_url);
        }
        return $payload;
    }

}