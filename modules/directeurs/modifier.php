<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

if(!G('id')){
	exit('Arguments invalides!');
}

$currentPage = 'Directeurs';

include_once(BASEPATH.'/modules/header.php');

$stmt = $bdd->prepare('SELECT * FROM TDF_DIRECTEUR WHERE N_DIRECTEUR = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosDirecteur = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(empty($infosDirecteur)){
	exit('Directeur non trouvé !');
}

if(P('nom') && P('prenom')){

	if(!checkNomCoureur(P('nom'))){
		error_add('Le nom est invalide !');
	}

	if(!checkPrenomCoureur(P('prenom'))){
		error_add('Le prénom est invalide !');
	}

	if(!error_exists()){
		$stmt = $bdd->prepare('
			UPDATE TDF_DIRECTEUR 
			SET NOM = :nom, PRENOM = :prenom 
			WHERE N_DIRECTEUR = :id
		');
		$stmt->bindValue(':id', G('id'));
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le directeur a bien été modifié !', 'directeurs/liste/', 1);
	}
}
?>

<h1>Modification du directeur: <?= $infosDirecteur->NOM . ' ' . $infosDirecteur->PRENOM ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>directeurs/liste/">Retour à la liste des directeurs</a></p>

<br />

<form class="form-horizontal" name="modifierDirecteur" method="post">
	
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" id="nom" name="nom" value="<?= $infosDirecteur->NOM ?>" >
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" id="prenom" name="prenom" value="<?= $infosDirecteur->PRENOM ?>" >
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Modifier</button>
		</div>
	</div>
</form>

<?php 
include_once(BASEPATH.'/modules/footer.php');
