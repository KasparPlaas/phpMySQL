<?php
session_start();
require_once 'config.php';
$yhendus->set_charset("utf8mb4");

// Check if user is an admin or peaadmin
if (!isset($_SESSION['kasutaja_id']) || !in_array($_SESSION['roll'], [2, 3])) {
    $_SESSION['teade'] = "Ligipääs keelatud.";
    header("Location: index.php");
    exit();
}

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kasutaja_id'], $_POST['roll'])) {
    $kasutaja_id = intval($_POST['kasutaja_id']);
    $new_roll = intval($_POST['roll']);

    // Prevent users from changing their own role
    if ($kasutaja_id !== $_SESSION['kasutaja_id'] && in_array($new_roll, [1, 2, 3])) {
        $stmt = $yhendus->prepare("UPDATE kasutajad SET roll = ? WHERE kasutaja_id = ?");
        $stmt->bind_param("ii", $new_roll, $kasutaja_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['teade'] = "Kasutaja rolli uuendati.";
    } else {
        $_SESSION['teade'] = "Rolli muutmine ebaõnnestus.";
    }

    header("Location: oigused.php");
    exit();
}

// Fetch all users
$kasutajad = [];
$result = $yhendus->query("SELECT kasutaja_id, kasutajanimi, email, roll FROM kasutajad ORDER BY roll DESC, kasutajanimi ASC");
while ($row = $result->fetch_assoc()) {
    $kasutajad[] = $row;
}

include 'header.php';
?>

<div class="container mt-5">
    <h2>Kasutajate õigused</h2>

    <?php if (isset($_SESSION['teade'])): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($_SESSION['teade']) ?></div>
        <?php unset($_SESSION['teade']); ?>
    <?php endif; ?>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kasutajanimi</th>
                    <th>Email</th>
                    <th>Roll</th>
                    <th>Muuda rolli</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kasutajad as $kasutaja): ?>
                    <tr>
                        <td><?= $kasutaja['kasutaja_id'] ?></td>
                        <td><?= htmlspecialchars($kasutaja['kasutajanimi']) ?></td>
                        <td><?= htmlspecialchars($kasutaja['email']) ?></td>
                        <td>
                            <?php
                                switch ($kasutaja['roll']) {
                                    case 1: echo "Külaline"; break;
                                    case 2: echo "Admin"; break;
                                    case 3: echo "Peaadmin"; break;
                                    default: echo "Tundmatu"; break;
                                }
                            ?>
                        </td>
                        <td>
                            <?php if ($kasutaja['kasutaja_id'] !== $_SESSION['kasutaja_id']): ?>
                                <form method="post" class="d-flex">
                                    <input type="hidden" name="kasutaja_id" value="<?= $kasutaja['kasutaja_id'] ?>">
                                    <select name="roll" class="form-select me-2">
                                        <option value="1" <?= $kasutaja['roll'] == 1 ? 'selected' : '' ?>>Külaline</option>
                                        <option value="2" <?= $kasutaja['roll'] == 2 ? 'selected' : '' ?>>Admin</option>
                                        <option value="3" <?= $kasutaja['roll'] == 3 ? 'selected' : '' ?>>Peaadmin</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Uuenda</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">Oma rolli ei saa muuta</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
