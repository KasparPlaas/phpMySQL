<?php
session_start();
require_once 'config.php';
$yhendus->set_charset("utf8mb4");

// Only admin or peaadmin allowed
if (!isset($_SESSION['kasutaja_id']) || !in_array($_SESSION['roll'], [2, 3])) {
    $_SESSION['teade'] = "Ligipääs keelatud.";
    header("Location: index.php");
    exit();
}

// Handle booking status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['broneering_id'], $_POST['status'])) {
    $id = intval($_POST['broneering_id']);
    $status = $_POST['status'];

    if (in_array($status, ['kinnitatud', 'keelatud'])) {
        $stmt = $yhendus->prepare("UPDATE broneeringud SET staatus = ? WHERE broneering_id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['teade'] = "Broneeringu staatus uuendatud.";
    }

    header("Location: admin_broneeringud.php");
    exit();
}

// Get all bookings with user and room info
$stmt = $yhendus->prepare("
    SELECT b.*, k.kasutajanimi, k.email, t.toa_nr
    FROM broneeringud b
    JOIN kasutajad k ON b.kasutaja_id = k.kasutaja_id
    JOIN toad t ON b.toa_id = t.toa_id
    ORDER BY b.broneering_id DESC
");
$stmt->execute();
$result = $stmt->get_result();
$broneeringud = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include 'header.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Broneeringute haldus</h2>

    <?php if (isset($_SESSION['teade'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['teade']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sulge"></button>
        </div>
        <?php unset($_SESSION['teade']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kasutaja</th>
                    <th>Email</th>
                    <th>Tuba</th>
                    <th>Saabumine</th>
                    <th>Lahkumine</th>
                    <th>Öid</th>
                    <th>Hind</th>
                    <th>Staatus</th>
                    <th>Tegevus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $badge = [
                    'ootel' => 'warning',
                    'pending' => 'warning',
                    'kinnitatud' => 'success',
                    'keelatud' => 'danger'
                ];
                $displayText = [
                    'ootel' => 'Ootel',
                    'pending' => 'Ootel',
                    'kinnitatud' => 'Kinnitatud',
                    'keelatud' => 'Keelatud'
                ];
                ?>
                <?php foreach ($broneeringud as $br): ?>
                    <tr>
                        <td><?= $br['broneering_id'] ?></td>
                        <td><?= htmlspecialchars($br['kasutajanimi']) ?></td>
                        <td><?= htmlspecialchars($br['email']) ?></td>
                        <td><?= htmlspecialchars($br['toa_nr']) ?></td>
                        <td><?= $br['saabumine'] ?></td>
                        <td><?= $br['lahkumine'] ?></td>
                        <td><?= $br['oode_arv'] ?></td>
                        <td><?= number_format($br['hind_kokku'], 2) ?> €</td>
                        <td>
                            <?php
                            $staatus = $br['staatus'];
                            echo "<span class='badge bg-{$badge[$staatus]} px-3 py-2'>{$displayText[$staatus]}</span>";
                            ?>
                        </td>
                        <td>
                            <?php if (in_array($br['staatus'], ['ootel', 'pending'])): ?>
                                <form method="post" class="d-flex flex-wrap gap-2 align-items-center" onsubmit="event.preventDefault(); confirmAction(this, this.status.value);">
                                    <input type="hidden" name="broneering_id" value="<?= $br['broneering_id'] ?>">
                                    <button type="submit" name="status" value="kinnitatud" class="btn btn-sm btn-outline-success">
                                        ✅ Kinnita
                                    </button>
                                    <button type="submit" name="status" value="keelatud" class="btn btn-sm btn-outline-danger">
                                        ❌ Keela
                                    </button>
                                    <?php if (!empty($br['email'])): ?>
                                        <a href="mailto:<?= htmlspecialchars($br['email']) ?>" class="btn btn-sm btn-outline-primary">
                                            📧 Kontakt
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Kontakt puudub</span>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Toiming tehtud</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($broneeringud)): ?>
                    <tr><td colspan="10" class="text-center text-muted">Broneeringuid ei leitud</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmAction(form, action) {
        const message = action === 'kinnitatud'
            ? 'Kas oled kindel, et soovid kinnitada selle broneeringu?'
            : 'Kas oled kindel, et soovid keelata selle broneeringu?';

        if (confirm(message)) {
            form.submit();
        }
    }
</script>

<?php include 'footer.php'; ?>
