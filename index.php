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

    <?php

      // MUUDA PÄRING

      if(isset($_GET["muuda"]) && isset($_GET["id"])){
        $id = $_GET["id"];
        $kuvaparing = "SELECT * FROM sport2025 WHERE id=".$id."";
        $saadetud_paring = mysqli_query($yhendus, $kuvaparing);
        $rida = mysqli_fetch_assoc($saadetud_paring);
      }
      if(isset($_GET["salvesta_muudatus"]) && isset($_GET["id"])){

        $id = $_GET["id"];
        $fullname = $_GET["fullname"];
        $email = $_GET["email"];
        $age = $_GET["age"];
        $gender = $_GET["gender"];
        $category = $_GET["category"];

        $muuda_paring="UPDATE sport2025 SET fullname= '".$fullname."', email='".$email."', age='".$age."', gender='".$gender."', category='".$category."' WHERE id ".$id."";
        $saada_paring = mysqli_query($yhendus, $muuda_paring);
        $tulemus = mysqli_affected_rows($yhendus);
        
        if($tulemus == 1){
          header('Location: index.php?msg=Andmed uuendatud');
        } else {
          echo "Andmed pole uuendatud";
        }

      }
    ?>

        <!-- sisestus VORM -->

    <form action="index.php" method="get">
      <input type="hidden" name="id" value="<?php !empty($rida['id']) ? print_r($rida['id']) : '' ?>">
      Nimi: <input type="text" name="fullname" required value="<?php !empty($rida['fullname']) ? print_r($rida['fullname']) : '' ?>"><br>
      E-mail: <input type="email" name="email" required value="<?php !empty($rida['email']) ? print_r($rida['email']) : '' ?>"><br>
      Vanus: <input type="number" name="age" min="16" max="88" step="1" required value="<?php !empty($rida['age']) ? print_r($rida['age']) : '' ?>"><br>
      Sugu: <input type="text" name="gender" required value="<?php !empty($rida['gender']) ? print_r($rida['gender']) : '' ?>"><br>
      Spordiala: <input type="text" name="category" required value="<?php !empty($rida['category']) ? print_r($rida['category']) : '' ?>"><br>
      
      <?php if(isset($_GET["muuda"]) && isset($_GET["id"])){ ?>
        <input type="submit" value="Salvesta_muudatus" name="salvesta_muudatus" class="btn btn-primary"><br>
      <?php } else { ?>
        <input type="submit" value="Salvesta" name="salvesta" class="btn btn-primary"><br>
      <?php } ?>
      
    </form>
    
    <?php

      if(isset($_GET['msg'])){
        echo "<div class='alert alert-success'>".$_GET['msg']."</div>";
      }

      // KUSTUTAMINE

      if(isset($_GET['kustuta']) && isset($_GET['id'])){
        $id = $_GET['id'];
        $kparing = "DELETE FROM sport2025 WHERE id=".$id."";
        $saada_paring = mysqli_query($yhendus, $kparing);
        $tulemus = mysqli_affected_rows($yhendus);
        if($tulemus == 1){
          header('Location: index.php?msg=Rida kustutatud');
        } else {
          echo "Kirjet ei lisatud";
        }
      }

      // OTSING

      if(isset($_GET["salvesta"]) && !empty($_GET["fullname"])){

        $fullname = $_GET["fullname"];
        $email = $_GET["email"];
        $age = $_GET["age"];
        $gender = $_GET["gender"];
        $category = $_GET["category"];

        $lisa_paring = "INSERT INTO sport2025 (fullname,email,age,gender,category) 
        VALUES ('".$fullname."','".$email."', '".$age."', ' ".$gender."', '".$category."')";

        $saada_paring = mysqli_query($yhendus, $lisa_paring);
        $tulemus = mysqli_affected_rows($yhendus);
        if($tulemus == 1){
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
      <?php
              if(isset($_GET['msg'])){
                echo "<div class='alert alert-success'>".$_GET['msg']."</div>";
              }
      ?>
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
                echo "<td><a class='btn btn-success' href='?muuda&id=".$rida['id']."'>Muuda</a></td>";
                echo "<td><a class='btn btn-danger' href='?kustuta&id=".$rida['id']."'>Kustuta</a></td>";
                echo "</tr>";

            }
        ?>

      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>  