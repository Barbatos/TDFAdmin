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

*/

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

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

	<form method="get" action="" class="form-inline">
		Ajouter un sponsor à... <br />
		- Une équipe existante : 
			<select name="equipe">
				<option value="">---</option>
				<?php 
				$stmt = $bdd->prepare('
					SELECT * FROM TDF_EQUIPE e
					LEFT JOIN TDF_SPONSOR s ON s.N_EQUIPE = e.N_EQUIPE
					WHERE s.N_SPONSOR = 
						(
							SELECT MAX(N_SPONSOR) FROM TDF_SPONSOR WHERE N_EQUIPE = s.N_EQUIPE
						)
					AND e.ANNEE_DISPARITION IS NULL
					ORDER BY s.NOM ASC
				');
				$stmt->execute();
				$listeEquipes = $stmt->fetchAll(PDO::FETCH_OBJ);
				$stmt->closeCursor();
				foreach($listeEquipes as $le){
				?>
				<option value="<?= $le->N_EQUIPE ?>"><?= $le->NOM ?></option>
				<?php 	
				}
				?>
			</select>
			<input type="submit" name="new" value="Go" class="btn" />
		<br /><br />
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

		$nom = P('nom');
		$nom = strtoupper($nom);

		if(!checkAbregeSponsor(P('nomAbrege'))){
			error_add('Le nom abrégé est invalide !');
		}

		if(!error_exists()){

			// On vérifie qu'un sponsor ayant le même nom ne soit pas déjà dans la 
			// liste des sponsors actifs.
			// Le sponsor actif est le dernier sponsor d'une équipe qui n'a pas disparu.
			$stmt = $bdd->prepare('
				SELECT * FROM TDF_SPONSOR s 
				JOIN TDF_EQUIPE e ON e.N_EQUIPE = s.N_EQUIPE 
				WHERE s.NOM = :nom AND s.CODE_TDF = :pays AND e.ANNEE_DISPARITION IS NULL
			');
			$stmt->bindValue(':nom', $nom);
			$stmt->bindValue(':pays', P('pays'));
			$stmt->execute();
			$sponsorExiste = $stmt->fetchAll(PDO::FETCH_OBJ);
			$stmt->closeCursor();

			if($sponsorExiste){
				message_redirect('Il existe déjà un sponsor actif ayant ce nom et ce pays.', 'sponsors/ajouter/');
			}

			// On génère le numéro de la nouvelle équipe
			$stmt = $bdd->prepare('SELECT MAX(N_EQUIPE) AS MAX FROM TDF_EQUIPE');
			$stmt->execute();
			$maxNEquipe = $stmt->fetch(PDO::FETCH_OBJ);
			$stmt->closeCursor();

			$idEquipe = $maxNEquipe->MAX + 1;

			// On ajoute la nouvelle équipe
			$stmt = $bdd->prepare('
				INSERT INTO TDF_EQUIPE (N_EQUIPE, ANNEE_CREATION)
				VALUES 
				( :idEquipe, :annee)
			');
			$stmt->bindValue(':annee', P('anneeCreation'));
			$stmt->bindValue(':idEquipe', $idEquipe);
			$stmt->execute();
			$stmt->closeCursor();

			$stmt = $bdd->prepare('
				INSERT INTO TDF_SPONSOR (N_EQUIPE, N_SPONSOR, NOM, NA_SPONSOR, CODE_TDF, ANNEE_SPONSOR)
				VALUES 
				(:nEquipe, :nSponsor, :nom, :na, :pays, :annee)
			');
			$stmt->bindValue(':nEquipe', $idEquipe);
			$stmt->bindValue(':nSponsor', 1);
			$stmt->bindValue(':nom', $nom);
			$stmt->bindValue(':na', P('nomAbrege'));
			$stmt->bindValue(':pays', P('pays'));
			$stmt->bindValue(':annee', P('annee'));
			if($stmt->execute()){
				message_redirect('Le sponsor et l\'équipe ont bien été créés !', 'sponsors/liste/', 1);
			}
			else {
				message_redirect('Impossible de créer le sponsor.', 'sponsors/ajouter/');
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

// Ajouter un sponsor à une équipe existante
else {

	$stmt = $bdd->prepare('
		SELECT * FROM TDF_EQUIPE e 
		JOIN TDF_SPONSOR s ON s.N_EQUIPE = e.N_EQUIPE
		WHERE e.N_EQUIPE = :equipe 
		AND s.N_SPONSOR = (SELECT MAX(N_SPONSOR) FROM TDF_SPONSOR s2 WHERE s2.N_EQUIPE = :equipe)
	'); /* AND e.ANNEE_DISPARITION IS NOT NULL */
	$stmt->bindValue(':equipe', G('equipe'));
	$stmt->execute();
	$infosEquipe = $stmt->fetch(PDO::FETCH_OBJ);
	$stmt->closeCursor();

	if(P()){
		if(!P('nom')) error_add('Le champ Nom est obligatoire !');
		if(!P('pays')) error_add('Le champ Pays est obligatoire !');

		if(P('annee') && (P('annee') < $infosEquipe->ANNEE_CREATION)) {
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
				WHERE s.NOM = :nom AND s.CODE_TDF = :pays AND e.N_EQUIPE = :equipe
			');
			$stmt->bindValue(':nom', P('nom'));
			$stmt->bindValue(':pays', P('pays'));
			$stmt->bindValue(':equipe', $infosEquipe->N_EQUIPE);
			$stmt->execute();
			$sponsorExiste = $stmt->fetchAll(PDO::FETCH_OBJ);
			$stmt->closeCursor();

			if($sponsorExiste){
				message_redirect('Il existe déjà un sponsor actif ayant ce nom et ce pays.', 'sponsors/ajouter/');
			}

			$stmt = $bdd->prepare('
				INSERT INTO TDF_SPONSOR (N_EQUIPE, N_SPONSOR, NOM, NA_SPONSOR, CODE_TDF, ANNEE_SPONSOR)
				VALUES 
				(:nEquipe, (SELECT MAX(N_SPONSOR) FROM TDF_SPONSOR WHERE N_EQUIPE = :nEquipe) + 1, :nom, :na, :pays, :annee)
			');
			$stmt->bindValue(':nEquipe', $infosEquipe->N_EQUIPE);
			$stmt->bindValue(':nSponsor', 1);
			$stmt->bindValue(':nom', P('nom'));
			$stmt->bindValue(':na', P('nomAbrege'));
			$stmt->bindValue(':pays', P('pays'));
			$stmt->bindValue(':annee', P('annee'));
			if($stmt->execute()){
				message_redirect('Le sponsor a bien été créé !', 'sponsors/liste/', 1);
			}
			else {
				message_redirect('Impossible de créer le sponsor.', 'sponsors/ajouter/');
			}
			$stmt->closeCursor();
		}
	}
?>

<h2>Ajouter un sponsor à l'équipe: <?= $infosEquipe->NOM ?></h2>


	 <form class="form-horizontal" name="ajouterSponsor" method="post">
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
?>



<?php include_once(BASEPATH.'/modules/footer.php'); ?>
