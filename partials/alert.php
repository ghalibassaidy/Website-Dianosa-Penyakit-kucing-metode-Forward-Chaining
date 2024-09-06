<?php
if(isset($_SESSION['message'])) {
  $data = $_SESSION['message'];
} else {
  $data = '';
}

if($data != '' && isset($_GET['alert']) && $_GET['alert'] != ''){ ?>
  <div id="alert-msg" class="alert alert_custom <?php echo ($_GET['alert'] == 'success' ? 'success' : ($_GET['alert'] == 'error' ? 'danger' : 'warning')) ?> shadow d-flex align-items-center mb-4" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-lg" viewBox="0 0 16 16">
      <path d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0L7.005 3.1ZM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"/>
    </svg>
    <div>
      <?php echo $data ?>
    </div>
  </div>
  <script>
    $(document).ready(function () {
      window.setTimeout(function() {
        $("#alert-msg").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
        });
      }, 4000);
    });
  </script>
<?php } ?>
