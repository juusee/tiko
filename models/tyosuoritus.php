<?php

class TyoSuoritus {

	function getById($id) {
		// Haetaan työsuoritukset joilla tsnro on $1.
		$tulos = pg_query_params('SELECT * FROM tyosuoritus WHERE tsnro=$1', array(intval($id)));
		// Virheenkäsittely.
		if (!$tulos) {
			echo "Virhe kyselyssä.\n";
			exit;
		}
		// Tallennetaan rivit muuttujaan.
		$data = pg_fetch_row($tulos);
		return $data;
	}

	function getAll() {
		// Haetaan kaikki työsuoritukset.
		$tulos = pg_query("SELECT * FROM tyosuoritus");
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
		// Lisätään työsuoritus annetuilla arvoilla.
		$kysely = 'INSERT INTO tyosuoritus(tila, kokonaissumma, kaupunki, postinumero, katuosoite, tyyppi, anro)
			VALUES ($1, $2, $3, $4, $5, $6, $7)';
		$paivitys = pg_query_params($kysely, array($data['tila'], 0, $data['kaupunki'],
			intval($data['postinumero']), $data['katuosoite'], $data['tyyppi'], intval($data['anro'])));
	}
}