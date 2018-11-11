<?php

namespace App\Http\Controllers;

use App\Models\Order;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Routes;
use App\Models\Reports;
use App\Models\Chart;
use Auth;
use App\Models\Sale;


class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $routes;
    public function __construct() {
        $this->routes = json_decode(session('routes_list'));
        $this->middleware('auth');
        //dd(Session::all());
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data['brands'] = DB::table('brands')->where('is_active', 1)->get();
        //return view('home', $data);

        $brand_wise_sales_current = Reports::brandWiseSales($this->routes,date('Y'));
        $brand_wise_sales_previous = Reports::brandWiseSales($this->routes,(date('Y')-1));
        //debug($brand_wise_sales,1);
        $series_current = seriesData($brand_wise_sales_current,'series');
        $series_previous = seriesData($brand_wise_sales_previous,'series');
        //debug($series,1);
        $lineSeriesArrayCurrent = Chart::lineProductivityComparison($this->routes, date('Y'));
        $lineSeriesCurrent = lineProducttivityComparison($lineSeriesArrayCurrent,date('m'));

        $lineSeriesArrayPrev = Chart::lineProductivityComparison($this->routes, date('Y')-1);
        $lineSeriesPrev = lineProducttivityComparison($lineSeriesArrayPrev);
        //dd($lineSeriesArrayCurrent,str_replace('"','',$lineSeriesCurrent),1);


        $categories = seriesData($brand_wise_sales_current,'categories');
        //debug($categories,1);
        $columnConfig = array('type'=>'column','chart_title'=>'Brand Wise Sales','yAxis_title'=>'Volume','height'=>350,'sname-1'=>'Current Year','sname-2'=>'Previous Year');
        $columnConfig['categories'] = $categories;
        $columnConfig['series_current'] = $series_current;
        $columnConfig['series_prevous'] = $series_previous;
        //debug($config['categories'],1);
        $data['columnConfig'] = $columnConfig;

        $lineConfig = array('type'=>'line','chart_title'=>'Productivity Comparison','yAxis_title'=>'Volume','height'=>350,'sname-1'=>'Current Year','sname-2'=>'Previous Year');
        $lineConfig['lineSeriesCurrent'] = $lineSeriesCurrent;
        $lineConfig['lineSeriesPrev'] = $lineSeriesPrev;
        $data['lineConfig'] = $lineConfig;

        $tigerSeries = Chart::focusedProductContribution($this->routes,['tp'],date('Y'));
        //debug($tigerSeries,1);
        $fizupSeries = Chart::focusedProductContribution($this->routes,['fp'],date('Y'));
        //debug($fizupSeries);
        $uroorangeSeries = Chart::focusedProductContribution($this->routes,['uop'],date('Y'));
       // debug($uroorangeSeries);
        $mangoliSeries = Chart::focusedProductContribution($this->routes,['m'],date('Y'));
        $uroOrangeeSeries = Chart::focusedProductContribution($this->routes,['ornj'],date('Y'));

        $otherSkue = focusedUnfocusedSkueShortNameArray('focused');
        $othersSeries = Chart::focusedProductContribution($this->routes,$otherSkue,date('Y'));
        //debug($otherSkue,1);

        $data['pieConfig'] = array('type'=>'pie','chart_title'=>'Tiger 250ml','id'=>'piechart','series'=>pieContributionSeries($tigerSeries));
        $data['pieFizupConfig'] = array('type'=>'pie','chart_title'=>'Fizz Up 250ml','id'=>'fizup-pie','series'=>pieContributionSeries($fizupSeries));
        $data['pieUroorangeConfig'] = array('type'=>'pie','chart_title'=>'Uro Orange','id'=>'uroorange-pie','series'=>pieContributionSeries($uroorangeSeries));
        $data['pieMangoliConfig'] = array('type'=>'pie','chart_title'=>'Mangoli','id'=>'mangoli-pie','series'=>pieContributionSeries($mangoliSeries));
        $data['pieUroOrangeeConfig'] = array('type'=>'pie','chart_title'=>'Uro Orangee','id'=>'uroorangee-pie','series'=>pieContributionSeries($uroOrangeeSeries));
        $data['pieOtherConfig'] = array('type'=>'pie','chart_title'=>'Total Case (All together)','id'=>'other-pie','series'=>pieContributionSeries($othersSeries));
        //debug($data['columnConfig'],1);
        return view('chart.dashboard_main', $data);
    }

    public function dashboardTargetOutlet() {
        //debug($this->routes,1);
        $query = Routes::selectRaw('sum(no_of_outlets) as total')
            ->where('is_active', 1)
            ->whereIn('id',$this->routes)->first();
        if (isset($query->total)) {
            return $query->total;
        } else {
            return 0;
        }
    }

    public function dashboardVisitedOutlet() {
        $outlet = Routes::selectRaw('sum(no_of_outlets) as total')
            ->where('is_active', 1)
            ->whereIn('id',$this->routes)
            ->first();
        $query = Order::selectRaw('sum(visited_outlet) as total')
            ->where('order_status', 'Processed')
            ->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),date('Y-m-d'))
            ->whereIn('route_id',$this->routes)
            ->first();
        if (isset($query->total)) {
            return $query->total.'/'.$outlet->total;
        } else {
            return '0/'.$outlet->total;
        }
    }

    public function dashboardSuccessfullCall() {
        $query = Order::selectRaw('sum(total_no_of_memo) as total')
            ->selectRaw('sum(visited_outlet) as vo_total')
            ->where('order_status', 'Processed')
            ->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),date('Y-m-d'))
            ->whereIn('route_id',$this->routes)
            ->first();
        if (isset($query->total)) {
            //return $query->total.'/'.$query->vo_total;
            return $query->vo_total.'/'.$query->total;
        } else {
            return '0/'.(($query->vo_total)?$query->vo_total:0);
        }
    }

    public function dashboardNoLifter() {
        $db = implode(', ',$this->routes);
        //debug($db,1);
        $query = DB::select('SELECT count(DISTINCT routes.distribution_houses_id) as house, 
          (SELECT Count(orders.dbid) FROM orders
            WHERE orders.order_type="Primary" 
              AND orders.order_status="Processed" 
              AND DATE_FORMAT(orders.order_date,"%Y-%m-%d") = "'.date("Y-m-d").'" 
              AND orders.dbid IN(SELECT DISTINCT routes.distribution_houses_id FROM routes where routes.id IN('.$db.'))) as lifting
            FROM routes where routes.id IN('.$db.')');

        return (($query[0]->house) - ($query[0]->lifting)).'/'.$query[0]->house;
    }

    public function dashboardNoOrders() {
        $route = Routes::selectRaw('COUNT(id) as total')->where('is_active', 1)->whereIn('id',$this->routes)->first();
        $query = Order::selectRaw('COUNT(id) as total')
            ->where('order_type','Secondary')
            ->where('order_status', 'Processed')
            ->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),date('Y-m-d'))
            ->whereIn('route_id',$this->routes)->first();

        if (isset($query->total)) {
            return $query->total.'/'.$route->total;
        } else {
            return '0/'.$route->total;
        }
    }

    public function dashboardNoSales() {
        $route = Routes::selectRaw('COUNT(id) as total')->where('is_active', 1)->whereIn('id',$this->routes)->first();
        $query = Sale::selectRaw('COUNT(id) as total')
            ->where('sale_type','Secondary')
            ->where('sale_status', 'Processed')
            ->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),date('Y-m-d'))
            ->whereIn('sale_route_id',$this->routes)->first();

        if (isset($query->total)) {
            return $query->total.'/'.$route->total;
        } else {
            return '0/'.$route->total;
        }
    }

    public function dashboardStrikeRate() {
        $query = Order::selectRaw('sum(visited_outlet) as totalVOutlet')
            ->selectRaw('sum(total_no_of_memo) as totalMemo')
            ->where('order_status', 'Processed')
            ->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),date('Y-m-d'))
            ->where('order_type','Secondary')
            ->whereIn('route_id',$this->routes)
            ->first();

        if (isset($query->totalVOutlet) && isset($query->totalMemo)) {
            return round(($query->totalVOutlet/$query->totalMemo),2).'%';
        } else {
            return '0%';
        }
    }

    public function brandWiseProductivityQuery($date)
    {
        $query = DB::table('orders')
            ->select(DB::raw('Sum(total_no_of_memo) AS ttmemo'))
            ->where('orders.order_date',$date)
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed')
            ->whereIn('orders.route_id',$this->routes)->first();
        //debug($query,1);
        return $query;
    }

    public function brandWiseProductivityLastSevenQuery($post,$date)
    {
        $query = DB::table('brands')
            ->select('brands.brand_name', DB::raw('Sum(order_details.no_of_memo) AS individual_mamo'))
            ->leftJoin('skues', 'skues.brands_id', '=', 'brands.id')
            ->leftJoin('order_details', 'skues.short_name', '=', 'order_details.short_name')
            ->leftJoin('orders', 'order_details.orders_id', '=', 'orders.id')
            ->where('orders.order_date',$date)
            ->where('brands.id', $post['brand_id'])
            ->where('orders.order_type', 'Secondary')
            ->where('orders.order_status', 'Processed')
            ->whereIn('orders.route_id',$this->routes)->first();

        return $query;
    }

    public function brandWiseProductivity(Request $request) {
        $post = $request->all();
        $cdate = date('Y-m-d');
        $beforesevendate = date('Y-m-d', strtotime('-7 days'));

        $totalMemo = $this->brandWiseProductivityQuery($cdate);
        $totalMemoSameDayLastWeek = $this->brandWiseProductivityQuery($beforesevendate);


        $query = $this->brandWiseProductivityLastSevenQuery($post,$cdate);
        //debug($query,1);
        $querySeven = $this->brandWiseProductivityLastSevenQuery($post,$beforesevendate);
        if($query->individual_mamo)
        {
            $current = number_format(($totalMemo->ttmemo / $query->individual_mamo),2);
        }
        else
        {
            $current = 0;
        }

        if($querySeven->individual_mamo)
        {
            $querySeven = number_format(($totalMemoSameDayLastWeek->ttmemo / $querySeven->individual_mamo),2);
        }
        else
        {
            $querySeven = 0;
        }

        $sdlw = ($current-$querySeven);

        $smdlw_parent = 'arrow_sort_sign_up';
        $smdlw_icon = 'up';
        if($sdlw > 0)
        {
            $smdlw_parent = 'arrow_sort_sign_down';
            $smdlw_icon = 'down';
        }

        $html = '<span class="brand_name">' . $post['brand_name'] . '</span>
                  <span class="brand_cal">';
        if ((int) $query->individual_mamo === 0) {
            $html .= 0;
        } else {
            $html .= round($current,2);
        }

        $html .= '</span>
                  <span class="brand_prev">
                    <span class="arrow_sign '.$smdlw_parent.'">
                      <i class="fa fa-sort-'.$smdlw_icon.'"></i>
                      &nbsp;';
        $html .= round($querySeven,2);
        $html .= '</span>
                    <span class="sdlw">(SDLW)</span>
                  </span>';
        return $html;
    }
}
