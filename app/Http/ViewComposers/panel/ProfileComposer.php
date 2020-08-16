<?php

namespace App\Http\ViewComposers\panel;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileComposer
{
    public function compose(View $view)
    {
        $user = null;
        if ( session()->exists('user') )
        {
            $user = [
                'name'=>session('user')->admin_name
            ];

            $view->with('user',(object)$user);
        }
        else
        {
            Auth::logout();
            session()->flush();
            return redirect('/');
        }
    }
}
