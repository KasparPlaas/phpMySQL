<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <h1>admin leht!!!</h1>
    <h2>Lisa postitus:</h2>
    <form method="POST">

        <div class="mb-3">
            <p>pealkiri:</p>
            <input type="text" class="form-control" id="pealkiri" name="pealkiri" required>
        </div>

        <div class="mb-3">
            <p>sisu:</p>
            <textarea name="sisu" class="form-control" rows="5" required></textarea>
            
        </div>
        <button type="submit" name="lisa" class="btn btn-primary">Lisa postitus</button>

    </form>

<?php

 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lisa'])) {
    $pealkiri = $_POST['pealkiri'];
    $sisu = $_POST['sisu'];

    $minu_fail = fopen("postitused/$pealkiri.txt", 'w');
    fwrite($minu_fail, $sisu);
    fclose($minu_fail);
    echo "<p class='text-success'>Postitus on lisatud!!!</p>";
}

?>

<h2>Kustusta postitus:</h2>



    <form method="POST">
        <div class="mb-3">
            <p>pealkiri:</p>
            <input type="text" class="form-control" id="pealkiri" name="pealkiri" required>
        </div>

        <button type="submit" name="kustuta" class="btn btn-danger">Kustuta postitus</button>
    </form>
<h3>olemas olevad postitused:</h3>
<?php
$postitused = glob('postitused/*.txt');

foreach ($postitused as $postitus) {

  $faili_info = pathinfo($postitus);
  $pealkiri = $faili_info['filename'];

  echo "$pealkiri<br>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kustuta'])) {
    $pealkiri = $_POST['pealkiri'];
    $failinimi = "postitused/$pealkiri.txt";

    if (file_exists($failinimi)) {
        unlink($failinimi);
        echo "<p class='text-success'>Postitus on kustutatud!!!</p>";
    } else {
        echo "<p class='text-danger'>Postitust ei leitud!!!</p>";
    }
}
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

