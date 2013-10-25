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

// On vérifie que l'url contient bien un numéro d'épreuve et une année à modifier
if(!G('id') || !G('annee')){
	exit('Arguments invalides!');
}

$currentPage = 'Epreuves';

include_once(BASEPATH.'/modules/header.php');

// On récupère les informations de l'épreuve
$stmt = $bdd->prepare('SELECT * FROM TDF_EPREUVE WHERE N_EPREUVE = :id AND ANNEE = :annee');
$stmt->bindValue(':id', G('id'));
$stmt->bindValue(':annee', G('annee'));
$stmt->execute();
$infosEpreuve = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

// L'épreuve est introuvable
if(empty($infosEpreuve)){
	message_redirect('Epreuve non trouvée !', 'epreuves/liste/');
}

// Si on envoie le formulaire pour modifier une épreuve
if(P()){

	// On vérifie que tous les champs obligatoires sont bien entrés
	if(!P('villeD')) error_add('Le champ ville de départ est obligatoire !');
	if(!P('villeA')) error_add('Le champ ville d\'arrivée est obligatoire !');
	if(!P('distance')) error_add('Le champ distance est obligatoire !');
	if(!P('paysD')) error_add('Le champ pays de départ est obligatoire !');
	if(!P('paysA')) error_add('Le champ pays d\'arrivée est obligatoire !');
	if(!P('date')) error_add('Le champ date est obligatoire !');
	if(!P('cat_code')) error_add('Le champ catégorie est obligatoire !');

	// On vérifie les champs importants
	verifEpreuve();

	// Si pas d'erreurs, on peut continuer
	if(!error_exists()){
		
		// on modifie l'épreuve dans la base
		$query = '
			UPDATE TDF_EPREUVE SET VILLE_D = :villed, 
			VILLE_A = :villea, DISTANCE = :distance, MOYENNE = :moyenne, CODE_TDF_D = :codetdfd, 
			CODE_TDF_A = :codetdfa, JOUR = to_date(:jour, \'dd/mm/yy\'), CAT_CODE = :cat_code 
			WHERE
			N_EPREUVE = :n_epreuve AND ANNEE = :annee';
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
			message_redirect('L\'épreuve a bien été modifiée dans la base !', 'epreuves/liste/', 1);
		}
		$stmt->closeCursor();
	}
}

?>

<h1>Modification de l'épreuve: #<?= $infosEpreuve->N_EPREUVE ?> de l'année <?= $infosEpreuve->ANNEE ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>epreuves/liste/">Retour à la liste des épreuves</a></p>

<br />

<form class="form-horizontal" name="ajouterEpreuve" method="post">
	<input type="hidden" name="numEpreuve" value="<?= $infosEpreuve->N_EPREUVE ?>" />
	<input type="hidden" name="annee" value="<?= $infosEpreuve->ANNEE ?>" />
	
	<div class="control-group">
		<label class="control-label" for="villeD">Ville départ</label>
		<div class="controls">
			<input type="text" name="villeD" value="<?= (P('villeD')) ? P('villeD') : $infosEpreuve->VILLE_D ?>" /> (en majuscules sans accents)
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="villeA">Ville arrivée</label>
		<div class="controls">
			<input type="text" name="villeA" value="<?= (P('villeA')) ? P('villeA') : $infosEpreuve->VILLE_A ?>" /> (en majuscules sans accents)
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="distance">Distance</label>
		<div class="controls">
			<input type="text" name="distance" value="<?= (P('distance')) ? P('distance') : $infosEpreuve->DISTANCE ?>" /> Km
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="moyenne">Moyenne</label>
		<div class="controls">
			<input type="text" name="moyenne" value="<?= (P('moyenne')) ? P('moyenne') : $infosEpreuve->MOYENNE ?>" /> Km
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
					if($infosEpreuve->CODE_TDF_D == $l->CODE_TDF){
						$add = 'selected=selected';
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
					if($infosEpreuve->CODE_TDF_A == $l->CODE_TDF){
						$add = 'selected=selected';
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
			<input type="text" name="date" value="<?= (P('date')) ? P('date') : $infosEpreuve->JOUR ?>" /> (jj/mm/aa)
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
					if($infosEpreuve->CAT_CODE == $l->CAT_CODE){
						$add = 'selected=selected';
					}
					echo '<option value="'.$l->CAT_CODE.'" '.$add.'>'.$l->LIBELLE.'</option>';
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
