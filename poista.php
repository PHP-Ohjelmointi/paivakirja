<?php

// Tällä sivulla poistetaan päiväkirjamerkintöjä.

require("funktioita.php");

$conn = yhdista_tietokantaan();

// Tämä kohta käsitellään kun poistaminen on varmistettu.
if(isset($_POST["varmistus"]) && isset($_POST["poista_id"]))
{
  $poistettava = mysql_real_escape_string($_POST["poista_id"]);
  $sql = "delete from merkinta where id = '$poistettava'";
  $tulos = mysql_query($sql, $conn);

  if($tulos)
  {
    if(mysql_affected_rows($conn) == 1)
    {
      html_alku("Merkintä on poistettu");
      echo "<p>Merkintä on nyt poistettu. Voit palata
        <a href=\"index.php\">etusivulle</a>.</p>\n";
      html_loppu();
    }
    else
    {
      html_alku("Merkintää ei poistettu");
      echo "<p>Merkinnän poistaminen epäonnistui! Voit palata
        <a href=\"index.php\">etusivulle</a>.</p>\n";
      html_loppu();
    }
  }
  else exit("Tietokantahaku epäonnistui! ".mysql_error($conn));
}
// Tämä kohta käsitellään kun poistamista ei ole vielä varmistettu
elseif(isset($_GET["poista_id"]))
{
  html_alku("Poista merkintä");
?>
<p>Haluatko varmasti poistaa merkinnän?</p>
<form action="poista.php" method="post">
<input type="hidden" name="poista_id"
value="<?php echo $_GET["poista_id"]; ?>" />
<input type="submit" name="varmistus" value="Poista merkintä" />
</form>
<p><a href="lue.php?id=<?php echo $_GET["poista_id"]; ?>">
  Ei, en halua poistaa merkintää.
</a></p>
<?php
  html_loppu();
}
// Tänne päädytään vain siinä tapauksessa,
// kun poistettavaa merkintää ei ole kerrottu!
else
{
  html_alku("Virhe!");
  echo "<p>Poistettavaa päiväkirjamerkintää ei ole määritetty!
    Voit palata <a href=\"index.php\">etusivulle</a>.</p>\n";
  html_loppu();
}

?>
