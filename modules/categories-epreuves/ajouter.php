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

$currentPage = 'Categories';

if(P()){
	
	if(!P('cat')) error_add('Le cat code est obligatoire !');
	if(!P('tep')) error_add('Le tep code est obligatoire !');
	if(!P('libelle')) error_add('Le libellé est obligatoire !');

	if(!checkCatCode(P('cat'))){
		error_add('Le cat code n\'est pas bon !');
	}

	if(!checkTepCode(P('tep'))){
		error_add('Le tep code n\'est pas bon !');
	}

	if(!error_exists()){
		// on vérifie que la catégorie n'existe pas déjà dans la base
		$stmt = $bdd->prepare('SELECT * FROM TDF_CATEGORIE_EPREUVE WHERE CAT_CODE = :id');
		$stmt->bindValue(':id', P('id'));
		$stmt->execute();
		$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		if($correspondance){
			message_redirect('Une catégorie d\'épreuve existe déjà !', 'categories-epreuves/ajouter/');
		}

		// on ajoute la catégorie
		$stmt = $bdd->prepare('
			INSERT INTO TDF_CATEGORIE_EPREUVE (CAT_CODE, TEP_CODE, LIBELLE) VALUES (:cat, :tep, :libelle)');
		$stmt->bindValue(':cat', P('cat'));
		$stmt->bindValue(':tep', P('tep'));
		$stmt->bindValue(':libelle', P('libelle'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('La catégorie d\'épreuve a bien été ajoutée à la base !', 'categories-epreuves/liste/', 1);
	}
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter une catégorie d'épreuve</h1>
<br />

<form class="form-horizontal" name="ajouterCategorieEpreuve" method="post">
	<div class="control-group">
		<label class="control-label" for="cat">Cat code</label>
		<div class="controls">
			<input type="text" name="cat" maxlength="3" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tep">Tep code</label>
		<div class="controls">
			<input type="text" name="tep" maxlength="2" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="libelle">Libellé</label>
		<div class="controls">
			<input type="text" name="libelle" maxlength="40" />
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
