<?php
	include("../modele/connexion.php");
    include("../modele/notification.php");
	
	$notification = $_REQUEST['val_sel'];
	getAllPosition($notification,$pdo);
?>