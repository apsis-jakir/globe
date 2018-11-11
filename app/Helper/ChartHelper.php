<?php
function seriesData($data,$type)
{
    if($type == 'series')
    {
        $series = array();
        foreach($data as $key => $value)
        {
            array_push($series,(int)(($value->tq)?$value->tq:0));
        }

        $series = json_encode($series);
        return $series;
    }
    else if($type == 'categories')
    {
        $categories = array();
        foreach($data as $key => $value)
        {
            array_push($categories,$value->brand_name);
        }
        $category = json_encode($categories);
        return $category;
    }

}


function pieContributionSeries($data)
{
    $series = [];
    foreach($data as $key => $value)
    {
        $series[] = [
            'name' => $value->zone_name,
            'y' => ($value->tquantity)?(int)$value->tquantity:0
        ];
    }

    return str_replace('"y"','y',str_replace('"name"','name',json_encode($series)));
}


function lineProducttivityComparison($data,$currentMonth=null)
{
    $dataArrange = [];
    foreach($data as $val)
    {
        $dataArrange[$val->sdate] = $val->tq;
    }


    $series = array();
    $months = tweelveMonth(date('Y'));
    $k =11;
    $index = 1;
    foreach($months as $key=>$val)
    {
        if(isset($dataArrange[$months[$k]]))
        {
            array_push($series,(float)$dataArrange[$months[$k]]);
        }
        else
        {
            array_push($series,0);
        }
        if($index==$currentMonth)
        {
            break;
        }
        $k--;
        $index++;
    }
    return json_encode($series);
}


function focusedUnfocusedSkueShortNameArray($type=null)
{
    $query = DB::table('skues');
    if($type)
    {
        $query->where('is_focused',1);
    }
    else
    {
        $query->where('is_focused',0);
    }
    $result = $query->get()->toArray();
    //debug($result,1);
    return array_column($result,'short_name');
}

?>