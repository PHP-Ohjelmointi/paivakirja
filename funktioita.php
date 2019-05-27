<?php

// Tässä tiedostossa on perusfunktioita.

// Tämä funktio yhdistää tietokantaan, ja ottaa käyttöön
// paivakirja-tietokannan.
function yhdista_tietokantaan()
{
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
}

// Tulostaa HTML-sivun alkuosan valitun otsikon kanssa.
function html_alku($otsikko, $paivays = "")
{
  echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"";
  echo "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
  echo "<html>\n";
  echo "<head>\n";
  echo "  <meta http-equiv=\"Content-Type\"
      content=\"text/html;charset=utf-8\" />\n";
  echo "  <link rel=\"stylesheet\" type=\"text/css\"
      href=\"paivakirja.css\" />\n";
  echo "  <title>", $otsikko, "</title>\n";
  echo "</head>\n";
  echo "<body>\n\n";
  echo "<div id=\"otsikkopaivays\">", $paivays, "</div>\n";
  echo "<h1>", $otsikko, "</h1>\n";
  echo "<div id=\"sisalto\">\n\n";
}

// Tulostaa HTML-sivun loppuosan.
function html_loppu()
{
  echo "\n</div>\n\n";
  echo "</body>\n";
  echo "</html>\n";
}

// Muotoilee tietokannasta saatavan päivämäärän
// käyttäjäystävällisemmäksi.
function muotoile_paivays($standardimuotoinen)
{
  // Standardimuotoinen päiväys on muotoa "VVVV-KK-PP HH:MM:SS".
  // Erotellaan ensin päiväys ja kellonaika.
  $paivays_ja_aika = explode(" ", $standardimuotoinen);
  // Sitten erotellaan päiväyksen osat.
  $paivays = explode("-", $paivays_ja_aika[0]);
  // Palautetaan osat yhdistettynä eri järjestyksessä,
  // ja edeltävät nollat poistettuna.
  return intval($paivays[2]).".".
         intval($paivays[1]).".".
         $paivays[0];
}

// Uudelleenohjaa käyttäjän toiselle sivulle.
function uudelleenohjaa($sivu)
{
  header("Location: http://" . $_SERVER["HTTP_HOST"]
    . dirname($_SERVER["PHP_SELF"])
    . "/" . $sivu);
}

?>
