<?php

define('PATH_ROOT' , dirname(__FILE__));
define("DS", DIRECTORY_SEPARATOR);

include PATH_ROOT . DS . "config" . DS . "db.php";
include PATH_ROOT . DS . "controllers" . DS . "controller.php";

include PATH_ROOT . DS . "AltoRouter.php";

$router = new AltoRouter();
$mainController = new MainController();


$router->map('GET', '/tikoAngular/ajax/getTyosuoritukset', function() use ($mainController) {
	$mainController->ajaxGetTyosuoritukset();
});

$router->map('GET', '/tikoAngular/ajax/getLaskut', function() use ($mainController) {
	$mainController->ajaxGetAllLaskut();
});

$router->map('GET', '/tikoAngular/ajax/tarkistaLaskut', function() use ($mainController) {
	$mainController->ajaxTarkistaLaskut();
});

$router->map('POST', '/tikoAngular/ajax/maksaLasku/[i:lnro]', function($lnro) use ($mainController) {
	$mainController->ajaxMaksaLasku($lnro);
});

$router->map('GET', '/tikoAngular/ajax/getAsiakkaat', function() use ($mainController) {
	$mainController->ajaxGetAsiakkaat();
});

$router->map('GET', '/tikoAngular/ajax/getTarvikkeet', function() use ($mainController) {
	$mainController->ajaxGetTarvikkeet();
});

$router->map('GET', '/tikoAngular/ajax/getTyot', function() use ($mainController) {
	$mainController->ajaxGetTyot();
});

$router->map('GET', '/tikoAngular/ajax/getTyosuoritus/[i:tsnro]', function($tsnro) use ($mainController) {
	$mainController->ajaxGetTyosuoritus($tsnro);
});

$router->map('GET', '/tikoAngular/ajax/getAsiakas/[i:anro]', function($anro) use ($mainController) {
	$mainController->ajaxGetAsiakas($anro);
});

$router->map('GET', '/tikoAngular/ajax/getKaytetytTarvikkeet/[i:tsnro]', function($tsnro) use ($mainController) {
	$mainController->ajaxGetKaytetytTarvikkeet($tsnro);
});

$router->map('GET', '/tikoAngular/ajax/getTehdytTyot/[i:tsnro]', function($tsnro) use ($mainController) {
	$mainController->ajaxGetTehdytTyot($tsnro);
});

$router->map('GET', '/tikoAngular/ajax/getLaskut/[i:tsnro]', function($tsnro) use ($mainController) {
	$mainController->ajaxGetLaskut($tsnro);
});

$router->map('POST', '/tikoAngular/ajax/createKaytettyTarvike/[i:tsnro]', function($tsnro) use ($mainController) {
	$mainController->ajaxaCreateKaytettyTarvike($tsnro);
});

$router->map('POST', '/tikoAngular/ajax/createTehtyTyo/[i:tsnro]', function($tsnro) use ($mainController) {
	$mainController->ajaxCreateTehtyTyo($tsnro);
});

$router->map('POST', '/tikoAngular/ajax/createTyosuoritus', function() use ($mainController) {
	$mainController->ajaxCreateTyosuoritus();
});

$router->map('POST', '/tikoAngular/ajax/createAsiakas', function() use ($mainController) {
	$mainController->ajaxCreateAsiakas();
});

$router->map('POST', '/tikoAngular/ajax/createLasku', function() use ($mainController) {
	$mainController->ajaxCreateLasku();
});

$router->map('GET', '*', function() {
	include PATH_ROOT . DS . "public" . DS . "index.html";
});

// match current request url
$match = $router->match();

// call closure or throw 404 status
if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
}/* else {
	// no route was matched
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}*/