<!DOCTYPE html>
<?php session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

extract($_POST);

if(isset($_SESSION['id']) || isset($numcarte)){
    $getId = 0;
    if (isset($_SESSION['id']))
	$getId = intval($_SESSION['id']);
    else
	$getId = $numcarte;
    $sql ="SELECT * FROM utilisateur WHERE numu =".$getId;
    $resultat=pg_query($sql);
    
    if (!$resultat){ 
    		echo "Probleme lors du lancement de la requete";
		exit;
	}
    	$userInfo = pg_fetch_array($resultat,0,PGSQL_ASSOC);
		
	//(toujours) verifier que la connexion est etablie
	if (!$con) {
		echo "Probleme connexion à la base";
		exit;
	}

	$result0 = pg_query($con,"select numu, (prenom || ' ' || nom) as prenomnom from utilisateur;");
	$ligne_Utilisateur=pg_fetch_array($result0);

	//(toujours)verifier que la requete a fonctionné
	if (!$result0) {
		echo "Probleme lors du lancement de la requête";
		exit;
	}
		
	//*
		
	$sql1 = "select nom, numstation,numligne,ligne.numtypeligne,typeligne.libelle as type,ligne.libelle from stationligne natural join station natural join ligne inner join typeligne on ligne.numtypeligne=typeligne.numtypeligne order by numtypeligne asc;";
	$result1 = pg_query($sql1);
	$ligne_Station=pg_fetch_array($result1);

	//(toujours)verifier que la requete a fonctionné
	if (!$result1) {
		echo "Probleme lors du lancement de la requête";
		exit;
	}
}
?>
<HTML>
<HEAD>
	<TITLE> ILEVIA </TITLE>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8"/> <!Differents parametres>
  <script src="https://kit.fontawesome.com/e62499e6fc.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet"> 
</HEAD>
<BODY>
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
    <h1>Validation</h1>
	</section>

<!------Course ------>

<section class="course">
  <h1>Valider un titre de transport</h1>
  <p>Vous devez être connecté à votre compte ou possédez une carte anonyme. Ensuite, vous pourrez selectionnez une station, une borne et le ticket que vous souhaité sélectionner si vous n'avez pas d'abonnement pour enregistrer votre passage.</p>

  <?php if(isset($_SESSION['id']) || isset($numcarte)) { ?> 

	  <div class="row">
	  <p><?php
	  extract($_POST);
	  if(!isset($station)) {
	  	echo "<form method = 'post'>";
	  	echo "<h3>Station</h3> <select class='red-btn' name = 'station'>";
		while($ligne_Station) {
			echo"<option value=".$ligne_Station['numstation'].",".$ligne_Station['numligne'].",".$ligne_Station['numtypeligne'].">".$ligne_Station['type']." - Ligne ".$ligne_Station['libelle']." - ".$ligne_Station['nom']."</option>";
		$ligne_Station=pg_fetch_array($result1);
		 }
	    echo "</select><br/><input class='red-btn' type='submit' name='submit' value='Valider'>";
	    if(isset($numcarte))
		    echo "<input type='hidden' name='numcarte' value='".$numcarte."'>";
	    echo "</form></p></div><div class=\"row\"><p>";
	    }
	  	if(isset($station)) {
			$possedeticket = pg_fetch_array(pg_query("select count(*) from ticket natural join tickcarte where numu=".$userInfo['numu']))['count'] > 0;
			$possedeabonnement = pg_fetch_array(pg_query("select count(numabo) from utilisateur where numabo is not null and numu=".$userInfo['numu']))['count'] > 0;
			if($possedeticket || $possedeabonnement) {
				echo "<form method = 'post'>";
				$stationinfo = explode(",",$station);
				
				$sql = "select numbv, numstation from bornevalid where numstation = ".$stationinfo[0].";";
				$result2 = pg_query($sql);
				$ligne=pg_fetch_array($result2);

				//(toujours)verifier que la requete a fonctionné
				if (!$result2) {
					echo "Probleme lors du lancement de la requête";
					exit;
				}
				
				echo "<h3>Borne numéro</h3> <select class='red-btn' name = 'values'>";
				while($ligne) {
					echo"<option value=\"".$userInfo['numu'].",".$ligne['numbv'].",".$stationinfo[0].",".$stationinfo[2]."\">".($ligne['numbv']+1)."</option>";
					$ligne=pg_fetch_array($result2);
				}
		 		echo "</select>";
				
				if (!$possedeabonnement) {
			 		$heure = pg_fetch_array(pg_query("select extract(hour from now()) as hour;"))['hour'];
			 		
			 		$sql = "select numtick, libelle, tempsvalide from ticket natural join tickcarte where numu=".$userInfo['numu'];
					$result2 = pg_query($sql);
					$ligne=pg_fetch_array($result2);
					//(toujours)verifier que la requete a fonctionné
					if (!$result2) {
						echo "Probleme lors du lancement de la requête";
						exit;
					}
					
					echo "<h3>Ticket à utiliser</h3> <form method = 'post'><select class='red-btn' name = 'ticket'>";
					while($ligne) {
						if($ligne['numtick'] == 5) {
							if(hour >= 19)
								echo"<option value=5>".$ligne['libelle']." - Utilisable jusqu'à la fin de service sur l'ensemble du réseau bus, métro et tram</option>";
						} 
						else {
							echo"<option value=".$ligne['numtick'].">".$ligne['libelle']." - Disponible ".$ligne['tempsvalide']."h</option>";
						}
						$ligne=pg_fetch_array($result2);
					}
			 		echo "</select>";
		 		}
		 		echo "<input class='red-btn' type='submit' name='submit' value='Valider'>";
		 		if(isset($numcarte))
			 		echo "<input type='hidden' name='numcarte' value=".$numcarte.">";
			 	echo "</form>";
			 }
			 else {
			 	echo "<p>Vous n'avez plus de tickets. Veuillez en acheter sur la <a class='red-btn' href=\"achatTickets.php\">page d'achat</a></p>";
			 }
		 } ?></p></div><div class='row'><p> <?php  
		// ------------------------------------------------------------------------------
		
		if(isset($values)) {
			
			$sql = "select count(*) from utilisateur natural join abonnement where numu=".$userInfo['numu'];
			$possedeAbonnement = pg_fetch_array(pg_query($sql))['count'] > 0;
			$validationRecente = false;
			if(!isset($ticket) || $ticket != 5) {
				$sql = "select count(*) from validation natural join ticket where now()-datevalidation < (interval '1' hour) * tempsvalide 
				and numtick is not null and numu=".$userInfo['numu'];
				$validationRecente = pg_fetch_array(pg_query($sql))['count'] > 0;
			} 
			else {
				$sql = "select extract(hour from now()) as hour, extract(day from now()) as day, 
				extract(day from (datevalidation + interval '1' day)) as nextday, extract(day from datevalidation) as validationday from validation";
				$ligne = pg_fetch_array(pg_query($sql));
				$validationRecente = ($ligne['hour'] >= 19 && $ligne['day'] == $ligne['validation_day']) || ($ligne['hour'] < 6 && $ligne['day'] == ($ligne['next_day']));
			}
			
			$vl = explode(",", $values);
			
			$sql = "insert into validation (\"numu\",\"numbv\",\"numstation\", \"typetransport\",\"datevalidation\") values (".$vl[0].",".$vl[1].",".$vl[2].",".$vl[3].",now())";
			if(!$validationRecente){
				$numtick = 0;
				if($possedeAbonnement)
					$numtick = -1;
				else
					$numtick = $ticket;
				$sql = "insert into validation (\"numu\",\"numbv\",\"numstation\", \"typetransport\",\"datevalidation\",\"numtick\") values (".$vl[0].",".$vl[1].",".$vl[2].",".$vl[3].",now(), ".$numtick.")";
			}
			$resultat = pg_query($sql);
			//(toujours)verifier que la requete a fonctionné
			if (!$resultat) {
				echo "Probleme lors du lancement de la requête";
				exit;
			}
			
			if(!$validationRecente && !$possedeAbonnement) {
				$sql = "update tickcarte set quantite=quantite-1 where numu=".$vl[0]." and numtick=".$numtick;
				$resultat = pg_query($sql);
				//(toujours)verifier que la requete a fonctionné
				if (!$resultat) {
					echo "Probleme lors du lancement de la requête";
					exit;
				}
				$sql = "delete from tickcarte where quantite<=0";
				$resultat = pg_query($sql);
				if (!$resultat) {
					echo "Probleme lors du lancement de la requête";
					exit;
				}
				$libelle = pg_fetch_array(pg_query("select libelle from ticket where numtick =".$ticket))['libelle'];
				echo "</p></div><div class='row'><h3>Un ticket de type \"".$libelle."\" a été prélevé.</h3></div>"; // Afficher le nom du numtick
			} 
			else if ($validationRecente) {
				$sql = "select extract(hour from now()-datevalidation) as hour, extract(minute from now()-datevalidation) as minute from validation 
				natural join ticket where now()-datevalidation < (interval '1' hour) * tempsvalide and numtick is not null and numu=".$userInfo['numu'];
				$resultat = pg_query($sql);
				$ligne= pg_fetch_array($resultat);
				
				if ($ligne['hour'] > 0) {
					echo "</p></div><div class='row'><p>Vous voyagez depuis ".$ligne['hour']." heures et ".$ligne['minute']." minutes.</p></div>";
				} 
				else {
					echo "</p></div><div class='row'><p>Vous voyagez depuis ".$ligne['minute']." minutes.</p></div>";
				}
			}
			echo "</p></div><div class='row'><p><h3>Validation effectuée</h3></p></div>";
		}?>
		
	<?php 	} else {
			echo "<form action='validation.php' method = 'post' name='carte'>
			<label class='red-btn'>Selectionner votre numéro de carte anonyme :</label>
			<input class='red-btn' type='text' name='numcarte' placeholder='110200'>
			<input class='red-btn' type='submit' value='Verifier'>
			</form>";
			echo "<form action = 'carteAnonyme.php' method='post' name='creationcarte'><p>Pas de carte ? <input type='submit' class='red-btn' name='submit' value='Créez en une'></p></form>";
			} ?>
  
</section>

<!-------- Footer -------->

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

</BODY>
</HTML>
