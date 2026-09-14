<?php
	include("../modele/connexion.php");
    include("../modele/function.php");
	include("../modele/liste.php");
	
	$idsalle=$_REQUEST['val_sel'];
	?><hr/><?php
	ListEleve($idsalle,$pdo);
?>