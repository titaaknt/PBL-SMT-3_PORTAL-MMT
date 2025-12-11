<?php 
include 'includes/header.php';

// PAGINATION
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

    <!-- FILTER: All | Karya | Prestasi -->
    <div class="d-flex justify-content-center mb-4">
      <div class="btn-group" role="group" aria-label="Filter jenis karya">
        <button type="button" class="btn btn-outline-primary btn-sm type-filter active" data-type="all">All</button>
        <button type="button" class="btn btn-outline-primary btn-sm type-filter" data-type="karya">Karya</button>
        <button type="button" class="btn btn-outline-primary btn-sm type-filter" data-type="prestasi">Prestasi</button>
      </div>
    </div>

    <div class="row">
    <?php
    if (pg_num_rows($query) == 0) {
        echo '<p class="text-muted text-center">Belum ada karya yang ditambahkan.</p>';
    } else {
        // kata-kata kunci untuk anggap prestasi
        $prestasi_keywords = ['prestasi','juara','award','kompetisi','lomba','penghargaan','winner','finalis'];

        while ($data = pg_fetch_assoc($query)) {

            $sumber = "";
            $deskripsi = $data['deskripsi'];

            if (stripos($deskripsi, "#sumber:") !== false) {
                $parts = preg_split("/#sumber *:/i", $deskripsi);
                $deskripsi = trim($parts[0]);
                $sumber = trim($parts[1] ?? '');
            }

            // tentukan jenis berdasarkan kolom kategori 
            $kategori_lower = strtolower($data['kategori'] ?? '');
            $isPrestasi = false;
            foreach ($prestasi_keywords as $kw) {
                if ($kw !== '' && strpos($kategori_lower, $kw) !== false) {
                    $isPrestasi = true;
                    break;
                }
            }
            // jika kategori tidak mengandung kata kunci prestasi, coba cek deskripsi/judul juga (opsional)
            if (!$isPrestasi) {
                $judul_lower = strtolower($data['judul'] ?? '');
                foreach ($prestasi_keywords as $kw) {
                    if ($kw !== '' && strpos($judul_lower, $kw) !== false) {
                        $isPrestasi = true;
                        break;
                    }
                }
            }

            $type = $isPrestasi ? 'prestasi' : 'karya';

            // encode deskripsi ke HTML entities agar aman di data-attribute
            $desc_for_attr = htmlspecialchars($deskripsi, ENT_QUOTES);
            $sumber_for_attr = htmlspecialchars($sumber, ENT_QUOTES);
            $judul_for_attr = htmlspecialchars($data['judul'], ENT_QUOTES);
            $gambar_for_attr = htmlspecialchars($data['gambar'], ENT_QUOTES);
            $tanggal_display = date('d M Y', strtotime($data['tanggal']));
            $preview_short = htmlspecialchars(mb_substr($deskripsi, 0, 80));
            $img_src = 'assets/img/' . htmlspecialchars($data['gambar']);
            $judul_print = htmlspecialchars($data['judul']);
            $kategori_print = htmlspecialchars($data['kategori']);
            $tanggal_print = date('d M Y', strtotime($data['tanggal']));

            echo '
            <div class="col-md-4 col-sm-6 mb-4 karya-col" data-type="'.htmlspecialchars($type).'">
                <div class="card h-100 shadow-sm border-0 karya-card karya-item"
                    style="cursor:pointer"
                    data-judul="'.$judul_for_attr.'"
                    data-gambar="'.$gambar_for_attr.'"
                    data-deskripsi="'.$desc_for_attr.'"
                    data-sumber="'.$sumber_for_attr.'"
                    data-tanggal="'.$tanggal_display.'">

                    <img src="'.$img_src.'" 
                         class="card-img-top"
                         style="height:220px;object-fit:cover;border-radius:10px 10px 0 0;">

                    <div class="card-body">
                        <h5 class="card-title text-primary">'.$judul_print.'</h5>
                        <p class="text-muted">'. $preview_short .'...</p>
                        <small class="text-muted">Tanggal: '.$tanggal_print.'</small>
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

      <button class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

      <img id="karyaImg" class="w-100 rounded-top" style="max-height:360px;object-fit:cover;">

      <div class="modal-body">
        <h4 id="karyaJudul" class="text-primary fw-bold mb-2"></h4>
        <div id="karyaDeskripsi" class="text-muted mb-2 karya-deskripsi"></div>
        <p id="karyaSumber" class="text-secondary fw-semibold"></p>
        <small id="karyaTanggal" class="text-muted"></small>
      </div>

    </div>
  </div>
</div>

<style>
.karya-card {
    border: none !important;
    background: #fff !important;
    border-radius: 16px !important;
    overflow: hidden;

    /* Soft shadow premium */
    box-shadow:
        0 8px 12px rgba(0, 0, 0, 0.04),
        0 20px 40px rgba(0, 0, 0, 0.07) !important;

    transition: transform 0.25s ease, box-shadow 0.25s ease;
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

/* filter button active */
.type-filter.active {
  background-color: #0b7bff;
  color: #fff;
  border-color: #0b7bff;
}

/* style link di modal/deskripsi */
#karyaDeskripsi a {
  color: #0d6efd;
  text-decoration: underline;
}

/* fix untuk teks super panjang di modal karya */
.karya-deskripsi {
    white-space: normal !important;
    overflow-wrap: break-word !important;
    word-break: break-word !important;
    max-width: 100%;
}

#karyaJudul {
    white-space: normal !important;
    overflow-wrap: break-word !important;
    word-break: break-word !important;
    max-width: 100%;
}


</style>

<script>
// helper: decode HTML entities from data-attribute
function decodeHtmlEntities(encoded) {
  const txt = document.createElement('textarea');
  txt.innerHTML = encoded;
  return txt.value;
}

// helper: autolink URLs (http(s):// or www.)
function autolink(text) {
  if (!text) return text;
  const urlRegex = /(\bhttps?:\/\/[^\s<]+|\bwww\.[^\s<]+)/gi;
  return text.replace(urlRegex, function(url) {
    let href = url;
    if (!href.match(/^https?:\/\//i)) {
      href = 'https://' + href;
    }
    const escHref = href.replace(/"/g, '%22');
    const escText = url.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    return '<a href="' + escHref + '" target="_blank" rel="noopener noreferrer">' + escText + '</a>';
  });
}

document.querySelectorAll('.karya-item').forEach(card=>{
    card.addEventListener('click', ()=>{

        // --- Deskripsi (decode + autolink) ---
        const rawDesc = card.getAttribute('data-deskripsi') || '';
        const decodedDesc = decodeHtmlEntities(rawDesc);
        const withLinks = autolink(decodedDesc);

        // --- Judul & tanggal & sumber ---
        document.getElementById('karyaJudul').innerText = decodeHtmlEntities(card.getAttribute('data-judul') || '');
        document.getElementById('karyaTanggal').innerText = "Tanggal: " + (card.getAttribute('data-tanggal') || '');

        const sumberRaw = decodeHtmlEntities(card.getAttribute('data-sumber') || '');
        if (sumberRaw.trim() !== "") {
            document.getElementById('karyaSumber').innerHTML = "<strong>Sumber:</strong> " + autolink(sumberRaw);
        } else {
            document.getElementById('karyaSumber').innerHTML = "";
        }

        // --- Gambar: pastikan path benar, sembunyikan bila error ---
        const imgEl = document.getElementById('karyaImg');
        let imgVal = card.getAttribute('data-gambar') || '';

        // jika berasal hanya dari filename tanpa path, tambahkan prefix
        if (imgVal && !imgVal.match(/^(https?:\/\/|\/|assets\/)/i)) {
          imgVal = 'assets/img/' + imgVal;
        }

        // jika kosong => sembunyikan
        if (!imgVal) {
          imgEl.style.display = 'none';
          imgEl.removeAttribute('src');
        } else {
          imgEl.style.display = ''; // show
          imgEl.src = imgVal;
        }

        // jika gagal load, sembunyikan gambar supaya tidak muncul ikon rusak
        imgEl.onerror = function() {
          imgEl.style.display = 'none';
          imgEl.removeAttribute('src');
        };
        imgEl.onload = function() {
          imgEl.style.display = ''; // tampilkan bila berhasil
        };

        // set deskripsi (pakai innerHTML biar link clickable), jaga newline
        document.getElementById('karyaDeskripsi').innerHTML = withLinks.replace(/\n/g, '<br>');

        // tampilkan modal
        new bootstrap.Modal(document.getElementById('karyaModal')).show();
    });
});

// =========================
// FILTER (client-side) - tetap seperti sebelumnya
// =========================
document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.type-filter');
  const cols = document.querySelectorAll('.karya-col');

  function setActive(btn) {
    buttons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  }

  function filter(type) {
    cols.forEach(c => {
      const t = c.dataset.type || 'karya';
      if (type === 'all' || t === type) {
        c.style.display = '';
      } else {
        c.style.display = 'none';
      }
    });
    window.scrollTo({ top: document.querySelector('section.container-lg').offsetTop - 20, behavior: 'smooth' });
  }

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      setActive(btn);
      filter(btn.dataset.type);
    });
  });
});
</script>