<html>
<body>
<?php

$nbTicket=$_POST["nbticket"];
$typeTicket=$_POST["typeticket"];

include("connexion.php");

//Connexion Ã  la base postgresql
$con=connect() ;
if (!$con){
  echo "Probleme connexion a la base";
  exit;
}

//requete (Ã  tester avant...
for (int i=0; i < $nbTicket; i++){

	$query="insert into ticket (libelle) values (".$typeTicket.")";

	 //echo $query; //pour debuguer, afficher la requete

	if(pg_query($con,$query)){
	  echo "Data saved";
	}
	else{
	  echo "Error insering data";
	}
}

header("Location: index.html");
?>

</body>
</html>
