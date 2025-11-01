<?php include 'includes/header.php'; ?>

<section class="container-lg my-5 px-4">
  <h3 class="text-primary fw-bold mb-4 text-center">Profil Laboratorium</h3>

  <?php
  $q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
  $data = pg_fetch_assoc($q);
  ?>

  <!-- Gambar di atas -->
  <div class="text-center mb-4">
    <div class="image-wrapper mx-auto shadow-sm">
      <img src="assets/img/labmmt.jpg" 
           alt="Laboratorium Multimedia & Mobile Tech" 
           class="img-fluid rounded-4">
    </div>
    <p class="mt-3 text-muted small fst-italic">
      Laboratorium Multimedia & Mobile Tech - Politeknik Negeri Malang
    </p>
  </div>

  <!-- Card Konten Profil -->
  <div class="card shadow-sm border-0 rounded-4 p-4">
    <div class="profile-text">
      <h5 class="fw-bold text-primary mb-3">Visi</h5>
      <p><?= nl2br($data['visi']); ?></p>

      <h5 class="fw-bold text-primary mb-3 mt-4">Misi</h5>
      <p><?= nl2br($data['misi']); ?></p>

      <h5 class="fw-bold text-primary mb-3 mt-4">Struktur Organisasi</h5>
      <p><?= nl2br($data['struktur']); ?></p>

      <h5 class="fw-bold text-primary mb-3 mt-4">Kontak</h5>
      <p><?= nl2br($data['kontak']); ?></p>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- STYLE TAMBAHAN -->
<style>
.profile-text {
  line-height: 1.7;
  font-size: 1rem;
  color: #333;
}

.image-wrapper {
  max-width: 850px;
  border-radius: 20px;
  overflow: hidden;
  transition: transform 0.4s ease, filter 0.3s ease;
}

.image-wrapper img {
  transition: transform 0.4s ease, filter 0.3s ease;
}

.image-wrapper:hover img {
  transform: scale(1.05);
  filter: brightness(1.05);
}

.card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

@media (max-width: 768px) {
  .profile-text {
    font-size: 0.95rem;
  }
  .image-wrapper {
    max-width: 100%;
  }
}
</style>