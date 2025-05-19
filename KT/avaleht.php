<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Praktika Maltal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
// kaustas olevad txt failid
$postitused = glob('postitused/*.txt');
?>
<main class="container my-5">
<?php
foreach ($postitused as $postitus) {

    $faili_info = pathinfo($postitus);
    $faili_sisu = file_get_contents($postitus);
    $pealkiri = $faili_info['filename'];

    echo "<div class='border-bottom py-3'>";
    echo "<h5 class='fw-bold'>". $pealkiri ."</h5>";
    echo "<p class='text-muted'>". nl2br($faili_sisu) ."</p>";
    echo "</div>";
}
?>
 </main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
