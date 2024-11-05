<?php
include '../koneksi.php';

// Ambil Seluruh Produk
$result = $kon->query("SELECT * FROM produk");
$products = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
  $produk = json_encode($products);
  echo "<script>console.log('produk: ',$produk);</script>";
}

// Ambil Seluruh Toko
$result = $kon->query("SELECT * FROM toko");
$shops = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $shops[] = $row;
  }
  $toko = json_encode($shops);
  echo "<script>console.log('toko: ',$toko);</script>";
}

if (isset($_POST['submit'])) {
  // Ambil data dari form
  $produk = $_POST['produk'];
  $toko = $_POST['toko'];
  $harga = $_POST['harga'];
  $tgl = $_POST['tgl'];
  $jumlah = $_POST['jumlah'];

  // Cek apakah transaksi sudah ada
  $checkQuery = "SELECT * FROM transaksi WHERE prod_id = '$produk' AND toko_id = '$toko' LIMIT 1";
  $checkResult = $kon->query($checkQuery);

  if ($checkResult->num_rows > 0) {
    // Jika transaksi sudah ada, lakukan UPDATE
    $updateQuery = "UPDATE transaksi SET harga = '$harga', tgl = '$tgl', jumlah = '$jumlah' 
                    WHERE prod_id = '$produk' AND toko_id = '$toko'";
    $kon->query($updateQuery);
  } else {
    // Jika tidak ada, lakukan INSERT
    $insertQuery = "INSERT INTO transaksi (prod_id, toko_id, harga, tgl, jumlah) 
                    VALUES ('$produk', '$toko', '$harga', '$tgl', '$jumlah')";
    $kon->query($insertQuery);
  }

  // Simpan data ke database
  $sql = "INSERT INTO transaksi (`prod_id`, `toko_id`, `harga`, `tgl`, `jumlah`) VALUES ('$produk', '$toko', '$harga', '$tgl', '$jumlah')";

  if ($kon->query($sql) === TRUE) {
    echo "<script>console.log('Transaksiberhasil ditambah: \\nProduk = $produk, Toko = $toko, Harga = $harga, Tgl = $tgl, Jumlah = $jumlah');</script>";
    $success = "Transaksi Berhasi Ditambahkan!";
  } else {
    echo "Error: " . $sql . "<br>" . $kon->error;
  }
}

$kon->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../layout/head.php'; ?>
  <title>Tambah Transaksi</title>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- background -->
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = 'produk';
  include '../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid p-0">
        <h6 class="font-weight-bolder text-white mb-0">Tambah Data Transaksi</h6>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row align-items-stretch">
        <div class="col-lg-8">
          <div class="card" id="reader"></div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <a href="<?= $url ?>transaksi/" class="btn btn-icon btn-3 btn-secondary m-2" type="button">
              <span class="btn-inner--icon"><i class="fa-solid fa-arrow-left"></i></span>
              <span class="btn-inner--text">Kembali</span>
            </a>
            <div class="card-body pt-0">
              <?php
              // Jika ada pesan error, tampilkan
              if (isset($success)) {
                echo "<span class='badge bg-gradient-success'>$success</span>";
              }
              ?>
              <form id="transaksi-form" class="mb-0" method="POST" action="">
                <div class="form-group">
                  <label for="produk">Produk</label>
                  <select class="form-select" name="produk" id="produk">
                    <?php foreach ($products as $produk): ?>
                      <option value="<?= htmlspecialchars($produk['bar']); ?>">
                        <?= htmlspecialchars(string: $produk['bar']); ?> |
                        <?= htmlspecialchars(string: $produk['nama']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="toko">Toko</label>
                  <select class="form-select" name="toko" id="toko">
                    <?php foreach ($shops as $shop): ?>
                      <option value="<?= htmlspecialchars($shop['id']); ?>">
                        <?= htmlspecialchars(string: $shop['id']); ?> |
                        <?= htmlspecialchars(string: $shop['nama']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="harga">Harga</label>
                  <input type="number" class="form-control" name="harga" id="harga" required>
                </div>
                <input type="datetime" hidden name="tgl" value="2024-09-22 02:46:34">
                <input type="number" hidden name="user" value="1">
                <input type="number" hidden name="jumlah" value="1">
                <button type="submit" name="submit" class="btn btn-primary mb-0">Tambah Data</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer px-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              ©2024, made for All <i class="fa fa-globe"></i> by
              <a href="#" class="font-weight-bold">Marini</a>
              for Study Geographic Informastion System
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <script>
    // Ini adalah fungsi yang ingin Anda jalankan saat halaman di-load
    const produkSelect = document.getElementById('produk');
    const tokoSelect = document.getElementById('toko');
    const hargaInput = document.getElementById('harga');

    // function initPage() {
    // }

    // Jalankan saat DOM sudah siap
    document.addEventListener('DOMContentLoaded', function () {
      console.log('load page...');
      // Event listener untuk mendeteksi perubahan
      produkSelect.addEventListener('change', checkTransaction);
      tokoSelect.addEventListener('change', checkTransaction);
    });

    function checkTransaction() {
      const produkId = produkSelect.value;
      const tokoId = tokoSelect.value;
      console.log("produkId: ", produkId, "\ntokoId: ", tokoId)
      if (produkId && tokoId) {
        // Kirim request AJAX ke server untuk cek apakah ada transaksi yang sesuai
        fetch('../fungsi/check_transaction.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            produk: produkId,
            toko: tokoId
          })
        })
          .then(response => response.json())
          .then(data => {
            if (data.exists) {
              // Jika transaksi sudah ada, isi input harga
              console.log('transaksi sudah ada, isi input harga: ', data.harga);
              hargaInput.value = data.harga;
            } else {
              // Jika tidak ada transaksi, kosongkan input harga
              console.log('transaksi belum ada, isi input harga: akan di kosongkan...');
              hargaInput.value = '';
            }
          })
          .catch(error => console.error('Error:', error));
      }
    }
    checkTransaction();

    // Jalankan langsung saat halaman diload (termasuk setelah refresh)
    // initPage();
  </script>

  <?php include '../layout/scripts.php' ?>
</body>

</html>