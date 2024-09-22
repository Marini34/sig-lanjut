<?php
include '../koneksi.php';
$success = (isset($_COOKIE['success'])) ? $_COOKIE['success'] : '';
// Ambil Seluruh Data By ID
$id = $_GET['id'];
$toko;
$result = $kon->query("SELECT * FROM toko WHERE toko.id = $id");
if ($result->num_rows > 0) {
  // var_dump($result->fetch_assoc());  
  $toko = $result->fetch_assoc();
  $data = json_encode($toko);
  echo "<script>console.log('data: ',$data);</script>";
}

if (isset($_POST['submit'])) {
  // Ambil data dari form
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];

  // Simpan data ke database
  $sql = "UPDATE toko SET `nama`='$nama', `alamat`='$alamat', `lat`='$lat', `lng`='$lng' WHERE `id` = $id";

  if ($kon->query($sql) === TRUE) {
    // Menampilkan data di console.log
    $namaLama = $toko['nama'];
    $alamatLama = $toko['alamat'];
    $latLama = $toko['lat'];
    $lngLama = $toko['lng'];
    echo "<script>console.log('Data berhasil diupdate! \\nLama: Nama = $namaLama, Alamat = $alamatLama, Lat = $latLama, Lng = $lngLama\\nBaru: Nama = $nama, Alamat = $alamat, Lat = $lat, Lng = $lng');</script>";
    setcookie('success', 'Data Berhasi Diupdate!', time() + 3, "/");
    $_GLOBAL['success'] = "Data Berhasi Diupdate!";
  } else {
    echo "Error: " . $sql . "<br>" . $kon->error;
  }
  header("Refresh:0");
  exit();
}

$kon->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../layout/head.php'; ?>
  <title>Edit Toko</title>
  <style>
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- background -->
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = 'toko';
  include '../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <?php $judul = "Update Data Toko";
    include '../layout/navbar.php'; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row align-items-stretch">
        <div class="col-lg-8">
          <div class="card" id="map"></div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <a href="<?= $url ?>toko/" class="btn btn-icon btn-3 btn-secondary m-2" type="button">
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
              <form id="poi-form" class="mb-0" method="POST" action="">
                <div class="form-group">
                  <label for="nama">Nama</label>
                  <input type="text" id="nama" class="form-control" name="nama" value="<?= htmlspecialchars($toko['nama']); ?>"
                    maxlength="13" required>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat Produk</label>
                  <input type="text" id="alamat" class="form-control" name="alamat" value="<?= htmlspecialchars($toko['alamat']); ?>"
                    required>
                </div>
                <p>*Tandai lokasi di map</p>
                <input type="double" id="lat" name="lat" class="d-none" value="<?= htmlspecialchars($toko['lat']); ?>">
                <input type="double" id="lng" name="lng" class="d-none" value="<?= $toko['lng']; ?>">
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
              <a href="#" class="font-weight-bold">Marini</a>
              for Study Geographic Informastion System
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <script>
    let center = { lat: <?= $toko['lat'] ?>, lng: <?= $toko['lng'] ?> };
    console.log(center);
    let map;
    let marker;

    async function initMap() {
      // Request needed libraries.
      const { Map } = await google.maps.importLibrary("maps");
      const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
      //draw map
      map = new Map(document.getElementById('map'), {
        center: center,
        zoom: 13,
        mapId: 'storied-deck-432408-h3'
      });

      // Create a custom marker element
      const markerElement = document.createElement('h1');
      markerElement.innerHTML = '📍';  // Example marker content
      markerElement.style.cursor = 'pointer'; // Change cursor to pointer

      // marker center 
      marker = new AdvancedMarkerElement({
        map,
        title: "Posisi yang ingin ditambah!",
        content: markerElement,
        position: center
      });

      map.addListener('click', (event) => {
        marker.position = event.latLng;
        document.getElementById('lat').value = marker.position.lat;
        document.getElementById('lng').value = marker.position.lng;
      });
    }

  </script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGBjlEnlrlO2KdsQMFL70E_Ppo3GmFPs&loading=async&callback=initMap&libraries=marker"
    async type="text/javascript" defer></script>
  <?php include '../layout/scripts.php' ?>
</body>

</html>