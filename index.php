<?php include("config.php"); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HKHK spordipäev 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <h1>HKHK spordipäev 2025!</h1>

        <table class="table table-striped">
            <?php
                $paring = "select * from sport2025";
                $saada_paring = mysqli_query($yhendus, $paring);
                // võtab kõik read
                // assoc annab nimelised väljad
                while($rida = mysqli_fetch_assoc($saada_paring)){
                    // print_r($rida); 
                    // print_r($rida ['fullname']);
                    echo "<tr>"

                    echo "</tr>"
                }
            ?>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  </body>
</html>