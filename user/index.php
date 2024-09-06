<?php
if(!@$_SESSION) {
  session_start();
}

include('../partials/header.php');
include('../modules/cek_admin.php');
include('../modules/koneksi.php');
require('../modules/encrypt.php');

$data = $koneksi->query('SELECT user_id, nama, username, role, created_at FROM users')->fetchAll(PDO::FETCH_OBJ);

if(isset($_POST['submit'])) {
  if($_SESSION['role'] == 'admin') {
    if(!$_POST['nama'] || !$_POST['username'] || !$_POST['password'] || !$_POST['role']) {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Nama, username, password dan role harus diisi';
    } else {
      $stmt = $koneksi->prepare('SELECT * FROM users WHERE username = :username');
      $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch();

      if ($stmt->rowCount() <= 0) {
        $data = [
          'nama' => $_POST['nama'],
          'username' => $_POST['username'],
          'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
          'role' => $_POST['role'],
          'created_at' => date('Y-m-d'),
        ];

        $stmt= $koneksi->prepare('INSERT INTO users (nama, username, password, role, created_at) VALUES (:nama, :username, :password, :role, :created_at)');
        $stmt->execute($data);
        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'Berhasil melakukan penambahan pengguna';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Username sudah dipakai';
      }
    }
  }
}

?>
  <main>
    <?php include('../partials/navbar.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero px-5 py-4 g-4" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-start mt-5">
        <div class="col-lg-7 mb-5">
          <h2 class="text_primary fw-bold lh-1 mb-4" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Daftar Pengguna</h2>
          <table id="user" class="table nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Username</th>
                <th scope="col">Role</th>
                <th scope="col">Tanggal pendaftaran</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $key => $d): ?>
                <tr>
                  <td scope="row"><?= $key+1 ?></td>
                  <td><?= $d->nama ?></td>
                  <td><?= $d->username ?></td>
                  <td><?= $d->role ?></td>
                  <td><?= $d->created_at ?></td>
                  <td>
                    <a href="edit.php?r=<?= encrypt($d->user_id)?>" class="a_primary me-1">Edit</a>
                    <a href="hapus.php?r=<?= encrypt($d->user_id)?>" class="text-danger">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="col-lg-5">
          <?php include('../partials/alert.php') ?>
          <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Tambah Pengguna</h2>
          <form method="POST" class="form_glass mt-4">
            <div class="mb-3 mt-3">
              <label for="nama" class="form-label">Nama <span class="text-danger text-sm">*</span></label>
              <input type="text" name="nama" class="form-control log" id="nama">
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Username <span class="text-danger text-sm">*</span></label>
              <input type="text" name="username" class="form-control log" id="username">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password <span class="text-danger text-sm">*</span></label>
              <input type="password" name="password" class="form-control log" id="password">
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <select id="role" name="role" class="form-select log">
                <option selected>Pilih role</option>
                <option value="pengguna">Pengguna</option>
                <option value="admin">Admin</option>
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
  <script>
    $(document).ready(function() {
      $('#user').DataTable({
        responsive: true
      });
    });
  </script>
<?php include('../partials/script.php') ?>
