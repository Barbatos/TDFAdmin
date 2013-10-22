<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Sponsors';

include_once(BASEPATH.'/modules/header.php');

$order = 'ANNEE_SPONSOR DESC, N_SPONSOR';
$type = 'DESC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

$stmt = $bdd->prepare('SELECT * FROM TDF_SPONSOR ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeSponsors = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

?>

<h1>Liste des sponsors</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>sponsors/ajouter/">Ajouter un sponsor</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=ANNEE_SPONSOR&t=<?= (((G('o') == 'ANNEE_SPONSOR') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">ANNEE_SPONSOR</a></th>
			<th><a href="?o=N_SPONSOR&t=<?= (((G('o') == 'N_SPONSOR') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">N_SPONSOR</a></th>
			<th><a href="?o=N_EQUIPE&t=<?= (((G('o') == 'N_EQUIPE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">N_EQUIPE</a></th>
			<th><a href="?o=NOM&t=<?= (((G('o') == 'NOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">NOM</a></th>
			<th><a href="?o=NA_SPONSOR&t=<?= (((G('o') == 'NA_SPONSOR') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">NA_SPONSOR</a></th>
			<th><a href="?o=CODE_TDF&t=<?= (((G('o') == 'CODE_TDF') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">CODE_TDF</a></th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeSponsors as $l){
		?>	
		<tr>
			<td><?= $l->ANNEE_SPONSOR ?></td>
			<td><?= $l->N_SPONSOR ?></td>
			<td><?= $l->N_EQUIPE ?></td>
			<td><?= $l->NOM ?></td>
			<td><?= $l->NA_SPONSOR ?></td>
			<td><?= $l->CODE_TDF ?></td>
		</tr>
		<?php 
		}
		?>
	</tbody>
</table>

<?php 
include_once(BASEPATH.'/modules/footer.php');
?>