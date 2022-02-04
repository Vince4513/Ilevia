<?php
session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

if(isset($_GET['id']) AND $_GET['id'] > 0){
    $getId = intval($_GET['id']);
    $sql ="SELECT * FROM utilisateur WHERE numu =".$getId;
    $resultat=pg_query($sql);
    
    if (!$resultat){ 
    	echo "Probleme lors du lancement de la requete";
	exit;
	}
    $userInfo = pg_fetch_array($resultat,0,PGSQL_ASSOC);
    $profil_anonyme = strlen($userInfo['nom'])==0;
?>

<html>
    <head>
        <title>ILEVIA</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="style-member.css">
    </head>
    <body>
        <section class="header">
            <div class="profil">
                <div class="row">
                    <div class="profilWindow-col">
                        <?php if($profil_anonyme)
                        	echo "<h2>Carte anonyme n°".$userInfo['numu']."</h2>";
                        else
                        	echo "
                        	<h2>Profil de ".$userInfo['nom']." ".$userInfo['prenom']."</h2>
                        	<h3>Identifiant : ".$userInfo['numu']."</h3>";
                        echo "<p>N'oubliez pas votre identifiant.</p>
                        	<p>Bienvenue sur votre compte !</p>";
                        if(isset($_SESSION['id']) AND $userInfo['numu'] == $_SESSION['id']){ 
                            echo "<div class='redirection-btn'>
                            <table><tr>
                            	    <td><a class='profil-btn' href='achatTickets.php'>Ticket</a></td>";
                            	    if(!$profil_anonyme)
                            	    	echo "<td><a class='profil-btn' href='achatAbonnement.php'>Abonnement</a></td>";
                            	    echo "<td><a class='profil-btn' href='validation.php'>Validation</a></td>
                            	    <td><a class='profil-btn' href='statistiques.php'>Statistiques</a></td>
                            	</tr>
                            </table></br></div>";
	
		// Mon abonnement -----------------------------------------------------------------
		
		
			if(isset($userInfo)) {
				echo "<h3>Mon abonnement</h3>";
		
				$sql = "select count(*) from utilisateur natural join abonnement where numu=".$userInfo['numu'].";";
				$resultat = pg_query($sql);
				$ligne=pg_fetch_array($resultat);
				if (!$resultat) {
				echo "Probleme lors du lancement de la requête";
				exit;
			}
		
			if(!$profil_anonyme) {
				if($ligne['count'] > 0) {
					$sql = "select * from utilisateur natural join abonnement where numu=".$userInfo['numu']." order by datefinabo asc;";
					$resultat = pg_query($sql);
					if (!$resultat) {
						echo "Probleme lors du lancement de la requête";
						exit;
					}
					// Affichage de l'abonnement
					$ligne=pg_fetch_array($resultat);
					echo "<p>Votre abonnement est le suivant :<br/>".$ligne['typeabo'].".<br/>Il expire le ".$ligne['datefinabo'].".</p>";
				} else {
					echo "<p>Vous n'avez pas d'abonnement.</p>";
				}
			}
		
		// Mes tickets restants -----------------------------------------------------------
		
			echo "<h3>Mes tickets</h3>";
		
			$sql = "select count(*) from utilisateur natural join tickcarte where numu=".$userInfo['numu'].";";
			$resultat = pg_query($sql);
			$ligne=pg_fetch_array($resultat);
			if (!$resultat) {
				echo "Probleme lors du lancement de la requête";
				exit;
			}
		
			if($ligne['count'] > 0) {
				$sql = "select libelle,quantite,prix from utilisateur natural join tickcarte natural join ticket where numu=".$userInfo['numu']." order by quantite asc;";
				$resultat = pg_query($sql);
				echo "<table><tr><td>Type de ticket</td><td>Quantité</td><td>Prix à l'unité</td></tr>";
				$ligne=pg_fetch_array($resultat);
				while ($ligne) {
					echo "<tr><td>".$ligne['libelle']."</td><td>".$ligne['quantite']."</td><td>".$ligne['prix']."</td></tr>";
					$ligne=pg_fetch_array($resultat);	
				}
				echo "</table></br>";
			} else {
			echo "<p>Aucun ticket restant.</p>";
		}
			
		// Mon historique de validation ---------------------------------------------------
			
			if(!$profil_anonyme) {
				echo "<h3>Historique de validation</h3>";
			
				$sql = "select count(*) from validation where numu=".$userInfo['numu'].";";
				$resultat = pg_query($sql);
				$ligne=pg_fetch_array($resultat);
				if (!$resultat) {
					echo "Probleme lors du lancement de la requête";
					exit;
				}
			
				$validations = $ligne['count'];
			
				if($validations > 0) {
					$sql = "select nom,libelle,extract(year from datevalidation) as year,extract(month from datevalidation) as month,extract(day from datevalidation) as day,extract(hour from datevalidation) as hour,extract(minute from datevalidation) as min from validation natural join station join typeligne on numtypeligne=typetransport where numu=".$userInfo['numu']." order by datevalidation desc;";
					$resultat = pg_query($sql);
					echo "<table><tr><td>Station</td><td>Type de ligne</td><td>Date</td></tr>";
					$ligne=pg_fetch_array($resultat);
					while ($ligne) {
						echo "<tr><td>".$ligne['nom']."</td><td>".$ligne['libelle']."</td><td>".$ligne['year']."/".$ligne['month']."/".$ligne['day']." ".$ligne['hour'].":".$ligne['min']."</td></tr>";
						$ligne=pg_fetch_array($resultat);	
					}
					echo "</table></br>";
				} else {
					echo "<p>Aucune validation récente.</p>";
				}
			}
		
		
		// Le nombre de fois que je suis monté à chaque station ---------------------------
		
			if (!$profil_anonyme && $validations >= 3) {
				echo "<h3>Mes stations préférées</h3>";
				
				$sql = "select nom, count(*) from validation natural join station where numu=".$userInfo['numu']." group by nom having count(*) >= 2 order by count desc;";
				$resultat = pg_query($sql);
				$ligne=pg_fetch_array($resultat);
				if (!$resultat) {
					echo "Probleme lors du lancement de la requête";
					exit;
				}
				
				echo "<table><tr><td>Station</td><td>Nombre de validations</td></tr>";
				while ($ligne) {
					echo "<tr><td>".$ligne['nom']."</td><td>".$ligne['count']."</td></tr>";
					$ligne=pg_fetch_array($resultat);	
				}
				echo "</table></br></br>";
		}
	}
} ?>
                    </div>
                    <div class="profilInfo-col">
                        <?php
                        	if(!$profil_anonyme){
		                echo "<h2>Informations</h2>
		                <p>Identité : ".$userInfo['nom']." ".$userInfo['prenom']."</p>
		                <p>CAF : ".$userInfo['caf']."</p>";
		                if(isset($_SESSION['id']) AND $userInfo['numu'] == $_SESSION['id']){ ?> 
		                    <a class="profil-btn" href="editionProfil.php">Editer mon profil</a>
                        <?php }} ?>
                        <a class="profil-btn" href="deconnexion.php">Sign out</a>
                    </div>

                </div>
            </div>
        </section>
    </body>
</html>
<?php
} 
?>
