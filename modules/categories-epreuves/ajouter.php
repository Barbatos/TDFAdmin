<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Categories';

if(P()){
	
	if(!P('cat')) error_add('Le cat code est obligatoire !');
	if(!P('tep')) error_add('Le tep code est obligatoire !');
	if(!P('libelle')) error_add('Le libellé est obligatoire !');

	if(!error_exists()){
		// on vérifie que la catégorie n'existe pas déjà dans la base
		$stmt = $bdd->prepare('SELECT * FROM TDF_CATEGORIE_EPREUVE WHERE CAT_CODE = :id');
		$stmt->bindValue(':id', P('id'));
		$stmt->execute();
		$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		if($correspondance){
			message_redirect('Une catégorie d\'épreuve existe déjà !', 'categories-epreuves/ajouter/');
		}

		// on ajoute la catégorie
		$stmt = $bdd->prepare('
			INSERT INTO TDF_CATEGORIE_EPREUVE (CAT_CODE, TEP_CODE, LIBELLE) VALUES (:cat, :tep, :libelle)');
		$stmt->bindValue(':cat', P('cat'));
		$stmt->bindValue(':tep', P('tep'));
		$stmt->bindValue(':libelle', P('libelle'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('La catégorie d\'épreuve a bien été ajoutée à la base !', 'categories-epreuves/liste/', 1);
	}
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter une catégorie d'épreuve</h1>
<br />

<form class="form-horizontal" name="ajouterCategorieEpreuve" method="post">
	<div class="control-group">
		<label class="control-label" for="cat">Cat code</label>
		<div class="controls">
			<input type="text" name="cat" maxlength="3" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tep">Tep code</label>
		<div class="controls">
			<input type="text" name="tep" maxlength="2" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="libelle">Libellé</label>
		<div class="controls">
			<input type="text" name="libelle" maxlength="40" />
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
