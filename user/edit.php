<?php
include('../modules/cek_admin.php');
include('../modules/koneksi.php');
require('../modules/encrypt.php');

if(!@$_SESSION) {
  session_start();
}

if($_SESSION['role'] == 'admin') {
  if($_GET['r'] != '') {
    $enc = $_GET['r'];
    $dec_user = decrypt($enc);
    $stmt = $koneksi->prepare('SELECT * FROM users WHERE user_id = :id');
    $stmt->bindParam(':id', $dec_user);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
      include('../partials/header.php');

      if(isset($_POST['submit'])) {
        if(!$_POST['nama'] || !$_POST['username'] || !$_POST['role']) {
          header('Location: edit.php?r='.$enc.'&alert=error');
          $_SESSION['message'] = 'Nama, username dan role harus ada';
        } else {
          $ada = false;
          if ($row->username != $_POST['username']){
            $stmtc = $koneksi->prepare('SELECT * FROM users WHERE username = :username');
            $stmtc->bindParam(':username', $_POST['username']);
            $stmtc->execute();
            if($stmtc->rowCount() > 0) {
              header('Location: edit.php?r='.$enc.'&alert=error');
              $_SESSION['message'] = 'Username sudah dipakai';
              $ada = true;
            }
          }

          if (!$ada) {
            if (!$_POST['password_new']) {
              $data = [
                'user_id' => $dec_user,
                'nama' => $_POST['nama'],
                'username' => $_POST['username'],
                'role' => $_POST['role']
              ];

              $stmt1 = $koneksi->prepare('UPDATE users SET nama=:nama, username=:username, role=:role WHERE user_id=:user_id');
              $stmt1->execute($data);
              header('Location: index.php?alert=success');
              $_SESSION['message'] = 'Berhasil melakukan edit pengguna';
            } else {
              if ($_POST['password_old']) {
                if (password_verify($_POST['password_old'], $row->password)) {
                  $data = [
                    'user_id' => $dec_user,
                    'nama' => $_POST['nama'],
                    'username' => $_POST['username'],
                    'password' => password_hash($_POST['password_new'], PASSWORD_BCRYPT),
                    'role' => $_POST['role']
                  ];

                  $stmt1 = $koneksi->prepare('UPDATE users SET nama=:nama, username=:username, password=:password, role=:role WHERE user_id=:user_id');
                  $stmt1->execute($data);
                  header('Location: index.php?alert=success');
                  $_SESSION['message'] = 'Berhasil melakukan edit pengguna';
                } else {
                  header('Location: edit.php?r='.$enc.'&alert=error');
                  $_SESSION['message'] = 'Pasword lama salah';
                }
              } else {
                header('Location: edit.php?r='.$enc.'&alert=error');
                $_SESSION['message'] = 'Password lama harus diisi';
              }
            }
          }
        }
      }
    ?>
        <main>
          <?php include('../partials/navbar.php') ?>
          <!-- Hero -->
          <div class="container-fluid col-xxl-8 hero px-5 py-4 g-4" style="position: relative;">
            <div class="row flex-lg-row-reverse align-items-center mt-5">
              <div class="col-lg-9 pt-2">
                <?php include('../partials/alert.php') ?>
                <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Edit Pengguna</h2>
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
                  <div class="mt-3 mb-3">
                    <label for="role" class="form-label">Role <br><span class="text-danger text-sm">* Jika tidak diganti maka biarkan</span></label>
                    <select id="role" name="role" class="form-select log">
                      <option value="" <?=($row->role=='') ? 'selected' : '';?> >Pilih role</option>
                      <option value="pengguna" <?=($row->role=='pengguna') ? 'selected' : '';?>>Pengguna</option>
                      <option value="admin" <?=($row->role=='admin') ? 'selected' : '';?>>Admin</option>
                    </select>
                  </div>
                  <div class="btn_submit_left">
                    <button type="submit" name="submit" class="ms-auto btn btn_primary transition duration-700 shadow-primary btn-md mt-2 mb-3">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Virus -->
          <?php include('../partials/virus2.php') ?>
        </main>
<?php include('../partials/script.php');
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'User tidak ditemukan';
    }
  }
}
