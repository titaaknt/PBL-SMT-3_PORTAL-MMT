<?php 
include 'includes/header.php';
?>

<?php
// =========================
// PAGINATION
// =========================
$limit = 6; // tampil 6 berita per halaman
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Hitung total berita
$resTotal = pg_query($conn, "SELECT COUNT(*) AS total FROM berita");
$totalDataRow = pg_fetch_assoc($resTotal);
$totalData = isset($totalDataRow['total']) ? (int)$totalDataRow['total'] : 0;
$totalPage = $totalData > 0 ? ceil($totalData / $limit) : 1;
if ($page > $totalPage) $page = $totalPage;

// Ambil data berita sesuai halaman
$query = pg_query($conn, 
  "SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset"
);
?>

<main class="flex-fill">

<section class="container-lg px-4 my-5 berita-section">
  <h3 class="text-primary fw-bold mb-4 text-center">Berita</h3>

  <div class="row">

    <?php
    if (pg_num_rows($query) == 0) {
        echo '<p class="text-muted text-center">Belum ada berita yang ditambahkan.</p>';
    } else {
        while ($data = pg_fetch_assoc($query)) {

            // preview untuk kartu (plain text)
            $preview = substr(strip_tags($data['isi']), 0, 80) . '...';

            // gunakan base64 untuk menyimpan isi berita (agar HTML tetap utuh di atribut)
            $isi_base64 = base64_encode($data['isi']);
            $judul_attr = htmlspecialchars($data['judul'], ENT_QUOTES);
            $tanggal_fmt = date('d M Y', strtotime($data['tanggal']));
            $gambar_path = 'assets/img/' . htmlspecialchars($data['gambar'], ENT_QUOTES);

            echo '
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm h-100 border-0 berita-card"
                    style="cursor:pointer"
                    data-judul="'.$judul_attr.'"
                    data-isi="'.$isi_base64.'"
                    data-tanggal="'.$tanggal_fmt.'"
                    data-gambar="'.$gambar_path.'">

                    <img src="'.$gambar_path.'" class="card-img-top"
                         style="height:220px;object-fit:cover;border-top-left-radius:10px;border-top-right-radius:10px;">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary fw-semibold">'.htmlspecialchars($data['judul']).'</h5>
                        <p class="card-text text-muted mb-3">'.$preview.'</p>
                        <small class="text-muted mt-auto">'.$tanggal_fmt.'</small>
                    </div>
                </div>
            </div>';
        }
    }
    ?>

  </div>

  <!-- ============================= -->
  <!-- PAGINATION -->
  <!-- ============================= -->
  <div class="d-flex justify-content-center my-4">
    <nav aria-label="Page navigation">
      <ul class="pagination">

        <!-- Previous -->
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>">«</a>
          </li>
        <?php endif; ?>

        <!-- Number -->
        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
          <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPage): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?>">»</a>
          </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>

</section>

</main>

<?php include 'includes/footer.php'; ?>


<!-- ============================= -->
<!-- MODAL DETAIL BERITA -->
<!-- ============================= -->
<div class="modal fade" id="beritaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 rounded-3">

      <img id="modalGambar" class="w-100 rounded-top" style="height:330px; object-fit:cover;" src="">

      <div class="modal-body px-4">
        <h4 id="modalJudul" class="fw-bold text-primary"></h4>
        <p class="text-muted" id="modalTanggal"></p>

        <div id="modalIsi" class="mt-3"></div>
      </div>

      <div class="modal-footer border-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>


<!-- ============================= -->
<!-- STYLE TAMBAHAN -->
<!-- ============================= -->
<style>
.berita-card {
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

.berita-card:hover {
    transform: translateY(-6px);
    box-shadow:
        0 12px 22px rgba(0, 0, 0, 0.06),
        0 28px 60px rgba(0, 0, 0, 0.10) !important;
}

/* FIX FOOTER DI BAWAH */
html, body { height: 100%; }
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
main.flex-fill { flex: 1 0 auto; }

/* Batasi judul dalam card */
.card-title {
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;

    display: -webkit-box;
    -webkit-line-clamp: 2;       /* batasi baris (optional) */
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Batasi preview deskripsi */
.card-text {
    white-space: normal !important;
    word-break: break-word !important;

    display: -webkit-box;
    -webkit-line-clamp: 3;       /* batasi baris (optional) */
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Judul dalam modal biar tidak meluber */
#modalJudul {
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
}

/* Supaya isi berita di modal tidak meluber */
#modalIsi {
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
}
</style>


<!-- ============================= -->
<!-- SCRIPT MODAL -->
<!-- ============================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // pilih semua kartu berita
    document.querySelectorAll('.berita-card').forEach(card => {
        card.addEventListener('click', () => {
            // ambil atribut
            const judul = card.dataset.judul || '';
            const tanggal = card.dataset.tanggal || '';
            const gambar = card.dataset.gambar || '';
            let isi_base64 = card.dataset.isi || '';

            // decode base64 -> isi (mengandung HTML)
            let isi = '';
            try {
                // atob for base64 decode (may throw if invalid)
                isi = isi_base64 ? atob(isi_base64) : '';
            } catch (e) {
                isi = '';
            }

            // deteksi sumber: split by "#sumber:" (case-insensitive)
            let sumber = '';
            const sumberMatch = isi.match(/#sumber\s*:\s*([\s\S]*)$/i);
            if (sumberMatch) {
                sumber = sumberMatch[1].trim();
                // hapus bagian sumber dari isi
                isi = isi.substring(0, sumberMatch.index).trim();
            }

            // set modal content
            document.getElementById('modalJudul').textContent = judul;
            document.getElementById('modalTanggal').textContent = tanggal;
            const modalGambar = document.getElementById('modalGambar');
            if (modalGambar) {
                modalGambar.src = gambar || '';
            }

            // isi bisa mengandung HTML => masukkan ke innerHTML
            let html = '';
            if (isi) {
                html += '<div>' + isi + '</div>';
            }
            if (sumber) {
                html += `
                    <hr>
                    <p class="mt-1 text-muted">
                        <strong>Sumber:</strong> ${sumber}
                    </p>`;
            }

            document.getElementById('modalIsi').innerHTML = html;

            // tampilkan modal (Bootstrap 5)
            const modalEl = document.getElementById('beritaModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    });
});
</script>