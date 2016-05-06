<?php

class Lasku {

    function getAll() {
        // Haetaan kaikki laskut. 
        $tulos = pg_query("SELECT * FROM lasku");
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

    function getByTyosuoritusId($id) {
        // Haetaan kaikki laskut missä tsnro on $1.
        $tulos = pg_query_params('SELECT * FROM lasku WHERE tsnro=$1', array(intval($id)));
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

    function create($data) {
        // Lisätään uusi lasku tauluun.
        $kysely = 'INSERT INTO lasku(kokonaissumma, laskutuslisa, erapaiva, mones_muistutus,tsnro, tarkistettu) VALUES ($1, $2, $3, $4, $5, $6)';
        $date = date('Y-m-d', strtotime($data['erapaiva'] . ' +1 day'));
        pg_query_params($kysely, array($data['kokonaissumma'], 0, $date, 0, $data['tsnro'], 'false'));
    }

    function tarkistaLaskut() {
        // Aloitetaan tapahtuma.
        pg_query("BEGIN");
        // Haetaan kaikki laskut.
        $tulos = pg_query("SELECT * FROM lasku");
        // Virheenkäsittely.
        if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
        }
        // tallennetaan rivit muuttujaan lasku.
        $laskut = array();
        while ($rivi = pg_fetch_row($tulos)) {
            $laskut[] = $rivi;
        }

        $current_date = date('Y-m-d');
        $tarkistetut = array();
        $uudet_laskut = array();

        // Muodostetaan muistutus tai karhulasku, jos tarvetta (tarkistetaan laskut).
        foreach ($laskut as $lasku) {
            $tarkistettu = $lasku[9];
            if ($tarkistettu == 'f') {
                $tarkistetut[] = $lasku[0];
                $maksupvm = $lasku[5];
                $erapaiva = $lasku[4];
                if ($current_date > $erapaiva && (!$maksupvm || $maksupvm > $erapaiva)) {
                    $mones_muistutus = $lasku[6] + 1;
                    $laskutuslisa = 5;
                    $kokonaissumma = $lasku[1] + $laskutuslisa;
                    $viivastyskorko = 0;
                    $erapaiva = $lasku[4];
                    $erapaiva = date('Y-m-d', strtotime($erapaiva . ' +1 month'));
                    $edellinen_lasku = $lasku[0];
                    $tsnro = $lasku[8];
                    if ($mones_muistutus > 2) { // karhu
                        $date_then = date('Y-m-d', strtotime($erapaiva . ' -' . $mones_muistutus . ' month'));
                        $date1 = date_create($date_then);
                        $date2 = date_create($current_date);
                        $date_difference_in_moths = date_diff($date1, $date2)->m;
                        $date_difference_in_days = date_diff($date1, $date2)->d + (30 * $date_difference_in_moths);
                        $viivastyskorko = (16 / 100 * $kokonaissumma * $date_difference_in_days) / 360;
                    }
                    $uudet_laskut[] = [$kokonaissumma, $laskutuslisa, $viivastyskorko, $erapaiva, $mones_muistutus,
                        $edellinen_lasku, $tsnro, 'false'];
                }
            }
        }

        // Päivitetään rivit.
        foreach ($tarkistetut as $row)  {
            $kysely = 'UPDATE lasku SET tarkistettu=true WHERE lnro=$1';
            pg_query_params($kysely, array($row));
        }
        // Lisätään uudet laskut.
        foreach ($uudet_laskut as $lasku)  {
            $kysely = 'INSERT INTO lasku(kokonaissumma, laskutuslisa, viivastyskorko, erapaiva,
                mones_muistutus, edellinen, tsnro, tarkistettu)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8)';
            pg_query_params($kysely, $lasku);
        }
        // Päätetään tapahtuma.
        pg_query("COMMIT");
    }

    function maksaLasku($lnro) {
        // Päivitetään laskun maksupvm nykyiseksi päiväksi.
        $kysely  = 'UPDATE lasku SET maksupvm=$1 WHERE lnro=$2';
        $current_date = date('Y-m-d');
        pg_query_params($kysely, array($current_date, $lnro));
    }
}