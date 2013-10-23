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

*/

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
