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
