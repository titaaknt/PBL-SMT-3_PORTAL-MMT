<?php
include '../includes/config.php';
include '../includes/auth.php';

// Ambil data profil
$q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
$data = pg_fetch_assoc($q);

// Ambil kontak existing (untuk ditampilkan awal di client)
$qc = pg_query($conn, "SELECT * FROM kontak_detail ORDER BY id ASC");
$contacts = [];
while ($r = pg_fetch_assoc($qc)) {
    $contacts[] = $r;
}

// Proses simpan profil (profil + replace kontak)
$msg = "";
if (isset($_POST['simpan_profil'])) {
    // profil fields
    $visi = pg_escape_string($conn, $_POST['visi'] ?? '');
    $misi = pg_escape_string($conn, $_POST['misi'] ?? '');
    $struktur = pg_escape_string($conn, $_POST['struktur'] ?? '');

    // upload struktur_img jika ada
    $struktur_img = $data['struktur_img'];
    if (!empty($_FILES['struktur_img']['name'])) {
        $namaFile = basename($_FILES['struktur_img']['name']);
        $tmp      = $_FILES['struktur_img']['tmp_name'];
        $tujuan   = "../assets/img/" . $namaFile;
        if (move_uploaded_file($tmp, $tujuan)) {
            $struktur_img = $namaFile;
        }
    }

    // update profil
    pg_query($conn, "UPDATE profil 
        SET visi='{$visi}', misi='{$misi}', struktur='{$struktur}', struktur_img='{$struktur_img}'
        WHERE id=1");

    // proses kontak yang dikirim (JSON)
    $contacts_json = $_POST['contacts_json'] ?? '[]';
    $contacts_arr = json_decode($contacts_json, true);
    if (!is_array($contacts_arr)) $contacts_arr = [];

    pg_query($conn, "BEGIN");
    // Hapus semua kontak dan insert ulang (simple)
    pg_query($conn, "DELETE FROM kontak_detail");
    $ok = true;
    foreach ($contacts_arr as $c) {
        $icon = pg_escape_string($conn, $c['icon'] ?? '');
        $link = pg_escape_string($conn, $c['link'] ?? '');
        $ket  = pg_escape_string($conn, $c['keterangan'] ?? '');
        $res = pg_query($conn, "INSERT INTO kontak_detail (icon, link, keterangan) VALUES ('$icon', '$link', '$ket')");
        if (!$res) $ok = false;
    }
    if ($ok) {
        pg_query($conn, "COMMIT");
        $msg = "✔ Profil dan kontak berhasil disimpan!";
    } else {
        pg_query($conn, "ROLLBACK");
        $msg = "✖ Gagal menyimpan kontak. Coba lagi.";
    }

    // refresh data
    $q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
    $data = pg_fetch_assoc($q);
    $qc = pg_query($conn, "SELECT * FROM kontak_detail ORDER BY id ASC");
    $contacts = [];
    while ($r = pg_fetch_assoc($qc)) $contacts[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Profil | Portal MMT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f4f7fb; font-family: 'Poppins', sans-serif; }
    .navbar { background:#003c8f; padding:15px 30px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
    .navbar-brand { color:white !important; font-weight:600; }
    .back-icon{ font-size:28px; color:white; margin-right:12px; text-decoration:none; }
    .container-box{ background:white; padding:30px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.06); margin-top:20px; }
    img.preview {
        max-width:100%;
        border-radius:8px;
        margin:8px auto;
        display:block;
    }
    .small-muted { font-size:0.9rem; color:#666; }
    /* keep layout same as before */
    .icon-preview { font-size: 1.35rem; display:inline-flex; align-items:center; gap:8px; }
  </style>
</head>
<body>
<nav class="navbar d-flex justify-content-between align-items-center">
  <div class="d-flex align-items-center">
    <a href="dashboard.php" class="back-icon"><i class="bi bi-arrow-left-circle"></i></a>
    <span class="navbar-brand">Kelola Profil</span>
  </div>
  <a href="../logout.php" class="btn btn-warning btn-sm">Logout</a>
</nav>

<div class="container container-box">
  <!-- HANYA JUDUL DI SINI (back sudah di navbar) -->
  <h4 class="m-0 fw-bold text-primary mb-3">Kelola Profil Laboratorium</h4>

  <?php if ($msg): ?>
    <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" id="profilForm">
    <div class="mb-3">
      <label class="fw-semibold">Visi</label>
      <textarea name="visi" class="form-control" rows="3"><?= htmlspecialchars($data['visi'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Misi</label>
      <textarea name="misi" class="form-control" rows="4"><?= htmlspecialchars($data['misi'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Struktur Organisasi (Deskripsi)</label>
      <textarea name="struktur" class="form-control" rows="3"><?= htmlspecialchars($data['struktur'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Upload Gambar Struktur</label>
      <input type="file" name="struktur_img" class="form-control">
      <?php if (!empty($data['struktur_img'])): ?>
        <img src="../assets/img/<?= htmlspecialchars($data['struktur_img']) ?>" class="preview">
      <?php endif; ?>
    </div>

    <hr class="my-4">
    <h5 class="fw-semibold">Kontak Laboratorium</h5>

    <!-- kontak input bar (mirip tampilan lama) -->
    <div class="row g-2 mb-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Icon</label>
        <!-- dropdown icon pilihan -->
        <select id="contactIcon" class="form-control">
          <option value="">-- Pilih Icon --</option>
          <option value="bi bi-envelope">Email</option>
          <option value="bi bi-telephone">Telepon</option>
          <option value="bi bi-globe">Website</option>
          <option value="bi bi-geo-alt">Alamat / Maps</option>
          <option value="bi bi-facebook">Facebook</option>
          <option value="bi bi-instagram">Instagram</option>
          <option value="bi bi-youtube">YouTube</option>
          <option value="bi bi-whatsapp">WhatsApp</option>
          <option value="bi bi-linkedin">LinkedIn</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Link</label>
        <input id="contactLink" class="form-control" placeholder="https://...">
      </div>

      <div class="col-md-3">
        <label class="form-label">Keterangan</label>
        <input id="contactKeterangan" class="form-control" placeholder="Email ">
      </div>

      <div class="col-md-2">
        <button type="button" id="btnAddContact" class="btn btn-primary w-100">Tambah</button>
      </div>
    </div>

    <!-- tabel kontak (sementara) -->
    <table class="table table-bordered table-striped" id="contactsTable">
      <thead class="table-primary">
        <tr>
          <th>No</th>
          <th>Icon</th>
          <th>Link</th>
          <th>Keterangan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- diisi oleh JS -->
      </tbody>
    </table>
    <div class="small-muted mb-3">Perubahan kontak belum tersimpan ke database sampai Anda menekan tombol <strong>Simpan Profil</strong>.</div>

    <!-- hidden input untuk mengirim daftar kontak ke server -->
    <input type="hidden" name="contacts_json" id="contacts_json" value="">

    <button name="simpan_profil" class="btn btn-primary mt-2">Simpan Profil</button>
  </form>
</div>

<script>
// initial contacts dari server
let contacts = <?= json_encode($contacts, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>;
const tbody = document.querySelector('#contactsTable tbody');
const iconInput = document.getElementById('contactIcon');
const linkInput = document.getElementById('contactLink');
const ketInput  = document.getElementById('contactKeterangan');
const btnAdd    = document.getElementById('btnAddContact');
const contactsJsonInput = document.getElementById('contacts_json');
const iconPreview = document.getElementById('iconPreview');

let editIndex = -1; // -1 = new

function renderContacts() {
  tbody.innerHTML = '';
  contacts.forEach((c, i) => {
    const tr = document.createElement('tr');
    const iconHtml = c.icon ? `<i class="${escapeHtml(c.icon)}"></i> ${escapeHtml(c.icon)}` : '';
    tr.innerHTML = `
      <td>${i+1}</td>
      <td>${iconHtml}</td>
      <td><a href="${escapeAttr(c.link)}" target="_blank" rel="noopener">${escapeHtml(c.link)}</a></td>
      <td>${escapeHtml(c.keterangan)}</td>
      <td>
        <button class="btn btn-sm btn-warning btn-edit" data-i="${i}">Edit</button>
        <button class="btn btn-sm btn-danger btn-del" data-i="${i}">Hapus</button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // event
  document.querySelectorAll('.btn-edit').forEach(b=>{
    b.addEventListener('click', e=>{
      const i = parseInt(e.currentTarget.dataset.i);
      startEdit(i);
    });
  });
  document.querySelectorAll('.btn-del').forEach(b=>{
    b.addEventListener('click', e=>{
      const i = parseInt(e.currentTarget.dataset.i);
      if (confirm('Hapus kontak ini dari daftar?')) {
        contacts.splice(i,1);
        renderContacts();
      }
    });
  });
}

function startEdit(i) {
  editIndex = i;
  const c = contacts[i];
  iconInput.value = c.icon || '';
  linkInput.value = c.link || '';
  ketInput.value = c.keterangan || '';
  updateIconPreview();
  btnAdd.textContent = 'Simpan';
}

function clearForm() {
  editIndex = -1;
  iconInput.value = '';
  linkInput.value = '';
  ketInput.value = '';
  updateIconPreview();
  btnAdd.textContent = 'Tambah';
}

btnAdd.addEventListener('click', ()=> {
  const icon = iconInput.value.trim();
  const link = linkInput.value.trim();
  const ket  = ketInput.value.trim();

  if (!icon && !link && !ket) {
    alert('Isi minimal satu field sebelum menambah.');
    return;
  }

  const obj = { icon: icon, link: link, keterangan: ket };

  if (editIndex >= 0) {
    contacts[editIndex] = obj;
  } else {
    contacts.unshift(obj); // tampilkan di atas
  }

  renderContacts();
  clearForm();
});

// sebelum submit form, masukkan contacts JSON
document.getElementById('profilForm').addEventListener('submit', function() {
  contactsJsonInput.value = JSON.stringify(contacts);
});

// helper
function escapeHtml(s) {
  if (!s) return '';
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}
function escapeAttr(s) {
  if (!s) return '';
  return s.replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

// ICON preview update
function updateIconPreview() {
  const v = iconInput.value;
  if (!v) {
    iconPreview.innerHTML = '<span class="text-muted">Preview icon akan muncul di sini</span>';
  } else {
    // tampilkan icon dan nama
    iconPreview.innerHTML = `<i class="${escapeHtml(v)}"></i> <small>${escapeHtml(v)}</small>`;
  }
}
iconInput.addEventListener('change', updateIconPreview);

// initial render
renderContacts();
updateIconPreview();
</script>
</body>
</html>