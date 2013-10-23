<?php

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

