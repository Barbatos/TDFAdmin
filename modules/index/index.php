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

$currentPage = 'Accueil';

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Accueil</h1>
<br />

<?php 
// Si on n'est pas connecté
if(!$admin->isLogged()){

	// Si un formulaire est envoyé, c'est le formulaire de connexion
	// On tente de connecter l'utilisateur
	if(P()){
		$admin->login();
	}
?>

<p>Bienvenue sur l'interface d'administration du Tour de France. Merci de vous connecter !</p>

<br /><br />

<form name="connexion" method="post" action="" class="form-horizontal">
	<div class="control-group">
		<label class="control-label" for="pseudo">Pseudo</label>
		<div class="controls">
			<input type="text" name="pseudo" placeholder="Pseudonyme" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="pseudo">Password</label>
		<div class="controls">
			<input type="password" name="password" placeholder="Mot de Passe" />
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<input type="submit" name="submit" class="btn" value="Connexion" />
		</div>
	</div>
</form>

<?php 
}
else {
?>

<p>Hey <strong><?= $admin->getPseudo() ?></strong> ! Bienvenue sur l'interface d'administration du Tour de France.</p>
<?php 
}

include_once(BASEPATH.'/modules/footer.php'); 
