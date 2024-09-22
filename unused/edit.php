<?php
include 'poi.php';

// $url = "http://" . $_SERVER['HTTP_HOST'] . "/gislanjut_Sisfo24/";
$url = "http://" . $_SERVER['HTTP_HOST'] . "/marini/sig-lanjut/";

// Sanitize the input
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

$data = getDataById($id); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <title>Data Lokasi</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons akun Leo-->
  <script src="https://kit.fontawesome.com/091b217840.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="./assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <style>
    #map {
      height: 80vh;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="<?= $url ?>assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">SIG-Lanjut</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " href="<?= $url ?>">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?= $url ?>crud.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tabel Lokasi</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid p-0">
        <h6 class="font-weight-bolder text-white mb-0">Update Data Point Of Interest</h6>
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
          <div class="card z-index-3" id="map"></div>
        </div>
        <div class="col-lg-4">
          <div class="card">
            <a href="<?= $url ?>crud.php" class="btn btn-icon btn-3 btn-secondary m-2" type="button">
              <span class="btn-inner--icon"><i class="fa-solid fa-arrow-left"></i></span>
              <span class="btn-inner--text">Kembali</span>
            </a>
            <div class="card-body">
              <form id="poi-form" class="mb-0">
                <div class="form-group">
                  <label for="name">POI Name</label>
                  <input value="<?= $data["name"] ?>" type="text" id="name" class="form-control" name="name" placeholder="Tugu Kemerdekaan" required>
                </div>
                <div class="form-group">
                  <label for="description">POI Description</label>
                  <input value="<?= $data["description"] ?>" type="text" id="description" class="form-control" name="description"
                    placeholder="Deskripsi Lokasi" required>
                </div>
                <p>*Tandai lokasi di map</p>
                <input value="<?= $data["lat"] ?>" type="double" id="lat" name="lat" placeholder="Latitude" class="d-none">
                <input value="<?= $data["lng"] ?>" type="double" id="lng" name="lng" placeholder="Longitude" class="d-none">
                <button type="submit" class="btn btn-primary mb-0">Update Data</button>
              </form>
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

  <script>
    let center = { lat: -6.200000, lng: 106.816666 }; // Jakarta sebagai contohconst center = { lat: -6.200000, lng: 106.816666 }; // Jakarta sebagai contoh

    function initMap() {
      //draw map
      map = new google.maps.Map(document.getElementById('map'), {
        center: center,
        zoom: 13,
        mapId: 'storied-deck-432408-h3'
      });

      // Create a custom marker element
      const markerElement = document.createElement('h1');
      markerElement.innerHTML = '📍';  // Example marker content
      markerElement.style.cursor = 'pointer'; // Change cursor to pointer

      // marker center 
      marker = new google.maps.marker.AdvancedMarkerElement({
        map,
        title: "Posisi yang ingin diedit!",
        content: markerElement,
        position: {lat:<?= $data["lat"] ?>, lng:<?= $data["lng"] ?>}
      });

      map.addListener('click', (event) => {
        marker.position = event.latLng;
        document.getElementById('lat').value = marker.position.lat;
        document.getElementById('lng').value = marker.position.lng;
      });
    }

    // menambahkan data ke db
    document.getElementById('poi-form').addEventListener('submit', function (e) {
      e.preventDefault();
      const id = <?= $id; ?>;
      const name = document.getElementById('name').value;
      const description = document.getElementById('description').value;
      const lat = document.getElementById('lat').value;
      const lng = document.getElementById('lng').value;
      if (lat != 0 || lng != 0) {
        fetch('poi.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${id}&name=${name}&description=${description}&lat=${lat}&lng=${lng}`
        })
          .then(response => response.text())
          .then(data => {
            alert(data);
            location.reload();
          })
          .catch(error => console.error('Error:', error));
      } else {
        alert(`Pilih Poisisi di Map \nLat: ${lat}, Lng: ${lng}`);
      }
    });

  </script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGBjlEnlrlO2KdsQMFL70E_Ppo3GmFPs&loading=async&callback=initMap&libraries=marker"
    async type="text/javascript" defer></script>
  <!--   Core JS Files   -->
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

</html>