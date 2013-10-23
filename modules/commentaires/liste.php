<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Commentaires';

include_once(BASEPATH.'/modules/header.php');

$order = 'ANNEE';
$type = 'DESC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

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

