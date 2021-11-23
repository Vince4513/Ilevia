<?php
//Suivre l'utilisateur
session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

if(isset($_POST['formInscription'])) {

  $username = htmlspecialchars($_POST['registrationUser']);
  $lastname = htmlspecialchars($_POST['lastName']);
  $firstname = htmlspecialchars($_POST['firstName']);
  $age = htmlspecialchars($_POST['age']);
  
  $email = htmlspecialchars($_POST['email']);
  $emailC = htmlspecialchars($_POST['email-C']);
  $pw = sha1($_POST['loginPassword']);
  $pw2 = sha1($_POST['verifyPassword']);

    if(!empty($_POST['registrationUser']) AND !empty($_POST['lastName']) AND !empty($_POST['firstName']) AND !empty($_POST['age']) AND !empty($_POST['email']) AND !empty($_POST['email-C']) AND !empty($_POST['loginPassword']) AND!empty($_POST['verifyPassword'])){
       
        $userLength = strlen($username);
        if($userLength <= 255){

            if($email == $emailC){
                $req_email = $dbh->prepare("SELECT * FROM member WHERE email = ?");
                $req_email->execute(array($email));
                $emailExist = $req_email->rowCount();

                if($emailExist == 0)
                {
                    if($pw == $pw2){
                        $req_pseudo = $dbh->prepare("SELECT * FROM member WHERE user = ?");
                        $req_pseudo->execute(array($username));
                        $pseudoExist = $req_pseudo->rowCount();

                        if($pseudoExist == 0)
                        {
                            $insertmbr = $dbh->prepare("INSERT INTO member(lastname, firstname, age, user, pw, email) VALUES (?, ?, ?, ?, ?, ?)");
                            $insertmbr->execute(array($lastname, $firstname, $age, $username, $pw, $email));
                            $erreur = "Votre compte a bien été créé !";
                            
                            $_SESSION['id'] = $dbh -> lastInsertId();
                            header("Location: profil.php?id=".$_SESSION['id']);
                        }
                        else{
                            $erreur = "Pseudo déjà utilisé";
                        }
                    }
                    else{
                        $erreur = "Vos mot de passe ne correspondent pas !";
                    }
                }
                else{
                    $erreur = "Addresse mail déjà utilisée";
                }
            }
            else{
                $erreur = "Vos addresses mails ne correspondent pas !";
            }
        }
        else{
            $erreur = "Votre pseudo ne doit pas dépasser 255 caratères !";
        }
    }
    else {
        $erreur = "Tous les champs ne sont pas complétés !";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <title>Monitoring Real Estate</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="script.js" defer></script>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="style-member.css">
</head>
<body>
  <div class="header">
    <div class="login-wrapper">
      <form action="" class="form form-reg" method="POST">
        <h2>Registration</h2>

        <!-------- Progression bar--------->
        <div class="container">
          <div class="progress-steps-container">
              <div class="step-progression" id="stepProgression"></div>
              <div class="step-number personnal"><i class="fas fa-user"></i><p>Personnal</p></div>
              <div class="step-number housing"><i class="fas fa-home"></i><p>Housing</p></div>
          </div>

          <div class="row">
              <button id="prevBtn" class="bar-btn">Prev</button>
              <button id="nextBtn" class="bar-btn">Next</button>
          </div>
        </div>

        <!---------- Input fields --------------->
        <div class="row">
          <div class="col-left">
            <div class="input-group">
              <input type="text" name="registrationUser" id="registrationUser" value="<?php if(isset($username)){echo $username;} ?>" required>
              <label for="registrationUser">User Name</label>
            </div>
            <div class="input-group">
              <input type="text" name="lastName" id="lastName" value="<?php if(isset($lastname)){echo $lastname;} ?>" required>
              <label for="lastName">Lastname</label>
            </div>
            <div class="input-group">
              <input type="text" name="firstName" id="firstName" value="<?php if(isset($firstname)){echo $firstname;} ?>" required>
              <label for="firstName">Firstname</label>
            </div>
            <div class="input-group">
              <input type="text" name="age" id="age" value="<?php if(isset($age)){echo $age;}?>" required>
              <label for="age">Age</label>
            </div>
          </div>
          <div class="col-right">
            <div class="input-group">
              <input type="email" name="email" id="email" value="<?php if(isset($email)){echo $email;} ?>" required>
              <label for="email">Email</label>
            </div>
            <div class="input-group">
              <input type="email" name="email-C" id="email-C" value="<?php if(isset($emailC)){echo $emailC;} ?>" required>
              <label for="email-C">Confirm email</label>
            </div>
            <div class="input-group">
              <input type="password" name="loginPassword" id="loginPassword" required>
              <label for="loginPassword">Password</label>
            </div>
            <div class="input-group">
              <input type="password" name="verifyPassword" id="verifyPassword" required>
              <label for="verifyPassword">Confirm Password</label>
            </div>
          </div>
        </div>
        <input type="submit" value="Registration" name="formInscription" class="submit-btn registration-btn">
        <a href="login.php" class="account">Already have an account?</a>
        <div align="center"><?php if(isset($erreur)){ echo $erreur; } ?></div>
      </form>
    </div>
  </div>
</body>
</html>