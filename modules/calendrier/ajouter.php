<?php
$currentPage = 'Calendrier';

if(P()){
	
	// on vérifie que l'année n'existe pas déjà dans la base
	$stmt = $bdd->prepare('SELECT * FROM TDF_ANNEE WHERE ANNEE = :annee');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();

	if($correspondance){
		message_redirect('Cette année existe déjà !', 'calendrier/ajouter/');
	}

	// on ajoute l'année dans la base
	$stmt = $bdd->prepare('
		INSERT INTO TDF_ANNEE (ANNEE, JOUR_REPOS) VALUES (:annee, :repos)');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->bindValue(':repos', P('repos'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('L\'année '.P('annee').' a bien été ajoutée à la base !', 'calendrier/ajouter/', 1);
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter une année au calendrier</h1>
<br />

<form class="form-horizontal" name="ajouterAnnee" method="post">
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
		<label class="control-label" for="repos">Jours de repos</label>
		<div class="controls">
			<select name="repos">
				<option value="">---</option>
				<?php 
				for($i = 1; $i <= 10; $i++){
					$add = '';
					if(P('repos') == $i){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
