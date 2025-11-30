<?php
include 'includes/header.php';

// ambil data profil
$q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
$data = pg_fetch_assoc($q);

// Tentukan gambar struktur
$struktur_img = trim($data['struktur_img'] ?? '');
$struktur_path = "assets/img/struktur-organisasi.png";

if ($struktur_img) {
    $temp_path = "assets/img/" . $struktur_img;
    if (file_exists(__DIR__ . "/" . $temp_path)) {
        $struktur_path = $temp_path;
    }
}
?>

<section class="container-lg my-5 px-4">
  <h3 class="text-primary fw-bold mb-4 text-center">Profil Laboratorium</h3>

  <!-- Gambar utama -->
  <div class="text-center mb-4">
    <div class="image-wrapper mx-auto shadow-sm">
      <img src="assets/img/labmmt.jpg"
           alt="Laboratorium Multimedia & Mobile Tech"
           class="img-fluid rounded-4"
           onclick="openModal('assets/img/labmmt.jpg')">
    </div>
    <p class="mt-3 text-muted small fst-italic">
      Laboratorium Multimedia & Mobile Tech - Politeknik Negeri Malang
    </p>
  </div>

  <div class="card shadow-sm border-0 rounded-4 p-4">
    <div class="profile-text">

      <h5 class="fw-bold text-primary mb-3">Visi</h5>
      <p><?= nl2br(htmlspecialchars($data['visi'])) ?></p>

      <h5 class="fw-bold text-primary mb-3 mt-4">Misi</h5>
      <p><?= nl2br(htmlspecialchars($data['misi'])) ?></p>

      <h5 class="fw-bold text-primary mb-3 mt-4">Struktur Organisasi</h5>
      <p><?= nl2br(htmlspecialchars($data['struktur'])) ?></p>

      <!-- Gambar Struktur Organisasi -->
      <div class="text-center mt-4">
        <div class="struktur-wrapper mx-auto shadow-sm">
          <img src="<?= htmlspecialchars($struktur_path) ?>"
               alt="Struktur Organisasi"
               class="img-fluid rounded-4"
               onclick="openModal('<?= htmlspecialchars($struktur_path) ?>')">
        </div>
        <p class="mt-2 text-muted small fst-italic">
          Struktur Organisasi Laboratorium Multimedia & Mobile Tech
        </p>
      </div>

      <h5 class="fw-bold text-primary mb-3 mt-5">Kontak Laboratorium</h5>
      <div class="row">

        <?php
        $qKontak = pg_query($conn, "SELECT * FROM kontak_detail ORDER BY id ASC");
        if (pg_num_rows($qKontak) > 0) {
            while ($k = pg_fetch_assoc($qKontak)) {
                echo '
                <div class="col-md-4 col-sm-6 mb-3 text-center">
                  <a href="' . htmlspecialchars($k['link']) . '" target="_blank" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm p-3 h-100">
                      <i class="bi ' . htmlspecialchars($k['icon']) . ' fs-2 text-primary mb-2"></i>
                      <p class="fw-semibold mb-0">' . htmlspecialchars($k['keterangan']) . '</p>
                    </div>
                  </a>
                </div>';
            }
        } else {
            echo "<p class='text-muted'>Belum ada data kontak ditambahkan.</p>";
        }
        ?>

      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- === MODAL POPUP GAMBAR === -->
<div id="imgModal" class="modal-img">
  <span class="close-btn" onclick="closeModal()">&times;</span>
  <img id="modalImage" class="modal-content-img">
</div>

<style>
/* --- Modal Style --- */
.modal-img {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.8);
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.modal-content-img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 12px;
  animation: zoomIn .3s ease;
}

@keyframes zoomIn {
  from { transform: scale(.7); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

.close-btn {
  position: absolute;
  top: 40px;
  right: 40px;
  font-size: 40px;
  font-weight: bold;
  color: white;
  cursor: pointer;
  user-select: none;
  text-shadow: 0 0 8px rgba(0,0,0,.8);
  transition: .2s;
}

.close-btn:hover {
  color: #ffdddd;
}

/* existing styles */
body {
  font-family: 'Poppins', sans-serif;
}

.card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.image-wrapper, .struktur-wrapper {
  border-radius: 20px;
  overflow: hidden;
  max-width: 850px;
  transition: .4s;
}

.image-wrapper img,
.struktur-wrapper img {
  transition: .4s;
}

.image-wrapper:hover img,
.struktur-wrapper:hover img {
  transform: scale(1.05);
  filter: brightness(1.05);
}
</style>

<script>
// === OPEN MODAL ===
function openModal(src) {
  document.getElementById('imgModal').style.display = 'flex';
  document.getElementById('modalImage').src = src;
}

// === CLOSE MODAL ===
function closeModal() {
  document.getElementById('imgModal').style.display = 'none';
}

// Klik area gelap → tutup modal
document.getElementById("imgModal").onclick = function(e) {
  if (e.target.id === "imgModal") {
      closeModal();
  }
}
</script>