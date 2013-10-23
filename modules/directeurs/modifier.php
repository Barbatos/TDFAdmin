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

if(!G('id')){
	exit('Arguments invalides!');
}

$currentPage = 'Directeurs';

include_once(BASEPATH.'/modules/header.php');

$stmt = $bdd->prepare('SELECT * FROM TDF_DIRECTEUR WHERE N_DIRECTEUR = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosDirecteur = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(empty($infosDirecteur)){
	exit('Directeur non trouvé !');
}

if(P('nom') && P('prenom')){

	if(!checkNomCoureur(P('nom'))){
		error_add('Le nom est invalide !');
	}

	if(!checkPrenomCoureur(P('prenom'))){
		error_add('Le prénom est invalide !');
	}

	if(!error_exists()){
		$stmt = $bdd->prepare('
			UPDATE TDF_DIRECTEUR 
			SET NOM = :nom, PRENOM = :prenom 
			WHERE N_DIRECTEUR = :id
		');
		$stmt->bindValue(':id', G('id'));
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le directeur a bien été modifié !', 'directeurs/liste/', 1);
	}
}
?>

<h1>Modification du directeur: <?= $infosDirecteur->NOM . ' ' . $infosDirecteur->PRENOM ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>directeurs/liste/">Retour à la liste des directeurs</a></p>

<br />

<form class="form-horizontal" name="modifierDirecteur" method="post">
	
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" id="nom" name="nom" value="<?= $infosDirecteur->NOM ?>" >
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" id="prenom" name="prenom" value="<?= $infosDirecteur->PRENOM ?>" >
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Modifier</button>
		</div>
	</div>
</form>

<?php 
include_once(BASEPATH.'/modules/footer.php');
