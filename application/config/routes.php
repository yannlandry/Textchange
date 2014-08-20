<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


# Pages principales
$route['login'] = "main/login";
$route['login/recover'] = "main/recover";
$route['logoff'] = "main/logoff";
$route['logout'] = "main/logoff";
$route['signup'] = "main/signup";
$route['activate'] = "main/activate";
$route['activate/resend'] = "main/resend";
$route['info'] = "main/info";
$route['info/(:any)'] = "main/info/$1";
$route['contact'] = "main/contact";


# Utilisateurs
$route['users'] = "users/index";
$route['users/(:any)/edit/avatar'] = "users/changepic/$1";
$route['users/(:any)/edit/password'] = "users/changepass/$1";
$route['users/(:any)/edit/email'] = "users/changemail/$1";
$route['users/(:any)/edit'] = "users/edit/$1";
$route['users/(:any)/report'] = "users/report/$1";
$route['users/(:any)'] = "users/view/$1";
$route['users/delete'] = "users/delete";


# Messages privés
$route['messages'] = "messages/index";
$route['messages/(:any)'] = "messages/conversation/$1";


# Établissements
$route['schools'] = "schools/index";
$route['schools/add'] = "schools/add";
$route['schools/(:num)'] = "schools/edit/$1";


# Livres
$route['books'] = "books/index";
$route['books/domain/(:num)'] = "books/domain/$1";
$route['books/isbn/(:num)'] = "books/isbn/$1";
$route['books/search'] = "books/search";
$route['books/add'] = "books/add";
$route['books/ad/(:num)'] = "books/ad/$1";
$route['books/ad/(:num)/edit'] = "books/edit/$1";
$route['books/ad/(:num)/delete'] = "books/delete/$1";
$route['books/ad/(:num)/report'] = "books/report/$1";
$route['books/ad/(:num)/email'] = "books/email/$1";
$route['books/search'] = "books/search";
$route['books/alerts'] = "books/alerts";


# Administration
$route['admin'] = "admin/index";
$route['admin/reports'] = "admin/reports";
$route['admin/reports/ads'] = "admin/adsreports";
$route['admin/reports/profiles'] = "admin/profilesreports";
$route['admin/management'] = "admin/management";
$route['admin/management/domains'] = "admin/domains";
#$route['admin/management/slideshow'] = "admin/slideshow";
$route['admin/maintenance'] = "admin/maintenance";
#$route['admin/maintenance/config'] = "admin/config";
$route['admin/maintenance/logs'] = "admin/logs";


# Erreurs
$route['error/(:num)'] = "error/index/$1";


# Par défaut
$route['default_controller'] = "main/index";



/* End of file routes.php */
/* Location: ./application/config/routes.php */