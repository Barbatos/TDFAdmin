<?php

$currentPage = 'Participations';

include_once(BASEPATH.'/modules/header.php');

?>

<form class="form-inline" name="annee" method="get" action="">
	Sélectionnez une année... 
	<select name="annee">
		<option value="">---</option>
		<?php 
		$stmt = $bdd->prepare('SELECT * FROM TDF_ANNEE ORDER BY ANNEE DESC');
		$stmt->execute();
		$listeAnnees = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		foreach($listeAnnees as $l){
			$add = "";
			if($l->ANNEE == G('annee')){
				$add = 'selected=selected';
			}
		?>
		<option value="<?= $l->ANNEE ?>" <?= $add ?>><?= $l->ANNEE ?></option>
		<?php 	
		}
		?>
	</select>
	<input class="btn" type="submit" name="go" value="Go" />
</form>

<?php 
if(G('annee')) {

	$stmt = $bdd->prepare('
		SELECT d.NOM AS NOMDIR1, d.PRENOM AS PRENOMDIR1, d2.NOM AS NOMDIR2, d2.PRENOM AS PRENOMDIR2, s.*, ea.* FROM TDF_EQUIPE_ANNEE ea 
		JOIN TDF_SPONSOR s ON s.N_SPONSOR = ea.N_SPONSOR AND s.N_EQUIPE = ea.N_EQUIPE
		JOIN TDF_DIRECTEUR d ON d.N_DIRECTEUR = ea.N_PRE_DIRECTEUR
		JOIN TDF_DIRECTEUR d2 ON d2.N_DIRECTEUR = ea.N_CO_DIRECTEUR
		WHERE ea.ANNEE = :annee
		ORDER BY s.NOM ASC
	');
	$stmt->bindValue(':annee', G('annee'));
	$stmt->execute();
	$listeEquipes = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();
?>


<h2>Liste des équipes participant au TDF <?= G('annee') ?></h2>

<br />

<p><a href="<?= $Site['base_address'] ?>participations/ajouter/">Ajouter une équipe participante</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th>ANNEE</th>
			<th><a href="?o=N_EQUIPE&t=<?= (((G('o') == 'N_EQUIPE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">N_EQUIPE</a></th>
			<th><a href="?o=NOM&t=<?= (((G('o') == 'NOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">SPONSOR</a></th>
			<th>Directeur</th>
			<th>Co Directeur</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($listeEquipes as $le){
		?>
		<tr>
			<td><?= $le->ANNEE ?></td>
			<td><?= $le->N_EQUIPE ?></td>
			<td><?= $le->NOM ?></td>
			<td><?= $le->PRENOMDIR1 . ' ' . $le->NOMDIR1 ?></td>
			<td><?= $le->PRENOMDIR2 . ' ' . $le->NOMDIR2 ?></td>
		</tr>
		<?php 	
		}
		?>
	</tbody>
</table>

<?php 
}
include_once(BASEPATH.'/modules/footer.php');
?>