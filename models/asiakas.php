<?php

class Asiakas {

    function getById($id) {
        // Haetaan asiakas jolla on anro $1.
        $tulos = pg_query_params('SELECT * FROM asiakas WHERE anro=$1', array(intval($id)));
        // Virheentarkistus.
        if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
        }
        // Tallennetaan tulos muuttujaan.
        $data = pg_fetch_row($tulos);
        return $data;
    }

    function getAll() {
        // Haetaan kaikki asiakkaat.
        $tulos = pg_query('SELECT * FROM asiakas');
        // Virheentarkistus.
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

    function create($data) {
        // Lisätään asiakas annetuilla arvoilla.
        $kysely = 'INSERT INTO asiakas(enimi, snimi, kaupunki, postinumero, katuosoite)
			VALUES ($1, $2, $3, $4, $5)';
        pg_query_params($kysely, array($data['enimi'], $data['snimi'], $data['kaupunki'],
            intval($data['postinumero']), $data['katuosoite']));
    }
}