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
    $q = pg_query($conn, "SELECT * FROM galeri WHERE id=$id_edit");
    $editData = pg_fetch_assoc($q);
}

/* ========================
   SIMPAN DATA TAMBAH
======================== */
if (isset($_POST['simpan_tambah'])) {
    $judul = $_POST['judul'];
    $jenis = $_POST['jenis'];
    $kategori_id = $_POST['kategori_id'];

    $file = $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], "../assets/img/" . $file);

    pg_query($conn, "
        INSERT INTO galeri (judul, jenis, kategori_id, file_path, admin_id)
        VALUES ('$judul', '$jenis', $kategori_id, '$file', 1)
    ");

    header("Location: galeri.php");
    exit;
}

/* ========================
   SIMPAN EDIT
======================== */
if (isset($_POST['simpan_edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $jenis = $_POST['jenis'];
    $kategori_id = $_POST['kategori_id'];
    $file_lama = $_POST['file_lama'];

    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], "../assets/img/" . $file);
    } else {
        $file = $file_lama;
    }

    pg_query($conn, "
        UPDATE galeri SET 
            judul='$judul',
            jenis='$jenis',
            kategori_id=$kategori_id,
            file_path='$file'
        WHERE id=$id
    ");

    header("Location: galeri.php");
    exit;
}

/* ========================
   HAPUS DATA
======================== */
if (isset($_GET['hapus'])) {
    pg_query($conn, "DELETE FROM galeri WHERE id={$_GET['hapus']}");
    header("Location: galeri.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Galeri | Portal MMT</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
  body { background:#f4f7fb; font-family:'Poppins',sans-serif; }

  .navbar {
      background:#003c8f;
      padding:15px 30px;
  }
  .navbar-brand { color:white !important; font-weight:600; }

  .back-icon{
      font-size:27px;
      color:white;
      margin-right:12px;
      text-decoration:none;
  }

  .container-box{
      background:white;
      padding:25px;
      border-radius:15px;
      box-shadow:0 4px 15px rgba(0,0,0,0.08);
      margin-top:20px;
  }

  .action-btns{ display:flex; gap:10px; }
  .col-desc{ max-width:260px; font-size:13px; white-space:normal; }
  .preview-file{ width:180px; border-radius:8px; }
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <a href="dashboard.php" class="back-icon"><i class="bi bi-arrow-left-circle"></i></a>
        <span class="navbar-brand">Kelola Galeri</span>
    </div>
    <a href="../logout.php" class="btn btn-warning btn-sm">Logout</a>
</nav>

<div class="container container-box">

    <div class="d-flex justify-content-between mb-3">
        <h4 class="text-primary fw-bold">Kelola Galeri & Dokumentasi</h4>
        <a href="galeri.php?aksi=tambah" class="btn btn-primary">+ Tambah Baru</a>
    </div>

    <!-- FORM TAMBAH -->
    <?php if ($mode == "tambah"): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input name="judul" class="form-control mb-2"
               placeholder="Judul galeri" required>

        <select name="jenis" class="form-control mb-2" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="foto">Foto</option>
            <option value="video">Video</option>
        </select>

        <select name="kategori_id" class="form-control mb-2" required>
            <option value="">-- Pilih Kategori --</option>
            <?php
            $kat = pg_query($conn, "SELECT * FROM kategori_galeri ORDER BY judul ASC");
            while ($k = pg_fetch_assoc($kat)):
            ?>
                <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['judul']) ?></option>
            <?php endwhile; ?>
        </select>

        <input type="file" name="file" class="form-control mb-3" required>

        <button name="simpan_tambah" class="btn btn-success">Simpan</button>
        <a href="galeri.php" class="btn btn-secondary">Batal</a>

    </form>
    <?php endif; ?>

    <!-- FORM EDIT -->
    <?php if ($mode == "edit"): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <input type="hidden" name="file_lama" value="<?= $editData['file_path'] ?>">

        <input name="judul" class="form-control mb-2"
               value="<?= htmlspecialchars($editData['judul']) ?>" required>

        <select name="jenis" class="form-control mb-2" required>
            <option value="foto" <?= $editData['jenis']=='foto'?'selected':'' ?>>Foto</option>
            <option value="video" <?= $editData['jenis']=='video'?'selected':'' ?>>Video</option>
        </select>

        <select name="kategori_id" class="form-control mb-2" required>
            <?php
            $kat = pg_query($conn, "SELECT * FROM kategori_galeri ORDER BY judul ASC");
            while ($k = pg_fetch_assoc($kat)):
                $sel = ($editData['kategori_id'] == $k['id']) ? "selected" : "";
            ?>
                <option value="<?= $k['id'] ?>" <?= $sel ?>>
                    <?= htmlspecialchars($k['judul']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="file" name="file" class="form-control mb-2">

        <p class="small text-secondary mb-1">File saat ini:</p>

        <?php if ($editData['jenis'] == "foto"): ?>
            <img src="../assets/img/<?= $editData['file_path'] ?>" class="preview-file mb-2">
        <?php else: ?>
            <video src="../assets/img/<?= $editData['file_path'] ?>" class="preview-file mb-2" controls></video>
        <?php endif; ?>

        <br>

        <button name="simpan_edit" class="btn btn-success">Simpan Perubahan</button>
        <a href="galeri.php" class="btn btn-secondary">Batal</a>

    </form>
    <?php endif; ?>

    <!-- TABLE DATA -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th width="130">Preview</th>
                <th width="140" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $no = 1;
        $q = pg_query($conn, "
            SELECT g.*, k.judul AS kategori
            FROM galeri g
            LEFT JOIN kategori_galeri k ON g.kategori_id = k.id
            ORDER BY g.id DESC
        ");
        while ($d = pg_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['judul']) ?></td>
                <td><?= $d['jenis'] ?></td>
                <td><?= htmlspecialchars($d['kategori']) ?></td>

                <td>
                    <?php if ($d['jenis'] == "foto"): ?>
                        <img src="../assets/img/<?= $d['file_path'] ?>" width="90" class="rounded">
                    <?php else: ?>
                        <video src="../assets/img/<?= $d['file_path'] ?>" width="120" controls></video>
                    <?php endif; ?>
                </td>

                <td class="text-center">
                    <div class="action-btns">
                        <a href="galeri.php?edit=<?= $d['id'] ?>" class="btn btn-warning btn-sm px-3">Edit</a>
                        <a href="galeri.php?hapus=<?= $d['id'] ?>"
                           onclick="return confirm('Hapus file ini?')"
                           class="btn btn-danger btn-sm px-3">Hapus</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>
</body>
</html>