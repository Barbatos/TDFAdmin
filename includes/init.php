<?php
/*

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

@package 	TDFAdmin
@authors 	Charles 'Barbatos' Duprey <cduprey@f1m.fr> && Adrien 'soullessoni' Demoget
@created 	20/09/2013
@copyright 	(c) 2013 TDFAdmin
@licence 	http://opensource.org/licenses/MIT
@link 		https://github.com/Barbatos/TDFAdmin

*/

// On définit le charset et le timezone
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Paris');
ini_set('arg_separator.output', '&amp;');

// On force la désactivation des magic quotes
ini_set('magic_quotes_runtime', 0);

// Inclusion de fonctions diverses
require_once("includes/fonctions.php");
require_once("includes/routage.class.php");
require_once("includes/admins.class.php");

// On récupère les paramètres GET
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

// Adresse de base du projet
$Site['base_address'] = 'http://localhost/projetphp/';

// Pas de messages à afficher ? On initialise quand même 
// les variables afin de ne pas avoir de notices de php.
if(!isset($_SESSION['errors'])){
	$_SESSION['errors'] = array();
	$_SESSION['messages'] = array();
}

// Nombre maximum de coureurs par équipe
define('MAX_NB_COUREURS', 9);

// On crée une instance de la classe admins
$admin = new Admins($bdd);
