<?php

define('MAX_NB_COUREURS', 9);

if(!G('equipe') || !G('annee')){
	message_redirect('Il manque des arguments dans la requête !', 'participations/liste/');
}

$currentPage = 'Participations';

include_once(BASEPATH.'/modules/header.php');


$stmt = $bdd->prepare('
	SELECT * FROM TDF_EQUIPE_ANNEE e
	JOIN TDF_SPONSOR s ON s.N_EQUIPE = e.N_EQUIPE AND s.N_SPONSOR = e.N_SPONSOR
	WHERE e.ANNEE = :annee AND e.N_EQUIPE = :equipe
');
$stmt->bindValue(':annee', G('annee'));
$stmt->bindValue(':equipe', G('equipe'));
$stmt->execute();
$infosEquipe = $stmt->fetch(PDO::FETCH_OBJ);
$stmt->closeCursor();

$stmt = $bdd->prepare('
	SELECT * FROM TDF_PARTICIPATION p 
	JOIN TDF_COUREUR c ON c.N_COUREUR = p.N_COUREUR
	WHERE p.N_EQUIPE = :equipe AND p.N_SPONSOR = :sponsor AND p.ANNEE = :annee 
');
$stmt->bindValue(':equipe', $infosEquipe->N_EQUIPE);
$stmt->bindValue(':sponsor', $infosEquipe->N_SPONSOR);
$stmt->bindValue(':annee', $infosEquipe->ANNEE);
$stmt->execute();
$listeCoureurs = $stmt->fetchAll(PDO::FETCH_OBJ);
$stmt->closeCursor();

?>

<h2>Liste des coureurs de l'équipe <?= $infosEquipe->NOM ?> pour l'année <?= G('annee') ?></h2>

<br />

<p><a href="<?= $Site['base_address'] ?>participations/liste/?annee=<?= G('annee') ?>">Retourner à la liste des équipes participantes</a></p>

<p><a href="<?= $Site['base_address'] ?>participations/ajouter-coureur/">Ajouter un coureur participant dans cette équipe</a></p>

<p>Nombre de coureurs: <strong><?= sizeof($listeCoureurs) ?> / <?= MAX_NB_COUREURS ?></strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<th>ANNEE</th>
			<th><a href="?o=N_EQUIPE&t=<?= (((G('o') == 'N_EQUIPE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">N_EQUIPE</a></th>
			<th><a href="?o=NOM&t=<?= (((G('o') == 'NOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">SPONSOR</a></th>
			<th>Coureur</th>
			<th>Num Dossard</th>
			<th>Jeune</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeCoureurs as $l){
		?>
		<tr>
			<td><?= $infosEquipe->ANNEE ?></td>
			<td><?= $infosEquipe->N_EQUIPE ?></td>
			<td><?= $infosEquipe->NOM ?></td>
			<td><?= $l->PRENOM . ' ' . $l->NOM ?></td>
			<td><?= $l->N_DOSSARD ?></td>
			<td><?= $l->JEUNE ?></td>
		</tr>
		<?php 	
		}
		?>
	</tbody>
</table>


<?php 

include_once(BASEPATH.'/modules/footer.php');

