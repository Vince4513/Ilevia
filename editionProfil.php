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

    if(isset($_POST['newPseudo']) AND !empty($_POST['newPseudo']) AND $_POST['newPseudo'] != $user['user']){
        $newPseudo = htmlspecialchars($_POST['newPseudo']);
        $insertPseudo = pg_query($con,"UPDATE member SET nom =".$newPseudo." WHERE numu=".$_SESSION['id']);
        header("Location: profil.php?id=".$_SESSION['id']);
    }

    if(isset($_POST['newEmail']) AND !empty($_POST['newEmail']) AND $_POST['newEmail'] != $user['email']){
        $newEmail = htmlspecialchars($_POST['newEmail']);
        
        $req_email = $dbh->prepare("SELECT * FROM member WHERE email = ?");
        $req_email->execute(array($newEmail));
        $emailExist = $req_email->rowCount();

        if($emailExist == 0) {
            $insertEmail = $dbh->prepare("UPDATE member SET email = ? WHERE num_member = ?");
            $insertEmail->execute(array($newEmail, $_SESSION['id']));
            header("Location: profil.php?id=".$_SESSION['id']);
        }
        else{
            $msg = "Address already used !";
        }
    }

    if(isset($_POST['newPw']) AND !empty($_POST['newPw']) AND isset($_POST['newPw2']) AND !empty($_POST['newPw2'])){
        $newPw = sha1($_POST['newPw']);
        $newPw2 = sha1($_POST['newPw2']);

        if($newPw == $newPw2){
            $insertPw = $dbh->prepare("UPDATE member SET pw = ? WHERE num_member = ?");
            $insertPw->execute(array($newPw, $_SESSION['id']));
            header("Location: profil.php?id=".$_SESSION['id']);
        }
        else{
            $msg = "Your passwords are differents";
        }
    }
    if(isset($_POST['newPseudo']) AND $_POST['newPseudo'] == $_SESSION['username']){
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
                                <div class="input-group">
                                    <input type="text" name="newPseudo" value="<?php echo $user['nom']; ?>" />
                                    <label for="newPseudo">Pseudo : </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-left">
                                    <div class="input-group">
                                        <input type="email" name="newEmail" value="<?php echo $user['prenom']; ?>" />
                                        <label for="newEmail">Email : </label>
                                    </div>
                                    <div class="input-group">
                                        <input type="email" name="newEmail2" value="<?php echo $user['dateNaiss'];?>" />
                                        <label for="newEmail2">Confirm email : </label>
                                    </div>
                                </div>
                                <div class="col-right">
                                    <div class="input-group">
                                        <input type="password" name="newPw" />
                                        <label for="newPw">Password : </label>
                                    </div>
                                    <div class="input-group">
                                        <input type="password" name="newPw2" />
                                        <label for="newPw2">Confirm password : </label>
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
