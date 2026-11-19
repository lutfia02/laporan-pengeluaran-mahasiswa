
<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Dashboard
$routes->get('/', 'Pengeluaran::index');

// Data Pengeluaran
$routes->get('pengeluaran', 'Pengeluaran::index');
$routes->get('pengeluaran/tambah', 'Pengeluaran::create');
$routes->post('pengeluaran/simpan', 'Pengeluaran::store');

$routes->get('pengeluaran/edit/(:num)', 'Pengeluaran::edit/$1');
$routes->post('pengeluaran/update/(:num)', 'Pengeluaran::update/$1');

$routes->post('pengeluaran/hapus/(:num)', 'Pengeluaran::delete/$1');

// Laporan dan Statistik
$routes->get('laporan', 'Laporan::index');
