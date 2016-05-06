<?php

class Kaytetty_tarvike {
    function getByTyosuoritusId($id) {

        // Haetaan kaikki käytetyt tarvikkeet joilla tsnro on $1.
        $tulos = pg_query_params('SELECT * FROM kaytetyt_tarvikkeet WHERE tsnro=$1', array(intval($id)));
        // Virheenkäsittely.
        if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
        }
        //Tallennetaan rivit muuttujaan.
        $data = array();
        while ($rivi = pg_fetch_row($tulos)) {
            $data[] = $rivi;
        }
        return $data;
    }

    function create($data, $tsnro, $trnro) {

        // Aloitetaan tapahtuma.
        pg_query("BEGIN");

        // Yritetään päivittää taulua, jos trnro $3 ja tsnro $4 löytyvät.
        $q = 'UPDATE kaytetyt_tarvikkeet SET maara=maara+$1, alennus=$2 WHERE trnro=$3 AND tsnro=$4';
        // Toteutetaan kysely
        pg_query_params($q, array($data['maara'], $data['alennus'], $trnro, $tsnro));

        // Yritetään lisätä arvot tauluun, jos trnro $1 ja tsnro $2 ei löydy.
        $q = 'INSERT INTO kaytetyt_tarvikkeet (trnro, tsnro, nimi, maara, alennus)
               SELECT $1, $2, $3, $4, $5
               WHERE NOT EXISTS (SELECT 1 FROM kaytetyt_tarvikkeet WHERE trnro=$1 AND tsnro=$2)';
        // Toteutetaan kysely.
        pg_query_params($q, array($trnro, $tsnro, $data['nimi'], $data['maara'], $data['alennus']));

        // Päätetään tapahtuma.
        pg_query("COMMIT");
    }
}