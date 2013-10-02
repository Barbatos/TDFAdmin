<?php

$currentPage = 'Calendrier';

include_once(BASEPATH.'/modules/header.php');

$order = 'ANNEE';
$type = 'DESC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

$stmt = $bdd->prepare('SELECT * FROM TDF_ANNEE ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeAnnees = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

// Si on veut supprimer une année
if((G('act') == 'supprimer') && G('id')){

	$stmt = $bdd->prepare('DELETE FROM TDF_COUREUR WHERE N_COUREUR = :id');
	$stmt->bindValue(':id', G('id'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('L\'année a bien été supprimée de la base. :(', 'calendrier/liste/', 1);
}
?>

<h1>Calendrier</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>calendrier/ajouter/">Ajouter une année</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=ANNEE&t=<?= (((G('o') == 'ANNEE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">ANNEE</a></th>
			<th><a href="?o=JOUR_REPOS&t=<?= (((G('o') == 'JOUR_REPOS') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Jours de repos</a></th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeAnnees as $l){
		?>	
		<tr>
			<td><?= $l->ANNEE ?></td>
			<td><?= $l->JOUR_REPOS ?></td>
			<td>
				<a href="<?= $Site['base_address'] ?>calendrier/modifier/?id=<?= $l->ANNEE ?>">modifier</a> 
				<a href="<?= $Site['base_address'] ?>calendrier/liste/?act=supprimer&id=<?= $l->ANNEE ?>"> - supprimer</a>
			</td>
		</tr>
		<?php 
		}
		?>
	</tbody>
</table>

<?php 
include_once(BASEPATH.'/modules/footer.php');
?>