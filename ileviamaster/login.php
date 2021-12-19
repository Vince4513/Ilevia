<?php
//Suivre l'utilisateur
session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

if(isset($_SESSION['id']) AND $_SESSION['id'] > 0){
  header("Location: profil.php?id=".$_SESSION['id']);
}
else{
	if(isset($_POST['formConnexion'])){
	    $nameConnect = htmlspecialchars($_POST['loginUser']);
	    $lastnameConnect = htmlspecialchars($_POST['loginPassword']);
	    
	    if(isset($nameConnect) AND isset($lastnameConnect)){
		
		$sql="SELECT * FROM utilisateur WHERE prenom ='".$lastnameConnect."' AND numu =".$nameConnect;
		$resultat=pg_query($sql);

		//(toujours)verifier que la requete a fonctionné
		if (!$resultat)
			{ echo "Probleme lors du lancement de la requete";
			exit;
			}
			
		//copie de la premiere ligne de resulat dans le tableau ligne
		$userExist=pg_num_rows($resultat);
		
		if($userExist == 1){

		    $userInfo = pg_fetch_array($resultat,0,PGSQL_ASSOC);
		    $_SESSION['id'] = $userInfo['numu'];
		    $_SESSION['name'] = $userInfo['nom'];
		    $_SESSION['lastname'] = $userInfo['prenom'];
		    $_SESSION['caf'] = $userInfo['caf'];
		    header("Location: profil.php?id=".$_SESSION['id']);
		}
		else{
		    $erreur = "Mauvais email ou identifiant";
		}
	    }
	    else{
		$erreur = "Tous les champs doivent être complétés !";
	    }
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ILEVIA</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="style-member.css">
</head>
<body>
  <div class="header">
    <div class="login-wrapper">
      <form action="" class="form" method="POST">
        <h2>Login</h2>
        <div class="input-group">
          <input type="text" name="loginUser" id="loginUser" required>
          <label for="loginUser">Identifiant</label>
        </div>
        <div class="input-group">
          <input type="password" name="loginPassword" id="loginPassword" required>
          <label for="loginPassword">Prenom</label>
        </div>
        <input type="submit" value="Login" name="formConnexion" class="submit-btn">
        
        <div class="link-col-r">
          <a href="registration.php" class="account">Inscription</a>
        </div>
        <div class="link-col-r">
          <a href="index.php" class="account">Menu</a>
        </div>
        <div align='center'><?php if(isset($erreur)){ echo $erreur; } ?></div>
      </form>
    </div>
  </div>
</body>
</html>
