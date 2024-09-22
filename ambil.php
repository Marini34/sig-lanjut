<?php

// digunakan untuk fetcing data map di index
header('Content-Type: application/json');

include 'koneksi.php';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Query untuk mengambil data PoI untuk megnhitung jarak
  $stmt = $pdo->prepare("
    SELECT 
      transaksi.id AS id, 
      produk.bar AS bar, 
      transaksi.harga AS harga, 
      toko.nama AS toko, 
      toko.lat AS lat, 
      toko.lng AS lng
    FROM transaksi 
    JOIN produk ON transaksi.prod_id = produk.bar 
    JOIN toko ON transaksi.toko_id = toko.id
    WHERE transaksi.prod_id = :product_id 
  ");
  $stmt->bindParam(':product_id', $_GET['id'], PDO::PARAM_STR);

  // Execute the statement
  $stmt->execute();

  $poiData = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Mengembalikan data dalam format JSON
  echo json_encode($poiData);
} catch (PDOException $e) {
  echo json_encode(['error' => $e->getMessage()]);
}