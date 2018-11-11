<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use URL;
use Session;
use Auth;
use Illuminate\Support\Facades\Input;
class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public  $module_id;
    public $allParentId = array();
    
    public function index()
    {
        
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
     public static  function menuList()
    {
         $module_id=Session::get('selected_module');
         $userId=Auth::id();
         $levelId=1;
         //echo $module_id;
         //$module_id=$request->session()->get('selected_module') ;;
         //return "test";
         $_this = new self;
         //echo $module_id.'jj';
         //exit();
        $menuList = Menu::where('is_active', '1')
                ->where('modules_id',$module_id)
                ->whereIn('id',function($query) use ($userId,$levelId){
                           $query->select('menus_id')
                            ->from('privilege_menus')
                            ->where('users_id', $userId)
                            ->orWhereIn('user_levels_id', function($query) use ($userId){
                            $query->select('user_levels_id')
                             ->from('privilege_levels')
                             ->where('users_id', $userId);
                       });
                      })
               ->orderBy('sort_number', 'asc')
               ->get()->all();
        $tree = $_this->buildTree($menuList);

        $menuHtml = $_this->makeMenuTree($tree);
        return $menuHtml;
        
    }
function buildTree(array $elements, $parentId = 0) {
   // $elements = $elementarray;
    $branch = array();
// echo "<pre>";
//        print_r($elements);
//        exit();
    foreach ($elements as $element) {
        if ($element['parent_menus_id'] == $parentId) {
            $children = $this->buildTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

//public function makeMenuTree($tree,$pid=null){
//    if(isset($_GET['mid']))
//    {
//        $mID = $_GET['mid'];
//    }
//    else
//    {
//        $mID = '';
//    }
//    $html='';
//
//
//    $menusId = $this->getAllParentId($mid);
//
//
//    foreach ($tree as $key=>$val){
//        if($val['children']){
////
//            $html .='<li class="treeview">
//              <a href="#">
//                <i class="'.$val['icon_class'].'"></i> <span>'.$val['name'].'</span>
//                <span class="pull-right-container">
//                  <i class="fa fa-angle-left pull-right"></i>
//                </span>
//              </a>';
//            $html .='<ul class="treeview-menu">';
//            $html .= $this->makeMenuTree($val['children']);
//            $html .='</ul>';
//            $html .='</li>';
//        }else{
//            $html .='<li class="">
//              <a href="'.URL::to($val['menu_url'].'?mid='.encrypt($val['id'])).'">
//                <i class="'.$val['icon_class'].'"></i> <span>'.$val['name'].'</span>
//                <span class="pull-right-container">
//                   <!--<small class="label pull-right bg-green">new</small>-->
//                </span>
//              </a></li>';
//
//        }
//    }
//    return $html;
//}



    public function makeMenuTree($tree, $pid = null)
    {
        if (isset($_GET['mid'])) {
            $mID = $_GET['mid'];
            $mid = decrypt(Input::get('mid'));
        } else {
            $mID = '';
            $mid = '';
        }
        $html = '';


        $menusId = $this->getAllParentId($mid);

        foreach ($tree as $key => $val) {
            if ($val['children']) {
                if (in_array($val['id'], $menusId))
                    $active = 'active';
                else
                    $active = '';


                $html .= '<li class="treeview ' . $active . ' ">
             <a href="#">
               <i class="' . $val['icon_class'] . '"></i> <span>' . $val['name'] . '</span>
               <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
               </span>
             </a>';
                $html .= '<ul class="treeview-menu">';
                $html .= $this->makeMenuTree($val['children']);
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $selectedMenu = '';
                if(isset($_GET['mid']))
                {
                    if($val['id']==decrypt(@$_GET['mid']))
                    {
                        $selectedMenu = 'active';
                    }
                }
                 $html .= '<li class="'.$selectedMenu.'">
                 <a href="' . URL::to($val['menu_url'] . '?mid=' . encrypt($val['id'])) . '">
                   <i class="' . $val['icon_class'] . '"></i> <span>' . $val['name'] . '</span>
                   <span class="pull-right-container">
                      <!--<small class="label pull-right bg-green">new</small>-->
                   </span>
                 </a></li>';

            }
            //$html .= "<li>".$val['menus_name']."</li>";
        }
        //echo $html;
        //exit();
        return $html;
    }


    public function getAllParentId($id)
    {
        $menusId = Menu::where('id', '=', $id)->pluck('parent_menus_id');
        foreach ($menusId as $key => $val) {
            $this->allParentId[$id] = $val;
            if ($val != 0) {
                $this->getAllParentId($val);
            } else {

            }
        }
        return $this->allParentId;
    }

}
