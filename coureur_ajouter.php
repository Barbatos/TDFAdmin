<?php
/*
^        Start of line
$        End of line
n?        Zero or only one single occurrence of character 'n'
n*        Zero or more occurrences of character 'n'
n+        At least one or more occurrences of character 'n'
n{2}        Exactly two occurrences of 'n'
n{2,}        At least 2 or more occurrences of 'n'
n{2,4}        From 2 to 4 occurrences of 'n'
.        Any single character
()        Parenthesis to group expressions
(.*)        Zero or more occurrences of any single character, ie, anything!
(n|a)        Either 'n' or 'a'
[1-6]        Any single digit in the range between 1 and 6
[c-h]        Any single lower case letter in the range between c and h
[D-M]        Any single upper case letter in the range between D and M
[^a-z]        Any single character EXCEPT any lower case letter between a and z.

        Pitfall: the ^ symbol only acts as an EXCEPT rule if it is the 
        very first character inside a range, and it denies the 
        entire range including the ^ symbol itself if it appears again 
        later in the range. Also remember that if it is the first 
        character in the entire expression, it means "start of line". 
        In any other place, it is always treated as a regular ^ symbol.
        In other words, you cannot deny a word with ^undesired_word 
        or a group with ^(undesired_phrase).
        Read more detailed regex documentation to find out what is 
        necessary to achieve this.

[_4^a-zA-Z]    Any single character which can be the underscore or the 
        number 4 or the ^ symbol or any letter, lower or upper case

?, +, * and the {} count parameters can be appended not only to a single character, but also to a group() or a range[].

therefore,
^.{2}[a-z]{1,2}_?[0-9]*([1-6]|[a-f])[^1-9]{2}a+$
would mean:

^.{2}         = A line beginning with any two characters, 
[a-z]{1,2}     = followed by either 1 or 2 lower case letters, 
_?         = followed by an optional underscore, 
[0-9]*         = followed by zero or more digits, 
([1-6]|[a-f])     = followed by either a digit between 1 and 6 OR a 
        lower case letter between a and f, 
[^1-9]{2}     = followed by any two characters except digits 
        between 1 and 9 (0 is possible), 
a+$         = followed by at least one or more 
        occurrences of 'a' at the end of a line.
*/

$currentPage = 'Coureur';

include_once("includes/init.php");

if(P()){
	if(!P('nom')) error_add('Le champ "nom" est obligatoire !');
	if(!P('prenom')) error_add('Le champ "prénom" est obligatoire !');
	//if(!P('anneeNaissance')) error_add('manque année naissance wesh'); pas obligatoire
	if(!P('pays')) error_add('Le champ "pays" est obligatoire !');
	//if(!P('anneeParticipation')) error_add('lolol annee participation'); pas obligatoire
	

	// /^[A-Z]+$/
	verifCoureur();
	
	if(!error_exists()){

		// on vérifie que le coureur n'existe pas déjà dans la base
		$stmt = $bdd->prepare('SELECT * FROM TDF_COUREUR WHERE NOM = :nom AND PRENOM = :prenom AND CODE_TDF = :pays');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->bindValue(':pays', P('pays'));
		$stmt->execute();
		$correspondance = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt->closeCursor();

		if($correspondance){
			message_redirect('Ce coureur existe déjà !', 'coureur_ajouter.php');
		}

		// on ajoute le coureur dans la base
		$stmt = $bdd->prepare('
			INSERT INTO TDF_COUREUR 
			(N_COUREUR, NOM, PRENOM, CODE_TDF, ANNEE_NAISSANCE, ANNEE_TDF)
			VALUES
			( (SELECT MAX(N_COUREUR)+5 FROM TDF_COUREUR), :nom, :prenom, :pays, :naissance, :anneetdf)');
		$stmt->bindValue(':nom', P('nom'));
		$stmt->bindValue(':prenom', P('prenom'));
		$stmt->bindValue(':pays', P('pays'));
		$stmt->bindValue(':naissance', P('anneeNaissance'));
		$stmt->bindValue(':anneetdf', P('anneeParticipation'));
		$stmt->execute();
		$stmt->closeCursor();

		message_redirect('Le coureur '.P('prenom').' '.P('nom').' a bien été ajouté à la base !', 'coureur_liste.php', 1);
	}
}

include_once("header.php");
?>

<h1>Ajouter un coureur</h1>
<br />

<form class="form-horizontal" name="ajouterCoureur" method="post">
	<div class="control-group">
		<label class="control-label" for="nom">Nom</label>
		<div class="controls">
			<input type="text" id="nom" name="nom" placeholder="DELAMARE" value="<?php if(P('nom')) echo P('nom') ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prenom">Prénom</label>
		<div class="controls">
			<input type="text" id="prenom" name="prenom" placeholder="Jojo" value="<?php if(P('prenom')) echo P('prenom') ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword">Année de naissance</label>
		<div class="controls">
			<select name="anneeNaissance">
				<option value="">---</option>
				<?php 
				for($i = (date('Y') - 18); $i >= 1900; $i--){
					$add = '';
					if(P('anneeNaissance') == $i){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pays">Pays</label>
		<div class="controls">
			<select name="pays">
				<option value="">---</option>
				<?php 
				$stmt = $bdd->prepare('SELECT * FROM TDF_PAYS ORDER BY NOM ASC');
				$stmt->execute();
				$listePays = $stmt->fetchAll(PDO::FETCH_OBJ);
				$stmt->closeCursor();

				foreach($listePays as $l){
					$add = '';
					if(P('pays') == $l->CODE_TDF){
						$add = 'selected=selected';
					}
					if(!P('pays') && $l->CODE_TDF == 'FRA'){
						$add=  'selected=selected';
					}
					echo '<option value="'.$l->CODE_TDF.'" '.$add.'>'.$l->NOM.'</option>';
				}
				?>	
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword">Année de participation</label>
		<div class="controls">
			<select name="anneeParticipation">
				<option value="">---</option>
				<?php 
				for($i = (date('Y') + 1); $i >= 1996; $i--){
					$add = '';
					if(P('anneeParticipation') == $i){
						$add = 'selected=selected';
					}
					if(!P('anneeParticipation') && $i == date('Y')){
						$add = 'selected=selected';
					}
					echo '<option value="'.$i.'" '.$add.'>'.$i.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<button type="submit" class="btn btn-primary btn-info" name="envoyer">Ajouter</button>
	</div>
</form>


<?php include_once("footer.php"); ?>
