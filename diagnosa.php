<?php
if(!@$_SESSION) {
  session_start();
}

include('partials/header.php');
include('modules/cek_login.php');
include('modules/koneksi.php');
require('modules/encrypt.php');

$gejala = $koneksi->query('SELECT * FROM gejala')->fetchAll(PDO::FETCH_OBJ); ?>

<main>
  <?php include('partials/navbar.php') ?>
  <!-- Hero -->
  <div class="container-fluid col-xxl-8 hero px-5 py-2 g-5" style="position: relative;">
    <div class="row flex-lg-row-reverse align-items-center py-5 mt-5">
      <div class="col-lg-9">
        <?php if(isset($_POST['submit'])) {
          $dumpGejala = $_POST['gejala'];
          if (!$_SESSION['diagnosa']) {
            $stmDA = $koneksi->query('SELECT * FROM diagnosa');
            $data = $stmDA->fetchAll(PDO::FETCH_OBJ);
            $pNum = $stmDA->rowCount() + 1;
            $kodeDiagnosa = 'D'.$_SESSION['id'].'D'.$pNum;
          }
        ?>
          <h2 class="text_primary fw-bold lh-1">Hasil Diagnosa</h2>
          <div class="card_glass table-responsive mt-4">
            <table class="table table-hover p-5">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Kode</th>
                  <th scope="col">Nama</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dumpGejala as $no => $dG):
                  $stDG = $koneksi->prepare('SELECT nama_gejala FROM gejala WHERE kode_gejala = :kg');
                  $stDG->execute([':kg' => $dG]);
                  $nG = $stDG->fetch(PDO::FETCH_OBJ);
                  $dGN = $nG->nama_gejala;
                ?>
                  <tr>
                    <th scope="row"><?= $no+1 ?></th>
                    <td><?= $dG ?></td>
                    <td><?= $dGN ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php
          $hasilAkhirPenyakit;
          $hasilAkhirAngka = 0;
          $bobot = array();
          $theta = array();
          $m = array();
          $dumpPenyakit = array();
          $sameMPenyakit = array();
          $sameMPenyakitBaru = array();
          $bagi2PerTabel = count($dumpGejala)-1;
          $mode = 1;
          $changeMode = false;
          $mM2 = array();
          $pP2 = array();

          if(count($dumpGejala) > 1) {
            for ($i = 0; $i < count($dumpGejala); $i++) {
              $stmt = $koneksi->prepare('SELECT bobot_gejala FROM gejala WHERE kode_gejala = :kg');
              $stmt->execute([':kg' => $dumpGejala[$i]]);
              $nilaiBobot = $stmt->fetch(PDO::FETCH_OBJ);
              $bobot[] = $nilaiBobot->bobot_gejala;
              $stp = $koneksi->prepare('SELECT penyakit FROM relasi_gejala WHERE gejala = :g');
              $stp->execute([':g' => $dumpGejala[$i]]);
              $resultPenyakit = $stp->fetchAll(PDO::FETCH_ASSOC);
              $tempDumpPenyakit = array();
              foreach ($resultPenyakit as $p) {
                $tempDumpPenyakit[] = $p['penyakit'];
              }
              $dumpPenyakit[] = $tempDumpPenyakit;
            }

            for ($i=0; $i < $bagi2PerTabel; $i++) {
              if(count($sameMPenyakit) > 0 && $mode == 1) {
                $sameMPenyakit[] = array_intersect($sameMPenyakit[$i-1], $dumpPenyakit[$i+1]);
              } else {
                $sameMPenyakit[] = array_intersect($dumpPenyakit[$i], $dumpPenyakit[$i+1]);
              }

              $isiPenyakit[] = array();
              if(count($m) > 0) {
                $theta[$i+2] = 1 - $bobot[$i+1];
              } else {
                $theta[$i] = 1 - $bobot[$i];
                $theta[$i+1] = 1 - $bobot[$i+1];
              }

              if($i > 0) {
                $m[$i+2] = $bobot[$i+1];
              } else {
                $m[$i+1] = $bobot[$i+1];
                $m[$i] = $bobot[$i];
              }

              if($mode == 1) {
            ?>
                <div class="card_glass table-responsive mt-4">
                  <table class="table table-hover p-5">
                    <thead>
                      <tr>
                        <th colspan="3"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Atas -->
                      <tr>
                        <td></td>
                        <td>
                          <!-- Title M2 -->
                          <span style="font-weight: bold;">M<?= $i > 1 ? $i*2+2 : ($i > 0 ? $i+3 : $i+2) ?></span>
                          <?php
                            echo '(';
                            foreach ($dumpPenyakit[$i+1] as $dP) {
                              echo $dP.',';
                            }
                            if($i > 0) {
                              echo ')'.' > '.'('.$m[$i+2].')';
                            } else {
                              echo ')'.' > '.'('.$m[$i+1].')';
                            }
                          ?>
                        </td>
                        <td>
                          <!-- Theta M2 -->
                          <span style="font-weight: bold;">&#952;</span> (<?= $i > 0 ? $theta[$i+2] : $theta[$i+1] ?>)
                        </td>
                      </tr>
                      <!-- Tengah -->
                      <tr>
                        <td>
                          <!-- Title M1 -->
                          <span style="font-weight: bold;">M<?= $i > 1 ? ($i*2)+1 : ($i > 0 ? $i+2 : $i+1) ?></span>
                          <?php
                            if($i > 0) {
                              echo '(';
                              foreach ($sameMPenyakit[$i-1] as $dP) {
                                echo $dP.',';
                              }
                              echo ')'.' > '.'('.$m[$i+1].')';
                            } else {
                              echo '(';
                              foreach ($dumpPenyakit[$i] as $dP) {
                                echo $dP.',';
                              }
                              echo ')'.' > '.'('.$m[$i].')';
                            }
                          ?>
                        </td>
                        <!-- M1 * M2 -->
                        <td>
                          <?php
                            echo '(';
                            $tempPenyakit = array();
                            foreach ($sameMPenyakit[$i] as $pM) {
                              $tempPenyakit[] = $pM;
                              echo $pM.',';
                            }
                            $isiPenyakit[0] = [$tempPenyakit, ($i > 0 ? $m[$i+1]*$m[$i+2] : $m[$i]*$m[$i+1])];
                            unset($tempPenyakit);
                            if($i > 0 ) {
                              echo ')'.' > '.'('.($m[$i+1]*$m[$i+2]).')';
                            } else {
                              echo ')'.' > '.'('.($m[$i]*$m[$i+1]).')';
                            }
                          ?>
                        </td>
                        <td>
                          <!-- M1 * Theta -->
                          <?php
                            $tempPenyakit = array();
                            if($i > 0) {
                              echo '(';
                              foreach ($sameMPenyakit[$i-1] as $dP) {
                                $tempPenyakit[] = $dP;
                                echo $dP.',';
                              }
                              echo ')'.' > '.'('.$m[$i+1]*$theta[$i+2].')';
                            } else {
                              echo '(';
                              foreach ($dumpPenyakit[$i] as $dP) {
                                $tempPenyakit[] = $dP;
                                echo $dP.',';
                              }
                              echo ')'.' > '.'('.$m[$i]*$theta[$i+1].')';
                            }
                            $isiPenyakit[1] = [$tempPenyakit, ($i > 0 ? $m[$i+1]*$theta[$i+2] : $m[$i]*$theta[$i+1])];
                            unset($tempPenyakit);
                          ?>
                        </td>
                      </tr>
                      <!-- Bawah -->
                      <tr>
                        <td>
                          <span style="font-weight: bold;">&#952;</span> (<?= $i > 0 ? $theta[$i+1] : $theta[$i] ?>)
                        </td>
                        <td>
                          <?php
                            echo '(';
                            $tempPenyakit = array();
                            foreach ($dumpPenyakit[$i+1] as $dP) {
                              $tempPenyakit[] = $dP;
                              echo $dP.',';
                            }
                            $isiPenyakit[2] = [$tempPenyakit, ($i > 0 ? $theta[$i+1]*$m[$i+2] : $theta[$i]*$m[$i+1])];
                            unset($tempPenyakit);
                            if($i > 0) {
                              echo ')'.' > '.'('.($theta[$i+1]*$m[$i+2]).')';
                            } else {
                              echo ')'.' > '.'('.($theta[$i]*$m[$i+1]).')';
                            }
                          ?>
                        </td>
                        <td>(&#952;) > <?= $i > 0 ? $theta[$i+1]*$theta[$i+2] : $theta[$i+1]*$theta[$i] ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
            <?php
              } else { ?>
                <div class="card_glass table-responsive mt-4">
                  <table class="table table-hover p-5">
                    <thead>
                      <tr>
                        <th colspan="3"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Atas -->
                      <tr>
                        <td></td>
                        <td>
                          <!-- Title M2 -->
                          <span style="font-weight: bold;">M<?= $i > 1 ? $i*2+2 : ($i > 0 ? $i+3 : $i+2) ?></span>
                          <?php
                            echo '(';
                            foreach ($dumpPenyakit[$i+1] as $dP) {
                              echo $dP.',';
                            }
                            if($i > 0) {
                              echo ')'.' > '.'('.$m[$i+2].')';
                            } else {
                              echo ')'.' > '.'('.$m[$i+1].')';
                            }
                          ?>
                        </td>
                        <td>
                          <!-- Theta M2 -->
                          <span style="font-weight: bold;">&#952;</span> (<?= $i > 0 ? $theta[$i+2] : $theta[$i+1] ?>)
                        </td>
                      </tr>
                      <!-- Tengah -->
                      <?php for($ib = 0; $ib < count($pP2); $ib++) {
                        unset($sameMPenyakitBaru);
                        $sameMPenyakitBaru = array();
                        $sameMPenyakitBaru[0] = array_intersect($pP2[$ib], $dumpPenyakit[$i+1]);
                      ?>
                        <tr>
                          <td>
                            <!-- Title M1 -->
                            <span style="font-weight: bold;">M<?= $i > 1 ? ($i*2)+1 : ($i > 0 ? $i+2 : $i+1) ?></span>
                            <?php
                              echo '(';
                              foreach ($pP2[$ib] as $pP) {
                                echo $pP.',';
                              }
                              echo ')'.' > '.'('.$mM2[$ib].')';
                            ?>
                          </td>
                          <!-- M * M -->
                          <td>
                            <?php
                              echo '(';
                              $tempPenyakit = array();
                              foreach ($sameMPenyakitBaru[0] as $pM) {
                                $tempPenyakit[] = $pM;
                                echo $pM.',';
                              }
                              $isiPenyakit[$ib][0] = [$tempPenyakit, ($i > 0 ? ($mM2[$ib]*$m[$i+2]) : ($mM2[$ib]*$m[$i+1]))];
                              unset($tempPenyakit);
                              if($i > 0 ) {
                                echo ')'.' > '.'('.($mM2[$ib]*$m[$i+2]).')';
                              } else {
                                echo ')'.' > '.'('.($mM2[$ib]*$m[$i+1]).')';
                              }
                            ?>
                          </td>
                          <td>
                            <!-- M * Theta -->
                            <?php
                              echo '(';
                              $tempPenyakit = array();
                              foreach ($pP2[$ib] as $pM) {
                                $tempPenyakit[] = $pM;
                                echo $pM.',';
                              }
                              $isiPenyakit[$ib][1] = [$tempPenyakit, ($i > 0 ? ($mM2[$ib]*$theta[$i+2]) : ($mM2[$ib]*$theta[$i+1]))];
                              unset($tempPenyakit);
                              if($i > 0 ) {
                                echo ')'.' > '.'('.($mM2[$ib]*$theta[$i+2]).')';
                              } else {
                                echo ')'.' > '.'('.($mM2[$ib]*$theta[$i+1]).')';
                              }
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                      <!-- Bawah -->
                      <tr>
                        <td>
                          <span style="font-weight: bold;">&#952;</span> (<?= $i > 0 ? $theta[$i+1] : $theta[$i] ?>)
                        </td>
                        <td>
                          <?php
                            echo '(';
                            $tempPenyakit = array();
                            foreach ($dumpPenyakit[$i+1] as $dP) {
                              $tempPenyakit[] = $dP;
                              echo $dP.',';
                            }
                            $isiPenyakit[count($pP2)][0] = [$tempPenyakit, ($i > 0 ? ($theta[$i+1]*$m[$i+2]) : ($theta[$i]*$m[$i+1]))];
                            unset($tempPenyakit);
                            if($i > 0) {
                              echo ')'.' > '.'('.($theta[$i+1]*$m[$i+2]).')';
                            } else {
                              echo ')'.' > '.'('.($theta[$i]*$m[$i+1]).')';
                            }
                          ?>
                        </td>
                        <td>(&#952;) > <?= $i > 0 ? $theta[$i+1]*$theta[$i+2] : $theta[$i+1]*$theta[$i] ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
            <?php }
              // Mode 2 blm akhir
              if($mode == 2 && $i != $bagi2PerTabel-1) {
                $filterPenyakit = array();
                $jumlahBobotHK = 0;
                foreach ($isiPenyakit as $iP) {
                  foreach ($iP as $cP) {
                    $filterPenyakit[] = $cP;
                  }
                }

                // Hitung jumlah bobot himpunan kosong
                foreach ($filterPenyakit as $fP) {
                  if (count($fP[0]) == 0) {
                    $jumlahBobotHK += $fP[1];
                  }
                }

                $iAda = array();
                $countAda = 0;
                $pSudahAda = array();

                for($fP1 = 0; $fP1 < count($filterPenyakit); $fP1++) { // Utama
                  if(!in_array($fP1, $pSudahAda) && count($filterPenyakit[$fP1][0]) != 0) {
                    $iAda[$countAda] = [$filterPenyakit[$fP1][0], $filterPenyakit[$fP1][1]];
                    for($fP = $fP1+1; $fP < count($filterPenyakit); $fP++) { // Bandingkan semua
                      $sama = array();
                      if (count($filterPenyakit[$fP1][0]) == count($filterPenyakit[$fP][0]) && count($filterPenyakit[$fP][0]) != 0) {
                        for($iPfa = 0; $iPfa < count($filterPenyakit[$fP1][0]); $iPfa++) { // Loop isi penyakit Utama
                          $ada = false;
                          for($iPfa2 = 0; $iPfa2 < count($filterPenyakit[$fP][0]); $iPfa2++) { // Loop isi penyakit bandingan
                            if($filterPenyakit[$fP1][0][$iPfa] == $filterPenyakit[$fP][0][$iPfa2]) {
                              $sama[] = true;
                              $ada = true;
                              break;
                            }
                          }

                          if(!$ada) {
                            $sama[] = false;
                          }
                        }
                      } else {
                        $sama[] = false;
                      }

                      if (in_array(false, $sama) === false) {
                        $iAda[$countAda][1] += $filterPenyakit[$fP][1];
                        $pSudahAda[] = $fP;
                      }
                    }

                    $iAda[$countAda][1] /= (1 - $jumlahBobotHK);
                    $iAda[$countAda][1] = round($iAda[$countAda][1], 6);
                    $countAda++;
                  }
                }

                unset($pP2);
                unset($mM2);
                $pP2 = array();
                $mM2 = array();

                for($cMP = 0; $cMP < count($iAda); $cMP++) {
                  $pP2[] = $iAda[$cMP][0];
                  $mM2[] = $iAda[$cMP][1];
                }

                $theta[$i+2] = round((($i > 0 ? $theta[$i+1]*$theta[$i+2] : $theta[$i+1]*$theta[$i])/(1 - $jumlahBobotHK)), 6);

              // Mode 2 jika terakhir
              } else if($mode == 2 && $i == $bagi2PerTabel-1) {
                $filterPenyakit = array();
                $jumlahBobotHK = 0;
                foreach ($isiPenyakit as $iP) {
                  foreach ($iP as $cP) {
                    $filterPenyakit[] = $cP;
                  }
                }

                // Hitung jumlah bobot himpunan kosong
                foreach ($filterPenyakit as $fP) {
                  if (count($fP[0]) == 0) {
                    $jumlahBobotHK += $fP[1];
                  }
                }

                $iAda = array();
                $countAda = 0;
                $pSudahAda = array();

                for($fP1 = 0; $fP1 < count($filterPenyakit); $fP1++) { // Utama
                  if(!in_array($fP1, $pSudahAda) && count($filterPenyakit[$fP1][0]) != 0) {
                    $iAda[$countAda] = [$filterPenyakit[$fP1][0], $filterPenyakit[$fP1][1]];

                    for($fP = $fP1+1; $fP < count($filterPenyakit); $fP++) { // Bandingkan semua
                      $sama = array();
                      if (count($filterPenyakit[$fP1][0]) == count($filterPenyakit[$fP][0]) && count($filterPenyakit[$fP][0]) != 0) {
                        for($iPfa = 0; $iPfa < count($filterPenyakit[$fP1][0]); $iPfa++) { // Loop isi penyakit Utama
                          $ada = false;
                          for($iPfa2 = 0; $iPfa2 < count($filterPenyakit[$fP][0]); $iPfa2++) { // Loop isi penyakit bandingan
                            if($filterPenyakit[$fP1][0][$iPfa] == $filterPenyakit[$fP][0][$iPfa2]) {
                              $sama[] = true;
                              $ada = true;
                              break;
                            }
                          }

                          if(!$ada) {
                            $sama[] = false;
                          }
                        }
                      } else {
                        $sama[] = false;
                      }

                      if (in_array(false, $sama) === false) {
                        $iAda[$countAda][1] += $filterPenyakit[$fP][1];
                        $pSudahAda[] = $fP;
                      }
                    }

                    $iAda[$countAda][1] /= (1 - $jumlahBobotHK);
                    $iAda[$countAda][1] = round($iAda[$countAda][1], 6);
                    $countAda++;
                  }
                }

                $hasilAkhirAngka = $iAda[0][1];
                $hasilAkhirPenyakit = $iAda[0][0];
                for($aNpS = 1; $aNpS < count($iAda); $aNpS++) {
                  if($hasilAkhirAngka < $iAda[$aNpS][1]) {
                    $hasilAkhirAngka = $iAda[$aNpS][1];
                    $hasilAkhirPenyakit = $iAda[$aNpS][0];
                  }
                }
              }

              // Jika terakhir blm mode 2
              if($mode != 2 && $i == $bagi2PerTabel-1) {
                $filterPenyakitB = array();
                $jumlahBobotHK = 0;
                foreach ($isiPenyakit as $iP) {
                  $filterPenyakitB[] = $iP;
                }

                // Hitung jumlah bobot himpunan kosong
                foreach ($filterPenyakitB as $fP) {
                  if (count($fP[0]) == 0) {
                    $jumlahBobotHK += $fP[1];
                  }
                }

                $iAda = array();
                $countAda = 0;
                $pSudahAda = array();

                for($fP1 = 0; $fP1 < count($filterPenyakitB); $fP1++) { // Utama
                  if(!in_array($fP1, $pSudahAda) && count($filterPenyakitB[$fP1][0]) != 0) {
                    $iAda[$countAda] = [$filterPenyakitB[$fP1][0], $filterPenyakitB[$fP1][1]];

                    for($fP = $fP1+1; $fP < count($filterPenyakitB); $fP++) { // Bandingkan semua
                      $sama = array();
                      if (count($filterPenyakitB[$fP1][0]) == count($filterPenyakitB[$fP][0]) && count($filterPenyakitB[$fP][0]) != 0) {
                        for($iPfa = 0; $iPfa < count($filterPenyakitB[$fP1][0]); $iPfa++) { // Loop isi penyakit Utama
                          $ada = false;
                          for($iPfa2 = 0; $iPfa2 < count($filterPenyakitB[$fP][0]); $iPfa2++) { // Loop isi penyakit bandingan
                            if($filterPenyakitB[$fP1][0][$iPfa] == $filterPenyakitB[$fP][0][$iPfa2]) {
                              $sama[] = true;
                              $ada = true;
                              break;
                            }
                          }

                          if(!$ada) {
                            $sama[] = false;
                          }
                        }
                      } else {
                        $sama[] = false;
                      }

                      if (in_array(false, $sama) === false) {
                        $iAda[$countAda][1] += $filterPenyakitB[$fP][1];
                        $pSudahAda[] = $fP;
                      }
                    }

                    $iAda[$countAda][1] /= (1 - $jumlahBobotHK);
                    $iAda[$countAda][1] = round($iAda[$countAda][1], 6);
                    $countAda++;
                  }
                }

                $hasilAkhirAngka = $iAda[0][1];
                $hasilAkhirPenyakit = $iAda[0][0];
                for($aNpS = 1; $aNpS < count($iAda); $aNpS++) {
                  if($hasilAkhirAngka < $iAda[$aNpS][1]) {
                    $hasilAkhirAngka = $iAda[$aNpS][1];
                    $hasilAkhirPenyakit = $iAda[$aNpS][0];
                  }
                }
              }

              if ($i == 0 && $i != $bagi2PerTabel-1) {
                $filterPenyakitB = array();
                $jumlahBobotHK = 0;
                foreach ($isiPenyakit as $iP) {
                  $filterPenyakitB[] = $iP;
                }

                // Hitung jumlah bobot himpunan kosong
                foreach ($filterPenyakitB as $fP) {
                  if (count($fP[0]) == 0) {
                    $jumlahBobotHK += $fP[1];
                  }
                }

                $iAda = array();
                $countAda = 0;
                $pSudahAda = array();

                for($fP1 = 0; $fP1 < count($filterPenyakitB); $fP1++) { // Utama
                  if(!in_array($fP1, $pSudahAda) && count($filterPenyakitB[$fP1][0]) != 0) {
                    $iAda[$countAda] = [$filterPenyakitB[$fP1][0], $filterPenyakitB[$fP1][1]];

                    for($fP = $fP1+1; $fP < count($filterPenyakitB); $fP++) { // Bandingkan semua
                      $sama = array();
                      if (count($filterPenyakitB[$fP1][0]) == count($filterPenyakitB[$fP][0]) && count($filterPenyakitB[$fP][0]) != 0) {
                        for($iPfa = 0; $iPfa < count($filterPenyakitB[$fP1][0]); $iPfa++) { // Loop isi penyakit Utama
                          $ada = false;
                          for($iPfa2 = 0; $iPfa2 < count($filterPenyakitB[$fP][0]); $iPfa2++) { // Loop isi penyakit bandingan
                            if($filterPenyakitB[$fP1][0][$iPfa] == $filterPenyakitB[$fP][0][$iPfa2]) {
                              $sama[] = true;
                              $ada = true;
                              break;
                            }
                          }

                          if(!$ada) {
                            $sama[] = false;
                          }
                        }
                      } else {
                        $sama[] = false;
                      }

                      if (in_array(false, $sama) === false) {
                        $iAda[$countAda][1] += $filterPenyakitB[$fP][1];
                        $pSudahAda[] = $fP;
                      }
                    }

                    $iAda[$countAda][1] /= (1 - $jumlahBobotHK);
                    $iAda[$countAda][1] = round($iAda[$countAda][1], 6);
                    $countAda++;
                  }
                }

                foreach ($iAda as $id) {
                  $pP2[] = $id[0];
                  $mM2[] = $id[1];
                }
                $theta[$i+2] = round((($i > 0 ? $theta[$i+1]*$theta[$i+2] : $theta[$i+1]*$theta[$i])/(1 - $jumlahBobotHK)), 6);
                $mode = 2;
              }

              unset($isiPenyakit);
            }
            ?>
            <div class="card_glass mt-4">
              <table class="table table-hover p-5">
                <thead>
                  <tr>
                    <th colspan="3" style="text-align: center;">Hasil diagnosa</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Hasil bobot</td>
                    <td>
                      Kemungkinan Penyakit
                    </td>
                  </tr>
                  <tr>
                    <td><?= $hasilAkhirAngka ?></td>
                    <td>
                      <?php
                        echo '(';
                        foreach ($hasilAkhirPenyakit as $aP) {
                          echo $aP.',';
                        }
                        echo ')';
                      ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          <?php
            foreach ($hasilAkhirPenyakit as $hP) {
              $stP = $koneksi->prepare('SELECT nama_penyakit, keterangan_penyakit, solusi_penyakit FROM penyakit WHERE kode_penyakit = :kP');
              $stP->execute([':kP' => $hP]);
              $rP = $stP->fetch(PDO::FETCH_OBJ);
          ?>
              <div class="card_glass mt-4">
                <table class="table table-hover p-5">
                  <thead>
                    <tr>
                      <th colspan="3" style="text-align: center;">Penyakit <?= $rP->nama_penyakit ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Kode Penyakit</td>
                      <td>
                        Keterangan
                      </td>
                      <td>
                        Solusi
                      </td>
                    </tr>
                    <tr>
                      <td><?= $hP ?></td>
                      <td>
                        <?= $rP->keterangan_penyakit ?>
                      </td>
                      <td>
                        <?= $rP->solusi_penyakit ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
          <?php }
            $rapiPenyakit = implode(',',$hasilAkhirPenyakit);
            $rapiGejala = implode(',',$dumpGejala);

            if (!$_SESSION['diagnosa']) {
              $data = [
                'kode' => $kodeDiagnosa,
                'user' => $_SESSION['id'],
                'penyakit' => $rapiPenyakit,
                'gejala' => $rapiGejala,
                'persentase' => $hasilAkhirAngka,
                'tanggal_diagnosa' => date('Y-m-d'),
              ];
              $stmt= $koneksi->prepare('INSERT INTO diagnosa (kode_diagnosa, user, penyakit, gejala, persentase, tanggal_diagnosa) VALUES (:kode, :user, :penyakit, :gejala, :persentase, :tanggal_diagnosa)');
              $stmt->execute($data);
              $_SESSION['diagnosa'] = True;
            }
          } else { ?>
            <h2 class="text-danger fw-bold lh-1 mt-5">Pilihan harus lebih dari 1</h2>
          <?php } ?>
          <div class="btn_submit_left mt-3">
            <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/cetak.php?kode=<?= encrypt($kodeDiagnosa)?>" class="mr-auto btn btn_primary transition duration-700 shadow-primary btn-md mt-2 mb-3 mr-2">Cetak Hasil</a>
            <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/ulang_diagnosa.php" class="ms-auto btn btn_primary transition duration-700 shadow-primary btn-md mt-2 mb-3">Ulang Diagnosa</a>
          </div>
        <?php } else { ?>
          <h2 class="text_primary fw-bold lh-1">Gejala Penyakit</h2>
          <form class="form_glass mt-4" method="POST">
            <div class="diagnosa-form mb-3">
              <?php foreach($gejala as $g): ?>
                <div class="form-check mt-3 mb-1">
                  <input class="form-check-input checkbox" type="checkbox" name="gejala[]" value="<?= $g->kode_gejala ?>" id="checkbox<?= $g->kode_gejala ?>">
                  <label class="form-check-label" for="checkbox<?= $g->kode_gejala ?>">
                    <?= $g->nama_gejala ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="btn_submit_left">
              <button type="submit" name="submit" class="ms-auto btn btn_primary transition duration-700 shadow-primary btn-md mt-2 mb-3">Submit</button>
            </div>
          </form>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- Virus -->
  <?php include('partials/virus2.php') ?>
</main>
<?php include('partials/script.php'); ?>
