<?php include 'includes/header.php'; ?>

<!-- KARYA & PRESTASI -->
<section class="container-lg px-4 my-5">
  <h3 class="text-primary fw-bold mb-4 text-center">Karya & Prestasi</h3>

  <div class="row">
    <?php
    $query = pg_query($conn, "SELECT * FROM karya ORDER BY tahun DESC");
    if (pg_num_rows($query) == 0) {
        echo '<p class="text-muted text-center">Belum ada karya yang ditambahkan.</p>';
    } else {
        while ($data = pg_fetch_assoc($query)) {
            echo '
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 karya-card">
                    <img src="assets/img/'.$data['gambar'].'" class="card-img-top" alt="'.$data['judul'].'" style="height:220px;object-fit:cover;border-top-left-radius:10px;border-top-right-radius:10px;">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-semibold">'.$data['judul'].'</h5>
                        <p class="card-text text-muted">'.substr($data['deskripsi'],0,80).'...</p>
                        <small class="text-muted">Tahun: '.$data['tahun'].'</small>
                    </div>
                </div>
            </div>';
        }
    }
    ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- STYLE TAMBAHAN -->
<style>
.karya-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 10px;
}
.karya-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

/* Responsif untuk HP */
@media (max-width: 768px) {
  .karya-card img {
    height: 180px;
  }
}
</style>