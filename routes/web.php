<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain(config('baseroot.panel'))->group(function() {

	///ROUTES TO APPLICATION'S PANEL
	if (file_exists(app_path(config('baseroot.base_web').'panel.php')))
	{
		include_once(app_path(config('baseroot.base_web').'panel.php'));
	}
});
