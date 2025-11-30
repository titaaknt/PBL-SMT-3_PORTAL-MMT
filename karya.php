<?php 
include 'includes/header.php';

// =========================
// PAGINATION
// =========================
$limit = 6; 
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM karya"), 0, 0);
$totalPage = ceil($total / $limit);

$query = pg_query($conn, "
    SELECT * FROM karya 
    ORDER BY tanggal DESC 
    LIMIT $limit OFFSET $offset
");
?>

<!-- WRAPPER UNTUK FOOTER TETAP DI BAWAH -->
<div class="page-wrapper d-flex flex-column" style="min-height:100vh;">

<main class="flex-fill">

<section class="container-lg px-4 my-5">
    <h3 class="text-primary fw-bold mb-4 text-center">Karya & Prestasi</h3>

    <div class="row">
    <?php
    if (pg_num_rows($query) == 0) {
        echo '<p class="text-muted text-center">Belum ada karya yang ditambahkan.</p>';
    } else {
        while ($data = pg_fetch_assoc($query)) {

            $sumber = "";
            $deskripsi = $data['deskripsi'];

            if (stripos($deskripsi, "#sumber:") !== false) {
                $parts = preg_split("/#sumber *:/i", $deskripsi);
                $deskripsi = trim($parts[0]);
                $sumber = trim($parts[1]);
            }

            echo '
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 karya-card karya-item"
                    style="cursor:pointer"
                    data-judul="'.htmlspecialchars($data['judul']).'"
                    data-gambar="assets/img/'.$data['gambar'].'"
                    data-deskripsi="'.htmlspecialchars($deskripsi).'"
                    data-sumber="'.htmlspecialchars($sumber).'"
                    data-tanggal="'.date('d M Y', strtotime($data['tanggal'])).'">

                    <img src="assets/img/'.$data['gambar'].'" 
                         class="card-img-top"
                         style="height:220px;object-fit:cover;border-radius:10px 10px 0 0;">

                    <div class="card-body">
                        <h5 class="card-title text-primary">'.$data['judul'].'</h5>
                        <p class="text-muted">'.substr($deskripsi, 0, 80).'...</p>
                        <small class="text-muted">Tanggal: '.date('d M Y', strtotime($data['tanggal'])).'</small>
                    </div>
                </div>
            </div>';
        }
    }
    ?>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center mt-4">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?>">«</a>
                </li>
            <?php endif; ?>

            <?php for ($i=1; $i <= $totalPage; $i++): ?>
                <li class="page-item <?= ($i==$page?'active':'') ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPage): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?>">»</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

</section>

</main>

</div> <!-- END WRAPPER -->

<?php include 'includes/footer.php'; ?>


<!-- MODAL DETAIL -->
<div class="modal fade" id="karyaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 position-relative">

      <button class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>

      <img id="karyaImg" class="w-100 rounded-top" style="max-height:360px;object-fit:cover;">

      <div class="modal-body">
        <h4 id="karyaJudul" class="text-primary fw-bold mb-2"></h4>
        <p id="karyaDeskripsi" class="text-muted"></p>
        <p id="karyaSumber" class="text-secondary fw-semibold"></p>
        <small id="karyaTanggal" class="text-muted"></small>
      </div>

    </div>
  </div>
</div>

<style>
.karya-card {
    transition: .3s;
    border-radius: 10px;
}
.karya-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* FIX FOOTER NEMPEL DI BAWAH */
html, body {
    height: 100%;
}
.page-wrapper {
    display: flex;
    flex-direction: column;
}
main.flex-fill {
    flex: 1 0 auto;
}
</style>

<script>
document.querySelectorAll('.karya-item').forEach(card=>{
    card.addEventListener('click', ()=>{

        document.getElementById('karyaImg').src = card.dataset.gambar;
        document.getElementById('karyaJudul').innerText = card.dataset.judul;
        document.getElementById('karyaDeskripsi').innerText = card.dataset.deskripsi;

        if(card.dataset.sumber.trim()!=""){
            document.getElementById('karyaSumber').innerHTML="<b>Sumber:</b> "+card.dataset.sumber;
        } else {
            document.getElementById('karyaSumber').innerHTML="";
        }

        document.getElementById('karyaTanggal').innerText = "Tanggal: " + card.dataset.tanggal;

        new bootstrap.Modal(document.getElementById('karyaModal')).show();
    });
});
</script>