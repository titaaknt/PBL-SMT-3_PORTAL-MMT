<?php 
include 'includes/header.php';

// =========================
// PAGINATION
// =========================
$limit = 6; // tampil 6 berita per halaman
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total berita
$resTotal = pg_query($conn, "SELECT COUNT(*) AS total FROM berita");
$totalData = pg_fetch_assoc($resTotal)['total'];
$totalPage = ceil($totalData / $limit);

// Ambil data berita sesuai halaman
$query = pg_query($conn, 
  "SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset"
);
?>

<main class="flex-fill">

<section class="container-lg px-4 my-5 berita-section">
  <h3 class="text-primary fw-bold mb-4 text-center">Berita & Kegiatan</h3>

  <div class="row">

    <?php
    if (pg_num_rows($query) == 0) {
        echo '<p class="text-muted text-center">Belum ada berita yang ditambahkan.</p>';
    } else {
        while ($data = pg_fetch_assoc($query)) {

            $preview = substr(strip_tags($data['isi']), 0, 80) . '...';

            echo '
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm h-100 border-0 berita-card"
                    style="cursor:pointer"
                    data-judul="'.htmlspecialchars($data['judul']).'"
                    data-isi="'.htmlspecialchars($data['isi']).'"
                    data-tanggal="'.date('d M Y', strtotime($data['tanggal'])).'"
                    data-gambar="assets/img/'.$data['gambar'].'">

                    <img src="assets/img/'.$data['gambar'].'" class="card-img-top"
                         style="height:220px;object-fit:cover;border-top-left-radius:10px;border-top-right-radius:10px;">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary fw-semibold">'.$data['judul'].'</h5>
                        <p class="card-text text-muted mb-3">'.$preview.'</p>
                        <small class="text-muted mt-auto">'.date('d M Y', strtotime($data['tanggal'])).'</small>
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

      <img id="modalGambar" class="w-100 rounded-top" style="height:330px; object-fit:cover;">

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
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 10px;
}
.berita-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

/* FIX FOOTER DI BAWAH */
html, body { height: 100%; }
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
main.flex-fill { flex: 1 0 auto; }
</style>


<!-- ============================= -->
<!-- SCRIPT MODAL -->
<!-- ============================= -->
<script>
document.querySelectorAll('.berita-card').forEach(card => {
    card.addEventListener('click', () => {

        document.getElementById('modalJudul').textContent = card.dataset.judul;
        document.getElementById('modalTanggal').textContent = card.dataset.tanggal;
        document.getElementById('modalGambar').src = card.dataset.gambar;

        let isi = card.dataset.isi;
        let sumber = "";

        // Deteksi sumber: #sumber:
        if (isi.toLowerCase().includes("#sumber")) {
            let pecah = isi.split(/#sumber *:/i);
            isi = pecah[0].trim();
            sumber = pecah[1]?.trim() ?? "";
        }

        let html = `<div>${isi}</div>`;

        if (sumber !== "") {
            html += `
                <hr>
                <p class="mt-1 text-muted">
                    <strong>Sumber:</strong> ${sumber}
                </p>`;
        }

        document.getElementById('modalIsi').innerHTML = html;

        new bootstrap.Modal(document.getElementById('beritaModal')).show();
    });
});
</script>