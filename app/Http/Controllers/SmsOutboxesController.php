<?php

namespace App\Http\Controllers;

use App\Models\SmsOutbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use DB;

class SmsOutboxesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function __construct()
    {
        $this->middleware('auth');
        DB::enableQueryLog();
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function writeOutbox($number, $message, array $options = [])
    {
        $input_data['inbox_id'] = $options['id'];
        $input_data['sms_receiver_number'] = $number;
        $input_data['sms_content'] = $message;
        if (array_key_exists('priority', $options)) {
            $input_data['priority'] = $options['priority'];
        }
        if (array_key_exists('order_type', $options)) {
            $input_data['order_type'] = $options['order_type'];
        }
        if (array_key_exists('order_state', $options)) {
            $input_data['order_state'] = $options['order_state'];
        }

        SmsOutbox::Create($input_data);

    }

    public function smsOutboxes()
    {
        $data['searching_options'] = 'grid.search_elements_all';
        $data['searchAreaOption'] = searchAreaOption(array('datepicker','show','zone','region','territory','house','route','route','category','brand','sku','month','datepicker','dss_report_type'));
        $data['ajaxUrl'] = URL::to('sms-outboxes-ajax');
        $data['breadcrumb'] = breadcrumb(array('active'=>'SMS Outbox'));
        $data['outboxes'] = DB::table('sms_outboxes')->where('created',date('Y-m-d'))->get();
        return view('reports.sms_outboxes',$data);
    }

    public function smsOutboxesAjax(Request $request)
    {
        $post = $request->all();
//        debug($post,1);
        $dateselect = explode(' - ', $post['created_at'][0]);
//        debug($dateselect,1);
        $query = DB::table('sms_outboxes');
        $query->where('created','>=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[0]))));
        $query->where('created','<=',date('Y-m-d',strtotime(str_replace('/','-',$dateselect[1]))));
        $data['outboxes'] = $query->get();
//        dd(DB::getQueryLog());
        return view('reports.sms_outboxes_ajax',$data);
    }
}
