<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomController extends Controller
{
    public function render($view,$data=null){
        $menu = $this->menuList();
        return view('pages.dashboard');
    }
     public static  function menuList()
    {
         //return "test";
         $_this = new self;
        $menuList = Menu::where('status', 'Active')
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
            $children = $this->buildTree($elements, $element['menus_id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}
public function makeMenuTree($tree){
    $html='';
    foreach ($tree as $key=>$val){
        $html .='<li class="active treeview">
          <a href="#">
            <i class="'.$val['icon_class'].'"></i> <span>'.$val['menus_name'].'</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>';
        //$html .= "<li>".$val['menus_name']."</li>";
    }
    //echo $html;
    //exit();
    return $html;
}
}
