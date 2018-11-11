<?php
function debug($dt = null, $true = false)
{
    if (defined('DEBUG_REMOTE_ADDR') && $_SERVER['REMOTE_ADDR'] != DEBUG_REMOTE_ADDR) return;
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $file_line = "<strong>" . $caller['file'] . "(line " . $caller['line'] . ")</strong>\n";
    echo "<br/>";
    print_r($file_line);
    echo "<br/>";
    echo "<pre>";
    print_r($dt);
    echo "</pre>";
    if ($true) {
        die("<b>die();</b>");
    }
}


function generateDataTables($sql = [], $columns = [], $search = [], $data_id_field = '')
{
    $obj = new TargetHelper();
    if (!empty($_REQUEST)) {

        $requestData = $_REQUEST;
        $final_sql = '';

        $main_sql = $sql['sql'];
        $group_by = '';
        $order_by = '';
        // set where condition
        if (isset($sql['sql']) && $sql['sql'] != '') {
            $where = " WHERE {$sql['where']}";
        } else {
            $where = " WHERE 1=1";
        }
        if (isset($sql['group_by']) && $sql['group_by'] != '') {
            $group_by = " GROUP BY {$sql['group_by']}";
        }
        if (isset($sql['order_by']) && $sql['order_by'] != '') {
            $order_by = " ORDER BY {$sql['order_by']}";
        }
        $final_sql = $main_sql . $where . $group_by . $order_by;

        $query = DB::select($final_sql);

        $data = $query;
        $totalData = count($query);
        $totalFiltered = $totalData;

        if (!empty($requestData['search']['value'])) {
            $first = 0;
            foreach ($search as $col) {
                if ($first == 0) {
                    $where .= " AND {$col} LIKE '" . $requestData['search']['value'] . "%' ";
                } else {
                    $where .= "OR {$col} LIKE '" . $requestData['search']['value'] . "%' ";
                }
                $first++;
            }
            $final_sql = $main_sql . $where . $group_by;

            $query = DB::select($final_sql);
            $totalFiltered = count($query);

            $final_sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

            $query = DB::select($final_sql);

        } else {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

            $final_sql = $main_sql . $where . $group_by . $order_by;
            $query = DB::select($final_sql);
        }
        $data = $query;


        $finalData = [];
        foreach ($data as $val) {
            $temp = [];
            foreach ($columns as $col) {
                $temp[] = $val->$col;
            }
            $temp['DT_RowId'] = 'row_' . $val->$data_id_field;
            $temp['DT_RowClass'] = 'rows';
            $finalData[] = $temp;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $finalData
        );
        return $json_data;
    }
}


function getGeographySearchData($post)
{
//    debug($post,1);
    if (isset($post['aso_id'])) {
        return $post['aso_id'];
    } else if (isset($post['id'])) {
        return $post['id'];
    } else if (isset($post['territories_id'])) {
        return $post['territories_id'];
    } else if (isset($post['regions_id'])) {
        return $post['regions_id'];
    } else if (isset($post['zones_id'])) {
        return $post['zones_id'];
    } else {
//        return [];
        return getHouseFromThisRoutes(json_decode(Session::get('routes_list'), true));
    }

}


function getSearchDataAll($post)
{
    $geography = array(
        'zones_id' => $post['zones_id'],
        'regions_id' => $post['regions_id'],
        'territories_id' => $post['territories_id'],
        'id' => $post['id']
    );

    $geography_value = array_filter($geography);

    $dquery = DB::table('distribution_houses')->select('id');
    if ($geography) {
        $dquery->where($geography_value);
    }
    $d_value = $dquery->get();

    $house_id = array();
    foreach ($d_value as $dv) {
        array_push($house_id, $dv->id);
    }


//        $squery = DB::table('skues')->select('skues.short_name');
//        $squery->leftJoin('brands','brands.id','=','skues.brands_id');
//        if($post['category_id'])
//        {
//            $squery->where('brands.categories_id',$post['category_id']);
//        }
//        if($post['brands_id'])
//        {
//            $squery->where('skues.brands_id',$post['brands_id']);
//        }
//        if($post['skues_id'])
//        {
//            $squery->where('skues.id',$post['skues_id']);
//        }
//        $s_value = $squery->get();
//
//        $short_name = array();
//        foreach ($s_value as $sv)
//        {
//            array_push($short_name,$sv->short_name);
//        }
//
//        $searchValue = array('house_id'=>$house_id,'short_name'=>$short_name);
    $searchValue = array('house_id' => $house_id);
    return $searchValue;
}

function breadcrumb($data)
{
    $html = '<ol class="breadcrumb">';
    $html .= '<li class="bredlink"><a href="' . URL::to('home') . '"><i class="fa fa-dashboard"></i> Home</a></li>';
    foreach ($data as $val => $url) {
        if ($val != 'active') {
            $html .= '<li class="bredlink"><a href="' . URL::to($url) . '">' . $val . '</a></li>';
        }
    }
    $html .= '<li class="active">' . $data['active'] . '</li>';
    $html .= '</ol>';
    return $html;
}

if (!function_exists('memoStructure')) {
    function memoStructure($brands = [], $skues = [])
    {
        $result = [];
        $selected_brands = \App\Models\Brand::orderBy('ordering', 'ASC');
        if (count($brands) > 0) {
            $selected_brands->whereIn('id', $brands);
        }
        $selected_brands = $selected_brands->get()->toArray();
        foreach ($selected_brands as $brand) {
            $selected_skues = \App\Models\Skue::orderBy('ordering', 'ASC')->where('brands_id', $brand['id']);
            if (count($skues) > 0) {
                $selected_skues->whereIn('id', $skues);
            }
            $selected_skues = $selected_skues->get()->toArray();
            foreach ($selected_skues as $key => $value) {
                $result[$brand['brand_name']][$value['short_name']] = $value['sku_name'];
            }
        }
        return $result;
    }
}

function previous_calculation($house_id, $previous_value, $total_amount, $stock, &$result_append)
{
    foreach ($previous_value as $key => $value) {

        $present_quantity = \App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $key)->first(['quantity']);
        if (!empty($present_quantity)) {
            $present_quantity = $present_quantity->toArray();
            if (!$stock) {
                //secondary
                $update_quantity = $present_quantity['quantity'] + $value;
                //stock_oc($house_id, $key, date('Y-m-d'), $present_quantity['quantity'],$value,false);
            } else {
                //primary
                $update_quantity = $present_quantity['quantity'] - $value;
                $result_append[$key] = $value;
            }
            \App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $key)->update(['quantity' => $update_quantity]);
        }

    }
    if ($stock) {
        $present_current_balance = \App\Models\DistributionHouse::where('id', $house_id)->first(['current_balance']);
        calculate_house_current_balance($house_id, $present_current_balance['current_balance'], $total_amount, 'plus');
        return true;
    }
    return true;
}

if (!function_exists('stock_update')) {
    function stock_update($house_id, $present_value = [], $total_amount = 0, $previous_value = [], $previous_total = 0,$date, $stock = false)
    {
        if(!empty($previous_value)){
            foreach($previous_value as $k=>$v){ // $k = sku name, $v = quantity in pcs
                if(!$stock)
                    \App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $k)->increment('quantity',$v);
                else
                    \App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $k)->decrement('quantity',$v);
            }
        }
        if(!empty($present_value)){
            foreach($present_value as $k=>$v){ // $k = sku name, $v = quantity in pcs
                if(!$stock){
                    \App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $k)->decrement('quantity',$v);
                    stock_oc($house_id, $k,$date, $v, isset($previous_value[$k]) ? $previous_value[$k] : 0, false);
                }else{
                    \App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $k)->increment('quantity',$v);
                    stock_oc($house_id, $k, $date, $v, isset($previous_value[$k]) ? $previous_value[$k] : 0, true);
                }
            }
        }
        return true;
    }
}

function liftingInsert($house_id, $sku, $date, $present_value)
{
    $get_openning=\App\Models\Stock_oc::where('house_id', $house_id)->where('short_name', $sku)->whereDate('date', '<', $date)->first(['closing']);
    if(is_null($get_openning)){
        $get_openning=\App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $sku)->first(['openning']);
        $openning = $get_openning['openning'];
    }
    else{
        $openning = $get_openning['closing'];
    }
    $data['house_id'] = $house_id;
    $data['short_name'] = $sku;
    $data['openning'] = $openning;
    $data['lifting'] = $present_value;
    $data['closing'] = $openning+$present_value;
    $data['date'] = $date;
    \App\Models\Stock_oc::insert($data);

}

function liftingUpdate($house_id, $sku, $date, $present_value, $old_value,$present)
{
    if($old_value > 0){
        $present['lifting'] = $present['lifting'] - $old_value;
        $present['closing']  = $present['closing'] - $old_value;
    }
    $data['lifting'] = $present['lifting'] + $present_value;
    $data['closing'] = $present['closing'] + $present_value;

    \App\Models\Stock_oc::where('house_id',$house_id)->where('short_name',$sku)->where('date',$date)->update($data);

}

function saleInsert($house_id, $sku, $date, $present_value)
{
    $get_openning=\App\Models\Stock_oc::where('house_id', $house_id)->where('short_name', $sku)->whereDate('date', '<', $date)->first(['closing']);
    if(is_null($get_openning)){
        $get_openning=\App\Models\Stocks::where('distributions_house_id', $house_id)->where('short_name', $sku)->first(['openning']);
        $openning = $get_openning['openning'];
    }
    else{
        $openning = $get_openning['closing'];
    }
    $data['house_id'] = $house_id;
    $data['short_name'] = $sku;
    $data['openning'] = $openning;
    $data['sale'] = $present_value;
    $data['closing'] = $openning-$present_value;
    $data['date'] = $date;
    \App\Models\Stock_oc::insert($data);
}

function saleUpdate($house_id, $sku, $date, $present_value, $old_value,$present)
{
    if($old_value > 0){
        $present['sale'] = $present['sale'] - $old_value;
        $present['closing']  = $present['closing'] + $old_value;
    }
    $data['sale'] = $present['sale'] + $present_value;
    $data['closing'] = $present['closing'] - $present_value;

    \App\Models\Stock_oc::where('house_id',$house_id)->where('short_name',$sku)->where('date',$date)->update($data);

}

if (!function_exists('stock_oc')) {
    function stock_oc($house_id, $sku, $date, $present_quantity, $previous_quantity = 0, $stock = true)
    {
        $get_present = \App\Models\Stock_oc::where('house_id', $house_id)->where('short_name', $sku)->where('date', $date)->first();
        if ($stock && is_null($get_present)) {
            liftingInsert($house_id, $sku, $date, $present_quantity);
        }
        if ($stock && !is_null($get_present)) {
            liftingUpdate($house_id, $sku, $date, $present_quantity, $previous_quantity,$get_present);
        }
        if (!$stock && is_null($get_present)) {
            saleInsert($house_id, $sku, $date, $present_quantity);
        }

        if (!$stock && !is_null($get_present)) {
            saleUpdate($house_id, $sku, $date, $present_quantity, $previous_quantity,$get_present);
        }

    }
}

if (!function_exists('get_module_name')) {
    function get_module_name($module_id = null)
    {
        $user_info = \App\Models\Module::where('id', $module_id)->first();
//            debug($user_info->name,1);
        if ($user_info) {
            return $user_info->name;
        } else {
            return '';
        }
    }
}
if (!function_exists('filter_array')) {
    function filter_array($array)
    {
        $result = array_filter($array, function ($dt) {
            return array_filter($dt);
        });
        return $result;
    }
}


if (!function_exists('repoStructure')) {
    function repoStructure($categories = [], $brands = [], $skues = [])
    {
        $result = [];
        $selected_categories = \App\Models\Category::orderBy('ordering', 'ASC');
        if (count($categories) > 0) {
            $selected_categories->whereIn('id', $categories);
        }
        $selected_categories = $selected_categories->get()->toArray();

        foreach ($selected_categories as $category) {
            $selected_brands = \App\Models\Brand::orderBy('ordering', 'ASC')->where('categories_id', $category['id']);
            if (count($brands) > 0) {
                $selected_brands->whereIn('id', $brands);
            }
            $selected_brands = $selected_brands->get()->toArray();
            foreach ($selected_brands as $brand) {
                $selected_skues = \App\Models\Skue::orderBy('ordering', 'ASC')->where('brands_id', $brand['id']);
                if (count($skues) > 0) {
                    $selected_skues->whereIn('id', $skues);
                }
                $selected_skues = $selected_skues->get()->toArray();
                foreach ($selected_skues as $key => $value) {
                    $result[$category['category_name']][$brand['brand_name']][$value['sku_name']] = $value['short_name'];
                }
            }
        }

        return $result;

    }
}


if (!function_exists('searchAreaOption')) {
    function searchAreaOption($data = array())
    {
        $all_options = array(
            'zone' => 1,
            'region' => 1,
            'territory' => 1,
            'house' => 1,
            'house_single'=>1,
            'aso' => 1,
            'route' => 1,
            'category' => 1,
            'brand' => 1,
            'sku' => 1,
            'month' => 1,
            'daterange' => 1,
            'package' => 1,
            'year' => 1,
            'datepicker' => 1,
            'dss_report_type' => 1,
            'ranking_report' => 1,
            'view-report'=>1,
            'Ordersalemode'=>1
        );
        $all_options=array_intersect_key($all_options,array_flip($data));
        $options = userWiseOptionRemove($all_options);
        $options['show'] = (in_array('show', $data) ? 1 : 0);
        return $options;
    }
}

if (!function_exists('userWiseOptionRemove')) {
    function userWiseOptionRemove($options)
    {
        $user_type = Auth::user()->user_type;
        if ($user_type == 'zone') {
            unset($options['zone']);
        } else if ($user_type == 'region') {
            unset($options['zone']);
            unset($options['region']);
        } else if ($user_type == 'territory') {
            unset($options['zone']);
            unset($options['region']);
            unset($options['territory']);
        } else if ($user_type == 'house') {
            unset($options['zone']);
            unset($options['region']);
            unset($options['territory']);
            unset($options['house']);
            unset($options['house_single']);
        }

        return $options;
    }
}


if (!function_exists('get_info_by_aso')) {
    function get_info_by_aso($id, $type = "market")
    {
        $data = DB::table('users')
            ->select('users.name', 'users.mobile', 'users.distribution_house_id', 'distribution_houses.incharge_name as dhname', 'distribution_houses.incharge_phone as dhphone', 'territories.incharge_name as tsoname', 'territories.incharge_phone as tsophone')
            ->join('territories', 'territories.id', '=', 'users.territories_id')
            ->join('distribution_houses', 'distribution_houses.id', 'users.distribution_house_id')
            ->where('users.user_type', $type)
            ->where('users.id', $id)
            ->first();
        return $data;
    }
}
if (!function_exists('get_primary_order_info_by_asm_rsm')) {
    function get_primary_order_info_by_asm_rsm($id, $date,$type)
    {
        $data = DB::table('orders')
            ->select('orders.*')
            ->where('orders.asm_rsm_id', $id)
            ->where('orders.order_date', $date)
            ->where('orders.order_type', $type)
            ->whereIn('orders.order_status', ['Processed'])
            ->first();
        return $data;
    }
}

if (!function_exists('get_info_by_asm')) {
    function get_info_by_asm($id, $house_id, $type = "territory")
    {
        $data = DB::table('users')
            ->select('users.name', 'users.mobile', 'distribution_houses.id as distribution_house_id')
            ->join('distribution_houses', 'distribution_houses.territories_id', 'users.territories_id')
            ->where('distribution_houses.id', $house_id)
            ->where('users.user_type', $type)
            ->where('users.id', $id)
            ->first();
        return $data;
    }
}

if (!function_exists('get_regular_price_by_sku')) {
    function get_regular_price_by_sku($sku)
    {
        $data = \App\Models\Skue::where('short_name', $sku)->first();
        if (!is_null($data)) {
            $result = $data->toArray();
            return $result['price'];
        }
        return 0;
    }
}

if (!function_exists('get_house_price_by_sku')) {
    function get_house_price_by_sku($sku)
    {
        $data = \App\Models\Skue::where('short_name', $sku)->first();
        if (!is_null($data)) {
            $result = $data->toArray();
            return $result['house_price'];
        }
        return 0;
    }
}

if (!function_exists('get_order_id_by_sale')) {
    function get_order_id_by_sale($aso_id, $order_date, $route_id)
    {
        $data = \App\Models\Order::where('aso_id', $aso_id)->where('order_date', $order_date)->where('route_id', $route_id)->where('order_type', 'Secondary')->whereIn('order_status', ['Processed', 'Edited'])->orderBy('id', 'DESC')->first();
        if (!is_null($data)) {
            $result = $data->toArray();
            return $result;
        }
        return 0;

    }
}

if(!function_exists('get_order_sale_info')){
    function get_order_sale_info($aso_id, $order_date, $route_id){
        $order = \App\Models\Order::where('aso_id', $aso_id)->where('order_date', $order_date)->where('order_type', 'Secondary')->whereIn('order_status', ['Processed', 'Edited'])->orderBy('id', 'DESC')->first();
        $sale = \App\Models\Sale::where('aso_id', $aso_id)->where('order_date', $order_date)->where('sale_type', 'Secondary')->whereIn('sale_status', ['Processed', 'Edited'])->orderBy('id', 'DESC')->first();
        if(!is_null($order) && !is_null($sale)){
            return true;
        }
        else{
            return false;
        }
    }
}
if (!function_exists('promotion_package_merge')) {
    function promotion_package_merge($a1, $a2, $package_qty = 0)
    {
        $sums = array();
        foreach (array_keys($a1 + $a2) as $key) {
            $sums[$key] = (isset($a1[$key]) ? $a1[$key] : 0) + (isset($a2[$key]) ? $a2[$key] : 0);
        }
        if ($package_qty > 0) {
            $sums = array_map(function ($el) use (&$package_qty) {
                return $el * $package_qty;
            }, $sums);
        }
        return $sums;


    }
}

if (!function_exists('get_package_by_name')) {
    function get_package_by_name($package_name)
    {
        $now=date('Y-m-d');
        $package_details = DB::table('promotional_package')
            //->select('promotional_package.package_details', 'promotional_package.package_free_item')
            ->select('promotional_package.id')
            ->where('promotional_package.shortname', $package_name)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
        $skues = [];
//        if (!is_null($package_details)) {
//            $skues['purchase'] = json_decode($package_details->package_details, true);
//            $skues['free'] = json_decode($package_details->package_free_item, true);
        //}
        return $package_details;
    }
}

if (!function_exists('sku_details_generate')) {
    function sku_details_generate()
    {
        $result = [];
        $data = \App\Models\Skue::all();
        foreach ($data as $value) {
            $result[$value->short_name] = [
                'db_price' => $value->house_price,
                'price' => $value->price,
                'size' => $value->pack_size,
                'db_unit_price' => number_format(($value->house_price / $value->pack_size), 2),
                'unit_price' => number_format(($value->price / $value->pack_size), 2)
            ];
        }

        file_put_contents('resources/schemas/sku.json', json_encode($result, JSON_PRETTY_PRINT));
    }
}

if (!function_exists('sku_pack_quantity')) {
    function sku_pack_quantity($sku, $quantity)
    {
        if (empty($sku) || empty($quantity) || $quantity < 0) {
            return 0;
        }
        $quantity = number_format((float)$quantity, 2);
        list($pack, $unit) = strstr($quantity, '.') ? explode('.', $quantity) : [$quantity, 0];
        $path = resource_path() . '/schemas/sku.json';
        $data = \Illuminate\Support\Facades\File::get($path);
        $skues = json_decode($data, true);
        $result = $pack * $skues[$sku]['size'];
        return $result + $unit;

    }
}

if (!function_exists('get_pack_size')) {
    function get_pack_size($sku)
    {
        $path = resource_path() . '/schemas/sku.json';
        $data = \Illuminate\Support\Facades\File::get($path);
        $skues = json_decode($data, true);
        return $skues[$sku]['size'];
    }
}

if (!function_exists('convert_to_case')) {
    function convert_to_case($value, $divider)
    {
        $remainder = fmod($value, $divider);
        $without_remainder = $value - $remainder;
        if ($remainder >= 0) {
            return +($without_remainder / $divider . '.' . sprintf("%02d", abs($remainder)));
        } else {
            return -($without_remainder / $divider . '.' . sprintf("%02d", abs($remainder)));
        }
    }
}

if (!function_exists('convert_to_case_value')) {
    function convert_to_case_value($sku,$value)
    {
        $path = resource_path() . '/schemas/sku.json';
        $data = \Illuminate\Support\Facades\File::get($path);
        $skues = json_decode($data, true);
        $remainder = fmod($value, $skues[$sku]['size']);
        $without_remainder = $value - $remainder;
        if ($remainder >= 0) {
            return +($without_remainder / $skues[$sku]['size'] . '.' . sprintf("%02d", abs($remainder)));
        } else {
            return -($without_remainder / $skues[$sku]['size'] . '.' . sprintf("%02d", abs($remainder)));
        }
    }
}

if (!function_exists('get_sku_price')) {
    function get_sku_price($sku, $house = true)
    {
        $path = resource_path() . '/schemas/sku.json';
        $data = \Illuminate\Support\Facades\File::get($path);
        $skues = json_decode($data, true);
        return $house ? $skues[$sku]['db_unit_price'] : $skues[$sku]['unit_price'];
    }
}

if(!function_exists('get_case_price')){
    function get_case_price($sku,$house=true){
        $path = resource_path() . '/schemas/sku.json';
        $data = \Illuminate\Support\Facades\File::get($path);
        $skues = json_decode($data, true);
        return $house ? $skues[$sku]['db_price'] : $skues[$sku]['price'];
    }
}

if (!function_exists('getUsersRoutes')) {
    function getUsersRoutes($asoSoId)
    {
        $data = DB::table('routes')
            ->where('routes.so_aso_user_id', $asoSoId)
            ->get();

        $routes_name = '';
        foreach ($data as $k => $v) {
            $routes_name .= $v->routes_name . ',';
        }
        return rtrim($routes_name, ',');
    }
}

if (!function_exists('getUsersRoutesId')) {
    function getUsersRoutesId($asoSoId)
    {
        $data = DB::table('routes')
            ->where('routes.so_aso_user_id', $asoSoId)
            ->get();

        $routes_name = '';
        foreach ($data as $k => $v) {
            $routes_name .= $v->id . ',';
        }
        return rtrim($routes_name, ',');
    }
}

if (!function_exists('getRoutesIdFromAsoId')) {
    function getRoutesIdFromAsoId($asoSoId)
    {
        $data = DB::table('routes')
            ->select('routes.id')
            ->whereIn('routes.so_aso_user_id', $asoSoId)
            ->get()->toArray();
        //debug(array_column($data,'id'),1);

        return array_column($data,'id');
    }
}

//if (!function_exists('calculate_case')) {
//    function calculate_case($sku, $number1, $number2, $operation = 'plus')
//    {
//        $path = resource_path() . '/schemas/sku.json';
//        $data = \Illuminate\Support\Facades\File::get($path);
//        $skues = json_decode($data, true);
//        switch ($operation) {
//            case 'plus':
//                $result = sku_pack_quantity($sku, $number1) + sku_pack_quantity($sku, $number2);
//                break;
//            case 'minus':
//                $result = sku_pack_quantity($sku, $number1) - sku_pack_quantity($sku, $number2);
//                break;
//
//        }
//
//        $remainder = fmod($result, $skues[$sku]['size']);
//        $without_remainder =  $result - $remainder;
//        if($remainder >= 0 ){
//            return +($without_remainder / $skues[$sku]['size'] . '.' .sprintf("%02d", abs($remainder)));
//        }
//        else{
//            return -($without_remainder / $skues[$sku]['size'] . '.' .sprintf("%02d", abs($remainder)));
//        }
//
//
//    }
//}
if (!function_exists('calculate_house_current_balance')) {
    function calculate_house_current_balance($house_id, $current_balance, $total_amount, $operation = "plus")
    {
        switch ($operation) {
            case 'plus':
                $update__current_balance = $current_balance + $total_amount;
                \App\Models\DistributionHouse::where('id', $house_id)->update(['current_balance' => $update__current_balance]);
                break;
            case 'minus':
                $update__current_balance = $current_balance - $total_amount;
                \App\Models\DistributionHouse::where('id', $house_id)->update(['current_balance' => $update__current_balance]);
                break;

        }
    }
}

if (!function_exists('availableWorkingDates')) {

    function availableWorkingDates($begin, $end, $exclude_date = [])
    {
        $arr = [];
        $begin = new \DateTime($begin);
        $end = new \DateTime($end);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $arr[] = $date->format("Y-m-d");
        }
        return array_diff($arr, $exclude_date);
    }
}

if (!function_exists('getNameAso')) {
    function getNameAso($id)
    {
        $data = \App\Models\User::where('id', $id)->first(['name']);
        return $data;
    }
}

if (!function_exists('getNameHouse')) {
    function getNameHouse($id)
    {
        $data = \App\Models\DistributionHouse::where('id', $id)->first(['point_name', 'market_name']);
        return $data;
    }
}

if (!function_exists('getNameTerritory')) {
    function getNameTerritory($id)
    {
        $data = \App\Models\Territorie::where('id', $id)->first(['territory_name']);
        return $data;
    }
}

if (!function_exists('getNameRegion')) {
    function getNameRegion($id)
    {
        $data = \App\Models\Region::where('id', $id)->first(['region_name']);
        return $data;
    }
}

if (!function_exists('getNameZone')) {
    function getNameZone($id)
    {
        $data = \App\Models\Zone::where('id', $id)->first(['zone_name']);
        return $data;
    }
}

if (!function_exists('getNameRoute')) {
    function getNameRoute($id)
    {
        $data = \App\Models\Routes::where('id', $id)->first(['routes_name']);
        return $data;
    }
}


function tweelveMonth($year)
{
    $year = $year + 1;
    for ($i = 1; $i <= 12; $i++) {
        $months[] = date("M Y", strtotime(date($year . '-01-01') . " -$i months"));
    }
    return $months;
}


if (!function_exists('getHouseFromThisRoutes')) {
    function getHouseFromThisRoutes($routes)
    {
        //debug($routes,1);
        $data = DB::table('routes')
            ->select('distribution_houses_id')
            ->whereIn('id', $routes)
            ->groupBy('distribution_houses_id')
            ->get()->toArray();
        return array_column($data, 'distribution_houses_id');
//        return $data;
    }
}
if (!function_exists('getSkuArrayFromMemoStructure')) {
    function getSkuArrayFromMemoStructure($memo_structure)
    {
        $sku = array();
        foreach($memo_structure as $category_key=>$category_value)
        {
            foreach($category_value as $brand_key=>$brand_value)
            {
                foreach($brand_value as $sku_key=>$sku_value)
                {
                    array_push($sku,$sku_value);
                }
            }
        }
        return $sku;
    }
}

function asoSumFromRoute($data)
{
    //debug($data,1);
    $uniqueAso = array_unique(array_column($data,'so_aso_user_id'));
    //debug($uniqueAso,1);
    $result = array();
    foreach ($data as $k=>$element) {
        $result[$element->so_aso_user_id][] = json_decode($element->target_value,true);
        $result['name'][$element->so_aso_user_id] = $element->field_name;
    }
    //debug($result,1);
    foreach($uniqueAso as $ua)
    {
        $third['jsonVal'][] = sum_associatve($result[$ua]);
        $third['name'][] = $result['name'][$ua];
        $third['id'][] = $ua;
    }
    //$third=sum_associatve($result[2373]);
    return $third;
}



function sum_associatve($arrays = []){
    $sum = array();
    if($arrays != '')
    {
        foreach ($arrays as $array) {
            if($array)
            {
                foreach ($array as $key => $value) {
                    if (isset($sum[$key])) {
                        $sum[$key] += $value;
                    } else {
                        $sum[$key] = $value;
                    }
                }
            }
        }
    }

    return $sum;
}


if (!function_exists('skuesFromMemoStructure')) {
    function skuesFromMemoStructure($categories = [], $brands = [], $skues = [])
    {
        $result = [];
        $selected_categories = \App\Models\Category::orderBy('ordering', 'ASC');
        if (count($categories) > 0) {
            $selected_categories->whereIn('id', $categories);
        }
        $selected_categories = $selected_categories->get()->toArray();

        foreach ($selected_categories as $category) {
            $selected_brands = \App\Models\Brand::orderBy('ordering', 'ASC')->where('categories_id', $category['id']);
            if (count($brands) > 0) {
                $selected_brands->whereIn('id', $brands);
            }
            $selected_brands = $selected_brands->get()->toArray();
            foreach ($selected_brands as $brand) {
                $selected_skues = \App\Models\Skue::orderBy('ordering', 'ASC')->where('brands_id', $brand['id']);
                if (count($skues) > 0) {
                    $selected_skues->whereIn('id', $skues);
                }
                $selected_skues = $selected_skues->get()->toArray();
                foreach ($selected_skues as $key => $value) {
                    $result[] = $value['short_name'];
                }
            }
        }

        return $result;

    }
}


function get_generated_code($prefix)
{
    $prefix = strtoupper($prefix);

    $default_number = date('Ymd');

    $check = DB::table('generated_ids')
        ->select(DB::raw('count(id) as total'))
        ->where('prefix', $prefix)
        ->where('default_char',$default_number)->first();

    if($check->total)
    {
        $generated_id = DB::table('generated_ids')->insertGetId([
            'prefix'=>$prefix,
            'sequential_id'=>DB::raw("(SELECT MAX(s.sequential_id)+1 FROM generated_ids s WHERE s.prefix='".$prefix."' AND s.default_char=".$default_number.")")
        ]);


        $sq_id = DB::table('generated_ids')
            ->where('id', $generated_id)->first();
        $actual_id = $default_number."-".$sq_id->sequential_id;

        DB::table('generated_ids')
            ->where('id', $generated_id)
            ->update([
                'default_char' => $default_number,
                'actual_id' => $actual_id
            ]);
        return $actual_id;
    }
    else
    {
        DB::table('generated_ids')->insert([
            'prefix' => $prefix,
            'default_char'=>$default_number,
            'sequential_id' => 1000
        ]);
        return get_generated_code($prefix);
    }
}



if (!function_exists('skuidToShortName')) {
    function skuidToShortName($sku_ids)
    {
        $data = DB::table('skues')
            ->select('short_name')
            ->whereIn('id', $sku_ids)
            ->get()->toArray();

        return array_column($data,'short_name');
    }
}


function getSKUList($memo_structure = []){
    $sku_list = [];
    foreach ($memo_structure as $cat_key => $cat_val) {
        $selected_skues = array_flatten($cat_val);
        foreach ($selected_skues as $sku){
            $sku_list[] = $sku;
        }
    }
    $skus = DB::table('skues')
        ->select('id', 'short_name', 'house_price', 'price', 'pack_size')
        ->whereIn('short_name', $sku_list)
        ->get()->toArray();
    $skuinfo = [];
    foreach ($skus as $sku){
        $skuinfo[$sku->short_name]['id'] = $sku->id;
        $skuinfo[$sku->short_name]['house_price'] = $sku->house_price;
        $skuinfo[$sku->short_name]['price'] = $sku->price;
        $skuinfo[$sku->short_name]['pack_size'] = $sku->pack_size;
    }
    $sku_details = [];
    foreach($sku_list as $skues){
        $sku_details[$skues] = $skuinfo[$skues];
    }
    return $sku_details;
}


function getSingleSkuInfo($short_name)
{
    $result = DB::table('skues')
        ->where('short_name', $short_name)
        ->first();
    return $result;
}

?>