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
?>

<!DOCTYPE html>
<html lang="fr">
  	<head>
	    <meta charset="UTF-8">
	    <title>Administration TDF</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="Administration TDF">
	    <meta name="author" content="Charles">

	    <link href="<?= $Site['base_address'] ?>assets/css/bootstrap.css" rel="stylesheet">
	    <style type="text/css">
	      body {
	        padding-top: 40px;
	        padding-bottom: 40px;
	        background-color: #f5f5f5;
	      }

	    </style>
	    <link href="<?= $Site['base_address'] ?>assets/css/bootstrap-responsive.css" rel="stylesheet">

	    <!--[if lt IE 9]>
	      	<script src="<?= $Site['base_address'] ?>assets/js/html5shiv.js"></script>
	    <![endif]-->
  	</head>
  	<body>
	    <div class="container">

			<div class="navbar navbar-inverse">
				<div class="navbar-inner">
					<a class="brand" href="<?= $Site['base_address'] ?>">TDFAdmin</a>
					<ul class="nav">
						<li <?php if($currentPage == "Accueil") echo 'class="active"' ?>>
							<a href="<?= $Site['base_address'] ?>">Accueil</a>
						</li>

						<li class="dropdown <?php if($currentPage == "Epreuves") echo 'active' ?>">
							<a href="<?= $Site['base_address'] ?>epreuves/liste/">Epreuves</a>
						</li>

						<li class="dropdown <?php if($currentPage == "Calendrier") echo 'active' ?>">
							<a href="<?= $Site['base_address'] ?>calendrier/liste/">Calendrier</a>
						</li>

						<li class="dropdown <?php if($currentPage == "Coureurs") echo 'active' ?>">
							<a href="<?= $Site['base_address'] ?>coureurs/liste/">Coureurs</a>
						</li>

						<li class="dropdown <?php if($currentPage == "Participations") echo 'active' ?>">
							<a href="<?= $Site['base_address'] ?>participations/liste/">Participations</a>
						</li>

						<li class="dropdown <?php if($currentPage == "Sponsors") echo 'active' ?>">
							<a href="<?= $Site['base_address'] ?>sponsors/liste/">Sponsors</a>
						</li>

						<li class="dropdown <?php if($currentPage == "Autres") echo 'active' ?>">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">
						      	Autres
						      	<b class="caret"></b>
						    </a>
						    <ul class="dropdown-menu">
						      	<li><a href="<?= $Site['base_address'] ?>categories-epreuves/liste/">Gérer les catégories d'épreuves</a></li>
						      	<li><a href="<?= $Site['base_address'] ?>commentaires/liste/">Gérer les commentaires</a></li>
						      	<li><a href="<?= $Site['base_address'] ?>directeurs/liste/">Gérer les directeurs</a></li>
						      	<!--<li><a href="<?= $Site['base_address'] ?>pays/liste/">Gérer les pays</a></li>-->
						    </ul>
						</li>

						<?php if($admin->isLogged()){ ?>
						<li>
							<a href="?logout=1">Déconnexion</a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<?php 
			if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])){
				foreach($_SESSION['errors'] as $e){
					echo '<div class="alert alert-error">'.$e['error'].'</div>';
				}
				$_SESSION['errors'] = array();
			}

			if(isset($_SESSION['messages']) && !empty($_SESSION['messages'])){
				foreach($_SESSION['messages'] as $e){
					echo '<div class="alert alert-success">'.$e['message'].'</div>';
				}
				$_SESSION['messages'] = array();
			}
			?>

