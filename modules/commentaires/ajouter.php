<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Commentaires';

if(P()){
	
	// on vérifie que l'année n'existe pas déjà dans la base
	$stmt = $bdd->prepare('SELECT * FROM TDF_COMMENTAIRE WHERE ANNEE = :annee');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();

	if($correspondance){
		message_redirect('Un commentaire pour cette année existe déjà !', 'commentaires/ajouter/');
	}

	// on ajoute le commentaire
	$stmt = $bdd->prepare('
		INSERT INTO TDF_COMMENTAIRE (ANNEE, COMMENTAIRE) VALUES (:annee, :com)');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->bindValue(':com', P('commentaire'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('Le commentaire a bien été ajoutée à la base !', 'commentaires/liste/', 1);
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter un commentaire</h1>
<br />

<form class="form-horizontal" name="ajouterCommentaire" method="post">
	<div class="control-group">
		<label class="control-label" for="annee">Année</label>
		<div class="controls">
			<select name="annee">
				<option value="">---</option>
				<?php 
				for($i = (date('Y')+10); $i >= 1950; $i--){
					$add = '';
					if(P('annee') == $i){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="repos">Commentaire</label>
		<div class="controls">
			<input type="text" name="commentaire" placeholder="Mon commentaire" />
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
