<?php
session_start();
require_once 'config.php';
$yhendus->set_charset("utf8mb4");

// User must be logged in
if (!isset($_SESSION['kasutaja_id'])) {
    header("Location: login.php");
    exit();
}

$kasutaja_id = $_SESSION['kasutaja_id'];
$teade = "";

// Get current user info
$stmt = $yhendus->prepare("SELECT kasutajanimi FROM kasutajad WHERE kasutaja_id = ?");
$stmt->bind_param("i", $kasutaja_id);
$stmt->execute();
$result = $stmt->get_result();
$kasutaja = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uus_nimi = trim($_POST['kasutajanimi'] ?? '');
    $parool1 = $_POST['parool1'] ?? '';
    $parool2 = $_POST['parool2'] ?? '';

    if ($uus_nimi === '') {
        $teade = "Kasutajanimi ei tohi olla tühi.";
    } elseif ($parool1 !== $parool2) {
        $teade = "Paroolid ei kattu.";
    } else {
        // Update username
        $stmt = $yhendus->prepare("UPDATE kasutajad SET kasutajanimi = ? WHERE kasutaja_id = ?");
        $stmt->bind_param("si", $uus_nimi, $kasutaja_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['kasutajanimi'] = $uus_nimi;

        // Update password if provided
        if (!empty($parool1)) {
            $hash = password_hash($parool1, PASSWORD_DEFAULT);
            $stmt = $yhendus->prepare("UPDATE kasutajad SET parool = ? WHERE kasutaja_id = ?");
            $stmt->bind_param("si", $hash, $kasutaja_id);
            $stmt->execute();
            $stmt->close();
        }

        $teade = "Andmed uuendatud.";
    }
}

include 'header.php';
?>

<div class="container mt-5">
    <h2>Muuda oma andmeid</h2>

    <?php if ($teade): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($teade) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-4 mb-4" style="max-width: 500px;">
        <div class="mb-3">
            <label for="kasutajanimi" class="form-label">Kasutajanimi</label>
            <input type="text" name="kasutajanimi" id="kasutajanimi" class="form-control" value="<?= htmlspecialchars($kasutaja['kasutajanimi']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="parool1" class="form-label">Uus parool (valikuline)</label>
            <input type="password" name="parool1" id="parool1" class="form-control" placeholder="Sisesta uus parool">
        </div>

        <div class="mb-3">
            <label for="parool2" class="form-label">Korda parooli</label>
            <input type="password" name="parool2" id="parool2" class="form-control" placeholder="Korda parooli">
        </div>

        <button type="submit" class="btn btn-primary">Salvesta muudatused</button>
    </form>
</div>

<?php include 'footer.php'; ?>
