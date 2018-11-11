<?php
/**
 * Created by PhpStorm.
 * User: shabbir
 * Date: 9/30/2018
 * Time: 11:56 AM
 */

namespace App\Http\Controllers;


use App\Models\PromotionalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Auth;
use DB;

class PromotionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        DB::enableQueryLog();
    }

    public function promotionalSaleList(){
        $data['metaTitle'] = 'Globe | Promotional Sale';
        $data['pageTitle'] = 'Promotional Sale List';
        $data['breadcrumb'] = breadcrumb(array('active' => 'Promotional' . ' Order List'));
        $data['ajaxUrl'] = URL::to('promotional-list-search');
        $data['searchAreaOption'] = searchAreaOption(array('show', 'zone', 'region', 'territory', 'house', 'daterange'));
        $data['searching_options'] = 'grid.search_elements_all';
        $data['list'] = PromotionalModel::getPromotionalSale();
        return view('reports.promotional_sale_list', $data);
    }

    public function promotionalSaleListAjax(Request $request){
        $post = $request->all();
        unset($post['_token']);
        $request_data = filter_array($post);
        $data['list']=PromotionalModel::getPromotionalSale($request_data,true);
//        dd($data['list']);
        return view('reports.promotional_sale_list_ajax', $data);
    }

    public function saleDetails($id){
        $data['pageTitle'] = 'Promotional Sale Details';
        $data['breadcrumb'] = breadcrumb(array('Reports' => 'Details', 'active' =>'Sales Details'));
        $data['details']= PromotionalModel::getDetailsById($id);
        return view('reports.sale_details_promo', $data);
    }

    public function updatePromotionalSale(Request $request){
        $id= $request->id;
        $post = $request->all();
        unset($post['_token']);
        unset($post['id']);
        $request_data = filter_array($post);
        PromotionalModel::updateSale($id,$request_data['quantity'],$request_data['memo']);
        return redirect('promotional-list')->with('success', 'Information has been added.');
    }

}