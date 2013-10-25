<?php
/*

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

@package 	TDFAdmin
@authors 	Charles 'Barbatos' Duprey <cduprey@f1m.fr> && Adrien 'soullessoni' Demoget
@created 	20/09/2013
@copyright 	(c) 2013 TDFAdmin
@licence 	http://opensource.org/licenses/MIT
@link 		https://github.com/Barbatos/TDFAdmin

*/

// Impossible de visualiser la page si on n'est pas identifié
if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Epreuves';

// Si on envoie le formulaire pour ajouter une épreuve
if(P()){
	
	// On vérifie que les champs sont bien entrés
	if(!P('annee')) error_add('Le champ année est obligatoire !');
	if(!P('villeD')) error_add('Le champ ville de départ est obligatoire !');
	if(!P('villeA')) error_add('Le champ ville d\'arrivée est obligatoire !');
	if(!P('distance')) error_add('Le champ distance est obligatoire !');
	if(!P('paysD')) error_add('Le champ pays de départ est obligatoire !');
	if(!P('paysA')) error_add('Le champ pays d\'arrivée est obligatoire !');
	if(!P('date')) error_add('Le champ date est obligatoire !');
	if(!P('cat_code')) error_add('Le champ catégorie est obligatoire !');

	// Vérification des champs importants
	verifEpreuve();

	// Si pas d'erreurs, on peut continuer
	if(!error_exists()){

		// on ajoute l'épreuve dans la base
		$query = '
			INSERT INTO TDF_EPREUVE (ANNEE, N_EPREUVE, VILLE_D, VILLE_A, DISTANCE, MOYENNE, CODE_TDF_D, CODE_TDF_A, JOUR, CAT_CODE) 
			VALUES 
			(
				:annee, (SELECT MAX(N_EPREUVE)+1 FROM TDF_EPREUVE WHERE ANNEE = :annee), :villed, :villea, 
				:distance, :moyenne, :codetdfd, :codetdfa, to_date(:jour, \'dd/mm/yy\'), :cat_code
			)';
		$stmt = $bdd->prepare($query);
		$stmt->bindValue(':annee', P('annee'));
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
				$stmt = $bdd->prepare('SELECT * FROM TDF_CATEGORIE_EPREUVE ORDER BY CAT_CODE ASC');
				$stmt->execute();
				$listeCatCodes = $stmt->fetchAll(PDO::FETCH_OBJ);
				$stmt->closeCursor();

				foreach($listeCatCodes as $l){
					$add = '';
					if(P('cat_code') == $l->CAT_CODE){
						$add = 'selected=selected';
					}
					echo '<option value="'.$l->CAT_CODE.'" '.$add.'>'.$l->LIBELLE.'</option>';
				}
				?>	
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); 
