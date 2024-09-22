<?php
?>
<aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="<?= $url ?>assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo" />
        <span class="ms-1 font-weight-bold">SIG-Lanjut</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0" />
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= ($active == 'dashboard') ? 'active' : '';?>" href="<?= $url ?>">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active == 'produk') ? 'active' : '';?>" href="<?= $url ?>produk/">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-bag-17 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Produk</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active == 'transaksi') ? 'active' : '';?>" href="<?= $url ?>transaksi/">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-cart text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Transaksi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active == 'toko') ? 'active' : '';?>" href="<?= $url ?>toko/">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-shop text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Toko</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>