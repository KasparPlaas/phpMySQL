<?php
include 'config.php';
session_start();

$veateade = '';
$registreeritud = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutajanimi = $_POST['kasutajanimi'];
    $parool = $_POST['parool'];
    $eesnimi = $_POST['eesnimi'];
    $perenimi = $_POST['perenimi'];
    $sugu = $_POST['sugu'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $isikukood = $_POST['isikukood'];
    $noustus = isset($_POST['tingimused']);

    if (
        empty($kasutajanimi) || empty($parool) || empty($eesnimi) || empty($perenimi) ||
        empty($email) || empty($telefon) || empty($isikukood)
    ) {
        $veateade = "Kõik väljad on kohustuslikud.";
    } elseif (!$noustus) {
        $veateade = "Peate nõustuma kasutustingimustega.";
    } else {
        $kontroll = mysqli_query($yhendus, "SELECT * FROM kasutajad 
            WHERE kasutajanimi = '$kasutajanimi' OR email = '$email' OR isikukood = '$isikukood'");

        if (mysqli_num_rows($kontroll) > 0) {
            $veateade = "Kasutajanimi, e-mail või isikukood on juba kasutusel.";
        } else {
            $krüpteeritudParool = password_hash($parool, PASSWORD_DEFAULT);
            $roll = 1;
            $pilt = "default.jpg";

            $sisesta = "INSERT INTO kasutajad 
                (kasutajanimi, parool, eesnimi, perenimi, sugu, email, telefon, isikukood, profiilipilt, roll) 
                VALUES ('$kasutajanimi', '$krüpteeritudParool', '$eesnimi', '$perenimi', '$sugu', '$email', '$telefon', '$isikukood', '$pilt', $roll)";

            if (mysqli_query($yhendus, $sisesta)) {
                $registreeritud = true;
            } else {
                $veateade = "Registreerimine ebaõnnestus. Proovi uuesti.";
            }
        }
    }
}
?>
<?php include 'header.php'; ?>
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-sm" style="max-width: 600px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-4 text-center">Registreeru</h2>

            <?php if (!empty($veateade)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($veateade) ?></div>
            <?php endif; ?>

            <?php if (!empty($registreeritud)): ?>
                <div class="alert alert-success">
                    Registreerimine õnnestus! <a href="login.php" class="alert-link">Logi sisse</a>.
                </div>
            <?php endif; ?>

            <?php if (!$registreeritud): ?>
                <form method="POST" novalidate>
                    <h5 class="mb-3">Sisselogimisinfo</h5>

                    <div class="form-floating mb-3">
                        <input type="text" name="kasutajanimi" class="form-control" id="kasutajanimi" placeholder="Kasutajanimi" required>
                        <label for="kasutajanimi">Kasutajanimi</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="parool" class="form-control" id="parool" placeholder="Parool" required>
                        <label for="parool">Parool</label>
                    </div>

                    <h5 class="mb-3">Isiklik info</h5>

                    <div class="form-floating mb-3">
                        <input type="text" name="eesnimi" class="form-control" id="eesnimi" placeholder="Eesnimi" required>
                        <label for="eesnimi">Eesnimi</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="perenimi" class="form-control" id="perenimi" placeholder="Perenimi" required>
                        <label for="perenimi">Perenimi</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sugu</label>
                        <select name="sugu" class="form-select" required>
                            <option value="mees">Mees</option>
                            <option value="naine">Naine</option>
                        </select>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="telefon" class="form-control" id="telefon" placeholder="Telefon" required>
                        <label for="telefon">Telefon</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" name="isikukood" class="form-control" id="isikukood" placeholder="Isikukood" required>
                        <label for="isikukood">Isikukood</label>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" name="tingimused" class="form-check-input" id="tingimused" required>
                        <label class="form-check-label" for="tingimused">Nõustun kasutustingimustega</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registreeru</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
