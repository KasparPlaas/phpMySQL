<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
session_start();

$veateade = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutajanimi = $_POST['kasutajanimi'];
    $sisestatudParool = $_POST['parool'];

    if (empty($kasutajanimi) || empty($sisestatudParool)) {
        $veateade = "Palun sisestage kasutajanimi ja parool.";
    } else {
        $stmt = $yhendus->prepare("SELECT kasutaja_id, eesnimi, kasutajanimi, parool, roll FROM kasutajad WHERE kasutajanimi = ?");
        $stmt->bind_param("s", $kasutajanimi);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $kasutaja = $result->fetch_assoc();

            if (password_verify($sisestatudParool, $kasutaja['parool'])) {
                // Set session variables
                $_SESSION['kasutaja_id'] = $kasutaja['kasutaja_id'];
                $_SESSION['user_id'] = $kasutaja['kasutaja_id']; // Match with other pages
                $_SESSION['eesnimi'] = $kasutaja['eesnimi'];
                $_SESSION['kasutajanimi'] = $kasutaja['kasutajanimi'];
                $_SESSION['roll'] = $kasutaja['roll'];

                header("Location: index.php");
                exit;
            } else {
                $veateade = "Vale kasutajanimi või parool.";
            }
        } else {
            $veateade = "Vale kasutajanimi või parool.";
        }
        $stmt->close();
    }
}
?>

<?php include 'header.php'; ?>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Logi sisse</h3>

        <?php if (!empty($veateade)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($veateade) ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-warning"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="kasutajanimi" class="form-label">Kasutajanimi</label>
                <input type="text" name="kasutajanimi" id="kasutajanimi" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="parool" class="form-label">Parool</label>
                <input type="password" name="parool" id="parool" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Logi sisse</button>
        </form>

        <div class="text-center mt-3">
            <a href="register.php">Loo uus konto</a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
