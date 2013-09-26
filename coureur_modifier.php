<?php

include_once("includes/init.php");

if(!G('id')){
	exit('Arguments invalides!');
}

$currentPage = 'Coureur';

include_once("header.php");

$stmt = $bdd->prepare('SELECT * FROM TDF_COUREUR WHERE N_COUREUR = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosCoureur = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(empty($infosCoureur)){
	exit('Coureur non trouvé !');
}

if(P()){
	if(!P('nom')) error_add('Le champ "nom" est obligatoire !');
	if(!P('prenom')) error_add('Le champ "prénom" est obligatoire !');
	if(!P('pays')) error_add('Le champ "pays" est obligatoire !');
	
	verifCoureur();
	
	if(!error_exists()){

		// on modifie le coureur dans la bdd
		$stmt = $bdd->prepare('
			UPDATE TDF_COUREUR 
			SET NOM = :nom, PRENOM = :prenom, CODE_TDF = :pays, ANNEE_NAISSANCE = :naissance, ANNEE_TDF = :anneetdf
			WHERE N_COUREUR = :id');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->bindValue(':pays', P('pays'));
		$stmt->bindValue(':naissance', P('anneeNaissance'));
		$stmt->bindValue(':anneetdf', P('anneeParticipation'));
		$stmt->bindValue(':id', G('id'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le coureur '.P('prenom').' '.P('nom').' a bien été modifié !', 'coureur_liste.php', 1);
	}
}
?>

<h1>Modification du coureur: <?= $infosCoureur->PRENOM ?> <?= $infosCoureur->NOM ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>coureur_liste.php">Retour à la liste des coureurs</a></p>

<br />

<form class="form-horizontal" name="ajouterCoureur" method="post">
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" id="nom" name="nom" placeholder="FOUTE" value="<?= $infosCoureur->NOM ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" id="prenom" name="prenom" placeholder="Ranah" value="<?= $infosCoureur->PRENOM ?>">
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
					if($infosCoureur->ANNEE_NAISSANCE == $i){
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
					if($infosCoureur->CODE_TDF == $l->CODE_TDF){
						$add = 'selected=selected';
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
				for($i = (date('Y') + 1); $i >= 1903; $i--){
					$add = '';
					if($infosCoureur->ANNEE_TDF == $i){
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
include_once("footer.php");
?>