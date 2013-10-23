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

include_once(BASEPATH.'/modules/header.php');

$order = 'CAT_CODE';
$type = 'ASC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

$stmt = $bdd->prepare('SELECT * FROM TDF_CATEGORIE_EPREUVE ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

// Si on veut supprimer une catégorie
if((G('action') == 'supprimer') && G('id')){

	$stmt = $bdd->prepare('DELETE FROM TDF_CATEGORIE_EPREUVE WHERE CAT_CODE = :id');
	$stmt->bindValue(':id', G('id'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('La catégorie d\'épreuve a bien été supprimée. :(', 'categories-epreuves/liste/', 1);
}
?>

<h1>Catégories d'épreuves</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>categories-epreuves/ajouter/">Ajouter une catégorie d'épreuve</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=CAT_CODE&t=<?= (((G('o') == 'CAT_CODE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">CAT_CODE</a></th>
			<th><a href="?o=TEP_CODE&t=<?= (((G('o') == 'TEP_CODE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">TEP_CODE</a></th>
			<th><a href="?o=LIBELLE&t=<?= (((G('o') == 'LIBELLE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">LIBELLE</a></th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeCategories as $l){
		?>	
		<tr>
			<td><?= $l->CAT_CODE ?></td>
			<td><?= $l->TEP_CODE ?></td>
			<td><?= $l->LIBELLE ?></td>
			<td>
				<a href="<?= $Site['base_address'] ?>categories-epreuves/liste/?action=supprimer&id=<?= $l->CAT_CODE ?>" onClick="javascript:return confirm('Etes-vous sûr ?');">supprimer</a>
			</td>
		</tr>
		<?php 
		}
		?>
	</tbody>
</table>

<?php 
include_once(BASEPATH.'/modules/footer.php');

