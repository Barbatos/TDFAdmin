<?php

$currentPage = 'Epreuves';

include_once(BASEPATH.'/modules/header.php');

$order = 'ANNEE DESC, N_EPREUVE';
$type = 'DESC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

$stmt = $bdd->prepare('SELECT * FROM TDF_EPREUVE ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeEpreuves = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

// Si on veut supprimer une épreuve
if((G('act') == 'supprimer') && G('id') && G('annee')){

	$stmt = $bdd->prepare('DELETE FROM TDF_EPREUVE WHERE N_EPREUVE = :id AND ANNEE = :annee');
	$stmt->bindValue(':id', G('id'));
	$stmt->bindValue(':annee', G('annee'));
	$stmt->execute();
	$stmt->closeCursor();

	message_redirect('L\'épreuve a bien été supprimée de la base. :(', 'epreuves/liste/', 1);
}
?>

<h1>Liste des épreuves</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>epreuves/ajouter/">Ajouter une épreuve</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=ANNEE&t=<?= (((G('o') == 'ANNEE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">ANNEE</a></th>
			<th><a href="?o=N_EPREUVE&t=<?= (((G('o') == 'N_EPREUVE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Num. épreuve</a></th>
			<th><a href="?o=VILLE_D&t=<?= (((G('o') == 'VILLE_D') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Ville départ</a></th>
			<th><a href="?o=VILLE_A&t=<?= (((G('o') == 'VILLE_A') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Ville arrivée</a></th>
			<th><a href="?o=DISTANCE&t=<?= (((G('o') == 'DISTANCE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Distance</a></th>
			<th><a href="?o=MOYENNE&t=<?= (((G('o') == 'MOYENNE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Moyenne</a></th>
			<th><a href="?o=CODE_TDF_D&t=<?= (((G('o') == 'CODE_TDF_D') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Pays départ</a></th>
			<th><a href="?o=CODE_TDF_A&t=<?= (((G('o') == 'CODE_TDF_A') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Pays arrivée</a></th>
			<th><a href="?o=JOUR&t=<?= (((G('o') == 'JOUR') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Jour</a></th>
			<th><a href="?o=CAT_CODE&t=<?= (((G('o') == 'CAT_CODE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Cat.</a></th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeEpreuves as $l){
		?>	
		<tr>
			<td><?= $l->ANNEE ?></td>
			<td><?= $l->N_EPREUVE ?></td>
			<td><?= $l->VILLE_D ?></td>
			<td><?= $l->VILLE_A ?></td>
			<td><?= $l->DISTANCE ?> Km</td>
			<td><?= $l->MOYENNE ?> Km</td>
			<td><?= $l->CODE_TDF_D ?></td>
			<td><?= $l->CODE_TDF_A ?></td>
			<td><?= $l->JOUR ?></td>
			<td><?= $l->CAT_CODE ?></td>
			<td>
				<a href="<?= $Site['base_address'] ?>epreuves/modifier/?id=<?= $l->N_EPREUVE ?>&annee=<?= $l->ANNEE ?>">modifier</a> 
				<a href="<?= $Site['base_address'] ?>epreuves/liste/?act=supprimer&id=<?= $l->N_EPREUVE ?>&annee=<?= $l->ANNEE ?>"> - supprimer</a>
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