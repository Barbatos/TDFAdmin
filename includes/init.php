<?php
session_start();

// Inclusion de fonctions diverses
include_once("includes/fonctions.php");

// Connexion à la base de données
$db['user'] 	= "copie_tdf";
$db['password'] = "copie_tdf";
$db['db'] 		= "oci:dbname=localhost/xe";

$bdd = new PDO($db['db'],$db['user'],$db['password']);
if(!$bdd){
	exit('Erreur de connexion à la base de données.');
}

unset($db);

$Site['base_address'] = 'http://localhost/projetphp/';

if(!isset($_SESSION['errors'])){
	$_SESSION['errors'] = array();
	$_SESSION['messages'] = array();
}