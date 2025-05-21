<?php
session_start();
require_once 'config.php';
$yhendus->set_charset("utf8mb4");

// --- AUTHORIZATION CHECKS ---
if (!isset($_SESSION['kasutaja_id']) || !isset($_SESSION['roll'])) {
    $_SESSION['teade'] = "Suuname teid sisselogimise lehele – palun logige esmalt sisse.";
    header("Location: index.php");
    exit();
}

$roll = $_SESSION['roll'];

if ($roll == 2 || $roll == 3) {
    $_SESSION['teade'] = "Teil on administraatori õigused – suuname teid broneeringute halduse lehele.";
    header("Location: admin_broneeringud.php");
    exit();
}

if ($roll != 1) {
    $_SESSION['teade'] = "Teil puuduvad õigused sellele lehele!";
    header("Location: index.php");
    exit();
}

include 'header.php';

$kasutaja_id = $_SESSION['kasutaja_id'];

// Fetch room types
$tyypid = [];
$stmt = $yhendus->prepare("SELECT DISTINCT tyyp FROM toad WHERE saadavus = 1");
$stmt->execute();
$stmt->bind_result($tyyp);
while ($stmt->fetch()) {
    $tyypid[] = $tyyp;
}
$stmt->close();

// Fetch all available rooms
$allToad = [];
$stmt = $yhendus->prepare("SELECT toa_id, toa_nr, hind, tyyp FROM toad WHERE saadavus = 1");
$stmt->execute();
$stmt->bind_result($toa_id, $toa_nr, $hind, $tyyp);
while ($stmt->fetch()) {
    $allToad[] = ['toa_id' => $toa_id, 'toa_nr' => $toa_nr, 'hind' => $hind, 'tyyp' => $tyyp];
}
$stmt->close();

// Booking form POST handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['broneeri'])) {
    $toa_id = intval($_POST['toa_id']);
    $saabumine = $_POST['saabumine'];
    $lahkumine = $_POST['lahkumine'];

    $today = new DateTime('today');
    $arrive = DateTime::createFromFormat('Y-m-d', $saabumine);
    $depart = DateTime::createFromFormat('Y-m-d', $lahkumine);

    if (!$toa_id || !$saabumine || !$lahkumine) {
        $_SESSION['teade'] = '<div class="alert alert-danger">Palun täida kõik väljad!</div>';
    } elseif (!$arrive || !$depart || $depart <= $arrive) {
        $_SESSION['teade'] = '<div class="alert alert-danger">Palun vali korrektne saabumise ja lahkumise kuupäev!</div>';
    } elseif ($arrive < $today) {
        $_SESSION['teade'] = '<div class="alert alert-danger">Saabumise kuupäev ei saa olla minevikus!</div>';
    } else {
        $stmt = $yhendus->prepare("INSERT INTO broneeringud (kasutaja_id, toa_id, saabumine, lahkumine, staatus, loodud) VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("iiss", $kasutaja_id, $toa_id, $saabumine, $lahkumine);
        if ($stmt->execute()) {
            $_SESSION['teade'] = '<div class="alert alert-success">Broneering edukalt lisatud!</div>';
        } else {
            $_SESSION['teade'] = '<div class="alert alert-danger">Viga: broneeringut ei õnnestunud salvestada.</div>';
        }
        $stmt->close();
    }

    // Redirect after processing POST to prevent resubmission
    header("Location: broneering.php");
    exit();
}

// Display message if set
$bookingMessage = '';
if (isset($_SESSION['teade'])) {
    $bookingMessage = $_SESSION['teade'];
    unset($_SESSION['teade']);
}

// Fetch user bookings
$broneeringud = [];
$stmt = $yhendus->prepare("
    SELECT b.broneering_id, t.toa_nr, t.tyyp, t.hind, b.saabumine, b.lahkumine, b.staatus, b.loodud
    FROM broneeringud b
    JOIN toad t ON b.toa_id = t.toa_id
    WHERE b.kasutaja_id = ?
    ORDER BY b.loodud DESC
");
$stmt->bind_param("i", $kasutaja_id);
$stmt->execute();
$stmt->bind_result($broneering_id, $toa_nr, $tyyp, $hind, $saabumine, $lahkumine, $staatus, $loodud);
while ($stmt->fetch()) {
    $broneeringud[] = compact('broneering_id', 'toa_nr', 'tyyp', 'hind', 'saabumine', 'lahkumine', 'staatus', 'loodud');
}
$stmt->close();
?>

<div class="container py-5">
    <h2 class="mb-4 text-center">Broneeri tuba</h2>
    <?= $bookingMessage ?>

    <form method="post" class="mb-5 shadow p-4 rounded bg-light" id="broneeringForm">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="tyyp" class="form-label">Vali toa tüüp:</label>
                <select id="tyyp" class="form-select" required>
                    <option value="">-- Vali tüüp --</option>
                    <?php foreach ($tyypid as $tyyp): ?>
                        <option value="<?= htmlspecialchars($tyyp) ?>"><?= ucfirst(htmlspecialchars($tyyp)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="toa_id" class="form-label">Vali toa number:</label>
                <select name="toa_id" id="toa_id" class="form-select" required disabled>
                    <option value="">-- Esiteks vali tüüp --</option>
                </select>
            </div>
            <div class="col-md-4 text-center">
                <img src="" alt="Toa pilt" id="toa_pilt" class="img-fluid rounded d-none" style="max-height: 120px;" />
            </div>

            <div class="col-md-6">
                <label for="saabumine" class="form-label">Saabumise kuupäev:</label>
                <input type="date" name="saabumine" id="saabumine" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-6">
                <label for="lahkumine" class="form-label">Lahkumise kuupäev:</label>
                <input type="date" name="lahkumine" id="lahkumine" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-12 mt-3">
                <div id="hind_info" class="alert alert-info fw-semibold d-none"></div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" name="broneeri" id="broneeri_btn" class="btn btn-primary" disabled>Broneeri</button>
            </div>
        </div>
    </form>

    <h3 class="mb-4 text-center">Minu broneeringud</h3>

    <div class="text-end mb-3">
        <a href="broneeringud.php" class="btn btn-outline-danger">Tühista broneering</a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if (empty($broneeringud)): ?>
            <div class="col">
                <div class="alert alert-secondary text-center w-100">Teil pole veel broneeringuid.</div>
            </div>
        <?php else: ?>
            <?php foreach ($broneeringud as $b): ?>
                <?php
                $algus = new DateTime($b['saabumine']);
                $lopp = new DateTime($b['lahkumine']);
                $oode = $algus->diff($lopp)->days;
                $kokku = $oode * $b['hind'];

                $badgeClass = match ($b['staatus']) {
                    'pending' => 'warning',
                    'aktsepteeritud', 'accepted' => 'success',
                    'tühistatud', 'cancelled' => 'secondary',
                    default => 'info'
                };
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="toad/<?= htmlspecialchars($b['tyyp']) ?>.jpg" class="card-img-top" alt="Tuba">
                        <div class="card-body">
                            <h5 class="card-title">Tuba <?= htmlspecialchars($b['toa_nr']) ?> (<?= ucfirst($b['tyyp']) ?>)</h5>
                            <p class="card-text"><strong>Saabumine:</strong> <?= $b['saabumine'] ?></p>
                            <p class="card-text"><strong>Lahkumine:</strong> <?= $b['lahkumine'] ?></p>
                            <p class="card-text"><strong>Öid:</strong> <?= $oode ?></p>
                            <p class="card-text"><strong>Hind kokku:</strong> <?= number_format($kokku, 2) ?> €</p>
                            <span class="badge bg-<?= $badgeClass ?>">Staatus: <?= ucfirst($b['staatus']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
const allRooms = <?= json_encode($allToad, JSON_UNESCAPED_UNICODE) ?>;

const tyypSelect = document.getElementById('tyyp');
const toaSelect = document.getElementById('toa_id');
const toaPilt = document.getElementById('toa_pilt');
const saabumineInput = document.getElementById('saabumine');
const lahkumineInput = document.getElementById('lahkumine');
const hindInfo = document.getElementById('hind_info');
const broneeriBtn = document.getElementById('broneeri_btn');

// Filter room options based on selected type
tyypSelect.addEventListener('change', () => {
    const selectedTyyp = tyypSelect.value;
    toaSelect.innerHTML = '<option value="">-- Vali toa number --</option>';
    toaSelect.disabled = true;
    toaPilt.classList.add('d-none');
    hindInfo.classList.add('d-none');
    broneeriBtn.disabled = true;

    if (!selectedTyyp) return;

    const filteredRooms = allRooms.filter(t => t.tyyp === selectedTyyp);

    filteredRooms.forEach(room => {
        const opt = document.createElement('option');
        opt.value = room.toa_id;
        opt.textContent = `${room.toa_nr} – ${room.hind} € / öö`;
        opt.dataset.hind = room.hind;
        toaSelect.appendChild(opt);
    });

    toaSelect.disabled = false;

    // Show room image
    toaPilt.src = `toad/${selectedTyyp}.jpg`;
    toaPilt.classList.remove('d-none');
});

// Calculate total price and nights
function calculateTotal() {
    const selectedOption = toaSelect.options[toaSelect.selectedIndex];
    const hind = selectedOption ? parseFloat(selectedOption.dataset.hind || 0) : 0;
    const saabumine = new Date(saabumineInput.value);
    const lahkumine = new Date(lahkumineInput.value);

    if (!isNaN(saabumine) && !isNaN(lahkumine) && lahkumine > saabumine && hind > 0) {
        const nights = Math.round((lahkumine - saabumine) / (1000 * 60 * 60 * 24));
        const total = nights * hind;

        hindInfo.innerHTML = `Valitud ööde arv: <strong>${nights}</strong><br>Hind kokku: <strong>${total.toFixed(2)} €</strong>`;
        hindInfo.classList.remove('d-none');
        broneeriBtn.disabled = false;
    } else {
        hindInfo.classList.add('d-none');
        broneeriBtn.disabled = true;
    }
}

// Trigger total price calculation
toaSelect.addEventListener('change', calculateTotal);
saabumineInput.addEventListener('change', calculateTotal);
lahkumineInput.addEventListener('change', calculateTotal);
</script>

<?php include 'footer.php'; ?>
