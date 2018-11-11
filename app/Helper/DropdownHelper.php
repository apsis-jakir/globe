<?php
function getUserWiseGeo($type)
{
    $routes = json_decode(session('routes_list'));
//    debug($routes,1);
    if($type != 'aso')
    {
        $sql = DB::table('routes')->select('distribution_houses.id');
        $sql->leftJoin('distribution_houses','distribution_houses.id','=','routes.distribution_houses_id');
        $sql->whereIn('routes.id',$routes);
        if($type == 'zone')
        {
            $sql->groupBy('distribution_houses.regions_id');
        }
        else if($type == 'region')
        {
            $sql->groupBy('distribution_houses.territories_id');
        }
        $result = $sql->get();
        return implode(',',array_column(collect($result)->toArray(),'id'));
    }
    else
    {
        return implode(',',$routes);
    }
}
function dropdownList($id,$post=[],$mendatory='')
{
//    debug($post);
    if($mendatory == '')
    {
        $mendatory = 'mendatory';
    }
    $dd_info = DB::table('dropdown_list')->where('dd_id',$id)->first();

    $query = $dd_info->query;
    $kword = explode('@', $query);

    if(isset($kword[1]))
    {
        $wherearray = explode('=',$kword[1]);
        $session = $wherearray[1];
//        debug($session,1);
        if($session)
        {

            $where = $wherearray[0].' IN('.getUserWiseGeo($session).')';
            $query = str_replace('@'.$kword[1].'@',$where,$query);
        }
        else
        {
            $query = str_replace('@'.$kword[1].'@',1,$query);
        }
    }
//    debug($query,1);
    $data_info = DB::select($query);
    $res=[];
    foreach ($data_info as $value){
        $res[] = collect($value)->toArray();
    }
    //$html = '<select id="'.$dd_info->selector_class.'" class="form-control '.$dd_info->selector_class.(($dd_info->multiselect)?' multiselect':'').'" name="'.$dd_info->field_name.'[]" '.(($dd_info->multiselect)?'multiple':'').'>';
    $html = '<select id="'.$dd_info->field_id.'" class="form-control '.$dd_info->selector_class.' '.$mendatory.'" name="'.$dd_info->field_name.'[]" '.(($dd_info->multiselect)?'multiple':'').'>';

    foreach($res as $k=>$v)
    {
        $html .= '<option '.(in_array($v[$dd_info->option_id], (isset($post[$dd_info->field_name])?$post[$dd_info->field_name]:[])) ? 'selected="selected"' : '').' value="'.$v[$dd_info->option_id].'">'.$v[$dd_info->option_value].'</option>';
    }
    $html .= '</select>';
    return $html;
}
function dropdownListSingle($id)
{
    $dd_info = DB::table('dropdown_list')->where('dd_id',$id)->first();
    $data_info = DB::select($dd_info->query);
    $res=[];
    foreach ($data_info as $value){
        $res[] = collect($value)->toArray();
    }
    $html = '<select class="form-control '.$dd_info->selector_class.'" name="'.$dd_info->field_name.'">';
    $html .= '<option value="">Select One</option>';
    foreach($res as $k=>$v)
    {
        $html .= '<option value="'.$v[$dd_info->option_id].'">'.$v[$dd_info->option_value].'</option>';
    }
    $html .= '</select>';
    return $html;
}



function get_zones()
{
    $query = DB::table('distribution_houses')
        ->select('zones.id','zones.zone_name as name')
        ->leftJoin('zones','zones.id','=','distribution_houses.zones_id')
        ->get()->toArray();
    return $query;
}
function get_regions()
{
    $query = DB::table('distribution_houses')
        ->select('regions.id','regions.region_name as name','distribution_houses.zones_id')
        ->leftJoin('regions','regions.id','=','distribution_houses.regions_id')
        ->groupBy('distribution_houses.regions_id')
        ->get()->toArray();
    return $query;
}
function get_territories()
{
    $query = DB::table('distribution_houses')
        ->select('territories.id','territories.territory_name as name','distribution_houses.regions_id')
        ->leftJoin('territories','territories.id','=','distribution_houses.territories_id')
        ->groupBy('distribution_houses.territories_id')
        ->get()->toArray();
    return $query;
}
function get_house()
{
    $query = DB::table('distribution_houses')
        ->select('id','point_name as name','distribution_houses.territories_id')
        ->get()->toArray();
    return $query;
}
function get_aso()
{
    $query = DB::table('routes')
        ->select('users.id','users.name','routes.distribution_houses_id')
        ->leftJoin('users','users.id','=','routes.so_aso_user_id')
        ->groupBy('routes.so_aso_user_id')
        ->get()->toArray();
    return $query;
}
function get_route()
{
    $query = DB::table('routes')
        ->select('routes.id','routes.routes_name as name','routes.so_aso_user_id')
        ->get()->toArray();
    return $query;
}

function viewReportOptionList()
{
    //$usertype = Auth::user()->user_type;
    $menuid = decrypt($_GET['mid']);
    //$viewReport = array('zone','region','territory','house');

    $data = DB::table('view_reports_previlege')
        ->select('view_reports.level','view_reports.value')
        ->leftJoin('view_reports','view_reports.id','=','view_reports_previlege.view_reports_id')
        ->where('view_reports_previlege.menu_id',$menuid)
        ->get()->toArray();
    return $data;
}
?>