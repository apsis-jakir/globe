<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class Reports extends Model
{
    //constructor


    public static function getInfo($zone_ids = [], $region_ids = [], $territory_ids = [], $house_ids = [], $category_ids = [], $brand_ids = [], $sku_ids = [])
    {
        $query = User::query();
        if (count($zone_ids) > 0) {
            $query = $query->whereIn('zones_id', $zone_ids);
        }
        if (count($region_ids) > 0) {
            $query = $query->whereIn('regions_id', $region_ids);
        }
        if (count($territory_ids) > 0) {
            $query = $query->whereIn('territories_id', $territory_ids);
        }
        if (count($house_ids) > 0) {
            $query = $query->whereIn('distribution_house_id', $house_ids);
        }
        $result = $query->get()->toArray();
        return $result;
    }
    public static function dbStockHouse($fieldName,$id)
    {
        if($fieldName == 'zones_id')
        {
            $info= Reports::getInfo((array)$id,[],[],[]);
        }
        else if($fieldName == 'regions_id')
        {
            $info= Reports::getInfo([],(array)$id,[],[]);
        }
        else if($fieldName == 'territories_id')
        {
            $info= Reports::getInfo([],[],(array)$id,[]);
        }
        else if($fieldName == 'id')
        {
            $info= Reports::getInfo([],[],[],(array)$id);
        }
        $houses = array_unique(array_column($info,'distribution_house_id'), SORT_REGULAR);
        $houses = array_filter($houses);
        return $houses;
        //debug($houses,1);
    }
    //House Stock list
    public static function getHouseStockInfo($view_report_ids, $selected_memo)
    {
        //debug($view_report_ids,1);
        $stock_list = [];
        foreach ($view_report_ids['ids'] as $rkey => $rvalue) {
            $hquery = DB::table($view_report_ids['table']);
            $hquery->select($view_report_ids['table'].'.'.$view_report_ids['view_report_field_name'].' as view_report_field_name', DB::raw('sum(distribution_houses.current_balance) as cb'));
            if($view_report_ids['table'] != 'distribution_houses')
            {
                $hquery->leftJoin('distribution_houses', 'distribution_houses.'.$view_report_ids['db_join_field_name'], '=', $view_report_ids['table'].'.id');
            }
            $hquery->where($view_report_ids['table'].'.id', $rvalue);
            $stock_show_info = (array)$hquery->first();

            //$house = DistributionHouse::where('id', $rvalue)->first()->toArray();
            //dd($stock_show_info,$house);
            $sku_quantity = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skue = array_values($cat_val);
                $selected_skues = array_flatten($selected_skue);
                foreach ($selected_skues as $key => $value) {
                    $dquery = Stocks::select(DB::raw('sum(quantity) as squantity'));
                    //$dquery->whereIn('distributions_house_id', $view_report_ids['selected_house']);
                    $dquery->whereIn('distributions_house_id', Reports::dbStockHouse($view_report_ids['db_join_field_name'],$rvalue));
                    $dquery->where('short_name', $value);
                    $data = $dquery->first();
                    if (!empty($data)) {
                        $sku_quantity[] = $data['squantity'];
                    } else {
                        $sku_quantity[] = 0.0;
                    }

                }
            }

            $stock_list[$stock_show_info['view_report_field_name']]['data'] = $sku_quantity;
            $stock_list[$stock_show_info['view_report_field_name']]['view_report_id'] = $rvalue;
            $stock_list[$stock_show_info['view_report_field_name']]['current_balance'] = $stock_show_info['cb'];
            $stock_list[$stock_show_info['view_report_field_name']]['table_name'] = $view_report_ids['table'];
            $stock_list[$stock_show_info['view_report_field_name']]['field_name'] = $view_report_ids['db_join_field_name'];

        }
        return $stock_list;
    }




    public static function getStockInfo($ids, $skues, $config)
    {
        //debug($config,1);
        $ordering = array();
        $query = $data = DB::table('distribution_houses');
        $query->select(
                $config['table'].'.id as field_id',
                $config['table'].'.'.$config['field_name'].' as field_name',
                'stocks.short_name',DB::raw('sum(stocks.quantity) as squantity'),
                DB::raw('sum(distribution_houses.current_balance) as cb'));

        if($config['type'] == 'regions')
        {
            $query->addSelect('zones.zone_name as zname');
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
            array_push($ordering,'zones.ordering');
        }

        if($config['type'] == 'territories')
        {
            $query->addSelect('zones.zone_name as zname','regions.region_name as rname');
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
            $query->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id');
            array_push($ordering,'zones.ordering');
            array_push($ordering,'regions.ordering');
        }

        if($config['type'] == 'house')
        {
            $query->addSelect('zones.zone_name as zname','regions.region_name as rname','territories.territory_name as tname');
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
            $query->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id');
            $query->leftJoin('territories', 'territories.id', '=', 'distribution_houses.territories_id');
            array_push($ordering,'zones.ordering');
            array_push($ordering,'regions.ordering');
            array_push($ordering,'territories.ordering');
        }

        $query->leftJoin('stocks', 'stocks.distributions_house_id', '=', 'distribution_houses.id');

        if($config['type'] != 'house')
        {
            $query->leftJoin($config['table'], $config['table'].'.id', '=', 'distribution_houses.'.$config['table'].'_id');
        }

        $query->whereIn('stocks.short_name', $skues);
        $query->whereIn('distribution_houses.id', $ids);
        $query->groupBy('stocks.short_name',$config['table'].'.id');
        //$query->orderBy('distribution_houses.zones_id','distribution_houses.regions_id','distribution_houses.territories_id','distribution_houses.id');

        foreach($ordering as $ord)
        {
            $query->orderBy($ord,'ASC');
        }

        $result = $query->get();

        //debug($result,1);

        $dataArray = array();
        foreach($result as $key=>$val)
        {
            $dataArray[$val->field_name]['table_id'] = $val->field_id;
            $dataArray[$val->field_name]['cb'] = $val->cb;
            $dataArray[$val->field_name]['parents'] = array(
                                                        'zone'=>(isset($val->zname)?$val->zname:''),
                                                        'region'=>(isset($val->rname)?$val->rname:''),
                                                        'territory'=>(isset($val->tname)?$val->tname:''),
                                                    );
            $dataArray[$val->field_name]['data'][$val->short_name] = $val->squantity;
        }
        //debug($dataArray,1);
        return $dataArray;
    }





    public static function getMonthlySaleReconciliation($selected_houses, $selected_memo, $selected_date_range)
    {
        $response = [];
        foreach ($selected_houses as $house_id) {
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    $response_data = $data = DB::table(DB::raw('stock_ocs'))
                        ->select('distribution_houses.point_name', 'stock_ocs.openning', 'stock_ocs.lifting', 'stock_ocs.sale', 'stock_ocs.closing')
                        ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'stock_ocs.house_id')
                        ->where('stock_ocs.house_id', $house_id)
                        ->whereBetween('stock_ocs.date', array_map('trim', explode(" - ", $selected_date_range[0])))
                        ->where('stock_ocs.short_name', $value)
                        ->orderBy('stock_ocs.house_id')
                        ->get();
                    if (!$response_data->isEmpty()) {
                        $response_data = collect($response_data)->toArray();
                        $first_data = reset($response_data);
                        $response[$first_data->point_name][$value]['openning'] = $first_data->openning;
                        $lifting = 0;
                        foreach ($response_data as $key => $val) {
                            $response[$val->point_name][$value]['lifting'] = calculate_case($value, isset($response[$val->point_name]['lifting']) ? $response[$val->point_name]['lifting'] : 0, $val->lifting, 'plus');
                            $response[$val->point_name][$value]['sale'] = calculate_case($value, isset($response[$val->point_name]['sale']) ? $response[$val->point_name]['sale'] : 0, $val->sale, 'plus');

                        }
                        $last_data = end($response_data);
                        $response[$first_data->point_name][$value]['closing'] = $last_data->closing;
                    }

                }
            }
        }

        return $response;


//        return [
//           "tp"=>[
//                "opening_stock"=> 1,
//                "lifting"      => 2,
//                "sales"        => 3,
//                "closing"      => 100
//           ]
//        ];

    }

    public static function getRouteInfoByHouse($house_ids)
    {
        $data = User::where('user_type', 'market')->whereIn('distribution_house_id', $house_ids)->get()->toArray();
        return $data;
    }

    public static function getRouteInfoHouse($house_ids)
    {
        $data = Routes::select('routes.*', 'users.id as uid', 'users.name as uname', 'distribution_houses.point_name')
            ->leftJoin('users', 'users.id', '=', 'routes.so_aso_user_id')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id')
            ->where('users.user_type', 'market')->whereIn('routes.distribution_houses_id', $house_ids)->get()->toArray();
        return $data;
    }

    public static function getAsoInfoByIds($aso_ids)
    {
        $data = User::where('user_type', 'market')->whereIn('id', $aso_ids)->get()->toArray();
        return $data;
    }

    public static function getRouteInfoByAso($aso_ids)
    {
        $data = Routes::whereIn('so_aso_user_id', $aso_ids)->get(['id'])->toArray();
        return $data;
    }


//    public static function getRouteInfoAso($route_ids)
//    {
//        $data = Routes::select('routes.*', 'users.id as uid', 'users.name as uname', 'distribution_houses.point_name')
//            ->leftJoin('users', 'users.id', '=', 'routes.so_aso_user_id')
//            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id')->where('users.user_type', 'market')->whereIn('routes.id', $route_ids)->get()->toArray();
//    }
    public static function getRouteInfoAso($route_ids)
    {
        //debug($route_ids,1);
        $data = Routes::select('routes.*', 'users.id as uid', 'users.name as uname', 'distribution_houses.point_name')
            ->leftJoin('users', 'users.id', '=', 'routes.so_aso_user_id')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id')
            ->where('users.user_type', 'market')->whereIn('routes.id', $route_ids)->get()->toArray();
        return $data;
    }

    public static function getHouseInfo($house_ids)
    {
        $data = Routes::select('routes.*', 'users.id as uid', 'users.name as uname', 'distribution_houses.point_name')
            ->leftJoin('users', 'users.id', '=', 'routes.so_aso_user_id')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id')
            ->where('users.user_type', 'market')->whereIn('routes.distribution_houses_id', $house_ids)
            ->groupBy('routes.distribution_houses_id')->get()->toArray();
        return $data;
    }

    private static function getLifting($house_id,$selected_date_range)
    {
        $data = $data = DB::table('skues')
            ->select('orders.order_da', 'sales.house_current_balance', 'sales.total_sale_amount', 'brands.brand_name', 'skues.sku_name', 'skues.short_name', 'order_details.quantity as oquantity', 'sale_details.quantity as salequantity')
            ->leftJoin('order_details', 'order_details.short_name', '=', 'skues.short_name')
            ->leftJoin('orders', function ($join) {
                $join->on('orders.id', '=', 'order_details.orders_id')
                    ->where('orders.order_status', 'Processed')
                    ->where('orders.order_type', 'Primary');
            })
            ->leftJoin('sales', function ($join) {
                $join->on('sales.order_id', '=', 'orders.id')
                    ->on('sales.order_date', '=', 'orders.order_date')
                    ->where('sales.sale_status', 'Processed');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
            ->where('orders.dbid', $house_id)
            ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->orderBy('orders.id', 'DESC')->get()->toArray();
        $response = [];
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $response[$value->short_name]['order_da'] = isset($response[$value->short_name]['order_da']) ? $response[$value->short_name]['order_da'] + $value->order_da : $value->order_da;
                $response[$value->short_name]['house_current_balance'] = isset($response[$value->short_name]['house_current_balance']) ? $response[$value->short_name]['house_current_balance'] + $value->house_current_balance : $value->house_current_balance;
                $response[$value->short_name]['total_sale_amount'] = isset($response[$value->short_name]['total_sale_amount']) ? $response[$value->short_name]['total_sale_amount'] + $value->total_sale_amount : $value->total_sale_amount;
                $response[$value->short_name]['brand_name'] = isset($response[$value->short_name]['brand_name']) ? $response[$value->short_name]['brand_name'] : $value->brand_name;
                $response[$value->short_name]['sku_name'] = isset($response[$value->short_name]['sku_name']) ? $response[$value->short_name]['sku_name'] : $value->sku_name;
                $response[$value->short_name]['short_name'] = isset($response[$value->short_name]['short_name']) ? $response[$value->short_name]['short_name'] : $value->short_name;
                $response[$value->short_name]['oquantity'] = isset($response[$value->short_name]['oquantity']) ?
                    calculate_case($value->short_name, $response[$value->short_name]['oquantity'], $value->oquantity, 'plus') : $value->oquantity;
                $response[$value->short_name]['salequantity'] = isset($response[$value->short_name]['salequantity']) ?
                    calculate_case($value->short_name, $response[$value->short_name]['salequantity'], $value->salequantity, 'plus') : $value->salequantity;
            }
        }
        return $response;
    }

    public static function getHouseLifting($ids, $selected_memo, $selected_date_range)
    {
        $house_lifting_list = [];
        foreach ($ids as $house_key => $house_value) {
            $house = DistributionHouse::where('id', $house_value)->first()->toArray();
            $sku_order_info = [];
            $data_result = self::getLifting($house_value,$selected_date_range);
//            $json = json_encode($data);
//            $data_result = json_decode($json, true);
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    if (count($data_result) > 0) {
                        $index = array_search($value, array_column($data_result, 'short_name'));
                        if ($index !== false) {
                            $sku_order_info['data'][] = [
                                !is_null($data_result[$value]['oquantity']) ? $data_result[$value]['oquantity'] : "N/R",
                                !is_null($data_result[$value]['salequantity']) ? $data_result[$value]['salequantity'] : "N/P"
                            ];
                            $sku_order_info['additional'] = [
                                'sale_amount' => isset($data_result[$value]['total_sale_amount']) ? $data_result[$value]['total_sale_amount'] : 0,
                                'deposit_amount' => isset($data_result[$value]['order_da']) ? $data_result[$value]['order_da'] : 0,
                                'current_balance' => isset($data_result[$value]['house_current_balance']) ? $data_result[$value]['house_current_balance'] : 0,
                            ];


                        } else {
                            $sku_order_info['data'][] = [
                                "N/R",
                                "N/P"
                            ];

                        }
                    } else {
                        $sku_order_info['data'][] = [
                            "N/R",
                            "N/P"
                        ];
                        $sku_order_info['additional'] = [
                            'sale_amount' => 0,
                            'deposit_amount' => 0,
                            'current_balance' => 0,
                        ];
                    }

                }
            }
            $house_lifting_list[$house['point_name']] = $sku_order_info;
//            $additional_info = reset($data_result);
//            $house_lifting_list[$house['point_name']]['additional']['sale_amount'] = $additional_info['total_sale_amount'];
//            $house_lifting_list[$house['point_name']]['additional']['deposit_amount'] = $additional_info['order_da'];
            //$house_lifting_list[$house['point_name']]['additional']['current_balance'] = $additional_info['house_current_balance'];

        }
        return $house_lifting_list;
    }

    public static function getHouseLiftingFormat($ids, $selected_memo,$date_range)
    {
        $house_lifting_list = [];
        foreach ($ids as $house_key => $house_value) {
            $house = DistributionHouse::where('id', $house_value)->first()->toArray();
            $sku_order_info = [];
            $data_result = self::getLifting($house_value,$date_range);
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    if(count($data_result) > 0){
                        $index = array_search($value, array_column($data_result, 'short_name'));
                        if ($index !== false) {
                            $sku_order_info['data']['Request'][] = !is_null($data_result[$value]['oquantity']) ? $data_result[$value]['oquantity'] : "N/R";
                            $sku_order_info['data']['Delivery'][] = !is_null($data_result[$value]['salequantity']) ? $data_result[$value]['salequantity'] : "N/P";
                            $sku_order_info['additional'] = [
                                'sale_amount' => isset($data_result[$value]['total_sale_amount']) ? $data_result[$value]['total_sale_amount'] : 0,
                                'deposit_amount' => isset($data_result[$value]['order_da']) ? $data_result[$value]['order_da'] : 0,
                                'current_balance' => isset($data_result[$value]['house_current_balance']) ? $data_result[$value]['house_current_balance'] : 0,
                            ];
                        } else {
                            $sku_order_info['data']['Request'][] = "N/R";
                            $sku_order_info['data']['Delivery'][] = "N/P";

                        }
                    }
                    else{
                        $sku_order_info['data']['Request'][] = "N/R";
                        $sku_order_info['data']['Delivery'][] = "N/P";
                        $sku_order_info['additional'] = [
                            'sale_amount' => 0,
                            'deposit_amount' => 0,
                            'current_balance' => 0,
                        ];
                    }



                }
            }
            $house_lifting_list[$house['point_name']] = $sku_order_info;
        }
        return $house_lifting_list;
    }

    private static function getAsoTargetByRoutes($ids, $target_month)
    {
        $data = Target::where('target_type', 'route')->whereIn('type_id', $ids)->where('target_month', $target_month)->get(['target_value']);
        if (!$data->isEmpty()) {
            return $data->toArray();
        } else {
            return [];
        }
    }

    private static function getDss($id, $date, $type)
    {
        $data = DB::table('skues')
            ->select('skues.short_name', 'orders.total_outlet', 'orders.visited_outlet', 'orders.total_no_of_memo', 'order_details.quantity as order_quantity', 'sale_details.quantity as sale_quantity');
        if ($type == 'aso') {
            $data = $data->leftJoin('orders', function ($join) use ($id) {
                $join->where('orders.aso_id', '=', $id);
            });
        } else {
            $data = $data->leftJoin('orders', function ($join) use ($id) {
                $join->where('orders.route_id', '=', $id);
            });
        }
        $data = $data->leftJoin('order_details', function ($join) {
            $join->on('order_details.orders_id', '=', 'orders.id')
                ->on('order_details.short_name', '=', 'skues.short_name');
        })
            ->leftJoin('sales', function ($join) {
                $join->on('sales.aso_id', '=', 'orders.aso_id')
                    ->on('sales.order_date', '=', 'orders.order_date')
                    ->where('orders.order_status', 'Processed');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed')
            ->where('orders.order_date', $date)
            ->groupBy('skues.short_name')
            ->get()->toArray();
        $response = [];
        if (count($data) > 0) {
            $order = [];
            $sale = [];
            foreach ($data as $value) {
                $response['data'][$value->short_name] = [
                    $value->order_quantity,
                    $value->sale_quantity,
                ];
                $response['additonal']['no_of_outlet'] = $value->total_outlet;
                $response['additonal']['visited_outlet'] = $value->visited_outlet;
                $response['additonal']['no_of_memo'] = $value->total_no_of_memo;
            }
        }

        return $response;
    }

    public static function dailySaleSummary($selected_asos, $selected_memo, $selected_date, $type = "aso")
    {
        $availabale_days = availableWorkingDates($selected_date[1], $selected_date[0]);
        $response = [];
        foreach ($selected_asos as $aso_value) {
            $order = [];
            $sale = [];
            foreach ($availabale_days as $day) {
                $remaining_days = count($availabale_days) - (array_search($day, $availabale_days) + 1);
                $time = strtotime($day);
                $month = date("F", $time);
                $year = date("Y", $time);
                $selected_routes = Reports::getRouteInfoByAso([$aso_value]);
                //aso
                if ($type == 'aso') {
                    $get_target = self::getAsoTargetByRoutes(array_column($selected_routes, 'id'), $month . '-' . $year);
                    $passed_array = [];
                    foreach ($get_target as $value) {
                        $passed_array[] = json_decode($value['target_value'], true);
                    }
                    $final = array();

                    array_walk_recursive($passed_array, function ($item, $key) use (&$final) {
                        $final[$key] = isset($final[$key]) ? $item + $final[$key] : $item;
                    });
                    $dss_data = self::getDss($aso_value, $day, $type);
                    $sku_info = [];
                    foreach ($selected_memo as $cat_key => $cat_val) {
                        $selected_skues = array_flatten($cat_val);
                        foreach ($selected_skues as $key => $value) {
                            $order[$value] = calculate_case($value, (isset($order[$value]) ? $order[$value] : 0), (count($dss_data) > 0 ? $dss_data['data'][$value][0] : 0), 'plus');
                            $sale[$value] = calculate_case($value, (isset($sale[$value]) ? $sale[$value] : 0), (count($dss_data) > 0 ? $dss_data['data'][$value][1] : 0), 'plus');
                            $rdt_calculate = count($final) > 0 ? $final[$value] - $sale[$value] : 0 - $sale[$value];
                            $sku_info['data']['RDT'][] = isset($final[$value]) && $remaining_days > 0 ? number_format(($rdt_calculate / $remaining_days), 2) : 0;
                            $sku_info['data']['Order'][] = count($dss_data) > 0 ? (!is_null($dss_data['data'][$value][0]) ? $dss_data['data'][$value][0] : 0) : 0;
                            $sku_info['data']['Sales'][] = count($dss_data) > 0 ? (!is_null($dss_data['data'][$value][1]) ? $dss_data['data'][$value][1] : 0) : 0;
                            $sku_info['data']['Cum Ach%'][] = count($final) > 0 ? number_format(($sale[$value] / $final[$value]) * 100, 2) : 0;
                        }
                    }

                    $response_data = $sku_info;
                    $response_data['additional']['no_of_outlet'] = count($dss_data) > 0 ? $dss_data['additonal']['no_of_outlet'] : 0;
                    $response_data['additional']['visited_outlet'] = count($dss_data) > 0 ? $dss_data['additonal']['visited_outlet'] : 0;
                    $response_data['additional']['total_memo'] = count($dss_data) > 0 ? $dss_data['additonal']['no_of_memo'] : 0;
                    $response[getNameAso($aso_value)->name][$day] = $response_data;
                } else {
                    foreach (array_column($selected_routes, 'id') as $route_id) {
                        $get_target = self::getAsoTargetByRoutes([$route_id], $month . '-' . $year);
                        if (count($get_target) > 0) {

                            $final = json_decode($get_target[0]['target_value'], true);
                        } else {
                            $final = [];
                        }
                        $dss_data = self::getDss($aso_value, $day, $type);
                        $sku_info = [];
                        foreach ($selected_memo as $cat_key => $cat_val) {
                            $selected_skues = array_flatten($cat_val);
                            foreach ($selected_skues as $key => $value) {
                                $order[$value] = calculate_case($value, (isset($order[$value]) ? $order[$value] : 0), (count($dss_data) > 0 ? $dss_data['data'][$value][0] : 0), 'plus');
                                $sale[$value] = calculate_case($value, (isset($sale[$value]) ? $sale[$value] : 0), (count($dss_data) > 0 ? $dss_data['data'][$value][1] : 0), 'plus');
                                $rdt_calculate = count($final) > 0 ? $final[$value]-$sale[$value] : 0 - $sale[$value];
                                $sku_info['data']['RDT'][] = isset($final[$value]) && $remaining_days > 0 ? number_format(($rdt_calculate / $remaining_days), 2) : 0;
                                $sku_info['data']['Order'][] = count($dss_data) > 0 ? (!is_null($dss_data['data'][$value][0]) ? $dss_data['data'][$value][0] : 0) : 0;
                                $sku_info['data']['Sales'][] = count($dss_data) > 0 ? (!is_null($dss_data['data'][$value][1]) ? $dss_data['data'][$value][1] : 0) : 0;
                                $sku_info['data']['Cum Ach%'][] = count($final) > 0 && $final[$value] > 0 ? number_format(($sale[$value] / $final[$value]) * 100, 2) : 0;
                            }
                        }

                        $response_data = $sku_info;
                        $response_data['additional']['no_of_outlet'] = count($dss_data) > 0 ? $dss_data['additonal']['no_of_outlet'] : 0;
                        $response_data['additional']['visited_outlet'] = count($dss_data) > 0 ? $dss_data['additonal']['visited_outlet'] : 0;
                        $response_data['additional']['total_memo'] = count($dss_data) > 0 ? $dss_data['additonal']['no_of_memo'] : 0;
                        $response[getNameRoute($route_id)->routes_name][$day] = $response_data;
                    }
                }

            }
        }

        return $response;


    }

//    public static function dailySaleSummaryRoute($selected_asos, $selected_memo, $selected_date){
//        $availabale_days = availableWorkingDates($selected_date[1], $selected_date[0]);
//        $response = [];
//        foreach ($selected_asos as $aso_value) {
//            $rdt = [];
//            $order = [];
//            $sale =[];
//            foreach ($availabale_days as $day) {
//                $remaining_days= count($availabale_days) - (array_search($day, $availabale_days)+1);
//                $time = strtotime($day);
//                $month = date("F", $time);
//                $year = date("Y", $time);
//                $selected_routes = Reports::getRouteInfoByAso([$aso_value]);
//                $get_target = self::getAsoTargetByRoutes(array_column($selected_routes, 'id'), $month . '-' . $year);
//                $passed_array = [];
//                foreach ($get_target as $value) {
//                    $passed_array[] = json_decode($value['target_value'], true);
//                }
//                $final = array();
//
//                array_walk_recursive($passed_array, function ($item, $key) use (&$final) {
//                    $final[$key] = isset($final[$key]) ? $item + $final[$key] : $item;
//                });
////                dd($aso_value,$day);
//                $dss_data = self::getDss($aso_value, $day);
//                $sku_info = [];
//                foreach ($selected_memo as $cat_key => $cat_val) {
//                    $selected_skues = array_flatten($cat_val);
//                    foreach ($selected_skues as $key => $value) {
//                        $order[$value] = calculate_case($value, (isset($order[$value]) ? $order[$value] : 0), (count($dss_data) > 0 ? $dss_data['data'][$value][0] : 0), 'plus');
//                        $sale[$value] = calculate_case($value, (isset($sale[$value]) ? $sale[$value] : 0), (count($dss_data) > 0 ?$dss_data['data'][$value][1] : 0), 'plus');
//                        $rdt_calculate =count($final) > 0 ? $final[$value] : 0 - $sale[$value];
//                        $sku_info['data']['RDT'][] = isset($final[$value]) && $remaining_days >0 ?  number_format(($rdt_calculate /$remaining_days),2) : 0 ;
//                        $sku_info['data']['Order'][] = count($dss_data) > 0 ? (!is_null($dss_data['data'][$value][0]) ? $dss_data['data'][$value][0] :0 ) : 0;
//                        $sku_info['data']['Sales'][] = count($dss_data) > 0 ? (!is_null($dss_data['data'][$value][1]) ? $dss_data['data'][$value][1] :0 ) : 0;
//                        $sku_info['data']['Cum Ach%'][] = count($final) > 0 ? number_format(($sale[$value] / $final[$value])*100, 2) : 0;
//                    }
//                }
//
//                $response_data = $sku_info;
//                $response_data['additional']['aso_id'] = $aso_value;
//                $response_data['additional']['aso_name'] = getNameAso($aso_value)->name;
//                $response_data['additional']['no_of_outlet'] = count($dss_data) > 0 ? $dss_data['additonal']['no_of_outlet'] : 0;
//                $response_data['additional']['visited_outlet'] =  count($dss_data) > 0 ? $dss_data['additonal']['visited_outlet'] : 0;
//                $response_data['additional']['total_memo'] =  count($dss_data) > 0 ? $dss_data['additonal']['no_of_memo'] : 0;
//                $response[$day][] = $response_data;
//            }
//        }
//
//        return $response;
//    }


    public static function individual_routes_info($route_id)
    {
        $result = DB::table('routes')
            ->select('routes.routes_code', 'routes.routes_name', 'distribution_houses.market_name', 'distribution_houses.point_name', 'distribution_houses.propietor_address')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id')
            ->where('routes.id', $route_id)
            ->first();
        return $result;
    }


    public static function individual_house_info($house_id)
    {
        $result = DB::table('routes')
            ->select('routes.routes_code', 'routes.routes_name', 'distribution_houses.market_name', 'distribution_houses.point_name', 'distribution_houses.propietor_address')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'routes.distribution_houses_id')
            ->where('routes.distribution_houses_id', $house_id)
            ->groupBy('routes.distribution_houses_id')
            ->first();
        return $result;
    }


    public static function get_sale_by_month_house($db_id, $sku_name, $month)
    {
        $date = date_parse($month);
        $data = DB::table('sales')
            ->select('sales.id', 'sale_details.quantity')
            ->join('sale_details', 'sales_id', '=', 'sales.id')
            ->where('sale_details.short_name', $sku_name)
            ->where('sales.dbid', $db_id)
            ->where('sales.sale_type', 'Secondary')
            ->whereYear('sales.order_date', $date['year'])
            ->whereMonth('sales.order_date', $date['month'])
            ->get()->toArray();
        $count = 0;
        if (count($data) > 0) {
            foreach ($data as $val) {
                $count += (int)$val->quantity;
            }
        }
        return $count;
    }

    public static function get_sale_by_month_route($route_id, $sku_name, $month)
    {
        $date = date_parse($month);

        $data = DB::table('sales')
            ->select('sales.id', 'sale_details.quantity')
            ->join('sale_details', 'sales_id', '=', 'sales.id')
            ->where('sale_details.short_name', $sku_name)
            ->where('sales.sale_route_id', $route_id)
            ->where('sales.sale_type', 'Secondary')
            ->whereYear('sales.order_date', $date['year'])
            ->whereMonth('sales.order_date', $date['month'])
            ->get()->toArray();
        $count = 0;
        if (count($data) > 0) {
            foreach ($data as $val) {
                $count += (int)$val->quantity;
            }
        }
        return $count;
    }


    public static function get_sale_by_month_house2($route_id, $sku_name, $month)
    {
        $date = date_parse($month);

        $data = DB::table('sales')
            ->select('sales.id', 'sale_details.quantity')
            ->join('sale_details', 'sales_id', '=', 'sales.id')
            ->where('sale_details.short_name', $sku_name)
            ->where('sales.dbid', $route_id)
            ->where('sales.sale_type', 'Secondary')
            ->whereYear('sales.order_date', $date['year'])
            ->whereMonth('sales.order_date', $date['month'])
            ->get()->toArray();
        $count = 0;
        if (count($data) > 0) {
            foreach ($data as $val) {
                $count += (int)$val->quantity;
            }
        }
        return $count;
    }


    public static function liftingStatementData($selected_houses,$selected_date_range)
    {
        $data = DB::table('sales')
            ->select('sales.sale_date','sales.total_sale_amount','sales.house_current_balance','orders.order_da','sales.dbid','orders.id')
            ->leftJoin('orders', 'orders.id', '=', 'sales.order_id')
            ->where('sales.sale_type', 'Primary')
            ->where('sales.sale_status', 'Processed')
            ->where('sales.dbid',$selected_houses)
            ->whereBetween('sales.sale_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->get()->toArray();
        return $data;
    }

    public static function accountStatementHouseInfo($selected_houses,$selected_date_range)
    {
        //debug($selected_date_range,1);
        $dateArray = explode(' - ',$selected_date_range[0]);
        $prevDate = date('Y-m-d', strtotime($dateArray[0] .' -1 day'));
        $data = DB::table('distribution_houses')
            ->select('distribution_houses.*',DB::raw('ifnull(sales.house_current_balance,0) as cb'))
            ->leftJoin('sales', function ($join) use($prevDate) {
                $join->on('sales.dbid', '=', 'distribution_houses.id')->where('sales.sale_date',$prevDate);
            })
            ->where('distribution_houses.id', $selected_houses)->first();
        return $data;
    }


    public static function getSecondaryOrderSaleByIds($ids,$selected_date_range)
    {
        $data = DB::table('skues')
            ->select('distribution_houses.id', 'distribution_houses.point_name', 'skues.short_name', 'orders.requester_name', 'skues.short_name', DB::raw('SUM(order_details.quantity) as order_quantity'), DB::raw('SUM(sale_details.quantity) as sale_quantity'))
            ->leftJoin('order_details', 'order_details.short_name', '=', 'skues.short_name')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.order_id', '=', 'orders.id')
                    ->on('sales.order_date', '=', 'orders.order_date');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed')
            ->whereIn('orders.aso_id', $ids)
            ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->groupBy('skues.short_name')
            ->groupBy('distribution_houses.point_name')
            ->get();
        return $data;
    }

    public static function getSecondaryOrderAsoSaleByIds($ids, $selected_date_range)
    {
//        debug($ids,1);
        $data = DB::table('skues')
            ->select('distribution_houses.id', 'orders.aso_id', 'distribution_houses.point_name', 'skues.short_name', 'orders.requester_name', 'skues.short_name', DB::raw('SUM(order_details.quantity) as order_quantity'), DB::raw('SUM(sale_details.quantity) as sale_quantity'))
            ->leftJoin('order_details', 'order_details.short_name', '=', 'skues.short_name')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.order_id', '=', 'orders.id')
                    ->on('sales.order_date', '=', 'orders.order_date');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed')
            ->where('orders.order_status', 'Processed')
            ->whereIn('orders.aso_id', $ids)
            ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->groupBy('orders.aso_id', 'skues.short_name')
            ->get();
        return $data;
    }

    public static function getSecondaryOrderDateSaleByIds($ids, $selected_date_range, $route_id = null)
    {
        $data = DB::table('skues')
            ->select('distribution_houses.id', 'orders.order_date', 'orders.aso_id', 'distribution_houses.point_name', 'routes.id as route_id', 'routes.routes_name', 'skues.short_name', 'orders.requester_name', 'skues.short_name', DB::raw('SUM(order_details.quantity) as order_quantity'), DB::raw('SUM(sale_details.quantity) as sale_quantity'))
            ->leftJoin('order_details', 'order_details.short_name', '=', 'skues.short_name')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.order_id', '=', 'orders.id')
                    ->on('sales.order_date', '=', 'orders.order_date');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
//            ->leftJoin('routes','routes.so_aso_user_id','=','orders.aso_id')
            ->leftjoin('routes', function ($join) {
                $join->on('routes.so_aso_user_id', '=', 'orders.aso_id')
                    ->on('routes.id', '=', 'orders.route_id')
                    ->on('routes.id', '=', 'sales.sale_route_id');
            })
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed');
        if ($route_id) {
            $data->where('orders.route_id', $route_id);
        }
        $data->whereIn('orders.aso_id', $ids)
            ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->groupBy('orders.order_date', 'orders.aso_id', 'skues.short_name');

        $result = $data->get();
//            debug($result,1);
        return $result;

    }

    public static function getSecondaryOrderRouteSaleByIds($ids, $selected_date_range, $route_id = null)
    {
        $data = DB::table('skues')
            ->select('distribution_houses.id', 'orders.order_date', 'orders.aso_id', 'distribution_houses.point_name', 'routes.id as route_id', 'routes.routes_name', 'skues.short_name', 'orders.requester_name', 'skues.short_name', DB::raw('SUM(order_details.quantity) as order_quantity'), DB::raw('SUM(sale_details.quantity) as sale_quantity'))
            ->leftJoin('order_details', 'order_details.short_name', '=', 'skues.short_name')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.order_id', '=', 'orders.id')
                    ->on('sales.order_date', '=', 'orders.order_date');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
//            ->leftJoin('routes','routes.so_aso_user_id','=','orders.aso_id')
            ->leftjoin('routes', function ($join) {
                $join->on('routes.so_aso_user_id', '=', 'orders.aso_id')
                    ->on('routes.id', '=', 'orders.route_id')
                    ->on('routes.id', '=', 'sales.sale_route_id');
            })
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed');
        if ($route_id) {
            $data->where('orders.route_id', $route_id);
        }
        $data->whereIn('orders.aso_id', $ids)
            ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->groupBy('orders.route_id', 'orders.aso_id', 'skues.short_name');

        $result = $data->get();
//            debug($result,1);
        return $result;
    }

    public static function orderVsSaleSecondaryDate($route_id, $asos, $selected_memo, $selected_date_range)
    {
        $response = [];
        $selected_aso = array_column($asos, 'id');

        $data = \App\Models\Reports::getSecondaryOrderDateSaleByIds($selected_aso, $selected_date_range, $route_id);
//            debug($data,1);
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
//                dd();
                $response[$value->order_date][$value->short_name]['requested'] = $value->order_quantity;
                $response[$value->order_date][$value->short_name]['delivered'] = $value->sale_quantity;
                $response[$value->order_date]['route_id'] = $value->route_id;
            }

        }
//        debug($response,1);
        $response_data = [];
        foreach ($response as $h_key => $h_value) {
            $sku_gen_value = [];
            foreach ($selected_memo as $cat_key => $cat_val) {

                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    $sku_gen_value[] = [
                        isset($h_value[$value]['requested']) ? $h_value[$value]['requested'] : 0,
                        isset($h_value[$value]['delivered']) ? $h_value[$value]['delivered'] : 0
                    ];

                }
            }
            $response_data[$h_key]['additional'] = [
                'route_id' => $h_value['route_id']
            ];


            $response_data[$h_key]['data'] = $sku_gen_value;
        }

        return $response_data;

    }

    public static function gerOrderVsSaleByHouseIds($house_ids, $selected_date_range)
    {

        $data = DB::table('skues')
            ->select('distribution_houses.id as house_id', 'distribution_houses.current_balance', 'distribution_houses.point_name', 'orders.order_date', 'orders.aso_id', 'sales.house_current_balance', 'distribution_houses.point_name', 'skues.short_name', 'orders.requester_name', 'skues.short_name', DB::raw('SUM(order_details.quantity) as order_quantity'), DB::raw('SUM(sale_details.quantity) as sale_quantity'))
            ->leftJoin('order_details', 'order_details.short_name', '=', 'skues.short_name')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.order_id', '=', 'orders.id')
                    ->on('sales.order_date', '=', 'orders.order_date');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->join('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
            ->where('orders.order_type', 'Primary')
            ->where('orders.order_status', 'Processed');
        if (is_array($house_ids)) {
            $data->whereIn('orders.dbid', $house_ids);
        } else {
            $data->where('orders.dbid', $house_ids);
        }

        $data->whereBetween('orders.order_date', array_map('trim', explode(" - ", $selected_date_range[0])))
            ->groupBy('skues.short_name','orders.dbid');
        if (!is_array($house_ids)) {
            $data->groupBy('orders.order_date');
        }
        $data = $data->get();

        return $data;
    }

    public static function order_vs_sale_primary_by_house($house_ids, $selected_memo, $selected_date_range)
    {
        $data = \App\Models\Reports::gerOrderVsSaleByHouseIds($house_ids, $selected_date_range);
        $response = [];
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $response[$value->point_name][$value->short_name]['requested'] = $value->order_quantity;
                $response[$value->point_name][$value->short_name]['delivered'] = $value->sale_quantity;
                $response[$value->point_name]['current_balace'] = $value->current_balance;
                $response[$value->point_name]['house_id'] = $value->house_id;
            }
        }
        $response_data = [];
        foreach ($response as $h_key => $h_value) {
            $sku_gen_value = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    $sku_gen_value[] = [
                        isset($h_value[$value]['requested']) ? $h_value[$value]['requested'] : 0,
                        isset($h_value[$value]['delivered']) ? $h_value[$value]['delivered'] : 0
                    ];

                }
            }
            $response_data[$h_key]['additional'] = [
                'house_id' => $h_value['house_id'],
                'current_balance' => $h_value['current_balace']
            ];


            $response_data[$h_key]['data'] = $sku_gen_value;
        }

        return $response_data;

    }

    public static function order_vs_sale_primary_by_date($house_ids, $selected_memo, $selected_date_range)
    {
        $data = \App\Models\Reports::gerOrderVsSaleByHouseIds($house_ids, $selected_date_range);

        $response = [];
        if (!$data->isEmpty()) {
            foreach ($data as $value) {
                $response[$value->order_date][$value->short_name]['requested'] = $value->order_quantity;
                $response[$value->order_date][$value->short_name]['delivered'] = $value->sale_quantity;
                $response[$value->order_date]['house_current_balance'] = $value->house_current_balance;
            }
        }


        $response_data = [];
        foreach ($response as $h_key => $h_value) {
            $sku_gen_value = [];
            foreach ($selected_memo as $cat_key => $cat_val) {

                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    $sku_gen_value[] = [
                        isset($h_value[$value]['requested']) ? $h_value[$value]['requested'] : 0,
                        isset($h_value[$value]['delivered']) ? $h_value[$value]['delivered'] : 0
                    ];

                }
            }
            $response_data[$h_key]['additional'] = [
                'current_balance' => $h_value['house_current_balance']
            ];


            $response_data[$h_key]['data'] = $sku_gen_value;
        }

        return $response_data;

    }

    public static function houseWisePerformance($ids, $selected_memo, $month)
    {
        $db_house_wise_performance = [];
        foreach ($ids as $house_key => $house_value) {
            $house = \App\Models\DistributionHouse::where('id', $house_value)->first()->toArray();
            $get_target = \App\Models\Target::where('target_type', 'house')->where('type_id', $house_value)->where('target_month', isset($month[0]) ? $month[0] : '')->first();
            $sku_target = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                $target_value = json_decode($get_target['base_value'], true);
                foreach ($selected_skues as $key => $value) {
                    $cumulative_sale = \App\Models\Reports::get_sale_by_month_house($house_value, $value, isset($month[0]) ? $month[0] : '');
                    if (!empty($get_target)) {
                        $sku_target['data'][] = [
                            isset($target_value[$value]) ? $target_value[$value] : 0,
                            $cumulative_sale,
                            achievement($target_value[$value], $cumulative_sale)
                        ];
                    } else {
                        $sku_target['data'][] = [
                            0, $cumulative_sale, 0
                        ];
                    }

                }

            }

            $db_house_wise_performance[$house['point_name']] = $sku_target;
            $db_house_wise_performance[$house['point_name']]['house_id'] = $house_value;
        }
        return $db_house_wise_performance;
    }

    public static function routeWisePerformance($ids, $selected_memo, $month)
    {
//        debug($ids,1);
        $route_wise_performance = [];
        foreach ($ids as $route_key => $route_value) {
            $get_target = \App\Models\Target::where('target_type', 'market')->where('type_id', $route_value['id'])->where('target_month', isset($month[0]) ? $month[0] : '')->first();
            $sku_target = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                $target_value = json_decode($get_target['base_value'], true);
                foreach ($selected_skues as $key => $value) {
                    $cumulative_sale = \App\Models\Reports::get_sale_by_month_route($route_value['id'], $value, isset($month[0]) ? $month[0] : '');
                    if (!empty($get_target)) {
                        $sku_target[] = [
                            isset($target_value[$value]) ? $target_value[$value] : 0,
                            $cumulative_sale,
                            achievement($target_value[$value], $cumulative_sale)
                        ];
                    } else {
                        $sku_target[] = [
                            0, $cumulative_sale, 0
                        ];
                    }

                }

            }

            $route_wise_performance[$route_value['name']] = $sku_target;
        }
        return $route_wise_performance;
    }


    public static function routeWisePerformance2($ids, $selected_memo, $month)
    {
//        debug($ids,1);
        $route_wise_performance = [];
        foreach ($ids as $route_key => $route_value) {
            $route_wise_performance[$route_key]['routes_name'] = $route_value['routes_name'];
            $route_wise_performance[$route_key]['aso_name'] = $route_value['uname'];
            $route_wise_performance[$route_key]['db_house'] = $route_value['point_name'];
            $route_wise_performance[$route_key]['route_id'] = $route_value['id'];

            $get_target = \App\Models\Target::where('target_type', 'market')->where('type_id', $route_value['uid'])->where('target_month', isset($month[0]) ? $month[0] : '')->first();
//            debug($get_target,1);
            $sku_target = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                $target_value = json_decode($get_target['base_value'], true);
                foreach ($selected_skues as $key => $value) {
                    $cumulative_sale = \App\Models\Reports::get_sale_by_month_route($route_value['id'], $value, isset($month[0]) ? $month[0] : '');
                    if (!empty($get_target)) {
                        $sku_target[] = [
                            isset($target_value[$value]) ? $target_value[$value] : 0,
                            $cumulative_sale,
                            achievement($target_value[$value], $cumulative_sale)
                        ];
                    } else {
                        $sku_target[] = [
                            0, $cumulative_sale, 0
                        ];
                    }

                }
//                debug($sku_target,1);

            }

//            $route_wise_performance[$route_value['routes_name']]=$sku_target;
            $route_wise_performance[$route_key]['result'] = $sku_target;
        }
//        debug($route_wise_performance,1);
        return $route_wise_performance;
    }


    public static function routeWisePerformance3($ids, $selected_memo, $month)
    {
//        debug($ids,1);
        $route_wise_performance = [];
        foreach ($ids as $route_key => $route_value) {
            $route_wise_performance[$route_key]['routes_name'] = $route_value['routes_name'];
            $route_wise_performance[$route_key]['aso_name'] = $route_value['uname'];
            $route_wise_performance[$route_key]['db_house'] = $route_value['point_name'];
            $route_wise_performance[$route_key]['route_id'] = $route_value['id'];

            $get_target = \App\Models\Target::where('target_type', 'market')
                ->where('type_id', $route_value['uid'])
                ->where('target_month', isset($month[0]) ? $month[0] : '')->first();
            //debug($get_target,1);
            $sku_target = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = $cat_val;
                $target_value = json_decode($get_target['base_value'], true);
//                debug($selected_skues,1);
                foreach ($selected_skues as $key => $value) {
                    $cumulative_sale = \App\Models\Reports::get_sale_by_month_route($route_value['id'], $key, isset($month[0]) ? $month[0] : '');
                    if (!empty($get_target)) {
                        $sku_target[$key] = [
                            isset($target_value[$key]) ? $target_value[$key] : 0,
                            $cumulative_sale,
                            achievement($target_value[$key], $cumulative_sale)
                        ];
                    } else {
                        $sku_target[$key] = [
                            0, $cumulative_sale, 0
                        ];
                    }

                }
//                debug($sku_target,1);

            }

//            $route_wise_performance[$route_value['routes_name']]=$sku_target;
            $route_wise_performance[$route_key]['result'] = $sku_target;
        }
//        debug($route_wise_performance,1);
        return $route_wise_performance;
    }

    public static function routeWisePerformance4($ids, $selected_memo, $month)
    {
//        debug($ids,1);
        $route_wise_performance = [];
        foreach ($ids as $house_key => $house_value) {
            $route_wise_performance[$house_key]['routes_name'] = $house_value['routes_name'];
            $route_wise_performance[$house_key]['aso_name'] = $house_value['uname'];
            $route_wise_performance[$house_key]['db_house'] = $house_value['point_name'];
            $route_wise_performance[$house_key]['route_id'] = $house_value['id'];

            $get_target = \App\Models\Target::where('target_type', 'house')
                ->where('type_id', $house_value['distribution_houses_id'])
                ->where('target_month', isset($month[0]) ? $month[0] : '')->first();
            //debug($get_target,1);
            $sku_target = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = $cat_val;
                $target_value = json_decode($get_target['base_value'], true);
//                debug($selected_skues,1);
                foreach ($selected_skues as $key => $value) {
                    $cumulative_sale = \App\Models\Reports::get_sale_by_month_house2($house_value['distribution_houses_id'], $key, isset($month[0]) ? $month[0] : '');
                    if (!empty($get_target)) {
                        $sku_target[$key] = [
                            isset($target_value[$key]) ? $target_value[$key] : 0,
                            $cumulative_sale,
                            achievement($target_value[$key], $cumulative_sale)
                        ];
                    } else {
                        $sku_target[$key] = [
                            0, $cumulative_sale, 0
                        ];
                    }

                }
//                debug($sku_target,1);

            }

//            $route_wise_performance[$route_value['routes_name']]=$sku_target;
            $route_wise_performance[$house_key]['result'] = $sku_target;
        }
//        debug($route_wise_performance,1);
        return $route_wise_performance;
    }

    public static function routeWiseStrikeRate($ids, $selected_memo, $date_range)
    {
        $route_wise_strike_rate = [];
        foreach ($ids as $route_key => $route_value) {
            $data = DB::table('order_details')
                ->select('skues.sku_name', 'order_details.short_name', DB::raw('SUM(orders.total_outlet) as total_outlet'), DB::raw('SUM(orders.visited_outlet) as visited_outlet'), 'orders.total_no_of_memo', DB::raw('SUM(order_details.quantity) as order_quantity'), DB::raw('SUM(sale_details.quantity) as  sale_quantity'), DB::raw('SUM(order_details.no_of_memo) as  total_indi_no_of_memo'))
                ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
                ->leftJoin('sales', function ($join) {
                    $join->on('sales.aso_id', '=', 'orders.aso_id')
                        ->on('sales.order_date', '=', 'orders.order_date');
                })
                ->leftJoin('sale_details', function ($join) {
                    $join->on('sale_details.sales_id', '=', 'sales.id')
                        ->on('sale_details.short_name', '=', 'order_details.short_name');
                })
                ->leftJoin('skues', 'skues.short_name', '=', 'order_details.short_name')
                ->where('orders.order_type', 'Secondary')
                ->where('orders.aso_id', $route_value['id'])
                ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $date_range[0])))
                ->groupBy('order_details.short_name')
                ->orderBy('orders.id', 'DESC');
            $data = $data->get()->toArray();
            $sku_gen_value = [];
            $sku_gen_additional = [];
            foreach ($selected_memo as $cat_key => $cat_val) {
                $selected_skues = array_flatten($cat_val);
                foreach ($selected_skues as $key => $value) {
                    $selected_value = array_search($value, array_column($data, 'short_name'));
                    if ($selected_value != false) {
                        $sku_gen_value[] = [
                            productivity($data[$selected_value]->total_indi_no_of_memo, $data[$selected_value]->total_no_of_memo),
                            avg_per_memo(array_sum(array_column($data, 'total_indi_no_of_memo')), $data[$selected_value]->total_no_of_memo),
                            volume_per_memo($data[$selected_value]->order_quantity, $data[$selected_value]->total_indi_no_of_memo),
                            protfolio_volume($data[$selected_value]->order_quantity, $data[$selected_value]->total_no_of_memo),
                            bounce_call($data[$selected_value]->order_quantity, $data[$selected_value]->sale_quantity)
                        ];
                    } else {
                        $sku_gen_value[] = [0, 0, 0, 0, 0, 0];
                    }

                }
            }
            $route_wise_strike_rate[$route_value['name']]['data'] = $sku_gen_value;
            if (count($data) > 0) {
                $route_wise_strike_rate[$route_value['name']]['additional'] = [
                    $data[count($data) - 1]->total_outlet,
                    $data[count($data) - 1]->visited_outlet,
                    visited_outlet_per($data[count($data) - 1]->visited_outlet, $data[$selected_value]->total_outlet),
                    $data[count($data) - 1]->total_no_of_memo,
                    call_productivity($data[count($data) - 1]->total_no_of_memo, $data[$selected_value]->visited_outlet)
                ];
            } else {
                $route_wise_strike_rate[$route_value['name']]['additional'] = [
                    0, 0, 0, 0, 0
                ];
            }


        }

        return $route_wise_strike_rate;

    }


    public static function brand_wise_sale($ids, $selected_memo, $tweelveMonth)
    {
        //debug($ids,1);
        $sql = 'SELECT brands.id as brandId,brands.brand_name,x.tq,x.ta,x.sdate,x.short_name FROM brands
            LEFT JOIN(
            SELECT
            skues.brands_id,
            skues.short_name,
            sale_details.case tq,
            (sale_details.price*sale_details.case) ta,
            MONTH(sales.sale_date) sdate
            FROM
            sale_details
            LEFT JOIN skues ON sale_details.short_name = skues.short_name
            LEFT JOIN sales ON sale_details.sales_id = sales.id AND YEAR(sales.sale_date)="'.$tweelveMonth.'" AND sales.sale_route_id IN ('.implode(",",$ids).')
            WHERE sales.sale_type="Secondary" AND sales.sale_status="Processed"
            GROUP BY
            skues.short_name,
            MONTH(sales.sale_date))x ON x.brands_id=brands.id';
        //debug($sql,1);
        $result = DB::select($sql);

        //debug($result,1);
        $dataArray = array();
        foreach ($result as $k=>$v)
        {
            $dataArray[$v->brand_name]['ta'] = isset($dataArray[$v->brand_name]['ta']) ? $dataArray[$v->brand_name]['ta']+ $v->ta : $v->ta;
            $dataArray[$v->brand_name]['tq'] = isset($dataArray[$v->brand_name]['tq']) ? $dataArray[$v->brand_name]['tq']+ $v->tq : $v->tq;
            $dataArray[$v->brand_name]['sdate'] = $v->sdate;
        }

        return $dataArray;
    }








public static function brandWiseSales($ids, $tweelveMonth)
    {
        //debug($ids,1);
        $sql = 'SELECT brands.brand_name,x.tq,x.ta,x.sdate FROM brands
            LEFT JOIN(
            SELECT
            skues.brands_id,
            Sum(sale_details.quantity) tq,
            Sum(sale_details.price*quantity) ta,
            MONTH(sales.sale_date) sdate
            FROM
            sale_details
            LEFT JOIN skues ON sale_details.short_name = skues.short_name
            LEFT JOIN sales ON sale_details.sales_id = sales.id AND YEAR(sales.sale_date)="'.$tweelveMonth.'" AND sales.sale_route_id IN ('.implode(",",$ids).')
            WHERE sales.sale_type="Secondary"
            GROUP BY
            skues.brands_id)x ON x.brands_id=brands.id';
        //debug($sql,1);
        $result = DB::select($sql);

        return (array)$result;
    }
    private static function getRankingAch($data)
    {
        $rankingConfig = Config::get('rank')['ranking'];
        $visited_outlet_mark = 0;
        $call_productivity_mark = 0;
        $brand_call_productivity = 0;
        $protfolio_volume = 0;
        $value_per_call = 0;
        $bounce_call = 0;
        foreach ($rankingConfig as $key => $value) {
            switch ($key) {
                case 'v_o':
                    $obtained_marks = ($data['total_visited_outlet'] / $data['total_outlet']) * 100;
                    if ($obtained_marks > 100) {
                        $obtained_marks = $rankingConfig['v_o']['required_mark'];
                    }
                    $visited_outlet_mark = number_format(($rankingConfig['v_o']['marks'] * $obtained_marks) / $rankingConfig['v_o']['required_mark'], 2);
                case 'c_p':
                    $obtained_marks = ($data['total_no_of_memo'] / $data['total_visited_outlet']) * 100;
                    if ($obtained_marks > 100) {
                        $obtained_marks = $rankingConfig['c_p']['required_mark'];
                    }
                    $call_productivity_mark = number_format(($rankingConfig['c_p']['marks'] * $obtained_marks) / $rankingConfig['c_p']['required_mark'], 2);
                case 'bcp':
                    $obtained_marks = ($data['total_individual_sku_quantity'] / $data['total_no_of_memo']);
                    $brand_call_productivity = number_format(($rankingConfig['bcp']['marks'] * $obtained_marks) / $rankingConfig['bcp']['required_mark'], 2);
                case 'p_v':
                    $obtained_marks = ($data['total_order_quantity'] / $data['total_no_of_memo']);
                    $protfolio_volume = number_format(($rankingConfig['p_v']['marks'] * $obtained_marks) / $rankingConfig['p_v']['required_mark'], 2);
                case 'v_p_c':
                    $obtained_marks = ($data['total_order_amount'] / $data['total_no_of_memo']);
                    $value_per_call = number_format(($rankingConfig['v_p_c']['marks'] * $obtained_marks) / $rankingConfig['v_p_c']['required_mark'], 2);
                case 'b_c':
                    $obtained_marks = ($data['total_order_quantity'] - $data['total_sale_quantity']) / $data['total_order_quantity'];
                    $bounce_call = number_format(($rankingConfig['b_c']['marks'] * $obtained_marks) / $rankingConfig['b_c']['required_mark'], 2);
                    if ($bounce_call < 0) {
                        $bounce_call = 0;
                    }

            }
        }
        $total = $visited_outlet_mark + $call_productivity_mark + $protfolio_volume + $value_per_call + $bounce_call + $brand_call_productivity;
        return $total;
    }

    private static function getDesignation($id, $type = "")
    {
        switch ($type) {
            case "aso":
                $designation_id = User::where('id', $id)->first(['designation_id']);
                break;
            case "territory":
                $designation_id = User::where('territories_id', $id)->first(['designation_id']);
                break;
            case "region":
                $designation_id = User::where('regions_id', $id)->first(['designation_id']);
                break;
            case "zone":
                $designation_id = User::where('zones_id', $id)->first(['designation_id']);
                break;

        }
        if (isset($designation_id->designation_id)) {
            $data = DB::table('designations')->select('designations.name')->where('id', $designation_id->designation_id)->first();
        }
        return isset($data->name) ? $data->name : 'No Designation';
    }

    private static function getMarketName($aso_id)
    {
        $house_id = User::where('id', $aso_id)->first(['distribution_house_id']);
        $market_name = DistributionHouse::where('id', $house_id->distribution_house_id)->first(['market_name']);
        return isset($market_name->market_name) ? $market_name->market_name : 'No Market';
    }

    private static function getZoneName($id, $type = "")
    {
        switch ($type) {
            case "aso":
                $zone_id = User::where('id', $id)->first(['zones_id']);
                break;
            case "house":
                $zone_id = User::where('distribution_house_id', $id)->first(['zones_id']);
                break;
            case "territory":
                $zone_id = User::where('territories_id', $id)->first(['zones_id']);
                break;
            case "region":
                $zone_id = User::where('regions_id', $id)->first(['zones_id']);
                break;

        }
        if (isset($zone_id->zones_id)) {
            $zone_name = Zone::where('id', $zone_id->zones_id)->first(['zone_name']);
        }
        return isset($zone_name->zone_name) ? $zone_name->zone_name : 'No Zone';
    }

    private static function getColor($value)
    {
        switch ($value) {
            case $value >= 80 && $value <= 100:
                return '59E759';
            case $value >= 70 && $value < 79:
                return '009900';
            case  $value >= 60 && $value < 60:
                return 'FFFF00';
            case  $value >= 50 && $value < 59:
                return 'FF9900';
            case   $value < 50:
                return 'FF0000';


        }
    }

    private static function getRankingDataSql($aso_id, $start_date, $end_date)
    {
        $data = DB::table('orders')
            ->select(
                DB::raw('SUM(orders.visited_outlet) as total_visited_outlet'),
                DB::raw('SUM(orders.total_outlet) as total_outlet'),
                DB::raw('SUM(orders.total_no_of_memo) as total_no_of_memo'),
                DB::raw('SUM(orders.order_total_sku) as total_order_quantity'),
                DB::raw('SUM(orders.order_amount) as total_order_amount'),
                DB::raw('SUM(sales.sale_total_sku) as total_sale_quantity'),
                DB::raw('count(order_details.short_name) as total_individual_sku_quantity'))
            ->leftJoin('sales', 'sales.order_id', '=', 'orders.id')
            ->leftJoin('order_details', 'order_details.orders_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', array($start_date, $end_date))
            ->where('orders.asm_rsm_id', 0)
            ->where('orders.aso_id', $aso_id)
            ->groupBy('orders.aso_id')
            ->get();
        $resultArray = json_decode(json_encode($data), true);
        return $resultArray;
    }

    private static function previousRankingAso($aso_id, $selected_month, &$response)
    {
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0] . "-1 month"));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0] . "-1 month"));
        $resultArray = self::getRankingDataSql($aso_id, $selected_month_first_day, $selected_month_last_day);
        foreach ($resultArray as $key => $value) {
            if (isset($response[$aso_id])) {
                $response[$aso_id]['pre_ach'] = self::getRankingAch($value);
            }
        }

    }

    private static function previousRankingHouse($house_id, $selected_month, &$response)
    {
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0] . "-1 month"));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0] . "-1 month"));
        $resultArray = self::getRankingDataSqlHouse($house_id, $selected_month_first_day, $selected_month_last_day);
        foreach ($resultArray as $key => $value) {
            if (isset($response[$house_id])) {
                $response[$house_id]['pre_ach'] = self::getRankingAch($value);
            }
        }
    }

    private static function previousRankingTerritory($territory_id, $selected_month, &$response)
    {
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0] . "-1 month"));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0] . "-1 month"));
        $resultArray = self::getRankingDataSqlTerritory($territory_id, $selected_month_first_day, $selected_month_last_day);
        foreach ($resultArray as $key => $value) {
            if (isset($response[$territory_id])) {
                $response[$territory_id]['pre_ach'] = self::getRankingAch($value);
            }
        }
    }

    private static function previousRankingRegion($region_id, $selected_month, &$response)
    {
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0] . "-1 month"));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0] . "-1 month"));
        $resultArray = self::getRankingDataSqlRegion($region_id, $selected_month_first_day, $selected_month_last_day);
        foreach ($resultArray as $key => $value) {
            if (isset($response[$region_id])) {
                $response[$region_id]['pre_ach'] = self::getRankingAch($value);
            }
        }
    }

    private static function previousRankingZone($zone_id, $selected_month, &$response)
    {
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0] . "-1 month"));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0] . "-1 month"));
        $resultArray = self::getRankingDataSqlZone($zone_id, $selected_month_first_day, $selected_month_last_day);
        foreach ($resultArray as $key => $value) {
            if (isset($response[$zone_id])) {
                $response[$zone_id]['pre_ach'] = self::getRankingAch($value);
            }
        }
    }

    public static function rankingAso($selected_asoes, $selected_month)
    {
        if (!isset($selected_month[0])) {
            $selected_month = date('F-Y', strtotime(now()));
        }
        $response = [];
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0]));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0]));
        foreach ($selected_asoes as $key => $aso_id) {
            $resultArray = self::getRankingDataSql($aso_id, $selected_month_first_day, $selected_month_last_day);
            if (count($resultArray) > 0) {
                foreach ($resultArray as $key => $value) {
                    $ach_value = self::getRankingAch($value);
                    $response[$aso_id] = [
                        'e_id' => $aso_id,
                        'name' => getNameAso($aso_id)->name,
                        'designation' => self::getDesignation($aso_id, "aso"),
                        'market_name' => self::getMarketName($aso_id),
                        'zone_name' => self::getZoneName($aso_id, "aso"),
                        'ach' => $ach_value,
                        'color' => self::getColor($ach_value)

                    ];
                    self::previousRankingAso($aso_id, $selected_month, $response);
                }

            } else {
                $response[$aso_id] = [
                    'e_id' => $aso_id,
                    'name' => getNameAso($aso_id)->name,
                    'designation' => self::getDesignation($aso_id, "aso"),
                    'market_name' => self::getMarketName($aso_id),
                    'zone_name' => self::getZoneName($aso_id, "aso"),
                    'ach' => 0,
                    'color' => self::getColor(-1)

                ];
            }

        }
        $value_ach = [];
        foreach ($response as $key => $value) {
            $value_ach[$key] = $value['ach'];
        }
        array_multisort($value_ach, SORT_DESC, $response);

        return $response;

    }

    private static function getRankingDataSqlHouse($house_id, $start_date, $end_date)
    {
        $asoes = Reports::getInfo([], [], [], [$house_id]);
        $selected_asoes = array_column($asoes, 'id');
        $data = DB::table('orders')
            ->select(
                DB::raw('SUM(orders.visited_outlet) as total_visited_outlet'),
                DB::raw('SUM(orders.total_outlet) as total_outlet'),
                DB::raw('SUM(orders.total_no_of_memo) as total_no_of_memo'),
                DB::raw('SUM(orders.order_total_sku) as total_order_quantity'),
                DB::raw('SUM(orders.order_amount) as total_order_amount'),
                DB::raw('SUM(sales.sale_total_sku) as total_sale_quantity'),
                DB::raw('count(order_details.short_name) as total_individual_sku_quantity'))
            ->leftJoin('sales', 'sales.order_id', '=', 'orders.id')
            ->leftJoin('order_details', 'order_details.orders_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', array($start_date, $end_date))
            ->where('orders.asm_rsm_id', 0)
            ->whereIn('orders.aso_id', $selected_asoes)
            ->groupBy('orders.dbid')
            ->get();
        $resultArray = json_decode(json_encode($data), true);
        return $resultArray;
    }

    public static function rankingHouse($selected_houses, $selected_month)
    {
        if (!isset($selected_month[0])) {
            $selected_month = date('F-Y', strtotime(now()));
        }
        $response = [];
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0]));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0]));
        foreach ($selected_houses as $key => $house_id) {
            $resultArray = self::getRankingDataSqlHouse($house_id, $selected_month_first_day, $selected_month_last_day);
            if (count($resultArray) > 0) {
                foreach ($resultArray as $key => $value) {
                    $ach_value = self::getRankingAch($value);
                    $response[$house_id] = [
                        'e_id' => $house_id,
                        'name' => getNameHouse($house_id)->point_name,
                        'designation' => "house",
                        'market_name' => getNameHouse($house_id)->market_name,
                        'zone_name' => self::getZoneName($house_id, "house"),
                        'ach' => $ach_value,
                        'color' => self::getColor($ach_value)

                    ];
                    self::previousRankingHouse($house_id, $selected_month, $response);
                }

            } else {
                $response[$house_id] = [
                    'e_id' => $house_id,
                    'name' => getNameHouse($house_id)->point_name,
                    'designation' => "house",
                    'market_name' => getNameHouse($house_id)->market_name,
                    'zone_name' => self::getZoneName($house_id, "house"),
                    'ach' => 0,
                    'color' => self::getColor(-1)

                ];
            }

        }
        $value_ach = [];
        foreach ($response as $key => $value) {
            $value_ach[$key] = $value['ach'];
        }
        array_multisort($value_ach, SORT_DESC, $response);

        return $response;
    }

    private static function getRankingDataSqlTerritory($territory_id, $start_date, $end_date)
    {
        $asoes = Reports::getInfo([], [], [$territory_id]);
        $selected_asoes = array_column($asoes, 'id');
        $data = DB::table('orders')
            ->select(
                DB::raw('SUM(orders.visited_outlet) as total_visited_outlet'),
                DB::raw('SUM(orders.total_outlet) as total_outlet'),
                DB::raw('SUM(orders.total_no_of_memo) as total_no_of_memo'),
                DB::raw('SUM(orders.order_total_sku) as total_order_quantity'),
                DB::raw('SUM(orders.order_amount) as total_order_amount'),
                DB::raw('SUM(sales.sale_total_sku) as total_sale_quantity'),
                DB::raw('count(order_details.short_name) as total_individual_sku_quantity'))
            ->leftJoin('sales', 'sales.order_id', '=', 'orders.id')
            ->leftJoin('order_details', 'order_details.orders_id', '=', 'orders.id')
            ->leftJoin('users', 'users.id', 'orders.aso_id')
            ->whereBetween('orders.order_date', array($start_date, $end_date))
            ->where('orders.asm_rsm_id', 0)
            ->whereIn('orders.aso_id', $selected_asoes)
            ->groupBy('users.territories_id')
            ->get();
        $resultArray = json_decode(json_encode($data), true);
        return $resultArray;
    }

    private static function getRankingDataSqlRegion($region_id, $start_date, $end_date)
    {
        $asoes = Reports::getInfo([], [$region_id]);
        $selected_asoes = array_column($asoes, 'id');
        $data = DB::table('orders')
            ->select(
                DB::raw('SUM(orders.visited_outlet) as total_visited_outlet'),
                DB::raw('SUM(orders.total_outlet) as total_outlet'),
                DB::raw('SUM(orders.total_no_of_memo) as total_no_of_memo'),
                DB::raw('SUM(orders.order_total_sku) as total_order_quantity'),
                DB::raw('SUM(orders.order_amount) as total_order_amount'),
                DB::raw('SUM(sales.sale_total_sku) as total_sale_quantity'),
                DB::raw('count(order_details.short_name) as total_individual_sku_quantity'))
            ->leftJoin('sales', 'sales.order_id', '=', 'orders.id')
            ->leftJoin('order_details', 'order_details.orders_id', '=', 'orders.id')
            ->leftJoin('users', 'users.id', 'orders.aso_id')
            ->whereBetween('orders.order_date', array($start_date, $end_date))
            ->where('orders.asm_rsm_id', 0)
            ->whereIn('orders.aso_id', $selected_asoes)
            ->groupBy('users.regions_id')
            ->get();
        $resultArray = json_decode(json_encode($data), true);
        return $resultArray;
    }

    private static function getRankingDataSqlZone($zone_id, $start_date, $end_date)
    {
        $asoes = Reports::getInfo([$zone_id]);
        $selected_asoes = array_column($asoes, 'id');
        $data = DB::table('orders')
            ->select(
                DB::raw('SUM(orders.visited_outlet) as total_visited_outlet'),
                DB::raw('SUM(orders.total_outlet) as total_outlet'),
                DB::raw('SUM(orders.total_no_of_memo) as total_no_of_memo'),
                DB::raw('SUM(orders.order_total_sku) as total_order_quantity'),
                DB::raw('SUM(orders.order_amount) as total_order_amount'),
                DB::raw('SUM(sales.sale_total_sku) as total_sale_quantity'),
                DB::raw('count(order_details.short_name) as total_individual_sku_quantity'))
            ->leftJoin('sales', 'sales.order_id', '=', 'orders.id')
            ->leftJoin('order_details', 'order_details.orders_id', '=', 'orders.id')
            ->leftJoin('users', 'users.id', 'orders.aso_id')
            ->whereBetween('orders.order_date', array($start_date, $end_date))
            ->where('orders.asm_rsm_id', 0)
            ->whereIn('orders.aso_id', $selected_asoes)
            ->groupBy('users.zones_id')
            ->get();
        $resultArray = json_decode(json_encode($data), true);
        return $resultArray;
    }

    public static function rankingTerritory($selected_territory, $selected_month)
    {
        if (!isset($selected_month[0])) {
            $selected_month = date('F-Y', strtotime(now()));
        }
        $response = [];
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0]));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0]));
        foreach ($selected_territory as $key => $territory_id) {
            $resultArray = self::getRankingDataSqlTerritory($territory_id, $selected_month_first_day, $selected_month_last_day);
            if (count($resultArray) > 0) {
                foreach ($resultArray as $key => $value) {
                    $ach_value = self::getRankingAch($value);
                    $response[$territory_id] = [
                        'e_id' => $territory_id,
                        'name' => getNameTerritory($territory_id)->territory_name,
                        'designation' => self::getDesignation($territory_id, "territory"),
                        'market_name' => getNameTerritory($territory_id)->territory_name,
                        'zone_name' => self::getZoneName($territory_id, "teritory"),
                        'ach' => $ach_value,
                        'color' => self::getColor($ach_value)

                    ];
                    self::previousRankingTerritory($territory_id, $selected_month, $response);
                }

            } else {
                $response[$territory_id] = [
                    'e_id' => $territory_id,
                    'name' => getNameTerritory($territory_id)->territory_name,
                    'designation' => self::getDesignation($territory_id, "territory"),
                    'market_name' => getNameTerritory($territory_id)->territory_name,
                    'zone_name' => self::getZoneName($territory_id, "territory"),
                    'ach' => 0,
                    'color' => self::getColor(-1)

                ];
            }
        }

        $value_ach = [];
        foreach ($response as $key => $value) {
            $value_ach[$key] = $value['ach'];
        }
        array_multisort($value_ach, SORT_DESC, $response);

        return $response;
    }


    public static function rankingRegion($selected_regions, $selected_month)
    {
        if (!isset($selected_month[0])) {
            $selected_month = date('F-Y', strtotime(now()));
        }
        $response = [];
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0]));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0]));
        foreach ($selected_regions as $key => $region_id) {
            $resultArray = self::getRankingDataSqlRegion($region_id, $selected_month_first_day, $selected_month_last_day);
            if (count($resultArray) > 0) {
                foreach ($resultArray as $key => $value) {
                    $ach_value = self::getRankingAch($value);
                    $response[$region_id] = [
                        'e_id' => $region_id,
                        'name' => getNameRegion($region_id)->region_name,
                        'designation' => self::getDesignation($region_id, "region"),
                        'market_name' => getNameRegion($region_id)->region_name,
                        'zone_name' => self::getZoneName($region_id, "region"),
                        'ach' => $ach_value,
                        'color' => self::getColor($ach_value)

                    ];
                    self::previousRankingRegion($region_id, $selected_month, $response);
                }

            } else {
                $response[$region_id] = [
                    'e_id' => $region_id,
                    'name' => getNameRegion($region_id)->region_name,
                    'designation' => self::getDesignation($region_id, "region"),
                    'market_name' => getNameRegion($region_id)->region_name,
                    'zone_name' => self::getZoneName($region_id, "region"),
                    'ach' => 0,
                    'color' => self::getColor(-1)

                ];
            }
        }
        $value_ach = [];
        foreach ($response as $key => $value) {
            $value_ach[$key] = $value['ach'];
        }
        array_multisort($value_ach, SORT_DESC, $response);
        return $response;


    }


    public static function rankingZone($selected_zones, $selected_month)
    {
        if (!isset($selected_month[0])) {
            $selected_month = date('F-Y', strtotime(now()));
        }
        $response = [];
        $selected_month_first_day = date('Y-m-01', strtotime($selected_month[0]));
        $selected_month_last_day = date('Y-m-t', strtotime($selected_month[0]));
        foreach ($selected_zones as $key => $zone_id) {
            $resultArray = self::getRankingDataSqlZone($zone_id, $selected_month_first_day, $selected_month_last_day);
            if (count($resultArray) > 0) {
                foreach ($resultArray as $key => $value) {
                    $ach_value = self::getRankingAch($value);
                    $response[$zone_id] = [
                        'e_id' => $zone_id,
                        'name' => getNameZone($zone_id)->zone_name,
                        'designation' => self::getDesignation($zone_id, "zone"),
                        'market_name' => getNameZone($zone_id)->zone_name,
                        'zone_name' => getNameZone($zone_id)->zone_name,
                        'ach' => $ach_value,
                        'color' => self::getColor($ach_value)

                    ];
                    self::previousRankingZone($zone_id, $selected_month, $response);
                }

            } else {
                $response[$zone_id] = [
                    'e_id' => $zone_id,
                    'name' => getNameZone($zone_id)->zone_name,
                    'designation' => self::getDesignation($zone_id, "zone"),
                    'market_name' => getNameZone($zone_id)->zone_name,
                    'zone_name' => getNameZone($zone_id)->zone_name,
                    'ach' => 0,
                    'color' => self::getColor(-1)

                ];
            }
        }

        $value_ach = [];
        foreach ($response as $key => $value) {
            $value_ach[$key] = $value['ach'];
        }
        array_multisort($value_ach, SORT_DESC, $response);

        return $response;
    }


    public static function targetStatement($target_config,$month,$ids)
    {
            $query = DB::table($target_config['table']);
            if($target_config['aso'] == 'aso')
            {
                $query->select('targets.base_value','targets.target_value','users.name as field_name','users.code','targets.target_type','routes.so_aso_user_id');
            }
            else
            {
                $query->select('targets.base_value','targets.target_value',$target_config['table'].'.'.$target_config['field_name'].' as field_name',$target_config['table'].'.'.$target_config['field_code'],'targets.target_type',$target_config['table'].'.id');
            }

            $query->leftJoin('targets', function ($join) use($target_config,$month,$ids){
                    $join->on('targets.type_id', '=', $target_config['table'].'.id')
                        ->where('target_type',$target_config['type'])
                        ->where('target_month',$month);
                });
            if($target_config['aso'] == 'aso')
            {
                $query->leftJoin('users','users.id','=','routes.so_aso_user_id');
            }
            $query->whereIn($target_config['table'].'.id',$ids);
//            $query->orderBy($target_config['table'].'.ordering');
            $data = $query->get()->toArray();
            //debug($data,1);
            return $data;

    }

    public static function primary_pending_orders($ids,$selected_date_range,$config,$ordersalemode)
    {
        $houseIds = array();
        $ordering = array();
        $date_range = explode(' - ',$selected_date_range[0]);
        $query = DB::table('order_details');
        $query->select(
            'distribution_houses.id',
            'orders.order_date',
            'order_details.short_name',
            DB::raw('sum(order_details.case) as q'),
            $config['table'].'.'.$config['field_name'].' as field_name',
            $config['table'].'.id as field_id'
        );
        $query->leftJoin('orders','orders.id','=','order_details.orders_id');
        $query->leftJoin('distribution_houses','distribution_houses.id','=','orders.dbid');

        if($config['type'] == 'regions')
        {
            $query->addSelect('zones.zone_name as zname');
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
            array_push($ordering,'zones.ordering');
        }

        if($config['type'] == 'territories')
        {
            $query->addSelect('zones.zone_name as zname','regions.region_name as rname');
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
            $query->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id');
            array_push($ordering,'zones.ordering');
            array_push($ordering,'regions.ordering');
        }

        if($config['type'] == 'house')
        {
            $query->addSelect('zones.zone_name as zname','regions.region_name as rname','territories.territory_name as tname');
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
            $query->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id');
            $query->leftJoin('territories', 'territories.id', '=', 'distribution_houses.territories_id');
            array_push($ordering,'zones.ordering');
            array_push($ordering,'regions.ordering');
            array_push($ordering,'territories.ordering');
        }

        if(($config['type'] == 'zones') || ($config['type'] == 'regions') || ($config['type'] == 'territories'))
        {
            $query->leftJoin($config['table'],$config['table'].'.id','=','distribution_houses.'.$config['type'].'_id');
        }

        $query->whereIn('orders.dbid',$ids);
        $query->whereBetween('orders.order_date',$date_range);

        $query->where('orders.order_type',$ordersalemode);
        $query->where('orders.order_status','Pending');

        if($config['type'] == 'date')
        {
            $query->groupBy($config['table'].'.'.$config['field_name'],'order_details.short_name');
            $houseIds = $ids;
        }
        else
        {
            $query->groupBy($config['table'].'.id','order_details.short_name');
        }

        foreach($ordering as $ord)
        {
            $query->orderBy($ord,'ASC');
        }


        $data = $query->get()->toArray();
//debug($data,1);
        $dataArray = array();
        foreach($data as $k=>$v)
        {
            $dataArray[$v->field_name]['table_id'] = $v->field_id;
            $dataArray[$v->field_name]['data'][$v->short_name] = $v->q;
            $dataArray[$v->field_name]['data_config']= array('config'=>$config,'date_range'=>$selected_date_range,'ids'=>$houseIds);
            $dataArray[$v->field_name]['parents'] = array(
                'zone'=>(isset($v->zname)?$v->zname:''),
                'region'=>(isset($v->rname)?$v->rname:''),
                'territory'=>(isset($v->tname)?$v->tname:''),
            );

        }
//        foreach($data as $k=>$v)
//        {
//            $dataArray['value'][$v->field_name][$v->short_name] = $v->q;
//            $dataArray['data'][$v->field_name] = $v->field_id;
//            $dataArray['data_config']= array('config'=>$config,'date_range'=>$selected_date_range,'ids'=>$houseIds);
//        }
        //debug($dataArray,1);
        return $dataArray;
    }

    public static function pending_orders_details($field_name,$field_id,$config_data)
    {
       // debug($config_data,1);
        $date_range = explode(' - ',$config_data['date_range'][0]);
        $query = DB::table('order_details');
        $query->select(
            'distribution_houses.id',
            'orders.id as oid',
            'orders.order_number',
            'orders.order_date',
            'order_details.short_name',
            'order_details.case as q',
            $config_data['config']['table'].'.'.$config_data['config']['field_name'].' as field_name',
            $config_data['config']['table'].'.id as field_id'
        );

        $query->leftJoin('orders','orders.id','=','order_details.orders_id');
        $query->leftJoin('distribution_houses','distribution_houses.id','=','orders.dbid');

        //for maping
        $query->addSelect('zones.zone_name as zname','regions.region_name as rname','territories.territory_name as tname','distribution_houses.point_name as hname');
        if($config_data['config']['type'] != 'zones')
        {
            $query->leftJoin('zones', 'zones.id', '=', 'distribution_houses.zones_id');
        }
        if($config_data['config']['type'] != 'regions')
        {
            $query->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id');
        }
        if($config_data['config']['type'] != 'territories')
        {
            $query->leftJoin('territories', 'territories.id', '=', 'distribution_houses.territories_id');
        }




        if(($config_data['config']['type'] == 'zones') || ($config_data['config']['type'] == 'regions') || ($config_data['config']['type'] == 'territories') || ($config_data['config']['type'] == 'territories'))
        {
            $query->leftJoin($config_data['config']['table'],$config_data['config']['table'].'.id','=','distribution_houses.'.$config_data['config']['type'].'_id');
            $query->where('distribution_houses.'.$config_data['config']['field_id'],$field_id);
        }
        else if($config_data['config']['type'] == 'date')
        {
            $query->whereIn('orders.dbid',$config_data['ids']);
            $query->where('orders.order_date',$field_name);
        }
        else
        {
            $query->whereBetween('orders.order_date',$date_range);
        }


        $query->where('orders.order_type','Primary');
        $query->where('orders.order_status','Pending');
        $query->groupBy('orders.id','order_details.short_name');
        $data = $query->get()->toArray();
       // debug($data,1);
        $dataArray = array();
        //$data = array();
        foreach($data as $k=>$v)
        {
            $dataArray[$v->oid]['order_number'] = $v->order_number;
            $dataArray[$v->oid]['order_date'] = $v->order_date;
            $dataArray[$v->oid]['data'][$v->short_name] = $v->q;
            $dataArray[$v->oid]['parents'] = array(
                'zone'=>(isset($v->zname)?$v->zname:''),
                'region'=>(isset($v->rname)?$v->rname:''),
                'territory'=>(isset($v->tname)?$v->tname:''),
                'house'=>(isset($v->hname)?$v->hname:'')
            );
        }
        return $dataArray;
    }
}
