<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center justify-content-center text-center text-white">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1 class="fw-bold display-5 mb-3">Laboratorium Multimedia & Mobile Tech</h1>
    <p class="lead mb-4">Pusat inovasi, kreativitas, dan pengembangan teknologi multimedia, mobile, serta interaktif di Politeknik Negeri Malang.</p>
    <a href="profil.php" class="btn btn-light fw-semibold px-4 py-2 shadow-sm">Lihat Profil</a>
  </div>
</section>

<!-- Tentang Laboratorium -->
<section class="container my-5 text-center">
  <h3 class="fw-bold text-primary mb-3">Tentang Laboratorium</h3>

  <!-- GAMBAR LOGO DITAMBAHKAN DI SINI -->
  <img src="assets/img/logolabmmt.png" alt="Logo Lab MMT" class="img-fluid mb-4" style="max-width:180px;">

  <p class="mx-auto" style="max-width: 900px;">
    Laboratorium Multimedia dan Mobile Tech merupakan salah satu laboratorium di Jurusan Teknologi Informasi Polinema
    yang berfokus pada bidang pengembangan aplikasi berbasis multimedia, mobile, serta teknologi interaktif.
    Di laboratorium ini, mahasiswa belajar dan berkolaborasi untuk menghasilkan karya inovatif seperti
    aplikasi mobile, media interaktif, game edukasi, serta penelitian berbasis AR/VR.
  </p>

  <p class="mx-auto" style="max-width: 900px;">
    Melalui kegiatan riset, praktikum, dan proyek berbasis industri, laboratorium ini menjadi wadah mahasiswa
    untuk mengasah kemampuan teknis dan kreatif dalam menghadapi tantangan era digital.
  </p>
</section>

<!-- Highlight Project -->
<section class="container my-5">
  <h3 class="fw-bold text-primary mb-4 text-center">Highlight Project</h3>
  <div class="row">
    <?php
    $query = pg_query($conn, "SELECT * FROM karya ORDER BY id DESC LIMIT 3");
    if (pg_num_rows($query) == 0) {
        echo "<p class='text-muted text-center'>Belum ada karya ditambahkan.</p>";
    }
    while ($data = pg_fetch_assoc($query)) {
        echo '
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm h-100 border-0">
            <img src="assets/img/'.$data['gambar'].'" class="card-img-top"
                 alt="'.$data['judul'].'" style="height:220px;object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title text-primary">'.$data['judul'].'</h5>
              <p class="card-text text-muted">'.substr($data['deskripsi'],0,80).'...</p>
            </div>
          </div>
        </div>';
    }
    ?>
  </div>

  <?php
  $countK = pg_query($conn, "SELECT COUNT(*) AS cnt FROM karya");
  $cK = pg_fetch_assoc($countK);
  if ($cK && (int)$cK['cnt'] > 0) {
    echo '<div class="d-flex justify-content-center mt-3">
            <a href="karya.php" class="btn btn-primary btn-lg px-4">Baca Selengkapnya</a>
          </div>';
  }
  ?>
</section>

<!-- Berita Terbaru -->
<section class="container my-5">
  <h3 class="fw-bold text-primary mb-4 text-center">Berita Terbaru</h3>
  <div class="row">
    <?php
    $query = pg_query($conn, "SELECT * FROM berita ORDER BY id DESC LIMIT 3");
    if (pg_num_rows($query) == 0) {
        echo "<p class='text-muted text-center'>Belum ada berita ditambahkan.</p>";
    }
    while ($data = pg_fetch_assoc($query)) {
        echo '
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm h-100 border-0">
            <img src="assets/img/'.$data['gambar'].'" class="card-img-top"
                 alt="'.$data['judul'].'" style="height:200px;object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title text-primary">'.$data['judul'].'</h5>
              <small class="text-muted">'.date('d M Y', strtotime($data['tanggal'])).'</small>
            </div>
          </div>
        </div>';
    }
    ?>
  </div>

  <?php
  $countB = pg_query($conn, "SELECT COUNT(*) AS cnt FROM berita");
  $cB = pg_fetch_assoc($countB);
  if ($cB && (int)$cB['cnt'] > 0) {
    echo '<div class="d-flex justify-content-center mt-3">
            <a href="berita.php" class="btn btn-primary btn-lg px-4">Baca Selengkapnya</a>
          </div>';
  }
  ?>
</section>

<!-- Kontak -->
<section class="contact-section text-white text-center py-4">
  <div class="container">
    <h5 class="fw-semibold mb-2">Hubungi Kami</h5>
    <p class="mb-0">
      Laboratorium Multimedia & Mobile Tech<br>
      Jurusan Teknologi Informasi, Politeknik Negeri Malang<br>
      Email: <a href="mailto:lab.mmt@polinema.ac.id" class="text-white text-decoration-underline">lab.mmt@polinema.ac.id</a>
    </p>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- STYLE -->
<style>
.hero-section {
  height: 75vh;
  background: url('assets/img/lab_photo.jpeg') center/cover no-repeat;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
.hero-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(6px);
}
.hero-content {
  position: relative;
  z-index: 2;
  color: #fff;
  text-shadow: 0 3px 10px rgba(0,0,0,0.5);
  max-width: 800px;
  padding: 20px;
}
.hero-content h1 {
  font-size: 2.5rem;
}
.hero-content p {
  font-size: 1.1rem;
}
.btn-light {
  background: rgba(255,255,255,0.9);
  border: none;
  color: #0056A6;
  transition: 0.3s;
}
.btn-light:hover {
  background: #ffc107;
  color: #fff;
}

.contact-section {
  background-color: #2D5172;
  padding: 60px 0;
  margin: 0;
  border: none;
}
.contact-section + footer {
  margin-top: 0 !important;
  padding-top: 0 !important;
}
section {
  margin-bottom: 0 !important;
}
footer {
  margin-top: 0 !important;
  padding-top: 20px;
}

@media (max-width: 768px) {
  .hero-content h1 {
    font-size: 1.8rem;
  }
}
</style>