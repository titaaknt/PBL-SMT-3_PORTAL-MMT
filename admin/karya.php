<?php
include '../includes/config.php';
include '../includes/auth.php';

$mode = "";

// tombol tambah
if (isset($_GET['aksi']) && $_GET['aksi'] == "tambah") {
    $mode = "tambah";
}

// ambil data edit
$editData = null;
if (isset($_GET['edit'])) {
    $mode = "edit";
    $id_edit = (int)$_GET['edit'];
    $q = pg_query($conn, "SELECT * FROM karya WHERE id=$id_edit");
    $editData = pg_fetch_assoc($q);
}

/* ====================
   SIMPAN TAMBAH
==================== */
if (isset($_POST['simpan_tambah'])) {
    // escape input agar tanda kutip / newline aman
    $judul     = pg_escape_string($conn, $_POST['judul'] ?? '');
    $kategori  = pg_escape_string($conn, $_POST['kategori'] ?? '');
    $tanggal   = pg_escape_string($conn, $_POST['tanggal'] ?? '');
    $deskripsi = pg_escape_string($conn, $_POST['deskripsi'] ?? '');

    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_raw = basename($_FILES['gambar']['name']);
        $gambar = pg_escape_string($conn, $gambar_raw);
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
    }

    pg_query($conn, "INSERT INTO karya (judul, kategori, tanggal, deskripsi, gambar)
                     VALUES ('$judul', '$kategori', '$tanggal', '$deskripsi', '$gambar')");

    header("Location: karya.php");
    exit;
}

/* ====================
   SIMPAN EDIT
==================== */
if (isset($_POST['simpan_edit'])) {
    $id = (int)$_POST['id'];
    $judul     = pg_escape_string($conn, $_POST['judul'] ?? '');
    $kategori  = pg_escape_string($conn, $_POST['kategori'] ?? '');
    $tanggal   = pg_escape_string($conn, $_POST['tanggal'] ?? '');
    $deskripsi = pg_escape_string($conn, $_POST['deskripsi'] ?? '');
    $gambar_lama = pg_escape_string($conn, $_POST['gambar_lama'] ?? '');

    if (!empty($_FILES['gambar']['name'])) {
        $gambar_raw = basename($_FILES['gambar']['name']);
        $gambar = pg_escape_string($conn, $gambar_raw);
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);
    } else {
        $gambar = $gambar_lama;
    }

    pg_query($conn, "UPDATE karya SET
                        judul='$judul',
                        kategori='$kategori',
                        tanggal='$tanggal',
                        deskripsi='$deskripsi',
                        gambar='$gambar'
                     WHERE id=$id");

    header("Location: karya.php");
    exit;
}

/* ====================
   HAPUS
==================== */
if (isset($_GET['hapus'])) {
    $idh = (int)$_GET['hapus'];
    pg_query($conn, "DELETE FROM karya WHERE id=$idh");
    header("Location: karya.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Karya | Portal MMT</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body { background:#f4f7fb; font-family:'Poppins',sans-serif; }
  .navbar { background:#003c8f; padding:15px 30px; }
  .navbar-brand { color:white !important; font-weight:600; }
  .back-icon{ font-size:27px; color:white; margin-right:12px; text-decoration:none; }
  .container-box{ background:white; padding:25px; border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.08); margin-top:20px; }
  .action-btns{ display:flex; gap:10px; }
  .col-desc{
      max-width:260px;
      font-size:13px;
      white-space: normal;        /* allow wrapping */
      word-break: break-word;     /* break long words/URLs */
      overflow-wrap: break-word;  /* extra fallback */
  }
  /* Make table cell vertical align top so long text doesn't misalign image/buttons */
  table.table td, table.table th { vertical-align: top; }
</style>
</head>

<body>

<nav class="navbar">
    <div class="d-flex align-items-center">
        <a href="dashboard.php" class="back-icon"><i class="bi bi-arrow-left-circle"></i></a>
        <span class="navbar-brand">Kelola Karya</span>
    </div>
    <a href="../logout.php" class="btn btn-warning btn-sm">Logout</a>
</nav>

<div class="container container-box">

    <div class="d-flex justify-content-between mb-3">
        <h4 class="text-primary fw-bold">Kelola Karya & Prestasi</h4>
        <a href="karya.php?aksi=tambah" class="btn btn-primary">+ Tambah Baru</a>
    </div>

    <!-- FORM TAMBAH -->
    <?php if ($mode == "tambah"): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input name="judul" class="form-control mb-2" placeholder="Judul karya" required>

        <!-- Kategori sekarang pilihan (Karya / Prestasi) -->
        <select name="kategori" class="form-control mb-2" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Karya">Karya</option>
            <option value="Prestasi">Prestasi</option>
        </select>

        <input name="tanggal" type="date" class="form-control mb-2" required>
        <textarea name="deskripsi" class="form-control mb-2" rows="3" placeholder="Deskripsi..." required></textarea>
        <input type="file" name="gambar" class="form-control mb-3" required>

        <button name="simpan_tambah" class="btn btn-success">Simpan</button>
        <a href="karya.php" class="btn btn-secondary">Batal</a>

    </form>
    <?php endif; ?>

    <!-- FORM EDIT -->
    <?php if ($mode == "edit"): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input type="hidden" name="id" value="<?= (int)$editData['id'] ?>">
        <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($editData['gambar']) ?>">

        <input name="judul" class="form-control mb-2"
            value="<?= htmlspecialchars($editData['judul']) ?>" required>

        <select name="kategori" class="form-control mb-2" required>
            <option value="Karya" <?= ($editData['kategori'] == 'Karya' ? 'selected' : '') ?>>Karya</option>
            <option value="Prestasi" <?= ($editData['kategori'] == 'Prestasi' ? 'selected' : '') ?>>Prestasi</option>
        </select>

        <input name="tanggal" type="date" class="form-control mb-2"
            value="<?= date('Y-m-d', strtotime($editData['tanggal'])) ?>" required>

        <textarea name="deskripsi" class="form-control mb-2" rows="3"><?= htmlspecialchars($editData['deskripsi']) ?></textarea>

        <input type="file" name="gambar" class="form-control mb-2">

        <p class="small text-secondary mb-1">Gambar saat ini:</p>
        <img src="../assets/img/<?= htmlspecialchars($editData['gambar']) ?>" width="120" class="rounded mb-2">

        <br>
        <button name="simpan_edit" class="btn btn-success">Simpan Perubahan</button>
        <a href="karya.php" class="btn btn-secondary">Batal</a>

    </form>
    <?php endif; ?>

    <!-- TABLE -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th class="col-desc">Deskripsi</th>
                <th width="120">Tanggal</th>
                <th width="130">Gambar</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $no = 1;
        // order by id DESC supaya entri baru muncul di atas
        $q = pg_query($conn, "SELECT * FROM karya ORDER BY id DESC");
        while ($d = pg_fetch_assoc($q)):

            // AMAN: tampilkan ringkasan tanpa HTML, dengan htmlspecialchars dan memotong multibyte
            $plain = strip_tags($d['deskripsi']);
            // use mb_substr to handle UTF-8
            $desc_short = htmlspecialchars(mb_substr($plain, 0, 80, 'UTF-8')) . (mb_strlen($plain, 'UTF-8') > 80 ? "..." : "");

            // jika ingin menampilkan link aktif di modal/detail nanti, gunakan nl2br + htmlspecialchars
        ?>

        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($d['judul']) ?></td>
            <td><?= htmlspecialchars($d['kategori']) ?></td>

            <td class="col-desc"><?= $desc_short ?></td>

            <td><?= date("d M Y", strtotime($d['tanggal'])) ?></td>

            <td><img src="../assets/img/<?= htmlspecialchars($d['gambar']) ?>" width="90" class="rounded"></td>

            <td class="text-center">
                <div class="action-btns">
                    <a href="karya.php?edit=<?= (int)$d['id'] ?>" class="btn btn-warning btn-sm px-3">Edit</a>
                    <a href="karya.php?hapus=<?= (int)$d['id'] ?>" onclick="return confirm('Hapus karya ini?')" class="btn btn-danger btn-sm px-3">Hapus</a>
                </div>
            </td>
        </tr>

        <?php endwhile; ?>

        </tbody>
    </table>

</div>
</body>
</html>