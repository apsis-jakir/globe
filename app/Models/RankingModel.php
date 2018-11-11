<?php
/**
 * Created by PhpStorm.
 * User: shabbir
 * Date: 9/25/2018
 * Time: 3:43 PM
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class RankingModel
{
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
        dd($response);
        return $response;
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

}