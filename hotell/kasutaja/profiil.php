<?php
include '../pealeht/header.php';

// Redirect if not logged in
if (!$kasutaja_id) {
    header("Location: ../kasutaja/login");
    exit();
}

// Fetch user data with prepared statement
$sql = "SELECT kasutajanimi, telefon, email, roll FROM kasutajad WHERE kasutaja_id = ?";
$stmt = mysqli_prepare($yhendus, $sql);
mysqli_stmt_bind_param($stmt, "i", $kasutaja_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutajanimi = trim($_POST['kasutajanimi'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (!empty($_POST['parool'])) {
        $parool = password_hash($_POST['parool'], PASSWORD_DEFAULT);
        $update_sql = "UPDATE kasutajad SET kasutajanimi = ?, telefon = ?, email = ?, parool = ? WHERE kasutaja_id = ?";
        $stmt = mysqli_prepare($yhendus, $update_sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $kasutajanimi, $telefon, $email, $parool, $kasutaja_id);
    } else {
        $update_sql = "UPDATE kasutajad SET kasutajanimi = ?, telefon = ?, email = ? WHERE kasutaja_id = ?";
        $stmt = mysqli_prepare($yhendus, $update_sql);
        mysqli_stmt_bind_param($stmt, "sssi", $kasutajanimi, $telefon, $email, $kasutaja_id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = 'Profiil edukalt uuendatud!';
    } else {
        $_SESSION['error'] = 'Viga profiili uuendamisel!';
    }
    
    header("Location: ../kasutaja/profiil");
    exit();
}

$roleNames = [
    1 => 'Klient',
    2 => 'Administraator', 
    3 => 'Haldur'
];
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Profile Card -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white text-center py-4 rounded-top-4" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                            <i class="bi bi-person-circle fs-2"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Minu Profiil</h3>
                            <small class="opacity-75">Muuda oma andmeid</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form method="post" id="profileForm" novalidate>
                        <div class="row g-4">
                            <!-- Username -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           id="kasutajanimi" 
                                           name="kasutajanimi" 
                                           class="form-control form-control-lg border-2" 
                                           placeholder="Kasutajanimi"
                                           value="<?php echo htmlspecialchars($user['kasutajanimi']); ?>" 
                                           required>
                                    <label for="kasutajanimi">
                                        <i class="bi bi-person me-2 text-primary"></i>Kasutajanimi
                                    </label>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control form-control-lg border-2" 
                                           placeholder="E-mail"
                                           value="<?php echo htmlspecialchars($user['email']); ?>" 
                                           required>
                                    <label for="email">
                                        <i class="bi bi-envelope me-2 text-primary"></i>E-mail
                                    </label>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="tel" 
                                           id="telefon" 
                                           name="telefon" 
                                           class="form-control form-control-lg border-2" 
                                           placeholder="Telefon"
                                           value="<?php echo htmlspecialchars($user['telefon']); ?>" 
                                           required>
                                    <label for="telefon">
                                        <i class="bi bi-telephone me-2 text-primary"></i>Telefon
                                    </label>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-12">
                                <div class="form-floating position-relative">
                                    <input type="password" 
                                           id="parool" 
                                           name="parool" 
                                           class="form-control form-control-lg border-2 pe-5" 
                                           placeholder="Uus parool">
                                    <label for="parool">
                                        <i class="bi bi-lock me-2 text-primary"></i>Uus parool (valikuline)
                                    </label>
                                    <button type="button" 
                                            class="btn position-absolute end-0 top-50 translate-middle-y me-3 border-0 bg-transparent"
                                            onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Jäta tühjaks, kui ei soovi parooli muuta
                                </div>
                            </div>

                            <!-- Role (Read-only) -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control form-control-lg bg-light border-2" 
                                           placeholder="Roll"
                                           value="<?php echo htmlspecialchars($roleNames[$user['roll']] ?? 'Teadmata'); ?>" 
                                           readonly>
                                    <label>
                                        <i class="bi bi-shield-check me-2 text-success"></i>Roll
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-lg btn-primary rounded-pill py-3 fw-bold">
                                <i class="bi bi-check-circle me-2"></i>
                                Salvesta Muudatused
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('parool');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
}

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const form = this;
    
    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    form.classList.add('was-validated');
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<?php include '../pealeht/footer.php'; ?>