<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mattias Elmers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>

<?php
// lehe kontroll
if (!empty($_GET['leht'])) {
    $leht = $_GET['leht'];
    $lubatud = array('Avaleht', 'minust', 'kontakt', 'admin');
    $kontroll = in_array($leht, $lubatud);
    if (!$kontroll) {
        $leht = 'Avaleht';
    }
} else {
    $leht = 'Avaleht'; 
}

// Suvlaine pilt
$taustad = array("img/banner1.jpg", "img/banner2.jpg", "img/banner3.jpg");
$taust = $taustad[array_rand($taustad)];
?>

<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-dark position-absolute top-0 start-0 end-0 bg-transparent">
  <div class="container">

    <a class="navbar-brand text-white" href="index.php?leht=Avaleht">Mattias Elmers</a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-white" href="index.php?leht=Avaleht">Avaleht</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="index.php?leht=minust">Minust</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="index.php?leht=kontakt">Kontakt</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="index.php?leht=admin">Admin</a></li>
      </ul>
      </div>
  </div>
</nav>

<!-- jumbo -->
<div class="d-flex align-items-center justify-content-center text-white text-center" style="height: 400px; background-image: url('<?php echo $taust; ?>'); background-size: cover; background-position: center;">
  <div>
    <h1 class="display-4 fw-bold">Praktika Hispaanias</h1>
    <p class="lead">Minu välispraktika HKHKs</p>
  </div>
</div>

<!-- sisu -->
<div class="container my-5">
  <?php
  if (file_exists($leht . '.php')) {
      include($leht . '.php');
  } else {
      echo '<h2 class="text-center">Leht pole saadaval</h2>';
  }
  ?>
</div>


<footer class="text-center pt-4 mt-5 border-top">
  <p class="mb-4">Mattias Elmers</p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>