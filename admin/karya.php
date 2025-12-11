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
    $id_edit = $_GET['edit'];
    $q = pg_query($conn, "SELECT * FROM karya WHERE id=$id_edit");
    $editData = pg_fetch_assoc($q);
}

/* ====================
   SIMPAN TAMBAH
==================== */
if (isset($_POST['simpan_tambah'])) {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    $gambar = $_FILES['gambar']['name'];
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/" . $gambar);

    pg_query($conn, "INSERT INTO karya (judul, kategori, tanggal, deskripsi, gambar)
                     VALUES ('$judul', '$kategori', '$tanggal', '$deskripsi', '$gambar')");

    header("Location: karya.php");
    exit;
}

/* ====================
   SIMPAN EDIT
==================== */
if (isset($_POST['simpan_edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_lama = $_POST['gambar_lama'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
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
    pg_query($conn, "DELETE FROM karya WHERE id={$_GET['hapus']}");
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
  .col-desc{ max-width:260px; font-size:13px; white-space:normal; }

  /* placeholder color */
  input.form-control::placeholder,
  textarea.form-control::placeholder {
      color: #6c757d !important;
      opacity: 0.6 !important;
  }
  
  .col-judul {
    max-width: 220px;      /* batas lebar judul */
    white-space: normal;    /* biar mau turun ke bawah */
    word-break: break-word; /* paksa pecah teks panjang */
}

.col-desc {
    max-width: 260px;      /* batas kolom */
    white-space: normal;   /* teks turun ke bawah */
    word-break: break-word; /* paksa pecah kata panjang */
    overflow-wrap: break-word;
}


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

        <input name="judul" class="form-control mb-2" placeholder="Judul" required>

        <select name="kategori" class="form-control mb-2" required>
            <option value="">-- Pilih Kategori yang Ditambahkan --</option>
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
    <?php if ($mode == "edit" && $editData): ?>
    <form method="post" enctype="multipart/form-data" class="mb-4">

        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
        <input type="hidden" name="gambar_lama" value="<?php echo $editData['gambar']; ?>">

        <input name="judul" class="form-control mb-2"
            value="<?php echo htmlspecialchars($editData['judul']); ?>" required>

        <select name="kategori" class="form-control mb-2" required>
            <option value="Karya" <?php echo ($editData['kategori'] == 'Karya') ? 'selected' : ''; ?>>Karya</option>
            <option value="Prestasi" <?php echo ($editData['kategori'] == 'Prestasi') ? 'selected' : ''; ?>>Prestasi</option>
        </select>

        <input name="tanggal" type="date" class="form-control mb-2"
            value="<?php echo date('Y-m-d', strtotime($editData['tanggal'])); ?>" required>

        <textarea name="deskripsi" class="form-control mb-2" rows="3"><?php echo htmlspecialchars($editData['deskripsi']); ?></textarea>

        <input type="file" name="gambar" class="form-control mb-2">

        <p class="small text-secondary mb-1">Gambar saat ini:</p>
        <img src="../assets/img/<?php echo $editData['gambar']; ?>" width="120" class="rounded mb-2">

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
                <th class="col-judul">Judul</th>

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
        $q = pg_query($conn, "SELECT * FROM karya ORDER BY id DESC");
        while ($d = pg_fetch_assoc($q)):

            $desc_full = htmlspecialchars($d['deskripsi']);
            $desc_full = preg_replace(
                '/(https?:\/\/[^\s]+)/',
                '<a href="$1" target="_blank">$1</a>',
                $desc_full
            );

            $desc_short = substr(strip_tags($desc_full), 0, 80) . "...";
        ?>

        <tr>
            <td><?php echo $no++; ?></td>
            
            <td class="col-judul"><?php echo htmlspecialchars($d['judul']); ?></td>

            <td><?php echo htmlspecialchars($d['kategori']); ?></td>

            <td class="col-desc"><?php echo $desc_short; ?></td>

            <td><?php echo date("d M Y", strtotime($d['tanggal'])); ?></td>

            <td><img src="../assets/img/<?php echo $d['gambar']; ?>" width="90" class="rounded"></td>

            <td class="text-center">
                <div class="action-btns">
                    <a href="karya.php?edit=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm px-3">Edit</a>
                    <a href="karya.php?hapus=<?php echo $d['id']; ?>" onclick="return confirm('Hapus karya ini?')" class="btn btn-danger btn-sm px-3">Hapus</a>
                </div>
            </td>
        </tr>

        <?php endwhile; ?>

        </tbody>
    </table>

</div>
</body>
</html>