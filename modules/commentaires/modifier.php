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

$currentPage = 'Commentaires';

include_once(BASEPATH.'/modules/header.php');

$stmt = $bdd->prepare('SELECT * FROM TDF_COMMENTAIRE WHERE ANNEE = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosCommentaire = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

if(empty($infosCommentaire)){
	exit('Année non trouvée !');
}

if(P('commentaire') && P('annee')){

	$stmt = $bdd->prepare('
		UPDATE TDF_COMMENTAIRE 
		SET COMMENTAIRE = :com WHERE ANNEE = :annee');
	$stmt->bindValue(':com', P('commentaire'));
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('Le commentaire a bien été modifié !', 'commentaires/liste/', 1);
}
?>

<h1>Modification du commentaire pour l'année : <?= $infosCommentaire->ANNEE ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>commentaires/liste/">Retour à la liste des commentaires</a></p>

<br />

<form class="form-horizontal" name="modifierCommentaire" method="post">
	<input type="hidden" name="annee" value="<?= $infosCommentaire->ANNEE ?>" />
	
	<div class="control-group">
		<label class="control-label" for="annee">Année</label>
		<div class="controls">
			<input type="text" id="annee" name="annee" value="<?= $infosCommentaire->ANNEE ?>" disabled=disabled>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="commentaire">Commentaire</label>
		<div class="controls">
			<input type="text" name="commentaire" value="<?= $infosCommentaire->COMMENTAIRE ?>" />
		</div>
	</div>
	
	<div class="control-group">
		<button type="submit" class="btn btn-primary btn-info" name="envoyer">Modifier</button>
	</div>
</form>

<?php 
include_once(BASEPATH.'/modules/footer.php');
