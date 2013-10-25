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

ini_set("session.use_trans_sid","0");
ini_set("url_rewriter.tags","");

session_start();

// On définit le répertoire racine
define('BASEPATH', dirname(__FILE__));

// On inclut le fichier d'initialisation du site
require_once(BASEPATH.'/includes/init.php');

// Si jamais on veut se déconnecter
if(G('logout') && $admin->isLogged()) {
	$admin->logout();
	message_redirect('Vous êtes maintenant déconnecté, à bientôt !', '', 1);
}

// On inclut le contrôleur correspondant à la page demandée en http
if(is_file($module = BASEPATH.'/modules/'.Routage::GetModule().'/'.Routage::GetAction().'.php')) {
	require_once($module);
}
