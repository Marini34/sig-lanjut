<?php
include '../koneksi.php';
// Periksa apakah ada request POST dengan JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['produk'], $data['toko'])) {
    $produk = $kon->real_escape_string($data['produk']);
    $toko = $kon->real_escape_string($data['toko']);

    // Cek apakah ada transaksi dengan prod_id dan toko_id yang sesuai
    $query = "SELECT * FROM transaksi WHERE prod_id = '$produk' AND toko_id = '$toko' LIMIT 1";
    $result = $kon->query($query);

    if ($result->num_rows > 0) {
        // Jika transaksi ditemukan
        $row = $result->fetch_assoc();
        echo json_encode([
            'exists' => true,
            'harga' => $row['harga']
        ]);
    } else {
        // Jika tidak ada transaksi
        echo json_encode([
            'exists' => false
        ]);
    }
} else {
    echo json_encode(['error' => 'Invalid data']);
}

$kon->close();

