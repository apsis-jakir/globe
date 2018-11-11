<?php

namespace App\Http\Controllers;

//use App\Models\Ordering;
use Illuminate\Http\Request;
use Auth;
use DB;
use targetHelper;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        DB::enableQueryLog();
    }
    public function ordering_brand_skue()
    {
        $data['brands'] = DB::table('brands')->where('is_active',1)->orderBy('ordering')->get();
        //$data['skues'] = DB::table('skues')->where('is_active',1)->get();
        return view('settings.brandskue',$data);
    }

    public function ordering_brand_skue_action(Request $request)
    {
        $post = $request->only('order');
        foreach($post['order'] as $order)
        {
            $brandering = explode('_',$order);
            $brand_id = $brandering[0];
            $index = $brandering[1];
            DB::table('brands')->where('id', $brand_id)->update(['ordering' => $index]);
        }
    }


    public function show_skue($id)
    {
        //$data['brands'] = DB::table('brands')->where('brands_id',1)->orderBy('settings')->get();
        $data['skues'] = DB::table('skues')->where('is_active',1)->where('brands_id',$id)->orderBy('ordering')->get();
        return view('settings.showskue',$data);
    }

    public function orderingSkueAction(Request $request)
    {
        $post = $request->only('order');
        //dd($post);
        foreach($post['order'] as $order)
        {
            $skueordering = explode('_',$order);
            $brand_id = $skueordering[0];
            $index = $skueordering[1];
            $skue_id = $skueordering[2];
            DB::table('skues')->where('id', $skue_id)->update(['ordering' => $index]);
        }
    }

    public function setPromotions()
    {
//        return 'set promotion page';
        $data['skues'] = DB::table('skues')
            ->join('brands', 'brands.id', '=', 'skues.brands_id')
            ->select('skues.*','brands.brand_name')
            ->where('skues.is_active',1)->orderBy('skues.ordering')->get();
        return view('settings.setPromotions',$data);
    }

    public function promotion_submit(Request $request)
    {
        $post = $request->all();
        //dd($post['package_name']);
        $data['shortname'] = strtolower($post['package_name']);
        $data['start_date'] = $post['package_start'];
        $data['end_date'] = $post['package_end'];
        $data['pack_name'] = $post['description'];
        $emsg = '';


        $existing_package = DB::table('promotional_package')
            ->where('shortname',$data['shortname'])->get();
        if(!$existing_package->isEmpty())
        {
            $emsg .= 'This package name already exist.<br/>';
        }


        if(!isset($post['package_key']))
        {
            $emsg .= 'Please choose package items<br/>';
        }
        else
        {
            foreach($post['package_key'] as $k=>$v)
            {
                if($post['package_value'][$k])
                {
                    $details[$v] = $post['package_value'][$k];
                }
                else
                {
                    $emsg .= 'Please input package ('.$v.') quantity<br/>';
                }
            }
        }
        if(!isset($post['free_items']))
        {
            $emsg .= 'Please choose free items<br/>';
        }
        else
        {
            foreach($post['free_items'] as $k=>$v)
            {
                if($post['free_items_value'][$k])
                {
                    $items[$v] = $post['free_items_value'][$k];
                }
                else
                {
                    $emsg .= 'Please input free items ('.$v.') quantity<br/>';
                }

            }
        }

        $data['created_by'] = Auth::id();

        if(isset($post['is_active']))
        {
            $data['is_active'] = $post['is_active'];
        }
        //debug($emsg,1);
        if($emsg == '')
        {
            $data['pack_details'] = json_encode($details);
            $data['pack_free_item'] = json_encode($items);
            //debug($data,1);
            DB::table('promotional_package')->insert($data);
            return redirect('promotionsList')->with('success', 'Information has been added.');

        }
        else
        {
            return redirect('promotions')->with('error', $emsg);
        }

    }


    public function promotions_list()
    {
        $data['promotions'] = DB::table('promotional_package')->orderBy('start_date')->get();
        return view('settings.promotions_list',$data);
    }

    public function delete_promotions($id)
    {
        DB::table('promotional_package')->where('id',$id)->delete();
        return redirect('promotionsList')->with('success', 'Information has been deleted.');
    }

    public function package_details($id)
    {
        $details = DB::table('promotional_package')->select('*')->where('id',$id)->first();

        $ddetails = json_decode($details->pack_details,true);
        //dd((array)$ddetails);
        $data['ddetails'] = $ddetails;
        $items = json_decode($details->pack_free_item,true);
        $data['items'] = $items;
        $psku_id = array();
        foreach($ddetails as $k=>$d)
        {
            $psku_id[] = $k;
        }

        $isku_id = array();
        foreach($items as $k=>$v)
        {
            $isku_id[] = $k;
        }
        //dd($isku_id);
        $data['package_details'] = DB::table('skues')
                                    ->select('skues.*','brands.brand_name')
                                    ->leftJoin('brands','brands.id','=','skues.brands_id')
                                    ->whereIn('skues.short_name', $psku_id)->get();
        $data['items_details'] = DB::table('skues')
                                ->select('skues.*','brands.brand_name')
                                ->leftJoin('brands','brands.id','=','skues.brands_id')
                                ->whereIn('skues.short_name', $isku_id)->get();
        //dd($psku_id);
        return view('settings.promotions_details',$data);
    }

    public function active_inactive($id,$is_active)
    {
        DB::table('promotional_package')->where('id', $id)->update(['is_active' => ($is_active)?0:1]);
        return redirect('promotionsList')->with('success', 'Information has been changed.');
    }


    public function target_set($type,$target_month=null)
    {
        //debug(session()->all(),1);
        //debug(Auth::user(),1);
        $data['type'] = $type;
        $data['target_month'] = $target_month;
        $data['targetType'] = 'new';

        if($target_month)
        {
            //$data['base'] = rand(10,555);
            $data['targetType'] = 'edit';
            $data['existingValue'] = DB::table('targets')
                ->select('*')
                ->where('target_type',$type)
                ->where('target_month',$target_month)->get();
//            debug($data['existingValue'][0]->base_date,1);

            if($type == 'zones')
            {
                $data['geographies'] = DB::table('zones')->select('id','zone_name as gname')->where('is_active',1)->orderBy('ordering')->get()->toArray();
            }
            else if($type == 'regions')
            {
                //$data['geographies'] = DB::table('regions')->select('id','region_name as gname')->orderBy('ordering')->get();
                $data['geographies'] = DB::table('distribution_houses')
                    ->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id')
                    ->select('regions.id','regions.region_name as gname')
                    ->where('distribution_houses.zones_id',Auth::user()->zones_id)
                    ->groupBy('distribution_houses.regions_id')
                    ->orderBy('regions.ordering')->get()->toArray();
            }
            else if($type == 'territories')
            {
                $data['geographies'] = DB::table('distribution_houses')
                    ->leftJoin('territories', 'territories.id', '=', 'distribution_houses.territories_id')
                    ->select('territories.id','territories.territory_name as gname')
                    ->where('distribution_houses.regions_id',Auth::user()->regions_id)
                    ->groupBy('distribution_houses.territories_id')
                    ->orderBy('territories.ordering')->get()->toArray();
            }
            else if($type == 'house')
            {
                $data['geographies'] = DB::table('distribution_houses')
                    ->select('distribution_houses.id','distribution_houses.point_name as gname')
                    ->where('distribution_houses.id',Auth::user()->distribution_house_id)->get()->toArray();
            }
            else if($type == 'market')
            {
//                $data['geographies'] = DB::table('users')
//                    ->select('id','name as gname')
//                    ->where('territories_id',Auth::user()->territories_id)
//                    ->where('user_type','market')->get();

                $data['geographies'] = DB::table('users')
                    ->select('id','name as gname')
                    ->where('distribution_house_id',Auth::user()->distribution_house_id)
                    ->where('user_type','market')->get()->toArray();
            }
            else if($type == 'route')
            {
                $data['geographies'] = DB::table('routes')
                    ->select('id','routes_name as gname')
                    ->where('distribution_houses_id',Auth::user()->distribution_house_id)->get()->toArray();
            }
            //$data['baseData'] = $this->baseData($data['geographies'],$base_date,$type);
            //$data['baseData'] = $this->baseData($data['geographies']);

            $data['skues'] = DB::table('skues')
                ->select('skues.*')
                ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
                ->where('skues.is_active',1)
                ->orderBy('brands.ordering')->get();

            $data['brands'] = DB::table('skues')
                ->select('brands.brand_name',DB::raw('COUNT(skues.brands_id) as total'))
                ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
                ->where('skues.is_active',1)
                ->groupBy('skues.brands_id')
                ->orderBy('brands.ordering')->get();
        }
        return view('settings.target_set',$data);
    }

    public function target_set_process(Request $request)
    {
        $post = $request->all();
        //debug($post,1);
        $base_date = $post['base_date'];
        //debug($base_date,1);
        $data['type'] = $post['target_type'];
        $data['target_month'] = $post['target_month'];
        $data['targetType'] = 'new';
        $data['existingValue'] = array();
        //$data['base'] = rand(10,555);
        if($post['target_type'] == 'zones')
        {
            $data['geographies'] = DB::table('zones')->select('id','zone_name as gname')->where('is_active',1)->orderBy('ordering')->get()->toArray();
        }
        else if($post['target_type'] == 'regions')
        {
            $data['geographies'] = DB::table('distribution_houses')
                ->leftJoin('regions', 'regions.id', '=', 'distribution_houses.regions_id')
                ->select('regions.id','regions.region_name as gname')
                ->where('distribution_houses.zones_id',Auth::user()->zones_id)
                ->groupBy('distribution_houses.regions_id')
                ->orderBy('regions.ordering')->get()->toArray();
        }
        else if($post['target_type'] == 'territories')
        {
            $data['geographies'] = DB::table('distribution_houses')
                ->leftJoin('territories', 'territories.id', '=', 'distribution_houses.territories_id')
                ->select('territories.id','territories.territory_name as gname')
                ->where('distribution_houses.regions_id',Auth::user()->regions_id)
                ->groupBy('distribution_houses.territories_id')
                ->orderBy('territories.ordering')->get()->toArray();
//            debug(DB::getQueryLog(),1);
        }
        else if($post['target_type'] == 'house')
        {
            $data['geographies'] = DB::table('distribution_houses')
                ->select('distribution_houses.id','distribution_houses.point_name as gname')
                ->where('distribution_houses.territories_id',Auth::user()->territories_id)->get()->toArray();
        }
        else if($post['target_type'] == 'market')
        {
            $data['geographies'] = DB::table('users')
                ->select('id','name as gname')
                ->where('distribution_house_id',Auth::user()->distribution_house_id)
                ->where('user_type','market')->get()->toArray();
        }
        else if($post['target_type'] == 'route')
        {
            $data['geographies'] = DB::table('routes')
                ->select('id','routes_name as gname')
                ->where('distribution_houses_id',Auth::user()->distribution_house_id)->get()->toArray();
        }
//        debug($post['target_type'],1);
        $data['baseData'] = $this->baseData($data['geographies'],$base_date,$post['target_type']);
        //debug($data['baseData'],1);
        $data['base'] = $this->totalBaseData($data['baseData']);
//debug($data['base'],1);
        $data['skues'] = DB::table('skues')
            ->select('skues.*')
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
            ->where('skues.is_active',1)
            ->orderBy('brands.ordering')->get();
        //debug($data['skues'],1);
        $data['brands'] = DB::table('skues')
            ->select('brands.brand_name',DB::raw('COUNT(skues.brands_id) as total'))
            ->leftJoin('brands', 'brands.id', '=', 'skues.brands_id')
            ->where('skues.is_active',1)
            ->groupBy('skues.brands_id')
            ->orderBy('brands.ordering')->get();


        $equery = DB::table('targets');
        $equery->where('target_type',$post['target_type']);
        $equery->where('target_month',$post['target_month']);
        if($post['target_type'] == 'regions')
        {
            $equery->whereIn('type_id',[DB::raw('select regions_id from distribution_houses where zones_id='.Auth::user()->zones_id)]);
        }
        else if($post['target_type'] == 'territories')
        {
            $equery->whereIn('type_id',[DB::raw('select territories_id from distribution_houses where regions_id='.Auth::user()->regions_id)]);
        }
        else if($post['target_type'] == 'house')
        {
            $equery->whereIn('type_id',[DB::raw('select id from distribution_houses where territories_id='.Auth::user()->territories_id)]);
        }
        else if($post['target_type'] == 'market')
        {
            $equery->whereIn('type_id',[DB::raw('select id from routes where distribution_houses_id='.Auth::user()->distribution_house_id)]);
        }
        else if($post['target_type'] == 'route')
        {
            $equery->whereIn('type_id',[DB::raw('select id from routes where distribution_houses_id='.Auth::user()->distribution_house_id)]);
        }
        $existing = $equery->get();

//debug($existing,1);

        if($existing->isEmpty())
        {
            return view('settings.target_set_ajax_data_show',$data);
        }
        else
        {
            echo false;
        }
    }

    public function baseData($geography,$base_date,$target_type)
    {
        $date = explode(' - ',$base_date);
        $startdate = date('Y-m-d',strtotime(str_replace('/','-',$date[0])));
        $enddate = date('Y-m-d',strtotime(str_replace('/','-',$date[1])));
        $daterange = array($startdate,$enddate);
        //debug($daterange,1);
        //$data = array(str_replace(' ','',date('Y-m-d',strtotime($base_date[0]))),str_replace(' ','',date('Y-m-d',strtotime($base_date[1]))));
        //debug($data,1);
        $base_data = TargetHelper::getBaseData($geography,$daterange,$target_type);
        //debug($base_data,1);
        $data = array();
        foreach($geography as $k=>$v)
        {
            $skues = DB::table('skues')->where('is_active',1)->orderBy('ordering')->get();
            foreach($skues as $sk=>$sv)
            {
                //$base = rand(10,100);
                $data[$v->id][$sv->brands_id][$sv->id] = (isset($base_data[$v->id][$sv->short_name])?$base_data[$v->id][$sv->short_name]:0);
            }
        }
        //debug($data,1);
        return $data;
    }

    public function totalBaseData($baseData)
    {
        //debug($baseData,1);
        $totalbase = array();
        foreach($baseData as $geography)
        {
            foreach($geography as $brand)
            {
                foreach($brand as $sk=>$sv)
                {
                    $totalbase[$sk][] = $sv;
                }
            }
        }
        //debug($baseData);
        //debug($totalbase);
        //debug(array_sum($totalbase[2]),1);
        return $totalbase;
    }

    public function target_submit(Request $request)
    {
        $post = $request->all();

//        debug($post,1);
        $insertData['target_type'] = $post['target_type'];
        $insertData['target_month'] = $post['target_month'];
        $insertData['base_date'] = $post['base_date'];

        if($post['edit'])
        {
            $dquery = DB::table('targets');
            $dquery->where('target_type', $post['target_type']);
            $dquery->where('target_month',$post['target_month']);
            if($post['target_type'] == 'regions')
            {
                $dquery->whereIn('type_id',[DB::raw('select regions_id from distribution_houses where zones_id='.Auth::user()->zones_id)]);
            }
            else if($post['target_type'] == 'territories')
            {
                $dquery->whereIn('type_id',[DB::raw('select territories_id from distribution_houses where regions_id='.Auth::user()->regions_id)]);
            }
            else if($post['target_type'] == 'house')
            {
                $dquery->whereIn('type_id',[DB::raw('select id from distribution_houses where territories_id='.Auth::user()->territories_id)]);
            }
            else if($post['target_type'] == 'market')
            {
                $dquery->whereIn('type_id',[DB::raw('select id from routes where distribution_houses_id='.Auth::user()->distribution_house_id)]);
            }
            else if($post['target_type'] == 'route')
            {
                $dquery->whereIn('type_id',[DB::raw('select id from routes where distribution_houses_id='.Auth::user()->distribution_house_id)]);
            }
            $dquery->delete();
        }

//        foreach($post['geography_id'] as $geography)
        foreach($post['base_distribute'] as $geography=>$value)
        {
            $insertData['type_id'] = $geography;
            $baseValue = array();
            foreach($post['base_distribute'][$geography] as $k=>$v)
            {
                foreach($v as $vk=>$vv)
                {
                    $baseValue[$vk] = $vv;
                }
            }
            $insertData['base_value'] = json_encode($baseValue);

            $targetValue = array();
            foreach($post['target_distribute'][$geography] as $k=>$v)
            {
                foreach($v as $vk=>$vv)
                {
                    $targetValue[$vk] = $vv;
                }
            }
            $insertData['target_value'] = json_encode($targetValue);
            $insertData['created_by'] = Auth::id();

            DB::table('targets')->insert($insertData);
//            dd($insertData);
        }
//        debug($insertData,1);
        return redirect('targetSet/'.$post['target_type'])->with('success', 'Information has been added.');
    }

    public function remove_target($type,$target_month)
    {
       // dd($type);
        DB::table('targets')
            ->where('target_type', $type)
            ->where('target_month',$target_month)->delete();
        return redirect('targetSet/'.$type)->with('success', 'Information has been removed.');
    }

}
