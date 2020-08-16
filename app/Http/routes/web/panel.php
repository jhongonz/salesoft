<?php

//ROUTES TO APPLICATION'S BACKEND
Route::namespace('Panel')->group(function(){

	//LOGIN'S ROUTES	
	Route::get('/sandbox','Login@sandbox')->middleware('guest')->name('panel.sandbox');
	Route::get('/','Login@index')->middleware('guest')->name('panel.login');
	Route::post('/login','Login@authenticate')->middleware('guest')->name('validate');
	Route::get('/home','Login@home')->middleware('auth')->name('home');
	Route::post('/logout','Login@logout')->middleware('auth')->name('logout');

	Route::middleware('auth')->group(function(){

		//ADMINISTRATOR'S ROUTES
		Route::prefix('manager')->group(function(){
			Route::get('/','Managers@index')->name('manager.index');
			Route::post('/get-main-list','Managers@getMainList')->name('manager.mainList');
			Route::post('/change-state','Managers@changeState')->name('manager.changeState');
			Route::post('/delete','Managers@delete')->name('manager.delete');
			Route::post('/get-registry','Managers@getRegistry')->name('manager.getRegistry');
			Route::post('/save','Managers@store')->name('manager.save');
		});

		//CUSTOMERS'S ROUTES
		Route::prefix('customer')->group(function(){
			Route::get('/','Customers@index')->name('customer.index');
			Route::post('/get-main-list','Customers@getMainList')->name('customer.mainList');
			Route::post('/change-state','Customers@changeState')->name('customer.changeState');
			Route::post('/delete','Customers@delete')->name('customer.delete');
			Route::post('/get-registry','Customers@getRegistry')->name('customer.getRegistry');
			Route::post('/save','Customers@store')->name('customer.save');
			Route::post('/get-customer','Customers@getCustomer')->name('customer.getCustomer');
		});

		//CUSTOMERS'S ROUTES
		Route::prefix('category')->group(function(){
			Route::get('/','Categories@index')->name('category.index');
			Route::post('/get-main-list','Categories@getMainList')->name('category.mainList');
			Route::post('/change-state','Categories@changeState')->name('category.changeState');
			Route::post('/delete','Categories@delete')->name('category.delete');
			Route::post('/get-registry','Categories@getRegistry')->name('category.getRegistry');
			Route::post('/save','Categories@store')->name('category.save');
		});

		//PRODUCT'S ROUTES
		Route::prefix('product')->group(function(){
			Route::get('/','Products@index')->name('product.index');
			Route::post('/get-main-list','Products@getMainList')->name('product.mainList');
			Route::post('/change-state','Products@changeState')->name('product.changeState');
			Route::post('/delete','Products@delete')->name('product.delete');
			Route::post('/get-registry','Products@getRegistry')->name('product.getRegistry');
			Route::post('/save','Products@store')->name('product.save');
			Route::post('/get-products','Products@getProducts')->name('products.getProducts');
		});

		//SALEPOINT'S ROUTES
		Route::prefix('salepoint')->group(function(){
			Route::get('/','Sales@index')->name('sale.salepoint');
			Route::post('/save-customer','Sales@saveCustomer')->name('sale.saveCustomer');
			Route::post('/save-product','Sales@saveProduct')->name('sale.saveProduct');
			Route::post('/process','Sales@process')->name('sale.process');
		});

		//SALE'S ROUTES
		Route::prefix('sale')->group(function(){
			Route::get('/','Sales@records')->name('sale.record');
			Route::post('/get-main-list','Sales@getMainList')->name('sale.mainList');
			Route::post('/change-state','Sales@changeState')->name('sale.changeState');
			Route::post('/delete','Sales@delete')->name('sale.delete');
		});
	});
});