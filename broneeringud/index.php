<?php
session_start();
include '../andmebaas/config.php';

// Kontrollime, kas kasutaja on sisse logitud
if (!isset($_SESSION['kasutaja_id'])) {
    header("Location: ../login/index.php");
    exit;
}

// Kontrollime, kas kasutajal on roll 1 (tavakasutaja)
if (!isset($_SESSION['roll']) || $_SESSION['roll'] != 1) {
    include '../header.php';
    echo "<div class='container mt-5'><div class='alert alert-danger'>Ligipääs keelatud! Ainult tavakasutajale (roll 1).</div></div>";
    include '../footer.php';
    exit;
}

// Võtame kasutaja sessioonist
$kasutaja_id = intval($_SESSION['kasutaja_id']);

include '../header.php';

// Tühistamise loogika
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tühista_id'])) {
    $id = intval($_POST['tühista_id']);

    $kontroll = mysqli_query($yhendus, "
        SELECT saabumine FROM bookings 
        WHERE broneering_id = $id 
          AND kasutaja_id = $kasutaja_id 
          AND staatus != 'tühistatud'
        LIMIT 1
    ");

    if ($rida = mysqli_fetch_assoc($kontroll)) {
        $vahe = (strtotime($rida['saabumine']) - time()) / (60 * 60 * 24);
        if ($vahe >= 3) {
            mysqli_query($yhendus, "UPDATE bookings SET staatus='tühistatud' WHERE broneering_id = $id");
        }
    }
}

// Broneeringute küsimine
$tulemus = mysqli_query($yhendus, "
    SELECT b.*, t.toa_nr 
    FROM bookings b
    JOIN toad t ON b.toa_id = t.toa_id
    WHERE b.kasutaja_id = $kasutaja_id
    ORDER BY b.saabumine DESC
");
?>

<div class="container mt-5 mb-5">
    <h3 class="mb-4">Minu broneeringud</h3>

    <?php if (mysqli_num_rows($tulemus) === 0): ?>
        <div class="text-center p-5 border rounded shadow-sm bg-light">
            <h4 class="mb-4">Teil ei ole veel broneeringuid.</h4>
            <a href="../broneerima" class="btn btn-primary btn-lg">Broneeri tuba</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Toa nr</th>
                        <th>Saabumine</th>
                        <th>Lahkumine</th>
                        <th>Hind (€)</th>
                        <th>Makseviis</th>
                        <th>Staatus</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($broneering = mysqli_fetch_assoc($tulemus)):
                        $saabumine = $broneering['saabumine'];
                        $vahe = (strtotime($saabumine) - time()) / (60 * 60 * 24);
                        $liiga_hiline = $vahe < 3;
                        $staatus = $broneering['staatus'];

                        $badgeClass = match ($staatus) {
                            'aktsepteeritud' => 'success',
                            'tühistatud' => 'danger',
                            'töötlemisel' => 'warning',
                            default => 'secondary',
                        };
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($broneering['toa_nr']) ?></td>
                        <td><?= htmlspecialchars($broneering['saabumine']) ?></td>
                        <td><?= htmlspecialchars($broneering['lahkumine']) ?></td>
                        <td><?= intval($broneering['hind_kokku']) ?></td>
                        <td><?= htmlspecialchars($broneering['maksmisviis']) ?></td>
                        <td><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($staatus) ?></span></td>
                        <td>
                            <?php if ($staatus !== 'tühistatud' && !$liiga_hiline): ?>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="tühista_id" value="<?= $broneering['broneering_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Tühista</button>
                                </form>
                            <?php elseif ($staatus !== 'tühistatud'): ?>
                                <button type="button" class="btn btn-sm btn-danger" disabled data-bs-toggle="tooltip" title="Liiga hilja tühistamiseks!">
                                    Tühista
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltips.forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>

<?php include '../footer.php'; ?>
