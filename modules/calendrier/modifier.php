<?php

if(!G('id')){
	exit('Arguments invalides!');
}

$currentPage = 'Calendrier';

include_once(BASEPATH.'/modules/header.php');

$stmt = $bdd->prepare('SELECT * FROM TDF_ANNEE WHERE ANNEE = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosAnnee = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(empty($infosAnnee)){
	exit('Année non trouvée !');
}

if(P('repos') && P('annee')){

	$stmt = $bdd->prepare('
		UPDATE TDF_ANNEE 
		SET JOUR_REPOS = :repos WHERE ANNEE = :annee');
	$stmt->bindValue(':repos', P('repos'));
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('L\'année '.P('annee').' a bien été modifiée !', 'calendrier/liste/', 1);
}
?>

<h1>Modification de l'année: <?= $infosAnnee->ANNEE ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>calendrier/liste/">Retour au calendrier</a></p>

<br />

<form class="form-horizontal" name="modifierAnnee" method="post">
	<input type="hidden" name="annee" value="<?= $infosAnnee->ANNEE ?>" />
	
	<div class="control-group">
		<label class="control-label" for="ann">Année</label>
		<div class="controls">
			<input type="text" id="ann" name="ann" value="<?= $infosAnnee->ANNEE ?>" disabled=disabled>
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
					if($infosAnnee->JOUR_REPOS == $i){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<button type="submit" class="btn btn-primary btn-info" name="envoyer">Modifier</button>
	</div>
</form>

<?php 
include_once(BASEPATH.'/modules/footer.php');
