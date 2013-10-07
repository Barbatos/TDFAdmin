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

function verifCoureur(){
	if(!preg_match('/^([A-Z]+)([A-Z-\' ]+)([A-Z]+)$/', P('nom'))) {
		error_add('Le nom doit être entré en majuscules sans accents.');
	}

	if(!preg_match('/^[A-Z]{1}([a-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\-\' ]+)([A-Z]?){1}([a-zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)$/', P('prenom'))){
		error_add('Le prénom doit avoir une première lettre majuscule sans accent et les lettres suivantes en minuscules.');
	} 
}

function verifEpreuve(){
	if(!preg_match('/^([A-Z]+)([A-Z-\' ]+)([A-Z]+)$/', P('villeD'))) {
		error_add('La ville de départ doit être entrée en majuscules sans accents.');
	}

	if(!preg_match('/^([A-Z]+)([A-Z-\' ]+)([A-Z]+)$/', P('villeA'))) {
		error_add('La ville d\'arrivée doit être entrée en majuscules sans accents.');
	}

	if(!preg_match('/^([0-9]+)([,]){0,1}([0-9]+)$/', P('distance'))) {
		error_add('La distance doit être un nombre (éventuellement à virgule)');
	}

	if(!preg_match('/^([0-9]+)([,]){0,1}([0-9]+)$/', P('moyenne'))) {
		error_add('La moyenne doit être un nombre (éventuellement à virgule)');
	}

	if(!preg_match('/^([0-9]+){2}([\/])([0-9]+){2}([\/])([0-9]+){2}$/', P('date'))) {
		error_add('La date doit être sous la forme jj/mm/aa');
	}
}

