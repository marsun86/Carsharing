fetch("api.php")
    .then(function () {
        // The JSON data will arrive here


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {


            if (this.readyState == 4 && this.status == 200) {
                var json = JSON.parse(this.responseText);



                for (i = 0; i < json.length; i++) {
                    var daten = json[i];

                    html += "<tr><td>";
                    html += daten['vehicel_id'];
                    html += "</td><td>";
                    html += daten['bezeichnung'];
                    html += "</td><td>";

                    var wb = daten['typ'];
                    if (wb == 0) {
                        html += "Kleinwagen";
                    } else if (wb == 1) {

                        html += "Kompaktwagen";
                    } else if (wb == 2) {
                        html += "Mittelklasse";
                    } else if (wb == 3) {
                        html += "Van";
                    } else {
                        html += "Transporter";
                    }
                    html += "</td><td>";
                    html += daten['kennzeichen'];
                    html += "</td><td>";
                    html += daten['anzahl_tueren'];
                    html += "</td><td>";
                    html += daten['anzahl_sitze'];
                    html += "</td><td>";
                    html += daten['standort'];
                    html += "</td></tr>";

                }












                document.getElementById("demo").innerHTML = json;

                document.querySelector('typzeigen').innerHTML = json.typ;
                document.querySelector('sitzezeigen').innerHTML = json.anzahl_sitze;




            }

            xmlhttp.open("POST", "./api.php", true)
            xmlhttp.send();

        };



    })
    .catch(function (err) {
        // If an error occured, you will catch it here
    });
