<?php

class Tehty_tyo {
    function getByTyosuoritusId($id) {
        // Haetaan tehdyt tyot joidenka tsnro on $1.
        $tulos = pg_query_params('SELECT * FROM tehdyt_tyot WHERE tsnro=$1', array(intval($id)));
        // Virheenkäsittely.
        if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
        }
        // Tallennetaan rivit muuttujaan.
        $data = array();
        while ($rivi = pg_fetch_row($tulos)) {
            $data[] = $rivi;
        }
        return $data;
    }

    function create($data, $tsnro, $tynro) {
        // Aloitetaan tapahtuma.
        pg_query("BEGIN");

        // Päivitetään taulu, jos tsnro ja tynro löytyvät.
        $q = 'UPDATE tehdyt_tyot SET tunnit=tunnit+$1, alennus=$2 WHERE tsnro=$3 AND tynro=$4';
        pg_query_params($q, array($data['tunnit'], $data['alennus'], $tsnro, $tynro));

        // Lisätään tauluun, jos tsnro ja tynro ei löydy.
        $q = 'INSERT INTO tehdyt_tyot (tsnro, tynro, nimi, tunnit, alennus)
               SELECT $1, $2, CAST($3 AS VARCHAR), $4, $5
               WHERE NOT EXISTS (SELECT 1 FROM tehdyt_tyot WHERE tsnro=$1 AND tynro=$2)';
        
        pg_query_params($q, array($tsnro, $tynro, $data['nimi'], $data['tunnit'], $data['alennus']));
        // Päätetään tapahtuma.
        pg_query("COMMIT");
    }
}