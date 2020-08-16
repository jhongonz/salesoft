<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Domains
    |--------------------------------------------------------------------------
    |
    | The domain configuration is used to define the different domains to which 
    | the application responds.
    |
    */
    
    'panel' => env('APP_DOMAIN_PANEL', null),

    /*
    |--------------------------------------------------------------------------
    | Root
    |--------------------------------------------------------------------------
    |
    | Esta configuración es usada para los directorios base
    |
    */

    'base_web' => env('BASE_ROUTE_WEB', 'Http/routes/web/')
];