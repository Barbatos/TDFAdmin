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

include_once(BASEPATH.'/modules/header.php');

$order = 'NOM';
$type = 'ASC';

// Permet de gérer les ordres d'affichage de toutes les données
if(G('o')) $order = G('o');
if( (G('t') == 'DESC') || (G('t') == 'ASC') ) $type = G('t');

// On récupère la liste de tous les directeurs
$stmt = $bdd->prepare('SELECT * FROM TDF_DIRECTEUR ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeDirecteurs = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

// Si on veut supprimer un directeur
if((G('action') == 'supprimer') && G('id')){

	$stmt = $bdd->prepare('DELETE FROM TDF_DIRECTEUR WHERE N_DIRECTEUR = :id');
	$stmt->bindValue(':id', G('id'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('Le directeur a bien été supprimé. :(', 'directeurs/liste/', 1);
}
?>

<h1>Directeurs</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>directeurs/ajouter/">Ajouter un directeur</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=N_DIRECTEUR&t=<?= (((G('o') == 'N_DIRECTEUR') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Numéro</a></th>
			<th><a href="?o=NOM&t=<?= (((G('o') == 'NOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Nom</a></th>
			<th><a href="?o=PRENOM&t=<?= (((G('o') == 'PRENOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Prénom</a></th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeDirecteurs as $l){
		?>	
		<tr>
			<td><?= $l->N_DIRECTEUR ?></td>
			<td><?= $l->NOM ?></td>
			<td><?= $l->PRENOM ?></td>
			<td>
				<a href="<?= $Site['base_address'] ?>directeurs/modifier/?id=<?= $l->N_DIRECTEUR ?>">modifier</a> 
				<a href="<?= $Site['base_address'] ?>directeurs/liste/?action=supprimer&id=<?= $l->N_DIRECTEUR ?>"> - supprimer</a>
			</td>
		</tr>
		<?php 
		}
		?>
	</tbody>
</table>

<?php 
include_once(BASEPATH.'/modules/footer.php');

