<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

if(!G('annee')){
	message_redirect('Il faut renseigner une année de participation !', 'sponsors/liste/');
}

$currentPage = 'Participations';

include_once(BASEPATH.'/modules/header.php');


if(P()){

	if(!P('directeur')) error_add('Il est obligatoire d\'avoir au moins un directeur !');
	if(!P('equipe')) error_add('Il faut spécifier une équipe/sponsor !');

	if(P('directeur') == P('directeur2')){
		error_add('C\'est absurde ! Le directeur et le co-directeur sont la même personne !');
	}

	if(!error_exists()){
		$stmt = $bdd->prepare('
			INSERT INTO TDF_EQUIPE_ANNEE 
			(ANNEE, N_EQUIPE, N_SPONSOR, N_PRE_DIRECTEUR, N_CO_DIRECTEUR)
			VALUES 
			(:annee, :equipe, (SELECT MAX(N_SPONSOR) FROM TDF_SPONSOR WHERE N_EQUIPE = :equipe), :directeur, :directeur2)
		');
		$stmt->bindValue(':annee', G('annee'));
		$stmt->bindValue(':equipe', P('equipe'));
		$stmt->bindValue(':directeur', P('directeur'));
		$stmt->bindValue(':directeur2', P('directeur2'));

		if($stmt->execute()){
			message_redirect('L\'équipe a bien été ajoutée pour l\'année '.G('annee').' !', 'participations/liste/?annee='.G('annee'), 1);
		}
		else {
			message_redirect('Erreur lors de l\'ajout de l\'équipe :(', 'participations/ajouter-equipe/?annee='.G('annee'));
		}
	}
	else {
		message_redirect('Il existe des erreurs qui empêchent le traitement du formulaire !', 'participations/ajouter-equipe/?annee='.G('annee'));
	}
}


?>

<h2>Ajouter une équipe participant au TDF <?= G('annee') ?></h2>
<br />

<form name="ajouterParticipationEquipe" method="post" action="" class="form-horizontal">
	<fieldset>
		<legend>Equipe</legend>

		<div class="control-group">
			<label class="control-label" for="anneeParticipation">Année de participation</label>
			<div class="controls">
				<input type="text" name="anneeParticipation" value="<?= G('annee') ?>" maxlength="4" disabled="disabled"/> 
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="equipe">Equipe/Sponsor</label>
			<div class="controls">
				<select name="equipe">
					<option value="">---</option>
					<?php 

					$stmt = $bdd->prepare('
						SELECT * FROM TDF_SPONSOR s 
						JOIN TDF_EQUIPE e ON e.N_EQUIPE = s.N_EQUIPE
						WHERE 
							e.ANNEE_DISPARITION IS NULL 
							AND s.N_SPONSOR = (SELECT MAX(N_SPONSOR) FROM TDF_SPONSOR WHERE N_EQUIPE = s.N_EQUIPE)
							AND NOT EXISTS (
								SELECT * FROM TDF_EQUIPE_ANNEE WHERE N_EQUIPE = e.N_EQUIPE AND ANNEE = :annee
							)
						ORDER BY s.NOM ASC
					');
					$stmt->bindValue(':annee', G('annee'));
					$stmt->execute();
					$listeSponsors = $stmt->fetchAll(PDO::FETCH_OBJ);
					$stmt->closeCursor();

					foreach($listeSponsors as $ls){
					?>
					<option value="<?= $ls->N_EQUIPE ?>"><?= $ls->NOM ?></option>
					<?php 	
					}
					?>
				</select>
			</div>
		</div>

		<?php 
		$stmt = $bdd->prepare('
			SELECT * FROM TDF_DIRECTEUR d 
			WHERE NOT EXISTS (
				SELECT * FROM TDF_EQUIPE_ANNEE 
				WHERE N_PRE_DIRECTEUR = d.N_DIRECTEUR OR N_CO_DIRECTEUR = d.N_DIRECTEUR AND ANNEE = :annee
			)
		');
		$stmt->bindValue(':annee', G('annee'));
		$stmt->execute();
		$listeDirecteurs = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();
		?>

		<div class="control-group">
			<label class="control-label" for="directeur">Directeur principal</label>
			<div class="controls">
				<select name="directeur">
					<option value="">---</option>
					<?php 
					foreach($listeDirecteurs as $ls){
					?>
					<option value="<?= $ls->N_DIRECTEUR ?>"><?= $ls->PRENOM . ' ' . $ls->NOM ?></option>
					<?php 	
					}
					?>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="directeur2">Co-Directeur</label>
			<div class="controls">
				<select name="directeur2">
					<option value="">---</option>
					<?php 
					foreach($listeDirecteurs as $ls){
					?>
					<option value="<?= $ls->N_DIRECTEUR ?>"><?= $ls->PRENOM . ' ' . $ls->NOM ?></option>
					<?php 	
					}
					?>
				</select>
			</div>
		</div>

		<div class="control-group">
				<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
			</div>
	</fieldset>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
