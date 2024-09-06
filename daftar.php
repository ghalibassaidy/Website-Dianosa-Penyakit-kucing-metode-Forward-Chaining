<?php
include('partials/header.php');
include('modules/koneksi.php');

if(!@$_SESSION) {
  session_start();
}

if(isset($_SESSION['loggedIn'])) {
  header('Location: dashboard.php');
}

if(isset($_POST['submit'])) {
  if(!$_POST['nama'] || !$_POST['username'] || !$_POST['password']) {
    header('Location: daftar.php?alert=error');
    $_SESSION['message'] = 'Nama, username dan password harus diisi';
  } else {
    $stmt = $koneksi->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($stmt->rowCount() == 0) {
      $data = [
        'nama' => $_POST['nama'],
        'username' => $_POST['username'],
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'role' => 'pengguna',
        'created_at' => date('Y-m-d'),
      ];

      $stmt= $koneksi->prepare('INSERT INTO users (nama, username, password, role, created_at) VALUES (:nama, :username, :password, :role, :created_at)');
      $stmt->execute($data);
      header('Location: masuk.php?alert=success');
      $_SESSION['message'] = 'Berhasil melakukan pendaftaran';
    } else {
      header('Location: daftar.php?alert=error');
      $_SESSION['message'] = 'Username sudah dipakai';
    }
  }
}
?>
  <main>
    <!-- Virus -->
    <?php include('partials/virus1.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-center">
        <div class="col-12 col-sm-8 col-lg-6 h-100 hero-img"></div>
        <div class="col-lg-6 px-5 py-2 g-5">
          <?php include('partials/alert.php') ?>
          <h1 class="display-5 text_primary fw-bold lh-1">Daftar</h1>
          <p class="text_primary">Masukkan inputan dengan benar.</p>
          <form class="form-daftar" method="POST">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama <span class="text-danger text-sm">*</span></label>
              <input type="text" name="nama" class="form-control w-50 log" id="nama">
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Username <span class="text-danger text-sm">*</span></label>
              <input type="text" name="username" class="form-control w-50 log" id="username">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password <span class="text-danger text-sm">*</span></label>
              <input type="password" name="password" class="form-control w-50 log" id="password">
            </div>
            <button type="submit" name="submit" class="btn btn_primary transition duration-700 shadow-primary btn-md">Daftar</button>
          </form>
          <div class="mt-4">Sudah mempunyai akun ? <a class="a_primary" href="masuk.php">Masuk</a></div>
        </div>
      </div>
    </div>
  </main>
<?php include('partials/script.php') ?>
