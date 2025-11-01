<?php
include '../includes/config.php';
include '../includes/auth.php';

if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../assets/img/" . $gambar);
    pg_query($conn, "INSERT INTO berita (judul, isi, gambar) VALUES ('$judul', '$isi', '$gambar')");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    pg_query($conn, "DELETE FROM berita WHERE id=$id");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Berita</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h4 class="text-primary mb-3">Kelola Berita & Kegiatan</h4>

  <form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="row">
      <div class="col-md-5"><input name="judul" class="form-control" placeholder="Judul berita" required></div>
      <div class="col-md-4"><input type="file" name="gambar" class="form-control" required></div>
      <div class="col-md-3"><button name="tambah" class="btn btn-primary w-100">Tambah</button></div>
    </div>
    <textarea name="isi" class="form-control mt-2" rows="3" placeholder="Isi berita..."></textarea>
  </form>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr><th>No</th><th>Judul</th><th>Tanggal</th><th>Gambar</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php
    $no=1;
    $query = pg_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
    while ($data = pg_fetch_assoc($query)) {
        echo "<tr>
              <td>$no</td>
              <td>{$data['judul']}</td>
              <td>{$data['tanggal']}</td>
              <td><img src='../assets/img/{$data['gambar']}' width='60'></td>
              <td><a href='?hapus={$data['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Hapus?\")'>Hapus</a></td>
              </tr>";
        $no++;
    }
    ?>
    </tbody>
  </table>
</div>
</body>
</html>