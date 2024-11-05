<?php
include '../koneksi.php';

try {
    // Periksa apakah ada request POST dengan JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['produk'], $data['toko'])) {
        $produk = $data['produk'];
        $toko = $data['toko'];

        // Cek apakah ada transaksi dengan prod_id dan toko_id yang sesuai
        $query = "SELECT * FROM transaksi WHERE prod_id = :produk AND toko_id = :toko LIMIT 1";
        $stmt = $kon->prepare($query);

        // Bind parameter
        $stmt->bindParam(':produk', $produk, PDO::PARAM_INT);
        $stmt->bindParam(':toko', $toko, PDO::PARAM_INT);

        // Eksekusi query
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Jika transaksi ditemukan
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
} catch (PDOException $e) {
    // Tangani error jika ada
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}