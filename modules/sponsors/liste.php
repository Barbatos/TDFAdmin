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