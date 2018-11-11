<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Routes;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    private function getAso($type,$id){
        $info = User::where('id',$id)->first();
        //debug($info,1);
        $query=User::query();
        if($type === 'admin'){
            $query=$query->where('user_type','market');
        }
        if($type === 'zone'){
            if($info->exists()) {
                $query = $query->where('user_type', 'market')->where('zones_id', $info->zones_id);
            }
        }

        if($type === 'region'){
            if($info->exists()) {
                $query = $query->where('user_type', 'market')->where('regions_id', $info->regions_id);
            }
        }

        if($type === 'territory'){
            if($info->exists()){
                $query=$query->where('user_type','market')->where('territories_id',$info->territories_id);
            }
        }

        if($type === 'house'){
            if($info->exists()){
                $query=$query->where('user_type','market')->where('distribution_house_id',$info->distribution_house_id);
            }
        }

        if($type === 'market'){
            if($info->exists()){
                $query=$query->where('user_type','market')->where('territories_id',$info->territories_id);
            }
        }




        $result=$query->get(['id']);
        if(!$result->isEmpty()){
            $result = $result->toArray();
        }
        else{
            $result=[];
        }
        return $result;
    }

    protected function authenticated(Request $request, $user)
    {
        sku_details_generate();
        $request->session()->put('selected_module',$user->default_module_id);
        $request->session()->put('user_type',$user->user_type);
        $selected_aso = $this->getAso($user->user_type,$user->id);
        $routes = Routes::whereIn('so_aso_user_id',array_column($selected_aso,'id'))->get(['id']);
        $request->session()->put('routes_list',!$routes->isEmpty() ? json_encode(array_column($routes->toArray(),'id')): json_encode([]));


    }
}
