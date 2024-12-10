<?php

// Get the request URI
$request = $_SERVER['REQUEST_URI'];

// Remove query parameters for clean routing
if (strpos($request, '?') !== false) {
  $request = explode('?', $request)[0];
} 

// Define routes and map to specific files
switch ($request) {
  // Routes for tambah transaksi
  case '/fungsi/check_transaction.php':
    require __DIR__ . '/../fungsi/check_transaction.php';
    break;
  // Routes for the 'produk' section
  case '/ambil':
  case '/ambil/':
  case '/ambil.php':
    require __DIR__ . '/../ambil.php';
    break;
  case '/produk':
  case '/produk/':
  case '/produk/index.php':
    require __DIR__ . '/../produk/index.php';
    break;
  case '/produk/tambah.php':
    require __DIR__ . '/../produk/tambah.php';
    break;
  case '/produk/edit.php':
    require __DIR__ . '/../produk/edit.php';
    break;

  // Routes for the 'toko' section
  case '/toko':
  case '/toko/':
  case '/toko/index.php':
    require __DIR__ . '/../toko/index.php';
    break;
  case '/toko/tambah.php':
    require __DIR__ . '/../toko/tambah.php';
    break;
  case '/toko/edit.php':
    require __DIR__ . '/../toko/edit.php';
    break;

  // Routes for the 'transaksi' section
  case '/transaksi':
  case '/transaksi/':
  case '/transaksi/index.php':
    require __DIR__ . '/../transaksi/index.php';
    break;
  case '/transaksi/tambah.php':
    require __DIR__ . '/../transaksi/tambah.php';
    break;
  case '/transaksi/edit.php':
    require __DIR__ . '/../transaksi/edit.php';
    break;

  // Routes for the 'api' section
  case '/api':
  case '/api/index.php':
    require __DIR__ . '/../api/index.php';
    break;

  // Default route (fallback or 404 page)
  default:
    require __DIR__ . '/../index.php';
    break;
}
