<?php
include '../kasutaja/session.php';
include '../andmebaas/config.php';
include '../pealeht/header.php';

// Ühenduse kontroll
if (!$yhendus) {
    die("<div class='alert alert-danger'>Viga: Andmebaasiühendus ebaõnnestus.</div>");
}

// Kasutaja õiguste kontroll - ainult sisselogitud kasutajad (klient või admin)
kontrolli_sisselogimist();

// Funktsioon broneeritud kuupäevade leidmiseks
function leiaJubaBroneeritudKuupaevad($yhendus, $toa_id) {
    $broneeritudKuupaevad = array();
    $paring = "SELECT saabumine, lahkumine FROM broneeringud WHERE toa_id = ?";
    $stmt = mysqli_prepare($yhendus, $paring);
    mysqli_stmt_bind_param($stmt, "i", $toa_id);
    mysqli_stmt_execute($stmt);
    $tulemus = mysqli_stmt_get_result($stmt);
    
    while ($rida = mysqli_fetch_assoc($tulemus)) {
        $algusKuupaev = new DateTime($rida['saabumine']);
        $loppKuupaev = new DateTime($rida['lahkumine']);
        $loppKuupaev->modify('-1 day'); // Lahkumise päeva ei arvesta
        
        $intervall = new DateInterval('P1D');
        $periood = new DatePeriod($algusKuupaev, $intervall, $loppKuupaev);
        
        foreach ($periood as $kuupaev) {
            $broneeritudKuupaevad[] = $kuupaev->format('Y-m-d');
        }
    }
    return $broneeritudKuupaevad;
}

// Funktsioon kuupäevade saadavuse kontrollimiseks
function onKuupaevSaadaval($yhendus, $toa_id, $saabumine, $lahkumine) {
    $paring = "SELECT * FROM broneeringud 
               WHERE toa_id = ? 
               AND NOT (lahkumine <= ? OR saabumine >= ?)";
    $stmt = mysqli_prepare($yhendus, $paring);
    mysqli_stmt_bind_param($stmt, "iss", $toa_id, $saabumine, $lahkumine);
    mysqli_stmt_execute($stmt);
    $tulemus = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($tulemus) == 0;
}
?>

<div class="container mt-4 mb-5">
<?php
// ETAPP 1: Kuva saadaval olevad toatüübid
if (!isset($_GET['tyyp'])) {
    echo "<div class='text-center mb-4 mb-4'>";
    echo "<h1 class='display-4 fw-bold text-primary mb-3'>Vali toa tüüp</h1>";
    echo "<p class='lead text-muted'>Vali endale sobiv toatüüp allpool olevast valikust</p>";
    echo "</div>";
    
    // Leia kõik saadaval olevad toatüübid
    $paring = "SELECT DISTINCT toa_tyyp, kirjeldus, MIN(toa_hind) AS min_hind 
               FROM toad 
               WHERE staatus = 'saadaval' 
               GROUP BY toa_tyyp";
    $tulemus = mysqli_query($yhendus, $paring);

    echo "<div class='row g-4 mb-4'>";
    while ($rida = mysqli_fetch_assoc($tulemus)) {
        echo "<div class='col-lg-4 col-md-6'>";
        echo "<div class='card h-100 shadow-sm border-0'>";
        echo "<img src='../pildid/toad/" . $rida['toa_tyyp'] . ".jpg' class='card-img-top' style='height: 200px; object-fit: cover;' alt='" . htmlspecialchars($rida['toa_tyyp']) . "'>";
        echo "<div class='card-body d-flex flex-column'>";
        echo "<h5 class='card-title text-primary'>" . htmlspecialchars($rida['toa_tyyp']) . "</h5>";
        echo "<p class='card-text flex-grow-1'>" . htmlspecialchars($rida['kirjeldus']) . "</p>";
        echo "<div class='mb-3'>";
        echo "<span class='badge bg-success fs-6 px-3 py-2'>Alates " . $rida['min_hind'] . "€ / öö</span>";
        echo "</div>";
        echo "<a href='?tyyp=" . urlencode($rida['toa_tyyp']) . "' class='btn btn-primary btn-lg'>Vali see tüüp</a>";
        echo "</div></div></div>";
    }
    echo "</div>";
    include '../pealeht/footer.php';
    exit;
}

// ETAPP 2: Kuva saadaval olevad toad valitud tüübist
$valitudTyyp = mysqli_real_escape_string($yhendus, $_GET['tyyp']);
if (!isset($_GET['tuba'])) {
    echo "<div class='mb-3'>";
    echo "<a href='../klient/broneeri' class='btn btn-outline-primary'><i class='fas fa-arrow-left'></i> Tagasi toatüüpide valikusse</a>";
    echo "</div>";
    
    echo "<div class='text-center mb-4 mt-5'>";
    echo "<h1 class='display-5 fw-bold text-primary'>Vali tuba</h1>";
    echo "<p class='lead'>Valitud tüüp: <strong>" . htmlspecialchars($valitudTyyp) . "</strong></p>";
    echo "</div>";
    
    // Leia kõik selle tüübi toad
    $paring = "SELECT * FROM toad WHERE toa_tyyp = ? AND staatus = 'saadaval'";
    $stmt = mysqli_prepare($yhendus, $paring);
    mysqli_stmt_bind_param($stmt, "s", $valitudTyyp);
    mysqli_stmt_execute($stmt);
    $tulemus = mysqli_stmt_get_result($stmt);

    echo "<div class='row justify-content-center mb-5'>";
    echo "<div class='col-lg-6'>";
    echo "<div class='card shadow border-0'>";
    echo "<div class='card-body p-4 mb-5'>";
    echo "<form method='get'>";
    echo "<input type='hidden' name='tyyp' value='" . htmlspecialchars($valitudTyyp) . "'>";
    echo "<div class='mb-3'>";
    echo "<label for='tuba' class='form-label fw-semibold'>Vali tuba:</label>";
    echo "<select name='tuba' id='tuba' class='form-select form-select-lg' required>";
    echo "<option value=''>-- Vali tuba --</option>";
    while ($rida = mysqli_fetch_assoc($tulemus)) {
        echo "<option value='" . $rida['toa_id'] . "'>Tuba nr " . $rida['toa_nr'] . " - " . $rida['toa_hind'] . "€ / öö</option>";
    }
    echo "</select>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-primary btn-lg w-100 mb-4'>Jätka broneerimisega</button>";
    echo "</form>";
    echo "</div></div></div></div>";
    include '../footer.php';
    exit;
}

// ETAPP 3: Broneeringu vorm
$toa_id = (int)$_GET['tuba'];
$paring = "SELECT * FROM toad WHERE toa_id = ? AND staatus = 'saadaval'";
$stmt = mysqli_prepare($yhendus, $paring);
mysqli_stmt_bind_param($stmt, "i", $toa_id);
mysqli_stmt_execute($stmt);
$tulemus = mysqli_stmt_get_result($stmt);
$valitudTuba = mysqli_fetch_assoc($tulemus);

if (!$valitudTuba) {
    echo "<div class='alert alert-danger'>";
    echo "<i class='fas fa-exclamation-triangle'></i> ";
    echo "Viga: Valitud tuba ei ole saadaval!";
    echo "</div>";
    include '../pealeht/footer.php';
    exit;
}

// Leia selle toa broneeritud kuupäevad
$broneeritudKuupaevad = leiaJubaBroneeritudKuupaevad($yhendus, $toa_id);

// Vormi töötlus
$vead = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saabumine = $_POST['saabumine'];
    $lahkumine = $_POST['lahkumine'];
    $makseviis = $_POST['makseviis'];
    $tingimused = isset($_POST['tingimused']) ? 1 : 0;

    // Kontrolli andmeid
    if (!$tingimused) {
        $vead[] = "Peate nõustuma broneerimistingimustega!";
    }
    if (strtotime($saabumine) >= strtotime($lahkumine)) {
        $vead[] = "Lahkumise kuupäev peab olema saabumisest hilisem!";
    }
    if (!onKuupaevSaadaval($yhendus, $toa_id, $saabumine, $lahkumine)) {
        $vead[] = "Valitud kuupäevadel on tuba juba broneeritud!";
    }

    // Kui vigasid ei ole, salvesta broneering
    if (empty($vead)) {
        $paevadeArv = (strtotime($lahkumine) - strtotime($saabumine)) / (60 * 60 * 24);
        $hindKokku = $paevadeArv * $valitudTuba['toa_hind'];

        $salvestaParing = "INSERT INTO broneeringud (kasutaja_id, toa_id, saabumine, lahkumine, staatus, hind_kokku, maksmisviis) 
                          VALUES (?, ?, ?, ?, 'töötlemisel', ?, ?)";
        $stmt = mysqli_prepare($yhendus, $salvestaParing);
        mysqli_stmt_bind_param($stmt, "iissis", $kasutaja_id, $toa_id, $saabumine, $lahkumine, $hindKokku, $makseviis);
        mysqli_stmt_execute($stmt);

        // Suuna pärast edukat broneeringut
        header("Location: ../klient/broneeringud");
        exit;
    }
}
?>

<div class="mb-3">
    <a href="../klient/broneeringud" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Tagasi tubade valikusse
    </a>
</div>

<div class="text-center mb-5 mt-5">
    <h1 class="display-5 fw-bold text-primary">Broneeri tuba</h1>
    <p class="lead text-muted">Lõpeta oma broneering ja naudi meie teenuseid</p>
</div>

<div class="row justify-content-center mb-4">
    <div class="col-lg-6">
        <div class="card shadow border-0">
            <img src="../pildid/toad/<?= $valitudTuba['toa_tyyp'] ?>.jpg" 
                 class="card-img-top" 
                 style="height: 200px; object-fit: cover;" 
                 alt="<?= $valitudTuba['toa_tyyp'] ?>">
            <div class="card-body text-center">
                <h5 class="card-title">Tuba nr <?= $valitudTuba['toa_nr'] ?> (<?= $valitudTuba['toa_tyyp'] ?>)</h5>
                <span class="badge bg-success fs-6 px-3 py-2"><?= $valitudTuba['toa_hind'] ?>€ / öö</span>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($vead)): ?>
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Broneerimisel tekkisid vead:</h5>
        <ul class="mb-0">
            <?php foreach ($vead as $viga): ?>
                <li><?= $viga ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h4 class="card-title text-center mb-4">Broneeringu üksikasjad</h4>
                
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="saabumine" class="form-label fw-semibold">Saabumise kuupäev:</label>
                            <input type="date" 
                                   name="saabumine" 
                                   id="saabumine" 
                                   class="form-control form-control-lg" 
                                   required 
                                   min="<?= date('Y-m-d') ?>" 
                                   value="<?= isset($_POST['saabumine']) ? htmlspecialchars($_POST['saabumine']) : '' ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lahkumine" class="form-label fw-semibold">Lahkumise kuupäev:</label>
                            <input type="date" 
                                   name="lahkumine" 
                                   id="lahkumine" 
                                   class="form-control form-control-lg" 
                                   required 
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                                   value="<?= isset($_POST['lahkumine']) ? htmlspecialchars($_POST['lahkumine']) : '' ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="makseviis" class="form-label fw-semibold">Makseviis:</label>
                        <select name="makseviis" id="makseviis" class="form-select form-select-lg" required>
                            <option value="">-- Vali makseviis --</option>
                            <option value="sularahas" <?= (isset($_POST['makseviis']) && $_POST['makseviis'] == 'sularahas') ? 'selected' : '' ?>>
                                💵 Sularahas kohapeal
                            </option>
                            <option value="ülekandega" <?= (isset($_POST['makseviis']) && $_POST['makseviis'] == 'ülekandega') ? 'selected' : '' ?>>
                                🏦 Pangaülekanne
                            </option>
                            <option value="kaardiga" <?= (isset($_POST['makseviis']) && $_POST['makseviis'] == 'kaardiga') ? 'selected' : '' ?>>
                                💳 Kaardimakse
                            </option>
                        </select>
                    </div>
                    
                    <div id="hinnaKalkulatsioon" class="alert alert-info text-center" style="display: none;">
                        <h5 class="alert-heading">Hinna kalkulaator</h5>
                        <p id="hinnaKuva" class="mb-0"></p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="tingimused" 
                                   id="tingimused" 
                                   class="form-check-input" 
                                   required 
                                   <?= isset($_POST['tingimused']) ? 'checked' : '' ?>>
                            <label for="tingimused" class="form-check-label">
                                Nõustun hotelli tingimustega ja kinnitan, et olen tutvunud privaatsuspoliitikaga
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-check-circle"></i> Kinnita broneering
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-warning text-center mt-4">
    <i class="fas fa-info-circle"></i> 
    Punasega märgitud kuupäevad on juba broneeritud
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Leia vajalikud elemendid
    const saabumineVali = document.getElementById('saabumine');
    const lahkumineVali = document.getElementById('lahkumine');
    const hinnaKuva = document.getElementById('hinnaKuva');
    const hinnaOsak = document.getElementById('hinnaKalkulatsioon');
    const paevaneHind = <?= $valitudTuba['toa_hind'] ?>;
    const broneeritudKuupaevad = <?= json_encode($broneeritudKuupaevad) ?>;

    // Funktsioon broneeritud kuupäevade kontrollimiseks
    function onKuupaevBroneeritud(kuupaevTekst) {
        return broneeritudKuupaevad.includes(kuupaevTekst);
    }

    // Funktsioon kuupäevade keelamiseks kalendris
    function keelaBroneeritudKuupaevad() {
        // Loo keelatud kuupäevade string
        let keelatedKuupaevad = '';
        broneeritudKuupaevad.forEach(function(kuupaev) {
            // Muuda kuupäev HTML5 date input formaati
            keelatedKuupaevad += kuupaev + ',';
        });
        
        // Lisa CSS stiil keelatud kuupäevadele
        const stiil = document.createElement('style');
        stiil.innerHTML = `
            input[type="date"]::-webkit-calendar-picker-indicator {
                cursor: pointer;
            }
            
            /* Keela broneeritud kuupäevad */
            ${broneeritudKuupaevad.map(kuupaev => 
                `input[type="date"][value="${kuupaev}"] { 
                    background-color: #ffebee !important; 
                    border-color: #f44336 !important; 
                    color: #d32f2f !important;
                }`
            ).join('\n')}
        `;
        document.head.appendChild(stiil);
    }

    // Funktsioon kuupäevade valideerimiseks
    function kontrolliKuupaevi() {
        // Kontrolli saabumise kuupäeva
        if (saabumineVali.value && onKuupaevBroneeritud(saabumineVali.value)) {
            saabumineVali.classList.add('is-invalid');
            saabumineVali.style.backgroundColor = '#ffebee';
            saabumineVali.setCustomValidity('See kuupäev on juba broneeritud!');
            
            // Kuva hoiatus
            let hoiatusTeade = saabumineVali.parentNode.querySelector('.broneeritud-hoiatus');
            if (!hoiatusTeade) {
                hoiatusTeade = document.createElement('div');
                hoiatusTeade.className = 'broneeritud-hoiatus text-danger small mt-1';
                hoiatusTeade.innerHTML = '<i class="fas fa-exclamation-circle"></i> See kuupäev on juba broneeritud!';
                saabumineVali.parentNode.appendChild(hoiatusTeade);
            }
        } else {
            saabumineVali.classList.remove('is-invalid');
            saabumineVali.style.backgroundColor = '';
            saabumineVali.setCustomValidity('');
            
            // Eemalda hoiatus
            const hoiatusTeade = saabumineVali.parentNode.querySelector('.broneeritud-hoiatus');
            if (hoiatusTeade) {
                hoiatusTeade.remove();
            }
        }
        
        // Kontrolli lahkumise kuupäeva
        if (lahkumineVali.value && onKuupaevBroneeritud(lahkumineVali.value)) {
            lahkumineVali.classList.add('is-invalid');
            lahkumineVali.style.backgroundColor = '#ffebee';
            lahkumineVali.setCustomValidity('See kuupäev on juba broneeritud!');
            
            // Kuva hoiatus
            let hoiatusTeade = lahkumineVali.parentNode.querySelector('.broneeritud-hoiatus');
            if (!hoiatusTeade) {
                hoiatusTeade = document.createElement('div');
                hoiatusTeade.className = 'broneeritud-hoiatus text-danger small mt-1';
                hoiatusTeade.innerHTML = '<i class="fas fa-exclamation-circle"></i> See kuupäev on juba broneeritud!';
                lahkumineVali.parentNode.appendChild(hoiatusTeade);
            }
        } else {
            lahkumineVali.classList.remove('is-invalid');
            lahkumineVali.style.backgroundColor = '';
            lahkumineVali.setCustomValidity('');
            
            // Eemalda hoiatus
            const hoiatusTeade = lahkumineVali.parentNode.querySelector('.broneeritud-hoiatus');
            if (hoiatusTeade) {
                hoiatusTeade.remove();
            }
        }
    }

    // Funktsioon keelatud kuupäevade kontrollimiseks enne valimist
    function kontrolliValikutKuupaev(sisendElement) {
        sisendElement.addEventListener('input', function(e) {
            if (onKuupaevBroneeritud(this.value)) {
                e.preventDefault();
                this.value = '';
                
                // Kuva popup hoiatus
                const hoiatus = document.createElement('div');
                hoiatus.className = 'alert alert-warning alert-dismissible fade show position-fixed';
                hoiatus.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
                hoiatus.innerHTML = `
                    <strong><i class="fas fa-calendar-times"></i> Kuupäev on broneeritud!</strong><br>
                    Palun valige mõni teine kuupäev.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(hoiatus);
                
                // Eemalda hoiatus 5 sekundi pärast
                setTimeout(() => {
                    if (hoiatus.parentNode) {
                        hoiatus.remove();
                    }
                }, 5000);
            }
            kontrolliKuupaevi();
        });
    }

    // Funktsioon hinna kalkuleerimiseks
    function arvutaHind() {
        const saabumine = new Date(saabumineVali.value);
        const lahkumine = new Date(lahkumineVali.value);
        
        if (saabumineVali.value && lahkumineVali.value && saabumine < lahkumine) {
            // Kontrolli, kas vahepeal on broneeritud kuupäevi
            let onBroneeritudKuupaevi = false;
            const algusKuupaev = new Date(saabumineVali.value);
            const loppKuupaev = new Date(lahkumineVali.value);
            
            for (let d = new Date(algusKuupaev); d < loppKuupaev; d.setDate(d.getDate() + 1)) {
                if (onKuupaevBroneeritud(d.toISOString().split('T')[0])) {
                    onBroneeritudKuupaevi = true;
                    break;
                }
            }
            
            if (onBroneeritudKuupaevi) {
                hinnaKuva.innerHTML = `
                    <span class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Valitud perioodis on broneeritud kuupäevi!
                    </span>
                `;
                hinnaOsak.style.display = 'block';
                hinnaOsak.className = 'alert alert-danger text-center';
            } else {
                const ajaVahe = Math.abs(lahkumine - saabumine);
                const paevadeArv = Math.ceil(ajaVahe / (1000 * 60 * 60 * 24));
                const koguHind = paevadeArv * paevaneHind;
                
                hinnaKuva.innerHTML = `
                    <strong class="fs-5 text-primary">päevade arv: ${paevadeArv}</strong><br>
                    <span class="text-muted">${paevaneHind}€ × ${paevadeArv} =</span> 
                    <strong class="fs-4 text-success">${koguHind}€ kokku</strong>
                `;
                hinnaOsak.style.display = 'block';
                hinnaOsak.className = 'alert alert-info text-center';
            }
        } else {
            hinnaOsak.style.display = 'none';
        }
    }

    // Sündmuste kuulajad
    saabumineVali.addEventListener('change', function() {
        kontrolliKuupaevi();
        arvutaHind();
        
        // Määra lahkumise minimaalne kuupäev
        if (this.value) {
            const jargminePaev = new Date(this.value);
            jargminePaev.setDate(jargminePaev.getDate() + 1);
            lahkumineVali.min = jargminePaev.toISOString().split('T')[0];
            
            // Kui lahkumise kuupäev on varem kui uus miinimum, nulli see
            if (lahkumineVali.value && new Date(lahkumineVali.value) < jargminePaev) {
                lahkumineVali.value = '';
            }
        }
    });

    lahkumineVali.addEventListener('change', function() {
        kontrolliKuupaevi();
        arvutaHind();
    });

    // Algsete kuupäevade seadmine
    function seadaAlgsedKuupaevad() {
        if (!saabumineVali.value) {
            const tana = new Date();
            saabumineVali.valueAsDate = tana;
            
            const homme = new Date();
            homme.setDate(homme.getDate() + 1);
            lahkumineVali.valueAsDate = homme;
        }
        
        kontrolliKuupaevi();
        arvutaHind();
    }

    // Käivita kõik funktsioonid
    keelaBroneeritudKuupaevad();
    kontrolliValikutKuupaev(saabumineVali);
    kontrolliValikutKuupaev(lahkumineVali);
    seadaAlgsedKuupaevad();
});
</script>

<?php include '../pealeht/footer.php'; ?>