<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
    include("../modele/function.php");
	
	$TypeOperation=$_REQUEST['val_sel'];
	getSousTypeOperation($TypeOperation,$pdo)
?>
