<!DOCTYPE html>
<?php session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

/************************************* Nombre d'utilisateurs ***************************************/
// Prépare une requête pour l'exécution
$result0 = pg_query($con,'SELECT * FROM utilisateur where nom is not null');

if (!$result0){ 
	echo "Probleme lors du lancement de la requete";
	exit;
}
$rows_Utilisateur = pg_num_rows($result0);


/************************************* Nombre de lignes *******************************************/
// Prépare une requête pour l'exécution
$result1 = pg_query($con,'SELECT * FROM ligne');

if (!$result1){ 
	echo "Probleme lors du lancement de la requete";
	exit;
}
$rows_Ligne = pg_num_rows($result1);


/***************************** Nombre de voyage par station ***************************************/
// Prépare une requête pour l'exécution
$result2 = pg_query($con,'select nom,count((numu,datevalidation,numbv,numstation)) as nbtrajetstation from validation natural join station group by nom');

if (!$result2){ 
	echo "Probleme lors du lancement de la requete";
	exit;
}
$rows_RidePerStation=pg_fetch_array($result2);


/************************************ Nombre de voyage par ligne **********************************/
// Prépare une requête pour l'exécution
$result3 = pg_query($con,"select t.libelle||' '||l.libelle as transport,count(*) as nbtrajetligne from validation natural join stationligne natural join typeligne t join ligne l on t.numtypeligne=l.numtypeligne where typetransport=l.numligne group by t.libelle,l.libelle order by t.libelle asc");

if (!$result3){ 
	echo "Probleme lors du lancement de la requete";
	exit;
}
$rows_RidePerLine = pg_num_rows($result3);


/*************************** Moyenne du nombre de voyage par utilisateur **************************/
// Prépare une requête pour l'exécution
$result4 = pg_query($con,'select cast(count((numu,datevalidation,numbv,numstation))as float)/(select count(distinct numu) from utilisateur) as trajetmoyen from validation;');

if (!$result4){ 
	echo "Probleme lors du lancement de la requete";
	exit;
}
$res4 = pg_fetch_array($result4);
$rows_NumberJourneysPerSuscriber = $res4[0];

/************************** Répartition des titres de transport utilisés **************************/
// Prépare une requête pour l'exécution
$result5 = pg_query($con,'select libelle, cast(count(*)as float)/(select count(*) from validation where numtick is not null) as Pourcentage from validation natural join ticket  group by libelle;');

if (!$result5){ 
	echo "Probleme lors du lancement de la requete";
	exit;
}
$rows_Repartition=pg_fetch_array($result5);

?>

<HTML>
<HEAD>
	<TITLE> ILEVIA </TITLE>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8"/>
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
    <h1>Statistiques</h1>
	</section>

<!------Course ------>

<section class="course">
  <h1>Here's the numbers</h1>
  <p>Si vous souhaitez connaître le nombre de lignes de métro ou autres statistiques sur l'entreprise,</br>vous les trouverez probablement ici...</p>
  
  <div class="row">
    <div class="course-col">
      <h3>Nombres d'abonnés</h3>
      <p><?php echo $rows_Utilisateur ?></p>
    </div>
    <div class="course-col">
      <h3>Nombre de lignes</h3>
      <p><?php echo $rows_Ligne ?></p>
    </div>
  </div>
  <div class="row">
    <div class="course-col">
      <h3>Nombre de trajets par station</h3>
      <p><table><?php 
        while($rows_RidePerStation) {
		echo"<tr><td>".$rows_RidePerStation['nom']."</td><td>".$rows_RidePerStation['nbtrajetstation']."</td></tr>";
		$rows_RidePerStation=pg_fetch_array($result2);
	}
	?></table></p>
    </div>
    <div class="course-col">
      <h3>Nombre de trajets par ligne</h3>
      <p><table><?php 
        while($rows_RidePerLine) {
		echo"<tr><td>".$rows_RidePerLine['transport']."</td><td>".$rows_RidePerLine['nbtrajetligne']."</td></tr>";
		$rows_RidePerLine=pg_fetch_array($result3);
	}
	?></table></p>
    </div>
  </div>
  <div class="row">
    <div class="course-col">
      <h3>Nombre de trajets moyen par abonnés</h3>
      <p><?php echo sprintf('%.2f',$rows_NumberJourneysPerSuscriber) ?></p>
    </div>
    <div class="course-col">
      <h3>Répartition des titres de transport utilisé</h3>
      <p><table><?php 
        while($rows_Repartition) {
		echo"<tr><td>".$rows_Repartition['libelle']."</td><td>".sprintf('%.1f',$rows_Repartition['pourcentage']*100) ." %</td></tr>";
		$rows_Repartition=pg_fetch_array($result5);
	}
	?></table></p>
    </div>
  </div>
  
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
