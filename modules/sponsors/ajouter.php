<?php
$currentPage = 'Sponsors';

include_once(BASEPATH.'/modules/header.php');
?>

<?php 

// On demande de choisir entre ajouter un nouveau sponsor et une nouvelle équipe ou bien ajouter
// un sponsor à une équipe existante
if(!G('equipe') && !G('new')){
?>

	<h1>Ajouter un sponsor</h1>
	<br />

	<form method="get" action="" >
		Ajouter un sponsor à... <br />
		- Une équipe existante : 
			<select name="equipe">
				<option value="">---</option>
			</select>
		<br />
		- Une nouvelle équipe : <input type="submit" name="new" value="Go" class="btn" />
	</form>

<?php 	
}

// On veut créer un nouveau sponsor pour une nouvelle équipe
else if(!G('equipe') && G('new')){
?>

	<h1>Ajouter un sponsor pour une nouvelle équipe</h1>
	<br />

    <form class="form-horizontal" name="ajouterSponsor" method="post">
		<fieldset>
			<legend>Equipe</legend>
			<div class="control-group">
				<label class="control-label" for="anneeCreation">Année de Création</label>
				<div class="controls">
					<select name="anneeCreation">
						<option value="">---</option>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Sponsor</legend>
		</fieldset>
	</form>
         	                                                                    
<?php 
}
else {
?>

	<form class="form-horizontal" name="ajouterSponsor" method="post">

		<div class="control-group">
			<label class="control-label" for="villeD">Ville départ</label>
			<div class="controls">
				<input type="text" name="villeD" value="<?= P('villeD') ?>" /> (en majuscules sans accents)
			</div>
		</div>
		
		<div class="control-group">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</form>

<?php 	
}
?>



<?php include_once(BASEPATH.'/modules/footer.php'); ?>
