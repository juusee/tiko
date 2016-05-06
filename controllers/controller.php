<?php

if (!$yhteys = pg_connect(DBConfig::$db_connection))
    die("Tietokantayhteyden luominen epäonnistui.");

include PATH_ROOT . DS . "views" .DS . "view.php";
include PATH_ROOT . DS . "models" .DS . "tyosuoritus.php";
include PATH_ROOT . DS . "models" .DS . "asiakas.php";
include PATH_ROOT . DS . "models" .DS . "tarvike.php";
include PATH_ROOT . DS . "models" .DS . "tyo.php";
include PATH_ROOT . DS . "models" .DS . "kaytetty_tarvike.php";
include PATH_ROOT . DS . "models" .DS . "tehty_tyo.php";
include PATH_ROOT . DS . "models" .DS . "lasku.php";

class MainController {

	protected $view;
	protected $tyosuoritus;
	protected $asiakas;
	protected $tarvike;
	protected $tyo;
	protected $kaytetty_tarvike;
	protected $tehty_tyo;
	protected $lasku;

	function __construct() {
		$this->view = new View();
		$this->tyosuoritus = new TyoSuoritus();
		$this->asiakas = new Asiakas();
		$this->tarvike = new Tarvike();
		$this->tyo = new Tyo();
		$this->kaytetty_tarvike = new Kaytetty_tarvike();
		$this->tehty_tyo = new Tehty_tyo();
		$this->lasku = new Lasku();
	}

	function ajaxGetTyosuoritus($tsnro) {
		$data = $this->tyosuoritus->getById($tsnro);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetAsiakas($anro) {
		$data = $this->asiakas->getById($anro);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetTyosuoritukset() {
		$data = $this->tyosuoritus->getAll();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetAllLaskut() {
		$data = $this->lasku->getAll();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxTarkistaLaskut() {
		$this->lasku->tarkistaLaskut();
		$data = $this->lasku->getAll();
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'Laskut tarkistettu!', 'laskut' => $data));
	}

	function ajaxMaksaLasku($lnro) {
		$this->lasku->maksaLasku($lnro);
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'Lasku maksettu!'));
	}

	function ajaxGetAsiakkaat() {
		$data = $this->asiakas->getAll();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetTarvikkeet() {
		$data = $this->tarvike->getAll();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetTyot() {
		$data = $this->tyo->getAll();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetKaytetytTarvikkeet($tsnro) {
		$data = $this->kaytetty_tarvike->getByTyosuoritusId($tsnro);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetTehdytTyot($tsnro) {
		$data = $this->tehty_tyo->getByTyosuoritusId($tsnro);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxGetLaskut($tsnro) {
		$data = $this->lasku->getByTyosuoritusId($tsnro);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function ajaxaCreateKaytettyTarvike($tsnro) {
		$data = array();

		$trnro = $this->tarvike->getTarvikeNroByName($_POST['nimi']);
		$this->kaytetty_tarvike->create($_POST, $tsnro, $trnro);
		$data['message'] = 'Tarvikkeet lisätty / muutettu!';

		$data['tarvike'] = array('nimi' => $_POST['nimi'], 'maara' => $_POST['maara'], 'alennus' => $_POST['alennus']);
		// response back.
		echo json_encode($data);
	}

	function ajaxCreateTehtyTyo($tsnro) {
		$data = array();

		$tynro = $this->tyo->getTyoNroByName($_POST['nimi']);
		$this->tehty_tyo->create($_POST, $tsnro, $tynro);

		$data['message'] = 'Työ lisätty / muutettu!';

		$data['tyo'] = array('nimi' => $_POST['nimi'], 'tunnit' => $_POST['tunnit'], 'alennus' => $_POST['alennus']);
		// response back.
		echo json_encode($data);
	}

	function ajaxCreateTyosuoritus() {
		$data = array();

		$this->tyosuoritus->create($_POST);
		$data['message'] = 'Työsuoritus lisätty!';
		// response back.
		echo json_encode($data);
	}

	function ajaxCreateAsiakas() {
		$data = array();
		$this->asiakas->create($_POST);
		$data['message'] = 'Asiakas lisätty!';
		// response back
		echo json_encode($data);
	}

	function ajaxCreateLasku() {
		$data = array();
		$this->lasku->create($_POST);
		$data['message'] = 'Lasku lisätty!';
		// response back
		echo json_encode($data);
	}
}


















































