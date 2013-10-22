<?php 
// Test de différents noms de coureurs

require_once('../includes/fonctions.php');
header('Content-Type: text/html; charset=UTF-8');

$prenomsCoureurs = array(
	'',
	'123',
	'"',
	'""',
	'\'',
	'\'\'',
	'\\\\',
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',

	'A',
	'a',
	'Aa',
	'aA',
	'aAa',
	'AaA',
	'AaAa',
	'aAaA',

	'A-A',
	'Aa-A',
	'Aa-aA',
	'Aa-Aa',
	'Aa-AaaA',

	'A\'A',
	'Aa\'A',
	'Aa\'aA',
	'Aa\'Aa',
	'Aa\'AaaA',

	'A A',
	'Aa A',
	'Aa aA',
	'Aa Aa',
	'Aa AaaA',

	'Aa--Aa--Aa',

	'Aa--Aa-Aa',
	'Aa-Aa--Aa',

	'Aa--Aa Aa',
	'Aa Aa--Aa',

	'Aa\'Aa--Aa',
	'Aa--Aa\'Aa',

	'éèàçöïôêëù',
	'€',
	'$',
	'+=/*-+',
	'\0',

	'A1a',
	'1Aa',
	'Aa1',

	' ',
	' Aa ',
	' Aa',
	'Aa ',

	'-Aa-',
	'Aa-',
	'-Aa',

	'\'Aa\'',
	'Aa\'',
	'\'Aa',

);

foreach($prenomsCoureurs as $val){
	echo $val.' : '.((checkPrenomCoureur($val)) ? 1 : 0).'<br />';
}
