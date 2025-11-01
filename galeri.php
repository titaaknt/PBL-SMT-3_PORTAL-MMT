<?php include 'includes/header.php'; ?>

<!-- GALERI MULTIMEDIA -->
<section class="container-lg px-4 my-5">
  <h3 class="text-primary fw-bold mb-4 text-center">Galeri Multimedia</h3>

  <div class="row">
    <?php
    $query = pg_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
    if (pg_num_rows($query) == 0) {
        echo '<p class="text-muted text-center">Belum ada galeri ditambahkan.</p>';
    } else {
        while ($data = pg_fetch_assoc($query)) {
            echo '
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
              <div class="card shadow-sm border-0 galeri-card">
                <img src="assets/img/'.$data['file_path'].'" 
                     class="card-img-top gallery-img" 
                     alt="'.$data['judul'].'" 
                     data-bs-toggle="modal" 
                     data-bs-target="#imageModal" 
                     data-img="assets/img/'.$data['file_path'].'" 
                     data-title="'.$data['judul'].'">
                <div class="card-body p-2 text-center">
                    <small class="text-muted">'.$data['judul'].'</small>
                </div>
              </div>
            </div>';
        }
    }
    ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- MODAL PREVIEW GAMBAR -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white border-0">
      <div class="modal-header border-0">
        <h6 class="modal-title" id="imageTitle"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>

<!-- STYLE TAMBAHAN -->
<style>
.galeri-card {
  border-radius: 10px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.galeri-card:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.gallery-img {
  height: 200px;
  object-fit: cover;
  cursor: pointer;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}
@media (max-width: 768px) {
  .gallery-img {
    height: 160px;
  }
}
.modal-content {
  border-radius: 10px;
}
</style>

<!-- SCRIPT UNTUK POPUP GAMBAR -->
<script>
document.querySelectorAll('.gallery-img').forEach(img => {
  img.addEventListener('click', () => {
    document.getElementById('modalImage').src = img.getAttribute('data-img');
    document.getElementById('imageTitle').innerText = img.getAttribute('data-title');
  });
});
</script>