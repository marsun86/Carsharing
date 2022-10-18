<?php

include_once("setup.php");

//require_once("includes/api.php");
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anwendungsfall 4 und 5</title>

  <!--<script src="scripts/carsharing.js"></script>-->

</head>

<body>
  <img src="meinhintergrund.jpg">
  <a href="index.php">zurueck zur Hauptseite </a>



  <span style="color:black;font: sizet 10px;     ">
    <form method="POST" enctype='multipart/form-data' action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <h1>Anwendungsfall 4: Ausleihen und Rueckgabe eines Fahrzeugs<h1>

          <!-- Fahrzeug ausleihen-->
          <h2>Kennzeichenabfrage zum Ausleihen<h2>
              <input type="text" name="kwert"> Kennzeichen eingeben<br>
              <input type="submit" name="kload"> ausleihen<br>
              <?php
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!empty($_POST['kwert'])) {
                  if (is_string($_POST['kwert'])) {
                    if (isset($_POST['kload'])) {
                      $Carsharing->kennzeichenabfrage();
                    }
                  }
                }
              }
              ?>
              <!-- Fahrzeug zurueckgeben-->
              <h2>Kennzeichenrueckgabe<h2> <input type="text" name="kennwert"> Kennzeichen eingeben<br>
                  <input type="text" name="kennstation"> Station eingeben<br>
                  <input type="submit" name="krueck" />zurueckgeben<br>

                  <?php
                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (!empty($_POST['kennwert']) and !empty($_POST['kennstation'])) {
                      if (is_string($_POST['kennwert'])) {
                        if (is_string($_POST['kennstation'])) {
                          if (isset($_POST['krueck'])) {
                            $Carsharing->kennzeichenrueckgabe();
                          }
                        }
                      }
                    }
                  }
                  ?>


                  <!-- Fahrzeug suchen nach -->

                  <h1>Anwendungsfall 5: Suchen eines Fahrzeugs (REST-API)<h1>
                      <h2>Anzeige nach Typ<h2>


                          <select name="typapi" id="typapi">
                            <option value="0">Kleinwagen</option>
                            <option value="1">Kompaktwagen</option>
                            <option value="2">Mittelklasse</option>
                            <option value="3">Van</option>
                            <option value="4">Transporter</option>
                          </select>
                          Typ waehlen<br>



                          <input type="submit" name="subtyp" value="Abfrage Typ" /> Typabfrage<br>
                          <p id="typzeigen"></p>
                          <div id="demo">


                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {



                              if (!empty($_POST['typapi'])) {



                                if (isset($_POST['subtyp'])) {



                            ?>
                                  <script src="scripts/carsharing.js"></script>
                            <?php

                                }
                              }
                            }



                            ?>







                            <h2> Anzeige nach Mindestanzahl Sitzplaetze<h2>
                                <input type="number" name="sapi"> Maximum Sitzplaetze eingeben<br>
                                <input type="button" name="sitzbutton" value="Abfrage Sitzplaetze">Anzeigen Ajax<br>

                                <p id="sitzezeigen"></p>
                                <div id="demo2">

                                  <?php







                                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    if (is_numeric($_POST['sapi'])) {
                                      if (isset($_POST['sitzbutton'])) {
                                  ?>


                                        <script src="scripts/carsharing.js"></script>


                                  <?php

                                      }
                                    }
                                  }


                                  ?>



    </form>


</body>

</html>