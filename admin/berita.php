<?php
include '../includes/config.php';
include '../includes/auth.php';

$mode = "";

/* ========================
   MODE TAMBAH
======================== */
if (isset($_GET['aksi']) && $_GET['aksi'] == "tambah") {
    $mode = "tambah";
}

/* ========================
   MODE EDIT
======================== */
$editData = null;
if (isset($_GET['edit'])) {
    $mode = "edit";
    $id_edit = $_GET['edit'];
    $q = pg_query($conn, "SELECT * FROM berita WHERE id=$id_edit");
    $editData = pg_fetch_assoc($q);
}

/* ========================
   SIMPAN TAMBAH
======================== */
if (isset($_POST['simpan_tambah'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = $_POST['tanggal'];

    $gambar = $_FILES['gambar']['name'];
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);

    pg_query($conn, "
        INSERT INTO berita (judul, isi, gambar, tanggal)
        VALUES ('$judul', '$isi', '$gambar', '$tanggal')
    ");

    header("Location: berita.php");
    exit;
}

/* ========================
   SIMPAN EDIT
======================== */
if (isset($_POST['simpan_edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = $_POST['tanggal'];
    $gambar_lama = $_POST['gambar_lama'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
    } else {
        $gambar = $gambar_lama;
    }

    pg_query($conn, "
        UPDATE berita SET
            judul='$judul',
            isi='$isi',
            gambar='$gambar',
            tanggal='$tanggal'
        WHERE id=$id
    ");

    header("Location: berita.php");
    exit;
}

/* ========================
   HAPUS DATA
======================== */
if (isset($_GET['hapus'])) {
    pg_query($conn, "DELETE FROM berita WHERE id={$_GET['hapus']}");
    header("Location: berita.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Berita | Portal MMT</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body { background:#f4f7fb; font-family:'Poppins',sans-serif; }

  .navbar {
      background:#003c8f;
      padding:15px 30px;
  }
  .navbar-brand { color:white !important; font-weight:600; }

  .back-icon {
      font-size:27px;
      color:white;
      margin-right:12px;
      text-decoration:none;
  }

  .container-box {
      background:white;
      padding:25px;
      border-radius:15px;
      box-shadow:0 4px 15px rgba(0,0,0,0.08);
      margin-top:20px;
  }

  .action-btns { display:flex; gap:10px; }
  .col-isi { max-width:260px; font-size:13px; }
  .preview-img { width:180px; border-radius:10px; }

  /* Kolom judul: biar turun baris & tidak melebar */
td.col-judul {
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    max-width: 180px; /* boleh kamu adjust */
}

/* Kolom deskripsi (isi berita) */
td.col-isi {
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    max-width: 260px; /* biar tetap kecil */
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <a href="dashboard.php" class="back-icon"><i class="bi bi-arrow-left-circle"></i></a>
        <span class="navbar-brand">Kelola Berita</span>
    </div>
    <a href="../logout.php" class="btn btn-warning btn-sm">Logout</a>
</nav>

<div class="container container-box">

    <div class="d-flex justify-content-between mb-3">
        <h4 class="text-primary fw-bold">Kelola Berita</h4>
        <a href="berita.php?aksi=tambah" class="btn btn-primary">+ Tambah Baru</a>
    </div>

    <!-- FORM TAMBAH -->
    <?php if ($mode == "tambah"): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input name="judul" class="form-control mb-2"
               placeholder="Judul berita" required>

        <input name="tanggal" type="date" class="form-control mb-2" required>

        <textarea name="isi" class="form-control mb-2" rows="4"
                  placeholder="Isi berita..." required></textarea>

        <input type="file" name="gambar" class="form-control mb-3" required>

        <button name="simpan_tambah" class="btn btn-success">Simpan</button>
        <a href="berita.php" class="btn btn-secondary">Batal</a>

    </form>
    <?php endif; ?>

    <!-- FORM EDIT -->
    <?php if ($mode == "edit"): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <input type="hidden" name="gambar_lama" value="<?= $editData['gambar'] ?>">

        <input name="judul" class="form-control mb-2"
               value="<?= htmlspecialchars($editData['judul']) ?>" required>

        <input name="tanggal" type="date" class="form-control mb-2"
               value="<?= date('Y-m-d', strtotime($editData['tanggal'])) ?>" required>

        <textarea name="isi" class="form-control mb-2" rows="4"
        ><?= htmlspecialchars($editData['isi']) ?></textarea>

        <input type="file" name="gambar" class="form-control mb-2">

        <p class="small text-secondary mb-1">Gambar saat ini:</p>
        <img src="../assets/img/<?= $editData['gambar'] ?>" class="preview-img mb-3">

        <br>

        <button name="simpan_edit" class="btn btn-success">Simpan Perubahan</button>
        <a href="berita.php" class="btn btn-secondary">Batal</a>

    </form>
    <?php endif; ?>

    <!-- TABLE DATA -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th class="col-isi">Isi Berita</th>
                <th width="120">Tanggal</th>
                <th width="120">Gambar</th>
                <th width="150" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $no = 1;
        $q = pg_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
        while ($d = pg_fetch_assoc($q)):
            $sumber = "#sumber: Jurusan Teknologi Informasi POLINEMA";
        ?>
            <tr>
                <td><?= $no++ ?></td>

                <td class="col-judul"><?= htmlspecialchars($d['judul']) ?></td>

                <td class="col-isi">
                    <?= substr(strip_tags($d['isi']), 0, 80) ?>...
                    <br><span class="text-muted small"><?= $sumber ?></span>
                </td>

                <td><?= date("d M Y", strtotime($d['tanggal'])) ?></td>

                <td><img src="../assets/img/<?= $d['gambar'] ?>" width="90" class="rounded"></td>

                <td class="text-center">
                    <div class="action-btns">
                        <a href="berita.php?edit=<?= $d['id'] ?>" class="btn btn-warning btn-sm px-3">Edit</a>
                        <a href="berita.php?hapus=<?= $d['id'] ?>"
                           onclick="return confirm('Hapus berita ini?')"
                           class="btn btn-danger btn-sm px-3"
                        >Hapus</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>
</body>
</html>