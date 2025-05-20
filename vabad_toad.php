<?php
session_start();
require 'config.php';

if (!isset($_SESSION['kasutaja_id'])) {
    $_SESSION['error'] = "Palun logi esmalt sisse.";
    header('Location: login.php');
    exit;
}

if ($_SESSION['roll'] != 1) {
    $_SESSION['error'] = "Sul puuduvad õigused sellele lehele sisenemiseks.";
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['kasutaja_id'];

function isRoomFree($conn, $roomId, $start, $end) {
    $sql = "SELECT COUNT(*) FROM broneeringud WHERE toa_id = ? AND staatus IN ('pending','accepted')
            AND NOT (lahkumine <= ? OR saabumine >= ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $roomId, $start, $end);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count == 0;
}

$message = '';
$errors = [];

// Broneeringu esmane samm: liigume toa tüübi ja kuupäevade valikust toa numbri ja makseviisi sisestamiseni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $roomType = trim($_POST['room_type'] ?? '');
    $selectedRoomNr = trim($_POST['selected_room_nr'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? '');
    $arrival = trim($_POST['saabumine'] ?? '');
    $departure = trim($_POST['lahkumine'] ?? '');

    if (!$roomType || !$selectedRoomNr || !$paymentMethod || !$arrival || !$departure) {
        $errors[] = "Palun täida kõik vajalikud väljad.";
    } elseif ($arrival >= $departure) {
        $errors[] = "Lahkumise kuupäev peab olema hiljem kui saabumise kuupäev.";
    } else {
        $stmt = $yhendus->prepare("SELECT toa_id FROM toad WHERE tyyp = ? AND toa_nr = ? AND saadavus = 1");
        $stmt->bind_param('ss', $roomType, $selectedRoomNr);
        $stmt->execute();
        $stmt->bind_result($realRoomId);

        if ($stmt->fetch()) {
            $stmt->close();
            if (isRoomFree($yhendus, $realRoomId, $arrival, $departure)) {
                $stmt2 = $yhendus->prepare("INSERT INTO broneeringud (kasutaja_id, toa_id, saabumine, lahkumine, staatus, makseviis, loodud)
                                           VALUES (?, ?, ?, ?, 'pending', ?, NOW())");
                $stmt2->bind_param('iisss', $user_id, $realRoomId, $arrival, $departure, $paymentMethod);
                if ($stmt2->execute()) {
                    $message = "Broneeringu taotlus saadetud! Ootame administraatori kinnitust.";
                } else {
                    $errors[] = "Broneeringu salvestamisel tekkis viga.";
                }
                $stmt2->close();
            } else {
                $errors[] = "Valitud tuba ei ole antud kuupäevadel vaba.";
            }
        } else {
            $errors[] = "Valitud toa number ei ole saadaval.";
            $stmt->close();
        }
    }
}

// Ava toatüübid
$typesSql = "SELECT tyyp, COUNT(*) AS saadaval FROM toad WHERE saadavus = 1 GROUP BY tyyp";
$roomTypesResult = $yhendus->query($typesSql);
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vabad Toad | HKHK Motell</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include 'header.php'; ?>

<div class="container py-4">
    <h1 class="text-center mb-4">Vabad Toad</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_type_id']) && !isset($_POST['confirm_booking'])): 
        $selectedType = $_POST['room_type_id'];
        $arrival = $_POST['saabumine'] ?? '';
        $departure = $_POST['lahkumine'] ?? '';

        if ($arrival && $departure && $arrival < $departure):
            $stmt = $yhendus->prepare("SELECT toa_nr, hind FROM toad WHERE tyyp = ? AND saadavus = 1");
            $stmt->bind_param('s', $selectedType);
            $stmt->execute();
            $resultRooms = $stmt->get_result();
            $freeRooms = [];
            while ($row = $resultRooms->fetch_assoc()) {
                $stmt2 = $yhendus->prepare("SELECT toa_id FROM toad WHERE tyyp = ? AND toa_nr = ?");
                $stmt2->bind_param('ss', $selectedType, $row['toa_nr']);
                $stmt2->execute();
                $stmt2->bind_result($roomId);
                $stmt2->fetch();
                $stmt2->close();
                if (isRoomFree($yhendus, $roomId, $arrival, $departure)) {
                    $freeRooms[] = $row;
                }
            }
        endif;
    ?>
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h4 class="card-title">Vabad <?= htmlspecialchars($selectedType) ?> toad</h4>
                <form method="post">
                    <input type="hidden" name="room_type" value="<?= htmlspecialchars($selectedType) ?>">
                    <input type="hidden" name="room_type_id" value="<?= htmlspecialchars($selectedType) ?>">
                    <input type="hidden" name="saabumine" value="<?= htmlspecialchars($arrival) ?>">
                    <input type="hidden" name="lahkumine" value="<?= htmlspecialchars($departure) ?>">

                    <div class="mb-3">
                        <label class="form-label">Vali toanumber ja hind</label>
                        <select name="selected_room_nr" class="form-select" required>
                            <option value="" disabled selected>Vali toanumber</option>
                            <?php if (!empty($freeRooms)): ?>
                                <?php foreach ($freeRooms as $r): ?>
                                    <option value="<?= htmlspecialchars($r['toa_nr']) ?>">
                                        <?= htmlspecialchars($r['toa_nr']) ?> — <?= number_format($r['hind'], 2, ',', ' ') ?> €/öö
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option disabled>Pole vabu tube</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Makseviis</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="" disabled selected>Vali makseviis</option>
                            <option value="sularaha">Sularaha</option>
                            <option value="kaardiga">Kaardiga</option>
                            <option value="kinkekaart">Kinkekaart</option>
                        </select>
                    </div>

                    <button type="submit" name="confirm_booking" class="btn btn-success w-100">Valmis</button>
                    <a href="vabad_toad.php" class="btn btn-secondary w-100 mt-2">Tagasi</a>
                </form>
            </div>
        </div>

    <?php else: ?>
        <form method="post" class="row g-3 justify-content-center mb-4">
            <div class="col-md-4">
                <label class="form-label">Tuba</label>
                <select name="room_type_id" class="form-select" required>
                    <option value="" selected>Vali tubade tüüp</option>
                    <?php 
                    $roomTypesResult->data_seek(0);
                    while ($type = $roomTypesResult->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($type['tyyp']) ?>">
                            <?= htmlspecialchars(ucfirst($type['tyyp'])) ?> (<?= $type['saadaval'] ?> vaba)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Saabumine</label>
                <input type="date" name="saabumine" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Lahkumine</label>
                <input type="date" name="lahkumine" class="form-control" required>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Järgmine</button>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php 
            $roomTypesResult->data_seek(0);
            while ($type = $roomTypesResult->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="toad/<?= htmlspecialchars($type['tyyp']) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($type['tyyp']) ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars(ucfirst($type['tyyp'])) ?></h5>
                            <p class="card-text"><strong><?= $type['saadaval'] ?> vaba</strong></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
