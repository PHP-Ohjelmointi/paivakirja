<?php

// Tällä sivulla luodaan ja muokataan päiväkirjamerkintöjä.

// Tätä funktiota kutsutaan jotta
// merkistöt saadaan toimimaan oikein.
mb_internal_encoding("UTF-8");

require("funktioita.php");

$conn = yhdista_tietokantaan();

$virheita = "";

if(isset($_REQUEST["muokkaa_id"]))
  $muokkaa_id = $_REQUEST["muokkaa_id"];
else
  $muokkaa_id = false;

// Ollaanko tallentamassa merkintää?
if(isset($_POST["tallenna"]))
{
  // Tarkistetaan tiedot virheiden varalta.
  $otsikon_pituus = mb_strlen($_POST["otsikko"]);
  $tekstin_pituus = mb_strlen($_POST["teksti"]);
  if($otsikon_pituus > 100)
    $virheita .= "Otsikko on liian pitkä, ".
        $otsikon_pituus.
        " merkkiä. (Maksimi 100 merkkiä.) ";
  if($otsikon_pituus < 1)
    $virheita .= "Et voi jättää otsikkoa tyhjäksi. ";
  if($tekstin_pituus > 65535)
    $virheita .= "Teksti on liian pitkä, ".
        $tekstin_pituus.
        " merkkiä. (Maksimi 65535 merkkiä.) ";

  if($virheita == "")
  {
    $otsikko = mysql_real_escape_string($_POST["otsikko"], $conn);
    $teksti = mysql_real_escape_string($_POST["teksti"], $conn);

    if($muokkaa_id != false)
    {
      $muokkaa_id = mysql_real_escape_string($_POST["muokkaa_id"],
                $conn);
      $sql = "update merkinta set otsikko = '$otsikko',
        teksti = '$teksti' where id = '$muokkaa_id'";
    }
    else
    {
      $sql = "insert into merkinta (id, luotu, muokattu, otsikko,
        teksti)  values (null, now(), null,
        '$otsikko', '$teksti')";
    }

    $tulos = mysql_query($sql, $conn);
    if(!$tulos) exit("Tietokantahaku epäonnistui: ".mysql_error());
    if($muokkaa_id != false)
      uudelleenohjaa("lue.php?id=$muokkaa_id");
    else
      uudelleenohjaa("lue.php?id=".mysql_insert_id($conn));
    exit;
  }

  $otsikko = $_POST["otsikko"];
  $teksti = $_POST["teksti"];
}
// Tai ehkä halutaan muokata olemassaolevaa merkintää?
elseif($muokkaa_id != false)
{
  $id = mysql_real_escape_string($muokkaa_id);

  $tulos = mysql_query("select otsikko, teksti from merkinta
        where id = '$id'", $conn);
  if(!$tulos) exit("Tietokantahaku epäonnistui: ".mysql_error());

  $rivi = mysql_fetch_assoc($tulos);
  if(!$rivi)
  {
    $virheita .= "Merkintää ei löytynyt! Voit kuitenkin
                  kirjoittaa uuden päiväkirjamerkinnän.";
    $otsikko = $teksti = "";
    $muokkaa_id = false;
  }
  else
  {
    // Merkintä löytyi.
    $otsikko = $rivi['otsikko'];
    $teksti = $rivi['teksti'];
  }
}
// Tai sitten lisätään uusi.
else
{
  $otsikko = $teksti = "";
}

// Aloitetaan sivu.
if($muokkaa_id == false)
  html_alku("Lisää uusi merkintä");
else
  html_alku("Muokkaa merkintää");

if($virheita != "")
  echo "<p>", $virheita, "</p>";
?>
<p>
<a href="index.php">Siirry takaisin etusivulle tallentamatta</a>
</p>

<form action="kirjoita.php" method="post">
<label for="otsikkokentta">Otsikko</label>
<input type="text" id="otsikkokentta" maxlength="100" name="otsikko"
value="<?php echo $otsikko; ?>" />
<label for="tekstikentta">Teksti</label>
<textarea id="tekstikentta" name="teksti"><?php

echo $teksti;

?></textarea>
<?php
if($muokkaa_id)
  echo "<input type=\"hidden\" name=\"muokkaa_id\" value=\"",
    $muokkaa_id, "\" />";
?>
<input type="submit" name="tallenna" value="Tallenna merkintä" />
</form>
<?php

html_loppu();

?>
