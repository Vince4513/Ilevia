<?php session_start();?>
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
	<section class="sub-header">
		<nav>
			<a href="index.html"><img src="images/logo.png"></a>
			<div class="nav-links" id="navLinks">
                <ul>
					<li><a href="index.html">MENU</a></li>
                    <li><a href="achatTickets.php">ACHAT TICKET</a></li>
					<li><a href="validation.php">VALIDATION TICKET</a></li>
					<li><a href="statistiques.php">STATISTIQUES</a></li>
                    <li><a href="login.php"><i class="fa fa-user"></i></a></li>
			    </ul>
			</div>
		</nav>
    <h1>Buy a Ticket</h1>
	</section>

<!-- Course -->

	<section class="course">
		<h1>How to buy a ticket...</h1>
		<p>Select the type of ticket you want and the quantity needed right after.</p>
		
		<div class="row">
			<table>
				<form action="achatTicket.php" method="POST">
					<tr><td><label for="typeticket">Type de ticket : </label></td><td><input type="text" name="typeticket" /></td></tr>	
					<tr><td><label for="nbticket">Nombre de ticket :</label></td><td><input type="number" name="nbticket" /></td></tr>
					  <tr><td colspan="2"><input class="center" type="submit" value="Acheter" /></td></tr>			
				</form>
			</table>
		</div>
		
	  </section>

<!-- Footer -->

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
