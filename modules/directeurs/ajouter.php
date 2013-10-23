<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Directeurs';

if(P()){
	
	if(!P('nom')) error_add('Il faut renseigner un nom !');
	if(!P('prenom')) error_add('Il faut renseigner un prénom !');

	// fonctionne aussi pour directeur :-)
	if(!checkNomCoureur(P('nom'))){
		error_add('Le champ nom est invalide !');
	}

	if(!checkPrenomCoureur(P('prenom'))){
		error_add('le champ prénom est invalide !');
	}

	if(!error_exists()){
		// on vérifie que le directeur n'existe pas déjà dans la base
		$stmt = $bdd->prepare('SELECT * FROM TDF_DIRECTEUR WHERE NOM = :nom AND PRENOM = :prenom');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->execute();
		$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		if($correspondance){
			message_redirect('Un directeur avec ce nom et ce prénom existe déjà !', 'directeurs/ajouter/');
		}

		// on ajoute le directeur
		$stmt = $bdd->prepare('
			INSERT INTO TDF_DIRECTEUR (N_DIRECTEUR, NOM, PRENOM) VALUES ( (SELECT MAX(N_DIRECTEUR) FROM TDF_DIRECTEUR) + 1, :nom, :prenom)');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le directeur a bien été ajouté à la base !', 'directeurs/liste/', 1);
	}
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter un directeur</h1>
<br />

<form class="form-horizontal" name="ajouterDirecteur" method="post">
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" name="nom" placeholder="Nom" value="<?= (P('nom')) ? P('nom') : '' ?>" maxlength="40" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" name="prenom" placeholder="Prénom" value="<?= (P('prenom')) ? P('prenom') : '' ?>" maxlength="40" />
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
