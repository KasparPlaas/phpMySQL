<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['kasutajanimi'] ?? '');
    $password = $_POST['parool'] ?? '';
    $firstname = trim($_POST['eesnimi'] ?? '');
    $lastname = trim($_POST['perenimi'] ?? '');
    $gender = $_POST['sugu'] ?? 'mees';
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['telefon'] ?? '');
    $idcode = trim($_POST['isikukood'] ?? '');
    $agree = isset($_POST['tingimused']);

    // Check all required fields
    if (!$username || !$password || !$firstname || !$lastname || !$email || !$phone || !$idcode) {
        $error = "Kõik väljad on kohustuslikud.";
    } elseif (!$agree) {
        $error = "Peate nõustuma kasutustingimustega.";
    } else {
        // Prepare statement to check duplicates
        $check = $yhendus->prepare("SELECT kasutajanimi FROM kasutajad WHERE kasutajanimi = ? OR email = ? OR isikukood = ?");
        if (!$check) {
            $error = "Andmebaasi viga: " . $yhendus->error;
        } else {
            $check->bind_param("sss", $username, $email, $idcode);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Kasutajanimi, e-mail või isikukood on juba kasutusel.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $role = 1; // kylaline
                $defaultProfilePic = 'default.jpg';

                $stmt = $yhendus->prepare("INSERT INTO kasutajad 
                    (kasutajanimi, parool, eesnimi, perenimi, sugu, email, telefon, isikukood, profiilipilt, roll) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    $error = "Andmebaasi viga: " . $yhendus->error;
                } else {
                    $stmt->bind_param("sssssssssi", $username, $hashedPassword, $firstname, $lastname, $gender, $email, $phone, $idcode, $defaultProfilePic, $role);

                    if ($stmt->execute()) {
                        header("Location: login.php");
                        exit;
                    } else {
                        $error = "Registreerimine ebaõnnestus. Palun proovige uuesti.";
                    }
                    $stmt->close();
                }
            }
            $check->close();
        }
    }
}
?>

<?php include 'header.php'; ?>
<div class="container py-5" style="max-width: 600px;">
    <h2 class="mb-4">Registreeru kasutajaks</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="form-floating mb-3">
            <input type="text" name="kasutajanimi" class="form-control" id="kasutajanimi" placeholder="Kasutajanimi" required>
            <label for="kasutajanimi">Kasutajanimi</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" name="parool" class="form-control" id="parool" placeholder="Parool" required>
            <label for="parool">Parool</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" name="eesnimi" class="form-control" id="eesnimi" placeholder="Eesnimi" required>
            <label for="eesnimi">Eesnimi</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" name="perenimi" class="form-control" id="perenimi" placeholder="Perenimi" required>
            <label for="perenimi">Perenimi</label>
        </div>

        <div class="form-floating mb-3">
            <select name="sugu" class="form-select" id="sugu">
                <option value="mees" selected>Mees</option>
                <option value="naine">Naine</option>
            </select>
            <label for="sugu">Sugu</label>
        </div>

        <div class="form-floating mb-3">
            <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" name="telefon" class="form-control" id="telefon" placeholder="Telefon" required>
            <label for="telefon">Telefon</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" name="isikukood" class="form-control" id="isikukood" placeholder="Isikukood" required>
            <label for="isikukood">Isikukood</label>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="tingimused" class="form-check-input" id="tingimused" required>
            <label class="form-check-label" for="tingimused">Nõustun kasutustingimustega</label>
        </div>

        <button type="submit" class="btn btn-success w-100">Registreeru</button>
    </form>
</div>
<?php include 'footer.php'; ?>
