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

  <!-- FILTER BAR: All | Foto | Video -->
  <div class="d-flex justify-content-center mb-4">
    <div class="btn-group" role="group" aria-label="Filter jenis">
      <button type="button" class="btn btn-outline-primary btn-sm type-filter active" data-type="all">All</button>
      <button type="button" class="btn btn-outline-primary btn-sm type-filter" data-type="photo">Foto</button>
      <button type="button" class="btn btn-outline-primary btn-sm type-filter" data-type="video">Video</button>
    </div>
  </div>

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
            // deteksi ekstensi
            $ext = pathinfo($t['file_path'], PATHINFO_EXTENSION);
            $isVideo = in_array(strtolower($ext), ['mp4','webm','ogg']);
            $type = $isVideo ? 'video' : 'photo';

            // cek poster thumbnail di server (assets/thumb/<filename>.jpg)
            $poster = '';
            $thumbPath = 'assets/thumb/' . basename($t['file_path']) . '.jpg';
            if (file_exists(__DIR__ . '/' . $thumbPath)) {
                $poster = $thumbPath;
            }
            // url media
            $mediaUrl = 'assets/img/' . $t['file_path'];
          ?>
            <div class="terbaru-slide" data-type="<?= $type ?>">
              <div class="card shadow-sm border-0 galeri-card terbaru-card">

                <?php if ($isVideo): ?>
                  <!-- tampilkan video sebagai thumbnail; gunakan poster jika tersedia -->
                  <video class="card-img-top terbaru-img"
                         style="width:100%;height:100%;object-fit:cover;border-radius:15px;cursor:pointer;"
                         preload="metadata"
                         <?php if ($poster): ?>poster="<?= htmlspecialchars($poster) ?>"<?php endif; ?>
                         data-bs-toggle="modal"
                         data-bs-target="#imageModal"
                         data-video="<?= htmlspecialchars($mediaUrl) ?>"
                         data-title="<?= htmlspecialchars($t['judul']) ?>">
                    <source src="<?= htmlspecialchars($mediaUrl) ?>#t=0.5" type="video/<?= htmlspecialchars($ext) ?>">
                    <?php if ($poster): ?>
                      <img src="<?= htmlspecialchars($poster) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($t['judul']) ?>">
                    <?php endif; ?>
                  </video>
                <?php else: ?>
                  <img src="<?= htmlspecialchars($mediaUrl) ?>" 
                       class="card-img-top terbaru-img"
                       alt="<?= htmlspecialchars($t['judul']) ?>"
                       data-bs-toggle="modal"
                       data-bs-target="#imageModal"
                       data-img="<?= htmlspecialchars($mediaUrl) ?>"
                       data-title="<?= htmlspecialchars($t['judul']) ?>">
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
  <!-- KATEGORI (dengan mini-carousel per kategori) -->
  <!-- ============================= -->
  <?php
  $kategori = pg_query($conn, "SELECT * FROM kategori_galeri ORDER BY judul ASC");

  while ($kat = pg_fetch_assoc($kategori)):
  ?>
    <h6 class="fw-semibold mb-3"><?= htmlspecialchars($kat['judul']) ?></h6>

    <?php
    // ambil semua media untuk kategori ini
    $gambar = pg_query(
      $conn,
      "SELECT * FROM galeri WHERE kategori_id = {$kat['id']} ORDER BY id DESC"
    );
    $countG = pg_num_rows($gambar);
    ?>

    <?php if ($countG == 0): ?>
      <p class="text-muted fst-italic">Belum ada data untuk kategori ini.</p>
    <?php else: ?>

      <!-- jika ada lebih dari 3 item, tampilkan sebagai carousel; kalau tidak tetap grid -->
      <?php if ($countG > 3): ?>
        <div class="kategori-carousel mb-4">
          <button class="kategori-nav kategori-prev" type="button">&#10094;</button>
          <div class="kategori-track-wrapper">
            <div class="kategori-track">
              <?php
              // render setiap item sebagai slide
              pg_result_seek($gambar, 0);
              while ($g = pg_fetch_assoc($gambar)):
                $ext = pathinfo($g['file_path'], PATHINFO_EXTENSION);
                $isVideo = in_array(strtolower($ext), ['mp4','webm','ogg']);
                $type = $isVideo ? 'video' : 'photo';
                $poster_k = '';
                $thumbPathK = 'assets/thumb/' . basename($g['file_path']) . '.jpg';
                if (file_exists(__DIR__ . '/' . $thumbPathK)) {
                    $poster_k = $thumbPathK;
                }
                $mediaUrlK = 'assets/img/' . $g['file_path'];
              ?>
                <div class="kategori-slide" data-type="<?= $type ?>">
                  <div class="card shadow-sm border-0 galeri-card kategori-card">
                    <?php if ($isVideo): ?>
                      <video class="kategori-img"
                             style="width:100%;height:230px;object-fit:cover;cursor:pointer;border-radius:10px;"
                             preload="metadata"
                             <?php if ($poster_k): ?>poster="<?= htmlspecialchars($poster_k) ?>"<?php endif; ?>
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal"
                             data-video="<?= htmlspecialchars($mediaUrlK) ?>"
                             data-title="<?= htmlspecialchars($g['judul']) ?>">
                        <source src="<?= htmlspecialchars($mediaUrlK) ?>#t=0.5" type="video/<?= htmlspecialchars($ext) ?>">
                        <?php if ($poster_k): ?>
                          <img src="<?= htmlspecialchars($poster_k) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($g['judul']) ?>">
                        <?php endif; ?>
                      </video>
                    <?php else: ?>
                      <img src="<?= htmlspecialchars($mediaUrlK) ?>"
                           class="kategori-img"
                           alt="<?= htmlspecialchars($g['judul']) ?>"
                           data-bs-toggle="modal"
                           data-bs-target="#imageModal"
                           data-img="<?= htmlspecialchars($mediaUrlK) ?>"
                           data-title="<?= htmlspecialchars($g['judul']) ?>">
                    <?php endif; ?>

                    <div class="card-body text-center p-2">
                      <small class="text-muted"><?= htmlspecialchars($g['judul']) ?></small>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
          <button class="kategori-nav kategori-next" type="button">&#10095;</button>
        </div>
      <?php else: ?>
        <!-- grid normal ketika item sedikit -->
        <div class="row mb-5">
          <?php
          pg_result_seek($gambar, 0);
          while ($g = pg_fetch_assoc($gambar)):
            $ext = pathinfo($g['file_path'], PATHINFO_EXTENSION);
            $isVideo = in_array(strtolower($ext), ['mp4','webm','ogg']);
            $type = $isVideo ? 'video' : 'photo';
            $poster_k = '';
            $thumbPathK = 'assets/thumb/' . basename($g['file_path']) . '.jpg';
            if (file_exists(__DIR__ . '/' . $thumbPathK)) {
                $poster_k = $thumbPathK;
            }
            $mediaUrlK = 'assets/img/' . $g['file_path'];
          ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3 d-flex justify-content-center">
              <div class="card shadow-sm border-0 galeri-card kategori-card" data-type="<?= $type ?>">
                <?php if ($isVideo): ?>
                  <video class="kategori-img"
                         style="width:100%;height:250px;object-fit:cover;cursor:pointer;border-radius:10px;"
                         preload="metadata"
                         <?php if ($poster_k): ?>poster="<?= htmlspecialchars($poster_k) ?>"<?php endif; ?>
                         data-bs-toggle="modal"
                         data-bs-target="#imageModal"
                         data-video="<?= htmlspecialchars($mediaUrlK) ?>"
                         data-title="<?= htmlspecialchars($g['judul']) ?>">
                    <source src="<?= htmlspecialchars($mediaUrlK) ?>#t=0.5" type="video/<?= htmlspecialchars($ext) ?>">
                    <?php if ($poster_k): ?>
                      <img src="<?= htmlspecialchars($poster_k) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($g['judul']) ?>">
                    <?php endif; ?>
                  </video>
                <?php else: ?>
                  <img src="<?= htmlspecialchars($mediaUrlK) ?>"
                       class="kategori-img"
                       alt="<?= htmlspecialchars($g['judul']) ?>"
                       data-bs-toggle="modal"
                       data-bs-target="#imageModal"
                       data-img="<?= htmlspecialchars($mediaUrlK) ?>"
                       data-title="<?= htmlspecialchars($g['judul']) ?>">
                <?php endif; ?>

                <div class="card-body text-center p-2">
                  <small class="text-muted"><?= htmlspecialchars($g['judul']) ?></small>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>

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
<!-- STYLE (TIDAK DIUBAH, ditambah sedikit utk tombol aktif + carousel kategori) -->
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

/* Carousel Terbaru */
.terbaru-carousel {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
}

.terbaru-track-wrapper { overflow: hidden; flex: 1; }
.terbaru-track { display:flex; transition: transform 0.4s ease; }
.terbaru-slide { flex: 0 0 490px; margin-right: 20px; }
.terbaru-card { width: 490px; height: 550px; border-radius:15px; }
.terbaru-img { width:100%; height:100%; object-fit:cover; }

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
.terbaru-nav:hover { background: rgba(0,0,0,0.6); }

/* ========== KATEGORI CAROUSEL ========== */
.kategori-carousel {
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom: 30px;
}
.kategori-nav {
  border:none;
  background: rgba(0,0,0,0.08);
  color:#333;
  width:36px;
  height:36px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
}
.kategori-nav:hover { background: rgba(0,0,0,0.12); }

.kategori-track-wrapper {
  overflow:hidden;
  flex:1;
}
.kategori-track {
  display:flex;
  gap:16px;
  transition: transform 0.35s ease;
  align-items:stretch;
}
.kategori-slide {
  flex: 0 0 240px;
}
.kategori-card { width:100%; }
.kategori-img { width:100%; height:230px; object-fit:cover; cursor:pointer; border-radius:10px; }

/* responsive */
@media (max-width: 1200px) {
  .terbaru-slide { flex: 0 0 380px; }
  .terbaru-card { width: 380px; height: 500px; }
  .kategori-slide { flex: 0 0 200px; }
}

@media (max-width:768px){
  .terbaru-wrapper-full { padding: 0 15px; }
  .terbaru-slide { flex: 0 0 80%; margin-right: 10px; }
  .terbaru-card { width: 100%; height: auto; }
  .terbaru-img { height: auto; }
  .kategori-slide { flex: 0 0 70%; }
}

/* FILTER button active */
.type-filter.active {
  background-color: #0b7bff;
  color: #fff;
  border-color: #0b7bff;
}
</style>

<!-- ============================= -->
<!-- SCRIPT MODAL + CAROUSEL + TYPE FILTER -->
<!-- ============================= -->
<script>
// Modal foto / video (mendukung <img> dan <video>)
document.querySelectorAll('.kategori-img, .terbaru-img, .kategori-img video, .terbaru-img video, .kategori-slide video, .terbaru-slide video').forEach(media => {
  media.addEventListener('click', (e) => {
    let el = e.currentTarget;
    const container = document.getElementById('modalMediaContainer');
    container.innerHTML = "";
    document.getElementById('imageTitle').innerText = el.dataset.title || '';

    if (el.dataset.video) {
      container.innerHTML = `<video controls autoplay style="width:100%;border-radius:10px;">
          <source src="${el.dataset.video}" type="video/mp4"></video>`;
    } else if (el.dataset.img) {
      container.innerHTML = `<img src="${el.dataset.img}" class="img-fluid rounded">`;
    } else if (el.tagName.toLowerCase() === 'video' && el.querySelector('source')) {
      const src = el.querySelector('source').src.split('#')[0];
      container.innerHTML = `<video controls autoplay style="width:100%;border-radius:10px;">
            <source src="${src}" type="video/mp4"></video>`;
    }
  });
});

// =============================
// CAROUSEL TERBARU (sama seperti sebelum)
// =============================
document.addEventListener('DOMContentLoaded', function () {

  // helper utk slides visibility
  const trackTerbaru   = document.querySelector('.terbaru-track');
  const prevTerbaru = document.querySelector('.terbaru-prev');
  const nextTerbaru = document.querySelector('.terbaru-next');

  function getVisibleSlidesTerbaru() {
    return Array.from(document.querySelectorAll('.terbaru-slide')).filter(s => s.style.display !== 'none');
  }

  let indexT = 0;
  function updateCarouselTerbaru() {
    const slides = getVisibleSlidesTerbaru();
    if (!slides.length) return;
    const slideWidth = slides[0].offsetWidth + 20;
    trackTerbaru.style.transform = `translateX(-${indexT * slideWidth}px)`;
    prevTerbaru.style.opacity = indexT === 0 ? "0.2" : "1";
    prevTerbaru.style.pointerEvents = indexT === 0 ? "none" : "auto";
    nextTerbaru.style.opacity = indexT >= slides.length - 1 ? "0.2" : "1";
    nextTerbaru.style.pointerEvents = indexT >= slides.length - 1 ? "none" : "auto";
  }
  nextTerbaru.addEventListener('click', ()=>{ if(indexT < getVisibleSlidesTerbaru().length-1) indexT++; updateCarouselTerbaru(); });
  prevTerbaru.addEventListener('click', ()=>{ if(indexT>0) indexT--; updateCarouselTerbaru(); });
  window.addEventListener('resize', ()=>{ indexT=0; updateCarouselTerbaru(); });
  updateCarouselTerbaru();

  // =============================
  // CAROUSEL PER-KATEGORI (mini slider)
  // =============================
  document.querySelectorAll('.kategori-carousel').forEach(car => {
    const trackWrapper = car.querySelector('.kategori-track-wrapper');
    const track = car.querySelector('.kategori-track');
    const prev = car.querySelector('.kategori-prev');
    const next = car.querySelector('.kategori-next');

    let idx = 0;
    function getVisibleSlides() {
      return Array.from(track.querySelectorAll('.kategori-slide')).filter(s => s.style.display !== 'none');
    }
    function update() {
      const slides = getVisibleSlides();
      if (!slides.length) return;
      const w = slides[0].offsetWidth + 16; // gap
      track.style.transform = `translateX(-${idx * w}px)`;
      prev.style.opacity = idx === 0 ? "0.3" : "1";
      prev.style.pointerEvents = idx === 0 ? "none" : "auto";
      next.style.opacity = idx >= slides.length - 1 ? "0.3" : "1";
      next.style.pointerEvents = idx >= slides.length - 1 ? "none" : "auto";
    }

    next.addEventListener('click', ()=> { if (idx < getVisibleSlides().length - 1) idx++; update(); });
    prev.addEventListener('click', ()=> { if (idx > 0) idx--; update(); });
    window.addEventListener('resize', ()=> { idx = 0; update(); });

    // initial
    update();
  });

  // ------------------------
  // TYPE FILTER (All / Foto / Video)
  // ------------------------
  const typeButtons = document.querySelectorAll('.type-filter');

  function setActiveType(btn) {
    typeButtons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  }

  function filterByType(type) {
    // slides terbaru
    document.querySelectorAll('.terbaru-slide').forEach(slide => {
      const t = slide.dataset.type || 'photo';
      slide.style.display = (type === 'all' || t === type) ? '' : 'none';
    });

    // kategori slides and cards
    document.querySelectorAll('.kategori-slide, .kategori-card').forEach(el => {
      const t = el.dataset.type || el.closest('.kategori-card')?.dataset?.type || 'photo';
      // for kategori-card (grid case), we might be operating on card element; find closest col to manipulate
      if (type === 'all' || t === type) {
        if (el.classList.contains('kategori-slide')) el.style.display = '';
        else {
          const col = el.closest('.col-lg-3, .col-md-4, .col-sm-6');
          if (col) col.style.display = '';
        }
      } else {
        if (el.classList.contains('kategori-slide')) el.style.display = 'none';
        else {
          const col = el.closest('.col-lg-3, .col-md-4, .col-sm-6');
          if (col) col.style.display = 'none';
        }
      }
    });

    // reset carousel positions
    document.querySelectorAll('.terbaru-track, .kategori-track').forEach(t => t.style.transform = 'translateX(0)');
  }

  typeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      setActiveType(btn);
      filterByType(btn.dataset.type);
    });
  });

});
</script>