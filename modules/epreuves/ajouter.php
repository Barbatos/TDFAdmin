<?php
$currentPage = 'Epreuve';

if(P()){
	
	if(!P('annee')) error_add('Le champ année est obligatoire !');
	if(!P('numEpreuve')) error_add('Le champ numéro d\'épreuve est obligatoire !');
	if(!P('villeD')) error_add('Le champ ville de départ est obligatoire !');
	if(!P('villeA')) error_add('Le champ ville d\'arrivée est obligatoire !');
	if(!P('distance')) error_add('Le champ distance est obligatoire !');
	if(!P('paysD')) error_add('Le champ pays de départ est obligatoire !');
	if(!P('paysA')) error_add('Le champ pays d\'arrivée est obligatoire !');
	if(!P('date')) error_add('Le champ date est obligatoire !');
	if(!P('cat_code')) error_add('Le champ catégorie est obligatoire !');

	// on vérifie que l'on a pas déjà une épreuve avec le même numéro
	// et la même année dans la base
	$stmt = $bdd->prepare('SELECT * FROM TDF_EPREUVE WHERE ANNEE = :annee AND N_EPREUVE = :epreuve');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->bindValue(':epreuve', P('numEpreuve'));
	$stmt->execute();
	$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();

	if($correspondance){
		message_redirect('Il y a déjà une épreuve ayant cette année et ce numéro !', 'epreuves/ajouter/');
	}

	verifEpreuve();

	if(!error_exists()){
		// on ajoute l'épreuve dans la base
		$query = '
			INSERT INTO TDF_EPREUVE (ANNEE, N_EPREUVE, VILLE_D, VILLE_A, DISTANCE, MOYENNE, CODE_TDF_D, CODE_TDF_A, JOUR, CAT_CODE) 
			VALUES 
			(:annee, :n_epreuve, :villed, :villea, :distance, :moyenne, :codetdfd, :codetdfa, :jour, :cat_code)';
		echo $query;
		$stmt = $bdd->prepare($query);
		$stmt->bindValue(':annee', P('annee'));
		$stmt->bindValue(':n_epreuve', P('numEpreuve'));
		$stmt->bindValue(':villed', P('villeD'));
		$stmt->bindValue(':villea', P('villeA'));
		$stmt->bindValue(':distance', P('distance'));
		$stmt->bindValue(':moyenne', P('moyenne'));
		$stmt->bindValue(':codetdfd', P('paysD'));
		$stmt->bindValue(':codetdfa', P('paysA'));
		$stmt->bindValue(':jour', P('date'));
		$stmt->bindValue(':cat_code', P('cat_code'));
		if($stmt->execute()){
			message_redirect('L\'épreuve a bien été ajoutée à la base !', 'epreuves/liste/', 1);
		}
		else echo 'derp';
		$stmt->closeCursor();
	}
}


include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter une épreuve</h1>
<br />

<form class="form-horizontal" name="ajouterEpreuve" method="post">
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
		<label class="control-label" for="numEpreuve">Num. épreuve</label>
		<div class="controls">
			<select name="numEpreuve">
				<option value="">---</option>
				<?php 
				for($i = 0; $i <= 25; $i++){
					$add = '';
					if(P('numEpreuve') == $i){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="villeD">Ville départ</label>
		<div class="controls">
			<input type="text" name="villeD" value="<?= P('villeD') ?>" /> (en majuscules sans accents)
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="villeA">Ville arrivée</label>
		<div class="controls">
			<input type="text" name="villeA" value="<?= P('villeA') ?>" /> (en majuscules sans accents)
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="distance">Distance</label>
		<div class="controls">
			<input type="text" name="distance" value="<?= P('distance') ?>" /> Km
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="moyenne">Moyenne</label>
		<div class="controls">
			<input type="text" name="moyenne" value="<?= P('moyenne') ?>" /> Km
		</div>
	</div>

	<?php 
	$stmt = $bdd->prepare('SELECT * FROM TDF_PAYS ORDER BY NOM ASC');
	$stmt->execute();
	$listePays = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();
	?>

	<div class="control-group">
		<label class="control-label" for="paysD">Pays départ</label>
		<div class="controls">
			<select name="paysD">
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
				}
				?>	
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="paysA">Pays arrivée</label>
		<div class="controls">
			<select name="paysA">
				<option value="">---</option>
				<?php 
				foreach($listePays as $l){
					$add = '';
					if(P('paysA') == $l->CODE_TDF){
						$add = 'selected=selected';
					}
					if(!P('paysA') && $l->CODE_TDF == 'FRA'){
						$add=  'selected=selected';
					}
					echo '<option value="'.$l->CODE_TDF.'" '.$add.'>'.$l->NOM.'</option>';
				}
				?>	
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="date">Date</label>
		<div class="controls">
			<input type="text" name="date" value="<?= P('date') ?>" /> (jj/mm/aa)
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="cat_code">Catégorie</label>
		<div class="controls">
			<select name="cat_code">
				<option value="">---</option>
				<?php 
				foreach($listeCatCodes as $l){
					$add = '';
					if(P('cat_code') == $l){
						$add = 'selected=selected';
					}
					echo '<option value="'.$l.'" '.$add.'>'.$l.'</option>';
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
