<?php
	session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$idanneescolaire = $_REQUEST["idanneescolaire"];
	$idpaiementtype = $_REQUEST["idpaiementtype"];
	$paiementtypelibelle = getlibelletypepaiement($idpaiementtype,$pdo);
	$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>EcolePlus|</title>
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
	<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet"/>
	<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>
	<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet"/>
	<link href="../vendors/starrr/dist/starrr.css" rel="stylesheet"/>
	<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
	<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
	<link href="../build/css/select.css" rel="stylesheet"/>
	<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
	<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
</head>
    <body style="font-size:11px;background-color:#fff;">
		<div style="padding:10px;">
			<div>
				<span>
					<b>INSTITUT PRIVE LA&Icirc;C AUGUSTE LE GRAND</b><br/>
					<div style="float:left;"><img src="images/logo_.jpg" width="90px" height="90px"></div>
					<br/>
					<div style="float:left;"> 
						<b>« Osons l'excellence »</b><br/>
						B.P : Lom&eacute; - TOGO <br/>
						TEL : 97 96 57 34  / 79 32 97 37
					</div>
				</span>
				<h1 style="color:#000;font-weight:bold;font-size:16px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms;text-align:center;">
					[ ETAT DE RECOUVREMENT ]
				</h1>
			</div><br/>
			<table width="100%" align="center">
				<tr>
					<td width="50%" style="text-align:center;color:#000;font-family:comic sans ms;">
						Type de frais : <?php echo $paiementtypelibelle;?>
					</td>
					<td width="50%" style="text-align:center;color:#000;font-family:comic sans ms;">
						Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?>
					</td>
				</tr>
			</table>
			<hr style="border:1px dotted #000;"/>
			<?php 
				EtatRecouvrementImprime($idpaiementtype,$paiementtypelibelle,$libelleanneescolaire,$idanneescolaire,$pdo);
			?>
			<hr style="border:1px dotted #000;"/>
			<span style="font-size:10px;font-weight:bold;color:#000;"><b><i>Lom&eacute; le : </i></b> <?php echo date('d/m/Y');?></span>
		</div>
    </body>
	<script>window.print();</script>
</html>