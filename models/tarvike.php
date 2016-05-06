<?php

class Tarvike {

    function getAll() {
        // Haetaan kaikki tarvikkeet.
        $tulos = pg_query("SELECT * FROM tarvike");
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

    function getTarvikeNroByName($name) {
        // Haetaan tarvikkeet joidenka nimi on $1.
        $tulos = pg_query_params('SELECT trnro FROM tarvike WHERE nimi=$1', array($name));
        // Virheenkäsittely
        if (!$tulos) {
            echo "Virhe kyselyssä getTarvikeNroByName.\n";
            exit;
        }
        // Tallennetaan rivit muuttujaan.
        $data = pg_fetch_row($tulos);
        return $data[0];
    }
}
