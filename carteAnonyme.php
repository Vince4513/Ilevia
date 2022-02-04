<?php
	session_start();
	include("connexion.php");
	$con=connect();

	//(toujours) verifier que la connexion est etablie
	if (!$con) {
		echo "Probleme connexion à la base";
		exit;
	}
?>


<html>
<head>
	<title>ILEVIA</title>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8"/>
	<script src="https://kit.fontawesome.com/e62499e6fc.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	  <link rel="preconnect" href="https://fonts.googleapis.com">
	  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">  
</head>
<body>
	<section class="sub-header">
		<nav>
			<a href='index.php'><img src='images/logo.png'></a>
			<div class="nav-links" id="navLinks">
	       			    <ul>
					    <li><a href='index.php'>MENU</a></li>
					    <li><a href='achatTickets.php'>TICKET</a></li>
					    <li><a href='achatAbonnement.php'>ABONNEMENT</a></li>
					    <li><a href='validation.php'>VALIDATION</a></li>
					    <li><a href='statistiques.php'>STATISTIQUES</a></li>
					    <li><a href='login.php'><i class='fa fa-user'></i></a></li>
				    </ul>
			</div>
		</nav>
    <h1>Carte Anonyme</h1>
	</section>
<section class="course">
<?php

	extract($_POST);
	
	if(isset($submit)) {
		$randomcard = rand();

		$sql = "select count(*) from utilisateur where numu=".$randomcard;
		$resultat = pg_query($sql);
		if (!$resultat) {
			echo "Probleme lors du lancement de la requête";
			exit;
		}
		$ligne=pg_fetch_array($resultat);
		
		while($ligne['count'] > 0) {
			$randomcard = rand();
			$sql = "select count(*) from utilisateur where numu=".$randomcard;
			$ligne=pg_fetch_array(pg_query($sql));
		}

		$sql = "insert into utilisateur (numu) values (".$randomcard.")";
		$resultat = pg_query($sql);
		if (!$resultat) {
			echo "Probleme lors du lancement de la requête";
			exit;
		}
		$ligne=pg_fetch_array($resultat);
		
		echo "<h3>La carte numéro ".$randomcard." vous a été attribuée. N'oubliez pas ce numéro.</h3>";
	} else {
		echo "<h3>Création de carte déjà effectuée ou indisponible.</h3>";
	}

?>
    	<a class='red-btn' href="index.php">Menu</a>
    </section>
  </body>
</html>
