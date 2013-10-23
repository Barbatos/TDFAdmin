<?php
/*

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

@package 	TDFAdmin
@authors 	Charles 'Barbatos' Duprey <cduprey@f1m.fr> && Adrien 'soullessoni' Demoget
@created 	20/09/2013
@copyright 	(c) 2013 TDFAdmin

*/

if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

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
		SELECT 
			d.NOM AS NOMDIR1, 
			d.PRENOM AS PRENOMDIR1, 
			d2.NOM AS NOMDIR2, 
			d2.PRENOM AS PRENOMDIR2, 
			s.*, ea.*,
			(SELECT COUNT(*) AS NB FROM TDF_PARTICIPATION WHERE N_EQUIPE = ea.N_EQUIPE AND ANNEE = :annee) AS NB_COUREURS 
		FROM TDF_EQUIPE_ANNEE ea 
		JOIN TDF_SPONSOR s ON s.N_SPONSOR = ea.N_SPONSOR AND s.N_EQUIPE = ea.N_EQUIPE
		JOIN TDF_DIRECTEUR d ON d.N_DIRECTEUR = ea.N_PRE_DIRECTEUR
		JOIN TDF_DIRECTEUR d2 ON d2.N_DIRECTEUR = ea.N_CO_DIRECTEUR
		WHERE ea.ANNEE = :annee
		ORDER BY s.NOM ASC
	');
	$stmt->bindValue(':annee', G('annee'));
	$stmt->execute();
	$adrien = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt->closeCursor();
?>


<h2>Liste des équipes participant au TDF <?= G('annee') ?></h2>

<br />

<p><a href="<?= $Site['base_address'] ?>participations/ajouter-equipe/?annee=<?= G('annee') ?>">Ajouter une équipe participante</a></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th>ANNEE</th>
			<th><a href="?o=N_EQUIPE&t=<?= (((G('o') == 'N_EQUIPE') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">N_EQUIPE</a></th>
			<th><a href="?o=NOM&t=<?= (((G('o') == 'NOM') && (G('t') == 'DESC'))) ? 'ASC' : 'DESC' ?>">SPONSOR</a></th>
			<th>Directeur</th>
			<th>Co Directeur</th>
			<th>Nb coureurs</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		foreach($adrien as $il => $iz){ // LOLOLOLOL Aziliz
		?>
		<tr>
			<td><?= $iz->ANNEE ?></td>
			<td><?= $iz->N_EQUIPE ?></td>
			<td><?= $iz->NOM ?></td>
			<td><?= $iz->PRENOMDIR1 . ' ' . $iz->NOMDIR1 ?></td>
			<td><?= $iz->PRENOMDIR2 . ' ' . $iz->NOMDIR2 ?></td>
			<td><?= $iz->NB_COUREURS ?></td>
			<td><a href="<?= $Site['base_address'] ?>participations/liste-coureurs/?equipe=<?= $iz->N_EQUIPE ?>&annee=<?= $iz->ANNEE ?>">Voir la liste des coureurs</a></td>
		</tr>
		<?php 	
		}
		?>
	</tbody>
</table>


<?php 
}
include_once(BASEPATH.'/modules/footer.php');

