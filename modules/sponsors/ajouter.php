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

	if(P()){
		if(!P('nom')) error_add('Le champ Nom est obligatoire !');
		if(!P('pays')) error_add('Le champ Pays est obligatoire !');
		if(!P('anneeCreation')) error_add('Le champ année de création pour l\'équipe est obligatoire !');

		if(P('annee') && (P('annee') < P('anneeCreation'))) {
			error_add('Cela n\'a aucun sens ! L\'année de participation du sponsor est plus vieille que l\'année de création de l\'équipe !!!');
		}

		//
		//
		//
		//
		// TODO : vérifier champ nom
		//
		//
		//
		//

		if(!error_exists()){

			// On vérifie qu'un sponsor ayant le même nom ne soit pas déjà dans la 
			// liste des sponsors actifs.
			// Le sponsor actif est le dernier sponsor d'une équipe qui n'a pas disparu.
			$stmt = $bdd->prepare('
				SELECT * FROM TDF_SPONSOR s 
				JOIN TDF_EQUIPE e ON e.N_EQUIPE = s.N_EQUIPE 
				WHERE s.NOM = :nom AND s.CODE_TDF = :pays AND e.ANNEE_DISPARITION IS NULL
			');
			$stmt->bindValue(':nom', P('nom'));
			$stmt->bindValue(':pays', P('pays'));
			$stmt->execute();
			$sponsorExiste = $stmt->fetchAll(PDO::FETCH_OBJ);
			$stmt->closeCursor();

			if($sponsorExiste){
				message_redirect('Il existe déjà un sponsor actif ayant ce nom et ce pays.', 'sponsors/ajouter/');
			}

			// On ajoute la nouvelle équipe
			$stmt = $bdd->prepare('
				INSERT INTO TDF_EQUIPE (N_EQUIPE, ANNEE_CREATION)
				VALUES 
				( (SELECT MAX(N_EQUIPE) FROM TDF_EQUIPE) + 1, :annee)
			');
			$stmt->bindValue(':annee', P('anneeCreation'));
			$stmt->execute();
			$stmt->closeCursor();

			// Maintenant on ajoute le sponsor en récupérant le numéro de l'équipe 
			// que l'on vient de créer.
			$idEquipe = $bdd->lastInsertId();

			$stmt = $bdd->prepare('
				INSERT INTO TDF_SPONSOR (N_EQUIPE, N_SPONSOR, NOM, NA_SPONSOR, CODE_TDF, ANNEE_SPONSOR)
				VALUES 
				(:nEquipe, :nSponsor, :nom, :na, :pays, :annee)
			');
			$stmt->bindValue(':nEquipe', $idEquipe);
			$stmt->bindValue(':nSponsor', 1);
			$stmt->bindValue(':nom', P('nom'));
			$stmt->bindValue(':na', P('nomAbrege'));
			$stmt->bindValue(':pays', P('pays'));
			$stmt->bindValue(':annee', P('annee'));
			if($stmt->execute()){
				message_redirect('Le sponsor et l\'équipe ont bien été créés !');
			}
			else {
				message_redirect('Impossible de créer le sponsor.');
			}
			$stmt->closeCursor();
		}
	}
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
		</fieldset>
		<fieldset>
			<legend>Sponsor</legend>
			<div class="control-group">
				<label class="control-label" for="nom">Nom</label>
				<div class="controls">
					<input type="text" name="nom" value="<?= P('nom') ?>" maxlength="40" /> (en majuscules sans accents)
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="nom">Nom Abrégé</label>
				<div class="controls">
					<input type="text" name="nomAbrege" value="<?= P('nomAbrege') ?>" maxlength="3" /> (3 lettres en majuscules sans accents)
				</div>
			</div>
			<?php 
			$stmt = $bdd->prepare('SELECT * FROM TDF_PAYS ORDER BY NOM ASC');
			$stmt->execute();
			$listePays = $stmt->fetchAll(PDO::FETCH_OBJ);
			$stmt->closeCursor();
			?>
			<div class="control-group">
				<label class="control-label" for="pays">Pays</label>
				<div class="controls">
					<select name="pays">
						<option value="">---</option>
						<?php
						foreach($listePays as $l){
							$add = '';
							if(P('paysD') == $l->CODE_TDF){
								$add = 'selected=selected';
							}
							if(!P('paysD') && $l->CODE_TDF == 'FRA'){
								$add=  'selected=selected';
							}
							echo '<option value="'.$l->CODE_TDF.'" '.$add.'>'.$l->NOM.'</option>';
						} ?>	
					</select>
				</div>
			</div>

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
				<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
			</div>

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
