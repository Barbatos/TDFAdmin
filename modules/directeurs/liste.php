<?php

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

$currentPage = 'Directeurs';

include_once(BASEPATH.'/modules/header.php');

$order = 'NOM';
$type = 'ASC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

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

