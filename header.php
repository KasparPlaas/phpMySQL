<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spaa Hotell | Premium Relaxation</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<header>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="hkhk.png" 
                     width="40" 
                     height="40" 
                     class="rounded-circle me-2 border border-2 border-primary object-fit-cover">
                <span class="fs-4 fw-semibold text-primary">HKHK Motell</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    $navLinks = [
                        'Avaleht' => 'index.php',
                        'Teenused' => 'services.php',
                        'Paketid' => 'packages.php',
                        'Meist' => 'about.php',
                        'Kontakt' => 'contact.php'
                    ];
                    foreach ($navLinks as $label => $href): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === $href ? 'active fw-semibold' : '' ?>" href="<?= $href ?>"><?= $label ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <!-- Language Selector -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate me-1"></i> ET
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="#">Eesti</a></li>
                            <li><a class="dropdown-item" href="#">English</a></li>
                            <li><a class="dropdown-item" href="#">Русский</a></li>
                        </ul>
                    </div>

                    <!-- User Menu -->
                    <?php if (isset($_SESSION['eesnimi'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['eesnimi']) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Minu profiil</a></li>
                                <li><a class="dropdown-item" href="bookings.php"><i class="bi bi-calendar-check me-2"></i>Broneeringud</a></li>
                                <?php if (in_array($_SESSION['roll'], ['admin', 'peaadmin'])): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-warning" href="/admin/index.php"><i class="bi bi-speedometer2 me-2"></i>Admin paneel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logi välja</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="d-flex gap-2">
                            <a href="login.php" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Logi sisse
                            </a>
                            <a href="register.php" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i> Registreeru
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
