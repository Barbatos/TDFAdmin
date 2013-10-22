<?php
ini_set("session.use_trans_sid","0");
ini_set("url_rewriter.tags","");
session_start();

define('BASEPATH', dirname(__FILE__));

require_once(BASEPATH.'/includes/init.php');

if(G('logout') && $admin->isLogged()) {
	$admin->logout();
	message_redirect('Vous êtes maintenant déconnecté, à bientôt !', '', 1);
}

if(is_file($module = BASEPATH.'/modules/'.Routage::GetModule().'/'.Routage::GetAction().'.php')) {
	require_once($module);
}
