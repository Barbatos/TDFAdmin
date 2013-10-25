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

/**
 * Vérifie si le visiteur est connecté
 *
 * @return 	true si l'utilisateur est connecté, sinon false
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function est_connecte(){
	if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
		return true;
	}
	else {
		return false;
	}
}

/**
 * Sécurise une chaîne de caractères.
 *
 * @param 	$string - chaîne de caractère
 * @return 	la chaîne sécurisée
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function s($string)
{
	return stripslashes(stripslashes(htmlspecialchars($string)));
}

/**
 * Récupère et sécurise un élément $_GET
 *
 * @param 	$string - chaîne de caractère - le paramètre à récupérer
 * @param 	$index - si string est un tableau, permet de récupérer un
 *				champ spécifique du tableau.
 * @return 	le contenu du champ
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
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

/**
 * Récupère et sécurise un élément $_POST
 *
 * @param 	$string - chaîne de caractère - le paramètre à récupérer
 * @param 	$index - si string est un tableau, permet de récupérer un
 *				champ spécifique du tableau.
 * @return 	le contenu du champ
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
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

/**
 * Ajoute un message d'erreur.
 *
 * @param 	$error - chaîne de caractères - l'erreur à afficher
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function error_add($error){
	$_SESSION['errors'][]['error'] = $error;
}

/**
 * Ajoute un message de confirmation.
 *
 * @param 	$message - chaîne de caractères - le message à afficher
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function message_add($message){
	$_SESSION['messages'][]['message'] = $message;
}

/**
 * Redirige l'utilisateur sur une page avec affichage
 * d'un message ou d'une erreur.
 *
 * @param 	$message - le message à afficher
 * @param 	$url - l'url sur laquelle rediriger l'utilisateur
 * @param 	$type - le type du message:
 *				1: message normal
 *				autre: message d'erreur
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
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

/**
 * Vérifie s'il existe des erreurs à afficher.
 *
 * @return 	true s'il y a des erreurs, sinon false
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function error_exists(){
	return (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) ? true : false;
}

/**
 * Effectue un multiexplode.
 *
 * @param 	$delimiters - la liste des délimiteurs
 * @param 	$string - la chaîne à traiter
 * @return 	la chaîne traitée
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 * @link 	http://php.net/manual/fr/function.explode.php
 */
function multiexplode ($delimiters, $string) {
	$ready = str_replace($delimiters, $delimiters[0], $string);
	$launch = explode($delimiters[0], $ready);

	return $launch;
}

/**
 * Remplace les accents dans une chaîne de caractères.
 *
 * @param 	$var - la chaîne de caractères à traiter
 * @return 	la chaîne traitée
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function replaceAccents($var){
	$var = str_replace(array('ä', 'à', 'â', 'ã', 'Ä', 'Â', 'À', 'Á', 'Ã', 'Å'), 	'a', $var);
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

/**
 * Remplace les accents majuscules uniquement 
 * dans une chaîne de caractères.
 *
 * @param 	$var - la chaîne de caractères à traiter
 * @return 	la chaîne traitée
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function replaceAccents2($var){
	$var = str_replace(array('Ä', 'Â', 'À', 'Á', 'Ã'), 								'a', $var);
	$var = str_replace(array('Ç'), 													'c', $var);
	$var = str_replace(array('Ê', 'Ë', 'É', 'È'), 									'e', $var);
	$var = str_replace(array('Î', 'Ï', 'Ì'), 										'i', $var);
	$var = str_replace(array('Ô', 'Ö', 'Ò', 'Õ'), 									'o', $var);
	$var = str_replace(array('Ù', 'Û', 'Ü'), 										'u', $var);
	$var = str_replace(array('Æ'), 													'ae', $var);
	$var = str_replace(array('Œ'), 													'oe', $var);
	return $var;
}

/**
 * Vérifie que le nom d'un coureur respecte les règles
 * syntaxiques exigées par le cahier des charges.
 *
 * @param 	$nom - la chaîne de caractères du nom du coureur
 * @return 	false si le nom est invalide, sinon true
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
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

/**
 * Vérifie que le prénom d'un coureur respecte les règles
 * syntaxiques exigées par le cahier des charges.
 *
 * @param 	$prenom - la chaîne de caractères du prénom du coureur
 * @return 	false si le prénom est invalide, sinon true
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function checkPrenomCoureur($prenom){
	
	$prenom = strtolower($prenom);
	$prenom = replaceAccents2($prenom);
	$prenom = trim($prenom);
	$exploded = multiexplode(array("-"," "), $prenom);
	$prenom2 = "";

	
	if(preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>1-9]/', $prenom)) {
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

/**
 * Vérifie que les informations d'une épreuve respectent les règles
 * syntaxiques exigées par le cahier des charges.
 *
 * @author 	Charles 'Barbatos' Duprey
 * @access 	public
 */
function verifEpreuve(){
	if(!checkNomVilleEpreuve( P('villeD') )) {
		error_add('La ville de départ doit être entrée en majuscules sans accents.');
	}

	if(!checkNomVilleEpreuve( P('villeA') )) {
		error_add('La ville d\'arrivée doit être entrée en majuscules sans accents.');
	}

	if(!preg_match('/^([0-9]+)([.]){0,1}([0-9]+)$/', P('distance')) || (P('distance') < 0)) {
		error_add('La distance doit être un nombre positif (éventuellement décimal avec un point. par ex: 303.3)');
	}

	if(!preg_match('/^([0-9]+)([.]){0,1}([0-9]+)$/', P('moyenne')) || (P('moyenne') < 0)) {
		error_add('La moyenne doit être un nombre positif (éventuellement décimal avec un point. par ex: 50.13)');
	}

	if(!preg_match('/^([0-9]){2}([\/])([0-9]){2}([\/])([0-9]){2}$/', P('date'))) {
		error_add('La date doit être sous la forme jj/mm/aa');
	}
}

/**
 * Vérifie que le nom d'une ville d'épreuve respecte les règles
 * syntaxiques exigées par le cahier des charges.
 *
 * @author 	Charles 'Barbatos' Duprey
 * @param 	$nom - le nom de ville
 * @return 	false si le nom est invalide, sinon true
 * @access 	public
 */
function checkNomVilleEpreuve($nom){
	$nom = replaceAccents($nom);
	$nom = strtoupper($nom);
	$nom = trim($nom);
	$nom = trim($nom, "-");

	preg_match('/[A-Z1-9]/', $nom, $test1);
	preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>]/', $nom, $test5);

	if($test1 && !$test5 && (strlen($nom) < 20)) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Vérifie que le nom abrégé d'un sponsor respecte les règles
 * syntaxiques exigées par le cahier des charges.
 *
 * @author 	Charles 'Barbatos' Duprey
 * @param 	$nom - le nom abrégé
 * @return 	false si le nom est invalide, sinon true
 * @access 	public
 */
function checkAbregeSponsor($nom) {

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

/**
 * Vérifie que le cat code d'une catégorie d'épreuve respecte 
 * les règles syntaxiques exigées par le cahier des charges.
 *
 * @author 	Charles 'Barbatos' Duprey
 * @param 	$nom - le cat code
 * @return 	false si le nom est invalide, sinon true
 * @access 	public
 */
function checkCatCode($nom){
	$nom = replaceAccents($nom);
	$nom = strtoupper($nom);

	if(preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>1-9]/', $nom)) {
		return false;
	}

	if(strlen($nom) > 3){
		return false;
	}

	$_POST['cat'] = $nom;
	return true;
}

/**
 * Vérifie que le tep code d'une catégorie d'épreuve respecte 
 * les règles syntaxiques exigées par le cahier des charges.
 *
 * @author 	Charles 'Barbatos' Duprey
 * @param 	$nom - le tep code
 * @return 	false si le nom est invalide, sinon true
 * @access 	public
 */
function checkTepCode($nom){
	$nom = replaceAccents($nom);
	$nom = strtoupper($nom);

	if(preg_match('/[&~\"#\{\(\[\|`_\\\^@\)\]°\}\+=\$¤£¨%µ*!§:;\.,\?<>1-9]/', $nom)) {
		return false;
	}

	if(strlen($nom) > 2){
		return false;
	}

	$_POST['tep'] = $nom;
	return true;
}
