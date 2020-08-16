<?php

namespace App\Providers;

use Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view){
            $domain = Request::root();

            switch($domain)
            {
                case config('baseroot.panel') : 

                    $base = (request()->ajax()) ? 'panel.base.layout_ajax' : 'panel.base.layout_master';
                    $view->with('layout',$base);

                    if (!request()->ajax())
                    {
                        //PANEL'S MENU
                        View::composer('panel.menu.menu_principal','App\Http\ViewComposers\panel\MenuComposer');

                        //USER'S MENU
                        View::composer('panel.menu.menu_user','App\Http\ViewComposers\panel\ProfileComposer');
                    }

                break;

                default:

                    Auth::logout();
                    session()->flush();

                    return redirect('/');
                break;
            }

        });
    }
}
