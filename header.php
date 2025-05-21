<?php
include '../andmebaas/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sessiooni aegumine
$timeout = 600;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: ../login/index.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

// Kasutaja andmed
$kasutaja_id = $_SESSION["kasutaja_id"] ?? null;
$kasutajanimi = $_SESSION["kasutajanimi"] ?? "";
$eesnimi = $_SESSION["eesnimi"] ?? "";
$roll = $_SESSION["roll"] ?? 0;

// Optionally re-verify from DB if needed, but not required if all set correctly at login
?>

<!DOCTYPE html>
<html lang="et" data-bs-theme="auto">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HKHK Hotell</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .carousel-item img {
            width: 100%;
            height: 600px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<header class="text-bg-dark">

<!-- Peamenüü -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fs-4" href="/">HKHK Hotell</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#peamenyy" aria-controls="peamenyy" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Peamenüü valikud -->
        <div class="collapse navbar-collapse" id="peamenyy">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#">Ülevaade</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Inventar</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Kliendid</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tooted</a></li>
                <?php if ($roll == 1): ?>
                    <li class="nav-item"><a class="nav-link" href="../broneerima">Broneerima</a></li>
                <?php endif; ?>
            </ul>

            <!-- kasutaja rippmenüü -->
            <?php if ($kasutaja_id): ?>          
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuButton" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($eesnimi); ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">

                            <!-- Tavakasutaja -->
                            <?php if ($roll == 1): ?>
                                <li><a class="dropdown-item" href="../broneeringud">Minu broneeringud</a></li>

                            <!-- Admin -->
                            <?php elseif ($roll == 2): ?>
                                <li><a class="dropdown-item" href="../broneeringutehaldus">Broneeringute haldus</a></li>

                            <!-- Haldur -->
                            <?php elseif ($roll == 3): ?>
                                <li><a class="dropdown-item" href="../kasutajahaldus">Kasutajahaldus</a></li>
                                <li><a class="dropdown-item" href="../broneeringud">Broneeringute haldus</a></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item" href="../profiil">Profiil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout">Logi välja</a></li>

                        </ul>
                    </li>
                </ul>

            <!-- Külastaja -->
            <?php else: ?>
                <div class="d-flex mb-1">
                    <a href="login/" class="btn btn-outline-light me-2">Logi sisse</a>
                    <a href="registreerimine/" class="btn btn-warning">Loo konto</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
</header>
