<?php
include '../koneksi.php';
$query = $kon->prepare("SELECT * FROM produk");
$query->execute();

$datas = $query->fetchAll(PDO::FETCH_ASSOC);

$jsonData = json_encode($datas);
echo "<script>console.log('Produk: ',$jsonData);</script>";
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  // Prepare the delete statements
  $sqlProduk = "DELETE FROM produk WHERE bar = :id";

  $stmtProduk = $kon->prepare($sqlProduk);
  $stmtProduk->bindParam(':id', $id, PDO::PARAM_STR);
  $stmtProduk->execute();
  $stmtProduk->closeCursor();

  header("Location: index.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../layout/head.php'; ?>
  <title>Data Produk</title>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = "produk";
  include '../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <?php $judul = "Data Produk";
    include '../layout/navbar.php'; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-head text-end">
              <a href="<?= $url ?>produk/tambah.php" class="btn btn-icon btn-3 btn-success m-2" type="button">
                <span class="btn-inner--icon"><i class="fa-solid fa-plus"></i></span>
                <span class="btn-inner--text">Tambahkan</span>
              </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <b class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                        Barcode</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Nama</th>
                      <th class="phone text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Kategori</th>
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
                              <p class="mb-0 text-xs text-wrap"><?= $data['bar']; ?></p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs text-secondary mb-0 text-wrap"><?= $data['nama']; ?></p>
                        </td>
                        <td class="phone">
                          <p class="text-xs text-secondary text-center mb-0 text-wrap"><?= $data['kategori']; ?></p>
                        </td>
                        <td class="align-middle text-center">
                          <a href="<?= $url ?>produk/edit.php?id=<?= urlencode($data['bar']); ?>"
                            class="edit btn btn-info m-0">Edit</a>
                          <button class="hapus btn btn-danger m-0" data-bs-toggle="modal" data-bs-target="#modal-default"
                            data-bar="<?= urlencode($data['bar']); ?>">Hapus</button>
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
                        <h6 class="modal-title" id="modal-title-default">yakin Ingin Menghapus</h6>
                      </div>
                      <div class="modal-footer">
                        <a href="<?= $url ?>produk/?delete=<?= urlencode($data['bar']); ?>" id="delete-link" type="button"
                          class="btn bg-gradient-danger">ya Hapus</a>
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
              <a href="#" class="font-weight-bold">Marini</a>
              for Study Geographic Informastion System
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <?php include '../layout/scripts.php' ?>
  <script>
    if (window.innerWidth <= 425) {
      const phones = document.getElementsByClassName('phone');
      for (let i = 0; i < phones.length; i++) {
        phones[i].hidden = true; // Menyembunyikan elemen
      }
      const edits = document.getElementsByClassName('edit');
      for (let i = 0; i < edits.length; i++) {
        edits[i].className = "edit btn btn-info m-0 p-1";
        edits[i].innerHTML = "<i class='ni ni-ruler-pencil'></i>";
      }
      const hapus = document.getElementsByClassName('hapus');
      for (let i = 0; i < hapus.length; i++) {
        hapus[i].className = "hapus btn btn-danger m-0 p-1";
        hapus[i].innerHTML = "<i class='ni ni-fat-remove'></i>";
      }
    }
    console.log(window.innerWidth);

    document.addEventListener('DOMContentLoaded', function () {
      var modal = document.getElementById('modal-default');
      modal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var bar = button.getAttribute('data-bar');
        var link = `<?= $url ?>produk/?delete=${bar}`;
        console.log(bar);

        // Update the modal's content
        let deleteLink = document.getElementById('delete-link');
        // deleteLink.setAttribute('href', link);
        deleteLink.textContent = `Ya Hapus ${bar}`; // Update button text
      });
    });

    
  </script>

</body>

</html>