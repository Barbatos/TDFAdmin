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

	      .form-signin {
	        max-width: 300px;
	        padding: 19px 29px 29px;
	        margin: 0 auto 20px;
	        background-color: #fff;
	        border: 1px solid #e5e5e5;
	        -webkit-border-radius: 5px;
	           -moz-border-radius: 5px;
	                border-radius: 5px;
	        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	                box-shadow: 0 1px 2px rgba(0,0,0,.05);
	      }
	      .form-signin .form-signin-heading,
	      .form-signin .checkbox {
	        margin-bottom: 10px;
	      }
	      .form-signin input[type="text"],
	      .form-signin input[type="password"] {
	        font-size: 16px;
	        height: auto;
	        margin-bottom: 15px;
	        padding: 7px 9px;
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
						<li class="dropdown <?php if($currentPage == "Coureur") echo 'active' ?>">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">
						      Gestion coureurs
						      <b class="caret"></b>
						    </a>
						    <ul class="dropdown-menu">
						      	<li><a href="<?= $Site['base_address'] ?>coureur_liste.php">Liste des coureurs</a></li>
						      	<li><a href="<?= $Site['base_address'] ?>coureur_ajouter.php">Ajouter un coureur</a></li>
						    </ul>
						</li>
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

