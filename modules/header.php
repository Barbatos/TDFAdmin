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
						      	<li><a href="<?= $Site['base_address'] ?>pays/liste/">Gérer les pays</a></li>
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

