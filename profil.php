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
                        <h2>Profil de <?php echo $userInfo['nom']." ".$userInfo['prenom']; ?></h2>
                        <p>Bienvenue sur votre compte !</p>
                        <?php 
                        if(isset($_SESSION['id']) AND $userInfo['numu'] == $_SESSION['id']){ ?>
                            <a class="profil-btn" href="achatTickets.php?id=".<?php echo $_SESSION['id']?>>Acheter un ticket</a>
                            <a class="profil-btn" href="validation.php?id=".<?php echo $_SESSION['id']?>">Valider un titre de transport</a>
                            <a class="profil-btn" href="statistiques.php?id=".<?php echo $_SESSION['id']?>">Afficher les statistiques</a>
                        <?php } ?>
                    </div>
                    <div class="profilInfo-col">
                        <h2>Informations</h2>
                        <p>Identité : <?php echo $userInfo['nom']." ".$userInfo['prenom']; ?></p>
                        <p>CAF : <?php echo $userInfo['caf']; ?></p>
                        <?php 
                        if(isset($_SESSION['id']) AND $userInfo['numu'] == $_SESSION['id']){ ?> 
                            <a class="profil-btn" href="editionProfil.php">Editer mon profil</a>
                            <a class="profil-btn" href="deconnexion.php">Sign out</a>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </section>
    </body>
</html>
<?php
} 
?>
