<?php
if(!@$_SESSION) {
  session_start();
}

include('partials/header.php');
include('modules/cek_login.php');
include('modules/koneksi.php');

$_SESSION['diagnosa']=False;
if($_SESSION['role'] == 'admin') {
  $diagnosa = $koneksi->query('SELECT * FROM diagnosa')->rowCount();
} else {
  $id = $_SESSION['id'];
  $stmt = $koneksi->prepare('SELECT * FROM diagnosa WHERE user = ?');
  $stmt->execute([$id]);
  $diagnosa = $stmt->rowCount();
}
$penyakit = $koneksi->query('SELECT * FROM penyakit')->rowCount();

$gejala = $koneksi->query('SELECT * FROM gejala')->rowCount();
$users = $koneksi->query('SELECT * FROM users')->rowCount();
?>
  <main>
    <?php include('partials/navbar.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero px-5 py-4 g-5" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-center mt-5">
        <div class="col-lg-10 pt-5">
          <?php include('partials/alert.php') ?>
          <h2 class="text_primary fw-bold lh-1">Dashboard</h2>
          <div class="col-12 col-lg-10">
            <div class="row">
              <div class="col-md-6 col-12 mt-3">
                <div class="card card_dashboard shadow-primary">
                  <div class="card-body clearfix">
                    <div class="row card_dashboard_body align-items-stretch">
                      <div class="col-7 d-flex align-items-center justify-content-start">
                        <div class="align-self-center d-flex ms-2 me-3 text_primary4">
                          <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-activity" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z"/>
                          </svg>
                        </div>
                        <div class="card_dashboard_text">
                          <h5 class="mb-0">Total Diagnosa</h5>
                        </div>
                      </div>
                      <div class="col-5 d-flex align-items-center justify-content-end">
                        <h3 class="mb-0"><?php echo $diagnosa ?></h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-12 mt-3">
                <div class="card card_dashboard shadow-primary">
                  <div class="card-body clearfix">
                    <div class="row card_dashboard_body align-items-stretch">
                      <div class="col-7 d-flex align-items-center justify-content-start">
                        <div class="align-self-center d-flex ms-2 me-3 text_primary4">
                          <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-shield-fill-exclamation" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm-.55 8.502L7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0zM8.002 12a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                          </svg>
                        </div>
                        <div class="card_dashboard_text">
                          <h5 class="mb-0">Total Penyakit</h5>
                        </div>
                      </div>
                      <div class="col-5 d-flex align-items-center justify-content-end">
                        <h3 class="mb-0"><?php echo $penyakit ?></h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php if($_SESSION['role'] == 'admin') { ?>
                <div class="col-md-6 col-12 mt-3">
                  <div class="card card_dashboard shadow-primary">
                    <div class="card-body clearfix">
                      <div class="row card_dashboard_body align-items-stretch">
                        <div class="col-7 d-flex align-items-center justify-content-start">
                          <div class="align-self-center d-flex ms-2 me-3 text_primary4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-slack" viewBox="0 0 16 16">
                              <path d="M3.362 10.11c0 .926-.756 1.681-1.681 1.681S0 11.036 0 10.111C0 9.186.756 8.43 1.68 8.43h1.682v1.68zm.846 0c0-.924.756-1.68 1.681-1.68s1.681.756 1.681 1.68v4.21c0 .924-.756 1.68-1.68 1.68a1.685 1.685 0 0 1-1.682-1.68v-4.21zM5.89 3.362c-.926 0-1.682-.756-1.682-1.681S4.964 0 5.89 0s1.68.756 1.68 1.68v1.682H5.89zm0 .846c.924 0 1.68.756 1.68 1.681S6.814 7.57 5.89 7.57H1.68C.757 7.57 0 6.814 0 5.89c0-.926.756-1.682 1.68-1.682h4.21zm6.749 1.682c0-.926.755-1.682 1.68-1.682.925 0 1.681.756 1.681 1.681s-.756 1.681-1.68 1.681h-1.681V5.89zm-.848 0c0 .924-.755 1.68-1.68 1.68A1.685 1.685 0 0 1 8.43 5.89V1.68C8.43.757 9.186 0 10.11 0c.926 0 1.681.756 1.681 1.68v4.21zm-1.681 6.748c.926 0 1.682.756 1.682 1.681S11.036 16 10.11 16s-1.681-.756-1.681-1.68v-1.682h1.68zm0-.847c-.924 0-1.68-.755-1.68-1.68 0-.925.756-1.681 1.68-1.681h4.21c.924 0 1.68.756 1.68 1.68 0 .926-.756 1.681-1.68 1.681h-4.21z"/>
                            </svg>
                          </div>
                          <div class="card_dashboard_text">
                            <h5 class="mb-0">Total Gejala</h5>
                          </div>
                        </div>
                        <div class="col-5 d-flex align-items-center justify-content-end">
                          <h3 class="mb-0"><?php echo $gejala ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mt-3">
                  <div class="card card_dashboard shadow-primary">
                    <div class="card-body clearfix">
                      <div class="row card_dashboard_body align-items-stretch">
                        <div class="col-7 d-flex align-items-center justify-content-start">
                          <div class="align-self-center d-flex ms-2 me-3 text_primary4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                              <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                              <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                              <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                            </svg>
                          </div>
                          <div class="card_dashboard_text">
                            <h5 class="mb-0">Total User</h5>
                          </div>
                        </div>
                        <div class="col-5 d-flex align-items-center justify-content-end">
                          <h3 class="mb-0"><?php echo $users ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Virus -->
    <?php include('partials/virus2.php') ?>
  </main>
<?php include('partials/script.php') ?>
