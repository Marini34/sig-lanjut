<?php
include __DIR__ . '/../koneksi.php';

$id = $_GET['id'];
$transaksi = null; // Initialize variable

// Ambil data transaksi berdasarkan ID
$query = $kon->prepare("SELECT * FROM transaksi WHERE id = :id");
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
if ($query->rowCount() > 0) {
  $transaksi = $query->fetch(PDO::FETCH_ASSOC);
  // $data = json_encode($transaksi);
  // echo "<script>console.log('Transaksi: ',$data);</script>";
}

// Ambil Data Produk
$query = $kon->prepare("SELECT * FROM produk");
$query->execute();

$products = [];
$namaProduk = null; // Ambil nama produk untuk select

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
  if ($row['bar'] == $transaksi['prod_id']) {
    $namaProduk = $row['nama'];
    continue;
  }
  $products[] = $row;
}

$produk = json_encode($products);
// echo "<script>console.log('produk kecuali transaksi: ',$produk);</script>";


// Ambil Seluruh Toko
$query = $kon->prepare("SELECT * FROM toko");
$query->execute();

$shops = [];
$namaToko = null; // Ambil nama toko untuk select

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
  if ($row['id'] == $transaksi['toko_id']) {
    $namaToko = $row['nama'];
    continue;
  }
  $shops[] = $row;
}

$toko = json_encode($shops);
// echo "<script>console.log('toko kecuali transaksi: ',$toko);</script>";

if (isset($_POST['submit'])) {
  // Ambil data dari form
  $produk = $_POST['produk'];
  $toko = $_POST['toko'];
  $harga = $_POST['harga'];
  $tgl = $_POST['tgl'];
  $jumlah = $_POST['jumlah'];
  $idTransaksi = $transaksi['id'];

  // Update data transaksi
  $sql = "UPDATE transaksi SET prod_id = :produk, toko_id = :toko, harga = :harga, tgl = :tgl, jumlah = :jumlah WHERE id = :idTransaksi";

  $query = $kon->prepare($sql);
  $query->bindParam(':produk', $produk, PDO::PARAM_STR);
  $query->bindParam(':toko', $toko, PDO::PARAM_INT);
  $query->bindParam(':harga', $harga, PDO::PARAM_INT);
  $query->bindParam(':tgl', $tgl, PDO::PARAM_STR);
  $query->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
  $query->bindParam(':idTransaksi', $idTransaksi, PDO::PARAM_INT);

  if ($query->execute()) {
    // Menampilkan data di console.log
    $produkLama = $transaksi['prod_id'];
    $tokoLama = $transaksi['toko_id'];
    $hargaLama = $transaksi['harga'];
    $tglLama = $transaksi['tgl'];
    $jumlahLama = $transaksi['jumlah'];
    // echo "<script>console.log('Data berhasil diupdate Dari: \\nProduk = $produkLama, Toko = $tokoLama, Harga = $hargaLama, Tgl = $tglLama, Jumlah = $jumlahLama\\nJadi: Produk = $produk, Toko = $toko, Harga = $harga, Tgl = $tgl, Jumlah = $jumlah');</script>";
    // $success = "Transaksi Berhasil Diupdate!";
    setcookie('success', 'Transaksi Berhasil Di Update!', time() + 1, "/");
    header("Location: " . $url . "transaksi/index.php");
    exit();
  } else {
    echo "<script>alert('Error, Transaksi Gagal Di Update" . htmlspecialchars($query->errorInfo()[2]) . "!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../layout/head.php'; ?>
  <title>Edit Transaksi</title>
  <style>
    .readonly-select {
      pointer-events: none;
      background-color: #e9ecef;
      /* Optional: Match disabled style */
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- background -->
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = 'transaksi';
  include __DIR__ . '/../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid p-0">
        <h6 class="font-weight-bolder text-white mb-0">Update Data Transaksi</h6>
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
                  <select class="form-select readonly-select" name="produk" id="produk">
                    <option value="<?= htmlspecialchars($transaksi['prod_id']); ?>" selected>
                      <?= htmlspecialchars($transaksi['prod_id']); ?> | <?= htmlspecialchars($namaProduk); ?>
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="toko">Toko</label>
                  <select class="form-select readonly-select" name="toko" id="toko">
                    <option value="<?= htmlspecialchars($transaksi['toko_id']); ?>" selected>
                      <?= htmlspecialchars($transaksi['toko_id']); ?> | <?= htmlspecialchars($namaToko); ?>
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="harga">Harga</label>
                  <input type="number" class="form-control" name="harga" id="harga" required
                    value="<?= htmlspecialchars($transaksi['harga']); ?>">
                </div>
                <input type="datetime" hidden name="tgl" value="2024-09-22 02:46:34">
                <input type="number" hidden name="user" value="1">
                <input type="number" hidden name="jumlah" value="1">
                <button type="submit" name="submit" class="btn btn-primary mb-0">Update Data</button>
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
              <a href="https://github.com/Marini34" class="font-weight-bolder">Marini</a>
              for Study Geographic Informastion System
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <script>
    //select new category
    function toggleNewCategoryInput(selectElement) {
      var newCategoryInput = document.getElementById('newCategoryInput');
      if (selectElement.value === 'new') {
        newCategoryInput.style.display = 'block';  // Show the new category input
      } else {
        newCategoryInput.style.display = 'none';  // Hide the new category input
      }
    }
  </script>
  <?php include __DIR__ . '/../layout/scripts.php' ?>
</body>

</html>