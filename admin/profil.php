<?php
include '../includes/config.php';
include '../includes/auth.php';

if (isset($_POST['simpan'])) {
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];
    $struktur = $_POST['struktur'];
    $kontak = $_POST['kontak'];
    pg_query($conn, "UPDATE profil SET visi='$visi', misi='$misi', struktur='$struktur', kontak='$kontak' WHERE id=1");
    $msg = "Data berhasil diperbarui!";
}
$q = pg_query($conn, "SELECT * FROM profil LIMIT 1");
$data = pg_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h4 class="text-primary mb-3">Kelola Profil Laboratorium</h4>
  <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
  <form method="post">
    <div class="mb-3">
      <label>Visi</label>
      <textarea name="visi" class="form-control" rows="3"><?= $data['visi']; ?></textarea>
    </div>
    <div class="mb-3">
      <label>Misi</label>
      <textarea name="misi" class="form-control" rows="4"><?= $data['misi']; ?></textarea>
    </div>
    <div class="mb-3">
      <label>Struktur Organisasi</label>
      <textarea name="struktur" class="form-control" rows="3"><?= $data['struktur']; ?></textarea>
    </div>
    <div class="mb-3">
      <label>Kontak</label>
      <textarea name="kontak" class="form-control" rows="2"><?= $data['kontak']; ?></textarea>
    </div>
    <button name="simpan" class="btn btn-primary">Simpan</button>
  </form>
</div>
</body>
</html>