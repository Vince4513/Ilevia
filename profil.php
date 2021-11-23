<?php
session_start();

try {
$dbh = new PDO('mysql:host=localhost;dbname=MRE', 'root', '');
}
catch (PDOException $e) {
print "Erreur !: " . $e->getMessage() . "<br/>";
die();
}

if(isset($_GET['id']) AND $_GET['id'] > 0){
    $getId = intval($_GET['id']);
    $reqUser = $dbh->prepare("SELECT * FROM member WHERE num_member = ?");
    $reqUser->execute(array($getId));
    $userInfo = $reqUser->fetch();
?>

<html>
    <head>
        <title>Monitoring Real Estate</title>
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
                        <h2>Profil de <?php echo $userInfo['user']; ?></h2>
                        <p>Bienvenue sur votre compte !</p>
                        <?php 
                        if(isset($_SESSION['id']) AND $userInfo['num_member'] == $_SESSION['id']){ ?>
                            <a class="profil-btn" href="research-housing.php">Accéder à la recherche de biens</a>
                        <?php } ?>
                    </div>
                    <div class="profilInfo-col">
                        <h2>Informations</h2>
                        <p>Pseudo : <?php echo $userInfo['user']; ?></p>
                        <p>Mail : <?php echo $userInfo['email']; ?></p>
                        <?php 
                        if(isset($_SESSION['id']) AND $userInfo['num_member'] == $_SESSION['id']){ ?> 
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