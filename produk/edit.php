<?php
include __DIR__ . '/../koneksi.php';
try {
  // Ambil Seluruh Data By ID
  $id = $_GET['id'];
  $produk = null;

  // Gunakan prepared statement untuk mengambil data produk berdasarkan ID
  $query = "SELECT * FROM produk WHERE produk.bar = :id";
  $stmt = $kon->prepare($query);
  $stmt->bindParam(':id', $id, PDO::PARAM_STR);
  $stmt->execute();
  $produk = $stmt->fetch(PDO::FETCH_ASSOC);

  // Ambil Seluruh Kategori
  $query = "SELECT DISTINCT kategori FROM produk";
  $stmt = $kon->prepare($query);
  $stmt->execute();

  $categories = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categories[] = $row['kategori'];
  }
  // $kategori = json_encode($categories);
  // echo "<script>console.log('kategori produk: ',$kategori);</script>";

  if (isset($_POST['submit'])) {
    // Ambil data dari form
    // echo "<script>console.log('submit');</script>";
    $bar = $_POST['barcode'];
    $nama = $_POST['nama'];
    $kategori = ($_POST['kategori'] == 'new') ? $_POST['kategoriBaru'] : $_POST['kategori'];
    // echo "<script>console.log('Barcode = $bar, Nama = $nama, Kategori = $kategori ');</script>";
    // Gunakan prepared statement untuk melakukan UPDATE
    $sql = "UPDATE produk SET nama = :nama, kategori = :kategori WHERE bar = :bar";
    $stmt = $kon->prepare($sql);
    $stmt->bindParam(':bar', $bar, PDO::PARAM_STR);
    $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
    $stmt->bindParam(':kategori', $kategori, PDO::PARAM_STR);

    if ($stmt->execute()) {
      // Menampilkan data di console.log
      $barLama = $produk['bar'];
      $namaLama = $produk['nama'];
      $kategoriLama = $produk['kategori'];
      // echo "<script>console.log('Data berhasil diupdate Dari: \\nBarcode = $barLama, Nama = $namaLama, Kategori = $kategoriLama\\nJadi: Barcode = $bar, Nama = $nama, Kategori = $kategori ');</script>";
      // $success = "Data Berhasil Diupdate!";
      setcookie('success', 'Produk Berhasil Di Update!', time() + 1, "/");
      header("Location: $url/produk/index.php");
      exit();
    } else {
      // echo "Error Execute: " . $stmt->errorInfo()[2];
      echo "<script>alert('Produk Gagal Diupdate" . htmlspecialchars($stmt->errorInfo()[2]) . "!');</script>";
    }
  }

} catch (PDOException $e) {
  // Menangani error koneksi atau query
  echo "<script>alert('PDO Error, Produk Gagal Diupdate" . htmlspecialchars($e->getMessage()) . "!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../layout/head.php'; ?>
  <title>Edit Produk</title>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- background -->
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = 'produk';
  include __DIR__ . '/../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid p-0">
        <h6 class="font-weight-bolder text-white mb-0">Update Data Produk</h6>
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
              <form id="poi-form" class="mb-0" method="POST" action="">
                <div class="form-group">
                  <label for="barcode">Barcode</label>
                  <input type="text" id="barcode" class="form-control" name="barcode"
                    value="<?= htmlspecialchars($produk['bar']); ?>" maxlength="13" readonly required>
                </div>
                <div class="form-group">
                  <label for="nama">Nama Produk</label>
                  <input type="text" id="nama" class="form-control" name="nama" value="<?= $produk['nama']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="kategori">Kategori</label>
                  <select class="form-select" name="kategori" id="kategori" onchange="toggleNewCategoryInput(this)">
                    <option value="<?= $produk['kategori']; ?>" selected><?= $produk['kategori']; ?></option>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?= htmlspecialchars($category); ?>">
                        <?= htmlspecialchars(string: $category); ?>
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
                <button type="submit" name="submit" class="btn btn-primary mb-0">Update Produk</button>
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
  <!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGBjlEnlrlO2KdsQMFL70E_Ppo3GmFPs&loading=async&callback=initMap&libraries=marker"
    async type="text/javascript" defer></script> -->
  <?php include __DIR__ . '/../layout/scripts.php' ?>
</body>

</html>