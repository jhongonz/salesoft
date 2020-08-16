<?php

/*
|--------------------------------------------------------------------------
| Database Constants
|--------------------------------------------------------------------------
| These are constants to manage the structure of the database and the actions of the data.
| The records are not deleted from the application, however, the delete action changes their 
| state and keeps it still naturally in the database.
|
| The naming of the tables is also defined in this part, this structure allows the table 
| configuration to be manipulated independently
*/

//REGISTER'S STATES
define('ST_DELETE', -1);
define('ST_DEFAULT', 0);
define('ST_NEW', 1);
define('ST_ACTIVE', 2);
define('ST_INACTIVE', 3);
define('ST_CONFIRMED', 4);
define('ST_APPROVED',5);
define('ST_REPROBATE',6);
define('ST_REGISTERED',7);

define('FORMAT_DATETIME_DATABASE','Y-m-d H:i:s');
define('FORMAT_DATE_DATABASE','Y-m-d');
define('FORMAT_TIME_DATABASE','H:i:s');

//TABLES
define('TB_MODULES','sk_modules');
define('TB_PROFILES','sk_profiles');
define('TB_PRIVILEGES','sk_privileges');
define('TB_USERS','sk_users');

define('TB_MANAGERS','app_managers');
define('TB_CUSTOMERS','app_customers');
define('TB_CATEGORIES','app_categories');
define('TB_PRODUCT','app_products');
define('TB_SALES','app_sales');
define('TB_SALES_DETAIL','app_sales_detail');
