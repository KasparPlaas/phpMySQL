<?php
// Hotelli päise fail - kaasa sessiooni haldus
include_once '../kasutaja/session.php';
$hotelli_nimi = "Eesti Hotell";
$navigatsiooni_menuu = [
    "Avaleht" => "../pealeht/index.php",
    "Toad" => "#toad",
    "Teenused" => "#teenused",
    "Kontakt" => "#kontakt"
];
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hotelli_nimi; ?></title>
    <!-- Bootstrap CSS 5.3.6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigeerimise päis -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow border-bottom">
        <div class="container">
            <!-- Logo/bränd -->
            <a class="navbar-brand fw-bold text-primary" href="../pealeht/index.php">
                <i class="bi bi-building me-2"></i><?php echo $hotelli_nimi; ?>
            </a>
            
            <!-- Mobiilse menüü nupp -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigeerimise menüü -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Peamised navigeerimise lingid -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach($navigatsiooni_menuu as $nimi => $link): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="<?php echo htmlspecialchars($link); ?>">
                            <?php echo htmlspecialchars($nimi); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                    
                    <!-- Broneerimise nupp klientidele -->
                    <?php if ($on_sisse_logitud && $kasutaja_roll === 'klient'): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="../klient/broneeri.php">
                            <i class="bi bi-calendar-plus me-1"></i>
                            Broneerima
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Kasutaja menüü -->
                <ul class="navbar-nav">
                    <?php if ($on_sisse_logitud): ?>
                        <!-- Sisse logitud kasutaja menüü -->
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle btn btn-outline-primary" 
                                    id="kasutajaMenuu" 
                                    data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                <?php echo htmlspecialchars($praegune_kasutaja); ?>
                                <?php if ($kasutaja_roll === 'admin'): ?>
                                    <span class="badge bg-warning text-dark ms-2">Admin</span>
                                <?php elseif ($kasutaja_roll === 'haldur'): ?>
                                    <span class="badge bg-info text-dark ms-2">Haldur</span>
                                <?php endif; ?>
                            </button>
                            
                            <!-- Rippmenüü -->
                            <ul class="dropdown-menu dropdown-menu-end">
                                <!-- Profiili link -->
                                <li>
                                    <a class="dropdown-item" href="../kasutaja/profiil.php">
                                        <i class="bi bi-person-gear me-2"></i>
                                        Profiil
                                    </a>
                                </li>
                                
                                <?php if ($kasutaja_roll === 'klient'): ?>
                                    <!-- Kliendi menüü -->
                                    <li>
                                        <a class="dropdown-item" href="../klient/broneeringud.php">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            Minu Broneeringud
                                        </a>
                                    </li>
                                    
                                <?php elseif ($kasutaja_roll === 'admin'): ?>
                                    <!-- Admini menüü -->
                                    <li>
                                        <a class="dropdown-item" href="../admin/broneeringute_haldus.php">
                                            <i class="bi bi-clipboard-data me-2"></i>
                                            Broneeringute Haldus
                                        </a>
                                    </li>
                                    
                                <?php elseif ($kasutaja_roll === 'haldur'): ?>
                                    <!-- Halduri menüü -->
                                    <li>
                                        <a class="dropdown-item" href="../admin/kasutajahaldus.php">
                                            <i class="bi bi-people-fill me-2"></i>
                                            Kasutajate Haldus
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="../admin/broneeringute_haldus.php">
                                            <i class="bi bi-clipboard-data me-2"></i>
                                            Broneeringute Haldus
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <!-- Eraldaja -->
                                <li><hr class="dropdown-divider"></li>
                                
                                <!-- Väljalogimine -->
                                <li>
                                    <a class="dropdown-item text-danger" href="../kasutaja/logout.php">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logi Välja
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                    <?php else: ?>
                        <!-- Mitte sisse logitud - sisselogimise/registreerimise nupud -->
                        <li class="nav-item me-2">
                            <a class="nav-link" href="../kasutaja/login.php">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Logi Sisse
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="../kasutaja/register.php">
                                <i class="bi bi-person-plus me-1"></i>
                                Registreeru
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>