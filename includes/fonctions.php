<?php

function est_connecte(){
	if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
		return true;
	}
	else {
		return false;
	}
}

function s($string)
{
	return stripslashes(stripslashes(htmlspecialchars($string)));
}

function G($str, $index = NULL)
{
	if(!isset($index)):
		if(isset($_GET[$str])):
			return s($_GET[$str]);
		
		else:
			return NULL;
		endif;
	
	else:
		if(isset($_GET[$str]) AND is_array($_GET[$str]) AND isset($_GET[$str][$index])):
			return s($_GET[$str][$index]);
			
		else:
			return NULL;
		endif;
	endif;	
}

// $_POST
function P($str = NULL, $index = NULL)
{
	if(!isset($str)){
		if(isset($_POST) && !empty($_POST)){
			return true;
		}
		else {
			return false;
		}
	}

	if(!isset($index)){
		if(isset($_POST[$str])){
			return s($_POST[$str]);
		} 
		else {
			return NULL;
		}
	}
	else {
		if(isset($_POST[$str]) AND is_array($_POST[$str]) AND isset($_POST[$str][$index])){
			return s($_POST[$str][$index]);
		}
		else {
			return NULL;
		}
	}
	
}

function error_add($error){
	$_SESSION['errors'][]['error'] = $error;
}

function message_add($message){
	$_SESSION['messages'][]['message'] = $message;
}

function message_redirect($message, $url = 'index.php', $type = 0){
	global $Site;

	if(empty($url)){
		$url = $_SERVER['HTTP_REFERER'];
	}
	else {
		$url = $Site['base_address'].$url;
	}

	if($type == 1){
		message_add($message);
	}
	else {
		error_add($message);
	}

	header('Location: '.$url);
	exit();
}

function error_exists(){
	return (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) ? true : false;
}

// @url http://php.net/manual/fr/function.explode.php
function multiexplode ($delimiters, $string) {
	$ready = str_replace($delimiters, $delimiters[0], $string);
	$launch = explode($delimiters[0], $ready);

	return $launch;
}

function replaceAccents($var){
	$var = str_replace(array('ä', 'à', 'â', 'ã', 'Ä', 'Â', 'À', 'Á', 'Ã'), 			'a', $var);
	$var = str_replace(array('č', 'ĉ', 'ç', 'Ç'), 									'c', $var);
	$var = str_replace(array('é', 'è', 'ê', 'ë', 'Ê', 'Ë', 'É', 'È'), 				'e', $var);
	$var = str_replace(array('î', 'ï', 'ì', 'Î', 'Ï', 'Ì'), 						'i', $var);
	$var = str_replace(array('ñ'), 													'n', $var);
	$var = str_replace(array('ô', 'ò', 'ö', 'õ', 'ø', 'ð' , 'Ô', 'Ö', 'Ò', 'Õ'), 	'o', $var);
	$var = str_replace(array('ù', 'û', 'ü', 'Ù', 'Û', 'Ü'), 						'u', $var);
	$var = str_replace(array('Æ', 'æ'), 											'ae', $var);
	$var = str_replace(array('œ', 'Œ'), 											'oe', $var);
	$var = str_replace(array('ÿ', 'ý'), 											'y', $var);
	return $var;
}

function checkNomCoureur($nom){
	$nom = replaceAccents($nom);
	$nom = strtoupper($nom);
	$nom = trim($nom);
	$nom = trim($nom, "-");

	preg_match('/[A-Z]/', $nom, $test1);
	preg_match('/-{3,}/', $nom, $test2);
	preg_match('/^[A-Z\' -]/', $nom, $test3);
	preg_match('/([A-Z\' ]+)([-]){2}([A-Z\' ]+)([-]){2}([A-Z\' ]+)/', $nom, $test4);
	preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>]/', $nom, $test5);

	if($test1 && !$test2 && $test3 && !$test4 && !$test5 && (strlen($nom) < 20)) {
		$_POST['nom'] = $nom;
		return true;
	}
	else {
		return false;
	}
}

function checkPrenomCoureur($prenom){
	
	$prenom = strtolower($prenom);
	$prenom = trim($prenom);
	$exploded = multiexplode(array("-"," "), $prenom);
	$prenom2 = "";

	
	if(preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>]/', $prenom)) {
		return false;
	}
	
	if(strlen($prenom) > 30) {
		return false;
	}
	
	foreach($exploded as $key => $t) {
		$t = ucfirst($t);
		if(!preg_match('/^[A-Z]/', $t)) {
			return false;
		}
		if(!preg_match('/[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]$/', $t)) {
			return false;
		}
		if(preg_match('/([A-Z]){1}([a-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*([A-Z])+/', $t)) {
			return false;
		}
		$prenom2 .= " ".$t;
	}
	$prenom = trim($prenom2);
	$_POST['prenom'] = $prenom;
	return true;
}

function verifEpreuve(){
	if(checkNomVilleEpreuve( P('villeD') )) {
		error_add('La ville de départ doit être entrée en majuscules sans accents.');
	}

	if(!checkNomVilleEpreuve( P('villeA') )) {
		error_add('La ville d\'arrivée doit être entrée en majuscules sans accents.');
	}

	if(!preg_match('/^([0-9]+)([.]){0,1}([0-9]+)$/', P('distance'))) {
		error_add('La distance doit être un nombre (éventuellement décimal avec un point. par ex: 303.3)');
	}

	if(!preg_match('/^([0-9]+)([.]){0,1}([0-9]+)$/', P('moyenne'))) {
		error_add('La moyenne doit être un nombre (éventuellement décimal avec un point. par ex: 50.13)');
	}

	if(!preg_match('/^([0-9]+){2}([\/])([0-9]+){2}([\/])([0-9]+){2}$/', P('date'))) {
		error_add('La date doit être sous la forme jj/mm/aa');
	}
}

function checkNomVilleEpreuve($nom){
	$nom = replaceAccents($nom);
	$nom = strtoupper($nom);
	$nom = trim($nom);
	$nom = trim($nom, "-");

	preg_match('/[A-Z1-9]/', $nom, $test1);
	preg_match('/-{3,}/', $nom, $test2);
	preg_match('/^[A-Z1-9\' -]/', $nom, $test3);
	preg_match('/([A-Z1-9\' ]+)([-]){2}([A-Z1-9\' ]+)([-]){2}([A-Z1-9\' ]+)/', $nom, $test4);
	preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>]/', $nom, $test5);

	if($test1 && !$test2 && $test3 && !$test4 && !$test5 && (strlen($nom) < 20)) {
		return true;
	}
	else {
		return false;
	}
}

function checkAbregSponsor($nom) {

	$nom = replaceAccents($nom);
	$nom = strtoupper($nom);
	$nom = trim($nom);


	if (strlen($nom) > 3) {
		return false;
	}

	if(!preg_match('/[A-Z]/', $nom)) {
		return false;
	}

	$_POST['nomAbrege'] = $nom;
	return true;
}
