<?php
session_start();
require_once 'config.php';
$yhendus->set_charset("utf8mb4");

// Check login
if (!isset($_SESSION['kasutaja_id']) || !isset($_SESSION['roll'])) {
    $_SESSION['teade'] = "Palun logige sisse.";
    header("Location: index.php");
    exit();
}

$kasutaja_id = $_SESSION['kasutaja_id'];

// Handle cancellation POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['broneering_id'])) {
    $broneering_id = intval($_POST['broneering_id']);

    // Check if booking belongs to user and is cancelable (>4 days before arrival)
    $stmt = $yhendus->prepare("
        SELECT b.broneering_id, b.saabumine 
        FROM broneeringud b 
        WHERE b.broneering_id = ? AND b.kasutaja_id = ? AND b.staatus = 'broneeritud'
    ");
    $stmt->bind_param("ii", $broneering_id, $kasutaja_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $saabumine);
        $stmt->fetch();
        $stmt->close();

        $today = new DateTime();
        $arrivalDate = new DateTime($saabumine);
        $diffDays = $today->diff($arrivalDate)->days;

        if ($today < $arrivalDate && $diffDays > 4) {
            // Allow cancel
            $stmt = $yhendus->prepare("UPDATE broneeringud SET staatus = 'tühistatud' WHERE broneering_id = ?");
            $stmt->bind_param("i", $broneering_id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['teade'] = "Broneering tühistatud edukalt.";
        } else {
            $_SESSION['teade'] = "Broneeringut saab tühistada vaid kuni 4 päeva enne saabumist.";
        }
    } else {
        $_SESSION['teade'] = "Broneeringut ei leitud või ei kuulu see teile.";
    }

    header("Location: broneeringud.php");
    exit();
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
    $broneeringud[] = [
        'broneering_id' => $broneering_id,
        'toa_nr' => $toa_nr,
        'tyyp' => $tyyp,
        'hind' => $hind,
        'saabumine' => $saabumine,
        'lahkumine' => $lahkumine,
        'staatus' => $staatus,
        'loodud' => $loodud,
    ];
}
$stmt->close();

include 'header.php';
?>

<div class="container mt-5">
    <h2>Minu broneeringud</h2>

    <?php if (isset($_SESSION['teade'])): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($_SESSION['teade']) ?></div>
        <?php unset($_SESSION['teade']); ?>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
        <?php
        $today = new DateTime();
        foreach ($broneeringud as $b):
            $arrival = new DateTime($b['saabumine']);
            $departure = new DateTime($b['lahkumine']);
            $nights = $arrival->diff($departure)->days;
            $total = $nights * $b['hind'];
            $canCancel = $today < $arrival && $today->diff($arrival)->days > 4 && $b['staatus'] === 'broneeritud';
        ?>
        <div class="col mb-4 mt-4">
            <div class="card shadow">
                <img src="toad/<?= htmlspecialchars($b['tyyp']) ?>.jpg" class="card-img-top" alt="Tuba <?= $b['tyyp'] ?>">
                <div class="card-body">
                    <h5 class="card-title">Tuba <?= htmlspecialchars($b['toa_nr']) ?> (<?= ucfirst($b['tyyp']) ?>)</h5>
                    <p class="card-text mb-1"><strong>Saabumine:</strong> <?= $b['saabumine'] ?></p>
                    <p class="card-text mb-1"><strong>Lahkumine:</strong> <?= $b['lahkumine'] ?></p>
                    <p class="card-text mb-1"><strong>Öid:</strong> <?= $nights ?></p>
                    <p class="card-text mb-1"><strong>Staatus:</strong> <?= $b['staatus'] ?></p>
                    <p class="fw-bold">Hind kokku: <?= number_format($total, 2) ?> €</p>
                    <?php if ($canCancel): ?>
                        <form method="post" onsubmit="return confirm('Kas oled kindel, et soovid selle broneeringu tühistada?');">
                            <input type="hidden" name="broneering_id" value="<?= $b['broneering_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Tühista broneering</button>
                        </form>
                    <?php else: ?>
                        <small class="text-muted">Tühistamine pole enam võimalik.</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
