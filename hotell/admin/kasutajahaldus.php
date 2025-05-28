<?php
// Kaasa vajalikud failid
include '../andmebaas/config.php';
include '../kasutaja/session.php';

// Kontrolli sisselogimist
if (!$on_sisse_logitud) {
    echo "<script>
        alert('Ligipääs keelatud! Teid viiakse avalehe!');
        setTimeout(function() {
            window.location.href = '../index.php';
        }, 5000);
    </script>";
    exit();
}

// Kontrolli kasutaja rolli - AINULT haldur roll lubatud
if ($kasutaja_roll !== 'haldur') {
    echo "<script>
        alert('Ligipääs keelatud! Ainult haldur roll on lubatud sellel lehel!');
        setTimeout(function() {
            window.location.href = '../index.php';
        }, 5000);
    </script>";
    exit();
}

// Kasutaja kustutamine
if (isset($_POST['kustuta_kasutaja'])) {
    $kasutaja_id = intval($_POST['kasutaja_id']);
    $kustuta_paring = "DELETE FROM kasutajad WHERE kasutaja_id = $kasutaja_id";
    
    if (mysqli_query($yhendus, $kustuta_paring)) {
        $teade = "Kasutaja edukalt kustutatud!";
    } else {
        $viga = "Viga kasutaja kustutamisel: " . mysqli_error($yhendus);
    }
}

// Kasutaja lisamine
if (isset($_POST['lisa_kasutaja'])) {
    $kasutajanimi = mysqli_real_escape_string($yhendus, $_POST['kasutajanimi']);
    $parool = hash_parool($_POST['parool']);
    $roll = mysqli_real_escape_string($yhendus, $_POST['roll']);
    $eesnimi = mysqli_real_escape_string($yhendus, $_POST['eesnimi']);
    $perenimi = mysqli_real_escape_string($yhendus, $_POST['perenimi']);
    $sugu = mysqli_real_escape_string($yhendus, $_POST['sugu']);
    $isikukood = mysqli_real_escape_string($yhendus, $_POST['isikukood']);
    
    $lisa_paring = "INSERT INTO kasutajad (kasutajanimi, parool, roll, eesnimi, perenimi, sugu, isikukood) 
                    VALUES ('$kasutajanimi', '$parool', '$roll', '$eesnimi', '$perenimi', '$sugu', '$isikukood')";
    
    if (mysqli_query($yhendus, $lisa_paring)) {
        $teade = "Kasutaja edukalt lisatud!";
    } else {
        $viga = "Viga kasutaja lisamisel: " . mysqli_error($yhendus);
    }
}

// Kasutaja muutmine
if (isset($_POST['muuda_kasutaja'])) {
    $kasutaja_id = intval($_POST['kasutaja_id']);
    $kasutajanimi = mysqli_real_escape_string($yhendus, $_POST['kasutajanimi']);
    $roll = mysqli_real_escape_string($yhendus, $_POST['roll']);
    $eesnimi = mysqli_real_escape_string($yhendus, $_POST['eesnimi']);
    $perenimi = mysqli_real_escape_string($yhendus, $_POST['perenimi']);
    $sugu = mysqli_real_escape_string($yhendus, $_POST['sugu']);
    $isikukood = mysqli_real_escape_string($yhendus, $_POST['isikukood']);
    
    $muuda_paring = "UPDATE kasutajad SET 
                     kasutajanimi='$kasutajanimi', 
                     roll='$roll', 
                     eesnimi='$eesnimi', 
                     perenimi='$perenimi', 
                     sugu='$sugu', 
                     isikukood='$isikukood' 
                     WHERE kasutaja_id=$kasutaja_id";
    
    // Kui parool on sisestatud, siis uuenda ka seda
    if (!empty($_POST['parool'])) {
        $parool = hash_parool($_POST['parool']);
        $muuda_paring = "UPDATE kasutajad SET 
                         kasutajanimi='$kasutajanimi', 
                         parool='$parool', 
                         roll='$roll', 
                         eesnimi='$eesnimi', 
                         perenimi='$perenimi', 
                         sugu='$sugu', 
                         isikukood='$isikukood' 
                         WHERE kasutaja_id=$kasutaja_id";
    }
    
    if (mysqli_query($yhendus, $muuda_paring)) {
        $teade = "Kasutaja andmed edukalt uuendatud!";
    } else {
        $viga = "Viga kasutaja andmete uuendamisel: " . mysqli_error($yhendus);
    }
}

// Kõigi kasutajate päring
$kasutajate_paring = "SELECT * FROM kasutajad ORDER BY kasutaja_id ASC";
$kasutajate_tulemus = mysqli_query($yhendus, $kasutajate_paring);

include '../pealeht/header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-16">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-dark">Kasutajahaldus</h2>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#lisaKasutajaModal">
                    Lisa kasutaja
                </button>
            </div>

            <?php if (isset($teade)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $teade; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($viga)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $viga; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Kasutajanimi</th>
                                    <th>Parool</th>
                                    <th>Roll</th>
                                    <th>Eesnimi</th>
                                    <th>Perenimi</th>
                                    <th>Sugu</th>
                                    <th>Isikukood</th>
                                    <th>Tegevused</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($kasutajate_tulemus && mysqli_num_rows($kasutajate_tulemus) > 0): ?>
                                    <?php while ($kasutaja = mysqli_fetch_assoc($kasutajate_tulemus)): ?>
                                    <tr>
                                        <td><?php echo $kasutaja['kasutaja_id']; ?></td>
                                        <td><?php echo htmlspecialchars($kasutaja['kasutajanimi']); ?></td>
                                        <td>••••••••</td>
                                        <td>
                                            <span class="badge bg-<?php echo $kasutaja['roll'] === 'admin' ? 'danger' : ($kasutaja['roll'] === 'haldur' ? 'warning' : 'secondary'); ?>">
                                                <?php echo htmlspecialchars($kasutaja['roll']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($kasutaja['eesnimi']); ?></td>
                                        <td><?php echo htmlspecialchars($kasutaja['perenimi']); ?></td>
                                        <td><?php echo htmlspecialchars($kasutaja['sugu']); ?></td>
                                        <td><?php echo htmlspecialchars($kasutaja['isikukood']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary me-3 mb-1 mt-1" 
                                                    onclick="muudaKasutaja(<?php echo $kasutaja['kasutaja_id']; ?>, '<?php echo htmlspecialchars($kasutaja['kasutajanimi']); ?>', '<?php echo htmlspecialchars($kasutaja['roll']); ?>', '<?php echo htmlspecialchars($kasutaja['eesnimi']); ?>', '<?php echo htmlspecialchars($kasutaja['perenimi']); ?>', '<?php echo htmlspecialchars($kasutaja['sugu']); ?>', '<?php echo htmlspecialchars($kasutaja['isikukood']); ?>')">
                                                Muuda
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger mb-1 mt-1" 
                                                    onclick="kustutaKasutaja(<?php echo $kasutaja['kasutaja_id']; ?>, '<?php echo htmlspecialchars($kasutaja['kasutajanimi']); ?>')">
                                                Kustuta
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Kasutajaid ei leitud</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lisa kasutaja modal -->
<div class="modal fade" id="lisaKasutajaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lisa uus kasutaja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lisaKasutajanimi" class="form-label">Kasutajanimi</label>
                                <input type="text" class="form-control" id="lisaKasutajanimi" name="kasutajanimi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lisaParool" class="form-label">Parool</label>
                                <input type="password" class="form-control" id="lisaParool" name="parool" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lisaRoll" class="form-label">Roll</label>
                                <select class="form-select" id="lisaRoll" name="roll" required>
                                    <option value="">Vali roll</option>
                                    <option value="admin">Admin</option>
                                    <option value="haldur">Haldur</option>
                                    <option value="kasutaja">Kasutaja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lisaSugu" class="form-label">Sugu</label>
                                <select class="form-select" id="lisaSugu" name="sugu" required>
                                    <option value="">Vali sugu</option>
                                    <option value="M">Mees</option>
                                    <option value="N">Naine</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lisaEesnimi" class="form-label">Eesnimi</label>
                                <input type="text" class="form-control" id="lisaEesnimi" name="eesnimi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lisaPerenimi" class="form-label">Perenimi</label>
                                <input type="text" class="form-control" id="lisaPerenimi" name="perenimi" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lisaIsikukood" class="form-label">Isikukood</label>
                        <input type="text" class="form-control" id="lisaIsikukood" name="isikukood" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tühista</button>
                    <button type="submit" class="btn btn-success" name="lisa_kasutaja">Lisa kasutaja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Muuda kasutaja modal -->
<div class="modal fade" id="muudaKasutajaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Muuda kasutaja andmeid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" id="muudaKasutajaId" name="kasutaja_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="muudaKasutajanimi" class="form-label">Kasutajanimi</label>
                                <input type="text" class="form-control" id="muudaKasutajanimi" name="kasutajanimi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="muudaParool" class="form-label">Uus parool (jäta tühjaks kui ei soovi muuta)</label>
                                <input type="password" class="form-control" id="muudaParool" name="parool">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="muudaRoll" class="form-label">Roll</label>
                                <select class="form-select" id="muudaRoll" name="roll" required>
                                    <option value="admin">Admin</option>
                                    <option value="haldur">Haldur</option>
                                    <option value="kasutaja">Kasutaja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="muudaSugu" class="form-label">Sugu</label>
                                <select class="form-select" id="muudaSugu" name="sugu" required>
                                    <option value="M">Mees</option>
                                    <option value="N">Naine</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="muudaEesnimi" class="form-label">Eesnimi</label>
                                <input type="text" class="form-control" id="muudaEesnimi" name="eesnimi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="muudaPerenimi" class="form-label">Perenimi</label>
                                <input type="text" class="form-control" id="muudaPerenimi" name="perenimi" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="muudaIsikukood" class="form-label">Isikukood</label>
                        <input type="text" class="form-control" id="muudaIsikukood" name="isikukood" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tühista</button>
                    <button type="submit" class="btn btn-primary" name="muuda_kasutaja">Kinnita muudatused</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Kustutamise kinnituse modal -->
<div class="modal fade" id="kustutaKinnitusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kinnita kustutamine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Kas olete kindel, et soovite kustutada kasutaja <strong id="kustutaKasutajaNimi"></strong>?</p>
                <p class="text-danger"><small>Seda tegevust ei saa tagasi võtta!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tühista</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" id="kustutaKasutajaId" name="kasutaja_id">
                    <button type="submit" class="btn btn-danger" name="kustuta_kasutaja">Kustuta</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Kasutaja kustutamise funktsioon
function kustutaKasutaja(kasutajaId, kasutajaNimi) {
    document.getElementById('kustutaKasutajaId').value = kasutajaId;
    document.getElementById('kustutaKasutajaNimi').textContent = kasutajaNimi;
    
    // Ava kustutamise kinnituse modal
    var kustutaModal = new bootstrap.Modal(document.getElementById('kustutaKinnitusModal'));
    kustutaModal.show();
}

// Kasutaja muutmise funktsioon
function muudaKasutaja(kasutajaId, kasutajanimi, roll, eesnimi, perenimi, sugu, isikukood) {
    // Täida vormid olemasolevatega andmetega
    document.getElementById('muudaKasutajaId').value = kasutajaId;
    document.getElementById('muudaKasutajanimi').value = kasutajanimi;
    document.getElementById('muudaRoll').value = roll;
    document.getElementById('muudaEesnimi').value = eesnimi;
    document.getElementById('muudaPerenimi').value = perenimi;
    document.getElementById('muudaSugu').value = sugu;
    document.getElementById('muudaIsikukood').value = isikukood;
    
    // Ava muutmise modal
    var muudaModal = new bootstrap.Modal(document.getElementById('muudaKasutajaModal'));
    muudaModal.show();
}

console.log('Page loaded successfully');
</script>

<?php 

include '../pealeht/footer.php';

?>