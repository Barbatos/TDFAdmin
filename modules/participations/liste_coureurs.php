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
@licence 	http://opensource.org/licenses/MIT
@link 		https://github.com/Barbatos/TDFAdmin

*/

// Impossible de visualiser la page si on n'est pas identifié
if(!$admin->isLogged()){
	message_redirect('Vous devez être identifié pour voir cette page !');
}

// On vérifie que le numéro de l'équipe et l'année sont bien 
// renseignées dans l'url
if(!G('equipe') || !G('annee')){
	message_redirect('Il manque des arguments dans la requête !', 'participations/liste/');
}

$currentPage = 'Participations';

include_once(BASEPATH.'/modules/header.php');

// On récupère les informations de l'équipe
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

// On récupère la liste des coureurs inscrits à cette équipe 
// pour l'année demandée
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

<?php if(sizeOf($listeCoureurs) < MAX_NB_COUREURS){ ?>
<p><a href="<?= $Site['base_address'] ?>participations/ajouter-coureur/?annee=<?= G('annee') ?>&equipe=<?= $infosEquipe->N_EQUIPE ?>">Ajouter un coureur participant dans cette équipe</a></p>
<?php } ?>

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
