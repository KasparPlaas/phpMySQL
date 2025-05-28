<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files with error handling
try {
    if (!file_exists('../andmebaas/config.php')) {
        throw new Exception('Database config file not found');
    }
    include '../andmebaas/config.php';
    
    if (!file_exists('../kasutaja/session.php')) {
        throw new Exception('Session file not found');
    }
    include '../kasutaja/session.php';
} catch (Exception $e) {
    die("Error loading files: " . $e->getMessage());
}

// Check if user is logged in
try {
    kontrolli_sisselogimist();
} catch (Exception $e) {
    echo "<script>
        alert('Session error: " . addslashes($e->getMessage()) . "');
        window.location.href = '../pealeht/index.php';
    </script>";
    exit();
}

// Check if user has rights (admin or haldur)
if (!isset($kasutaja_roll) || ($kasutaja_roll !== 'admin' && $kasutaja_roll !== 'haldur')) {
    echo "<script>
        alert('Ligipääs puudub! Teil pole piisavalt õigusi.');
        window.location.href = '../pealeht/index.php';
    </script>";
    exit();
}

// Check database connection
if (!isset($yhendus) || !$yhendus) {
    die("Error: Database connection not established");
}

// First, let's check what columns exist in the toad table
$toad_columns = [];
try {
    $column_query = "SHOW COLUMNS FROM toad";
    $column_result = mysqli_query($yhendus, $column_query);
    if ($column_result) {
        while ($column = mysqli_fetch_assoc($column_result)) {
            $toad_columns[] = $column['Field'];
        }
    }
} catch (Exception $e) {
    // If toad table doesn't exist, continue without it
    $toad_columns = [];
}

// Action processing
$teade = '';
$teade_tyyp = '';

// Update booking
if (isset($_POST['uuenda_broneering'])) {
    try {
        $broneering_id = (int)$_POST['broneering_id'];
        $kasutaja_id = (int)$_POST['kasutaja_id'];
        $toa_id = (int)$_POST['toa_id'];
        $saabumine = $_POST['saabumine'];
        $lahkumine = $_POST['lahkumine'];
        $staatus = $_POST['staatus'];
        $hind_kokku = (int)$_POST['hind_kokku'];
        $maksmisviis = $_POST['maksmisviis'];
        
        // Validate required fields
        if (empty($saabumine) || empty($lahkumine) || empty($staatus) || empty($maksmisviis)) {
            throw new Exception('All fields are required');
        }
        
        $sql = "UPDATE broneeringud SET 
                kasutaja_id = ?, 
                toa_id = ?, 
                saabumine = ?, 
                lahkumine = ?, 
                staatus = ?, 
                hind_kokku = ?, 
                maksmisviis = ? 
                WHERE broneering_id = ?";
        
        $stmt = mysqli_prepare($yhendus, $sql);
        if (!$stmt) {
            throw new Exception('SQL prepare failed: ' . mysqli_error($yhendus));
        }
        
        mysqli_stmt_bind_param($stmt, "iissssii", $kasutaja_id, $toa_id, $saabumine, $lahkumine, $staatus, $hind_kokku, $maksmisviis, $broneering_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $teade = "Broneering edukalt uuendatud!";
            $teade_tyyp = "success";
        } else {
            throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        $teade = "Viga broneeringu uuendamisel: " . $e->getMessage();
        $teade_tyyp = "danger";
    }
}

// Delete booking
if (isset($_POST['kustuta_broneering'])) {
    try {
        $broneering_id = (int)$_POST['broneering_id'];
        
        if ($broneering_id <= 0) {
            throw new Exception('Invalid booking ID');
        }
        
        $sql = "DELETE FROM broneeringud WHERE broneering_id = ?";
        $stmt = mysqli_prepare($yhendus, $sql);
        if (!$stmt) {
            throw new Exception('SQL prepare failed: ' . mysqli_error($yhendus));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $broneering_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $teade = "Broneering edukalt kustutatud!";
            $teade_tyyp = "success";
        } else {
            throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        $teade = "Viga broneeringu kustutamisel: " . $e->getMessage();
        $teade_tyyp = "danger";
    }
}

// Search and filter bookings
$otsing = isset($_GET['otsing']) ? trim($_GET['otsing']) : '';
$staatus_filter = isset($_GET['staatus']) ? $_GET['staatus'] : '';
$kuupaev_alates = isset($_GET['kuupaev_alates']) ? $_GET['kuupaev_alates'] : '';
$kuupaev_kuni = isset($_GET['kuupaev_kuni']) ? $_GET['kuupaev_kuni'] : '';

// Build SQL query based on available columns
$select_fields = "b.*";
$join_clauses = "";
$search_conditions = [];

// Add kasutajad table if it exists
$select_fields .= ", k.kasutajanimi";
$join_clauses .= " LEFT JOIN kasutajad k ON b.kasutaja_id = k.kasutaja_id";

// Add toad table fields if they exist
if (!empty($toad_columns)) {
    $join_clauses .= " LEFT JOIN toad t ON b.toa_id = t.toa_id";
    
    // Add available room fields
    if (in_array('toa_number', $toad_columns)) {
        $select_fields .= ", t.toa_number";
    }
    if (in_array('toa_nimi', $toad_columns)) {
        $select_fields .= ", t.toa_nimi";
    }
    if (in_array('number', $toad_columns)) {
        $select_fields .= ", t.number as toa_number";
    }
    if (in_array('nimi', $toad_columns)) {
        $select_fields .= ", t.nimi as toa_nimi";
    }
}

// SQL query to get bookings
$sql = "SELECT $select_fields FROM broneeringud b $join_clauses WHERE 1=1";

$params = array();
$types = "";

if (!empty($otsing)) {
    $search_parts = ["k.kasutajanimi LIKE ?"];
    $otsing_param = "%$otsing%";
    $params[] = $otsing_param;
    $types .= "s";
    
    // Add room search if columns exist
    if (in_array('toa_number', $toad_columns)) {
        $search_parts[] = "t.toa_number LIKE ?";
        $params[] = $otsing_param;
        $types .= "s";
    }
    if (in_array('number', $toad_columns)) {
        $search_parts[] = "t.number LIKE ?";
        $params[] = $otsing_param;
        $types .= "s";
    }
    if (in_array('toa_nimi', $toad_columns)) {
        $search_parts[] = "t.toa_nimi LIKE ?";
        $params[] = $otsing_param;
        $types .= "s";
    }
    if (in_array('nimi', $toad_columns)) {
        $search_parts[] = "t.nimi LIKE ?";
        $params[] = $otsing_param;
        $types .= "s";
    }
    
    $sql .= " AND (" . implode(" OR ", $search_parts) . ")";
}

if (!empty($staatus_filter)) {
    $sql .= " AND b.staatus = ?";
    $params[] = $staatus_filter;
    $types .= "s";
}

if (!empty($kuupaev_alates)) {
    $sql .= " AND b.saabumine >= ?";
    $params[] = $kuupaev_alates;
    $types .= "s";
}

if (!empty($kuupaev_kuni)) {
    $sql .= " AND b.lahkumine <= ?";
    $params[] = $kuupaev_kuni;
    $types .= "s";
}

$sql .= " ORDER BY b.loodud DESC";

try {
    $stmt = mysqli_prepare($yhendus, $sql);
    if (!$stmt) {
        throw new Exception('SQL prepare failed: ' . mysqli_error($yhendus));
    }
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('SQL execute failed: ' . mysqli_stmt_error($stmt));
    }
    
    $tulemus = mysqli_stmt_get_result($stmt);
    if (!$tulemus) {
        throw new Exception('Failed to get result: ' . mysqli_error($yhendus));
    }
} catch (Exception $e) {
    die("Database query error: " . $e->getMessage());
}

// Check if header file exists
if (!file_exists('../pealeht/header.php')) {
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body>";
    echo "<h1>Error: Header file not found</h1>";
} else {
    include '../pealeht/header.php';
}
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-12">
            <!-- Header with user info -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                <div>
                    <h1 class="h2 fw-bold text-gradient text-primary mb-1">
                        <i class="fas fa-calendar-check me-2"></i>Broneeringute Haldus
                    </h1>
                    <p class="text-muted mb-0">Halda kõiki broneeringuid ühes kohas</p>
                </div>
                <div class="d-flex align-items-center gap-2 bg-light rounded-3 p-3 shadow-sm">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold"><?php echo htmlspecialchars($praegune_kasutaja ?? 'Unknown'); ?></p>
                        <span class="badge bg-info bg-opacity-15 text-info fs-6"><?php echo htmlspecialchars($kasutaja_roll ?? 'Unknown'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <?php if (!empty($teade)): ?>
                <div class="alert alert-dismissible fade show alert-<?php echo $teade_tyyp; ?> shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas <?php echo $teade_tyyp === 'success' ? 'fa-check-circle' : ($teade_tyyp === 'danger' ? 'fa-exclamation-circle' : 'fa-info-circle'); ?>"></i>
                        <span><?php echo htmlspecialchars($teade); ?></span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Search and filter card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-sliders-h text-primary"></i>
                        <span>Otsi ja filtreeri</span>
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <form method="GET" class="row gy-3 gx-4">
                        <div class="col-md-3">
                            <label for="otsing" class="form-label small fw-semibold text-muted">Otsing</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="otsing" name="otsing" 
                                       value="<?php echo htmlspecialchars($otsing); ?>" 
                                       placeholder="Kasutaja, tuba...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="staatus" class="form-label small fw-semibold text-muted">Staatus</label>
                            <select class="form-select" id="staatus" name="staatus">
                                <option value="">Kõik staatused</option>
                                <option value="töötlemisel" <?php echo $staatus_filter === 'töötlemisel' ? 'selected' : ''; ?>>Töötlemisel</option>
                                <option value="makstud" <?php echo $staatus_filter === 'makstud' ? 'selected' : ''; ?>>Makstud</option>
                                <option value="aktsepteeritud" <?php echo $staatus_filter === 'aktsepteeritud' ? 'selected' : ''; ?>>Aktsepteeritud</option>
                                <option value="tühistatud" <?php echo $staatus_filter === 'tühistatud' ? 'selected' : ''; ?>>Tühistatud</option>
                                <option value="läbi" <?php echo $staatus_filter === 'läbi' ? 'selected' : ''; ?>>Läbi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="kuupaev_alates" class="form-label small fw-semibold text-muted">Alates</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-day text-muted"></i></span>
                                <input type="date" class="form-control" id="kuupaev_alates" name="kuupaev_alates" 
                                       value="<?php echo htmlspecialchars($kuupaev_alates); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="kuupaev_kuni" class="form-label small fw-semibold text-muted">Kuni</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-day text-muted"></i></span>
                                <input type="date" class="form-control" id="kuupaev_kuni" name="kuupaev_kuni" 
                                       value="<?php echo htmlspecialchars($kuupaev_kuni); ?>">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                                <i class="fas fa-search me-2"></i>Otsi
                            </button>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-secondary px-3">
                                <i class="fas fa-undo me-1"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bookings table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-list text-primary"></i>
                            <span>Broneeringud</span>
                        </h5>
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>Kokku: <?php echo mysqli_num_rows($tulemus); ?> broneeringut
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if ($tulemus && mysqli_num_rows($tulemus) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Kasutaja</th>
                                        <th>Tuba</th>
                                        <th>Kuupäevad</th>
                                        <th class="text-center">Staatus</th>
                                        <th class="text-end pe-4">Hind</th>
                                        <th>Toimingud</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($rida = mysqli_fetch_assoc($tulemus)): ?>
                                        <tr id="rida_<?php echo $rida['broneering_id']; ?>" class="<?php echo $rida['staatus'] === 'tühistatud' ? 'opacity-75' : ''; ?>">
                                            <td class="ps-4 fw-semibold">#<?php echo $rida['broneering_id']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-user text-primary fs-6"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium"><?php echo htmlspecialchars($rida['kasutajanimi'] ?? 'N/A'); ?></div>
                                                        <small class="text-muted"><?php echo date('d.m.Y H:i', strtotime($rida['loodud'])); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium"><?php 
                                                    // Fix for undefined "nimi" warning
                                                    $roomNumber = $rida['toa_number'] ?? $rida['number'] ?? 'Tuba #' . $rida['toa_id'];
                                                    echo htmlspecialchars($roomNumber); 
                                                ?></div>
                                                <?php if (!empty($rida['toa_nimi'] ?? ($rida['nimi'] ?? ''))): ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars($rida['toa_nimi'] ?? $rida['nimi']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium"><?php echo date('d.m.Y', strtotime($rida['saabumine'])); ?></span>
                                                    <small class="text-muted"><?php echo date('d.m.Y', strtotime($rida['lahkumine'])); ?></small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $staatus_klassid = [
                                                    'töötlemisel' => 'warning',
                                                    'makstud' => 'info',
                                                    'aktsepteeritud' => 'success',
                                                    'tühistatud' => 'danger',
                                                    'läbi' => 'secondary'
                                                ];
                                                $klass = $staatus_klassid[$rida['staatus']] ?? 'secondary';
                                                ?>
                                                <span class="badge rounded-pill bg-<?php echo $klass; ?> bg-opacity-15 text-<?php echo $klass; ?> py-2 px-3">
                                                    <?php echo ucfirst($rida['staatus'] ?? 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 fw-bold">
                                                <?php echo number_format($rida['hind_kokku'] ?? 0, 0, ',', ' '); ?>€
                                                <div class="small text-muted fw-normal">
                                                    <?php echo ucfirst($rida['maksmisviis'] ?? 'N/A'); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary rounded-3" 
                                                            onclick="muudaBroneeringut(<?php echo $rida['broneering_id']; ?>)"
                                                            data-bs-toggle="tooltip" title="Muuda">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    
                                                    <?php if (in_array($rida['staatus'], ['töötlemisel', 'makstud'])): ?>
                                                        <button type="button" class="btn btn-sm btn-icon btn-outline-success rounded-3" 
                                                                onclick="muudaStaatust(<?php echo $rida['broneering_id']; ?>, 'aktsepteeritud', <?php echo $rida['kasutaja_id']; ?>, <?php echo $rida['toa_id']; ?>, '<?php echo $rida['saabumine']; ?>', '<?php echo $rida['lahkumine']; ?>', <?php echo $rida['hind_kokku']; ?>, '<?php echo $rida['maksmisviis']; ?>')"
                                                                data-bs-toggle="tooltip" title="Aktsepteeri">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger rounded-3" 
                                                                onclick="muudaStaatust(<?php echo $rida['broneering_id']; ?>, 'tühistatud', <?php echo $rida['kasutaja_id']; ?>, <?php echo $rida['toa_id']; ?>, '<?php echo $rida['saabumine']; ?>', '<?php echo $rida['lahkumine']; ?>', <?php echo $rida['hind_kokku']; ?>, '<?php echo $rida['maksmisviis']; ?>')"
                                                                data-bs-toggle="tooltip" title="Tühista">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="py-4 my-3">
                                <i class="fas fa-calendar-times fa-4x text-light mb-4"></i>
                                <h4 class="fw-semibold text-muted mb-3">Broneeringuid ei leitud</h4>
                                <p class="text-muted mb-4">Proovige muuta otsingufiltreid või lisada uus broneering</p>
                                <button class="btn btn-primary px-4">
                                    <i class="fas fa-plus me-2"></i>Lisa uus broneering
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit modal -->
<div class="modal fade" id="muutmiseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Muuda Broneeringut
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="muutmiseVorm">
                <div class="modal-body">
                    <input type="hidden" name="broneering_id" id="muuda_broneering_id">
                    <input type="hidden" name="uuenda_broneering" value="1">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="muuda_kasutaja_id" class="form-label">Kasutaja ID</label>
                            <input type="number" class="form-control" id="muuda_kasutaja_id" name="kasutaja_id" required>
                        </div>
                        <div class="col-md-6">
                            <label for="muuda_toa_id" class="form-label">Toa ID</label>
                            <input type="number" class="form-control" id="muuda_toa_id" name="toa_id" required>
                        </div>
                        <div class="col-md-6">
                            <label for="muuda_saabumine" class="form-label">Saabumine</label>
                            <input type="date" class="form-control" id="muuda_saabumine" name="saabumine" required>
                        </div>
                        <div class="col-md-6">
                            <label for="muuda_lahkumine" class="form-label">Lahkumine</label>
                            <input type="date" class="form-control" id="muuda_lahkumine" name="lahkumine" required>
                        </div>
                        <div class="col-md-6">
                            <label for="muuda_staatus" class="form-label">Staatus</label>
                            <select class="form-select" id="muuda_staatus" name="staatus" required>
                                <option value="töötlemisel">Töötlemisel</option>
                                <option value="makstud">Makstud</option>
                                <option value="aktsepteeritud">Aktsepteeritud</option>
                                <option value="tühistatud">Tühistatud</option>
                                <option value="läbi">Läbi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="muuda_maksmisviis" class="form-label">Maksmisviis</label>
                            <select class="form-select" id="muuda_maksmisviis" name="maksmisviis" required>
                                <option value="sularahas">Sularahas</option>
                                <option value="ülekandega">Ülekandega</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="muuda_hind_kokku" class="form-label">Hind kokku (€)</label>
                            <input type="number" class="form-control" id="muuda_hind_kokku" name="hind_kokku" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tühista
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Salvesta muudatused
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit booking
function muudaBroneeringut(broneeringId) {
    // Find the corresponding row in the table
    const rida = document.getElementById('rida_' + broneeringId);
    if (!rida) {
        alert('Viga: Ei leia broneeringu andmeid');
        return;
    }
    
    const rakud = rida.getElementsByTagName('td');
    
    // Fill modal with data
    document.getElementById('muuda_broneering_id').value = broneeringId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('muutmiseModal'));
    modal.show();
    
    // Here you could make an AJAX request to get exact data
    laeBroneeringAndmed(broneeringId);
}

// Load booking data with AJAX
function laeBroneeringAndmed(broneeringId) {
    // Here should be an AJAX request to load data
    // For now, we leave it empty as it would need an additional PHP file
    console.log('Loading booking data for ID: ' + broneeringId);
}

// Change status
function muudaStaatust(broneeringId, uusStaatus, kasutajaId, toaId, saabumine, lahkumine, hind, maksmisviis) {
    if (confirm('Kas olete kindel, et soovite muuta broneeringu staatust?')) {
        const vorm = document.createElement('form');
        vorm.method = 'POST';
        vorm.style.display = 'none';
        
        const inputs = [
            {name: 'broneering_id', value: broneeringId},
            {name: 'staatus', value: uusStaatus},
            {name: 'uuenda_broneering', value: '1'},
            {name: 'kasutaja_id', value: kasutajaId},
            {name: 'toa_id', value: toaId},
            {name: 'saabumine', value: saabumine},
            {name: 'lahkumine', value: lahkumine},
            {name: 'hind_kokku', value: hind},
            {name: 'maksmisviis', value: maksmisviis}
        ];
        
        inputs.forEach(input => {
            const sisend = document.createElement('input');
            sisend.type = 'hidden';
            sisend.name = input.name;
            sisend.value = input.value;
            vorm.appendChild(sisend);
        });
        
        document.body.appendChild(vorm);
        vorm.submit();
    }
}

// Delete booking
function kustutaBroneering(broneeringId) {
    if (confirm('Kas olete kindel, et soovite selle broneeringu kustutada? Seda toimingut ei saa tagasi võtta!')) {
        const vorm = document.createElement('form');
        vorm.method = 'POST';
        vorm.style.display = 'none';
        
        const idSisend = document.createElement('input');
        idSisend.type = 'hidden';
        idSisend.name = 'broneering_id';
        idSisend.value = broneeringId;
        
        const toimingSisend = document.createElement('input');
        toimingSisend.type = 'hidden';
        toimingSisend.name = 'kustuta_broneering';
        toimingSisend.value = '1';
        
        vorm.appendChild(idSisend);
        vorm.appendChild(toimingSisend);
        
        document.body.appendChild(vorm);
        vorm.submit();
    }
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const teated = document.querySelectorAll('.alert');
    teated.forEach(function(teade) {
        setTimeout(function() {
            if (bootstrap && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(teade);
                bsAlert.close();
            }
        }, 5000);
    });
});
</script>

<?php
// Clean up
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
if (isset($yhendus)) {
    mysqli_close($yhendus);
}

// Check if footer file exists
if (!file_exists('../pealeht/footer.php')) {
    echo "</body></html>";
} else {
    include '../pealeht/footer.php';
}
?>