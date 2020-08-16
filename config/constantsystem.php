<?php

/*
|--------------------------------------------------------------------------
| Database Constants
|--------------------------------------------------------------------------
| These are general constants used in the application
*/

//REGION SETTING
define('TIMEZONE','America/Lima');

//STATUS LOGIC
define('STATUS_OK', 2);
define('STATUS_FAIL', 1);
define('DB_TRUE', 1);
define('DB_FALSE', 0);

//TYPE PERSONAL DOCUMENTS
define('DNI','dni');
define('IMMIGRATION','immigration');
define('PASSPORT','passport');
define('FOREIGN','foreign');
define('TYPE_DOCUMENT',[
	DNI => 'Documento nacional de identidad',
	IMMIGRATION => 'Carnet de extranjeria',
	PASSPORT => 'Pasaporte',
	FOREIGN => 'Documento de identidad extranjero'
]);
define('TYPE_DOCUMENT_ABBREVIATION',[
	DNI => 'D.N.I',
	IMMIGRATION => 'C.E',
	PASSPORT => 'P',
	FOREIGN => 'DIE'
]);

//GENDER'S TYPE
define('MALE','male');
define('FEMALE','female');
define('TYPE_GENDER',[
	MALE => 'Masculino',
	FEMALE => 'Femenino'
]);

//IGV
define('IGV_PORCENTAGE',18);