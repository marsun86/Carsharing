<?php
include_once("model.php");


class Carsharing
{


    // Verbindungskonstruktor
    private $db;

    //Initialisierung der privaten Eigenschaft db.
    public function __construct($host, $port, $name, $user, $password, $usedb = true)
    {
        try {

            $this->db = new PDO("mysql:host={$host};port={$port}", $user, $password);

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            if ($usedb) {

                //Schema anlegen
                $checkdb = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='Carsharing'";
                $checkdbstmt = $this->db->query($checkdb);
                $checkdbstmt->execute();
                $dbexists = $checkdbstmt->rowCount() > 0;

                if (!$dbexists) {

                    echo "Datenbank und notwendige Tabellen existieren noch nicht";
                    echo '<a href="setup.php">Link zur setup.php</a>';
                    die;
                }


                $this->db->exec('USE Carsharing');
            }
        } catch (PDOException $e) {
            echo "Fehler" . $e->getMessage();
            echo '<a href="setup.php">Link zur setup.php</a>';
        }
    }







    //Datenbank erstellen
    public function datenbankerstellen()
    {
        $cd = "CREATE DATABASE IF NOT EXISTS Carsharing";
        $this->db->query($cd);
    }





    //Tabellenfunktion
    public function init()
    {

        // TabelleStations/  
        $tabellestation = "CREATE TABLE IF NOT EXISTS `station` (

`stations_id` int NOT NULL AUTO_INCREMENT, 
  `nombre` varchar(50)  NULL ,
  `adresse` varchar(50) NULL ,
  `geographische_Breite` float NULL ,
  `geographische_Laenge` float NULL ,
  `anzahl_stellplaetze` int NULL ,
  PRIMARY KEY (`stations_id`) 
 )DEFAULT CHARSET=utf8 collate=utf8_bin";
        $this->db->exec($tabellestation);

        // Tabelle Vehicel
        $tabellecar = "CREATE TABLE IF NOT EXISTS `vehicel` (
        `vehicel_id` int NOT NULL AUTO_INCREMENT, 
          `bezeichnung` varchar(50) NULL,
          `typ` int NULL,
          CONSTRAINT chk_Person CHECK (Typ>=0 AND Typ<=4),
          `kennzeichen` varchar(20) NULL ,
          `anzahl_tueren` int NULL ,
          `anzahl_sitze` int NULL ,
          `standort` varchar(50) NULL,
          PRIMARY KEY (`vehicel_id`)
         )DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $this->db->exec($tabellecar);
    }

    //Eingabe Daten zur Station
    function einzeleinleseSTATIONS()
    {
        $id = htmlspecialchars($_POST["id"]);
        $namen = htmlspecialchars($_POST["nameid"]);
        $adresse = htmlspecialchars($_POST["adresseid"]);
        $breite = htmlspecialchars($_POST["breiteid"]);
        $laenge = htmlspecialchars($_POST["laengeid"]);
        $stellplatzstation = htmlspecialchars($_POST["stellplatzid"]);



        $breitecar_float = floatval(str_replace(',', '.', $breite));
        $laengecar_float = floatval(str_replace(',', '.', $laenge));

        $sql = "INSERT INTO station (stations_id,nombre,adresse,geographische_Breite,geographische_Laenge,anzahl_stellplaetze) VALUES (?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $namen, $adresse, $breitecar_float, $laengecar_float, $stellplatzstation]);
    }

    //alles in DB
    public function inDB()
    {





        $id = htmlspecialchars($_POST["id"]);
        $namen = htmlspecialchars($_POST["nameid"]);
        $adresse = htmlspecialchars($_POST["adresseid"]);
        $breite = htmlspecialchars($_POST["breiteid"]);
        $laenge = htmlspecialchars($_POST["laengeid"]);
        $stellplatzstation = htmlspecialchars($_POST["stellplatzid"]);



        $breitecar_float = floatval(str_replace(',', '.', $breite));
        $laengecar_float = floatval(str_replace(',', '.', $laenge));

        $sql = $this->db->prepare("INSERT INTO station (stations_id,nombre,adresse,geographische_Breite,geographische_Laenge,anzahl_stellplaetze) VALUES 
   (:id,:namen, :adresse,:breite, :laenge,:stellplatzstation)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':namen', $namen);
        $sql->bindParam(':adresse', $adresse);
        $sql->bindParam(':breite', $breite);
        $sql->bindParam(':laenge', $laenge);
        $sql->bindParam(':stellplatzstation', $stellplatzstation);


        $sql->execute();

        $id = htmlspecialchars($_POST["idauto"]);
        $z = htmlspecialchars($_POST["bezeichnungid"]);
        $n = htmlspecialchars($_POST["typid"]);
        $rechtswert = htmlspecialchars($_POST["kennzeichenid"]);
        $hochwert = htmlspecialchars($_POST["tuerid"]);
        $crs = htmlspecialchars($_POST["sitzeid"]);
        $bbox = htmlspecialchars($_POST["ortid"]);

        $stmt = $this->db->prepare("INSERT INTO vehicel (vehicel_id,bezeichnung,typ,kennzeichen,anzahl_tueren,anzahl_sitze,standort)
            VALUES (:id,:z, :n,:rechtswert, :hochwert,:crs, :bbox)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':z', $z);
        $stmt->bindParam(':n', $n);
        $stmt->bindParam(':rechtswert', $rechtswert);
        $stmt->bindParam(':hochwert', $hochwert);
        $stmt->bindParam(':crs', $crs);
        $stmt->bindParam(':bbox', $bbox);

        $stmt->execute();


        echo "Daten in DB";
    }










    //Eingabe Daten zum Auto
    function einzeleinleseCAR()
    {

        $autoid = htmlspecialchars($_POST["idauto"]);
        $bezeichnung = htmlspecialchars($_POST["bezeichnungid"]);
        $typ = htmlspecialchars($_POST["typid"]);
        $kennzeichen = htmlspecialchars($_POST["kennzeichenid"]);
        $tuer = htmlspecialchars($_POST["tuerid"]);
        $sitz = htmlspecialchars($_POST["sitzeid"]);
        $ort = htmlspecialchars($_POST["ortid"]);


        $sql = "INSERT INTO vehicel (vehicel_id,bezeichnung,typ,kennzeichen,anzahl_tueren,anzahl_sitze,standort) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$autoid, $bezeichnung, $typ, $kennzeichen, $tuer, $sitz, $ort]);
    }




    public function anzeigeSTATIONS()
    {

        //Tabelle Stations anzeigen

        $stmt = $this->db->prepare(
            "SELECT stations_id,nombre,adresse,geographische_Breite,geographische_Laenge,anzahl_stellplaetze
     FROM station  where  geographische_Breite != '' order by stations_id"
        );
        $stmt->execute();
        $users = $stmt->fetchAll();
        foreach ($users as $user) {
            echo
            "<table border='1px' style=width:'600px'; line-height:'40px';>
       <tr>
          <th>Station ID</th>
          <th>Name</th>
          <th>Adresse</th>
          <th>geogr. Breite</th>
          <th>geogr. Laenge</th>
          <th>Stellplaetze</th>
        </tr>
        <tr>
          <td>";
            echo $user['stations_id'];
            echo "</td><td>";
            echo $user['nombre'];
            echo "</td><td>";
            echo $user['adresse'];
            echo "</td><td>";
            echo $user['geographische_Breite'];
            echo  "</td><td>";
            echo $user['geographische_Laenge'];
            echo  "</td><td>";
            echo $user['anzahl_stellplaetze'];
            echo "</td><td>";
            echo "</tr>";
            echo "</table>";
        }
    }


    // tabelle Tabelle Vehicel anzeigen
    public function anzeigeCars()
    {


        $stmt = $this->db->prepare(
            "SELECT vehicel_id,bezeichnung,typ,kennzeichen,anzahl_tueren,anzahl_sitze,standort
 FROM vehicel where  kennzeichen IS NOT NULL and bezeichnung !='' "
        );
        $stmt->execute();
        $car = $stmt->fetchAll();
        foreach ($car as $daten) {
            echo
            "<table border='1px' style=width:'200px'; line-height:'40px';><tr>
      <th>Vehicel ID</th>
      <th>Bezeichnung</th>
      <th>Typ</th>
      <th>Kennzeichen</th>
      <th>Tueren</th>
      <th>Sitze</th>
      <th>Standort</th>
    </tr>
    <tr>
      <td>";
            echo $daten['vehicel_id'];
            echo "</td><td>";
            echo $daten['bezeichnung'];
            echo "</td><td>";
            $wb = $daten['typ'];
            if ($wb == 0) {
                echo "Kleinwagen";
            } elseif ($wb == 1) {

                echo  "Kompaktwagen";
            } elseif ($wb == 2) {
                echo "Mittelklasse";
            } elseif ($wb == 3) {
                echo "Van";
            } else {
                echo "Transporter";
            }




            echo  "</td><td>";
            echo $daten['kennzeichen'];
            echo "</td><td>";
            echo $daten['anzahl_tueren'];
            echo "</td><td>";
            echo $daten['anzahl_sitze'];
            echo "</td><td>";
            echo $daten['standort'];
            echo "</td><td>";
            echo "</tr>";
            echo "</table>";
        }
    }

    // Textdatei hochladen
    public function upload()
    {
        //Text Quelle
        $verzeichnis = "upload/";
        $dateiName = basename($_FILES["upload"]["name"]);
        $pfadDateiName = $verzeichnis . $dateiName;
        //Textdatei öffnen
        $file = "$pfadDateiName";
        $fopen = fopen($file, 'r');
        $fread = fread($fopen, filesize($file));
        fclose($fopen);

        //Textdatei auslesen
        $str = str_replace("---STATIONS---", "", $fread);
        $rem  = "---VEHCLES---";
        $split = explode($rem, $str);

        $stationausgabe = trim($split[0]);
        $autoausgabe = trim($split[1]);

        // sorgt 1,2,3,4,5 je array
        $remove = "\n";
        $split = explode($remove, $stationausgabe);
        $split1 = explode($remove, $autoausgabe);

        $stations = [];
        $vehicles = [];
        $semikolon = ";";
        foreach ($split as $key => $val) {
            $stations[] = explode($semikolon, $val);
        }
        echo "<pre>";
        //var_dump($array);
        foreach ($split1 as $key => $val) {
            $vehicles[] = explode($semikolon, $val);
        }
        echo "<pre>";

        $sql = "INSERT INTO station(
    stations_id,
    nombre,
    adresse,
    geographische_Breite,
    geographische_Laenge,
    anzahl_stellplaetze,

) VALUES (

:stations_id, 
:nombre, 
:adresse,
:geographische_Breite,
:geographische_Laenge,
:anzahl_stellplaetze,
)";


        $sql1 = "INSERT INTO station (nombre,adresse,geographische_Breite,geographische_Laenge,anzahl_stellplaetze)
 VALUES (:nombre, :adresse, :geographische_Breite,  :geographische_Laenge,  :anzahl_stellplaetze)";


        $sql2 = "INSERT INTO vehicel(
    bezeichnung,
    typ,
    kennzeichen,
    anzahl_tueren,
    anzahl_sitze,
    standort
) VALUES (

:bezeichnung, 
:typ,
:kennzeichen,
:anzahl_tueren,
:anzahl_sitze,
:standort
)";



        // array ist Stations, Array 2 ist Carss
        foreach ($stations as $va) {
            $nameinDB = $va[0];
            $adresseinDB = $va[1];
            $geographische_BreiteinDB = $va[2];
            $geographische_LaengeinDB = $va[3];
            $anzahl_stellplaetzeinDB = $va[4];
            var_dump($nameinDB);
            var_dump($adresseinDB);
            var_dump($geographische_BreiteinDB);
            var_dump($geographische_LaengeinDB);
            var_dump($anzahl_stellplaetzeinDB);




            $data = [
                // ':stations_id' => $id,
                ':nombre' => $nameinDB,
                ':adresse' => $adresseinDB,
                ':geographische_Breite' => $geographische_BreiteinDB,
                ':geographische_Laenge' => $geographische_LaengeinDB,
                ':anzahl_stellplaetze' => $anzahl_stellplaetzeinDB
            ];



            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute($data);
        }
        echo "<pre>";



        foreach ($vehicles as $valu) {
            $name2inDB = $valu[0];
            $typ2inDB = $valu[1];
            $kennzeichen2inDB = $valu[2];
            $anzahl_tueren2inDB = $valu[3];
            $anzahl_sitze2inDB = $valu[4];
            $standortinDB = $valu[5];
            var_dump($name2inDB);
            var_dump($typ2inDB);
            var_dump($kennzeichen2inDB);
            var_dump($anzahl_tueren2inDB);
            var_dump($anzahl_sitze2inDB);
            var_dump($standortinDB);



            $data2 = [
                // ':stations_id' => $id,
                ':bezeichnung' => $name2inDB,
                ':typ' => $typ2inDB,
                ':kennzeichen' => $kennzeichen2inDB,
                ':anzahl_tueren' => $anzahl_tueren2inDB,
                ':anzahl_sitze' => $anzahl_sitze2inDB,
                ':standort' => $standortinDB
            ];




            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute($data2);
        }
        echo "<pre>";
    }





    //Anzeige ausgeliehene Fahrzeuge
    public function anzeigeausgelieheneFahrzeuge()
    {


        $abfrage = $this->db->prepare(
            " Select * from vehicel where  kennzeichen != '' and standort IS NULL OR standort = ''"
        );
        $abfrage->execute();
        $caraway = $abfrage->fetchAll();

        foreach ($caraway as $ausgeliehen) {
            echo
            "<table border='1px' style=width:'600px'; line-height:'40px';>
    <tr>
       <th>Auto_ID</th>
       <th>Bezeichnung</th>
       <th>Kennzeichen</th>
       <th>Standort</th>
     </tr>
     <tr>
       <td>";
            echo $ausgeliehen['vehicel_id'];
            echo "</td><td>";
            echo $ausgeliehen['bezeichnung'];
            echo "</td><td>";
            echo $ausgeliehen['kennzeichen'];
            echo "</td><td>";
            echo $ausgeliehen['standort'];
            echo "</td><td>";
            echo "</tr>";
            echo "</table>";
        }
    }




    //ausleihen eines Fahrzeugs

    public function kennzeichenabfrage()
    {
        $wertkenn = $_POST["kwert"];
        $query = $this->db->prepare("select * from vehicel where kennzeichen=:kennzeichen");
        $query->bindParam(':kennzeichen', $wertkenn);
        $query->execute();
        $vehicel = $query->fetch();

        if (!$vehicel) {
            echo "Auto mit Kennzeichen " . htmlspecialchars($wertkenn) . " existiert nicht";
            return;
        }

        if ($vehicel['standort'] == null) {
            echo "Es wir versucht ein Fahrzeug auszuleihen dass schon ausgeliehen ist";
            return;
        }

        $nullsetzen = $this->db->prepare("UPDATE vehicel SET standort = null WHERE kennzeichen = :kennzeichen");
        $nullsetzen->bindParam(':kennzeichen', $wertkenn);
        $nullsetzen->execute();
        echo "Fahrzeug ausgeliehen";
    }


    //Rueckgabe eines Fahrzeugs

    public function kennzeichenrueckgabe()
    {
        $wertkennzeichen = $_POST["kennwert"];
        $wertstation = $_POST["kennstation"];

        $query = $this->db->prepare("select * from vehicel where kennzeichen=:kennzeichen");
        $query->bindParam(':kennzeichen', $wertkennzeichen);
        $query->execute();
        $vehicel = $query->fetch();

        if (!$vehicel) {
            echo "Auto mit Kennzeichen " . htmlspecialchars($wertkennzeichen) . " existiert nicht";
            return;
        }

        if ($vehicel['standort'] != null) {
            echo "Es wir versucht ein Fahrzeug zurueckzugeben das nicht ausgeliehen ist";
            return;
        }









        $query = $this->db->prepare("select * from station where nombre=:nombre");
        $query->bindParam(':nombre', $wertstation);
        $query->execute();
        $station = $query->fetch();

        if (!$station) {
            echo "Station" . htmlspecialchars($wertstation) . " existiert nicht";
            return;
        }

        $stellplaetzebesetztstmt = $this->db->prepare("select count(*) from vehicel where standort=:standort");
        $stellplaetzebesetztstmt->bindParam(':standort', $station['standort']);
        $stellplaetzebesetztstmt->execute();
        $stellplaetzebesetzt = $stellplaetzebesetztstmt->fetchColumn();

        if ($stellplaetzebesetzt >= $station['anzahl_stellplaetze']) {
            echo "Stellplatz ist voll";
            return;
        }


        $zurueckgeben = $this->db->prepare("UPDATE vehicel SET standort = :standort WHERE kennzeichen = :kennzeichen");
        $zurueckgeben->bindParam(':kennzeichen', $wertkennzeichen);
        $zurueckgeben->bindParam(':standort', $wertstation);
        $zurueckgeben->execute();
        echo "Auto ist zurueckgegeben";
    }













    public function ajaxrequest()
    {

        $type = htmlspecialchars($_POST["typapi"]);
        $asitze = htmlspecialchars($_POST['sapi']);




        //$type = htmlspecialchars($_POST["typapi"]);
        //$asitze = htmlspecialchars($_POST['sapi']);
        $st = $this->db->prepare("SELECT * FROM vehicel WHERE typ = :typ AND anzahl_sitze >= :anzahl_sitze");
        $st->bindParam(':typ', $type);
        $st->bindParam(':anzahl_sitze', $asitze);
        $st->execute();
        //Array zu Json
        echo json_encode($st->fetchAll());
    }

    // ende der klasse
}
