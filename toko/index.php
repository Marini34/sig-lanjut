<?php
include __DIR__ . '/../koneksi.php';
$query = $kon->prepare("SELECT * FROM toko");
$query->execute();

$datas = $query->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  // Prepare the delete statements
  $sqlToko = "DELETE FROM toko WHERE id = :id";

  $stmtToko = $kon->prepare($sqlToko);
  $stmtToko->bindParam(':id', $id, PDO::PARAM_STR);
  $stmtToko->execute();
  $stmtToko->closeCursor();
  header('Location: ' . $url . 'toko/index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../layout/head.php'; ?>
  <title>Data Toko</title>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = "toko";
  include __DIR__ . '/../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <?php $judul = "Data Toko";
    include __DIR__ . '/../layout/navbar.php'; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-head text-end">
              <a href="<?= $url ?>toko/tambah.php" class="btn btn-icon btn-3 btn-success m-2" type="button">
                <span class="btn-inner--icon"><i class="fa-solid fa-plus"></i></span>
                <span class="btn-inner--text">Tambahkan</span>
              </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <b class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Nama</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Alamat</th>
                      <th class="phone text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Lokasi</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($datas as $data): ?>
                      <tr>
                        <td>
                          <p class="mb-0 text-xs text-wrap"><?= $data['nama']; ?></p>
                        </td>
                        <td>
                          <p class="text-xs text-secondary mb-0 text-wrap"><?= $data['alamat']; ?></p>
                        </td>
                        <td class="phone align-middle text-center text-sm">
                          <p class="text-xs text-secondary mb-0"><?= $data['lat']; ?></p>
                          <p class="text-xs text-secondary mb-0"><?= $data['lng']; ?></p>
                        </td>
                        <td class="align-middle text-center">
                          <a href="<?= $url ?>toko/edit.php?id=<?= urlencode($data['id']); ?>"
                            class="edit btn btn-info m-0">Edit</a>
                          <button class="hapus btn btn-danger m-0" data-bs-toggle="modal" data-bs-target="#modal-default"
                            data-nama="<?= $data['nama']; ?>">Hapus</button>
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
                        <a href="<?= $url ?>toko/?delete=<?= urlencode($data['id']); ?>" id="delete-link" type="button"
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
        var nama = button.getAttribute('data-nama');
        console.log(nama);

        // Update the modal's content
        let deleteLink = document.getElementById('delete-link');
        // deleteLink.setAttribute('href', link);
        deleteLink.textContent = `Ya Hapus ${nama}`; // Update button text
      });
    });


  </script>

</body>

</html>