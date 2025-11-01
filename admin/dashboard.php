<?php
include '../includes/config.php';
include '../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Portal MMT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-primary">
  <div class="container">
    <span class="navbar-brand">Dashboard Admin</span>
    <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4">
  <h4 class="mb-4 text-primary fw-bold">Selamat Datang, <?= $_SESSION['admin']; ?> 👋</h4>

  <div class="row text-center">
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h6>Data Profil</h6>
        <a href="profil.php" class="btn btn-outline-primary btn-sm">Kelola</a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h6>Karya</h6>
        <a href="karya.php" class="btn btn-outline-primary btn-sm">Kelola</a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h6>Berita</h6>
        <a href="berita.php" class="btn btn-outline-primary btn-sm">Kelola</a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h6>Galeri</h6>
        <a href="galeri.php" class="btn btn-outline-primary btn-sm">Kelola</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>