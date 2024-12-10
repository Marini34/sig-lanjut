<?php
include __DIR__ . '/../koneksi.php';
$success = $_COOKIE['success'] ?? '';
ob_start();
$query = "
  SELECT transaksi.id AS id, produk.bar AS bar, produk.nama AS nama, transaksi.harga AS harga, toko.nama AS toko 
  FROM transaksi JOIN produk ON transaksi.prod_id = produk.bar JOIN toko ON transaksi.toko_id = toko.id
";
$stmt = $kon->prepare($query);
$stmt->execute();
$datas = $stmt->fetchAll(PDO::FETCH_ASSOC);


// if ($datas) {
//   $hasil = json_encode($datas);
//   echo "<script>console.log('Data: $hasil')</script>";
// }

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  try {
    $stmt = $kon->prepare("DELETE FROM transaksi WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);

    if ($stmt->execute()) {
      setcookie('success', 'Traksaksi Berhasil Dihapus!', time() + 1, "/");
    }

    header('Location: ' . $url . 'transaksi/index.php');
    exit();
  } catch (PDOException $e) {
    // Catch and display the error message
    echo "<script>alert('PDO Error, Transaksi Gagal Dihapus" . htmlspecialchars($e->getMessage()) . "!');</script>";
  }
}
// Flush the output buffer at the end of the script
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../layout/head.php'; ?>
  <title>Data Transaksi</title>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = "transaksi";
  include __DIR__ . '/../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <?php $judul = "Data Transaksi";
    include __DIR__ . '/../layout/navbar.php'; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-head text-end">
              <a href="<?= $url ?>transaksi/tambah.php" class="btn btn-icon btn-3 btn-success m-2" type="button">
                <span class="btn-inner--icon"><i class="fa-solid fa-plus"></i></span>
                <span class="btn-inner--text">Tambahkan</span>
              </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <?php
              if (isset($success)) {
                echo "<span class='badge bg-gradient-success mx-4'>$success</span>";
              }
              ?>
              <b class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                        Produk</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Harga</th>
                      <th class="phone text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Toko</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($datas as $data): ?>
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <p class="mb-0 text-xs text-wrap"><?= $data['nama']; ?></p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs text-secondary mb-0 text-wrap"><?= $data['harga']; ?></p>
                        </td>
                        <td class="phone">
                          <p class="text-xs text-secondary text-center mb-0 text-wrap"><?= $data['nama']; ?></p>
                        </td>
                        <td class="align-middle text-center">
                          <a href="<?= $url ?>transaksi/edit.php?id=<?= urlencode($data['id']); ?>"
                            class="edit btn btn-info m-0">Edit</a>
                          <button class="hapus btn btn-danger m-0" data-bs-toggle="modal"
                            data-bs-target="#modal-default">Hapus</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <!-- modal -->
                <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                  aria-hidden="true">
                  <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Yakin Ingin Menghapus Transaksi</h6>
                      </div>
                      <div class="modal-body">
                        <p><?= $data['nama']; ?> | <?= $data['toko']; ?></p>
                      </div>
                      <div class="modal-footer">
                        <a href="<?= $url ?>transaksi/?delete=<?= urlencode($data['id']); ?>" id="delete-link"
                          type="button" class="btn bg-gradient-danger">Ya Hapus</a>
                        <button type="button" class="btn btn-link  ml-auto" data-bs-dismiss="modal">Kembali</button>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer px-3 position-absolute bottom-2">
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
  <?php include __DIR__ . '/../layout/scripts.php' ?>
  <script>
    if (window.innerWidth <= 425) {
      const phones = document.getElementsByClassName('phone');
      for (let i = 0; i < phones.length; i++) {
        phones[i].hidden = true; // Menyembunyikan elemen
      }
      const edits = document.getElementsByClassName('edit');
      for (let i = 0; i < edits.length; i++) {
        edits[i].className = "edit btn btn-info m-0 p-1";
        edits[i].innerHTML = "<i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i>";
      }
      const hapus = document.getElementsByClassName('hapus');
      for (let i = 0; i < hapus.length; i++) {
        hapus[i].className = "hapus btn btn-danger m-0 p-1";
        hapus[i].innerHTML = "<i class='fa-solid fa-trash fa-sm' style='color: #ffffff;'></i>";
      }
    }
    // console.log(window.innerWidth);
  </script>

</body>

</html>