<?php

$currentPage = 'Coureurs';

include_once(BASEPATH.'/modules/header.php');

$order = 'NOM';
$type = 'ASC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

$stmt = $bdd->prepare('SELECT * FROM TDF_COUREUR ORDER BY '.$order.' '.$type);
$stmt->execute();
$listeCoureurs = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

// Si on veut supprimer un coureur
if((G('act') == 'supprimer') && G('id')){

	// On ne peut pas supprimer un coureur ayant au moins une participation au tour de France,
	// donc on ne supprime pas si ANNEE_TDF est rempli.
	// ANNEE_TDF correspond à l'année du premier TDF effectué par un coureur.
	$stmt = $bdd->prepare('SELECT ANNEE_TDF FROM TDF_COUREUR WHERE N_COUREUR = :id');
	$stmt->bindValue(':id', G('id'));
	$stmt->execute();
	$coureur = $stmt->fetch(PDO::FETCH_OBJ);
	$stmt->closeCursor();

	// On peut supprimer le coureur, il n'a jamais participé à un TDF.
	if(!$coureur->ANNEE_TDF){

		// Suppression du coureur.
		$stmt = $bdd->prepare('DELETE FROM TDF_COUREUR WHERE N_COUREUR = :id');
		$stmt->bindValue(':id', G('id'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le coureur a bien été supprimé de la base. :(', 'coureurs/liste/', 1);
	}
	else {
		message_redirect('Ce coureur ne peut être supprimé ! Il a déjà participé à un ou plusieurs TDF.', 'coureurs/liste/');
	}
}
?>

<h1>Liste des coureurs</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>coureurs/ajouter/">Ajouter un coureur</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="?o=N_COUREUR&t=<?= (((G('o') == 'N_COUREUR') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">N_COUREUR</a></th>
			<th><a href="?o=NOM&t=<?= (((G('o') == 'NOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Nom</a></th>
			<th><a href="?o=PRENOM&t=<?= (((G('o') == 'PRENOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Prénom</a></th>
			<th><a href="?o=ANNEE_NAISSANCE&t=<?= (((G('o') == 'ANNEE_NAISSANCE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Année naiss.</a></th>
			<th><a href="?o=CODE_TDF&t=<?= (((G('o') == 'CODE_TDF') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Pays</a></th>
			<th><a href="?o=ANNEE_TDF&t=<?= (((G('o') == 'ANNEE_TDF') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">Année participation</a></th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeCoureurs as $l){
		?>	
		<tr <?php if(empty($l->ANNEE_TDF)) echo 'class="error"' ?>>
			<td><?= $l->N_COUREUR ?></td>
			<td><?= $l->NOM ?></td>
			<td><?= $l->PRENOM ?></td>
			<td><?= $l->ANNEE_NAISSANCE ?></td>
			<td><?= $l->CODE_TDF ?></td>
			<td><?= $l->ANNEE_TDF ?></td>
			<td>
				<a href="<?= $Site['base_address'] ?>coureurs/modifier/?id=<?= $l->N_COUREUR ?>">modifier</a> 
				<?php if(empty($l->ANNEE_TDF)){ ?>
				<a href="<?= $Site['base_address'] ?>coureurs/liste/?act=supprimer&id=<?= $l->N_COUREUR ?>"> - supprimer</a>
				<?php } ?>
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