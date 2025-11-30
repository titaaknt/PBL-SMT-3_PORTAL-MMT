<?php
include 'includes/config.php';
session_start();

$message = "";

if (isset($_POST['reset'])) {
    $username = $_POST['username'];
    $password_baru = md5($_POST['password_baru']);

    // cek username
    $query = pg_query($conn, "SELECT * FROM admin WHERE username='$username'");

    if (pg_num_rows($query) > 0) {
        // masukkan ke temp_password
        pg_query($conn, "UPDATE admin SET temp_password='$password_baru' WHERE username='$username'");
        $message = "Password baru berhasil disimpan! Silakan login.";
    } else {
        $message = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | Portal MMT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('assets/img/bg-lab_photo.jpeg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(5px);
    }

    .card-reset {
      z-index: 10;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>

<body>
<div class="overlay"></div>

<div class="card-reset">
  <h4 class="text-center text-primary fw-bold mb-3">Reset Password</h4>

  <?php if ($message != ""): ?>
    <div class="alert alert-info text-center py-2"><?= $message ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="fw-semibold">Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="fw-semibold">Password Baru</label>
      <input type="password" name="password_baru" class="form-control" required>
    </div>

    <button type="submit" name="reset" class="btn btn-primary w-100 py-2">Simpan Password Baru</button>

    <div class="text-center mt-3">
      <a href="login.php">← Kembali ke Login</a>
    </div>
  </form>
</div>

</body>
</html>