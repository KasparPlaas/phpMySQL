<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "config.php"; // Include this if not already included in pages
?>
<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Spaa Hotell | Premium Relaxation</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- Custom CSS (optional) -->
  <style>
    body { padding-top: 70px; }
    .navbar-brand span { white-space: nowrap; }
  </style>
</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-3">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="hkhk.png" width="40" height="40" class="rounded-circle me-2 border border-primary" alt="Logo">
        <span class="fs-4 fw-semibold text-primary">HKHK Motell</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active fw-bold' : '' ?>" href="index.php">Avaleht</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'teenused.php' ? 'active fw-bold' : '' ?>" href="teenused.php">Teenused</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'meist.php' ? 'active fw-bold' : '' ?>" href="meist.php">Meist</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'kontakt.php' ? 'active fw-bold' : '' ?>" href="kontakt.php">Kontakt</a>
          </li>

          <?php if (isset($_SESSION['roll']) && $_SESSION['roll'] == 1): ?>
            <li class="nav-item">
              <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'broneerima.php' ? 'active fw-bold' : '' ?>" href="broneerima.php">Broneeri tuba</a>
            </li>
          <?php endif; ?>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <!-- Language dropdown -->
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-translate me-1"></i> ET
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item active" href="#">Eesti</a></li>
              <li><a class="dropdown-item" href="#">English</a></li>
              <li><a class="dropdown-item" href="#">Русский</a></li>
            </ul>
          </div>

          <!-- User Dropdown -->
          <?php if (isset($_SESSION['eesnimi'], $_SESSION['roll'])): ?>
            <div class="dropdown">
              <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>
                <?= htmlspecialchars($_SESSION['eesnimi']) ?>
                <?php
                  if (in_array($_SESSION['roll'], [2,3])) {
                    $res = $yhendus->query("SELECT COUNT(*) AS c FROM broneeringud WHERE staatus='pending'");
                    $c = $res->fetch_assoc()['c'] ?? 0;
                    if ($c > 0) echo '<span class="badge bg-danger ms-1">' . $c . '</span>';
                  }
                ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="andmed.php"><i class="bi bi-person me-2"></i>Minu andmed</a></li>

                <?php if ($_SESSION['roll'] == 1): ?>
                  <li><a class="dropdown-item" href="broneeringud.php"><i class="bi bi-calendar-check me-2"></i>Minu broneeringud</a></li>
                <?php endif; ?>

                <?php if (in_array($_SESSION['roll'], [2,3])): ?>
                  <li><a class="dropdown-item" href="broneeringud.php"><i class="bi bi-calendar-check me-2"></i>Broneeringud</a></li>
                  <li><a class="dropdown-item text-warning" href="admin_broneeringud.php"><i class="bi bi-speedometer2 me-2"></i>Broneeringute haldus</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['roll'] == 3): ?>
                  <li><a class="dropdown-item text-danger" href="oigused.php"><i class="bi bi-person-gear me-2"></i>Õiguste haldus</a></li>
                <?php endif; ?>

                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logi välja</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="login.php" class="btn btn-outline-primary"><i class="bi bi-box-arrow-in-right me-1"></i>Logi sisse</a>
            <a href="register.php" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Registreeru</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
