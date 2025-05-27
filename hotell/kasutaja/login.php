<?php
include_once '../kasutaja/session.php';
include_once '../andmebaas/config.php';

$veateade = '';

// Check if user is remembered
if (!isset($_SESSION['kasutaja_id']) && isset($_COOKIE['remember_user'])) {
    $remember_token = $_COOKIE['remember_user'];
    $remember_token = mysqli_real_escape_string($yhendus, $remember_token);
    
    $sql = "SELECT kasutaja_id, kasutajanimi, roll FROM kasutajad WHERE remember_token = '$remember_token'";
    $result = mysqli_query($yhendus, $sql);
    
    if ($result && mysqli_num_rows($result) === 1) {
        $kasutaja = mysqli_fetch_assoc($result);
        logi_sisse($kasutaja['kasutaja_id'], $kasutaja['kasutajanimi'], $kasutaja['roll']);
        header('Location: ../pealeht/index.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutajanimi = trim($_POST['kasutajanimi'] ?? '');
    $parool = $_POST['parool'] ?? '';
    $remember_me = isset($_POST['remember_me']);
    
    if (!empty($kasutajanimi) && !empty($parool)) {
        $kasutajanimi = mysqli_real_escape_string($yhendus, $kasutajanimi);
        $sql = "SELECT kasutaja_id, kasutajanimi, parool, roll FROM kasutajad WHERE kasutajanimi = '$kasutajanimi'";
        $result = mysqli_query($yhendus, $sql);
        
        if ($result && mysqli_num_rows($result) === 1) {
            $kasutaja = mysqli_fetch_assoc($result);
            
            if (kontrolli_parooli($parool, $kasutaja['parool'])) {
                logi_sisse($kasutaja['kasutaja_id'], $kasutaja['kasutajanimi'], $kasutaja['roll']);
                
                // Handle remember me functionality
                if ($remember_me) {
                    $remember_token = bin2hex(random_bytes(32));
                    $kasutaja_id = $kasutaja['kasutaja_id'];
                    
                    // Save token to database
                    $update_sql = "UPDATE kasutajad SET remember_token = '$remember_token' WHERE kasutaja_id = $kasutaja_id";
                    mysqli_query($yhendus, $update_sql);
                    
                    // Set cookie for 1 day
                    setcookie('remember_user', $remember_token, time() + (24 * 60 * 60), '/', '', false, true);
                }
                
                header('Location: ../pealeht/index.php');
                exit();
            } else {
                $veateade = "Vale parool!";
            }
        } else {
            $veateade = "Kasutajat ei leitud!";
        }
    } else {
        $veateade = "Palun täida kõik väljad!";
    }
}
?>

<?php include '../pealeht/header.php'; ?>

<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Header Section -->
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px;">
                            <i class="bi bi-person-lock text-white" style="font-size: 1.8rem;"></i>
                        </div>
                        <h1 class="h3 fw-bold text-dark mb-2">Tere tulemast tagasi!</h1>
                        <p class="text-muted mb-0">Sisestage oma andmed sisselogimiseks</p>
                    </div>

                    <!-- Error Message -->
                    <?php if (!empty($veateade)): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?php echo htmlspecialchars($veateade); ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="post" action="login.php" novalidate>
                        
                        <!-- Username Field -->
                        <div class="mb-4">
                            <label for="kasutajanimi" class="form-label fw-semibold text-dark">
                                <i class="bi bi-person me-1 text-primary"></i>Kasutajanimi
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg border-2 rounded-3" 
                                   name="kasutajanimi" 
                                   id="kasutajanimi" 
                                   placeholder="Sisestage oma kasutajanimi"
                                   value="<?php echo isset($_POST['kasutajanimi']) ? htmlspecialchars($_POST['kasutajanimi']) : ''; ?>"
                                   required>
                            <div class="invalid-feedback">
                                Palun sisestage kasutajanimi.
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="parool" class="form-label fw-semibold text-dark">
                                <i class="bi bi-lock me-1 text-primary"></i>Parool
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg border-2 rounded-start-3" 
                                       name="parool" 
                                       id="parool" 
                                       placeholder="Sisestage oma parool"
                                       required>
                                <button class="btn btn-outline-secondary border-2 rounded-end-3" 
                                        type="button" 
                                        id="togglePassword"
                                        aria-label="Näita parooli">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Palun sisestage parool.
                            </div>
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="remember_me" 
                                       id="remember_me">
                                <label class="form-check-label text-dark" for="remember_me">
                                    <i class="bi bi-clock me-1 text-primary"></i>Jäta mind meelde (1 päev)
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold py-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Logi sisse
                            </button>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="text-center">
                            <a href="#" class="text-decoration-none text-primary fw-semibold">
                                <i class="bi bi-question-circle me-1"></i>Unustasid parooli?
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="bi bi-shield-check me-1"></i>
                    Teie andmed on kaitstud ja turvalised
                </small>
            </div>
        </div>
    </div>
</div>

<script>
// Password visibility toggle
const toggleBtn = document.getElementById('togglePassword');
const passwordInput = document.getElementById('parool');

toggleBtn.addEventListener('click', function() {
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.className = 'bi bi-eye-slash';
        this.setAttribute('aria-label', 'Peida parool');
    } else {
        passwordInput.type = 'password';
        icon.className = 'bi bi-eye';
        this.setAttribute('aria-label', 'Näita parooli');
    }
});

// Form validation
const form = document.querySelector('form');
const inputs = form.querySelectorAll('input[required]');

form.addEventListener('submit', function(e) {
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
    }
});

// Remove validation on input
inputs.forEach(input => {
    input.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
        }
    });
});

// Focus enhancement
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('kasutajanimi').focus();
});
</script>

<?php include '../pealeht/footer.php'; ?>