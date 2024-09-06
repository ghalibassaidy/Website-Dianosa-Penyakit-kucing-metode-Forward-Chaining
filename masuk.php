<?php
include('partials/header.php');
include('modules/koneksi.php');

if(!@($_SESSION)) {
  session_start();
}

if(isset($_SESSION['loggedIn'])) {
  header('Location: dashboard.php');
}

if(isset($_POST['submit'])) {
  if(!$_POST['username'] || !$_POST['password']) {
    header('Location: masuk.php?alert=error');
    $_SESSION['message'] = 'Username dan password harus diisi';
  } else {
    if ($stmt = $koneksi->prepare('SELECT user_id, nama, password, role FROM users WHERE username = :username')) {
      $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
      $stmt->execute();
      $fetch = $stmt->fetch(PDO::FETCH_OBJ);
    }

    if ($stmt->rowCount() > 0) {
      if (password_verify($_POST['password'], $fetch->password)) {
        session_regenerate_id();
        $_SESSION['loggedIn'] = True;
        $_SESSION['name'] = $fetch->nama;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['id'] = $fetch->user_id;
        $_SESSION['role'] = $fetch->role;
        $_SESSION['diagnosa'] = False;
        header('Location: dashboard.php');
      } else {
        header('Location: masuk.php?alert=error');
        $_SESSION['message'] = 'Username atau password salah';
      }
    } else {
      header('Location: masuk.php?alert=error');
      $_SESSION['message'] = 'Username atau password salah';
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
        <div class="col-lg-6 px-5 py-5 g-5">
          <?php include('partials/alert.php') ?>
          <h1 class="display-5 text_primary fw-bold lh-1">Masuk</h1>
          <p class="text_primary">Gunakan akun yang sudah didaftarkan.</p>
          <form class="form-login" method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Username <span class="text-danger text-sm">*</span></label>
              <input type="text" name="username" class="form-control w-50 log" id="username">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password <span class="text-danger text-sm">*</span></label>
              <input type="password" name="password" class="form-control w-50 log" id="password">
            </div>
            <button type="submit" name="submit" class="btn btn_primary transition duration-700 shadow-primary btn-md">Masuk</button>
          </form>
          <div class="mt-4">Belum mempunyai akun ? <a class="a_primary" href="daftar.php">Daftar</a></div>
        </div>
      </div>
    </div>
  </main>
<?php include('partials/script.php') ?>
