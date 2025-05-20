<?php
include 'config.php';
session_start();

// Only allow admin
if (!isset($_SESSION['roll']) || $_SESSION['roll'] != 2) {
    exit("Ligipääs keelatud");
}

// Confirm booking
if (isset($_GET['confirm'])) {
    $id = intval($_GET['confirm']);
    mysqli_query($yhendus, "UPDATE broneeringud SET staatus = 'kinnitatud' WHERE id = $id");
}

// Show all pending bookings
$broneeringud = mysqli_query($yhendus, "
    SELECT b.id, t.nimi AS tuba, k.kasutajanimi, b.staatus
    FROM broneeringud b
    JOIN kasutajad k ON b.kasutaja_id = k.id
    JOIN toad t ON b.tuba_id = t.id
    WHERE b.staatus = 'ootel'
");
?>
<h2>Ootel broneeringud</h2>
<table border="1">
    <tr><th>Kasutaja</th><th>Tuba</th><th>Staatus</th><th>Tegevus</th></tr>
    <?php while ($r = mysqli_fetch_assoc($broneeringud)): ?>
        <tr>
            <td><?= $r['kasutajanimi'] ?></td>
            <td><?= $r['tuba'] ?></td>
            <td><?= $r['staatus'] ?></td>
            <td><a href="?confirm=<?= $r['id'] ?>">Kinnita</a></td>
        </tr>
    <?php endwhile; ?>
</table>
