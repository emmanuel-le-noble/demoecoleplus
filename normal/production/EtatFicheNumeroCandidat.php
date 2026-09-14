<?php
    session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/numeroevaluation.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$id = trim($_REQUEST["id"]);	
	$tab = explode("*",trim($id));
	$idanneescolaire = $tab[0];
	$idposition = $tab[1];
	$idsalle = $tab[2];
	$LibelleAnneeScolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
	$CodeSalle = getCodeSalle($idsalle,$pdo);
	$LibellePosition = getLibellePosition($idposition,$pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta charset="utf-8"/>
	<meta http-equiv=s"X-UA-Compatible" content="IE=edge"/>
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
			margin-top: 60px;
			display: flex;
			justify-content: space-between;
			font-size: 14px;
			font-weight: bold;
		}
		.sign-box {
			width: 45%;
			text-align: center;
		}
		.sign-line {
			margin-top: 60px;
			border-top: 1px solid #000;
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
<body style="background-color:#fff;">
	<div>
		<table width="100%" style="font-size:11px;">
			<tr>
				<td width="33%" align="center">
					<div class="left">
						<div class="bloc">
						  <div><b>MINISTERE DE L'EDUCATION NATIONALE</b></div>
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
		<center><h2>FICHE DE N° DE TABLE DES CANDIDATS</h2></center>
		<table width="100%" align="center">
			<tr>
				<td width="30%">
					CLASSE : <b><?php echo $CodeSalle;?></b>
				</td>
				<td width="35%">
					EVALUATION : <b><?php echo $LibellePosition;?></b>
				</td>
				<td width="35%">
					ANN&Eacute;E SCOLAIRE : <b><?php echo $LibelleAnneeScolaire;?></b>
				</td>
			</tr>
		</table>
		<hr style="border:1px dotted #000;"/>
		<?php ImprimeFicheNumeroCandidat($idsalle,$idanneescolaire,$idposition,$pdo);?>
		<hr style="border:1px dotted #000;"/>
		<span style="font-size:10px;font-weight:bold;color:#000;"><b><i>Lom&eacute; le : </i></b> <?php echo date('d/m/Y');?></span>
		<!-- Signatures -->
		<div class="signatures">
			<div class="sign-box">
				<div class="sign-line"></div>
				<span></span>
			</div>
			<div class="sign-box">
				<div class="sign-line"></div>
				<span>Directeur des études</span>
			</div>
		</div>
	</div>
</body>
<script>window.print();</script>
</html>