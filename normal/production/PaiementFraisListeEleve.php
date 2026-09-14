<?php
	include("../modele/connexion.php");
    include("../modele/paiementfrais.php");
	
	$idanneescolaire=$_REQUEST['val_sel'];
	getAllEleve($idanneescolaire,$pdo);