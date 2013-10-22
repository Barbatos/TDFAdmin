<?php 

$currentPage = 'Accueil';

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Accueil</h1>
<br />

<?php 
if(!$admin->isLogged()){

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
