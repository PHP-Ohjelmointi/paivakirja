<?php

// Tällä sivulla luetaan päiväkirjamerkintöjä.

// Sisällytetään sivulle usein käytettyjä funktioita.
require("funktioita.php");

// Avataan conn tietokantaan.
$conn = yhdista_tietokantaan();

// Jos merkintää ei pyydetty, käytetään tyhjää ID-arvoa.
if(isset($_GET["id"]))
{
  $id = mysql_real_escape_string($_GET["id"], $conn);

  // Tehdään tietokantahaku.
  $tulos = mysql_query("select luotu, muokattu, otsikko, teksti
                        from merkinta where id = '$id'", $conn);
  if(!$tulos)
    exit("Tietokantahaku epäonnistui: ".mysql_error($conn));

  // Haetaan haun tuottama tulos.
  $rivi = mysql_fetch_assoc($tulos);
  if(!$rivi)
  {
    // Tietokannasta ei löytynytkään vastaavaa riviä,
    // näytetään virheilmoitus.
    html_alku("Päiväkirjani");
    echo "<p>Päiväkirjamerkintää ei löytynyt!</p>";
    echo "<p><a href=\"index.php\">",
         "Siirry takaisin etusivulle</a></p>";
    html_loppu();
  }
  else
  {
    // Näytetään päiväkirjamerkintä.
    html_alku($rivi["otsikko"], muotoile_paivays($rivi["luotu"]));
    echo "<p><a href=\"index.php\">",
         "Siirry takaisin etusivulle</a></p>";

    if($rivi["muokattu"])
      echo "<p class=\"muokattu\">Merkintää on muokattu ",
        muotoile_paivays($rivi["muokattu"]),
        "</p>\n";

    echo "<p>", nl2br(htmlspecialchars($rivi["teksti"])), "</p>";
    echo "<p><a href=\"kirjoita.php?muokkaa_id=", $id, "\">";
    echo "Muokkaa merkintää";
    echo "</a> tai <a href=\"poista.php?poista_id=", $id, "\">";
    echo "poista merkintä";
    echo "</a></p>\n";
    html_loppu();
  }
}
else
{
   html_alku("Päiväkirjani");
   echo "<p>Päiväkirjamerkintää ei määritetty!</p>";
   echo "<p><a href=\"index.php\">",
        "Siirry takaisin etusivulle</a></p>";
   html_loppu();
}

?>
