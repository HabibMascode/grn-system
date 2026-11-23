<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// LOGIN
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');

// HOME ADMIN
$routes->get('home', 'HomeAdmin::index');

// HOME ACCOUNTING
$routes->get('homeacc', 'HomeAcc::index');

// LOGOUT
$routes->get('logout', 'Auth::logout');

// SEARCH ADMIN (GRN)
$routes->get('grn', 'Grn::index');
$routes->get('grn/form', 'Grn::form');
$routes->get('grn/edit', 'Grn::edit');
$routes->post('grn/submit', 'Grn::submit');

// TERIMA / TOLAK ACCOUNTING
$routes->get('accounting', 'Grn::accounting'); 
$routes->post('accounting/terima', 'Grn::terima'); 
$routes->post('accounting/tolak', 'Grn::tolak');

// KELOLA ACCOUNTING (hanya admin)
$routes->get('register',                  'Register::index');
$routes->get('kelola-accounting',         'Register::index');
$routes->post('register/save',            'Register::save');
$routes->post('register/delete/(:num)',   'Register::delete/$1');
$routes->post('register/update/(:num)',  'Register::update/$1');

// MANAJEMEN SUPPLIER (hanya admin)
$routes->get('supplier',                 'Supplier::index');
$routes->post('supplier/save',           'Supplier::save');
$routes->get('supplier/edit/(:num)',     'Supplier::edit/$1');
$routes->post('supplier/update/(:num)',   'Supplier::update/$1');
$routes->post('supplier/delete/(:num)',   'Supplier::delete/$1');
$routes->get('supplier/search',          'Supplier::search');

// PENGATURAN SUPPLIER ACCOUNTING (hanya admin)
$routes->get('accounting-supp',          'AccountingSupp::index');
$routes->post('accounting-supp/save',    'AccountingSupp::save');
$routes->get('accounting-supp/get/(:num)', 'AccountingSupp::getSuppliers/$1');

// ==========================================
// REST API ROUTES (Untuk Frontend Terpisah: Next.js / Vite / React / Vue)
// ==========================================
$routes->group('api', static function ($routes) {
    // Config & Info
    $routes->get('config', 'Api::config');

    // Auth
    $routes->post('auth/login',  'Api::login');
    $routes->post('login',       'Api::login');
    $routes->get('auth/me',      'Api::me');
    $routes->post('auth/logout', 'Api::logout');
    $routes->post('logout',      'Api::logout');

    // GRN Dokumen
    $routes->get('grn',               'Api::getGrnList');
    $routes->get('grn/(:num)',        'Api::getGrnDetail/$1');
    $routes->post('grn/submit',       'Api::submitGrn');

    // Accounting
    $routes->get('accounting',        'Api::getAccountingList');
    $routes->post('accounting/terima', 'Api::terimaAccounting');
    $routes->post('accounting/tolak',  'Api::tolakAccounting');

    // Supplier
    $routes->get('suppliers',         'Api::getSuppliers');
});
