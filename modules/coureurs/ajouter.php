<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Coureurs';

if(P()){
	if(!P('nom')) error_add('Le champ "nom" est obligatoire !');
	if(!P('prenom')) error_add('Le champ "prénom" est obligatoire !');
	if(!P('pays')) error_add('Le champ "pays" est obligatoire !');
	
	if(!checkNomCoureur(P('nom'))){
		error_add('Le champ nom doit être entré en majuscules sans accents');
	}

	if(!checkPrenomCoureur(P('prenom'))){
		error_add('Le prénom doit avoir une première lettre majuscule sans accent et les lettres suivantes en minuscules.');
	}

	if(!error_exists()){

		// on vérifie que le coureur n'existe pas déjà dans la base
		$stmt = $bdd->prepare('SELECT * FROM TDF_COUREUR WHERE NOM = :nom AND PRENOM = :prenom AND CODE_TDF = :pays');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->bindValue(':pays', P('pays'));
		$stmt->execute();
		$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		if($correspondance){
			message_redirect('Ce coureur existe déjà !', 'coureurs/ajouter/');
		}

		// on ajoute le coureur dans la base
		$stmt = $bdd->prepare('
			INSERT INTO TDF_COUREUR 
			(N_COUREUR, NOM, PRENOM, CODE_TDF, ANNEE_NAISSANCE, ANNEE_TDF)
			VALUES
			( (SELECT MAX(N_COUREUR)+5 FROM TDF_COUREUR), :nom, :prenom, :pays, :naissance, :anneetdf)');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->bindValue(':pays', P('pays'));
		$stmt->bindValue(':naissance', P('anneeNaissance'));
		$stmt->bindValue(':anneetdf', P('anneeParticipation'));
		if($stmt->execute()){
			message_redirect('Le coureur '.P('prenom').' '.P('nom').' a bien été ajouté à la base !', 'coureurs/liste/', 1);
		}
		$stmt->closeCursor();
	}
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter un coureur</h1>
<br />

<form class="form-horizontal" name="ajouterCoureur" method="post">
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" id="nom" name="nom" placeholder="DELAMARE" value="<?php if(P('nom')) echo P('nom') ?>" maxlength="20">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" id="prenom" name="prenom" placeholder="Jojo" value="<?php if(P('prenom')) echo P('prenom') ?>" maxlength="30">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword">Année de naissance</label>
		<div class="controls">
			<select name="anneeNaissance">
				<option value="">---</option>
				<?php 
				for($i = (date('Y') - 18); $i >= 1900; $i--){
					$add = '';
					if(P('anneeNaissance') == $i){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pays">Pays</label>
		<div class="controls">
			<select name="pays">
				<option value="">---</option>
				<?php 
				$stmt = $bdd->prepare('SELECT * FROM TDF_PAYS ORDER BY NOM ASC');
				$stmt->execute();
				$listePays = $stmt->fetchAll(PDO::FETCH_OBJ);
				$stmt->closeCursor();

				foreach($listePays as $l){
					$add = '';
					if(P('pays') == $l->CODE_TDF){
						$add = 'selected=selected';
					}
					if(!P('pays') && $l->CODE_TDF == 'FRA'){
						$add=  'selected=selected';
					}
					echo '<option value="'.$l->CODE_TDF.'" '.$add.'>'.$l->NOM.'</option>';
				}
				?>	
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword">Année de participation</label>
		<div class="controls">
			<select name="anneeParticipation">
				<option value="">---</option>
				<?php 
				for($i = (date('Y') + 1); $i >= 1996; $i--){
					$add = '';
					if(P('anneeParticipation') == $i){
						$add = 'selected=selected';
					}
					if(!P('anneeParticipation') && $i == date('Y')){
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
