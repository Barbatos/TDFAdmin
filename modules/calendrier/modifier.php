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

// Il faut renseigner l'année à modifier
if(!G('id')){
	exit('Arguments invalides!');
}

$currentPage = 'Calendrier';

include_once(BASEPATH.'/modules/header.php');

// On récupère les informations sur l'année demandée et on en 
// profite pour vérifier qu'elle existe dans la base
$stmt = $bdd->prepare('SELECT * FROM TDF_ANNEE WHERE ANNEE = :id');
$stmt->bindValue(':id', G('id'));
$stmt->execute();
$infosAnnee = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

// L'année n'existe pas dans la base
if(empty($infosAnnee)){
	exit('Année non trouvée !');
}

// Si on a envoyé le formulaire pour modifier l'année
if(P('repos') && P('annee')){

	$stmt = $bdd->prepare('
		UPDATE TDF_ANNEE 
		SET JOUR_REPOS = :repos WHERE ANNEE = :annee');
	$stmt->bindValue(':repos', P('repos'));
	$stmt->bindValue(':annee', P('annee'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('L\'année '.P('annee').' a bien été modifiée !', 'calendrier/liste/', 1);
}
?>

<h1>Modification de l'année: <?= $infosAnnee->ANNEE ?></h1>

<br />

<p><a href="<?= $Site['base_address'] ?>calendrier/liste/">Retour au calendrier</a></p>

<br />

<form class="form-horizontal" name="modifierAnnee" method="post">
	<input type="hidden" name="annee" value="<?= $infosAnnee->ANNEE ?>" />
	
	<div class="control-group">
		<label class="control-label" for="ann">Année</label>
		<div class="controls">
			<input type="text" id="ann" name="ann" value="<?= $infosAnnee->ANNEE ?>" disabled=disabled>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="repos">Jours de repos</label>
		<div class="controls">
			<select name="repos">
				<option value="">---</option>
				<?php 
				for($i = 1; $i <= 10; $i++){
					$add = '';
					if($infosAnnee->JOUR_REPOS == $i){
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
include_once(BASEPATH.'/modules/footer.php');
