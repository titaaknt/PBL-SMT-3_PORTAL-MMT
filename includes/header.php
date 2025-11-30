<?php
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="stylesheet" href="assets/css/style.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portal Showcase Lab Multimedia & Mobile Tech</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .navbar {
      background-color: #0056A6;
    }
    .navbar-brand img {
      height: 40px;
    }
    .nav-link {
      color: white !important;
      font-weight: 500;
      margin-left: 8px;
    }
    .nav-link:hover {
      text-decoration: underline;
      color: #FFD700 !important;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand text-white d-flex align-items-center" href="index.php">
      <img src="assets/img/logolabmmt.png" alt="Polinema" class="me-2" style="height:50px;">
      <div class="lh-sm">
        <div class="fw-bold" style="font-size: 14px;">LABORATORIUM MULTIMEDIA & MOBILE TECH</div>
        <div style="font-size: 13px;">POLITEKNIK NEGERI MALANG</div>
      </div>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <i class="bi bi-list text-white fs-3"></i>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav text-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="karya.php">Karya</a></li>
        <li class="nav-item"><a class="nav-link" href="berita.php">Berita</a></li>
        <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
      </ul>
    </div>
  </div>
</nav>