<?php
include '../config.php';
session_start();

// Restrict access to admin and peaadmin roles
if (!isset($_SESSION['roll']) || !in_array($_SESSION['roll'], ['admin', 'peaadmin'])) {
    header('Location: ../index.php');
    exit;
}
?>
<?php include 'header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Administraatori haldusala</h2>
    <p class="lead">Tere, <strong><?= htmlspecialchars($_SESSION['eesnimi'] ?? 'Admin') ?></strong>!</p>

    <div class="row g-4 mt-4">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-check me-2"></i>Broneeringud</h5>
                    <p class="card-text">Vaata, muuda ja kustuta broneeringuid.</p>
                    <a href="manage_reservations.php" class="btn btn-primary">Halda broneeringuid</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-door-open me-2"></i>Toad</h5>
                    <p class="card-text">Lisa ja muuda tubade infot.</p>
                    <a href="manage_rooms.php" class="btn btn-primary">Halda tube</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-gift me-2"></i>Pakkumised</h5>
                    <p class="card-text">Vaata ja uuenda hotellipakkumisi.</p>
                    <a href="manage_offers.php" class="btn btn-primary">Halda pakkumisi</a>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['roll'] === 'peaadmin'): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="bi bi-people-fill me-2"></i>Kasutajad</h5>
                    <p class="card-text">Muuda kasutajate rolle ja õigusi.</p>
                    <a href="manage_users.php" class="btn btn-warning">Halda kasutajaid</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../footer.php'; ?>
