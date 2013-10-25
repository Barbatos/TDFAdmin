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

$currentPage = 'Directeurs';

// Si le formulaire pour ajouter un directeur a été envoyé
if(P()){
	
	// On vérifie que les informations obligatoires ont bien été entrées
	if(!P('nom')) error_add('Il faut renseigner un nom !');
	if(!P('prenom')) error_add('Il faut renseigner un prénom !');

	// fonctionne aussi pour directeur :-)
	if(!checkNomCoureur(P('nom'))){
		error_add('Le champ nom est invalide !');
	}

	// On vérifie que le prénom a été entré correctement
	if(!checkPrenomCoureur(P('prenom'))){
		error_add('le champ prénom est invalide !');
	}

	// Si pas d'erreurs on peut ajouter le directeur
	if(!error_exists()){

		// On vérifie que le directeur n'existe pas déjà dans la base
		$stmt = $bdd->prepare('SELECT * FROM TDF_DIRECTEUR WHERE NOM = :nom AND PRENOM = :prenom');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->execute();
		$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		// Le directeur est déjà présent dans la base, derp derp
		if($correspondance){
			message_redirect('Un directeur avec ce nom et ce prénom existe déjà !', 'directeurs/ajouter/');
		}

		// On ajoute le directeur
		$stmt = $bdd->prepare('
			INSERT INTO TDF_DIRECTEUR (N_DIRECTEUR, NOM, PRENOM) VALUES ( (SELECT MAX(N_DIRECTEUR) FROM TDF_DIRECTEUR) + 1, :nom, :prenom)');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le directeur a bien été ajouté à la base !', 'directeurs/liste/', 1);
	}
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter un directeur</h1>
<br />

<form class="form-horizontal" name="ajouterDirecteur" method="post">
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" name="nom" placeholder="Nom" value="<?= (P('nom')) ? P('nom') : '' ?>" maxlength="40" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" name="prenom" placeholder="Prénom" value="<?= (P('prenom')) ? P('prenom') : '' ?>" maxlength="40" />
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); 
