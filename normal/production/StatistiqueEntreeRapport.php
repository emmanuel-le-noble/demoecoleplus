<?php
	session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/statistiquefinance.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$idanneescolaire = $_REQUEST["idanneescolaire"];
	$idcompte = $_REQUEST["idcompte"];
	$datedebut = trim($_REQUEST["datedebut"]);
	$tab = explode("-",trim($datedebut));
	$datedebut = $tab[2]."/".$tab[1]."/".$tab[0];
	$datefin = trim($_REQUEST["datefin"]);
	$tab = explode("-",trim($datefin));
	$datefin = $tab[2]."/".$tab[1]."/".$tab[0];
	$libellecompte = getLibelleCompte($idcompte,$pdo);
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
	<style>
		/* Format impression A4 */
		@page {
			size: A4;
			margin: 5mm;
		}
		body {
			font-family: "Segoe UI", Arial, sans-serif;
			color: #000;
			background: #fff;
			position: relative;
		}
		.signature-date {
			margin-top: 30px;
			text-align: right;
			font-size: 13px;
		}
		.signatures {
			margin-top: 30px;
			display: flex;
			justify-content: space-between;
			font-size: 14px;
			font-weight: bold;
			margin-bottom:100px;
		}
		.sign-box {
			width: 45%;
			text-align: center;
		}
		.sign-line {
			margin-top: 30px;
			border-top: 0px solid #000;
			width: 100%;
		}
		.table-bordered {
			border: 1px solid black !important;
		}
		.table-bordered th,
		.table-bordered td {
			border: 1px solid black !important;
		}
	</style>
</head>
    <body style="font-size:11px;background-color:#fff;">
	    <div>
			<table width="100%" style="font-size:11px;">
				<tr>
					<td width="33%" align="center">
						<div class="left">
							<div class="bloc">
							  <div><b>MINISTERE DES ENSEIGNEMENTS PRIMAIRE, SECONDAIRE ET TECHNIQUE</b></div>
							  <div style="margin-top:2mm"><b>INSTITUT PRIVÉ LAÏC AUGUSTE LE GRAND</b></div>
							  <div>Lomé – Amadahomé, Prolongement de la rue Orabank Adidogomé</div>
							  <div>TEL: 96 45 43 11 / 96 45 43 28</div>
							</div>
						</div>
					</td>
					<td width="33%" valign="top" align="center">
						<div class="cell"><img src="images/logo_.jpg" width="70px" height="70px"></div>
						<div class="motto">« Osons l'excellence »</div>
					</td>
					<td width="33%" valign="top" align="center">
						<div class="right">
							<div class="rep">République Togolaise<br/><small>Travail – Liberté – Patrie</small></div>
						 </div>
					</td>
				</tr>
			</table>
			<center><h2> LISTE DES ENTR&Eacute;ES EFFECTU&Eacute;ES </h2></center>
			<table width="100%" align="center">
				<tr>
					<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
						Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?>
					</td>
					<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
						Compte : <?php echo $libellecompte;?>
					</td>
					<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
						Date d&eacute;but : <?php echo $datedebut;?>
					</td>
					<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
						Date fin : <?php echo $datefin;?>
					</td>
				</tr>
			</table>
			<hr style="border:1px dotted #000;"/>
			<?php ListeEntree(trim($_REQUEST["datedebut"]),trim($_REQUEST["datefin"]),$idanneescolaire,$idcompte,$pdo)?>  
			<hr style="border:1px dotted #000;"/>
			<span style="font-size:10px;font-weight:bold;color:#000;"><b><i>Lom&eacute; le : </i></b> <?php echo date('d/m/Y');?></span>
		</div>
		<script>window.print();</script>
	</body
</html>