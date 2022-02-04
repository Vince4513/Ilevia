<?php
//Suivre l'utilisateur
session_start();

//Connexion a la base
include("connexion.php");
$con=connect();

if(isset($_POST['formInscription'])) {

  echo $lastname = htmlspecialchars($_POST['lastName']);
  echo $firstname = htmlspecialchars($_POST['firstName']);
  echo $dateNaiss = htmlspecialchars($_POST['datenaiss']);
  echo $caf = htmlspecialchars($_POST['caf']);

    if(!empty($_POST)){
       
        $userLength = strlen($lastname);
        if($userLength <= 255){
          $insertmbr = "INSERT INTO utilisateur(nom, prenom, datenaiss, caf) VALUES ('".$lastname."', '".$firstname."', '".$dateNaiss."', ".$caf.") returning numu;";
          $resultat = pg_query($insertmbr);
	  $rowa = pg_fetch_row($resultat);
   	  $rowb = current($rowa);
          
          $_SESSION['id'] = $rowb;
          $erreur = "Votre compte a bien été créé !</br>Votre mot de passe est votre prenom et votre identifiant est le suivant : ".$rowb;
          header("Location: profil.php?id=".$_SESSION['id']);
             
        }
        else{
            $erreur = "Votre nom ne peut dépasser 255 caratères !";
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
 <title>ILEVIA</title>
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
        <h2>Inscription</h2>

        <!---------- Input fields --------------->
        <div class="row">
          <div class="col-left">
            <div class="input-group">
              <input type="text" name="lastName" id="lastName" value="<?php if(isset($lastname)){echo $lastname;} ?>" required>
              <label for="lastName">Nom</label>
            </div>
            <div class="input-group">
              <input type="text" name="firstName" id="firstName" value="<?php if(isset($firstname)){echo $firstname;} ?>" required>
              <label for="firstName">Prenom</label>
            </div>

          </div>
          
          <div class="col-right">
            <div class="input-group">
              <input type="date" name="datenaiss" id="datenaiss" value="<?php if(isset($dateNaiss)){echo $dateNaiss;}?>" required>
              <label for="datenaiss">Date de naissance</label>
            </div>
            <div class="input-group">
              <input type="number" min=0 max=3 title="QF1 : Quotient Familial CAF inférieur à 374€
QF2 : Quotient Familial CAF de 375€ à 537€.
QF3 : Quotient Familial CAF de 538€ à 716€." value=0 name="caf" id="caf" value="<?php if(isset($caf)){echo $caf;} ?>" required>
              <label for="caf">CAF</label>
            </div>
          </div>
        </div>
        <div align="center"><?php if(isset($erreur)){ echo $erreur; } ?></div>
        <input type="submit" value="Inscription" name="formInscription" class="submit-btn registration-btn">
        <a href="login.php" class="account">Déjà inscrit.e?</a>
      </form>
    </div>
  </div>
</body>
</html>
