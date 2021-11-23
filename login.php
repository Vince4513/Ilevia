<?php
//Suivre l'utilisateur
session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

if(isset($_POST['formConnexion'])){
    $userConnect = htmlspecialchars($_POST['loginUser']);
    $pwConnect = sha1($_POST['loginPassword']);
    
    if(!empty($userConnect) AND !empty($pwConnect)){
        
        $reqUser = $dbh->prepare("SELECT * FROM member WHERE pw = ? AND user = ? ");
        $reqUser->execute(array($pwConnect, $userConnect));
        $userExist = $reqUser->rowCount();
        
        if($userExist == 1){

            $userInfo = $reqUser->fetch();
            $_SESSION['id'] = $userInfo['num_member'];
            $_SESSION['username'] = $userInfo['user'];
            $_SESSION['email'] = $userInfo['email'];
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
        <img src="images/avatar.png" alt="">
        <h2>Login</h2>
        <div class="input-group">
          <input type="text" name="loginUser" id="loginUser" required>
          <label for="loginUser">User Name</label>
        </div>
        <div class="input-group">
          <input type="password" name="loginPassword" id="loginPassword" required>
          <label for="loginPassword">Password</label>
        </div>
        <input type="submit" value="Login" name="formConnexion" class="submit-btn">
        
        <div class="link-col-l">
          <a href="#forgot-pw" class="forgot-pw">Forgot Password?</a>
        </div>
        <div class="link-col-r">
          <a href="registration.php" class="account">Registration?</a>
        </div>
        
      </form>
      <div><?php if(isset($erreur)){ echo $erreur; } ?></div>

      <div id="forgot-pw">
        <form action="form-pw.php" class="form" method="POST">
          <a href="#" class="close">&times;</a>
          <h2>Reset Password</h2>
          <div class="input-group">
            <input type="text" name="userName" id="userName" required>
            <label for="userName">Username</label>
          </div>
          <div class="input-group">
            <input type="email" name="email" id="email" required>
            <label for="email">Email</label>
          </div>
          <input type="submit" value="Submit" class="submit-btn">
        </form>
      </div>
    </div>
  </div>
</body>
</html>