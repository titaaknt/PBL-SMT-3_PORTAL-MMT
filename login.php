<?php
include 'includes/config.php';
session_start();

// ============ LOGIN PROCESS ============
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = pg_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    if (pg_num_rows($query) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

// ============ RESET PASSWORD FORM SUBMIT ============
if (isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $newpass = md5($_POST['newpass']);

    $update = pg_query($conn, "UPDATE admin SET password='$newpass' WHERE username='$username'");

    if ($update) {
        $success = "Password berhasil diperbarui! Silakan login.";
    } else {
        $error = "Gagal memperbarui password!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin | Portal MMT</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('assets/img/bg-lab_photo.jpeg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .title-blue {
      color : #0056A6;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(5px);
    }

    .login-card {
      position: relative;
      z-index: 10;
      background: rgba(255, 255, 255, 0.98);
      
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      padding: 40px 30px;
      width: 100%;
      max-width: 400px;
    }

    .login-card img {
      width: 70px;
      display: block;
      margin: 0 auto 10px;
    }

    .btn-login {
      background-color: #0056A6;
      color: white;
      font-weight: 600;
    }

    .btn-login:hover {
      background-color: #003f7d;
    }

    .text-footer {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin-top: 15px;
    font-size: 0.9rem;
    color: #333;
}


  </style>
</head>

<body>

<div class="overlay"></div>

<div class="login-card">

    <?php
    // ===========================
    // MODE 1 : RESET PASSWORD
    // ===========================
    if (isset($_GET['reset'])):
        $username = $_GET['reset'];
    ?>

        <h4 class="text-center">Password Baru</h4>

        <?php if(isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <?php if(isset($success)) echo "<div class='alert alert-success text-center'>$success</div>"; ?>

        <form method="POST">
            <input type="hidden" name="username" value="<?= $username ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <input type="password" name="newpass" class="form-control" required>
            </div>

            <button type="submit" name="reset_password" class="btn btn-success w-100 py-2">Update Password</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Kembali ke Login</a>
        </div>

    <?php
    // ===========================
    // MODE 2 : FORGOT PASSWORD
    // ===========================
    elseif (isset($_GET['forgot'])):
    ?>
    <h4 class="text-center">Lupa Password</h4>

  <?php if(isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

<form method="POST" action="login.php?forgot=1">
    <div class="mb-3">
        <label class="form-label fw-semibold">Masukkan Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username admin..." required>
    </div>

    <?php
    if (isset($_POST['check_username'])) {
        $u = $_POST['username'];
        $q = pg_query($conn, "SELECT * FROM admin WHERE username='$u'");
        if (pg_num_rows($q) > 0) {
            echo "<script>window.location='login.php?reset=$u';</script>";
        } else {
            echo "<div class='alert alert-danger text-center'>Username tidak ditemukan!</div>";
        }
    }
    ?>

    <button class="btn btn-primary w-100 py-2" name="check_username" type="submit">Lanjutkan</button>
</form>

<div class="text-center mt-3">
    <a href="login.php">Kembali ke Login</a>
</div>


       

    <?php
    // ===========================
    // MODE 3 : NORMAL LOGIN
    // ===========================
    else:
    ?>

        <img src="assets/img/polinema.png">
        <h4 class="text-center title-blue">Login Admin</h4>

        <?php if(isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <?php if(isset($success)) echo "<div class='alert alert-success text-center'>$success</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-login w-100 py-2">Masuk</button>
        </form>

        <div class="text-center mt-2">
            <a href="login.php?forgot=1" class="text-decoration-none" style="font-size: 0.85rem;">
                Lupa Password?
            </a>
        </div>

            <div class="text-footer">© 2025 Laboratorium Multimedia & Mobile Tech<br>Politeknik Negeri Malang</div>
  </div>

    <?php endif; ?>
</div>
</body>
</html>