<?php
include '../includes/config.php';
include '../includes/auth.php';

if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['kategori'];
    $tahun = $_POST['tahun'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "../assets/img/" . $gambar);

    pg_query($conn, "INSERT INTO karya (judul, deskripsi, kategori, tahun, gambar)
                     VALUES ('$judul', '$deskripsi', '$kategori', '$tahun', '$gambar')");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    pg_query($conn, "DELETE FROM karya WHERE id=$id");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Karya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h4 class="text-primary mb-3">Kelola Karya & Prestasi</h4>

  <form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="row">
      <div class="col-md-3"><input name="judul" class="form-control" placeholder="Judul" required></div>
      <div class="col-md-3"><input name="kategori" class="form-control" placeholder="Kategori" required></div>
      <div class="col-md-2"><input name="tahun" type="number" class="form-control" placeholder="Tahun" required></div>
      <div class="col-md-3"><input type="file" name="gambar" class="form-control" required></div>
      <div class="col-md-1"><button name="tambah" class="btn btn-primary">+</button></div>
    </div>
    <textarea name="deskripsi" class="form-control mt-2" rows="2" placeholder="Deskripsi singkat..."></textarea>
  </form>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr><th>No</th><th>Judul</th><th>Kategori</th><th>Tahun</th><th>Gambar</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php
    $no=1;
    $query = pg_query($conn, "SELECT * FROM karya ORDER BY tahun DESC");
    while ($data = pg_fetch_assoc($query)) {
        echo "<tr>
              <td>$no</td>
              <td>{$data['judul']}</td>
              <td>{$data['kategori']}</td>
              <td>{$data['tahun']}</td>
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