<?php
session_start(); // Always first before any output!

include '../andmebaas/config.php'; // Needed before DB query

$vigane = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $_POST["kasutajanimi"];
    $parool = $_POST["parool"];
    $pea_meeles = isset($_POST["pea_meeles"]);

    $nimi = mysqli_real_escape_string($yhendus, $nimi); // Security against SQL injection

    $kysimus = "SELECT kasutaja_id, kasutajanimi, parool, roll, eesnimi FROM kasutajad WHERE kasutajanimi = '$nimi'";
    $vastus = mysqli_query($yhendus, $kysimus);

    if ($kasutaja = mysqli_fetch_assoc($vastus)) {
        if (password_verify($parool, $kasutaja["parool"])) {

            // Pikenda sessiooni kui vaja
            if ($pea_meeles) {
                ini_set('session.gc_maxlifetime', 86400); // 24h
                setcookie(session_name(), session_id(), time() + 86400, "/");
            }

            // Set session variables
            $_SESSION["kasutaja_id"] = $kasutaja["kasutaja_id"];
            $_SESSION["kasutajanimi"] = $kasutaja["kasutajanimi"];
            $_SESSION["eesnimi"] = $kasutaja["eesnimi"];
            $_SESSION["roll"] = $kasutaja["roll"];

            header("Location: ../index.php");
            exit;
        }
    }

    $vigane = true;
}

include '../header.php';
?>


<div class="container mt-5 mb-5" style="max-width: 400px;">
    <h2 class="mb-4">Logi sisse</h2>

    <!-- TEATED -->

    <?php if (isset($_GET['timeout'])): ?>
        <div class="alert alert-warning">Sessioon aegus, palun logi uuesti sisse.</div>
    <?php endif; ?>


    <?php if (isset($_GET["registreeritud"]) && $_GET["registreeritud"] == 1): ?>
        <div class="alert alert-success">Registreerimine õnnestus! Nüüd saad sisse logida.</div>
    <?php endif; ?>

    <?php if ($vigane): ?>
        <div class="alert alert-danger">Vale kasutajanimi või parool.</div>
    <?php endif; ?>


    <!-- Sisselogimis vorm -->

    <form method="post">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="kasutajanimi" id="kasutajanimi" placeholder="Kasutajanimi" required>
            <label for="kasutajanimi">Kasutajanimi</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="parool" id="parool" placeholder="Parool" required>
            <label for="parool">Parool</label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="pea_meeles" id="pea_meeles">
            <label class="form-check-label" for="pea_meeles">Pea mind meeles (24h)</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Logi sisse</button>

        <hr class="my-4">
        <small class="text-body-secondary">Sisselogides nõustud kasutustingimustega.</small>
    </form>
    <img src="../pildid/login.gif" class="img-fluid mt-4" alt="Random Image">
</div>

<?php include '../footer.php'; ?>
