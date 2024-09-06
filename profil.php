<?php
include('modules/cek_login.php');
include('modules/koneksi.php');

if(!@$_SESSION) {
  session_start();
}

$_SESSION['diagnosa']=False;
$id = $_SESSION['id'];
$stmt = $koneksi->prepare('SELECT * FROM users WHERE user_id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);

include('partials/header.php');
if(isset($_POST['submit'])) {
  if(!$_POST['nama'] || !$_POST['username']) {
    header('Location: profil.php?alert=error');
    $_SESSION['message'] = 'Nama, username harus ada';
  } else {
    $ada = false;
    if ($row->username != $_POST['username']){
      $stmtc = $koneksi->prepare('SELECT * FROM users WHERE username = :username');
      $stmtc->bindParam(':username', $_POST['username']);
      $stmtc->execute();
      if($stmtc->rowCount() > 0) {
        header('Location: profil.php?alert=error');
        $_SESSION['message'] = 'Username sudah dipakai';
        $ada = true;
      }
    }

    if (!$ada) {
      if (!$_POST['password_new']) {
        $data = [
          'user_id' => $id,
          'nama' => $_POST['nama'],
          'username' => $_POST['username']
        ];

        $stmt1 = $koneksi->prepare('UPDATE users SET nama=:nama, username=:username WHERE user_id=:user_id');
        $stmt1->execute($data);
        header('Location: profil.php?alert=success');
        $_SESSION['message'] = 'Berhasil melakukan edit pengguna';
      } else {
        if ($_POST['password_old']) {
          if (password_verify($_POST['password_old'], $row->password)) {
            $data = [
              'user_id' => $id,
              'nama' => $_POST['nama'],
              'username' => $_POST['username'],
              'password' => password_hash($_POST['password_new'], PASSWORD_BCRYPT)
            ];

            $stmt1 = $koneksi->prepare('UPDATE users SET nama=:nama, username=:username, password=:password WHERE user_id=:user_id');
            $stmt1->execute($data);
            header('Location: profil.php?alert=success');
            $_SESSION['message'] = 'Berhasil melakukan edit pengguna';
          } else {
            header('Location: profil.php?alert=error');
            $_SESSION['message'] = 'Pasword lama salah';
          }
        } else {
          header('Location: profil.php?alert=error');
          $_SESSION['message'] = 'Password lama harus diisi';
        }
      }
    }
  }
}
?>
  <main>
    <?php include('partials/navbar.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero px-5 py-4 g-4" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-start mt-5 me-5">
        <div class="col-lg-8">
          <?php include('partials/alert.php') ?>
          <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Edit Profil</h2>
          <form method="POST" class="form_glass mt-4 row">
            <div class="mb-3 mt-3 col-md-6">
              <label for="nama" class="form-label">Nama <span class="text-danger text-sm">*</span></label>
              <input type="text" name="nama" class="form-control log" id="nama" value="<?= $row->nama ?>">
            </div>
            <div class="mt-3 col-md-6">
              <label for="username" class="form-label">Username <span class="text-danger text-sm">*</span></label>
              <input type="text" name="username" class="form-control log" id="username" value="<?= $row->username ?>">
            </div>
            <div class="mt-3 col-md-6">
              <label for="password_old" class="form-label">Password lama <br><span class="text-danger text-sm">* Jika tidak diganti maka kosongi</span></label>
              <input type="password" name="password_old" class="form-control log" id="password_old">
            </div>
            <div class="mt-3 col-md-6">
              <label for="password_new" class="form-label">Password baru <br><span class="text-danger text-sm">* Jika tidak diganti maka kosongi</span></label>
              <input type="password" name="password_new" class="form-control log" id="password_new">
            </div>
            <div class="btn_submit_left">
              <button type="submit" name="submit" class="ms-auto btn btn_primary transition duration-700 shadow-primary btn-md mt-2 mb-3">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Virus -->
    <?php include('partials/virus2.php') ?>
  </main>
<?php include('partials/script.php');
