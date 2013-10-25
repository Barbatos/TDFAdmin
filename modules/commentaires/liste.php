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

$currentPage = 'Commentaires';

include_once(BASEPATH.'/modules/header.php');

// Permet de gérer les ordres d'affichage de toutes les données
$order = 'ANNEE';
$type = 'DESC';

if(G('o')) $order = G('o');
if( (G('t') == 'DESC') || (G('t') == 'ASC') ) $type = G('t');


// On récupère la liste de tous les commentaires
$stmt = $bdd->prepare('SELECT * FROM TDF_COMMENTAIRE ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeCommentaires = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

// Si on veut supprimer un commentaire
if((G('action') == 'supprimer') && G('id')){

	$stmt = $bdd->prepare('DELETE FROM TDF_COMMENTAIRE WHERE ANNEE = :id');
	$stmt->bindValue(':id', G('id'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('Le commentaire a bien été supprimé. :(', 'commentaires/liste/', 1);
}
?>

<h1>Commentaires</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>commentaires/ajouter/">Ajouter un commentaire</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=ANNEE&t=<?= (((G('o') == 'ANNEE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Année</a></th>
			<th><a href="?o=COMMENTAIRE&t=<?= (((G('o') == 'COMMENTAIRE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Commentaire</a></th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeCommentaires as $l){
		?>	
		<tr>
			<td><?= $l->ANNEE ?></td>
			<td><?= $l->COMMENTAIRE ?></td>
			<td>
				<a href="<?= $Site['base_address'] ?>commentaires/modifier/?id=<?= $l->ANNEE ?>">modifier</a> 
				<a href="<?= $Site['base_address'] ?>commentaires/liste/?action=supprimer&id=<?= $l->ANNEE ?>"> - supprimer</a>
			</td>
		</tr>
		<?php 
		}
		?>
	</tbody>
</table>

<?php 
include_once(BASEPATH.'/modules/footer.php');
