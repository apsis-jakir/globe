<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class LiftingModel extends Model
{
    public static function organizeDate($array1, $array2, $key)
    {
        $result = Array();
        foreach ($array1 as $key_1 => &$value_1) {
            foreach ($array2 as $key_1 => $value_2) {
                if ($value_1[$key] == $value_2[$key]) {
                    if (array_key_exists('NULL', $value_1)) {
                        unset($value_1['NULL']);
                    }
                    if (array_key_exists('NULL', $value_2)) {
                        unset($value_2['NULL']);
                    }
                    $result[] = array_merge($value_1, $value_2);
                }
            }

        }
        return $result;
    }

    public static function executeQuery($ids, $date_range, $order, $sale, $type)
    {
        $date = array_map('trim', explode(" - ", $date_range[0]));
        $p = "";
        $q = "";
        $order_by = "";

        $da = "";
        $current_balance = "";
        $lifting = "";
        if ($type == 'date') {
            $p .= "orders.order_date as odate";
            $q .= "sales.order_date as odate";
            $order_by = "odate";

            //deposit
            $da .= "(SELECT Sum( orders.order_da ) FROM orders WHERE orders.dbid = distribution_houses_info.id AND orders.order_date = " . $order_by . " 
            AND orders.order_status='Processed' ORDER BY orders.order_date DESC)  AS deposit";
            $current_balance .= "(SELECT sales.house_current_balance FROM sales WHERE sales.dbid = distribution_houses_info.id AND sales.order_date = " . $order_by . " AND sales.sale_status='Processed' AND sales.sale_type='Primary' ORDER BY sales.id DESC LIMIT 1) as balance";
            $lifting .= "(SELECT Sum( sales.total_sale_amount) FROM sales WHERE sales.dbid = distribution_houses_info.id AND sales.order_date = " . $order_by . " AND sales.sale_status='Processed') as amount";


        } else {
            $p .= "distribution_houses_info." . $type . "_name";
            $order_by = "distribution_houses_info." . $type . "_name";

            //deposit
            $da .= "(SELECT Sum( orders.order_da ) FROM orders WHERE orders.dbid = distribution_houses_info.id AND orders.order_date between '" . $date[0] . "' AND '" . $date[1] . "' AND orders.order_status='Processed' ORDER BY orders.order_date DESC)  AS deposit";
            $current_balance .= "(SELECT sales.house_current_balance FROM sales WHERE sales.dbid = distribution_houses_info.id AND sales.order_date between '" . $date[0] . "' AND '" . $date[1] . "' AND sales.sale_status='Processed' AND sales.sale_type='Primary' ORDER BY sales.id DESC LIMIT 1) as balance";
            $lifting .= "(SELECT Sum( sales.total_sale_amount) FROM sales WHERE sales.dbid = distribution_houses_info.id AND sales.order_date between '" . $date[0] . "' AND '" . $date[1] . "' AND sales.sale_status='Processed') as amount";
        }


        $order = $data = DB::table('distribution_houses_info')
            ->select(
                'distribution_houses_info.id',
                $p,
                DB::raw(substr($order, 0, -1)),
                DB::raw($da),
                DB::raw('NULL as balance'),
                DB::raw('NULL as amount'),
                DB::raw('NULL as zname'),
                DB::raw('NULL as rname'),
                DB::raw('NULL as tname'),
                DB::raw('NULL as pname')

            )
            ->leftJoin('orders', function ($join) use ($date_range) {
                $join->on('orders.dbid', '=', 'distribution_houses_info.id')
                    ->where('orders.order_status', 'Processed')
                    ->where('orders.order_type', 'Primary')
                    ->whereBetween('orders.order_date', array_map('trim', explode(" - ", $date_range[0])));
            })
            ->leftJoin('order_details', 'order_details.orders_id', '=', 'orders.id')
            ->whereIn('distribution_houses_info.id', $ids)
            ->groupBy($order_by)
            ->orderBy('orders.id', 'DESC')->get()->toArray();
        $sale = $data = DB::table('distribution_houses_info')
            ->select(
                $type == 'date' ? $q : $p,
                DB::raw(substr($sale, 0, -1)),
                DB::raw('NULL'),
                DB::raw($current_balance),
                DB::raw($lifting),
                'distribution_houses_info.zone_name as zname',
                'distribution_houses_info.region_name as rname',
                'distribution_houses_info.territory_name as tname',
                'distribution_houses_info.house_name as pname'


            )
            ->leftJoin('sales', function ($join) use ($date_range) {
                $join->on('sales.dbid', '=', 'distribution_houses_info.id')
                    ->where('sales.sale_status', 'Processed')
                    ->where('sales.sale_type', 'Primary')
                    ->whereBetween('sales.order_date', array_map('trim', explode(" - ", $date_range[0])));
            })
            ->leftJoin('sale_details', 'sale_details.sales_id', '=', 'sales.id')
            ->whereIn('distribution_houses_info.id', $ids)
            ->groupBy($order_by)
            ->orderBy('sales.id', 'DESC')->get()->toArray();
        $order = json_decode(json_encode($order), true);
        $sale = json_decode(json_encode($sale), true);
        $data = self::organizeDate($order, $sale, $type != 'date' ? $type . "_name" : $order_by);
        return $data;
    }

    private static function getCaseConvert($data, $key)
    {
        $result = [];
        foreach ($data as $k => $v) {
            $exp_key = explode('_', $k);
            if ($exp_key[0] == $key) {
                $result[$exp_key[1]] = number_format(convert_to_case_value($exp_key[1], $v), 2);
            }
        }
        return $result;
    }

    private static function getTotalEachSku($data, $indentifier)
    {
        $result = [];
        foreach ($data as $key => $each_date) {
            foreach ($each_date as $k => $v) {
                $exp_key = explode('_', $k);
                if ($exp_key[0] == $indentifier) {
                    $result[$exp_key[1]] = isset($result[$exp_key[1]]) ? $result[$exp_key[1]] + $v : $v;
                }
            }
        }

        foreach ($result as $key => $value) {
            $result[$key] = number_format(convert_to_case_value($key, $value), 2);
        }
        return $result;
    }

    private static function getTotalEach($data,&$result)
    {
        $result['total']['deposit']=0;
        $result['total']['amount']=0;
        $result['total']['balance']=0;
        foreach ($data as $key => $each_date) {
                $result['total']['deposit']+= $each_date['deposit'];
                $result['total']['amount']+= $each_date['amount'];
                $result['total']['balance']+= $each_date['balance'];

        }
    }

    public static function getLifting($ids, $data_range, $type, $selected_memo, $link = true)
    {
        $list_key = "";
        if ($type == 'date') {
            $list_key = 'odate';
        } else {
            $list_key = $type . "_name";
        }

        $order = "";
        $sale = "";
        $count = 0;
        foreach ($selected_memo as $cat_key => $cat_val) {
            $selected_skues = array_flatten($cat_val);
            foreach ($selected_skues as $key => $value) {
                $order .= 'SUM(CASE WHEN order_details.short_name ="' . $value . '" THEN order_details.quantity ELSE 0 END ) AS `order_' . $value . '`,';
                $sale .= 'SUM(CASE WHEN sale_details.short_name ="' . $value . '" THEN sale_details.quantity ELSE 0 END ) AS `sale_' . $value . '`,';
                $count++;
            }
        }

        $get_data = self::executeQuery($ids, $data_range, $order, $sale, $type);
        //debug($get_data,1);
        $response = [];

        foreach ($get_data as $key => $value) {
            if ($link) {
                $response[$value[$list_key]]['link'] = URL::to('house-lifting-format-date/' . http_build_query([$value['id']]) . '/' . $type . '/' . http_build_query($data_range) . '/' . http_build_query($selected_memo));
            } else {
                $response[$value[$list_key]]['link'] = "//";
            }
            $response[$value[$list_key]]['req'] = self::getCaseConvert($value, 'order');
            $response[$value[$list_key]]['del'] = self::getCaseConvert($value, 'sale');
            $response[$value[$list_key]]['deposit'] = $value['deposit'];
            $response[$value[$list_key]]['balance'] = $value['balance'];
            $response[$value[$list_key]]['amount'] = $value['amount'];
            $response[$value[$list_key]]['zone'] = $value['zname'];
            $response[$value[$list_key]]['region'] = $value['rname'];
            $response[$value[$list_key]]['territory'] = $value['tname'];
            $response[$value[$list_key]]['house'] = $value['pname'];

        }
        $response['total']['req'] = self::getTotalEachSku($get_data, 'order');
        $response['total']['del'] = self::getTotalEachSku($get_data, 'sale');
        self::getTotalEach($get_data,$response);
        return $response;

    }
}
