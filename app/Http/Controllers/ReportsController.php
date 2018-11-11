<?php

namespace App\Http\Controllers;

//use App\Models\Ordering;
use App\Models\DistributionHouse;
use App\Models\OrderDetail;
use App\Models\Reports;
use App\Models\Routes;
use App\Models\Stocks;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use reportsHelper;
use Symfony\Component\Console\Helper\Helper;
use App\Models\Menu;
use App\Models\User;

//use Carbon\Carbon;

//use URL;

class ReportsController extends Controller
{
    private $routes;

    public function __construct()
    {
        $this->routes = json_decode(Session::get('routes_list'), true);
        $this->middleware('auth');
        DB::enableQueryLog();
    }

    public function order_list($type = null)
    {
        $data['type'] = $type;
        if ($type == 'primary') {
            $data['pageTitle'] = 'Primary Order List';
        } else if ($type == 'secondary') {
            $data['pageTitle'] = 'Secondary Order List';
        }
        $data['breadcrumb'] = breadcrumb(array('active' => ucfirst($type) . ' Order List'));
        $data['ajaxUrl'] = URL::to('orderListAjax/' . $type);
        $data['searching_options'] = 'grid.search_elements_all';
        //$data['searching_options'] = 'grid.search_elements_all_single';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'daterange'));
//        $data['searching_options'] = 'reports.order_list_search';
        $data['searching_options'] = 'grid.search_elements_all';

        //debug($this->routes);
        $defaultPost = array(
            'created_at' => Array(date('Y-m-d') . ' - ' . date('Y-m-d'))
        );
        $data['orders'] = reportsHelper::order_list_query($type, $defaultPost, $this->routes);
        $data['aso_name'] = DB::table('orders')
            ->select('requester_name')
            ->groupBy('requester_name')->get();

        $data['houses'] = DB::table('orders')
            ->select('dh_name')
            ->groupBy('dh_name')->get();

        $data['routes'] = DB::table('orders')
            ->select('route_name')
            ->groupBy('route_name')->get();
        return view('reports.order_list', $data);
    }

    public function check_distribution_balack(Request $request)
    {
        $post = $request->all();
        $price = reportsHelper::getDistributorCurrentBalance($post);
        echo $price;
    }

    public function order_list_ajax(Request $request, $type = null)
    {
        $post = $request->all();
        $data['type'] = $type;
        $data['orders'] = reportsHelper::order_list_query($type, $post, $this->routes);

        return view('reports.order_list_ajax', $data);
    }

    public function salesList($type)
    {
        $data['pageTitle'] = ucfirst($type) . ' Sales List';
        $data['type'] = $type;
        $data['ajaxUrl'] = URL::to('salesListAjax/' . $type);
//        $data['searching_options'] = 'reports.sales_list_search';
//        $data['searching_options'] = 'grid.search_elements_all_single';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'daterange'));
        $data['breadcrumb'] = breadcrumb(array('active' => ucfirst($type) . ' Sales List'));

        //$data['sales'] = DB::table('sales')->get();
        $defaultPost = array(
            'created_at' => Array(date('Y-m-d') . ' - ' . date('Y-m-d')),
            'id' => getHouseFromThisRoutes(json_decode(Session::get('routes_list'), true))
        );
        $data['sales'] = reportsHelper::sales_list_query($type, $defaultPost);
        return view('reports.sales_list', $data);
    }

    public function sales_list_ajax(Request $request, $type = null)
    {
        $post = $request->all();
        $data['type'] = $type;
        $data['sales'] = reportsHelper::sales_list_query($type, $post);
        //debug($data['sales'],1);
        return view('reports.sales_list_ajax', $data);
    }

    public function salesDetails($type, $id)
    {
        $data['type'] = $type;
        $data['pageTitle'] = ucfirst($type) . ' Sales Details';
        $data['breadcrumb'] = breadcrumb(array('Reports' => 'sales-list/' . $type, 'active' => ucfirst($type) . ' Sales Details'));

        $data['sales_info'] = DB::table('sales')
           ->select('sales.*', 'orders.order_da','orders.order_amount', 'distribution_houses.current_balance', 'distribution_houses.market_name', 'distribution_houses.point_name', 'distribution_houses.current_balance')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'sales.dbid')
            ->leftJoin('orders', function ($join) {
                $join->on('orders.id', '=', 'sales.order_id')
                     ->where('orders.order_status','=','Processed');
            })
            ->where('sales.id', $id)->first();

        $data['sales'] = DB::table('skues')
            ->select('skues.id as sid', 'sale_details.*', 'order_details.quantity as order_quantity', 'skues.sku_name', 'brands.brand_name')
            ->leftJoin('sale_details', function ($join) use ($id) {
                $join->on('sale_details.short_name', '=', 'skues.short_name')
                    ->where('sale_details.sales_id', $id);
            })
            ->leftJoin('sales', function ($join2) {
                $join2->on('sales.id', '=', 'sale_details.sales_id')
                    ->where('sales.sale_status', 'Processed');
            })
            ->leftJoin('orders', function ($join) {
                $join->on('orders.id', '=', 'sales.order_id')
                    ->where('orders.order_status','=','Processed');
            })
            ->leftJoin('order_details', function ($join) {
                $join->on('order_details.orders_id', '=', 'orders.id')
                    ->on('order_details.short_name', '=', 'sale_details.short_name');
            })
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')->groupBy('sale_details.short_name')->get();

        $data['memo'] = memoStructure();
        if ($type == 'secondary') {
            return view('reports.sale_details_secondary', $data);
        }
        if ($type == 'primary') {
            return view('reports.sales_details', $data);
        }
    }

    public function primary_order_details($type, $id)
    {
        //debug(Auth::user()->user_type,1);
        $data['type'] = $type;
        $data['breadcrumb'] = breadcrumb(array('Reports' => 'order-list/' . $type, 'active' => ucfirst($type) . ' Order List'));
        $data['orders_info'] = DB::table('orders')
            ->select('orders.*', 'distribution_houses.current_balance', 'distribution_houses.market_name', 'distribution_houses.point_name', 'distribution_houses.current_balance')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
            ->where('orders.id', $id)->first();

        $data['orders'] = DB::table('order_details')
            ->select('skues.id as sid', 'order_details.*', 'skues.sku_name', 'brands.brand_name', 'order_details.no_of_memo as sku_memo')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('skues', 'skues.short_name', '=', 'order_details.short_name')
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
            ->where('order_details.orders_id', $id)->get();

        $data['memo'] = memoStructure();

        if ($type == 'primary') {
            return view('reports.order_details', $data);
        } else if ($type == 'secondary') {
            return view('reports.order_details_secondary', $data);
        }
    }

    public function getPackSizeQuanity(Request $request)
    {
        $post = $request->all();
        return sku_pack_quantity($post['sku'], $post['quantity']);
    }

    private function getTotalAmount($post)
    {
        $total_order_amount = 0;
        foreach ($post['quantity'] as $k => $q) {
            //$total_order_amount += (sku_pack_quantity($k,$q)*$post['price'][$k]);
            $total_order_amount += ($q * $post['price'][$k]);
        }
        return $total_order_amount;

    }

   public function totalCaseCount($data, &$count_sku)
    {
        $group1 = 0;
        $group2 = 0;
        $group3 = 0;
        $group4 = 0;
        $group5 = 0;
        foreach ($data as $key => $value) {
            $total = sku_pack_quantity($key, $value);
            switch (get_pack_size($key)) {
                case 24:
                    $group1 += $total;
                    if ($total > 0) {
                        $count_sku++;
                    }
                    break;
                case 12:
                    $group2 += $total;
                    if ($total > 0) {
                        $count_sku++;
                    }
                    break;
                case 9:
                    $group3 += $total;
                    if ($total > 0) {
                        $count_sku++;
                    }
                    break;
                case 6:
                    $group4 += $total;
                    if ($total > 0) {
                        $count_sku++;
                    }
                    break;
                case 36:
                    $group5 += $total;
                    if ($total > 0) {
                        $count_sku++;
                    }
                    break;
            }
        }
        return convert_to_case($group1, 24) + convert_to_case($group2, 12) + convert_to_case($group3, 9) + convert_to_case($group4, 6) + convert_to_case($group5, 36);
    }

    private function rejectPreviousPrimary($post, &$previous_total, &$previous_value, &$previous_data)
    {
        $id = $post['id'];
        //$previous_data = getPreviousStockByAsoDate($post['asm_rsm_id'], $post['order_date'], 0, $post['dh_id'], 'Primary');
        $previous_data = getPreviousStockById($id, 'Primary');
        if (!empty($previous_data['data'])) {
            foreach ($previous_data['data'] as $key => $value) {
                $previous_value[$key] = $value;
                $previous_total += $value * get_sku_price($key);
            }
            return $previous_value;
        }
        return $previous_value;
    }

    private function rejectPreviousSecondary($post, &$previous_total, &$previous_value, &$previous_data){
        $id = $post['id'];
        //$previous_data = getPreviousStockByAsoDate($post['aso_id'], $post['order_date'], 0, $post['dh_id']);
        $previous_data = getPreviousStockById($id);
        if (!empty($previous_data['data'])) {
            foreach ($previous_data['data'] as $key => $value) {
                $previous_value[$key] = $value;
                $previous_total += $value * get_sku_price($key);
            }
            return $previous_value;
        }
        return $previous_value;
    }

    public function primary_sales_create(Request $request)
    {
        $post = $request->all();
        $sale_total_sku = 0;
        $total_case_count = $this->totalCaseCount($post['quantity'], $sale_total_sku);
        $salesdata = array(
            'asm_rsm_id' => $post['asm_rsm_id'],
            'dbid' => $post['dh_id'],
            'order_number'=>get_generated_code('PO'),
            'order_id' => $post['order_id'],
            'order_date' => $post['order_date'],
            'sale_date' => $post['order_date'],
            'sender_name' => $post['sender_name'],
            'sender_phone' => $post['sender_phone'],
            'dh_name' => $post['dh_name'],
            'dh_phone' => $post['dh_phone'],
            'sale_type' => 'Primary',
            'house_current_balance' => ($post['current_balance'] + $post['order_da']) - $this->getTotalAmount($post),
            'sale_total_sku' => $sale_total_sku,
            'sale_total_case' => $total_case_count,
            'created_by' => Auth::id()
        );
        $previous_total = 0;
        $previous_value = [];
        if ($present_value = $this->rejectPreviousPrimary($post, $previous_total, $previous_value, $previous_data)) {
            $salesdata['house_current_balance'] = ($salesdata['house_current_balance'] + $previous_data['additional']['sale_total']) - $previous_data['additional']['previous_da'];
            DB::table('sales')->where('id', $previous_data['additional']['sales_id'])->update(['sale_status' => 'Rejected']);
        }
        //------------------------------
        $sale_id = DB::table('sales')->insertGetId($salesdata);
        $sales_details_data = [];
        $present_value = [];
        $total_sale_amount = 0;
        foreach ($post['quantity'] as $k => $q) {
            $present_value[$k] = sku_pack_quantity($k, $q);
            if ((float)$q > 0) {
                //$total_sale_amount +=(sku_pack_quantity($k,$q)*$post['price'][$k]);
                $total_sale_amount += ($q * $post['price'][$k]);
                $sales_details_data['sales_id'] = $sale_id;
                $sales_details_data['short_name'] = $k;
                $sales_details_data['quantity'] = sku_pack_quantity($k, $q);
                $sales_details_data['case'] = $q;
                $sales_details_data['price'] = $post['price'][$k];
                $sales_details_data['created_by'] = Auth::id();
                DB::table('sale_details')->insert($sales_details_data);
            }

        }

        DB::table('orders')->where('id', $post['order_id'])->update(['order_status' => 'Processed']);
        DB::table('orders')->where('id', $post['order_id'])->update(['order_da' => $post['order_da']]);
        DB::table('sales')->where('id', $sale_id)->update(['total_sale_amount' => $total_sale_amount,'deposit_amount'=>$post['order_da']]);
        $current_balance = $salesdata['house_current_balance'];///+$total_sale_amount;
        DB::table('distribution_houses')->where('id', $post['dh_id'])->update(['current_balance' => $current_balance]);

        stock_update($post['dh_id'], $present_value, $total_sale_amount, $previous_value, $previous_total,$post['order_date'], true);

        if(isset($post['redirect']) && $post['redirect'] == 'order'){
            return redirect('order-list/primary')->with('success', 'Information has been added.');
        }
        else{
            return redirect('sales_list/primary')->with('success', 'Information has been added.');
        }
    }

    public function secondary_sales_create(Request $request){
        $post = $request->all();
        $sale_total_sku = 0;
        $total_case_count = $this->totalCaseCount($post['quantity'], $sale_total_sku);
        $salesdata = array(
            'aso_id' => $post['aso_id'],
            'order_number'=>get_generated_code('ASO'),
            'dbid' => $post['dh_id'],
            'order_id' => $post['order_id'],
            'order_date' => $post['order_date'],
            'sale_date' => $post['order_date'],
            'sender_name' => $post['sender_name'],
            'sender_phone' => $post['sender_phone'],
            'dh_name' => $post['dh_name'],
            'dh_phone' => $post['dh_phone'],
            'sale_type' => 'Secondary',
            'sale_total_sku' => $sale_total_sku,
            'sale_total_case' => $total_case_count,
            'sale_route_id'=>$post['route_id'],
            'created_by' => Auth::id()
        );
        $previous_total = 0;
        $previous_value = [];
        if ($present_value = $this->rejectPreviousSecondary($post, $previous_total, $previous_value, $previous_data)) {
            DB::table('sales')->where('id', $previous_data['additional']['sales_id'])->update(['sale_status' => 'Rejected']);
        }

        //------------------------------
        $sale_id = DB::table('sales')->insertGetId($salesdata);
        $sales_details_data = [];
        $present_value = [];
        $total_sale_amount = 0;
        foreach ($post['quantity'] as $k => $q) {
            $present_value[$k] = sku_pack_quantity($k, $q);
//            if ((float)$q > 0) {
                //$total_sale_amount +=(sku_pack_quantity($k,$q)*$post['price'][$k]);
                $total_sale_amount += ($q * $post['price'][$k]);
                $sales_details_data['sales_id'] = $sale_id;
                $sales_details_data['short_name'] = $k;
                $sales_details_data['quantity'] = sku_pack_quantity($k, $q);
                $sales_details_data['case'] = $q;
                $sales_details_data['price'] = $post['price'][$k];
                $sales_details_data['created_by'] = Auth::id();
                DB::table('sale_details')->insert($sales_details_data);
//            }

        }

        DB::table('orders')->where('id', $post['order_id'])->update(['order_status' => 'Processed']);
        DB::table('sales')->where('id', $sale_id)->update(['total_sale_amount' => $total_sale_amount]);

        stock_update($post['dh_id'], $present_value, $total_sale_amount, $previous_value, $previous_total,$post['order_date']);

        return redirect('sales_list/secondary')->with('success', 'Information has been added.');
    }


    public function updateSecondaryOrder(Request $request)
    {
        $post = $request->all();
        foreach ($post['quantity'] as $k => $q) {
            OrderDetail::updateOrCreate([
                'orders_id' => $post['order_id'],
                'short_name' => $k

            ], [
                'case' => $q,
                'quantity' => sku_pack_quantity($k, $q),
                'price' => get_case_price($k, false),
                'no_of_memo' => $post['memo'][$k]
            ]);

        }
        DB::table('orders')->where('id', $post['order_id'])->update(['order_status' => 'Edited']);

        return redirect('order-list/secondary')->with('success', 'Information has been added.');
    }

    public function order_vs_sale_primary()
    {
        $data['ajaxUrl'] = URL::to('salesListAjax');
        $data['searching_options'] = 'reports.sales_list_search';

        $data['ordervssale'] = DB::table('order_details')
            ->select('distribution_houses.point_name', 'orders.id as oid', 'orders.requester_name', 'orders.order_date', 'orders.dh_name', 'orders.order_date', 'brands.brand_name', 'skues.sku_name', 'order_details.short_name', 'order_details.quantity', 'sales.sale_date', 'sale_details.quantity as salequantity')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.asm_rsm_id', '=', 'orders.asm_rsm_id')
                    ->on('sales.order_date', '=', 'orders.order_date');
            })
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.sales_id', '=', 'sales.id')
                    ->on('sale_details.short_name', '=', 'order_details.short_name');
            })
            ->leftJoin('skues', 'skues.short_name', '=', 'order_details.short_name')
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'orders.dbid')
            ->where('orders.order_type', 'Primary')
            ->orderBy('orders.id', 'DESC')->get();
        //dd(DB::getQueryLog());
//        debug($data['ordervssale'],1);
        return view('reports.order_vs_sale_primary', $data);
    }

    public function currentStock()
    {
        $data['ajaxUrl'] = URL::to('current-stock-search');
        $data['searching_options'] = 'reports.sales_list_search';

        $data['current_stocks'] = DB::table('stocks')
            ->select('stocks.quantity', 'distribution_houses.market_name', 'skues.sku_name', 'brands.brand_name')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'stocks.distributions_house_id')
            ->leftJoin('skues', 'skues.short_name', '=', 'stocks.short_name')
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')->get();
        return view('reports.current_stock', $data);
    }

    public function currentStockSearch(Request $request)
    {
        $data['current_stocks'] = DB::table('stocks')
            ->select('stocks.quantity', 'distribution_houses.market_name', 'skues.sku_name', 'brands.brand_name')
            ->leftJoin('distribution_houses', 'distribution_houses.id', '=', 'stocks.distributions_house_id')
            ->leftJoin('skues', 'skues.short_name', '=', 'stocks.short_name')
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')->get();
        return view('reports.current_stock_ajax', $data);
    }

    //House Stock
    public function houseStock(Request $request)
    {
//        debug(Auth::user(),1);
        $data['ajaxUrl'] = URL::to('house-stock-search');
        $data['view'] = 'house_stock_ajax';
        $data['header_level'] = 'Current Stock';
        $data['view_report'] = 'House';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'view-report'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 1;
        $data['level_col_data'] = [];
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'House Wise Stock'));
        return view('reports.main', $data);
    }

    //House Stock Search
    public function houseStockSearch(Request $request)
    {
        //debug(getHouseFromThisRoutes($this->routes),1);
        $data['ajaxUrl'] = URL::to('house-stock-search');
        $data['searching_options'] = 'grid.search_elements_all';
        $post = $request->all();

        unset($post['_token']);
        $request_data = filter_array($post);

        $data['view_report'] = ucwords($post['view_report'][0]);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $memo = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['memo_structure'] = $memo;
        //$data['memo_structure1']= $memo;
        $data['level'] = 1;
        $data['level_col_data'] = [];

        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);

        $view_report_ids = array(
            'ids' => $selected_houses,
            'table' => 'distribution_houses',
            'view_report_field_name' => 'point_name',
            'db_join_field_name' => 'id',
            'selected_house' => $selected_houses
        );

        if ($post['view_report'][0] == 'zone') {
            $info = Reports::getInfo($zone_ids, [], [], []);
            $houses = array_unique(array_column($info, 'distribution_house_id'), SORT_REGULAR);
            $houses = array_filter($houses);
            $view_report_ids = array(
                'ids' => $zone_ids,
                'table' => 'zones',
                'view_report_field_name' => 'zone_name',
                'db_join_field_name' => 'zones_id',
                'selected_house' => $houses
            );
        } else if ($post['view_report'][0] == 'region') {
            $info = Reports::getInfo([], $region_ids, [], []);
            $houses = array_unique(array_column($info, 'distribution_house_id'), SORT_REGULAR);
            $houses = array_filter($houses);
            $view_report_ids = array(
                'ids' => $region_ids,
                'table' => 'regions',
                'view_report_field_name' => 'region_name',
                'db_join_field_name' => 'regions_id',
                'selected_house' => $houses
            );
        } else if ($post['view_report'][0] == 'territory') {
            $info = Reports::getInfo([], [], $territory_ids, []);
            $houses = array_unique(array_column($info, 'distribution_house_id'), SORT_REGULAR);
            $houses = array_filter($houses);
            $view_report_ids = array(
                'ids' => $territory_ids,
                'table' => 'territories',
                'view_report_field_name' => 'territory_name',
                'db_join_field_name' => 'territories_id',
                'selected_house' => $houses
            );
        }

        $data['stock_list'] = Reports::getHouseStockInfo($view_report_ids, $memo);
        //debug($data['stock_list'],1);

        return view('reports.ajax.house_stock_ajax', $data);

    }
    //House Stock Memo
//    public function houseStockMemo($house_id){
//        $data['house_info'] = DistributionHouse::where('id',$house_id)->first();
//        $data['stocks'] = Stocks::where('distributions_house_id',$house_id)->get(['short_name','quantity'])->toArray();
//        $data['memo'] = memoStructure();
//        return view('reports.memo.house_stock',$data);
//    }
    public function houseStockMemo($id, $table, $field)
    {
        //dd($table);
        $hquery = DB::table($table)->where('id', $id)->first();
        $memo_title = '';
        if ($table == 'zones') {
            $memo_title .= "<h3>" . $hquery->zone_name . "</h3>";
            $memo_title .= "<h3>Zone Code - " . $hquery->code . "</h3>";
        } else if ($table == 'regions') {
            $memo_title .= "<h3>" . $hquery->region_name . "</h3>";
            $memo_title .= "<h3>Region Code - " . $hquery->region_code . "</h3>";
        } else if ($table == 'territories') {
            $memo_title .= "<h3>" . $hquery->territory_name . "</h3>";
            $memo_title .= "<h3>Territory Code - " . $hquery->territory_code . "</h3>";
        } else if ($table == 'distribution_houses') {
            $memo_title .= "<h3>" . $hquery->point_name . ' - ' . $hquery->market_name . "</h3>";
            $memo_title .= "<h5>Proprietor Name : " . $hquery->propietor_name . "</h5>";
            $memo_title .= "<h5>House Code : " . $hquery->code . "</h5>";
        }
        $data['memo_title'] = $memo_title;

        //$data['house_info'] = DistributionHouse::where('id',$id)->first();


        $dquery = Stocks::select('short_name', DB::raw('sum(quantity) as squantity'));
        $dquery->whereIn('distributions_house_id', Reports::dbStockHouse($field, $id));
        $dquery->groupBy('short_name');
        $data['stocks'] = $dquery->get()->toArray();


        //$data['stocks'] = Stocks::where('distributions_house_id',$id)->get(['short_name','quantity'])->toArray();

        //dd($dataa,$data['stocks']);

        $data['memo'] = memoStructure();
        return view('reports.memo.house_stock', $data);
    }

    public function saleConciliation(Request $request)
    {
        $data['ajaxUrl'] = URL::to('monthly-sale-reconciliation-search');
        $data['view'] = 'sale_reconciliation_ajax';
        $data['header_level'] = 'Monthly Sale And Reconciliation';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'daterange'));
        $data['memo_structure'] = repoStructure();
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Monthly Sale And Reconciliation'));
        return view('reports.main', $data);
    }

    public function saleConciliationSearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('monthly-sale-reconciliation-search');
        $data['view'] = 'sale_reconciliation_ajax';
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);
        //Memo
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Monthly Sale And Reconciliation'));


        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);

        $data['monthly_sale_reconciliation'] = Reports::getMonthlySaleReconciliation($selected_houses, $data['memo_structure'], $selected_date_range);
        return view('reports.ajax.sale_reconciliation_ajax', $data);

    }


    public function houseLifting(Request $request)
    {
        $data['ajaxUrl'] = URL::to('house-lifting-search');
        $data['view'] = 'house_lifting_ajax';
        $data['header_level'] = ' House Wise Lifting';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('ranking_report', 'show', 'aso', 'month', 'year', 'datepicker', 'dss_report_type'));
        $memo = repoStructure();
        $data['level'] = 2;
        $data['level_col_data'] = ['Requested', 'Delivery'];
        $data['memo_structure'] = $memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'House Wise Lifting'));
        return view('reports.main', $data);
    }

    public function houseLiftingSearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('house-lifting-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];


        $data['house_lifting_list'] = Reports::getHouseLifting($selected_houses, $data['memo_structure'], $selected_date_range);


        return view('reports.ajax.house_lifting_ajax', $data);

    }

    public function houseLiftingFormat(Request $request)
    {
        $data['ajaxUrl'] = URL::to('house-lifting-format-search');
        $data['view'] = 'house_lifting_format_ajax';
        $data['header_level'] = ' House Wise Lifting';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('ranking_report', 'show', 'aso', 'month', 'year', 'datepicker', 'dss_report_type'));
        $memo = repoStructure();
        $data['level'] = 2;
        $data['level_col_data'] = ['Requested', 'Delivery'];
        $data['memo_structure'] = $memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'House Wise Lifting'));
        return view('reports.main', $data);
    }

    public function houseLiftingFormatSearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('house-lifting-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 1;
        $data['level_col_data'] = [];


        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];


        $data['house_lifting_list'] = Reports::getHouseLiftingFormat($selected_houses, $data['memo_structure'], $selected_date_range);
        //dd($data['house_lifting_list']);


        return view('reports.ajax.house_lifting_format_ajax', $data);

    }


    public function houseWisePerformance(Request $request)
    {
        $data['ajaxUrl'] = URL::to('db-wise-performance-search');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'db_wise_performance_ajax';
        $data['header_level'] = ' DB House Wise Performance';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'month'));
        $memo = repoStructure();
        $data['level'] = 3;
        $data['level_col_data'] = ['Target', 'Sales', 'Ach%'];
        $data['memo_structure'] = $memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'DB House Wise Performance'));
        return view('reports.main', $data);
    }

    public function houseWisePerformanceSearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('db-wise-performance-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);
        $data['postData'] = urlencode(serialize($request_data));;

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 3;
        $data['level_col_data'] = ['Target', 'Sales', 'Ach%'];

        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $selected_months = array_key_exists('month', $request_data) ? $request_data['month'] : [];
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);
        $data['house_wise_performance'] = Reports::houseWisePerformance($selected_houses, $data['memo_structure'], $selected_months);
//        debug($data['house_wise_performance'],1);

        return view('reports.ajax.db_wise_performance_ajax', $data);
    }

    public function routeWisePerformenceByCategory()
    {
        $data['ajaxUrl'] = URL::to('route-wise-performence-by-category-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'route_wise_performence_by_category_ajax';
        $data['header_level'] = 'Route Wise Performence By Category';
        $data['searchAreaOption'] = searchAreaOption(array('ranking_report', 'show', 'daterange', 'year', 'datepicker', 'dss_report_type'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 3;
        $data['level_col_data'] = ['Target', 'Sale', 'Ach%'];
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Route Wise Performence By Category'));
        return view('reports.main', $data);
    }

    public function routeWisePerformenceByCategoryAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('route-wise-performence-by-category-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 3;
        $data['level_col_data'] = ['Target', 'Sales', 'Ach%'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $selected_months = array_key_exists('month', $request_data) ? $request_data['month'] : [];
        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }
        $data['route_wise_performance'] = Reports::routeWisePerformance($selected_route, $data['memo_structure'], $selected_months);

        return view('reports.ajax.route_wise_performence_by_category_ajax', $data);

    }


    public function routeWisePerformenceCategory($dbid = null, $post_data = null)
    {
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        if ($dbid) {

            $post = unserialize(urldecode($post_data));
            $selected_months = array_key_exists('month', $post) ? $post['month'] : [];
            $data['selectedMonths'] = urlencode(serialize($selected_months));
            $route_ids = array_column(Reports::getRouteInfoHouse(array($dbid)), 'id');
            $selected_route = Reports::getRouteInfoAso($route_ids);
            $data['route_wise_performance'] = Reports::routeWisePerformance2($selected_route, $data['memo_structure'], $selected_months);
        }
        $data['ajaxUrl'] = URL::to('route-wise-performence-category-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'route_wise_performence_category_ajax';
        $data['header_level'] = 'Route Wise Performence By Category';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'month'));

        $data['level'] = 3;
        $data['level_col_data'] = ['Target', 'Sale', 'Ach%'];
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Route Wise Performence By Category'));
        return view('reports.main', $data);
    }

    public function routeWisePerformenceCategoryAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('route-wise-performence-by-category-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);
        $asouserid = (isset($request_data['aso_id'])) ? $request_data['aso_id'] : [];
        $get_route_id = Routes::whereIn('so_aso_user_id', $asouserid)->get()->toArray();

//        debug(array_column($route_id,'id'),1);
        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 3;
        $data['level_col_data'] = ['Target', 'Sales', 'Ach%'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $route_ids = array_column($get_route_id, 'id');
        $selected_months = array_key_exists('month', $request_data) ? $request_data['month'] : [];
//        debug(json_encode($selected_months),1);
        //$data['selectedMonths'] = urlencode($selected_months);
        $data['selectedMonths'] = urlencode(serialize($selected_months));
//        debug(unserialize($data['selectedMonths']),1);
        if (count($route_ids) == 0) {
//            debug('d',1);
            $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoHouse($selected_houses);
        } else {
            $selected_route = Reports::getRouteInfoAso($route_ids);
        }
        $data['route_wise_performance'] = Reports::routeWisePerformance2($selected_route, $data['memo_structure'], $selected_months);
//        debug($data['route_wise_performance'],1);
        return view('reports.ajax.route_wise_performence_category_ajax', $data);

    }

    public function individualRoutePerformance($id, $month, $type = null)
    {
        $data['type'] = $type;
        $data['memu_structure'] = memoStructure();
        if ($type == 'house') {
            $data['header_level'] = 'House Performence';
            $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'House Performence'));
            $data['houseInfo'] = Reports::individual_house_info($id);

            $house[0]['id'] = $id;
            $house[0]['name'] = $data['houseInfo']->point_name;
            $selected_house = Reports::getHouseInfo($house);
            $selected_months = array($month);
            $data['route_wise_performance'] = Reports::routeWisePerformance4($selected_house, $data['memu_structure'], $selected_months);
            //debug($data['route_wise_performance'],1);
        } else {
            // if type null that means route wise
            $data['header_level'] = 'Route Performence';
            $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Route Wise Performence'));
            $data['routeInfo'] = Reports::individual_routes_info($id);

            $route_ids[0]['id'] = $id;
            $route_ids[0]['name'] = $data['routeInfo']->routes_name;
            //debug($route_ids,1);
            $selected_route = Reports::getRouteInfoAso($route_ids);
            $selected_months = unserialize(urldecode($month));
            $data['route_wise_performance'] = Reports::routeWisePerformance3($selected_route, $data['memu_structure'], $selected_months);
        }


        //debug($data['route_wise_performance'],1);


        return view('reports.route_wise_performance_individual', $data);
    }


    public function strikeRateByCategory(Request $request)
    {
        $data['ajaxUrl'] = URL::to('strike-rate-search');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'strike_rate_ajax';
        $data['header_level'] = 'Strike Rate By Category';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 5;
        $data['level_col_data'] = ['Prod', 'Avg/Mem', 'Vol/Mem', 'Prot', 'Bouc Call'];
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Strike Rate By Category'));
        return view('reports.main', $data);
    }

    public function strikeRateByCategoryAjax(Request $request)
    {

        $data['ajaxUrl'] = URL::to('strike-rate-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku'));
        $memo = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['memo_structure'] = $memo;
        $data['level'] = 5;
        $data['level_col_data'] = ['Prod', 'Avg/Mem', 'Vol/Mem', 'Port', 'Bouc Call'];

        //req

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $selected_months = array_key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }

        $data['route_wise_strike_rate'] = Reports::routeWiseStrikeRate($selected_route, $data['memo_structure'], $selected_months);

        //dd($data['route_wise_strike_rate']);


        return view('reports.ajax.strike_rate_ajax', $data);


    }

    public function monthlySaleReconcilation(Request $request)
    {
        $data['ajaxUrl'] = URL::to('monthly-sale-reconc-search');
        $data['searching_options'] = 'grid.search_elements_all';

        $data['searchAreaOption'] = searchAreaOption(array('ranking_report', 'show', 'aso', 'daterange', 'year', 'datepicker', 'dss_report_type'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 4;
        $data['level_col_data'] = ['Opening', 'Lifting', 'Sales', 'Closing Stock'];
        return view('reports.monthly_sale_recon', $data);
    }

    public function monthlySaleReconcilationAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('strike-rate-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['searchAreaOption'] = searchAreaOption(array('ranking_report', 'show', 'month', 'year', 'datepicker', 'dss_report_type'));
        $memo = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['memo_structure'] = $memo;
        $data['level'] = 4;
        $data['level_col_data'] = ['Opening', 'Lifting', 'Sales', 'Closing Stock'];

        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);
        $selected_months = array_key_exists('month', $request_data) ? $request_data['month'] : [];

        $data['house_wise_monthly_recon'] = monthly_sale_recon_by_house($selected_houses, $data['memo_structure'], $selected_months);

        return view('reports.monthly_sale_recon_ajax', $data);
    }

    public function saleSummaryMonth(Request $request)
    {
        $data['ajaxUrl'] = URL::to('sale-summary-month-search');
        $data['searching_options'] = 'grid.search_elements_all';
        //$data['searchAreaOption'] = array('show'=>1,'daterange'=>0);
        $data['searchAreaOption'] = searchAreaOption(array('ranking_report', 'show', 'year', 'datepicker', 'dss_report_type'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 4;
        $data['level_col_data'] = ['Target', 'RDT', 'Order', 'Sale', 'Cum Ach%'];
        return view('reports.sale_summary_by_month', $data);
    }

    public function saleSummaryMonthAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('sale-summary-month-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 4;
        $data['level_col_data'] = ['RDT', 'Order', 'Sale', 'Cum Ach%'];

        //Request
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $selected_months = array_key_exists('month', $request_data) ? $request_data['month'] : [];
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }

        $data['sale_summary_by_month'] = dailySaleSummaryByMonth($selected_route, $data['memo_structure'], $selected_months, $selected_date_range);


        return view('reports.sale_summary_by_month_ajax', $data);
    }

    public function orderVsSaleSecondary(Request $request)
    {
        $data['ajaxUrl'] = URL::to('order-vs-sale-secondary-search');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'order_vs_sale_secondary_ajax';
        $data['header_level'] = 'Order VS Sale Secondary';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'daterange'));
        $data['memo_structure'] = repoStructure();
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'order vs sale secondary'));
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];
        return view('reports.main', $data);
    }

    public function orderVsSaleSecondaryAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('order-vs-sale-secondary-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
//        debug($post,1);
        unset($post['_token']);
        $request_data = filter_array($post);
        $data['post_data'] = $post;
        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


        //Request

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }


        $data['order_vs_sale_secondary'] = orderVsSaleSecondary($selected_route, $data['memo_structure'], $selected_date_range);


        return view('reports.ajax.order_vs_sale_secondary_ajax', $data);
    }


    public function orderVsSaleSecondaryAso($dbid, $postdata)
    {
        $post = json_decode($postdata, true);
        $data['post_data'] = $post;
        $data['ajaxUrl'] = URL::to('order-vs-sale-secondary-aso-search/' . $dbid);
        $data['view'] = 'order_vs_sale_secondary_aso_ajax';
        $data['header_level'] = ' Order VS Sale Secondary By ASO';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'daterange'));
        $data['memo_structure'] = repoStructure();
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'order vs sale secondary by ASO'));
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


//        --------
        $request_data = filter_array($post);
        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];
        //Request
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : array('id' => $dbid);
        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo([], [], [], $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }
        $data['order_vs_sale_secondary'] = orderVsSaleSecondaryAso($selected_route, $data['memo_structure'], $selected_date_range);
//        --------


        return view('reports.main', $data);
    }

    public function orderVsSaleSecondaryAsoSearch(Request $request, $dbid = null)
    {
//        $data['ajaxUrl'] = URL::to('order-vs-sale-secondary-search');
//        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
//        debug($post,1);
        unset($post['_token']);
        $request_data = filter_array($post);
        $data['post_data'] = $post;
        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


        //Request

        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : array('id' => $dbid);
        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];

        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo([], [], [], $house_ids);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }

        $data['order_vs_sale_secondary'] = orderVsSaleSecondaryAso($selected_route, $data['memo_structure'], $selected_date_range);

        return view('reports.ajax.order_vs_sale_secondary_aso_ajax', $data);
    }


    public function orderVsSaleSecondaryRoute($aso_id, $postdata)
    {
        $post = json_decode($postdata, true);
        $data['post_data'] = $post;
        $data['ajaxUrl'] = URL::to('order-vs-sale-secondary-route-search/' . $aso_id);
        $data['view'] = 'order_vs_sale_secondary_route_ajax';
        $data['header_level'] = ' Order VS Sale Secondary By Route';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'daterange'));
        $data['memo_structure'] = repoStructure();
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'order vs sale secondary by Route'));
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


//        --------
        $request_data = filter_array($post);
        $data['post_data'] = $post;

        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


        //Request

        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : array('id' => $aso_id);

        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo([], [], [], []);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }

        $data['order_vs_sale_secondary'] = orderVsSaleSecondaryRoute($selected_route, $data['memo_structure'], $selected_date_range);
//        --------


        return view('reports.main', $data);
    }


    public function orderVsSaleSecondaryRouteSearch(Request $request, $aso_id = null)
    {
        $post = $request->all();

        unset($post['_token']);
        $request_data = filter_array($post);
        $data['post_data'] = $post;
//        debug($request_data,1);
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


        //Request

        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : array('id' => $aso_id);
//debug($route_ids,1);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo([], [], [], []);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }
//        debug($selected_route,1);
        $data['order_vs_sale_secondary'] = orderVsSaleSecondaryRoute($selected_route, $data['memo_structure'], $selected_date_range);
//        debug($data['order_vs_sale_secondary'],1);

        return view('reports.ajax.order_vs_sale_secondary_route_ajax', $data);
    }


    public function orderVsSaleSecondaryDate($aso_id, $route_id, $postdata)
    {
        $post = json_decode($postdata, true);
        $data['post_data'] = $post;
        $data['ajaxUrl'] = URL::to('order-vs-sale-secondary-date-search/' . $aso_id . '/' . $route_id);
        $data['view'] = 'order_vs_sale_secondary_date_ajax';
        $data['header_level'] = 'Order VS Sale Secondary By Date';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'daterange'));
        $data['memo_structure'] = repoStructure();
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'order vs sale secondary by Route'));
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


//        --------
        $request_data = filter_array($post);
        $data['post_data'] = $post;
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];

        //Request

        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : array('id' => $aso_id);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo([], [], [], []);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }
        $data['order_vs_sale_secondary'] = Reports::orderVsSaleSecondaryDate($route_id, $selected_route, $data['memo_structure'], $selected_date_range);

//        --------


        return view('reports.main', $data);
    }


    public function orderVsSaleSecondaryDateSearch(Request $request, $aso_id = null, $route_id = null)
    {
        $post = $request->all();
//        debug($route_id,1);
        unset($post['_token']);
        $request_data = filter_array($post);
        $data['post_data'] = $post;
//        debug($request_data,1);
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];


        //Request

        $route_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : array('id' => $aso_id);
//        debug($route_ids,1);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        if (count($route_ids) == 0) {
            $get_info = Reports::getInfo([], [], [], []);
            $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
            $selected_houses = array_filter($selected_houses);
            $selected_route = Reports::getRouteInfoByHouse($selected_houses);
        } else {
            $selected_route = Reports::getAsoInfoByIds($route_ids);
        }
//        debug($selected_route,1);
        //$data['order_vs_sale_secondary'] = orderVsSaleSecondaryRoute($selected_route, $data['memo_structure'],$selected_date_range);
        $data['order_vs_sale_secondary'] = Reports::orderVsSaleSecondaryDate($route_id, $selected_route, $data['memo_structure'], $selected_date_range);

        return view('reports.ajax.order_vs_sale_secondary_date_ajax', $data);
    }


    //primary order vs sale
    public function orderVsSalePrimary(Request $request)
    {
        $data['ajaxUrl'] = URL::to('order-vs-sale-primary-search');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'order_vs_sale_primary_ajax';
        $data['header_level'] = 'Order Vs Sale (Primary)';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'daterange'));
        $data['memo_structure'] = repoStructure();
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'order vs sale secondary'));
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];
        return view('reports.main', $data);
    }

    public function orderVsSalePrimarySearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('order-vs-sale-primary-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];

        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        $selected_houses = array_filter($selected_houses);
        $data['post_data'] = $post;
        $data['order_vs_sale_primary'] = Reports::order_vs_sale_primary_by_house($selected_houses, $data['memo_structure'], $selected_date_range);

//        dd( $data['order_vs_sale_primary'] );
        return view('reports.ajax.order_vs_sale_primary_ajax', $data);
    }

    public function orderVsSalePrimaryDateWise(Request $request, $house_id, $post_data)
    {
        $request_pass_data = json_decode($post_data, true);
        unset($request_pass_data['_token']);
        $request_data = filter_array($request_pass_data);
        $data['ajaxUrl'] = URL::to('order-vs-sale-primary-date-wise-search/' . $house_id);
        $data['view'] = 'order_vs_sale_primary_ajax';
        $data['header_level'] = 'Order Vs Sale (Primary)';

        $data['searching_options'] = 'grid.search_elements_all';
        //$data['searchAreaOption'] = array('show'=>1,'daterange'=>0);
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'daterange'));
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'order vs sale secondary'));
        //memo
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];
        $data['post_data'] = $post_data;
        $data['date_wise'] = true;
        $data['current_balance'] = true;
        $data['order_vs_sale_primary'] = Reports::order_vs_sale_primary_by_date($house_id, $data['memo_structure'], $selected_date_range);
        return view('reports.main', $data);
    }

    public function orderVsSalePrimaryDateWiseSearch(Request $request, $house_id)
    {
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 2;
        $data['level_col_data'] = ['Req', 'Del'];

        //Requested Information
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        $data['post_data'] = $post;
        $data['current_balance'] = true;
        $data['order_vs_sale_primary'] = Reports::order_vs_sale_primary_by_date($house_id, $data['memo_structure'], $selected_date_range);

        return view('reports.ajax.order_vs_sale_primary_ajax', $data);
    }

    public function dailySaleSummary(Request $request)
    {
        $data['ajaxUrl'] = URL::to('daily-sale-summary-search');
        $data['view'] = 'daily_sale_summary_ajax';
        $data['header_level'] = 'Daily Sale Summary';
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'category', 'brand', 'sku', 'datepicker'));
        $memo = repoStructure();
        $data['level'] = 1;
        $data['level_col_data'] = [];
        $data['memo_structure'] = $memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Daily Sale Summary'));
        return view('reports.main', $data);
    }

    public function dailySaleSummarySearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('daily-sale-summary-search');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 1;
        $data['level_col_data'] = [];

        $selected_date = array_key_exists('date', $request_data) ? $request_data['date'] : [];
        if (count($selected_date) > 0) {
            $first_date_find = strtotime(date("d-m-Y", strtotime($selected_date[0])) . ", first day of this month");
            $selected_date[1] = date("d-m-Y", $first_date_find);
        } else {
            $selected_date[0] = date("d-m-Y", strtotime(now()));
            $first_date_find = strtotime(date("d-m-Y", strtotime($selected_date[0])) . ", first day of this month");
            $selected_date[1] = date("d-m-Y", $first_date_find);
        }
        //Requested Information
        $selected_asos = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];

        $report_type = array_key_exists('dss_report_type', $request_data) ? $request_data['dss_report_type'] : [];
        $data['daily_sale_summary'] = Reports::dailySaleSummary($selected_asos, $data['memo_structure'], $selected_date, count($report_type) > 0 ? $report_type[0] : 'aso');


        return view('reports.ajax.daily_sale_summary_ajax', $data);
    }

//    public function dailySaleSummaryRoute(Request $request,$aso_id,$post_data){
//        $request_pass_data = json_decode($post_data,true);
//        unset($request_pass_data['_token']);
//        $request_data = filter_array($request_pass_data);
//        $data['ajaxUrl'] = URL::to('daily-sale-summary-search');
//        $data['searching_options'] = 'grid.search_elements_all';
//        $data['view'] = 'daily_sale_summary_ajax';
//        $data['header_level'] = 'Daily Sale Summary Route';
//        //memeo structure
//        $categorie_ids =array_key_exists('category_id',$request_data) ? $request_data['category_id'] : [];
//        $brand_ids =array_key_exists('brands_id',$request_data) ? $request_data['brands_id'] : [];
//        $sku_ids =array_key_exists('skues_id',$request_data) ? $request_data['skues_id'] : [];
//
//        $data['memo_structure']= repoStructure($categorie_ids,$brand_ids,$sku_ids);
//        $data['level'] = 1;
//        $data['level_col_data'] =[];
//
//        $selected_date= array_key_exists('date',$request_data) ? $request_data['date'] : [];
//
//        $selected_date[0]= date("d-m-Y",strtotime(now()));
//        $first_date_find = strtotime(date("d-m-Y", strtotime($selected_date[0])) . ", first day of this month");
//        $selected_date[1]= date("d-m-Y",$first_date_find);
//
//        //Requested Information
//        $selected_asos=array_key_exists('aso_id',$request_data) ? $request_data['aso_id'] : [];
//        $data['breadcrumb'] = breadcrumb(array('Reports'=>'','active'=>'Daily Sale Summary'));
//        $data['post_data'] = $request_pass_data;
//        $data['daily_sale_summary'] = Reports::dailySaleSummary($selected_asos, $data['memo_structure'],$selected_date);
//        return view('reports.main',$data);
//
//    }

    //public function routeWisePerformenceByCategory(){
    /*
	public function brandWiseSale()
    {
        $data['ajaxUrl'] = URL::to('brand-wise-sale-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'brand_wise_sale_ajax';
        $data['header_level'] = 'Brand Wise Sale';
        //$data['searchAreaOption'] = searchAreaOption(array('show','month','daterange'));
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'route', 'year'));
        //$data['mendatory'] = searchAreaOption(array('zone', 'year'));
        //debug($data['mendatory'],1);
        //$data['searchAreaOption'] = searchAreaOption(array('show','year'));
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['level'] = 2;
        $data['level_col_data'] = ['Amount', 'Quantity'];
        $data['tweelveMonth'] = tweelveMonth(date('Y'));


        $data['level_col_data'] = ['Target', 'Sale', 'Ach%'];
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Brand Wise Sale'));
        return view('reports.main', $data);
    }


    public function brandWiseSaleSearch(Request $request)
    {
        $data['ajaxUrl'] = URL::to('brand-wise-sale-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

//        if(isset($request_data['zones_id']) && isset($request_data['year']))
//        {
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);

        $data['tweelveMonth'] = tweelveMonth($post['year'][0]);

        $data['level'] = 2;
        $data['level_col_data'] = ['Amount', 'Quantity'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $aso_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $route_ids = array_key_exists('route_id', $request_data) ? $request_data['route_id'] : [];


        if (count($route_ids) == 0) {
            if ($aso_ids) {
                $selected_route = getRoutesIdFromAsoId($aso_ids);
            } else {
                $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
                $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
                $selected_houses = array_filter($selected_houses);
                $selected_route = array_column(Reports::getRouteInfoHouse($selected_houses), 'id');
            }

        } else {
            $selected_route = $route_ids;
        }

        $data['brand_wise_sale'] = Reports::brand_wise_sale($selected_route, $data['memo_structure'], $post['year'][0]);
//        }
//        else
//        {
//            $data['errorMsg'] = 'Star(*) marks field are required.';
//        }

//debug($data['errorMsg'],1);

        return view('reports.ajax.brand_wise_sale_ajax', $data);

    }

*/


/*
    public function liftingStatement()
    {
        $data['ajaxUrl'] = URL::to('lifting-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'lifting-statement-ajax';
        $data['header_level'] = 'Lifting Statement';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house_single', 'daterange'));
        $data['mendatory'] = searchAreaOption(array('house', 'daterange'));

        $memo = repoStructure();
        $data['memo_structure'] = $memo;

        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Lifting Statement'));
        return view('reports.main', $data);
    }


    public function liftingStatementAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('lifting-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();

        unset($post['_token']);
        $request_data = filter_array($post);
        //debug($request_data,1);
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);


        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];

        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_houses = array_filter($selected_houses);
        //debug($selected_houses);
        //$selected_route=array_column(Reports::getRouteInfoHouse($selected_houses),'id');
        //debug($selected_date_range[0],1);
        $data['daterange'] = $selected_date_range;
        $data['statement'] = Reports::liftingStatementData($selected_houses, $selected_date_range);
        $data['accountStatementHouseInfo'] = Reports::accountStatementHouseInfo($selected_houses, $selected_date_range);
        //debug($data['accountStatementHouseInfo'],1);
        return view('reports.ajax.lifting-statement-ajax', $data);

    }


    public function targetStatement()
    {
        //debug(Auth::user()->user_type,1);
        $data['ajaxUrl'] = URL::to('target-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'target-statement-ajax';
        $data['header_level'] = 'Target Statement';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'aso', 'route', 'month', 'view-report'));
        $data['mendatory'] = searchAreaOption(array('zone', 'month'));

        $memo = repoStructure();
        $data['memo_structure'] = $memo;

        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Target Statement'));
        return view('reports.main', $data);
    }


    public function targetStatementAjax(Request $request)
    {
        $data['ajaxUrl'] = URL::to('target-statement-ajax');
        $data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();

        unset($post['_token']);
        $request_data = filter_array($post);
        //debug($request_data,1);
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);

        $data['level'] = 1;
        $data['level_col_data'] = ['Amount', 'Quantity'];

        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $aso_ids = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $asotoroute = getRoutesIdFromAsoId($aso_ids);
        $route_ids = array_key_exists('route_id', $request_data) ? $request_data['route_id'] : [];
        $selected_month = key_exists('month', $request_data) ? $request_data['month'] : [];
        $view_reports = key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        //dd($asotoroute[0],$route_ids[0]);
        $data['view_reports'] = $view_reports[0];
        //$data['view_report'] = ucwords($post['view_report'][0]);
        $selected_values = array('zones' => $zone_ids, 'regions' => $region_ids, 'territories' => $territory_ids, 'house' => $house_ids, 'aso' => $asotoroute, 'route' => $route_ids);

        $target_config = ReportsHelper::targetsConfigData($view_reports[0]);
        $data['config'] = $target_config;
        //debug($data['config']['field_name'],1);

        $data['targetStatement'] = Reports::targetStatement($target_config, $selected_month[0], $selected_values[$target_config['type']]);
        //debug($data['targetStatement'],1);
        if ($view_reports[0] == 'aso') {
            $data['asoSum'] = asoSumFromRoute($data['targetStatement']);
            //debug($data['asoSum'],1);
        }
        return view('reports.ajax.target-statement-ajax', $data);

    }
	

    public function pendingOrder()
    {
        //debug(Auth::user()->user_type,1);
        $data['ajaxUrl'] = URL::to('pending-order-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'pending-order-ajax';
        $data['header_level'] = 'Pending Order';

        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'category', 'brand', 'sku', 'daterange', 'view-report'));
        $data['mendatory'] = searchAreaOption(array('zone', 'month'));

        $memo = repoStructure();
        $data['memo_structure'] = $memo;

        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Pending Order'));
        return view('reports.main', $data);
    }

    public function pendingOrderAjax(Request $request)
    {
        //$data['ajaxUrl'] = URL::to('order-vs-sale-primary-search');
        //$data['searching_options'] = 'grid.search_elements_all';

        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);

        //memeo structure
        $categorie_ids = array_key_exists('category_id', $request_data) ? $request_data['category_id'] : [];
        $brand_ids = array_key_exists('brands_id', $request_data) ? $request_data['brands_id'] : [];
        $sku_ids = array_key_exists('skues_id', $request_data) ? $request_data['skues_id'] : [];

        $data['memo_structure'] = repoStructure($categorie_ids, $brand_ids, $sku_ids);
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];

        //Requested Information
        $zone_ids = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $region_ids = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $territory_ids = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $house_ids = array_key_exists('id', $request_data) ? $request_data['id'] : getHouseFromThisRoutes($this->routes);
        $get_info = Reports::getInfo($zone_ids, $region_ids, $territory_ids, $house_ids);
        $selected_houses = array_unique(array_column($get_info, 'distribution_house_id'), SORT_REGULAR);
        $selected_date_range = key_exists('created_at', $request_data) ? $request_data['created_at'] : [];
        $selected_houses = array_filter($selected_houses);
        $view_reports = key_exists('view_report', $request_data) ? $request_data['view_report'] : [];
        $data['view_report'] = $view_reports[0];
        $data['post_data'] = $post;

        if ($view_reports[0] == 'date') {
            $data['config'] = array('type' => 'date', 'table' => 'orders', 'field_name' => 'order_date');
        } else {
            $data['config'] = ReportsHelper::targetsConfigData($view_reports[0]);
        }


        $data['pending_orders'] = Reports::primary_pending_orders($selected_houses, $selected_date_range, $data['config']);
        //debug($data['pending_orders'],1);
//        dd( $data['order_vs_sale_primary'] );
        return view('reports.ajax.pending-order-ajax', $data);
    }

    public function pendingOrderDetailsAjax($field_name,$field_id,$config)
    {
        $data['header_level'] = 'Pending Order Details';
        $memo = repoStructure();
        $data['memo_structure'] = $memo;
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Pending Order Details'));
        $data['level'] = 1;
        $data['level_col_data'] = ['Req', 'Del'];
        $get = json_decode($config,true);
        $data['pending_orders_details'] = Reports::pending_orders_details($field_name,$field_id,$get);
        return view('reports.ajax.pending-order-details-ajax', $data);
    }
*/
    public function ranking()
    {
        $data['ajaxUrl'] = URL::to('ranking-ajax');
        $data['searching_options'] = 'grid.search_elements_all';
        $data['view'] = 'ranking_ajax';
        $data['header_level'] = 'Ranking';
        $data['searchAreaOption'] = searchAreaOption(array('show','zone','region','territory','house','aso','route', 'month', 'ranking_report'));
        $data['breadcrumb'] = breadcrumb(array('Reports' => '', 'active' => 'Ranking'));
        return view('reports.main', $data);
    }

    public function rankingAjax(Request $request)
    {
        $data['ranking'] = [];
        //request data
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);
        $report_type = array_key_exists('ranking_report', $request_data) ? $request_data['ranking_report'] : [];
        $selected_asoes = array_key_exists('aso_id', $request_data) ? $request_data['aso_id'] : [];
        $selected_houses = array_key_exists('id', $request_data) ? $request_data['id'] : [];
        $selected_territory = array_key_exists('territories_id', $request_data) ? $request_data['territories_id'] : [];
        $selected_region = array_key_exists('regions_id', $request_data) ? $request_data['regions_id'] : [];
        $selected_zone = array_key_exists('zones_id', $request_data) ? $request_data['zones_id'] : [];
        $selected_month = array_key_exists('month', $request_data) ? $request_data['month'] : [];
        switch (isset($report_type[0]) ? $report_type[0] : '') {
            case "aso":
                $data['ranking'] = Reports::rankingAso($selected_asoes, $selected_month);
                break;
            case "house":
                $data['ranking'] = Reports::rankingHouse($selected_houses, $selected_month);
                break;
            case "territory":
                $data['ranking'] = Reports::rankingTerritory($selected_territory, $selected_month);
                break;
            case "region":
                $data['ranking'] = Reports::rankingRegion($selected_region, $selected_month);
                break;
            case "zone":
                $data['ranking'] = Reports::rankingZone($selected_zone, $selected_month);


        }

        return view('reports.ajax.ranking_ajax', $data);


        //$data['ranking'] = Reports::brand_wise_sale($selected_route, $data['memo_structure'],$data['tweelveMonth']);
    }

}
