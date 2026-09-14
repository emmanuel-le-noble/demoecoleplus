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
	$tab = explode("/",trim($datedebut));
	$datedebut = $tab[2]."-".$tab[1]."-".$tab[0];
	
	$datefin = trim($_REQUEST["datefin"]);
	$tab = explode("/",trim($datefin));
	$datefin = $tab[2]."-".$tab[1]."-".$tab[0];

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
</head>
    <body style="font-size:11px;background-color:#fff;">
	<div style="padding:10px;">
		<div>
			<span>
				<b>COLLEGE PRIVE LA&Icirc;C BORDJO</b><br/>
				<div style="float:left;"><img src="images/bordjo.jpeg" width="90px" height="50px"></div>
				<div style="float:left;"> 
					<b>CEG - BORDJO</b><br/>
					B.P 80883 Lom&eacute; - TOGO <br/>
					TEL : 70 47 58 58 / 96 98 68 68
				</div><br/>
			</span>
			<h1 style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms;text-align:center;">
				[ LISTE DES SORTIES EFFECTU&Eacute;ES ]
			</h1>
		</div><br/>
		<table width="100%" align="center">
			<tr>
				<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
					Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?>
				</td>
				<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
					Compte : <?php echo $libellecompte;?>
				</td>
				<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
					Date d&eacute;but : <?php echo $_REQUEST["datedebut"];?>
				</td>
				<td width="25%" style="text-align:center;color:#000;font-family:comic sans ms;">
					Date fin : <?php echo $_REQUEST["datefin"];?>
				</td>
			</tr>
		</table>
		<hr style="border:1px dotted #000;"/>
		<?php ListeSortie($datedebut,$datefin,$idanneescolaire,$idcompte,$pdo);?>
		<hr style="border:1px dotted #000;"/>
		<table width="100%" align="center">
			<tr>
				<td width="50%" style="text-align:center;color:#000;font-family:comic sans ms;">
					<span style="font-size:10px;font-weight:bold;color:#000;"><b><i>Lom&eacute; le : </i></b> <?php echo date('d/m/Y');?></span>
				</td>
				<td width="50%" style="text-align:center;color:#000;font-family:comic sans ms;">
					<span style="font-size:10px;font-weight:bold;color:#000;"><b><i>Imprim&eacute; par </i></b> <?php echo $_SESSION['nomuser'].' '.$_SESSION['prenom'];?></span>
				</td>
			</tr>
		</table>
	</div>
    </body>
	<script>window.print();</script>
</html>