<?php

if(!G('annee')){
	message_redirect('Il faut renseigner une année de participation !', 'participations/liste/');
}

if(!G('equipe')){
	message_redirect('Il faut renseigner une équipe !', 'participations/liste/');
}

$stmt = $bdd->prepare('SELECT * FROM TDF_EQUIPE_ANNEE WHERE ANNEE = :annee AND N_EQUIPE = :equipe');
$stmt->bindValue(':annee', G('annee'));
$stmt->bindValue(':equipe', G('equipe'));
$stmt->execute();
$infosEquipe = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(!$infosEquipe){
	message_redirect('Cette équipe n\'est pas encore inscrite au TDF pour l\'année '.G('annee').' !', 'participations/liste/');
}

$currentPage = 'Participations';

include_once(BASEPATH.'/modules/header.php');


if(P()){
	if(!P('coureur')) error_add('Il est obligatoire de renseigner un coureur !');

	if(!error_exists()){

		// Attribution du numéro de dossard
		// Chaque équipe a 9 numéros allant de X1 à X9 pour les 9 coureurs

		// On commence par chercher si l'équipe a déjà d'autres coureurs d'enregistrés
		$stmt = $bdd->prepare('
			SELECT MAX(N_DOSSARD) AS MAX
			FROM TDF_PARTICIPATION 
			WHERE N_EQUIPE = :equipe AND ANNEE = :annee
		');
		$stmt->bindValue(':equipe', G('equipe'));
		$stmt->bindValue(':annee', G('annee'));
		$stmt->execute();
		$nbCoureursEq = $stmt->fetch(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		$numDossard = -1;

		// L'équipe a bien des coureurs d'enregistrés
		// Dans ce cas, il suffit de faire n+1 pour le numéro de dossard
		if($nbCoureursEq->MAX >= 1){
			$numDossard = $nbCoureursEq->MAX + 1;
		}

		// Pas de coureurs enregistrés.
		// Il faut récupérer le chiffre des dizaines du numéro de dossard 
		// de l'équipe précédente et y ajouter 1.
		else {
			$stmt = $bdd->prepare('SELECT MAX(N_DOSSARD) AS MAX FROM TDF_PARTICIPATION WHERE ANNEE = :annee');
			$stmt->bindValue(':annee', G('annee'));
			$stmt->execute();
			$maxDossard = $stmt->fetch(PDO::FETCH_OBJ);
			$stmt->closeCursor();

			// Si aucun coureur n'a encore été enregistré, on démarre 
			// l'enregistrement des dossards au numéro 1.
			if(!$maxDossard->MAX || $maxDossard->MAX == 0){
				$numDossard = 1;
			}
			else {
				if((int)$maxDossard->MAX < 10){
					$numDossard = '11';
				}
				else {
					$numDossard = ((int)($maxDossard->MAX[0])+1).'1';
				}
			}
			
		}

		if(!$numDossard || $numDossard == -1){
			message_redirect('Une erreur est survenue lors de la génération du numéro de dossard du coureur', 'participations/liste/');
		}

		if(P('jeune'))
			$jeune = 'o';
		else {
			$jeune = null;
		}

		$stmt = $bdd->prepare('
			INSERT INTO TDF_PARTICIPATION 
			(ANNEE, N_COUREUR, N_EQUIPE, N_SPONSOR, N_DOSSARD, JEUNE)
			VALUES 
			(:annee, :coureur, :equipe, :sponsor, :dossard, :jeune)
		');
		$stmt->bindValue(':annee', G('annee'));
		$stmt->bindValue(':coureur', P('coureur'));
		$stmt->bindValue(':equipe', $infosEquipe->N_EQUIPE);
		$stmt->bindValue(':sponsor', $infosEquipe->N_SPONSOR);
		$stmt->bindValue(':dossard', $numDossard);
		$stmt->bindValue(':jeune', $jeune);

		if($stmt->execute()){
			message_redirect('Le coureur a bien été ajouté à cette équipe pour l\'année '.G('annee').' !', 'participations/liste-coureurs/?annee='.G('annee').'&equipe='.$infosEquipe->N_EQUIPE, 1);
		}
		else {
			message_redirect('Le coureur n\'a pas pu être ajouté à la base de données ! :(', 'participations/liste-coureurs/?annee='.G('annee').'&equipe='.$infosEquipe->N_EQUIPE);
		}

		$stmt->closeCursor();

	}
}
?>

<h2>Ajouter un coureur à l'équipe <?= G('equipe') ?> pour l'année <?= G('annee') ?></h2>
<br />

<form name="ajouterParticipationEquipe" method="post" action="" class="form-horizontal">
	<fieldset>
		<legend>Coureur</legend>

		<div class="control-group">
			<label class="control-label" for="coureur">Coureur</label>
			<div class="controls">
				<select name="coureur">
					<option value="">---</option>
					<?php 

					$stmt = $bdd->prepare('
						SELECT * FROM TDF_COUREUR c 
						WHERE NOT EXISTS (
							SELECT * FROM TDF_PARTICIPATION p WHERE p.N_COUREUR = c.N_COUREUR AND p.ANNEE = :annee
						)
						ORDER BY c.NOM ASC
					');
					$stmt->bindValue(':annee', G('annee'));
					$stmt->execute();
					$listeCoureurs = $stmt->fetchAll(PDO::FETCH_OBJ);
					$stmt->closeCursor();

					foreach($listeCoureurs as $lc){
					?>
					<option value="<?= $lc->N_COUREUR ?>"><?= $lc->PRENOM ?> <?= $lc->NOM ?></option>
					<?php 	
					}
					?>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="equipe">Jeune</label>
			<div class="controls">
				<input type="checkbox" name="jeune" />
			</div>
		</div>

		<div class="control-group">
				<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
			</div>
	</fieldset>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
