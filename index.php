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

    <form action="index.php" method="get">
      Nimi: <input type="text" name="fullname" required><br>
      E-mail: <input type="email" name="email" required><br>
      Vanus: <input type="number" name="age" min="16" max="88" step="1" required><br>
      Sugu: <input type="text" name="gender" limit="5" required><br>
      Spordiala: <input type="text" name="category" limit="20" required><br>
      <input type="submit" value="Salvesta" class="btn btn-primary" required><br>
    </form>
    <?php
      if(isset($_GET["fullname"]) && !empty($_GET["fullname"])){
        $lisa_paring = "INSERT INTO sport2025 (fullname,email,age,gender,category) 
        VALUES ('Mario vaarikas', 'asdf@sdf.ee', '18', ' mees', 'jooks')";

        $saada_paring = mysqli_query($yhendus, $lisa_paring);
        $tulemas = mysqli_affected_rows($yhendus);
        if($tulemas == 1){
          echo "Kirje edukalt lisatud";
        } else {
          echo "Kirjet ei lisatud";
        }
      }
    ?>

    <form action="index.php" method="get" class="py-4">
      <input type="text" name="otsi">
      <select name="cat">
        <option value="fullname">Nimi</option>
        <option value="category">Spordiala</option>
      </select>
      <input type="submit" value="Otsi...">
    </form>

    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">firstname</th>
          <th scope="col">email</th>
          <th scope="col">age</th>
          <th scope="col">gender</th>
          <th scope="col">category</th>
          <th scope="col">reg_time</th>
          <th scope="col">muuda</th>
          <th scope="col">kustuta</th>
        </tr>
      </thead>
      <tbody>

        <?php
            if(isset($_GET['otsi']) && !empty($_GET["otsi"])){
              $s = $_GET['otsi'];
              $cat = $_GET['cat'];
              echo "Otsing: ".$s;
              $paring = 'SELECT * FROM sport2025 WHERE '.$cat.' LIKE "%'.$s.'%"';
              // var_dump($paring);
            } else {
              $paring = "SELECT * from sport2025 LIMIT 50";
            }
            
            $saada_paring = mysqli_query($yhendus, $paring);
            // võtab kõik read
            // assoc annab nimelised väljad
            while($rida = mysqli_fetch_assoc($saada_paring)){
                // print_r($rida); 
                // print_r($rida ['fullname']);

                echo "<tr>";
                echo "<td>".$rida['id']."</td>";
                echo "<td>".$rida['fullname']."</td>";
                echo "<td>".$rida['email']."</td>";
                echo "<td>".$rida['age']."</td>";
                echo "<td>".$rida['gender']."</td>";
                echo "<td>".$rida['category']."</td>";
                echo "<td>".$rida['reg_time']."</td>";
                echo "<td><a class='btn btn-success' href=''>Muuda</a></td>";
                echo "<td><a class='btn btn-danger' href=''>Kustuta</a></td>";
                echo "</tr>";

            }
        ?>

      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>