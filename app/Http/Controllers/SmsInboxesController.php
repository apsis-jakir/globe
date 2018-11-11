<?php

namespace App\Http\Controllers;

use App\Models\DistributionHouse;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\OrderDetail;
use App\Models\Sale;
use App\Models\Skue;
use App\Models\SmsInbox;
use App\Models\SmsOutbox;
use App\Models\Stocks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Sms;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

const ORDER = 'order';
const SALE = 'sale';
const PRIMARY = 'primary';
const PROMOTION = 'promotion';
class SmsInboxesController extends Controller
{
    private $sms;

    function __construct()
    {
//        dd(Config::get('rank'));
//        $exclude_date =['2018-08-10'];
//        dd(availableWorkingDates('2018-08-08',date('Y-m-d',strtotime(now())), $exclude_date));
        //dd(sku_pack_quantity('tp', 1.01));
        //stock_oc(1, 'tp', date('Y-m-d'), 2.05, 2.06, true);
    
        //$this->middleware('auth');
    
        $this->sms = new Sms();
    }


    /**
     * Display a listing of the sms inboxes.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $smsInboxes = SmsInbox::orderBy('id', 'desc')->paginate(10);

        return view('sms_inboxes.index', compact('smsInboxes'));
    }

    /**
     * Show the form for creating a new sms inbox.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {


        return view('sms_inboxes.create');
    }

    /**
     * Store a new sms inbox in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);
            $data['created_by'] = Auth::Id();
            SmsInbox::create($data);

            return redirect()->route('sms_inboxes.sms_inbox.index')
                ->with('success_message', 'Sms Inbox was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified sms inbox.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $smsInbox = SmsInbox::findOrFail($id);

        return view('sms_inboxes.show', compact('smsInbox'));
    }

    /**
     * Show the form for editing the specified sms inbox.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $smsInbox = SmsInbox::findOrFail($id);


        return view('sms_inboxes.edit', compact('smsInbox'));
    }

    /**
     * Update the specified sms inbox in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);
            $data['updated_by'] = Auth::Id();
            $smsInbox = SmsInbox::findOrFail($id);
            $smsInbox->update($data);

            return redirect()->route('sms_inboxes.sms_inbox.index')
                ->with('success_message', 'Sms Inbox was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified sms inbox from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $smsInbox = SmsInbox::findOrFail($id);
            $smsInbox->delete();

            return redirect()->route('sms_inboxes.sms_inbox.index')
                ->with('success_message', 'Sms Inbox was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'sender' => 'required|string|min:1|max:15',
            'sms_content' => 'required',
            'sms_status' => 'nullable',
            'is_active' => 'nullable|boolean',

        ];

        $data = $request->validate($rules);

        $data['is_active'] = $request->has('is_active');

        return $data;
    }

    /**
     * total check
     * @param $input_data
     * @param string $type
     * @param int $total_memo
     * @return int
     */
    private function convertCase(&$input_data, $type = "sale", &$calculate_total_amount = 0)
    {
        //debug($input_data,1);
        $total = 0;
        $calculate_total_amount = 0;

        $memo = memoStructure();

        if ($type === ORDER) {
            foreach ($memo as $cat_key => $cat_val) {
                foreach ($cat_val as $sku_key => $sku_val) {
                    $key = array_search($sku_key, array_keys($input_data));
                    if ($key !== false) {
                        $val = explode(',', $input_data[$sku_key]);
                        $total_quantity_unit = sku_pack_quantity($sku_key, (float)$val[0]);
                        $input_data[$sku_key] = [
                            'case' => $val[0],
                            'memo' => $val[1],
                            'quantity' => $total_quantity_unit
                        ];
                        if ($total_quantity_unit > 0) {
                            $calculate_total_amount = $calculate_total_amount + ($val[0] * get_case_price($sku_key, false));
                            $total = $total + $val[1];
                        }
                    } else {
                        $input_data[$sku_key] = [
                            'case' => 0,
                            'memo' => 0,
                            'quantity' => 0
                        ];
                    }
                }
            }
        }

        if ($type === SALE) {

            foreach ($memo as $cat_key => $cat_val) {
                foreach ($cat_val as $sku_key => $sku_val) {
                    $key = array_search($sku_key, array_keys($input_data));
                    if ($key !== false) {
                        $total_quantity_unit = sku_pack_quantity($sku_key, $input_data[$sku_key]);
                        $input_data[$sku_key] = [
                            'case' => $input_data[$sku_key],
                            'quantity' => $total_quantity_unit
                        ];
                        if ($total_quantity_unit > 0) {
                            $calculate_total_amount = $calculate_total_amount + ($input_data[$sku_key]['case'] * get_case_price($sku_key, false));
                            $total++;
                        }
                    } else {
                        $input_data[$sku_key] = [
                            'case' => 0,
                            'quantity' => 0
                        ];
                    }
                }
            }
        }

        if ($type === PRIMARY) {
            foreach ($memo as $cat_key => $cat_val) {
                foreach ($cat_val as $sku_key => $sku_val) {
                    $key = array_search($sku_key, array_keys($input_data));
                    if($key !== false){
                        $total_quantity_unit = sku_pack_quantity($sku_key, $input_data[$sku_key]);
                        $input_data[$sku_key] = [
                            'case' =>  $input_data[$sku_key],
                            'quantity' => $total_quantity_unit
                        ];
                        if ( $input_data[$sku_key] > 0) {
                            $calculate_total_amount = $calculate_total_amount + ( $input_data[$sku_key]['case'] * get_case_price($sku_key));
                            $total++;
                        }
                    }else{
                        $input_data[$sku_key] = [
                            'case' => 0,
                            'quantity' => 0
                        ];
                    }

                }
            }
        }


//        foreach ($input_data as $key => $value) {
//
////            if ($type === SALE) {
////                $total_quantity_unit=sku_pack_quantity($key,$value);
////                $input_data[$key]=[
////                    'case'=>$value,
////                    'quantity'=>$total_quantity_unit
////                ];
////                if($total_quantity_unit > 0){
////                    $calculate_total_amount = $calculate_total_amount+($total_quantity_unit*get_sku_price($key,false));
////                    $total++;
////                }
////            }
//            if ($type === SALE) {
//                $total_quantity_unit=sku_pack_quantity($key,$value);
//                $input_data[$key]=[
//                    'case'=>$value,
//                    'quantity'=>$total_quantity_unit
//                ];
//                if($total_quantity_unit > 0){
//                    $calculate_total_amount = $calculate_total_amount+($value*get_case_price($key,false));
//                    $total++;
//                }
//            }
//            if($type===PRIMARY){
////                $total_quantity_unit=sku_pack_quantity($key,$value);
////                $input_data[$key]=[
////                    'case'=>$value,
////                    'quantity'=>$total_quantity_unit
////                ];
////                if($total_quantity_unit > 0){
////                    $calculate_total_amount = $calculate_total_amount+($total_quantity_unit*get_sku_price($key));
////                    $total++;
////                }
//                $total_quantity_unit=sku_pack_quantity($key,$value);
//                $input_data[$key]=[
//                    'case'=>$value,
//                    'quantity'=>$total_quantity_unit
//                ];
//                if($value > 0){
//                    $calculate_total_amount = $calculate_total_amount+($value*get_case_price($key));
//                    $total++;
//                }
//            }
//            if ($type === ORDER) {
//
////                $val = explode(',', $value);
////                $total_quantity_unit=sku_pack_quantity($key,(float)$val[0]);
////                $input_data[$key]=[
////                    'case'=>$val[0],
////                    'memo'=>$val[1],
////                    'quantity'=>$total_quantity_unit
////                ];
////                if($total_quantity_unit > 0){
////                    $calculate_total_amount = $calculate_total_amount+($total_quantity_unit*get_sku_price($key,false));
////                    $total=$total+$val[1];
////                }
//                $val = explode(',', $value);
//                $total_quantity_unit=sku_pack_quantity($key,(float)$val[0]);
//                $input_data[$key]=[
//                    'case'=>$val[0],
//                    'memo'=>$val[1],
//                    'quantity'=>$total_quantity_unit
//                ];
//                if($total_quantity_unit > 0){
//                    $calculate_total_amount = $calculate_total_amount+($val[0]*get_case_price($key,false));
//                    $total=$total+$val[1];
//                }
//            }
//
//            if($type === PROMOTION){
//                $package_total=0;
//                $total=0;
//                foreach ($value as $val){
//                    $package_details = get_package_by_name('package2');
//                    $package_details['free']=[];
//                    $package_merge = promotion_package_merge($package_details['purchase'],$package_details['free'],$val['short_name']);
//                    foreach ($package_merge as $key=>$value){
//                        $total = $total + (int) $value;
//                        $package_total = (int)$package_total+($value*(int)get_regular_price_by_sku($key));
//                    }
//
//                    $calculate_total_amount+=$package_total;
//
//                }
//
//            }
//        }

        return $total;
    }

    private function  totalCaseCount($data)
    {
        $group1 = 0;
        $group2 = 0;
        $group3 = 0;
        $group4 = 0;
        $group5 = 0;
        foreach ($data as $key => $value) {
            switch (get_pack_size($key)) {
                case 24:
                    $group1 += $value['quantity'];
                    break;
                case 12:
                    $group2 += $value['quantity'];
                    break;
                case 9:
                    $group3 += $value['quantity'];
                    break;
                case 6:
                    $group4 += $value['quantity'];
                    break;
                case 36:
                    $group5 += $value['quantity'];
                    break;
            }
        }
        return convert_to_case($group1, 24) + convert_to_case($group2, 12) + convert_to_case($group3, 9) + convert_to_case($group4, 6)+ convert_to_case($group5, 36) ;
    }

    /**
     * prepare order data
     * @param $data
     * @return bool
     */
    private function prepareOrderData(&$data, $extra_data = [])
    {
        $aso_id = $data['asoid'];
        $order_date = $data['dt'];
        $route_id = isset($data['rt']) ? $data['rt'] : '';
        $get_information = get_route_info($route_id, $aso_id);
        if (is_null($get_information)) {
            $order_information['status'] = false;
            $order_information['message'] = "Invalid Route Information!!";
            $order_information['additional'] = $extra_data['additional'];
            $order_information['identifier'] = $extra_data['identifier'];
            return $order_information;
        }
        $total_outlet = $data['ou'];
        $visited_outlet = $data['vo'];
        $total_memo_order = $data['me'];
        $order_total_sku = $data['total'];
        $route_name = $get_information->routes_name;
        if ($total_memo_order > $visited_outlet) {
            $order_information['status'] = false;
            $order_information['message'] = "Invalid Total Memo!!";
            $order_information['additional'] = $extra_data['additional'];
            $order_information['identifier'] = $extra_data['identifier'];
            return $order_information;
        }

        if ($visited_outlet > $total_outlet) {
            $order_information['status'] = false;
            $order_information['message'] = "Invalid visited outlet!!";
            $order_information['additional'] = $extra_data['additional'];
            $order_information['identifier'] = $extra_data['identifier'];
            return $order_information;
        }

        unset($data['asoid'], $data['rt'], $data['dt'], $data['ou'], $data['vo'], $data['me'], $data['total']);
        $get_information = get_info_by_aso($aso_id);
        if (is_null($get_information)) {
            $order_information['status'] = false;
            $order_information['message'] = "Invalid ASO Information!!";
            $order_information['additional'] = $extra_data['additional'];
            $order_information['identifier'] = $extra_data['identifier'];
            return $order_information;
        }
        Order::where('aso_id', $aso_id)->where('order_date', $order_date)
            ->where('route_id', $route_id)
            ->where('order_type', 'Secondary')
            ->where('created_at', '>', Carbon::now()->subHours(48)->toDateTimeString())
            ->update(['order_status' => 'Rejected']);
        $total_sku_count = $this->convertCase($data, 'order', $order_total_amount);
        //debug($data);
        $total_case_count = $this->totalCaseCount($data);
        $order_information = [];
        $order_information['order'] = [
            'aso_id' => $aso_id,
            'dbid' => $get_information->distribution_house_id,
            'order_number'=>get_generated_code('ASO'),
            'order_date' => $order_date,
            'requester_name' => $get_information->name,
            'requester_phone' => $get_information->mobile,
            'route_id' => $route_id,
            'route_name' => $route_name,
            'total_outlet' => $total_outlet,
            'visited_outlet' => $visited_outlet,
            'order_type' => 'Secondary',
            'total_no_of_memo' => $total_memo_order,
            'order_total_sku' => $total_sku_count,
            'order_total_case' => $total_case_count,
            'order_amount' => $order_total_amount,
            'order_status' => 'Processed',
            'created_by' => Auth::Id()
        ];
        $order_information['status'] = true;
        return $order_information;
    }

    /**
     * prepare sale data
     * @param $data
     * @return bool
     */
    private function prepareSaleData(&$data, $extra_data)
    {
        $aso_id = $data['asoid'];
        $order_date = $data['dt'];
        $sale_total_sku = $data['total'];
        $order_details = get_order_id_by_sale($aso_id, $order_date, $data['rt']);
        unset($data['asoid'], $data['dt'], $data['rt'], $data['total']);
        $total_sku_count = $this->convertCase($data, SALE, $total_sale_amount);
        $total_case_count = $this->totalCaseCount($data);

        $order_id = $order_details['id'];
        $route_id = $order_details['route_id'];
        $route_name = $order_details['route_name'];
        if ($order_id == 0) {
            $sale_information['status'] = false;
            $sale_information['message'] = "Invalid Order Date or Route Information!!";
            $sale_information['additional'] = $extra_data['additional'];
            $sale_information['identifier'] = $extra_data['identifier'];
            return $sale_information;
        }
        $get_information = get_info_by_aso($aso_id);
        if (is_null($get_information)) {
            $sale_information['status'] = false;
            $sale_information['message'] = "Invalid ASO Information!!";
            $sale_information['additional'] = $extra_data['additional'];
            $sale_information['identifier'] = $extra_data['identifier'];
            return $sale_information;
        }
        $sale_information = [];

        $sale_information['order'] = [
            'aso_id' => $aso_id,
            'dbid' => $get_information->distribution_house_id,
            'order_number'=>get_generated_code('PO'),
            'order_id' => $order_id,
            'order_date' => $order_date,
            'sale_date' => $order_date,
            'sender_name' => $get_information->name,
            'sender_phone' => $get_information->mobile,
            'sale_type' => 'Secondary',
            'sale_total_sku' => $total_sku_count,
            'sale_total_case' => $total_case_count,
            'total_sale_amount' => $total_sale_amount,
            'sale_route_id' => $route_id,
            'sale_route' => $route_name,
            'created_by' => Auth::Id()
        ];
        $sale_information['status'] = true;
        return $sale_information;


    }

    /**
     * prepare primary data
     * @param $data
     * @return bool
     */
    private function preparePrimaryData(&$data, $extra_data)
    {
        $asm_rms_id = $data['asm_rsm_id'];
        $dbid = $data['dbid'];
        $order_date = $data['dt'];
        //$primary_order_total_sku =  $data['total'];
        $da = $data['da'];
        unset($data['asm_rsm_id'], $data['dbid'], $data['dt'], $data['total'], $data['da']);
        $primary_order_total = 0;
        //debug($primary_order_total,1);
        $total_sku_count = $this->convertCase($data, PRIMARY, $primary_order_total);
        //debug($primary_order_total,1);
        $total_case_count = $this->totalCaseCount($data);
        $get_information = get_info_by_asm($asm_rms_id, $dbid);


        if (is_null($get_information)) {
            $primary_order_information['status'] = false;
            $primary_order_information['message'] = "Invalid ASM/RSM Information!!";
            $primary_order_information['additional'] = $extra_data['additional'];
            $primary_order_information['identifier'] = $extra_data['identifier'];
            return $primary_order_information;
        }

        if ($get_information->distribution_house_id != $dbid) {
            $primary_order_information['status'] = false;
            $primary_order_information['message'] = "Invalid Distribution House Information !!";
            $primary_order_information['additional'] = $extra_data['additional'];
            $primary_order_information['identifier'] = $extra_data['identifier'];
            return $primary_order_information;
        }

//        Order::where('asm_rsm_id', $asm_rms_id)
//            ->where('order_date', $order_date)
//            ->whereIn('order_status', ['Pending','Processed'])
//            ->where('dbid', $dbid)
//            ->where('order_type', 'Primary')
//            ->where('created_at', '>', Carbon::now()->subHours(48)->toDateTimeString())->update(['order_status' => 'Rejected']);

        $primary_order_information['order'] = [
            'asm_rsm_id' => $asm_rms_id,
            'dbid' => $dbid,
            'order_number'=>get_generated_code('PO'),
            'order_date' => $order_date,
            'requester_name' => $get_information->name,
            'requester_phone' => $get_information->mobile,
            'order_type' => 'Primary',
            'order_total_sku' => $total_sku_count,
            'order_total_case' => $total_case_count,
            'order_amount' => $primary_order_total,
            'order_da' => $da,
            'created_by' => Auth::Id()
        ];
        $primary_order_information['status'] = true;
        return $primary_order_information;

    }

    private function preparePromotionData(&$data, $extra_data)
    {
        $aso_id = $data['asoid'];
        $order_date = $data['dt'];
        $route_id = $data['rt'];
        $route_name = !is_null($route = get_route_info($route_id)) ? $route->routes_name : '';
        unset($data['asoid'], $data['dt'], $data['rt']);

        $get_information = get_info_by_aso($aso_id);

        if(!get_order_sale_info($aso_id, $order_date, $route_id)){
            $promotional_sale['status'] = false;
            $promotional_sale['message'] = "Dont have Order and Sale!!";
            $promotional_sale['additional'] = $extra_data['additional'];
            $promotional_sale['identifier'] = $extra_data['identifier'];
            return $promotional_sale;
        }
        if (is_null($get_information)) {
            $promotional_sale['status'] = false;
            $promotional_sale['message'] = "Invalid ASO Information!!";
            $promotional_sale['additional'] = $extra_data['additional'];
            $promotional_sale['identifier'] = $extra_data['identifier'];
            return $promotional_sale;
        }
        $route_information = get_route_info($route_id, $aso_id);
        if (is_null($route_information)) {
            $promotional_sale['status'] = false;
            $promotional_sale['message'] = "Invalid Route Information!!";
            $promotional_sale['additional'] = $extra_data['additional'];
            $promotional_sale['identifier'] = $extra_data['identifier'];
            return $promotional_sale;
        }

        $promotional_sale = [];
        if (!getPreviousSale($aso_id, $order_date, 'Promotional')->isEmpty()) {
            rejectPreviousSale($aso_id, $order_date, $promotional_sale, $route_id, 'Promotional');
        }

        $total_sku_count = $this->convertCase($data, 'promotion', $sale_total_amount);
        if (!empty($aso_id) && !empty($order_date)) {
            $promotional_sale['order'] = [
                'aso_id' => $aso_id,
                'dbid' => $get_information->distribution_house_id,
                'order_date' => $order_date,
                'order_number'=>get_generated_code('PSO'),
                'sale_date' => $order_date,
                'sender_name' => $get_information->name,
                'sender_phone' => $get_information->mobile,
//                'dh_phone' => $get_information->dhname,
//                'dh_name' => $get_information->dhphone,
//                'tso_name' => $get_information->tsoname,
//                'tso_phone' => $get_information->tsophone,
                'sale_total_sku' => $total_sku_count,
                'total_sale_amount' => $sale_total_amount,
                'sale_route_id' => $route_id,
                'sale_route' => $route_name,
                'sale_type' => 'Promotional',
                'created_by' => Auth::Id()
            ];
            $promotional_sale['status'] = true;
            return $promotional_sale;
        } else {
            $promotional_sale['status'] = false;
            $promotional_sale['message'] = "Invalid Primary Order Total SKU !!";
            $promotional_sale['additional'] = $extra_data['additional'];
            $promotional_sale['identifier'] = $extra_data['identifier'];
            return $promotional_sale;
        }
    }

    /**
     * process order
     * @param $id
     * @param $parseData
     * @return \Illuminate\Http\RedirectResponse
     */

    private function processOrder($id, $parseData)
    {
        $order_information = $this->prepareOrderData($parseData['data'], $parseData);
        //debug($order_information,1);
        if (isset($order_information['status']) && $order_information['status'] != false) {
            foreach ($parseData['data'] as $key => $value) {
                if ((int)$value['memo'] > $order_information['order']['total_no_of_memo']) {
                    $order_information['status'] = false;
                    $order_information['message'] = "Invalid {$key} memo!!";
                    $order_information['additional'] = $parseData['additional'];
                    $order_information['identifier'] = $parseData['identifier'];
                    return $order_information;
                }
                $order_information['order_details'][] = [
                    "short_name" => $key,
                    "quantity" => (float)$value['quantity'],
                    "case" => (float)$value['case'],
                    "price" => get_case_price($key, false),
                    "no_of_memo" => (int)$value['memo'],
                    "created_by" => 1
                ];
            }

            if (Order::insertOrder($order_information['order'], $order_information['order_details'])) {
                SmsInbox::find($id)->update(['sms_status' => 'Processed']);

                return redirect()->route('sms_inboxes.sms_inbox.index')
                    ->with('success_message', 'Order successfully placed!');
            }
        } else {
            //return error with additional information
            return $order_information;
        }
    }

    /**
     * process sell
     * @param $id
     * @param $parseData
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processSell($id, $parseData)
    {
        $sale_information = $this->prepareSaleData($parseData['data'], $parseData);
        if (isset($sale_information['status']) && $sale_information['status'] != false) {

            foreach ($parseData['data'] as $key => $value) {
                $sale_information['order_details'][] = [
                    "short_name" => $key,
                    "quantity" => $value['quantity'],
                    'case' => (float)$value['case'],
                    "price" => get_case_price($key, false),
                    "created_by" => 1
                ];
            }

            if (!getPreviousSale($sale_information['order']['aso_id'],
                $sale_information['order']['order_date'])->isEmpty()
            ) {
                rejectPreviousSale($sale_information['order']['aso_id'], $sale_information['order']['order_date'],
                    $sale_information, $sale_information['order']['sale_route_id']);
            }

            //modify stock
            //dd($sale_information['order']['aso_id'],$sale_information['order_details'],isset($sale_information['update']) && $sale_information['update'] ? $sale_information['previous_data']: []);
            if (!modify_stock($sale_information['order']['aso_id'], $sale_information['order_details'], isset($sale_information['update']) && $sale_information['update'] ? $sale_information['previous_data'] : [],$sale_information['order']['order_date'])) {
                $sale_information['status'] = false;
                $sale_information['message'] = "No stock available for request SKUES!!";
                $sale_information['additional'] = $parseData['additional'];
                $sale_information['identifier'] = $parseData['identifier'];
                return $sale_information;
            }
            if (Sale::insertSale($sale_information['order'], $sale_information['order_details'])) {
                SmsInbox::find($id)->update(['sms_status' => 'Processed']);

                return redirect()->route('sms_inboxes.sms_inbox.index')
                    ->with('success_message', 'Sale successfully placed!');
            }
        } else {
            //return error with additional information
            return $sale_information;

        }
    }

    /**
     * process primary
     * @param $id
     * @param $parseData
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processPrimary($id, $parseData)
    {
        $primary_information = $this->preparePrimaryData($parseData['data'], $parseData);
        //debug($primary_information,1);
        if (isset($primary_information['status']) && $primary_information['status'] != false) {
            $is_existing_primary_order_found = get_primary_order_info_by_asm_rsm($primary_information['order']['asm_rsm_id'], $primary_information['order']['order_date'],'Primary');
            if(!empty($is_existing_primary_order_found)){
                return  array('message' => "Duplicate Order Entry Is Not Allowed!");
            }else{
                DB::table('orders')
                    ->where('asm_rsm_id', $primary_information['order']['asm_rsm_id'])
                    ->where('order_date',$primary_information['order']['order_date'])
                    ->where('order_status', 'Pending')
                    ->update(['order_status' => 'Rejected']);
                
                foreach ($parseData['data'] as $key => $value) {
                    $primary_information['order_details'][] = [
                        "short_name" => $key,
                        "quantity" => $value['quantity'],
                        "case" => (float)$value['case'],
                        "price" => get_case_price($key),
                        "created_by" => Auth::id()
                    ];
                }

                if (Order::insertOrder($primary_information['order'], $primary_information['order_details'])){
                    SmsInbox::find($id)->update(['sms_status' => 'Processed']);
                    return redirect()->route('sms_inboxes.sms_inbox.index')
                        ->with('success_message', 'Primary Order successfully placed!');
                }
            }
        } else {
            //return error with additional information
            return $primary_information;

        }
    }

    private function packageInformation($packages){
        $result_array=[];
        foreach ($packages as $package){
            $package_data = explode('-',$package);
            if(count($package_data)<3){
                return $result_array =['status'=>false];
            }
            $result_array[$package_data[0]]=[
                'quantity'  => $package_data[1],
                'no_of_memo' => $package_data[2]
            ];
        }
        return $result_array;
    }

    private function processPromotion($id, $parseData)
    {

        $promotion_information = $this->preparePromotionData($parseData['data'], $parseData);
        $promotion_information['order_details'] = [];
        $package_count=0;
        if (isset($promotion_information['status']) && $promotion_information['status'] != false) {
            $prom_data= $this->packageInformation($parseData['data']['pdn']);
            if(isset($prom_data['status']) && $prom_data['status'] === false){
                $error_message = 'Invalid Package Information!!';
                SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected']);
                //SmsOutboxesController::writeOutbox($get_info['sender'], $error_message, ['id' => $id, 'order_type' => 'promotional', 'priority' => 3]);
                return redirect()->route('sms_inboxes.sms_inbox.index')
                        ->with('error_message', $error_message);
            }
            else{
               foreach ($prom_data as $key=>$value){
                   $valid_package=get_package_by_name($key);
                   if(is_null($valid_package)){
                       $error_message = 'Invalid Package Name Contains *'.$key.' !!';
                       SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected']);
                       //SmsOutboxesController::writeOutbox($get_info['sender'], $error_message, ['id' => $id, 'order_type' => 'promotional', 'priority' => 3]);
                       return redirect()->route('sms_inboxes.sms_inbox.index')
                           ->with('error_message', $error_message);
                   }
                   else{
                       $package_count=$package_count+($value['quantity']* $value['no_of_memo']);
                       $promotion_information['order_details'][]=[
                           "short_name" => $key,
                           "case" => $value['quantity'],
                           "price" => 0,
                           'no_of_memo' => $value['no_of_memo'],
                           "created_by" => Auth::id()
                       ];
                   }

               }
            }
            $promotion_information['order']['sale_total_sku']= $package_count;

            if (!getPreviousSale($promotion_information['order']['aso_id'],
                $promotion_information['order']['order_date'],'Promotional')->isEmpty()
            ) {
                rejectPreviousPromotion($promotion_information['order']['aso_id'], $promotion_information['order']['order_date'],
                    $promotion_information['order']['sale_route_id']);
            }

            if (Sale::insertSale($promotion_information['order'], $promotion_information['order_details'])) {
                SmsInbox::find($id)->update(['sms_status' => 'Processed']);

                return redirect()->route('sms_inboxes.sms_inbox.index')
                    ->with('success_message', 'Promotion successfully placed!');
            }

        } else {
            //return error with additional information
            return $promotion_information;
        }
    }
    public function ManualProcessing(){
        $smsinboxes = DB::table('sms_inboxes')->where('sms_status', '=', 'Active')->limit(100)->get();
        foreach ($smsinboxes as $smsinbox){
            self::process($smsinbox->id);
        }
    }
    /**
     * process sms
     * @param $id sms ID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process($id, Request $request)
    {
        $parseData = $this->sms->parseSms($id);
        //debug($parseData['type'],1);
        if ($parseData['status'] === true) {
            switch ($parseData['type']) {
                case ORDER:

                    $result = $this->processOrder($id, $parseData);

                    if (!is_a($result, 'Illuminate\Http\RedirectResponse')) {
                        $error_message = isset($result['message']) ? $result['message'] : 'Invalid Order !';
                        SmsOutboxesController::writeOutbox($parseData['additional']['sender'], $error_message, ['id' => $parseData['additional']['id'], 'order_type' => strtolower($parseData['identifier']), 'priority' => 3]);
                        SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected', 'reason' => $error_message]);
                        return redirect()->route('sms_inboxes.sms_inbox.index')
                            ->with('error_message', $error_message);
                    }
                    return $result;
                    break;

                case SALE:

                    $result = $this->processSell($id, $parseData);
                    if (!is_a($result, 'Illuminate\Http\RedirectResponse')) {
                        $error_message = isset($result['message']) ? $result['message'] : 'Invalid Sale !!';
                        SmsOutboxesController::writeOutbox($parseData['additional']['sender'], $error_message, ['id' => $parseData['additional']['id'], 'order_type' => strtolower($parseData['identifier']), 'priority' => 3]);
                        SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected', 'reason' => $error_message]);
                        return redirect()->route('sms_inboxes.sms_inbox.index')
                            ->with('error_message', $error_message);
                    }
                    return $result;
                    break;

                case PRIMARY:

                    $result = $this->processPrimary($id, $parseData);
                    if (!is_a($result, 'Illuminate\Http\RedirectResponse')) {
                        $error_message = isset($result['message']) ? $result['message'] : 'Invalid Primary Order!!';
                        SmsOutboxesController::writeOutbox($parseData['additional']['sender'], $error_message, ['id' => $parseData['additional']['id'], 'order_type' => strtolower($parseData['identifier']), 'priority' => 3]);
                        SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected', 'reason' => $error_message]);
                        return redirect()->route('sms_inboxes.sms_inbox.index')
                            ->with('error_message', $error_message);
                    }
                    return $result;
                    //dd(is_a($result,'RedirectResponse'));
                    break;

                case PROMOTION:

                    $result = $this->processPromotion($id, $parseData);
                    if (!is_a($result, 'Illuminate\Http\RedirectResponse')) {
                        $error_message = isset($result['message']) ? $result['message'] : 'Invalid Promotinal Sale!!';
                        SmsOutboxesController::writeOutbox($parseData['additional']['sender'], $error_message, ['id' => $parseData['additional']['id'], 'order_type' => strtolower($parseData['identifier']), 'priority' => 3]);
                        SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected', 'reason' => $error_message]);
                        return redirect()->route('sms_inboxes.sms_inbox.index')
                            ->with('error_message', $error_message);
                    }
                    return $result;
                    break;

                default:

                    $error_message = 'Invalid message format !';
                    SmsOutboxesController::writeOutbox($parseData['additional']['sender'], $error_message, ['id' => $parseData['additional']['id'], 'order_type' => strtolower($parseData['identifier']), 'priority' => 3]);
                    SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected', 'reason' => $error_message]);
                    return redirect()->route('sms_inboxes.sms_inbox.index')
                        ->with('error_message', $error_message);
                    break;
            }
        } else {
            $error_message = $parseData['message'];
            SmsInbox::where('id', $id)->update(['sms_status' => 'Rejected', 'reason' => $error_message]);
            SmsOutboxesController::writeOutbox($parseData['additional']['sender'], $error_message, ['id' => $parseData['additional']['id'], 'order_type' => strtolower($parseData['identifier']), 'priority' => 3]);
            return redirect()->route('sms_inboxes.sms_inbox.index')
                ->with('error_message', $error_message);
        }
    }

    public function captureSms(Request $request)
    {

        $post = $request->all();
//        dd(unserialize('a:3:{s:6:"number";s:14:"+8801719415744";s:4:"text";s:2:"hi";s:15:"timestampMillis";s:13:"1532498441665";}'));
       //file_put_contents('1.txt',serialize($post));
//        die;

        $sender = $post['from'];
        $message_body = $post['message'];
        $send_at = $post['sent_timestamp'];
        $device_id = $post['device_id'];
//        session_id($sender);
//        session_start();
//        session_destroy();
//        die;
//        if(!isset($_SESSION['message'])){
//            $_SESSION['message'] ="";
//        }
//        if(strpos($message_body,'Total') === false){
//
//            $_SESSION['message'].=$message_body;
//            return 0;
//        }
//        else{
//            $_SESSION['message'].=$message_body;
//        }


//        //dd(unserialize("a:4:{s:4:\"body\";s:153:\"Order/ASOID-11/Dt-2018-02-17/Rt-1/OU-10/VO-8/ME-17/Tp-000,001/Tc-001,01/BHp-000,0/BHc-000,1/Fp-000,1/Fc-000,1/F(h)-000,1/F(1)-000,1/F(2)-000,1/UCp-000,1/\";s:2:\"id\";s:2:\"19\";s:6:\"sender\";s:14:\"+8801719415744\";s:7:\"sent_at\";s:25:\"Tue, 24 Jul 2018 16:51:12\";}"));
//        session_start();
        $data = [
            "transactionId" => 1,
            "sender" => preg_replace('/\+88/', '', $sender),
            "sms_content" => $message_body,
            "device_id" => $device_id
        ];

        try {
            $result = DB::table('sms_inboxes')->insertGetId(
                $data
            );
            if ($result) {
                $this->process($result, $request);
            }
        } catch (Exception $e) {

        }
//        dd(empty($result));
//         dd($message_id,$message_body,$sender,$send_at);
//        //dd(unserialize('a:4:{s:4:"body";s:2:"hi";s:2:"id";s:1:"1";s:6:"sender";s:14:"+8801719415744";s:7:"sent_at";s:25:"Tue, 24 Jul 2018 13:36:03";}'));
//        file_put_contents('1.txt',serialize($post));
    }

    public function sendOutboxSend(Request $request)
    {
        $outbox_pending = SmsOutbox::where('status', 'Draft')->get();
        foreach ($outbox_pending as $pending) {
            $number = $pending['sms_receiver_number'];
            $content = $pending['sms_content'];
            $response = sendSms($number, $content);
            $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $response_array = json_decode($json, TRUE);
            if (isset($response_array['result']['status']) && $response_array['result']['status'] == 0) {
                SmsOutbox::where('id', $pending['id'])->update(['status' => 'Sent']);
            }
        }
    }

}
