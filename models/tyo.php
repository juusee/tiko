<?php

class Tyo {

    function getAll() {
        // Haetaan kaikki työt.
        $tulos = pg_query("SELECT * FROM tyo");
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

    function getTyoNroByName($name) {
        // Haetaan tyot joiden nimi on $1.
        $tulos = pg_query_params('SELECT tynro FROM tyo WHERE nimi=$1', array($name));
        // Virheenkäsittely.
        if (!$tulos) {
            echo "Virhe kyselyssä getTarvikeNroByName.\n";
            exit;
        }
        // Tallennetaan rivit muuttujaan.
        $data = pg_fetch_row($tulos);
        return $data[0];
    }
}
