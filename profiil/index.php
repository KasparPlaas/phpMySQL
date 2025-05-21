<?php
include '../header.php';

if (!$kasutaja_id) {
    header("Location: ../login");
    exit();
}

// Fetch user data
$sql = "SELECT kasutajanimi, telefon, email, roll FROM kasutajad WHERE kasutaja_id = $kasutaja_id";
$result = mysqli_query($yhendus, $sql);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutajanimi = $_POST['kasutajanimi'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($_POST['parool'])) {
        $parool = password_hash($_POST['parool'], PASSWORD_DEFAULT);
        $update_sql = "UPDATE kasutajad SET kasutajanimi = '$kasutajanimi', telefon = '$telefon', email = '$email', parool = '$parool' WHERE kasutaja_id = $kasutaja_id";
    } else {
        $update_sql = "UPDATE kasutajad SET kasutajanimi = '$kasutajanimi', telefon = '$telefon', email = '$email' WHERE kasutaja_id = $kasutaja_id";
    }

    mysqli_query($yhendus, $update_sql);
    header("Location: ../profiil");
    exit();
}

// Define role names
$roleNames = [
    1 => 'tavakasutaja',
    2 => 'admin',
    3 => 'haldur'
];
?>

<div class="container mt-5 mb-5">
    <h2>Minu profiil</h2>
    <form method="post">
        <div class="mb-3">
            <label for="kasutajanimi" class="form-label">Kasutajanimi</label>
            <input type="text" id="kasutajanimi" name="kasutajanimi" class="form-control" required
                   value="<?php echo htmlspecialchars($user['kasutajanimi']); ?>">
        </div>

        <div class="mb-3">
            <label for="parool" class="form-label">Uus parool (täida, kui soovid muuta)</label>
            <input type="password" id="parool" name="parool" class="form-control" placeholder="Sisesta uus parool">
        </div>

        <div class="mb-3">
            <label for="telefon" class="form-label">Telefon</label>
            <input type="text" id="telefon" name="telefon" class="form-control" required
                   value="<?php echo htmlspecialchars($user['telefon']); ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required
                   value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Roll</label>
            <input type="text" class="form-control" readonly
                   value="<?php echo htmlspecialchars($roleNames[$user['roll']] ?? 'Teadmata'); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Salvesta muudatused</button>
    </form>
</div>

<?php include '../footer.php'; ?>
