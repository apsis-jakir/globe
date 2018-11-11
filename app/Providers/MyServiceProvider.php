<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ModuleController;
class MyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //View::share('menus', MenuController::menuList());
        //View::share('modules', ModuleController::getModuleList());
        //View::share('selected_module', 1);
        //$module_id=Session::get('selected_module');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
