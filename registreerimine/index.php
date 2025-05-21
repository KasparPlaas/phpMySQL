<?php
include '../header.php';

$teade = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eesnimi = $_POST["eesnimi"];
    $perenimi = $_POST["perenimi"];
    $email = $_POST["email"];
    $telefon = $_POST["telefon"];
    $isikukood = $_POST["isikukood"];
    $kasutajanimi = $_POST["kasutajanimi"];
    $parool = password_hash($_POST["parool"], PASSWORD_DEFAULT);
    $pilt = "";
    $roll = 1;
    $sugu = "";

    // Väga lihtne kontroll: ainult numbrid ja pikkus
    if (!is_numeric($telefon) || !is_numeric($isikukood) || strlen($isikukood) != 11) {
        $teade = "Registreerimine ebaõnnestus!";
    } else {
        $esimene = substr($isikukood, 0, 1);
        if ($esimene % 2 == 0) {
            $sugu = "naine";
        } else {
            $sugu = "mees";
        }

        $lisamine = "INSERT INTO kasutajad (eesnimi, perenimi, sugu, email, telefon, profiilipilt, isikukood, kasutajanimi, parool, roll, loomis_aeg)
                     VALUES ('$eesnimi', '$perenimi', '$sugu', '$email', '$telefon', '$pilt', '$isikukood', '$kasutajanimi', '$parool', $roll, NOW())";

        if (mysqli_query($yhendus, $lisamine)) {
            header("Location: ../login/?registreeritud=1");
            exit;
        } else {
            $teade = "Registreerimine ebaõnnestus!";
        }
    }
}
?>

<div class="container mt-5" style="max-width: 600px;">
    <h2>Loo uus konto</h2>

    <?php if ($teade): ?>
        <div class="alert alert-danger"><?php echo $teade; ?></div>
    <?php endif; ?>

    <form method="post">
        <h5 class="mt-4">Sisselogimis info:</h5>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="kasutajanimi" placeholder="Kasutajanimi" required>
            <label for="kasutajanimi">Kasutajanimi</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="parool" placeholder="Parool" required>
            <label for="parool">Parool</label>
        </div>

        <h5 class="mt-4">Isiklikud andmed:</h5>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="eesnimi" placeholder="Eesnimi" required>
            <label for="eesnimi">Eesnimi</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="perenimi" placeholder="Perenimi" required>
            <label for="perenimi">Perenimi</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="telefon" placeholder="Telefon" required>
            <label for="telefon">Telefon</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="isikukood" placeholder="Isikukood" maxlength="11" required>
            <label for="isikukood">Isikukood</label>
        </div>
        <button class="w-100 btn btn-lg btn-success" type="submit">Registreeri</button>
    </form>
</div>

<?php include '../footer.php'; ?>