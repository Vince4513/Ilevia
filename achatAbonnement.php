<?php
	session_start();
	include("connexion.php");
	$con=connect();

	//(toujours) verifier que la connexion est etablie
	if (!$con) {
		echo "Probleme connexion à la base";
		exit;
	}
	
	if(isset($_SESSION['id'])){
		$result = pg_query($con,"select numabo, nom from utilisateur where numu=".$_SESSION['id']);
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
    	<h1>Abonnement</h1>
	</section>

<!-- Course -->

	<section class="course">
		<h1>Acheter un abonnement</h1>
		<p>Pour souscrire à un abonnement, vous devez être connecté à votre compte.</p></br>
		
		<?php if(isset($_SESSION['id'])){
			$ligne = pg_fetch_array($result);
			if(strlen($ligne['nom'])>0){	
				if(isset($ligne['numabo'])) {
				  $sql = "select typeabo, datefinabo, extract(year from datefinabo) as year, extract(month from datefinabo) as month, extract(day from datefinabo) as day from utilisateur natural join abonnement where numu=".$_SESSION['id'];
				  $resultat = pg_query($sql);
				  $ligne=pg_fetch_array($resultat);
				  if (!$resultat) {
				  	echo "Probleme lors du lancement de la requête";
				  	exit;
				  }
				  echo "<p>Vous disposez déjà d'un abonnement de type ".$ligne['typeabo']." sur votre carte.";
			  	  if(isset($ligne['datefinabo']))
			  	  	echo " Expiration de l'abonnement le <b>".$ligne['day']."/".$ligne['month']."/".$ligne['year']."</b></p>";
			  	  echo "<div class='row'><p><form action='abonnementSupprime.php' method = 'post'><input type='submit' class='red-btn' name='submit' value='Supprimer mon abonnement'></form></p></div>";
				}
				else {?> 
			
				    <p>Selectionnez le type d'abonnement souhaité.</p>
				    <?php
				    	$sql = "select extract(years from age(datenaiss)) as age from utilisateur where numu=".$_SESSION['id'];
				    	$resultat = pg_query($sql);
					$ligne=pg_fetch_array($resultat);
					if (!$resultat) {
						echo "Probleme lors du lancement de la requête";
						exit;
					}
					$age = $ligne['age'];
				    
					$sql = "select * from abonnement order by numabo asc;";
					$resultat = pg_query($sql);
					$ligne=pg_fetch_array($resultat);
					if (!$resultat) {
						echo "Probleme lors du lancement de la requête";
						exit;
					}
					
					echo "<div class='row'><p><form action='abonnementTermine.php' method = 'post'><select class='red-btn' name = 'abonnements'>";
					while($ligne) {
						if(!isset($ligne['agemax']) || $age < $ligne['agemax'])
							echo"<option value=".$ligne['numabo'].">".$ligne['typeabo']." : ".sprintf('%.2f',$ligne['prix'])."€/mois</option><br>";
						$ligne=pg_fetch_array($resultat);
					}
					echo "</select>
					<input type='submit' class='red-btn' name='submit' value='Acheter'></form></p></div>";
				}
			     }
		     }
			?>
	  </section>

<!-- Footer -->

	<section class="footer">
	<h4>About Us</h4>
	<p>We are a team of students who are doing a database project.</p>
	<div class="icons">
		<i class="fa fa-facebook"></i>
		<i class="fa fa-twitter"></i>
		<i class="fa fa-instagram"></i>
		<i class="fa fa-linkedin"></i>
		<i class="fa fa-youtube"></i>
	</div>
	<p>Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by Us</p>
	</section>

</body>
</html>
