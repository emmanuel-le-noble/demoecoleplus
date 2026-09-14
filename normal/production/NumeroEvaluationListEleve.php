<?php
	include("../modele/connexion.php");
    include("../modele/numeroevaluation.php");
	
	$idsalle = $_REQUEST['val_sel'];
	ListEleveSalle($idsalle,$pdo);
?>