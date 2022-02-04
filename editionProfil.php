<?php
//Suivre l'utilisateur
session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

if(isset($_SESSION['id'])){

    $resultat = pg_query($con,"SELECT * FROM utilisateur WHERE numu =".$_SESSION['id']);
    if (!$resultat){ 
	echo "Probleme lors du lancement de la requete";
	exit;
	}
    $userInfo = pg_fetch_array($resultat,0,PGSQL_ASSOC);

    if(isset($_POST['newName']) AND $_POST['newName'] != $userInfo['nom']){
        $newName = htmlspecialchars($_POST['newName']);
        $insertName = pg_query($con,"UPDATE utilisateur SET nom ='".$newName."' WHERE numu=".$_SESSION['id']);
        header("Location: profil.php?id=".$_SESSION['id']);
    }

    if(isset($_POST['newCaf']) AND $_POST['newCaf'] != $userInfo['caf']){
        $newCaf = htmlspecialchars($_POST['newCaf']);
        $insertCaf = pg_query($con,"UPDATE utilisateur SET caf =".$newCaf." WHERE numu =".$_SESSION['id']);
        header("Location: profil.php?id=".$_SESSION['id']);
    }
    
    if(isset($_POST['newName']) AND $_POST['newName'] == $userInfo['nom'] AND isset($_POST['newCaf']) AND $_POST['newCaf'] == $userInfo['caf']){
        header("Location: profil.php?id=".$_SESSION['id']);
    }
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
                        <h2>Edition de mon profil</h2>
                        
                        <form action="" class="form form-edit" method="POST">
                            <div class="row">
                                <div class="col-left">
                                    <div class="input-group">
                                        <input type="text" name="newName" value="<?php echo $userInfo['nom']; ?>" />
                                        <label for="newName">Nom : </label>
                                    </div>
                                </div>
                                <div class="col-right">
                                    <div class="input-group">
                                        <input type="number" name="newCaf" title="QF1 : Quotient Familial CAF inférieur à 374€
QF2 : Quotient Familial CAF de 375€ à 537€.
QF3 : Quotient Familial CAF de 538€ à 716€." min=0 max=3 value="<?php echo $userInfo['caf'];?>" />
                                        <label for="newCaf">CAF : </label>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="submit-btn" name="formEdition" value="Udpate my profil" />
                        </form>
                        <?php if(isset($msg)) { echo $msg; } ?>
                    </div>
                    <div class="profilInfo-col">
                        <h2>Informations</h2>
                        <p>Identité : <?php echo $userInfo['nom']." ".$userInfo['prenom']; ?></p>
                        <p>CAF : <?php echo $userInfo['caf']; ?></p>
                        <?php 
                        if(isset($_SESSION['id']) AND $userInfo['numu'] == $_SESSION['id']){ ?> 
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
<?php
}
else{
    header("Location: connexion.php");
}
?>
