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
    <h1>Ticket</h1>
	</section>

<!-- Course -->

	<section class="course">
		<h1>Acheter un ticket...</h1>
		<p>Select the type of ticket you want and the quantity needed right after.</p></br>
		
		<?php 
		
		extract($_POST);
		
		if(isset($_SESSION['id']) || isset($numcarte)){ ?> 
		
		<div class="row">
		    <?php
			$sql = "select * from ticket where numtick>=0 order by numtick asc;";
			$resultat = pg_query($sql);
			$ligne=pg_fetch_array($resultat);
			if (!$resultat) {
				echo "Probleme lors du lancement de la requête";
				exit;
			}
			echo "<p><form action='achatTermine.php' method = 'post'><select class='red-btn' name = 'tickets'>";
			while($ligne) {
				echo"<option value=".$ligne['numtick'].">".$ligne['libelle']." : ".sprintf('%.2f',$ligne['prix'])."€";
				if (isset($ligne['prix10'])){
					echo ". Prix à la dizaine : ".sprintf('%.2f',$ligne['prix10'])."€";					}
				echo"</option><br>";
				$ligne=pg_fetch_array($resultat);
			}
			echo "</select>
			<input type='number' class='red-btn' name='nombre' value='1' min='1'><br/><br/>
			<input type='submit' class='red-btn' name='submit' value='Acheter'>
			<input type='hidden' name='numcarte' value=".$numcarte."></form>";
			?>
		   </p>
		</div>
		<?php 	} else {
				echo "<form action='achatTickets.php' method = 'post' name='carte'>
				<label class='red-btn'>Selectionner votre numéro de carte anonyme :</label>
				<input class='red-btn' type='text' name='numcarte' placeholder='110200'>
				<input class='red-btn' type='submit' value='Verifier'>
				</form>";
				echo "<form action = 'carteAnonyme.php' method='post' name='creationcarte'><p>Pas de carte ? <input type='submit' class='red-btn' name='submit' value='Créez en une'></p></form>";
			} ?>
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
