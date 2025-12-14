<?php 
include 'includes/header.php';
?>

<!-- ============================= -->
<!-- CSS GALERI (INLINE)          -->
<!-- ============================= -->
<style>
/* (CSS Anda persis sama — saya tidak mengubah apa pun) */
section.container-lg {
  min-height: 75vh;
}

/* ============================= */
/* CARD UMUM */
/* ============================= */
.galeri-card {
    border: none !important;
    background: #fff !important;
    border-radius: 16px !important;
    overflow: hidden;

    /* Soft shadow premium */
    box-shadow:
        0 8px 12px rgba(0, 0, 0, 0.04),
        0 20px 40px rgba(0, 0, 0, 0.07) !important;

    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.galeri-card:hover {
    transform: translateY(-6px);
    box-shadow:
        0 12px 22px rgba(0, 0, 0, 0.06),
        0 28px 60px rgba(0, 0, 0, 0.10) !important;
}
/* ============================= */
/* FULL-WIDTH UNTUK TERBARU */
/* ============================= */
.terbaru-wrapper-full {
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  width: 100vw;
  padding: 0 40px;
}

/* Judul Terbaru */
.terbaru-header {
  padding-top: 10px;
}

.terbaru-title {
  font-size: 1.5rem;
  font-weight: 600;
}

/* ============================= */
/* TERBARU CAROUSEL */
/* ============================= */
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
  flex-wrap: nowrap;
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

/* Media (img / video) terbaru */
.terbaru-media {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  border-radius: 15px;
}

/* Tombol next / prev terbaru */
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
  transition: 0.3s;
}

.terbaru-nav:hover {
  background: rgba(0,0,0,0.6);
}

/* ============================= */
/* KATEGORI: CARD & MEDIA */
/* ============================= */
.kategori-card {
  width: 320px;
  height: 380px;
  border-radius: 15px;
}

.kategori-media {
  width: 100%;
  height: 80%;
  object-fit: cover;
  cursor: pointer;
  border-radius: 15px 15px 0 0;
}

/* ============================= */
/* VIDEO WRAPPER + PLAY ICON */
/* ============================= */
.video-wrapper {
  position: relative;
  cursor: pointer;
  width: 100%;
  height: 100%;
}

.video-wrapper video {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 15px 15px 0 0;
}

.terbaru-video {
  height: 100%;
}

.kategori-video {
  height: 100%;
}

.play-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 70px;
  color: #fff;
  opacity: 0.8;
  pointer-events: none;
  text-shadow: 0 0 10px rgba(0,0,0,0.7);
  transition: 0.2s;
}

.video-wrapper:hover .play-icon {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1.1);
}

/* ============================= */
/* FILTER TOGGLE WRAPPER */
/* ============================= */
.filter-toggle-wrapper {
  position: relative;
  display: inline-block;
}

#toggleFilterBtn {
  display: flex;
  align-items: center;
  gap: 5px;
  transition: all 0.3s ease;
  position: relative;
  z-index: 10;
}

#filterBtnIcon {
  transition: transform 0.3s ease;
  display: inline-block;
  font-size: 0.8rem;
}

#toggleFilterBtn.collapsed #filterBtnIcon {
  transform: rotate(180deg);
}

/* ============================= */
/* DROPDOWN FILTER */
/* ============================= */
.filter-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 8px;
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  min-width: 150px;
  z-index: 1000;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: all 0.3s ease;
  opacity: 1;
  transform: translateY(0);
}

.filter-dropdown.hidden {
  opacity: 0;
  transform: translateY(-10px);
  pointer-events: none;
}

.filter-option {
  padding: 10px 20px;
  border: none;
  background: white;
  text-align: left;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.875rem;
  color: #495057;
  border-bottom: 1px solid #f0f0f0;
}

.filter-option:last-child {
  border-bottom: none;
}

.filter-option:hover {
  background: #f8f9fa;
  color: #0d6efd;
}

.filter-option.active {
  background: #0d6efd;
  color: white;
  font-weight: 500;
}

.filter-option.active:hover {
  background: #0b5ed7;
}

/* ============================= */
/* ANIMASI ITEM */
/* ============================= */
.galeri-item {
  transition: all 0.3s ease;
}

.galeri-item.hidden {
  display: none !important;
}

/* ============================= */
/* KATEGORI CAROUSEL MENYAMPING */
/* ============================= */
.kategori-carousel {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
}

.kategori-track-wrapper {
  overflow: hidden;
  flex: 1;
}

.kategori-track {
  display: flex;
  flex-wrap: nowrap;      /* supaya konten menyamping, bukan turun */
  transition: transform 0.4s ease;
}

.kategori-slide {
  flex: 0 0 320px;        /* lebar satu kartu kategori */
  margin-right: 20px;
}

/* Tombol next / prev kategori */
.kategori-nav {
  border: none;
  background: rgba(0,0,0,0.4);
  color: #fff;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: 0.3s;
  font-size: 0.9rem;
}

.kategori-nav:hover {
  background: rgba(0,0,0,0.6);
}

/* ============================= */
/* RESPONSIVE DESIGN */
/* ============================= */

@media (max-width: 1200px) {
  .terbaru-slide {
    flex: 0 0 380px;
  }
  
  .terbaru-card {
    width: 380px;
    height: 500px;
  }

  .kategori-slide {
    flex: 0 0 260px;
    margin-right: 16px;
  }
}

@media (max-width: 768px) {
  .terbaru-wrapper-full {
    padding: 0 15px;
  }

  .terbaru-carousel {
    gap: 5px;
  }
  
  .terbaru-slide {
    flex: 0 0 80%;
    margin-right: 10px;
  }
  
  .terbaru-card {
    width: 100%;
    height: auto;
  }
  
  .terbaru-media {
    height: auto;
  }

  .filter-dropdown {
    right: 0;
    left: auto;
    min-width: 140px;
  }
  
  #toggleFilterBtn {
    font-size: 0.813rem;
    padding: 0.375rem 0.75rem;
  }
  
  .kategori-slide {
    flex: 0 0 70%;
    margin-right: 12px;
  }

  .kategori-card {
    width: 100%;
  }
}

@media (max-width: 576px) {
  .terbaru-title {
    font-size: 1.25rem;
  }
  
  .play-icon {
    font-size: 50px;
  }
  
  .terbaru-nav {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }
}
</style>

<!-- ============================= -->
<!-- GALERI SECTION (HTML + PHP)  -->
<!-- ============================= -->
<section class="container-lg px-4 my-5">

  <h3 class="text-primary fw-bold mb-4 text-center">Galeri Multimedia</h3>

  <?php  
  // Ambil hanya 6 item terbaru (bisa gambar / video)
  $galeri_terbaru = pg_query($conn, "SELECT * FROM galeri ORDER BY id DESC LIMIT 6");
  ?>

  <!-- WRAPPER FULL WIDTH UNTUK TERBARU -->
  <div class="terbaru-wrapper-full">

    <!-- JUDUL TERBARU -->
    <div class="terbaru-header d-flex align-items-center mb-3">
      <h4 class="fw-semibold terbaru-title mb-0">Terbaru</h4>
    </div>

    <!-- CAROUSEL TERBARU -->
    <div class="terbaru-carousel mb-5">

      <button class="terbaru-nav terbaru-prev" type="button">&#10094;</button>

      <div class="terbaru-track-wrapper">
        <div class="terbaru-track">

          <?php while ($t = pg_fetch_assoc($galeri_terbaru)): 
            $ext = strtolower(pathinfo($t['file_path'], PATHINFO_EXTENSION));
            $isVideo = in_array($ext, ['mp4','webm','ogg']);
          ?>
            <div class="terbaru-slide" data-media-type="<?= $isVideo ? 'video' : 'foto' ?>">
              <div class="card shadow-sm border-0 galeri-card terbaru-card">

                <?php if ($isVideo): ?>
                  <!-- VIDEO TERBARU -->
                  <div class="video-wrapper"
                       data-video="assets/img/<?= htmlspecialchars($t['file_path']) ?>"
                       data-title="<?= htmlspecialchars($t['judul']) ?>">
                    <video class="card-img-top terbaru-media terbaru-video video-thumb" preload="metadata" muted>
                      <source src="assets/img/<?= htmlspecialchars($t['file_path']) ?>" type="video/<?= htmlspecialchars($ext) ?>">
                    </video>
                    <div class="play-icon">&#9658;</div>
                  </div>
                <?php else: ?>
                  <!-- GAMBAR TERBARU -->
                  <img src="assets/img/<?= htmlspecialchars($t['file_path']) ?>"
                       class="card-img-top terbaru-media terbaru-img"
                       alt="<?= htmlspecialchars($t['judul']) ?>"
                       data-img="assets/img/<?= htmlspecialchars($t['file_path']) ?>"
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


  <!-- ===================================== -->
  <!-- KATEGORI SECTION - CAROUSEL MENYAMPING -->
  <!-- ===================================== -->
  <?php
  $kategori = pg_query($conn, "SELECT * FROM kategori_galeri ORDER BY judul ASC");
  $first = true;

  while ($kat = pg_fetch_assoc($kategori)):
  ?>
    <div class="kategori-section mb-5" data-kategori="<?= $kat['id'] ?>">

      <!-- Header kategori + filter global (hanya di kategori pertama) -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-semibold mb-0"><?= htmlspecialchars($kat['judul']) ?></h6>

        <?php if ($first): ?>
          <div class="filter-toggle-wrapper">
            <button type="button" class="btn btn-sm btn-primary" id="toggleFilterBtn">
              <span id="filterBtnText">Sembunyikan Filter</span>
              <span id="filterBtnIcon">▲</span>
            </button>

            <div class="filter-dropdown" id="globalFilterPanel">
              <button type="button" class="filter-option active" data-filter="all">Semua</button>
              <button type="button" class="filter-option" data-filter="foto">Foto</button>
              <button type="button" class="filter-option" data-filter="video">Video</button>
            </div>
          </div>
          <?php $first = false; ?>
        <?php endif; ?>
      </div>

      <?php
      $gambar = pg_query(
        $conn,
        "SELECT * FROM galeri
         WHERE kategori_id = {$kat['id']}
         ORDER BY id DESC
         LIMIT 20"
      );

      if (pg_num_rows($gambar) == 0):
      ?>
        <p class="text-muted fst-italic">Belum ada data untuk kategori ini.</p>

      <?php else: ?>
        <!-- CAROUSEL KATEGORI MENYAMPING -->
        <div class="kategori-carousel" data-kategori-id="<?= $kat['id'] ?>">
          <button class="kategori-nav kategori-prev" type="button">&#10094;</button>

          <div class="kategori-track-wrapper">
            <div class="kategori-track">
              <?php while ($g = pg_fetch_assoc($gambar)): 
                $extG = strtolower(pathinfo($g['file_path'], PATHINFO_EXTENSION));
                $isVideoG = in_array($extG, ['mp4','webm','ogg']);
                $mediaType = $isVideoG ? 'video' : 'foto';
              ?>
                <div class="kategori-slide galeri-item"
                     data-media-type="<?= $mediaType ?>">
                  <div class="card shadow-sm border-0 galeri-card kategori-card">

                    <?php if ($isVideoG): ?>
                      <!-- VIDEO KATEGORI -->
                      <div class="video-wrapper"
                           data-video="assets/img/<?= htmlspecialchars($g['file_path']) ?>"
                           data-title="<?= htmlspecialchars($g['judul']) ?>">
                        <video class="card-img-top kategori-media kategori-video video-thumb" preload="metadata" muted>
                          <source src="assets/img/<?= htmlspecialchars($g['file_path']) ?>" type="video/<?= htmlspecialchars($extG) ?>">
                        </video>
                        <div class="play-icon">&#9658;</div>
                      </div>
                    <?php else: ?>
                      <!-- GAMBAR KATEGORI -->
                      <img src="assets/img/<?= htmlspecialchars($g['file_path']) ?>"
                           class="card-img-top kategori-media kategori-img"
                           alt="<?= htmlspecialchars($g['judul']) ?>"
                           data-img="assets/img/<?= htmlspecialchars($g['file_path']) ?>"
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
      <?php endif; ?>
    </div>

  <?php endwhile; ?>

</section>

<!-- ============================= -->
<!-- MODAL GAMBAR -->
<!-- ============================= -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white border-0">
      <div class="modal-header border-0">
        <h6 class="modal-title" id="imageTitle"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>

<!-- ============================= -->
<!-- MODAL VIDEO -->
<!-- ============================= -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white border-0">
      <div class="modal-header border-0">
        <h6 class="modal-title" id="videoTitle"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <video id="modalVideo" class="w-100 rounded" controls style="max-height:80vh; object-fit:contain;"></video>
      </div>
    </div>
  </div>
</div>

<!-- ============================= -->
<!-- JAVASCRIPT GALERI (INLINE)   -->
<!-- ============================= -->
<script>
/**
 * GALERI.JS - JavaScript untuk Galeri Multimedia
 * Perbaikan: klik gambar/video, mencegah freeze saat modal video ditutup,
 *            filter hanya memengaruhi kategori (TERBARU tidak ikut ter-filter).
 */

/* ====== HELPER ====== */
function setTrackTransform(el, offset) {
  if (!el) return;
  el.style.transform = 'translateX(-' + offset + 'px)';
}

/* ====== IMAGE CLICK (delegation safe) ====== */
document.addEventListener('click', function (e) {
  const img = e.target.closest('.kategori-img, .terbaru-img');
  if (!img) return;
  e.stopPropagation();

  const src = img.dataset.img || img.getAttribute('src');
  const title = img.dataset.title || '';
  const modalImage = document.getElementById('modalImage');
  const imageTitle = document.getElementById('imageTitle');
  if (modalImage) modalImage.src = src;
  if (imageTitle) imageTitle.innerText = title;

  // show bootstrap modal
  const m = new bootstrap.Modal(document.getElementById('imageModal'));
  m.show();
});

/* ====== VIDEO CLICK (delegation safe) ====== */
(function() {
  const modalEl = document.getElementById('videoModal');
  const modalVideo = document.getElementById('modalVideo');
  const videoTitle = document.getElementById('videoTitle');

  if (!modalEl || !modalVideo) {
    // still bind wrappers to show simple fallback if needed
    document.addEventListener('click', function(e){
      const wrapper = e.target.closest('.video-wrapper');
      if (!wrapper) return;
      e.stopPropagation();
      alert('Video modal belum tersedia.');
    });
    return;
  }

  function cleanupModalVideo() {
    try {
      modalVideo.pause();
      try { modalVideo.currentTime = 0; } catch(e) {}
      // remove children <source>
      while (modalVideo.firstChild) modalVideo.removeChild(modalVideo.firstChild);
      modalVideo.removeAttribute('src');
      try { modalVideo.load(); } catch(e){}
    } catch(err) {
      console.error('cleanupModalVideo', err);
    }
  }

  // delegate clicks on wrappers
  document.addEventListener('click', function(e){
    const wrapper = e.target.closest('.video-wrapper');
    if (!wrapper) return;
    e.stopPropagation();

    const src = wrapper.dataset.video;
    const title = wrapper.dataset.title || '';

    // cleanup previous
    cleanupModalVideo();

    // create new source element
    const source = document.createElement('source');
    source.src = src;
    const ext = (src.split('.').pop() || 'mp4').split('?')[0].toLowerCase();
    let mime = 'video/mp4';
    if (ext === 'webm') mime = 'video/webm';
    else if (ext === 'ogg' || ext === 'ogv') mime = 'video/ogg';
    source.type = mime;
    modalVideo.appendChild(source);

    if (videoTitle) videoTitle.innerText = title;
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // try to play after modal animation
    setTimeout(() => {
      try {
        const p = modalVideo.play();
        if (p && typeof p.catch === 'function') {
          p.catch(()=>{/* autoplay blocked */});
        }
      } catch (_) {}
    }, 250);
  });

  // stop & cleanup when modal starts hiding (prevents freeze)
  modalEl.addEventListener('hide.bs.modal', function () {
    try {
      modalVideo.pause();
      try { modalVideo.currentTime = 0; } catch(e) {}
    } catch(e){}
    // remove sources asap
    while (modalVideo.firstChild) modalVideo.removeChild(modalVideo.firstChild);
    modalVideo.removeAttribute('src');
    try { modalVideo.load(); } catch(e){}
  });

  modalEl.addEventListener('hidden.bs.modal', function () {
    // final cleanup & clear title
    while (modalVideo.firstChild) modalVideo.removeChild(modalVideo.firstChild);
    modalVideo.removeAttribute('src');
    try { modalVideo.load(); } catch(e){}
    if (videoTitle) videoTitle.innerText = '';
  });

  // also clean up on page unload
  window.addEventListener('beforeunload', function(){ 
    while (modalVideo.firstChild) modalVideo.removeChild(modalVideo.firstChild);
    modalVideo.removeAttribute('src');
  });
})();

/* ====== FILTER GLOBAL (HANYA MEMENGARUHI KATEGORI, BUKAN TERBARU) ====== */
(function(){
  const filterButtons = Array.from(document.querySelectorAll('.filter-option'));
  if (!filterButtons.length) return;

  filterButtons.forEach(btn => {
    btn.addEventListener('click', function (ev) {
      ev.stopPropagation(); // penting: mencegah handler klik dokumen mengganggu
      ev.preventDefault();

      const filter = btn.dataset.filter; // all / foto / video

      // update tombol aktif
      filterButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // HANYA filter item kategori (elemen yang berada di carousel kategori)
      // Kita target .kategori-slide (juga .galeri-item agar kompatibel)
      const kategoriItems = document.querySelectorAll('.kategori-slide, .galeri-item');

      kategoriItems.forEach(item => {
        const type = (item.dataset.mediaType || item.getAttribute('data-media-type') || 'foto').toString();
        if (filter === 'all' || type === filter) {
          item.classList.remove('hidden');
          item.style.display = '';
        } else {
          item.classList.add('hidden');
          item.style.display = 'none';
        }
      });

      // Reset transform hanya untuk track kategori (biar carousel tidak tertinggal posisi saat beberapa item disembunyikan)
      document.querySelectorAll('.kategori-track').forEach(trackK => setTrackTransform(trackK, 0));

      // NOTE: Tidak menyentuh .terbaru-slide / .terbaru-track — bagian "Terbaru" tidak akan ter-filter.
    });
  });
})();

/* ====== TOGGLE FILTER PANEL ====== */
(function(){
  const toggleBtn = document.getElementById('toggleFilterBtn');
  const filterPanel = document.getElementById('globalFilterPanel');
  const filterBtnText = document.getElementById('filterBtnText');

  if (!toggleBtn || !filterPanel) return;

  // show initially
  filterPanel.classList.remove('hidden');

  toggleBtn.addEventListener('click', function(ev){
    ev.stopPropagation();
    const hidden = filterPanel.classList.toggle('hidden');
    if (hidden) {
      filterBtnText.textContent = 'Tampilkan Filter';
      toggleBtn.classList.add('collapsed');
    } else {
      filterBtnText.textContent = 'Sembunyikan Filter';
      toggleBtn.classList.remove('collapsed');
    }
  });

  // close panel if clicking outside (but allow clicks inside)
  document.addEventListener('click', function(e){
    if (!toggleBtn.contains(e.target) && !filterPanel.contains(e.target)) {
      if (!filterPanel.classList.contains('hidden')) {
        filterPanel.classList.add('hidden');
        filterBtnText.textContent = 'Tampilkan Filter';
        toggleBtn.classList.add('collapsed');
      }
    }
  });
})();

/* ====== CAROUSEL TERBARU + KATEGORI ====== */
document.addEventListener('DOMContentLoaded', function () {
  // TERBARU CAROUSEL
  (function initTerbaruCarousel() {
    const track = document.querySelector('.terbaru-track');
    const wrapper = document.querySelector('.terbaru-track-wrapper');
    const slides = track ? Array.from(track.querySelectorAll('.terbaru-slide')) : [];
    const btnPrev = document.querySelector('.terbaru-prev');
    const btnNext = document.querySelector('.terbaru-next');

    if (!track || slides.length === 0 || !wrapper) return;

    let current = 0;

    function slideWidth() {
      const s = slides.find(sl => sl.style.display !== 'none' && sl.offsetWidth > 0);
      return s ? (s.offsetWidth + 20) : (slides[0].offsetWidth + 20);
    }

    function maxIndex() {
      const sw = slideWidth();
      const visible = Math.floor(wrapper.offsetWidth / sw) || 1;
      return Math.max(0, slides.filter(s=>s.style.display !== 'none').length - visible);
    }

    function goTo(i) {
      const slidesVisible = slides.filter(s => s.style.display !== 'none');
      if (slidesVisible.length === 0) {
        setTrackTransform(track, 0);
        return;
      }
      current = Math.max(0, Math.min(i, maxIndex()));
      const sw = slideWidth();
      const offset = current * sw;
      setTrackTransform(track, offset);
    }

    btnNext && btnNext.addEventListener('click', function(e) { e.stopPropagation(); goTo(current + 1); });
    btnPrev && btnPrev.addEventListener('click', function(e) { e.stopPropagation(); goTo(current - 1); });

    window.addEventListener('resize', function () { goTo(current); });

    goTo(0);
  })();

  // KATEGORI CAROUSELS (banyak)
  document.querySelectorAll('.kategori-carousel').forEach(function(carousel){
    const track = carousel.querySelector('.kategori-track');
    const wrapper = carousel.querySelector('.kategori-track-wrapper');
    const slides = Array.from(carousel.querySelectorAll('.kategori-slide'));
    const btnPrev = carousel.querySelector('.kategori-prev');
    const btnNext = carousel.querySelector('.kategori-next');

    if (!track || slides.length === 0 || !wrapper) return;

    let current = 0;

    function slideWidthK() {
      const s = slides[0];
      return s.offsetWidth + 20;
    }

    function maxIndexK() {
      const sw = slideWidthK();
      const visible = Math.floor(wrapper.offsetWidth / sw) || 1;
      return Math.max(0, slides.filter(s=>s.style.display !== 'none').length - visible);
    }

    function goToK(i) {
      current = Math.max(0, Math.min(i, maxIndexK()));
      const sw = slideWidthK();
      const offset = current * sw;
      setTrackTransform(track, offset);
    }

    btnNext && btnNext.addEventListener('click', function(e){ e.stopPropagation(); goToK(current + 1); });
    btnPrev && btnPrev.addEventListener('click', function(e){ e.stopPropagation(); goToK(current - 1); });

    window.addEventListener('resize', () => goToK(current));
    goToK(0);
  });
});
</script>

<?php include 'includes/footer.php'; ?>