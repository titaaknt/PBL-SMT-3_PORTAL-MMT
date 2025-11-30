/**
 * ============================= 
 * GALERI.JS - JavaScript untuk Galeri Multimedia
 * =============================
 */

// ============================= 
// MODAL GAMBAR
// =============================
document.querySelectorAll('.kategori-img, .terbaru-img').forEach(img => {
  img.addEventListener('click', () => {
    document.getElementById('modalImage').src = img.dataset.img;
    document.getElementById('imageTitle').innerText = img.dataset.title;
  });
});

// ============================= 
// MODAL VIDEO
// =============================
document.querySelectorAll('.video-wrapper').forEach(wrapper => {
  wrapper.addEventListener('click', () => {
    const src = wrapper.dataset.video;
    const title = wrapper.dataset.title || '';
    const modalVideo = document.getElementById('modalVideo');
    const videoTitle = document.getElementById('videoTitle');

    if (modalVideo) {
      modalVideo.src = src;
      modalVideo.load();
    }
    if (videoTitle) {
      videoTitle.innerText = title;
    }
  });
});

// Hentikan video saat modal ditutup
const videoModalEl = document.getElementById('videoModal');
if (videoModalEl) {
  videoModalEl.addEventListener('hidden.bs.modal', () => {
    const modalVideo = document.getElementById('modalVideo');
    if (modalVideo) {
      modalVideo.pause();
      modalVideo.src = '';
    }
  });
}

// ============================= 
// TOGGLE FILTER PANEL
// =============================
const toggleBtn = document.getElementById('toggleFilterBtn');
const filterPanel = document.getElementById('globalFilterPanel');
const filterBtnText = document.getElementById('filterBtnText');
const filterBtnIcon = document.getElementById('filterBtnIcon');

if (toggleBtn && filterPanel) {
  // Set initial state - panel terbuka
  filterPanel.classList.remove('hidden');
  
  toggleBtn.addEventListener('click', () => {
    if (filterPanel.classList.contains('hidden')) {
      // Tampilkan panel filter
      filterPanel.classList.remove('hidden');
      filterBtnText.textContent = 'Sembunyikan Filter';
      toggleBtn.classList.remove('collapsed');
    } else {
      // Sembunyikan panel filter
      filterPanel.classList.add('hidden');
      filterBtnText.textContent = 'Tampilkan Filter';
      toggleBtn.classList.add('collapsed');
    }
  });
  
  // Tutup dropdown ketika klik di luar
  document.addEventListener('click', (e) => {
    if (!toggleBtn.contains(e.target) && !filterPanel.contains(e.target)) {
      if (!filterPanel.classList.contains('hidden')) {
        filterPanel.classList.add('hidden');
        filterBtnText.textContent = 'Tampilkan Filter';
        toggleBtn.classList.add('collapsed');
      }
    }
  });
}

// ============================= 
// FILTER GLOBAL UNTUK SEMUA KATEGORI
// =============================
document.querySelectorAll('.filter-option').forEach(btn => {
  btn.addEventListener('click', () => {
    const filter = btn.dataset.filter; // all / foto / video
    
    // Update active state
    document.querySelectorAll('.filter-option').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Terapkan filter ke SEMUA kategori
    document.querySelectorAll('.galeri-item').forEach(item => {
      const type = item.dataset.mediaType; // "foto" atau "video"
      if (filter === 'all' || type === filter) {
        item.classList.remove('hidden');
        item.style.display = '';
      } else {
        item.classList.add('hidden');
        item.style.display = 'none';
      }
    });
  });
});

// ============================= 
// CAROUSEL TERBARU
// =============================
document.addEventListener('DOMContentLoaded', function () {
  const track = document.querySelector('.terbaru-track');
  const slides = Array.from(document.querySelectorAll('.terbaru-slide'));
  const btnPrev = document.querySelector('.terbaru-prev');
  const btnNext = document.querySelector('.terbaru-next');
  const wrapper = document.querySelector('.terbaru-track-wrapper');

  if (!track || slides.length === 0 || !wrapper) return;

  let currentIndex = 0;
  let autoPlayTimer = null;

  /**
   * Mendapatkan offset maksimal untuk track
   */
  function getMaxOffset() {
    const slideWidth = slides[0].offsetWidth + 20; // 20 = margin-right
    const trackWidth = slideWidth * slides.length;
    const wrapperWidth = wrapper.offsetWidth;
    return Math.max(0, trackWidth - wrapperWidth);
  }

  /**
   * Pindah ke slide tertentu
   */
  function goToSlide(index) {
    const slideWidth = slides[0].offsetWidth + 20;
    currentIndex = Math.max(0, Math.min(index, slides.length - 1));
    const rawOffset = slideWidth * currentIndex;
    const maxOffset = getMaxOffset();
    const offset = Math.min(rawOffset, maxOffset);
    track.style.transform = `translateX(-${offset}px)`;
  }

  /**
   * Pindah ke slide berikutnya
   */
  function nextSlide() {
    if (currentIndex < slides.length - 1) {
      goToSlide(currentIndex + 1);
    }
  }

  /**
   * Pindah ke slide sebelumnya
   */
  function prevSlide() {
    if (currentIndex > 0) {
      goToSlide(currentIndex - 1);
    }
  }

  /**
   * Mulai autoplay carousel
   */
  function startAutoPlay() {
    stopAutoPlay();
    autoPlayTimer = setInterval(() => {
      if (currentIndex < slides.length - 1) {
        nextSlide();
      } else {
        goToSlide(0); // Kembali ke awal
      }
    }, 4000); // 4 detik per slide
  }

  /**
   * Stop autoplay carousel
   */
  function stopAutoPlay() {
    if (autoPlayTimer) {
      clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
  }

  // Event listeners untuk tombol navigasi
  if (btnNext) {
    btnNext.addEventListener('click', () => {
      nextSlide();
      startAutoPlay(); // Restart autoplay
    });
  }

  if (btnPrev) {
    btnPrev.addEventListener('click', () => {
      prevSlide();
      startAutoPlay(); // Restart autoplay
    });
  }

  // Pause autoplay saat hover
  const carousel = document.querySelector('.terbaru-carousel');
  if (carousel) {
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);
  }

  // Inisialisasi carousel
  goToSlide(0);
  startAutoPlay();
});