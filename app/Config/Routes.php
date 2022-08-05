<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('UserController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('operator', 'OperatorController::index', ['as' => 'operator']);
$routes->get('dashboard/(:segment)', 'AdminController::index/$1', ['as' => 'dashboard']);
$routes->cli("cron/run", "CronController::runDaily");
$routes->cli("cron/send_bulk_promotional_sms_to_student", "CronController::promotionalSmsToAllStudentsCron");
$routes->cli("cron/send_single_test_sms", "CronController::smsTest");
$routes->post('api_login', 'ApiController::login');
$routes->get('api/case', 'ApiController::getCases');
$routes->get('api/mitra', 'ApiController::getMitra');
$routes->get('api/attendance', 'ApiController::getAttendance');
$routes->get('api/intimation_sms', 'ApiController::sendIntimationSms');
$routes->get('api/connected_sms', 'ApiController::sendNormalConnectedCallsSms');

// Myth:Auth routes file.
$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], static function ($routes) {
    // Login/out
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
