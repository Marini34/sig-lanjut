<?php
include __DIR__ . '/../koneksi.php';

if (isset($_POST['submit'])) {
  // Ambil data dari form
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];

  // Simpan data ke database
  try {
    // Siapkan query SQL dengan parameter yang aman
    $sql = "INSERT INTO toko (nama, alamat, lat, lng) VALUES (:nama, :alamat, :lat, :lng)";
    $stmt = $kon->prepare($sql);

    // Bind parameter untuk mencegah SQL injection
    $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
    $stmt->bindParam(':alamat', $alamat, PDO::PARAM_STR);
    $stmt->bindParam(':lat', $lat, PDO::PARAM_STR);
    $stmt->bindParam(':lng', $lng, PDO::PARAM_STR);

    // Eksekusi query
    if ($stmt->execute()) {
      // Menampilkan data di console.log
      echo "<script>console.log('Data berhasil dikirim: Nama = $nama, Alamat = $alamat, lat = $lat, lng = $lng');</script>";
      $success = "Data Berhasil Ditambahkan";
    } else {
      echo "Gagal menambahkan data.";
    }
  } catch (PDOException $e) {
    // Tangani error jika terjadi kesalahan dalam query atau koneksi
    echo "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include __DIR__ . '/../layout/head.php'; ?>
  <title>Tambah Toko</title>
  <style>
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <?php $active = 'toko';
  include __DIR__ . '/../layout/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <?php $judul = "Data Toko";
    include __DIR__ . '/../layout/navbar.php'; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row align-items-stretch">
        <div class="col-lg-8 mb-2">
          <div class="card z-index-3" id="map"></div>
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
                  <input type="text" id="nama" class="form-control" name="nama" placeholder="Toko Budi" maxlength="30"
                    required>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <input type="text" id="alamat" class="form-control" name="alamat" placeholder="Jl. Keramat Jati" required>
                </div>
                <p>*Tandai lokasi di map</p>
                <input type="double" id="lat" name="lat" class="d-none" value="-0.05623711446160067">
                <input type="double" id="lng" name="lng" class="d-none" value="109.3372446919995">
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
    let center = { lat: -0.05187711699572585, lng: 109.35046474605898 }; // gor untan
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
  <?php include __DIR__ . '/../layout/scripts.php'; ?>
</body>

</html>