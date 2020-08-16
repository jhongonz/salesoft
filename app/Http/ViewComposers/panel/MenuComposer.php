<?php

namespace App\Http\ViewComposers\panel;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class MenuComposer
{
    public function compose(View $view)
    {
    	if (session()->exists('user'))
    	{
	    	$route = \Route::current();
    		$parts = explode('/',$route->uri);
    		$lroute = $parts[0];

        	$fathers = \App\Models\Module::where('mod_state',ST_ACTIVE)
        		->where('mod__mod_id', null)
	        	->orderBy('mod_position','ASC')
	        	->get(['mod_id','mod_state','mod_link','mod_name','mod_icon_menu','mod_icon_panel','mod_hidden']);

	        $menu = array();
	        $options = array();
	        $independets = array();
	    	foreach($fathers as $father)
	    	{
	    		$expand = false;
	    		if ($father->mod_link == null)
	    		{
			        $children = \DB::table(TB_MODULES)
			        	->join(TB_PRIVILEGES,TB_MODULES.'.mod_id','=',TB_PRIVILEGES.'.pri__mod_id')
			        	->where('pri__pro_id',session('user')->user__pro_id)
			        	->where('mod__mod_id',$father->mod_id)
			        	->where('mod_state',ST_ACTIVE)
			        	->orderBy('mod_position','ASC')
			        	->get(['mod_id','mod_state','mod_link','mod_name','mod_icon_menu','mod_icon_panel','mod_hidden']);

			        $minors = [];
			        if (count($children) > 0)
			        {
			        	$minors = array();
			        	foreach ($children as $child)
			        	{
			        		$regChild = new \stdClass();
			        		$regChild->id = $child->mod_id;
			        		$regChild->name = $child->mod_name;
			        		$regChild->link = $child->mod_link;
			        		$regChild->iconMenu = $child->mod_icon_menu;
			        		$regChild->iconPanel = $child->mod_icon_panel;
			        		$regChild->hidden = ($child->mod_hidden == DB_TRUE) ? true : false;

			        		$minors[] = $regChild;

			        		if ($child->mod_link == $lroute)
			        		{
			        			$expand = true;
			        		}
			        	}
			        }

		    		$regFather = new \stdClass();
		    		$regFather->id = $father->mod_id;
		    		$regFather->name = $father->mod_name;
		    		$regFather->link = $father->mod_link;
		    		$regFather->iconMenu = $father->mod_icon_menu;
		    		$regFather->iconPanel = $father->mod_icon_panel;
		    		$regFather->haveChild = (count($minors) > 0) ? true : false;
		    		$regFather->children = $minors;
		    		$regFather->expand = $expand;

		    		$options[] = $regFather;
	    		}
	    		else
	    		{
	    			$regUnique = new \stdClass();
		    		$regUnique->id = $father->mod_id;
		    		$regUnique->name = $father->mod_name;
		    		$regUnique->link = $father->mod_link;
		    		$regUnique->iconMenu = $father->mod_icon_menu;
		    		$regUnique->iconPanel = $father->mod_icon_panel;
		    		$regUnique->hidden = ($father->mod_hidden == DB_TRUE) ? true : false;
		    		$regUnique->haveChild = false;
		    		$regUnique->children = null;
		    		$regUnique->expand = false;

		    		$independets[] = $regUnique;	
	    		}
	    	}
	    	
	    	$menu = array_merge($options,$independets);
	    	$view->with('menu',$menu);
    	}
    	else
    	{
    		Auth::logout();
			session()->flush();
			return redirect('/');
    	}
    }
}
