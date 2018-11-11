<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use Session;
use Auth;
class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public static  function getModuleList(){
         $userLevel =100;
         $userId=Auth::id();
		 //echo $userId;
		 //exit();
         //$module_id=Session::all();
         //echo "<pre>";
         //print_r($module_id);
         //exit();
        $moduleList = Module::where('is_active', '1')
                      ->whereIn('id',function($query) use ($userId){
                           $query->select('modules_id')
                            ->from('privilege_modules')
                            ->where('users_id', $userId)
                            ->orWhereIn('user_levels_id', function($query) use ($userId){
                            $query->select('user_levels_id')
                             ->from('privilege_levels')
                             ->where('users_id', $userId);
                       });
                      })
                      ->get();
                      
//                       $moduleList = Module::where("status",'Active')
//                      ->whereIn('modules_id',function($query) use ($userId){
//                           $query->select('user_module_id')
//                            ->from('privilege_modules')
//                            ->where('user_id', $userId);
//                      })
//                      ->pluck('modules_name','modules_id');
        
        return $moduleList;
        //echo "<pre>";
        //print_r($moduleList);
    }
    public  function moduleChanger(Request $request,$id){
        $moduleid= decrypt($id);
//        debug(session()->all()['selected_module'],1);
        //session('selected_module', $moduleid);
        $request->session()->forget('selected_module');
        $request->session()->put('selected_module', $moduleid);
        return redirect('/');
        //echo $moduleid;
        //exit();
        
    }
}
