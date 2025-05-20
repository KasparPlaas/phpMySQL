<?php
include 'config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['kasutajanimi'] ?? '');
    $password = $_POST['parool'] ?? '';

    if (!$username || !$password) {
        $error = "Palun sisestage kasutajanimi ja parool.";
    } else {
        $stmt = $yhendus->prepare("SELECT parool, roll FROM kasutajad WHERE kasutajanimi = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashedPasswordFromDb, $userRole);
        if ($stmt->fetch()) {
            if (password_verify($password, $hashedPasswordFromDb)) {
                // Login success - save user info to session
                $_SESSION['kasutajanimi'] = $username;
                $_SESSION['roll'] = $userRole;
                header("Location: admin/index.php"); // Redirect after login
                exit;
            } else {
                $error = "Vale parool.";
            }
        } else {
            $error = "Kasutajanimi ei leitud.";
        }
        $stmt->close();
    }
}
?>

<?php include 'header.php'; ?>
<div class="container py-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Logi sisse</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="form-floating mb-3">
            <input type="text" name="kasutajanimi" class="form-control" id="kasutajanimi" placeholder="Kasutajanimi" required autofocus>
            <label for="kasutajanimi">Kasutajanimi</label>
        </div>

        <div class="form-floating mb-4">
            <input type="password" name="parool" class="form-control" id="parool" placeholder="Parool" required>
            <label for="parool">Parool</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Logi sisse</button>
    </form>

    <div class="mt-3 text-center">
        <a href="register.php">Loo uus konto</a>
    </div>
</div>
<?php include 'footer.php'; ?>
