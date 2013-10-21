<?php 
// Test de différents noms de coureurs

require_once('../includes/fonctions.php');
header('Content-Type: text/html; charset=UTF-8');

$nomsCoureurs = array(
	'Michel',
	'Mïchelîn',
	'Jean-Paul',
	'Jean-Paul-Olivier',
	'Jean Paul Olivier',
	'Jean\'s Derp--Herp-Derp Blop',
	'MAR"ISE',
	'nADaL',
	'NADAL',
	'NAD-A-L',
	'NA--D-A-L',
	'NA--Dal',
	'F ederer',
	'FedereR',
	'Jean\' Paul',
	'\'Jean Paul',
	'Jean\'Paul',
	'Jean\'-Pierre',
	'Jean Pierre--Durand',
	'Jean-Pierre_Durand',
	'Jean\\Dupont',
	'Jeançé',
	'J---e-an',
	'Jean--Paul--Durand',
);

foreach($nomsCoureurs as $val){
	echo $val.' : '.((checkNomCoureur($val)) ? 1 : 0).'<br />';
}
