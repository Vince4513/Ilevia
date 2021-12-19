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
					    <li><a href='achatAbonnements.php'>ABONNEMENT</a></li>
					    <li><a href='validation.php'>VALIDATION</a></li>
					    <li><a href='statistiques.php'>STATISTIQUES</a></li>
					    <li><a href='login.php'><i class='fa fa-user'></i></a></li>
				    </ul>
			</div>
		</nav>
    <h1>Ticket</h1>
	</section>
<section class="course">
<?php

	extract($_POST);
	
	if(isset($tickets) && isset($_SESSION['id']) && isset($nombre)) {
		
		$ligne=pg_fetch_array(pg_query("select * from tickcarte where numu=".$_SESSION['id']." and numtick=".$tickets));
		$nombretickets = 0;
		$exists = false;
		if(isset($ligne['quantite'])) {
			$nombretickets = $ligne['quantite'];
			$exists = true;
		}
		
		$ligne=pg_fetch_array(pg_query("select * from ticket where numtick=".$tickets));
		$libelle = $ligne['libelle'];
		
		$prix = $ligne['prix'];
		$prix10 = $prix;
		$dizaines = 0;
		
		if(isset($ligne['prix10'])) {
			$prix10 = $ligne['prix10'];
			$dizaines = $nombre;
			$nombre = $nombre % 10;
			$dizaines -= $nombre;
			$dizaines /= 10;
		}
		
		$prixtotal = $prix * $nombre + $prix10 * $dizaines;
		
		$nombretickets += $nombre;
		$nombretickets += $dizaines;
		$sql = "insert into tickcarte (\"numu\",\"numtick\",\"quantite\") values (".$_SESSION['id'].",".$tickets.",".$nombretickets.")";
		if($exists) {
			$sql = "update tickcarte set quantite=".$nombretickets." where numu=".$_SESSION['id']." and numtick=".$tickets;
		}
		$resultat = pg_query($sql);
		//(toujours)verifier que la requete a fonctionné
		if (!$resultat) {
			echo "Probleme lors du lancement de la requête";
			exit;
		}
		
		echo "<h3>Vous avez dépensé ".sprintf('%.2f',$prixtotal)."€ pour acheter ".$nombre." ".$libelle."</h3>";
	}
	
?>
    	<a class='red-btn' href="index.php">Menu</a>
    </section>
  </body>
</html>
