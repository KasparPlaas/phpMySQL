<?php
// Kaasame sessiooni haldamise faili
include '../kasutaja/session.php';

// Kontrollime andmebaasi ühendust
if (!$yhendus) {
    die("Andmebaasi ühendus ebaõnnestus: " . mysqli_connect_error());
}

// Kontrollime kasutaja õigusi - ainult tavakasutajad (roll 'klient')
if (!$on_sisse_logitud || $kasutaja_roll != 'klient') {
    header("Location: ../pealeht/index.php");
    exit;
}

// Töötleme broneeringu tühistamise päringut
if (isset($_POST['tyhista_broneering']) && isset($_POST['broneering_id'])) {
    $broneering_id = intval($_POST['broneering_id']);
    
    // Kontrollime, kas broneering kuulub kasutajale
    $kontrolli_paring = "SELECT * FROM broneeringud WHERE broneering_id = $broneering_id AND kasutaja_id = $kasutaja_id";
    $kontrolli_tulemus = mysqli_query($yhendus, $kontrolli_paring);
    
    if (mysqli_num_rows($kontrolli_tulemus) > 0) {
        $broneering = mysqli_fetch_assoc($kontrolli_tulemus);
        $saabumine = new DateTime($broneering['saabumine']);
        $tana = new DateTime();
        $paevi_jarel = $tana->diff($saabumine)->days;
        
        // Kontrollime tühistamise tingimusi
        if ($saabumine > $tana && $paevi_jarel > 3 && $broneering['staatus'] !== 'tühistatud') {
            $uuenda_paring = "UPDATE broneeringud SET staatus = 'tühistatud' WHERE broneering_id = $broneering_id";
            if (mysqli_query($yhendus, $uuenda_paring)) {
                $edu_teade = "Broneering edukalt tühistatud!";
            } else {
                $vea_teade = "Viga broneeringu tühistamisel: " . mysqli_error($yhendus);
            }
        } else {
            $vea_teade = "Broneeringut ei saa enam tühistada (liiga hilja või juba tühistatud).";
        }
    } else {
        $vea_teade = "Broneeringut ei leitud või see ei kuulu teile.";
    }
}

// Päring kõigi kasutaja broneeringute saamiseks
$broneeringute_paring = "
    SELECT b.*, t.toa_nr, t.toa_tyyp
    FROM broneeringud b
    JOIN toad t ON b.toa_id = t.toa_id
    WHERE b.kasutaja_id = $kasutaja_id
    ORDER BY b.saabumine DESC
";

$broneeringute_tulemus = mysqli_query($yhendus, $broneeringute_paring);

include '../pealeht/header.php';
?>

<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            
            <!-- Lehe pealkiri -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-2 border-primary p-3 mb-3">
                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                </div>
                <h1 class="display-6 fw-bold text-dark mb-2">Minu Broneeringud</h1>
                <p class="lead text-muted">Hallake ja jälgige oma hotellibroneeringuid</p>
                <a href="../klient/broneeri.php" class="btn btn-primary btn-lg px-4 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-plus me-2"></i>Lisa Uus Broneering
                </a>
            </div>

            <!-- Edu ja veateated -->
            <?php if (isset($edu_teade)): ?>
                <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle border border-success p-2 me-3">
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Suurepärane!</h5>
                            <p class="mb-0"><?= htmlspecialchars($edu_teade) ?></p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($vea_teade)): ?>
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle border border-danger p-2 me-3">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Viga!</h5>
                            <p class="mb-0"><?= htmlspecialchars($vea_teade) ?></p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($broneeringute_tulemus) === 0): ?>
                <!-- Tühi olek - pole broneeringuid -->
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-2 border-light p-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold text-dark mb-3">Broneeringuid pole</h3>
                                <p class="text-muted mb-4 lead">
                                    Teil ei ole veel ühtegi broneeringut.<br>
                                    Alustage oma esimese broneeringu tegemisega!
                                </p>
                                <a href="../klient/broneeri.php" class="btn btn-primary btn-lg px-4 py-2 rounded-pill">
                                    <i class="fas fa-bed me-2"></i>Broneeri Esimene Tuba
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Broneeringute nimekiri -->
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                    <div class="card-header border-0 py-4 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold text-dark">Teie Broneeringud</h4>
                                <p class="text-muted mb-0">Kokku <?= mysqli_num_rows($broneeringute_tulemus) ?> broneering(ut)</p>
                            </div>
                            <div class="rounded-pill border border-primary px-3 py-2">
                                <span class="fw-bold text-primary"><?= mysqli_num_rows($broneeringute_tulemus) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-door-open me-2 text-primary"></i>Tuba
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-bed me-2 text-primary"></i>Tüüp
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-calendar-plus me-2 text-primary"></i>Saabumine
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-calendar-minus me-2 text-primary"></i>Lahkumine
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-euro-sign me-2 text-primary"></i>Hind
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-credit-card me-2 text-primary"></i>Makseviis
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>Staatus
                                    </th>
                                    <th class="px-4 py-4 border-0 fw-bold text-center">
                                        <i class="fas fa-cogs me-2 text-primary"></i>Tegevused
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($rida = mysqli_fetch_assoc($broneeringute_tulemus)):
                                    $staatus = htmlspecialchars($rida['staatus']);
                                    $staatuse_klass = match ($staatus) {
                                        'töötlemisel'   => 'warning',
                                        'tühistatud'    => 'danger',
                                        'aktsepteeritud'=> 'success',
                                        default         => 'secondary',
                                    };

                                    $tana = new DateTime();
                                    $saabumine = new DateTime($rida['saabumine']);
                                    $lahkumine = new DateTime($rida['lahkumine']);
                                    $paevi_jarel = $tana->diff($saabumine)->days;
                                    $saab_tyhistada = $saabumine > $tana && $paevi_jarel > 3 && $staatus !== 'tühistatud';
                                    
                                    // Kuupäevade vormindamine
                                    $saabumine_vormindatud = $saabumine->format('d.m.Y');
                                    $lahkumine_vormindatud = $lahkumine->format('d.m.Y');
                                    $oode_arv = $saabumine->diff($lahkumine)->days;
                                ?>
                                <tr class="align-middle">
                                    <td class="px-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle border border-primary p-2 me-3">
                                                <i class="fas fa-door-open text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($rida['toa_nr']) ?></h6>
                                                <small class="text-muted">Tuba</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="badge rounded-pill px-3 py-2 border border-info text-info">
                                            <?= htmlspecialchars($rida['toa_tyyp']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?= $saabumine_vormindatud ?></h6>
                                            <?php if ($saabumine > $tana): ?>
                                                <small class="text-muted"><?= $paevi_jarel ?> päeva pärast</small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?= $lahkumine_vormindatud ?></h6>
                                            <small class="text-muted"><?= $oode_arv ?> ööd</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <h5 class="mb-0 fw-bold text-success">
                                            <?= htmlspecialchars($rida['hind_kokku']) ?>€
                                        </h5>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="text-capitalize fw-semibold">
                                            <?= htmlspecialchars($rida['maksmisviis']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="badge bg-<?= $staatuse_klass ?> px-3 py-2 rounded-pill">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                            <?= ucfirst($staatus) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <?php if ($staatus !== 'tühistatud'): ?>
                                            <?php if ($saab_tyhistada): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="broneering_id" value="<?= $rida['broneering_id'] ?>">
                                                    <button type="submit" name="tyhista_broneering" 
                                                            class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                                            onclick="return kinnita_tyhistamine()">
                                                        <i class="fas fa-times me-1"></i>Tühista
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" 
                                                        disabled title="Broneeringut ei saa enam tühistada">
                                                    <i class="fas fa-ban me-1"></i>Tühista
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">
                                                <i class="fas fa-check-circle"></i> Tühistatud
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Informatsioon ja abi -->
                <div class="row mt-5">
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="rounded-circle border border-info p-2 me-3">
                                        <i class="fas fa-info-circle text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Tühistamise Tingimused</h5>
                                        <p class="text-muted mb-0">
                                            Broneeringuid saab tühistada tasuta kuni 3 päeva enne saabumist. 
                                            Hilisema tühistamise korral võidakse rakendada tasu.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="rounded-circle border border-warning p-2 me-3">
                                        <i class="fas fa-question-circle text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Vajate Abi?</h5>
                                        <p class="text-muted mb-3">
                                            Küsimuste korral võtke meiega julgelt ühendust.
                                        </p>
                                        <a href="tel:+372123456" class="btn btn-outline-primary btn-sm rounded-pill me-2">
                                            <i class="fas fa-phone me-1"></i>Helista
                                        </a>
                                        <a href="mailto:info@hotell.ee" class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-envelope me-1"></i>Kirjuta
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Kinnituse funktsioon broneeringu tühistamiseks
function kinnita_tyhistamine() {
    return confirm('Oled kindel, et soovid selle broneeringu tühistada?\n\nSeda tegevust ei saa tagasi võtta.');
}

// Automaatne teadete peitmine 5 sekundi pärast
document.addEventListener('DOMContentLoaded', function() {
    const teated = document.querySelectorAll('.alert[role="alert"]');
    teated.forEach(function(teade) {
        setTimeout(function() {
            const sulgemise_nupp = teade.querySelector('.btn-close');
            if (sulgemise_nupp) {
                sulgemise_nupp.click();
            }
        }, 5000);
    });
});
</script>

<?php include '../pealeht/footer.php'; ?>