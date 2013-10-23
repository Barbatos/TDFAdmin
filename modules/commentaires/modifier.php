<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

if(!G('id')){
	exit('Arguments invalides!');
}

$currentPage = 'Commentaires';

include_once(BASEPATH.'/modules/header.php');

$stmt = $bdd->prepare('SELECT * FROM TDF_COMMENTAIRE WHERE ANNEE = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosCommentaire = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(empty($infosCommentaire)){
	exit('Année non trouvée !');
}

if(P('commentaire') && P('annee')){

	$stmt = $bdd->prepare('
		UPDATE TDF_COMMENTAIRE 
		SET COMMENTAIRE = :com WHERE ANNEE = :annee');
	$stmt->bindValue(':com', P('commentaire'));
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('Le commentaire a bien été modifié !', 'commentaires/liste/', 1);
}
?>

<h1>Modification du commentaire pour l'année : <?= $infosCommentaire->ANNEE ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>commentaires/liste/">Retour à la liste des commentaires</a></p>

<br />

<form class="form-horizontal" name="modifierCommentaire" method="post">
	<input type="hidden" name="annee" value="<?= $infosCommentaire->ANNEE ?>" />
	
	<div class="control-group">
		<label class="control-label" for="annee">Année</label>
		<div class="controls">
			<input type="text" id="annee" name="annee" value="<?= $infosCommentaire->ANNEE ?>" disabled=disabled>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="commentaire">Commentaire</label>
		<div class="controls">
			<input type="text" name="commentaire" value="<?= $infosCommentaire->COMMENTAIRE ?>" />
		</div>
	</div>
	
	<div class="control-group">
		<button type="submit" class="btn btn-primary btn-info" name="envoyer">Modifier</button>
	</div>
</form>

<?php 
include_once(BASEPATH.'/modules/footer.php');
