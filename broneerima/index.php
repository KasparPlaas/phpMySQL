<?php
include '../header.php'; // Assumes $kasutaja_id and $yhendus are defined

// Fetch room types with availability count
$tyypide_tulemus = mysqli_query($yhendus, "
    SELECT toa_tyyp, COUNT(*) AS saadaval_kokku 
    FROM toad 
    WHERE saadaval = 1 
    GROUP BY toa_tyyp
");

// Fetch all available rooms
$toad_tulemus = mysqli_query($yhendus, "SELECT toa_id, toa_tyyp, toa_nr, hind FROM toad WHERE saadaval = 1");

// Group by room type for JS
$toad_tyypide_jargi = [];
while ($tuba = mysqli_fetch_assoc($toad_tulemus)) {
    $toad_tyypide_jargi[$tuba['toa_tyyp']][] = $tuba;
}

$sõnum = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $toa_id = intval($_POST['toa_id'] ?? 0);
    $saabumine = $_POST['saabumine'] ?? '';
    $lahkumine = $_POST['lahkumine'] ?? '';
    $maksmisviis = $_POST['maksmisviis'] ?? '';
    $noustun = isset($_POST['noustun']);

    if (!$noustun) {
        $sõnum = 'Peate nõustuma tingimustega!';
    } elseif (!$toa_id || !$saabumine || !$lahkumine || !$maksmisviis) {
        $sõnum = 'Palun täitke kõik väljad!';
    } elseif (strtotime($saabumine) < strtotime(date('Y-m-d')) || strtotime($lahkumine) <= strtotime($saabumine)) {
        $sõnum = 'Palun valige kehtivad tulevased kuupäevad!';
    } else {
        $res = mysqli_query($yhendus, "SELECT hind, toa_nr FROM toad WHERE toa_id = $toa_id AND saadaval = 1 LIMIT 1");
        if ($res && $tuba = mysqli_fetch_assoc($res)) {
            $hind = $tuba['hind'];
            $toa_nr = $tuba['toa_nr'];

            $päevad = (strtotime($lahkumine) - strtotime($saabumine)) / (60*60*24);

            $allahindlus = 0;
            if ($päevad > 30) $allahindlus = 0.20;
            elseif ($päevad > 14) $allahindlus = 0.15;
            elseif ($päevad > 7) $allahindlus = 0.10;
            elseif ($päevad > 3) $allahindlus = 0.05;

            $hind_kokku = (int) round($hind * $päevad * (1 - $allahindlus));

            $staatus = ($maksmisviis === 'ülekandega') ? 'töötlemisel' : 'makstud';

            $stmt = $yhendus->prepare("
                INSERT INTO broneeringud 
                    (kasutaja_id, toa_id, saabumine, lahkumine, maksmisviis, staatus, hind_kokku, loodud) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("iissssi", $kasutaja_id, $toa_id, $saabumine, $lahkumine, $maksmisviis, $staatus, $hind_kokku);

            if ($stmt->execute()) {
                // ✅ Mark room as unavailable
                $update_stmt = $yhendus->prepare("UPDATE toad SET saadaval = 0 WHERE toa_id = ?");
                $update_stmt->bind_param("i", $toa_id);
                $update_stmt->execute();
                $update_stmt->close();

                header("Location: ../broneeringud");
                exit;
            } else {
                $sõnum = "Broneeringu salvestamine ebaõnnestus: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $sõnum = "Valitud tuba ei leitud või pole enam saadaval!";
        }
    }
}
?>

<!-- HTML + FORM START -->
<div class="container mt-4 mb-5">
    <h3>Broneeri tuba</h3>

    <?php if ($sõnum): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($sõnum) ?></div>
    <?php endif; ?>

    <form method="post" id="broneeringuvorm">
        <div class="mb-3">
            <label for="toa_tyyp" class="form-label">Toa tüüp</label>
            <select id="toa_tyyp" class="form-select" required>
                <option value="">Vali tüüp</option>
                <?php while ($tyyp = mysqli_fetch_assoc($tyypide_tulemus)): ?>
                    <option value="<?= htmlspecialchars($tyyp['toa_tyyp']) ?>">
                        <?= htmlspecialchars($tyyp['toa_tyyp']) ?> (Saadaval: <?= $tyyp['saadaval_kokku'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3 d-none" id="toa_nr_div">
            <label for="toa_id" class="form-label">Toa number</label>
            <select name="toa_id" id="toa_id" class="form-select" required>
                <option value="">Vali toa number</option>
            </select>
        </div>

        <div class="mb-3 d-none" id="saabumine_div">
            <label for="saabumine" class="form-label">Saabumise kuupäev</label>
            <input type="date" name="saabumine" id="saabumine" class="form-control" min="<?= date('Y-m-d') ?>" required autocomplete="off">
        </div>

        <div class="mb-3 d-none" id="lahkumine_div">
            <label for="lahkumine" class="form-label">Lahkumise kuupäev</label>
            <input type="date" name="lahkumine" id="lahkumine" class="form-control" min="<?= date('Y-m-d') ?>" required autocomplete="off">
        </div>

        <div class="mb-3 d-none" id="hind_kokku_div">
            <strong>Hind kokku:</strong> <span id="hind_kokku">0</span> €
        </div>

        <div class="mb-3 d-none" id="makseviis_div">
            <label class="form-label">Makseviis</label><br>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="maksmisviis" value="sularahas" id="sularahas" required>
                <label for="sularahas" class="form-check-label">
                    Sularahas kohapeal<br><small class="text-muted">Kui ei ilmu kohale, trahv 20€</small>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="maksmisviis" value="ülekandega" id="ulekandega" required>
                <label for="ulekandega" class="form-check-label">
                    Ülekandega<br><small class="text-muted">Maksmata jääb olekuks "töötlemisel"</small>
                </label>
            </div>
        </div>

        <div class="form-check mb-3 d-none" id="noustun_div">
            <input class="form-check-input" type="checkbox" name="noustun" id="noustun" required>
            <label for="noustun" class="form-check-label">
                Nõustun tingimustega: trahv sularahas kui ei ilmu kohale märgitud kuupäevaks
            </label>
        </div>

        <button type="submit" class="btn btn-primary d-none" id="broneeri_btn">Broneeri</button>
        <a href="../index.php" class="btn btn-secondary d-none" id="loobu_btn">Loobu</a>
    </form>
</div>

<!-- JS -->
<script>
    const ruumid = <?= json_encode($toad_tyypide_jargi, JSON_HEX_TAG) ?>;

    const toaTyyp = document.getElementById('toa_tyyp');
    const toaNrDiv = document.getElementById('toa_nr_div');
    const toaId = document.getElementById('toa_id');
    const saabumineDiv = document.getElementById('saabumine_div');
    const lahkumineDiv = document.getElementById('lahkumine_div');
    const hindKokkuDiv = document.getElementById('hind_kokku_div');
    const hindKokkuSpan = document.getElementById('hind_kokku');
    const makseviisDiv = document.getElementById('makseviis_div');
    const noustunDiv = document.getElementById('noustun_div');
    const broneeriBtn = document.getElementById('broneeri_btn');
    const loobuBtn = document.getElementById('loobu_btn');

    function peidaEdasised() {
        saabumineDiv.classList.add('d-none');
        lahkumineDiv.classList.add('d-none');
        hindKokkuDiv.classList.add('d-none');
        makseviisDiv.classList.add('d-none');
        noustunDiv.classList.add('d-none');
        broneeriBtn.classList.add('d-none');
        loobuBtn.classList.add('d-none');
        hindKokkuSpan.textContent = '0';
        document.getElementById('saabumine').value = '';
        document.getElementById('lahkumine').value = '';
    }

    toaTyyp.addEventListener('change', () => {
        const tyyp = toaTyyp.value;
        toaId.innerHTML = '<option value="">Vali toa number</option>';
        peidaEdasised();

        if (tyyp && ruumid[tyyp]) {
            ruumid[tyyp].forEach(room => {
                toaId.innerHTML += `<option value="${room.toa_id}" data-hind="${room.hind}">Toa nr: ${room.toa_nr} | Hind: ${room.hind}€ / öö</option>`;
            });
            toaNrDiv.classList.remove('d-none');
        } else {
            toaNrDiv.classList.add('d-none');
        }
    });

    toaId.addEventListener('change', () => {
        peidaEdasised();
        if (toaId.value) saabumineDiv.classList.remove('d-none');
    });

    document.getElementById('saabumine').addEventListener('change', () => {
        const saabumineDate = new Date(document.getElementById('saabumine').value);
        const minLahkumine = new Date(saabumineDate.getTime() + 24*60*60*1000);
        document.getElementById('lahkumine').min = minLahkumine.toISOString().split('T')[0];
        document.getElementById('lahkumine').value = '';
        lahkumineDiv.classList.remove('d-none');
        hindKokkuDiv.classList.add('d-none');
        makseviisDiv.classList.add('d-none');
        noustunDiv.classList.add('d-none');
        broneeriBtn.classList.add('d-none');
        loobuBtn.classList.add('d-none');
    });

    document.getElementById('lahkumine').addEventListener('change', () => {
        const saabumine = document.getElementById('saabumine').value;
        const lahkumine = document.getElementById('lahkumine').value;
        if (saabumine && lahkumine && lahkumine > saabumine) {
            const päevad = (new Date(lahkumine) - new Date(saabumine)) / (1000 * 60 * 60 * 24);
            const hind_öö = parseFloat(toaId.options[toaId.selectedIndex].getAttribute('data-hind')) || 0;

            let allahindlus = 0;
            if (päevad > 30) allahindlus = 0.20;
            else if (päevad > 14) allahindlus = 0.15;
            else if (päevad > 7) allahindlus = 0.10;
            else if (päevad > 3) allahindlus = 0.05;

            const hindKokku = hind_öö * päevad * (1 - allahindlus);
            hindKokkuSpan.textContent = hindKokku.toFixed(2);
            hindKokkuDiv.classList.remove('d-none');
            makseviisDiv.classList.remove('d-none');
            noustunDiv.classList.remove('d-none');
            broneeriBtn.classList.remove('d-none');
            loobuBtn.classList.remove('d-none');
        }
    });
</script>

<?php include '../footer.php'; ?>
