<?php
//Suivre l'utilisateur
session_start();

?>

<html>
<head>
	<title>ILEVIA</title>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8"/>
	<script src="https://kit.fontawesome.com/e62499e6fc.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
	
</head>
<body>
	<section class="header">
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
      
      <div class="text-box">
        <h1>Projet BD Ilevia</h1>
        <p>Vincent Réau - Arnaud Rougier</p>
        <a href="statistiques.php" class="hero-btn">Voir les statistiques</a>
      </div>
	</section>
		
<!------ Actions (Course) ------>

<section class="course">
  <h1>Actions réalisables</h1>
  <p>Vous avez accès à trois différentes sections sur notre site qui vous permettront de réserver un ticket, valider un ticket ou encore voir les statistiques de la plateforme.</p>
  
  <div class="row">
    <div class="course-col">
      <h3>Acheter un ticket</h3>
      <p>Pour acheter un ticket, vous n'avez pas forcement besoinde vous connecter à votre compte. Pour ce faire, cliquez sur le bouton "ACHAT TICKET" dans la barre de navigation.</p>
    </div>
    <div class="course-col">
      <h3>Valider un ticket</h3>
      <p>Pour valider un titre de transport, vous devez être connecté à votre compte et être en possesion d'un titre de transport.</p>
    </div>
    <div class="course-col">
      <h3>Statistiques</h3>
      <p>Les statistiques vous informent en temps réel sur toutes les informations de notre entreprise. N'hésitez pas à allez y faire un tour... :)</p>
    </div>
  </div>
  
</section>

<!-------- Testimonials -------->

<section class="testimonials">
  <h1>Point de vue des clients</h1>
  <p>Quelques avis de nos différents clients à propos de nos services.</p>
  <div class="row">
    <div class="testimonial-col">
      <img src="images/user1.jpg">
      <div>
        <p>Belle plateforme nécessitant tout de même de bien connaître le site avant d'être à l'aise.</p>
        <h3>Christine Berkley</h3>
        <i class="fa fa-star"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-star-o"></i>
      </div>
    </div>
    <div class="testimonial-col">
      <img src="images/user2.jpg">
      <div>
        <p>Je connaissais le principe et je trouve ce site remarquable.</br>Continuez comme ça !</p>
        <h3>David Byer</h3>
        <i class="fa fa-star"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-star"></i>
        <i class="fa fa-star-half"></i>
      </div>
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

</body>
</html>
