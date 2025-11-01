<?php
include 'includes/config.php';
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $query = pg_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    if (pg_num_rows($query) > 0) {
        $data = pg_fetch_assoc($query);
        $_SESSION['admin'] = $data['username'];
        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
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
      backdrop-filter: blur(10px);
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

    .login-card h4 {
      color: #0056A6;
      font-weight: 600;
      text-align: center;
      margin-bottom: 25px;
    }

    .btn-login {
      background-color: #0056A6;
      color: white;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-login:hover {
      background-color: #003f7d;
    }

    .text-footer {
      text-align: center;
      font-size: 0.9rem;
      color: #333;
      margin-top: 15px;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="overlay"></div>

  <div class="login-card">
    <img src="assets/img/polinema.png" alt="Logo Polinema">
    <h4>Login Admin</h4>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger text-center py-2"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
      </div>
      <button type="submit" name="login" class="btn btn-login w-100 py-2">Masuk</button>
    </form>

    <div class="text-footer">© 2025 Lab Multimedia & Mobile Tech<br>Politeknik Negeri Malang</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>