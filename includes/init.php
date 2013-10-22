<?php

// Charset
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Europe/Paris');
ini_set('arg_separator.output', '&amp;');
mb_internal_encoding('UTF-8');

ini_set('magic_quotes_runtime', 0);

// Inclusion de fonctions diverses
require_once("includes/fonctions.php");
require_once("includes/routage.class.php");

Routage::Dispatch();

// Connexion à la base de données
$db['user'] 	= "copie_tdf";
$db['password'] = "copie_tdf";
$db['db'] 		= "oci:dbname=localhost/xe;charset=UTF8";

$bdd = new PDO($db['db'],$db['user'],$db['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
if(!$bdd){
	exit('Erreur de connexion à la base de données.');
}

unset($db);

$Site['base_address'] = 'http://localhost/projetphp/';

if(!isset($_SESSION['errors'])){
	$_SESSION['errors'] = array();
	$_SESSION['messages'] = array();
}

define('MAX_NB_COUREURS', 9);
