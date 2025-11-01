<?php
include '../includes/config.php';
include '../includes/auth.php';

if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $jenis = $_POST['jenis'];
    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    move_uploaded_file($tmp, "../assets/img/" . $file);
    pg_query($conn, "INSERT INTO galeri (judul, file_path, jenis) VALUES ('$judul', '$file', '$jenis')");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    pg_query($conn, "DELETE FROM galeri WHERE id=$id");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Galeri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h4 class="text-primary mb-3">Kelola Galeri</h4>

  <form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="row">
      <div class="col-md-3"><input name="judul" class="form-control" placeholder="Judul file" required></div>
      <div class="col-md-3">
        <select name="jenis" class="form-control">
          <option value="foto">Foto</option>
          <option value="video">Video</option>
        </select>
      </div>
      <div class="col-md-4"><input type="file" name="file" class="form-control" required></div>
      <div class="col-md-2"><button name="tambah" class="btn btn-primary w-100">Tambah</button></div>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr><th>No</th><th>Judul</th><th>Jenis</th><th>File</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php
    $no=1;
    $query = pg_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
    while ($data = pg_fetch_assoc($query)) {
        echo "<tr>
              <td>$no</td>
              <td>{$data['judul']}</td>
              <td>{$data['jenis']}</td>
              <td><img src='../assets/img/{$data['file_path']}' width='60'></td>
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