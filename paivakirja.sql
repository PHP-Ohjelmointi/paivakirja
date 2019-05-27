
-- Tämän tiedoston SQL-lausekkeet luovat tietokannan päiväkirja-esimerkkisovellukselle.

-- Luodaan tietokanta, joka käyttää UTF-8-merkistökoodausta ja aakkosjärjestystä
-- joka sopii suomalaiseen sovellukseen.
create database paivakirja character set utf8 collate utf8_swedish_ci;

use paivakirja;

-- Tietokanta on yksinkertainen: siinä on vain yksi taulu.
create table merkinta (
id       integer auto_increment primary key,
luotu    datetime not null,
muokattu timestamp null on update current_timestamp,
otsikko  varchar(100) not null,
teksti   text
);

-- Käyttäjälle annetaan vain välttämättömät oikeudet tietokantaan
grant insert, select, update, delete on paivakirja.* to 'pkirja'@'localhost' identified by 'salasana';

-- Alla oleva komento on vaihtoehtoinen versio ylemmälle grant-komennolle, mutta on hiukan rajatumpi
-- ja vaatii päivittämistä myöhemmin jos tietokantaan lisätään tauluja.
-- grant insert, select, update, delete on paivakirja.merkinta to 'pkirja'@'localhost' identified by 'salasana';


-- Päiväkirjasovellus voidaan poistaa tietokannasta seuraavilla komennoilla:
drop user 'pkirja'@'localhost';
drop database paivakirja;