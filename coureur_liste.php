<?php

$currentPage = 'Coureur';

include_once("includes/init.php");
include_once("header.php");

$order = 'NOM';
$type = 'ASC';

if(G('o')){
	$order = G('o');
}

if( (G('t') == 'DESC') || (G('t') == 'ASC') ) {
	$type = G('t');
}

$sql = 'SELECT * FROM TDF_COUREUR ORDER BY '.$order.' '.$type;
$stmt = $bdd->prepare($sql);
$stmt->execute();
$listeCoureurs = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

?>

<h1>Liste des coureurs</h1>

<br />

<p><a href="<?= $Site['base_address'] ?>coureur_ajouter.php">Ajouter un coureur</a></p>

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
				<a href="<?= $Site['base_address'] ?>coureur_modifier.php?id=<?= $l->N_COUREUR ?>">modifier</a> 
				<?php if(empty($l->ANNEE_TDF)){ ?>
				<a href="<?= $Site['base_address'] ?>coureur_liste.php?act=supprimer&id=<?= $l->N_COUREUR ?>"> - supprimer</a>
				<?php } ?>
			</td>
		</tr>
		<?php 
		}
		?>
	</tbody>
</table>
<?php 
include_once("footer.php");
?>