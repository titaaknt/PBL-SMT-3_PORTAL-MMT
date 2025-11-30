<?php 
include 'includes/header.php';

// =====================================
// GALERI TERBARU (tanpa pagination)
// =====================================
$galeri_terbaru = pg_query($conn, "SELECT * FROM galeri ORDER BY id DESC LIMIT 6");
?>

<!-- ============================= -->
<!-- GALERI SECTION -->
<!-- ============================= -->
<section class="container-lg px-4 my-5">

  <h3 class="text-primary fw-bold mb-4 text-center">Galeri Multimedia</h3>

  <!-- ============================= -->
  <!-- TERBARU FULL WIDTH -->
  <!-- ============================= -->
  <div class="terbaru-wrapper-full">

    <div class="terbaru-header d-flex align-items-center mb-3">
      <h4 class="fw-semibold terbaru-title mb-0">Terbaru</h4>
    </div>

    <div class="terbaru-carousel mb-5">

      <button class="terbaru-nav terbaru-prev" type="button">&#10094;</button>

      <div class="terbaru-track-wrapper">
        <div class="terbaru-track">

          <?php while ($t = pg_fetch_assoc($galeri_terbaru)): 
            $ext = pathinfo($t['file_path'], PATHINFO_EXTENSION);
            $isVideo = in_array(strtolower($ext), ['mp4','webm','ogg']);
          ?>
            <div class="terbaru-slide">
              <div class="card shadow-sm border-0 galeri-card terbaru-card">

                <?php if ($isVideo): ?>
                  <video class="card-img-top terbaru-img"
                         style="width:100%;height:100%;object-fit:cover;border-radius:15px;cursor:pointer;"
                         data-bs-toggle="modal"
                         data-bs-target="#imageModal"
                         data-video="assets/img/<?= $t['file_path'] ?>"
                         data-title="<?= $t['judul'] ?>">
                    <source src="assets/img/<?= $t['file_path'] ?>" type="video/mp4">
                  </video>
                <?php else: ?>
                  <img src="assets/img/<?= $t['file_path'] ?>" 
                       class="card-img-top terbaru-img"
                       alt="<?= $t['judul'] ?>"
                       data-bs-toggle="modal"
                       data-bs-target="#imageModal"
                       data-img="assets/img/<?= $t['file_path'] ?>"
                       data-title="<?= $t['judul'] ?>">
                <?php endif; ?>

              </div>
            </div>
          <?php endwhile; ?>

        </div>
      </div>

      <button class="terbaru-nav terbaru-next" type="button">&#10095;</button>
    </div>
  </div>


  <!-- ============================= -->
  <!-- KATEGORI -->
  <!-- ============================= -->
  <?php
  $kategori = pg_query($conn, "SELECT * FROM kategori_galeri ORDER BY judul ASC");

  while ($kat = pg_fetch_assoc($kategori)):
  ?>
    <h6 class="fw-semibold mb-3"><?= $kat['judul'] ?></h6>

    <div class="row mb-5">
      <?php
      $gambar = pg_query(
        $conn,
        "SELECT * FROM galeri 
         WHERE kategori_id = {$kat['id']} 
         ORDER BY id DESC 
         LIMIT 4"
      );

      if (pg_num_rows($gambar) == 0):
      ?>
        <p class="text-muted fst-italic">Belum ada data untuk kategori ini.</p>

      <?php else: while ($g = pg_fetch_assoc($gambar)): 
        $ext = pathinfo($g['file_path'], PATHINFO_EXTENSION);
        $isVideo = in_array(strtolower($ext), ['mp4','webm','ogg']); ?>

        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 d-flex justify-content-center">
          <div class="card shadow-sm border-0 galeri-card kategori-card">

            <?php if ($isVideo): ?>
              <video class="kategori-img"
                     style="width:100%;height:250px;object-fit:cover;cursor:pointer;border-radius:10px;"
                     data-bs-toggle="modal"
                     data-bs-target="#imageModal"
                     data-video="assets/img/<?= $g['file_path'] ?>"
                     data-title="<?= $g['judul'] ?>">
                <source src="assets/img/<?= $g['file_path'] ?>" type="video/mp4">
              </video>
            <?php else: ?>
              <img src="assets/img/<?= $g['file_path'] ?>"
                   class="kategori-img"
                   alt="<?= $g['judul'] ?>"
                   data-bs-toggle="modal"
                   data-bs-target="#imageModal"
                   data-img="assets/img/<?= $g['file_path'] ?>"
                   data-title="<?= $g['judul'] ?>">
            <?php endif; ?>

            <div class="card-body text-center p-2">
              <small class="text-muted"><?= $g['judul'] ?></small>
            </div>
          </div>
        </div>

      <?php endwhile; endif; ?>
    </div>

  <?php endwhile; ?>

</section>

<?php include 'includes/footer.php'; ?>

<!-- ============================= -->
<!-- MODAL GAMBAR / VIDEO -->
<!-- ============================= -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white border-0">
      <div class="modal-header border-0">
        <h6 class="modal-title" id="imageTitle"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div id="modalMediaContainer"></div>
      </div>
    </div>
  </div>
</div>


<!-- ============================= -->
<!-- STYLE (TIDAK DIUBAH) -->
<!-- ============================= -->
<style>
section.container-lg {
  min-height: 75vh;
}

.galeri-card {
  border-radius: 10px;
  overflow: hidden;
  transition: 0.3s;
}
.galeri-card:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* ===== Terbaru full width ===== */
.terbaru-wrapper-full {
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  width: 100vw;
  padding: 0 40px;
}

.terbaru-title {
  font-size: 1.5rem;
  font-weight: 600;
}

/* Carousel */
.terbaru-carousel {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
}

.terbaru-track-wrapper {
  overflow: hidden;
  flex: 1;
}

.terbaru-track {
  display: flex;
  transition: transform 0.4s ease;
}

.terbaru-slide {
  flex: 0 0 490px;
  margin-right: 20px;
}

.terbaru-card {
  width: 490px;
  height: 550px;
  border-radius: 15px;
}

.terbaru-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.terbaru-nav {
  border: none;
  background: rgba(0,0,0,0.4);
  color: #fff;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}
.terbaru-nav:hover {
  background: rgba(0,0,0,0.6);
}

/* Kategori */
.kategori-card { width: 300px !important; }
.kategori-img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  cursor: pointer;
}

/* responsive */
@media (max-width: 1200px) {
  .terbaru-slide { flex: 0 0 380px; }
  .terbaru-card { width: 380px; height: 500px; }
}

@media (max-width:768px){
  .terbaru-wrapper-full { padding: 0 15px; }
  .terbaru-slide { flex: 0 0 80%; margin-right: 10px; }
  .terbaru-card { width: 100%; height: auto; }
  .terbaru-img { height: auto; }
}
</style>

<!-- ============================= -->
<!-- SCRIPT MODAL + CAROUSEL -->
<!-- ============================= -->
<script>
// Modal foto / video
document.querySelectorAll('.kategori-img, .terbaru-img').forEach(media => {
  media.addEventListener('click', () => {
    const container = document.getElementById('modalMediaContainer');
    container.innerHTML = ""; 
    document.getElementById('imageTitle').innerText = media.dataset.title;

    if (media.dataset.video) {
      container.innerHTML = `<video controls autoplay style="width:100%;border-radius:10px;">
          <source src="${media.dataset.video}" type="video/mp4"></video>`;
    } 
    else {
      container.innerHTML = `<img src="${media.dataset.img}" class="img-fluid rounded">`;
    }
  });
});


// =============================
// CAROUSEL TANPA RUANG KOSONG
// =============================
document.addEventListener('DOMContentLoaded', function () {

  const track   = document.querySelector('.terbaru-track');
  const slides  = Array.from(document.querySelectorAll('.terbaru-slide'));
  const prevBtn = document.querySelector('.terbaru-prev');
  const nextBtn = document.querySelector('.terbaru-next');

  let index = 0;
  const maxIndex = slides.length - 1;

  function updateCarousel() {
    const slideWidth = slides[0].offsetWidth + 20;
    track.style.transform = `translateX(-${index * slideWidth}px)`;

    // Disable tombol saat mentok
    prevBtn.style.opacity = index === 0 ? "0.2" : "1";
    prevBtn.style.pointerEvents = index === 0 ? "none" : "auto";

    nextBtn.style.opacity = index === maxIndex ? "0.2" : "1";
    nextBtn.style.pointerEvents = index === maxIndex ? "none" : "auto";
  }

  nextBtn.addEventListener('click', () => {
    if (index < maxIndex) index++;
    updateCarousel();
  });

  prevBtn.addEventListener('click', () => {
    if (index > 0) index--;
    updateCarousel();
  });

  updateCarousel();
});
</script>