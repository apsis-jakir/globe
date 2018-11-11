<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Auth;

class CommonController extends Controller {
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

    public function getAllPlaces()
    {
        $zones = get_zones();
        $regions = get_regions();
        $territories = get_territories();
        $house = get_house();
        $aso = get_aso();
        $route = get_route();
        //debug(array('route'=>$route),1);
        return json_encode(array('zones'=>$zones,'regions'=>$regions,'territories'=>$territories,'houses'=>$house,'aso'=>$aso,'route'=>$route));
        //debug($result,1);
    }
}
