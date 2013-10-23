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

$currentPage = 'Commentaires';

if(P()){
	
	// on vérifie que l'année n'existe pas déjà dans la base
	$stmt = $bdd->prepare('SELECT * FROM TDF_COMMENTAIRE WHERE ANNEE = :annee');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();

	if($correspondance){
		message_redirect('Un commentaire pour cette année existe déjà !', 'commentaires/ajouter/');
	}

	// on ajoute le commentaire
	$stmt = $bdd->prepare('
		INSERT INTO TDF_COMMENTAIRE (ANNEE, COMMENTAIRE) VALUES (:annee, :com)');
	$stmt->bindValue(':annee', P('annee'));
	$stmt->bindValue(':com', P('commentaire'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('Le commentaire a bien été ajoutée à la base !', 'commentaires/liste/', 1);
}

include_once(BASEPATH.'/modules/header.php');
?>

<h1>Ajouter un commentaire</h1>
<br />

<form class="form-horizontal" name="ajouterCommentaire" method="post">
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
		<label class="control-label" for="repos">Commentaire</label>
		<div class="controls">
			<input type="text" name="commentaire" placeholder="Mon commentaire" />
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
		</div>
	</div>
</form>


<?php include_once(BASEPATH.'/modules/footer.php'); ?>
