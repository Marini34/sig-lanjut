<?php
include __DIR__ . '/../koneksi.php';

// Mengambil data kategori
$query = $kon->prepare("SELECT DISTINCT kategori FROM produk");
$query->execute();

$categories = [];
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
  $categories[] = $row['kategori'];
}

// $kategoriJson = json_encode($categories);
// Menampilkan kategori di console
// echo "<script>console.log('kategori: ',$kategoriJson);</script>";

if (isset($_POST['submit'])) {
  // Ambil data dari form
  $bar = $_POST['barcode'];
  $nama = $_POST['nama'];
  $kategori = ($_POST['kategori'] == 'new') ? $_POST['kategoriBaru'] : $_POST['kategori'];

  // Cek apakah barcode sudah ada di database
  $sql_check = "SELECT * FROM produk WHERE bar = :barcode";
  $query = $kon->prepare($sql_check);
  $query->bindParam(':barcode', $bar);
  $query->execute();

  if ($query->rowCount() > 0) {
    // Jika barcode sudah ada, tampilkan pesan error
    $errorMessage = "Barcode sudah terdaftar";
  } else {
    // Simpan data ke database
    $sql = "INSERT INTO produk (bar, nama, kategori) VALUES (:barcode, :nama, :kategori)";
    $query = $kon->prepare($sql);

    // Bind parameter
    $query->bindParam(':barcode', $bar);
    $query->bindParam(':nama', $nama);
    $query->bindParam(':kategori', $kategori);

    if ($query->execute()) {
      // Menampilkan data di console.log
      // echo "<script>console.log('Data berhasil dikirim: Barcode = $bar, Nama = $nama, Kategori = $kategori');</script>";
      $success = "Produk Berhasil Ditambahkan";
    } else {
      echo "<script>alert('Error, Produk Gagal Ditambah" . htmlspecialchars($kon->errorInfo()[2]) . "!');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../layout/head.php'; ?>
  <title>Tambah Produk</title>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = 'produk';
  include __DIR__ . '/../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid p-0">
        <h6 class="font-weight-bolder text-white mb-0">Tambahkan Data Produk</h6>
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
            <a href="<?= $url ?>produk/" class="btn btn-icon btn-3 btn-secondary m-2" type="button">
              <span class="btn-inner--icon"><i class="fa-solid fa-arrow-left"></i></span>
              <span class="btn-inner--text">Kembali</span>
            </a>
            <div class="card-body pt-0">
              <?php
              // Jika ada pesan error, tampilkan
              if (isset($errorMessage)) {
                echo "<span class='badge bg-gradient-danger'>$errorMessage</span>";
              } else if (isset($success)) {
                echo "<span class='badge bg-gradient-success'>$success</span>";
              }
              ?>
              <form id="poi-form" class="mb-0" method="POST" action="">
                <div class="form-group">
                  <label for="barcode">Barcode</label>
                  <input type="text" id="barcode" class="form-control" name="barcode" placeholder="1111122222333"
                    maxlength="13" pattern="\d{13}" title="Please enter exactly 13 digits" required
                    oninput="this.value = this.value.replace(/\D/g, '')">

                </div>
                <div class="form-group">
                  <label for="nama">Nama Produk</label>
                  <input type="text" id="nama" class="form-control" name="nama" placeholder="Aqua 100ml" required>
                </div>
                <div class="form-group">
                  <label for="kategori">Kategori</label>
                  <select class="form-select" name="kategori" id="kategori" onchange="toggleNewCategoryInput(this)">
                    <option value="lainnya" selected>--Select a category--</option>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?= htmlspecialchars($category); ?>">
                        <?= htmlspecialchars($category); ?>
                      </option>
                    <?php endforeach; ?>
                    <option value="new">--New Category--</option>
                  </select>
                  <!-- This input field is hidden initially -->
                  <div id="newCategoryInput" class="form-group" style="display: none;">
                    <label for="newCategory">Masukkan Kategori Baru</label>
                    <input type="text" class="form-control" name="kategoriBaru" id="newCategoryInput">
                  </div>
                </div>
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
              <a href="https://github.com/Marini34" class="font-weight-bolder">Marini</a>
              for Study Geographic Informastion System
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> -->
  <script>
    // let productID = document.getElementById('barcode');
    // let html5QrcodeScanner = new Html5QrcodeScanner(
    //   "reader",
    //   { fps: 10, qrbox: { width: 500, height: 250 } },
    //   /* verbose= */ false);

    // html5QrcodeScanner.render((decodedText, decodedResult) => {
    //   productID.value = decodedText;
    //   p.value = decodedText;
    // }, (error) => {
    //   console.warn(`Code scan error = ${error}`);
    // });

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
  <?php include __DIR__ . '/../layout/scripts.php'; ?>
</body>

</html>