<?php
function connect()
{
$con=pg_connect("host=serveur-etu.polytech-lille.fr user=vreau port=5432 password=postgres dbname=reaurougierilevia") ;
return $con;
}
?>
