<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");
	
	if(isset($_SESSION['iduser']))
	{
		$iduser = $_SESSION['iduser']; 
		$nomuser = $_SESSION['nomuser'];
		$prenomuser = $_SESSION['prenom'];
		$photo = $_SESSION['photo'];

		session_unset();  
		session_destroy(); 
		
		header ('location:index.php');  
	}
?>