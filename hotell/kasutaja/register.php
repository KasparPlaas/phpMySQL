<?php
include '../kasutaja/session.php';

// Suuna ümber kui juba sisse logitud
if ($on_sisse_logitud) {
    header('Location: ../pealeht/index.php');
    exit();
}

$viga = '';
$edukas = '';

// Käsitle registreerimist
if (isset($_POST['registreeru'])) {
    $andmed = array_map('trim', $_POST);
    
    // Kontrolli kohustuslikud väljad
    $kohustuslikud = ['kasutajanimi', 'parool', 'eesnimi', 'perenimi', 'sugu', 'email', 'telefon', 'isikukood'];
    foreach ($kohustuslikud as $vali) {
        if (empty($andmed[$vali])) {
            $viga = 'Kõik väljad on kohustuslikud!';
            break;
        }
    }
    
    // Valideerimine
    if (!$viga) {
        if (strlen($andmed['kasutajanimi']) < 3 || strlen($andmed['kasutajanimi']) > 20) {
            $viga = 'Kasutajanimi peab olema 3-20 tähemärki!';
        } elseif (strlen($andmed['parool']) < 6) {
            $viga = 'Parool peab olema vähemalt 6 tähemärki!';
        } elseif ($andmed['parool'] !== $andmed['parool_kinnitus']) {
            $viga = 'Paroolid ei kattu!';
        } elseif (!filter_var($andmed['email'], FILTER_VALIDATE_EMAIL)) {
            $viga = 'Vigane e-posti aadress!';
        } elseif (strlen($andmed['email']) > 60) {
            $viga = 'E-post liiga pikk (max 60 tähemärki)!';
        } elseif (!preg_match('/^[0-9]{11}$/', $andmed['isikukood'])) {
            $viga = 'Isikukood peab olema 11 numbrit!';
        } elseif (strlen($andmed['telefon']) < 7 || strlen($andmed['telefon']) > 20) {
            $viga = 'Telefon peab olema 7-20 tähemärki!';
        } elseif (strlen($andmed['eesnimi']) > 20 || strlen($andmed['perenimi']) > 20) {
            $viga = 'Nimi tohib olla max 20 tähemärki!';
        }
    }
    
    // Kontrolli duplikaate
    if (!$viga) {
        $kontrollid = [
            'kasutajanimi' => 'Kasutajanimi on juba kasutusel!',
            'email' => 'E-post on juba registreeritud!',
            'isikukood' => 'Isikukood on juba registreeritud!'
        ];
        
        foreach ($kontrollid as $vali => $teade) {
            $paring = mysqli_prepare($yhendus, "SELECT kasutaja_id FROM kasutajad WHERE $vali = ?");
            mysqli_stmt_bind_param($paring, 's', $andmed[$vali]);
            mysqli_stmt_execute($paring);
            if (mysqli_fetch_assoc(mysqli_stmt_get_result($paring))) {
                $viga = $teade;
                mysqli_stmt_close($paring);
                break;
            }
            mysqli_stmt_close($paring);
        }
    }
    
    // Loo kasutaja
    if (!$viga) {
        $paring = mysqli_prepare($yhendus, 
            "INSERT INTO kasutajad (kasutajanimi, parool, eesnimi, perenimi, sugu, email, telefon, isikukood, roll, loomis_aeg) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'klient', NOW())"
        );
        
        $rahastksi_parool = password_hash($andmed['parool'], PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($paring, 'ssssssss', 
            $andmed['kasutajanimi'], 
            $rahastksi_parool, 
            $andmed['eesnimi'], 
            $andmed['perenimi'], 
            $andmed['sugu'], 
            $andmed['email'], 
            $andmed['telefon'], 
            $andmed['isikukood']
        );
        
        if (mysqli_stmt_execute($paring)) {
            $edukas = 'Konto loodud edukalt! Suuname sisselogimise lehele...';
            echo "<script>setTimeout(() => window.location.href = '../kasutaja/login.php', 2000);</script>";
        } else {
            $viga = 'Viga konto loomisel!';
        }
        mysqli_stmt_close($paring);
    }
}

include '../pealeht/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3><i class="bi bi-person-plus"></i> Loo Uus Konto</h3>
                </div>
                <div class="card-body">
                    
                    <?php if ($viga): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= $viga ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($edukas): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <?= $edukas ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="regVorm">
                        <!-- Kasutajakonto -->
                        <h5 class="text-primary mb-3">Sisselogimine</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Kasutajanimi *</label>
                            <input type="text" class="form-control" name="kasutajanimi" 
                                   value="<?= htmlspecialchars($_POST['kasutajanimi'] ?? '') ?>" required>
                            <small class="text-muted">3-20 tähemärki</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parool *</label>
                                <input type="password" class="form-control" name="parool" id="parool" required>
                                <small class="text-muted">Vähemalt 6 tähemärki</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Korda parooli *</label>
                                <input type="password" class="form-control" name="parool_kinnitus" id="paroolKinnitus" required>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Isiklikud andmed -->
                        <h5 class="text-primary mb-3">Isiklikud Andmed</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Eesnimi *</label>
                                <input type="text" class="form-control" name="eesnimi" 
                                       value="<?= htmlspecialchars($_POST['eesnimi'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Perenimi *</label>
                                <input type="text" class="form-control" name="perenimi" 
                                       value="<?= htmlspecialchars($_POST['perenimi'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sugu *</label>
                                <select class="form-select" name="sugu" required>
                                    <option value="">Vali sugu</option>
                                    <option value="mees" <?= ($_POST['sugu'] ?? '') == 'mees' ? 'selected' : '' ?>>Mees</option>
                                    <option value="naine" <?= ($_POST['sugu'] ?? '') == 'naine' ? 'selected' : '' ?>>Naine</option>
                                    <option value="muu" <?= ($_POST['sugu'] ?? '') == 'muu' ? 'selected' : '' ?>>Muu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Isikukood *</label>
                                <input type="text" class="form-control" name="isikukood" id="isikukood"
                                       value="<?= htmlspecialchars($_POST['isikukood'] ?? '') ?>" 
                                       maxlength="11" required>
                                <small class="text-muted">11 numbrit</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Kontakt -->
                        <h5 class="text-primary mb-3">Kontaktandmed</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-post *</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon *</label>
                                <input type="tel" class="form-control" name="telefon" 
                                       value="<?= htmlspecialchars($_POST['telefon'] ?? '') ?>" 
                                       placeholder="+372 12345678" required>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" name="registreeru" class="btn btn-primary btn-lg" id="regNupp">
                                <span id="nuppTekst">Loo Konto</span>
                                <span id="laadimisIkoon" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center">
                        <p>Juba on konto? <a href="../kasutaja/login.php" class="text-primary">Logi sisse</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Vorm ja elemendid
const vorm = document.getElementById('regVorm');
const nuppTekst = document.getElementById('nuppTekst');
const laadimisIkoon = document.getElementById('laadimisIkoon');
const parool = document.getElementById('parool');
const paroolKinnitus = document.getElementById('paroolKinnitus');
const isikukood = document.getElementById('isikukood');

// Vormi saatmine
vorm.addEventListener('submit', function() {
    nuppTekst.textContent = 'Töötleb...';
    laadimisIkoon.classList.remove('d-none');
});

// Parooli kinnitus
function kontrolliParoole() {
    if (paroolKinnitus.value && parool.value !== paroolKinnitus.value) {
        paroolKinnitus.setCustomValidity('Paroolid ei kattu!');
        paroolKinnitus.classList.add('is-invalid');
    } else {
        paroolKinnitus.setCustomValidity('');
        paroolKinnitus.classList.remove('is-invalid');
    }
}

paroolKinnitus.addEventListener('input', kontrolliParoole);
parool.addEventListener('input', kontrolliParoole);

// Isikukood - ainult numbrid
isikukood.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').slice(0, 11);
});
</script>

<?php include '../pealeht/footer.php'; ?>