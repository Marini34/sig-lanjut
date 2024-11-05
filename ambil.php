<?php

// digunakan untuk fetcing data map di index
header('Content-Type: application/json');

include __DIR__.'/koneksi.php';

try {
  if (empty($_GET['id'])) {
    throw new Exception('ID produk tidak boleh kosong');
  }

  // Query untuk mengambil data PoI untuk megnhitung jarak
  $stmt = $kon->prepare("
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
  echo json_encode(['PDO error' => $e->getMessage()]);
} catch (Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
}