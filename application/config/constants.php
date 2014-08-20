<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* Site-specific constants */
if(ENVIRONMENT == 'development') {
	# Base url defined in application/hooks/language.php
	define('ASSETS_ROOT', BASE_URL.'/static');
	define('UPLOADS_ROOT', BASE_URL.'/uploads');

	define('CSDATE_SERVER_ADJUST', -3);
	define('CSDATE_DATABASE_ADJUST', 0);
}
else {
	# Base url defined in application/hooks/language.php
	define('ASSETS_ROOT', 'http://static.textchange.ca');
	define('UPLOADS_ROOT', 'http://uploads.textchange.ca');
	
	define('CSDATE_SERVER_ADJUST', +1);
	define('CSDATE_DATABASE_ADJUST', +1);
}


/* Site configuration */
define('SMALL_ITEMS_PER_PAGE', 16);
define('LARGE_ITEMS_PER_PAGE', 8);


/* End of file constants.php */
/* Location: ./application/config/constants.php */