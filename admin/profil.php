<?php
include '../includes/config.php';
include '../includes/auth.php';

// Ambil data profil
$q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
$data = pg_fetch_assoc($q);

// === CRUD KONTAK ===
if (isset($_POST['tambah_kontak'])) {
    $icon = pg_escape_string($conn, $_POST['icon']);
    $link = pg_escape_string($conn, $_POST['link']);
    $ket  = pg_escape_string($conn, $_POST['keterangan']);
    pg_query($conn, "INSERT INTO kontak_detail (icon, link, keterangan) VALUES ('$icon', '$link', '$ket')");
}

if (isset($_GET['hapus_kontak'])) {
    $id = $_GET['hapus_kontak'];
    pg_query($conn, "DELETE FROM kontak_detail WHERE id=$id");
}

$editKontak = null;
if (isset($_GET['edit_kontak'])) {
    $id = $_GET['edit_kontak'];
    $r = pg_query($conn, "SELECT * FROM kontak_detail WHERE id=$id");
    $editKontak = pg_fetch_assoc($r);
}

if (isset($_POST['update_kontak'])) {
    $id = $_POST['id'];
    $icon = pg_escape_string($conn, $_POST['icon']);
    $link = pg_escape_string($conn, $_POST['link']);
    $ket = pg_escape_string($conn, $_POST['keterangan']);
    pg_query($conn, "UPDATE kontak_detail SET icon='$icon', link='$link', keterangan='$ket' WHERE id=$id");
}

// === UPDATE PROFIL ===
if (isset($_POST['simpan_profil'])) {
    $visi = pg_escape_string($conn, $_POST['visi']);
    $misi = pg_escape_string($conn, $_POST['misi']);
    $struktur = pg_escape_string($conn, $_POST['struktur']);
    $struktur_img = $data['struktur_img'];

    if (!empty($_FILES['struktur_img']['name'])) {
        $namaFile = $_FILES['struktur_img']['name'];
        $tmp      = $_FILES['struktur_img']['tmp_name'];
        $tujuan   = "../assets/img/" . $namaFile;
        if (move_uploaded_file($tmp, $tujuan)) {
            $struktur_img = $namaFile;
        }
    }

    pg_query($conn, "UPDATE profil 
        SET visi='$visi', misi='$misi', struktur='$struktur', struktur_img='$struktur_img'
        WHERE id=1");

    $msg = "✔ Profil berhasil diperbarui!";
    $q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
    $data = pg_fetch_assoc($q);
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
    body {
      background: #f4f7fb;
      font-family: 'Poppins', sans-serif;
    }

    /* NAVBAR SAMA PERSIS DENGAN BERITA / KARYA */
    .navbar {
      background: #003c8f;
      padding: 15px 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .navbar-brand {
      font-weight: 600;
      color: white !important;
    }

    .btn-logout {
      background-color: #ffc107;
      color: #003c8f;
      font-weight: 600;
    }

    /* ICON BACK SAMA 100% */
    .back-icon {
      font-size: 28px;
      color: white !important;
      margin-right: 12px;
      text-decoration: none;
    }

    .back-icon:hover {
      opacity: 0.8;
    }

    .container-box {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
      margin-top: 25px;
    }

    img.preview {
      max-width: 100%;
      border-radius: 10px;
      border: 1px solid #ddd;
      margin-top: 6px;
    }

    .container-box {
    margin-top: 15 !important;
    padding-top: 15px;
}

  </style>
</head>

<body>

<!-- NAVBAR (SAMA PERSIS SEPERTI BERITA/KARYA) -->
<nav class="navbar">
    <div class="d-flex align-items-center">
        <a href="dashboard.php" class="back-icon">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
        <span class="navbar-brand">Kelola Profil</span>
    </div>
    <a href="../logout.php" class="btn btn-logout btn-sm">Logout</a>
</nav>



<div class="container container-box">

 <!-- HEADER DENGAN ICON BACK -->
  <div class="mb-3">
    <a href="dashboard.php" class="back-icon">
      <i class="bi bi-arrow-left-circle"></i>
    </a>
    <h4 class="m-0 fw-bold text-primary">Kelola Profil Laboratorium</h4>
  </div>

  <?php if (isset($msg)) { ?>
    <div class="alert alert-success"><?= $msg ?></div>
  <?php } ?>

  <form method="post" enctype="multipart/form-data">

    <div class="mb-3">
      <label class="fw-semibold">Visi</label>
      <textarea name="visi" class="form-control" rows="3"><?= htmlspecialchars($data['visi']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Misi</label>
      <textarea name="misi" class="form-control" rows="4"><?= htmlspecialchars($data['misi']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Struktur Organisasi</label>
      <textarea name="struktur" class="form-control" rows="3"><?= htmlspecialchars($data['struktur']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Upload Struktur (Gambar)</label>
      <input type="file" name="struktur_img" class="form-control">
      <?php if ($data['struktur_img']): ?>
        <img src="../assets/img/<?= $data['struktur_img'] ?>" class="preview">
      <?php endif; ?>
    </div>

    <hr class="my-4">
    <h5 class="fw-semibold">Kontak Laboratorium</h5>

    <div class="row g-2 mb-3">
      <div class="col-md-3">
        <input name="icon" class="form-control" placeholder="bi bi-envelope"
               value="<?= htmlspecialchars($editKontak['icon'] ?? '') ?>">
      </div>

      <div class="col-md-4">
        <input name="link" class="form-control" placeholder="mailto:..." 
               value="<?= htmlspecialchars($editKontak['link'] ?? '') ?>">
      </div>

      <div class="col-md-3">
        <input name="keterangan" class="form-control" placeholder="Email"
               value="<?= htmlspecialchars($editKontak['keterangan'] ?? '') ?>">
      </div>

      <div class="col-md-2">
        <?php if ($editKontak): ?>
          <input type="hidden" name="id" value="<?= $editKontak['id'] ?>">
          <button name="update_kontak" class="btn btn-success w-100">Update</button>
        <?php else: ?>
          <button name="tambah_kontak" class="btn btn-primary w-100">Tambah</button>
        <?php endif; ?>
      </div>
    </div>

    <table class="table table-bordered table-striped">
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
        <?php
        $no = 1;
        $qc = pg_query($conn, "SELECT * FROM kontak_detail ORDER BY id ASC");
        while ($k = pg_fetch_assoc($qc)) {
          echo "
          <tr>
            <td>{$no}</td>
            <td><i class='{$k['icon']}'></i> {$k['icon']}</td>
            <td>{$k['link']}</td>
            <td>{$k['keterangan']}</td>
            <td>
              <a href='?edit_kontak={$k['id']}' class='btn btn-warning btn-sm'>Edit</a>
              <a href='?hapus_kontak={$k['id']}' onclick='return confirm(\"Yakin hapus?\")'
                 class='btn btn-danger btn-sm'>Hapus</a>
            </td>
          </tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>

    <button name="simpan_profil" class="btn btn-primary mt-3">Simpan Profil</button>

  </form>

</div>
</body>
</html>