<?php 
	session_name("ecoleplus");
	session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/stock.php");

	$id = trim($_REQUEST["id"]);	
	$tab = explode('*',$id);
	$idArticleSortie = $tab[0];
	$ideleve = $tab[1];
	$NomPrenomEleve = getNomPrenomEleve($ideleve, $pdo);
	$NumSortie = $tab[2];
	$DateSortie = $tab[3];
	$Montant = $tab[4];
	$NomUser = $tab[5];
	$tab = explode('-',$DateSortie);
	$DateSortie_ = $tab[2].'/'.$tab[1].'/'.$tab[0];
	$iduser = $_SESSION['iduser'];
	$StatutAnneescolaire = getstatutAnneeScolairee($_SESSION['idanneescolaire'],$pdo);
	$Directeur = getDirecteur($_SESSION['idanneescolaire'],$StatutAnneescolaire,$pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="designneww.css" rel="stylesheet"/>
</head>
	<body>
		<table width="100%" height="400px" align="center">
			<tr>
				<td valign="top" width="30%">
					<span>
						<b>INSTITUT PRIVE ECOLEPLUS</b><br/>
						<div style="float:left;"><img src="images/logo_.jpg" width="90px" height="50px"></div>
						<div style="float:left;"> 
							<b>« Travail - Discipline - Réussite »</b><br/>
							B.P : Lom&eacute; - TOGO <br/>
							TEL : 00 00 00 00  / 00 00 00 00
						</div>
					</span>
				</td>
				<td valign="top" width="30%">
					<h2><u>RE&Ccedil;U DE VENTE</u></h2>     
				</td>
				<td valign="top" align="center" width="30%">
					<div style="border-radius:5px;border:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<center><b>REPUBLIQUE TOGOLAISE</b></center>
							<center><b>Travail - Liberte - Patrie</b></center>
							<b>Ann&eacute;e scolaire : </b><?php echo $_SESSION['libelleanneescolaire'];?><br/>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="left">
					<div style="border-radius:5px;border-bottom:1px solid #000;border-top:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<b>N° re&ccedil;u :</b> <?php echo $NumSortie;?>&nbsp;&nbsp;<br/>
							<b>Date de paiement :</b> <?php echo $DateSortie_;?>&nbsp;&nbsp;<br/>
							<b>Caissi&egrave;r(e) :</b> <?php echo $NomUser;?>&nbsp;&nbsp;<br/>
						</span>
					</div>
				</td>
				<td></td>	
				<td></td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Identit&eacute; du client :</b> <?php echo $NomPrenomEleve;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"><b>Montant pay&eacute; :</b> <?php echo number_format($Montant,0,""," ");?>&nbsp;&nbsp;FCFA</td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b></b></td>
				<td></td>	
				<td style="border-bottom:3px dotted #000;"><b>Fait &agrave; Lom&eacute; , le </b><?php echo date('d/m/Y');?></td>
			</tr>
			<tr>
				<td colspan="3">
				<?php 
					ListByVenteForPrint($idArticleSortie,$pdo);
				?>
				</td>
			</tr>
			<tr>
				<td><b>Signature & Cachet Caissi&egrave;r(e)</b></td>
				<td></td>
				<td><b>Nom & Signature Payeur</b></td>
			</tr>
		</table>
		<div style="float:left;"></div>
		<br/><br/><br/><br/>
		<center>
			<span style="font-size:11px;font-weight:bold;text-align:center;"><i>::: NB : Tous les frais payés sont non remboursables. Nous vous remercions pour le paiement. Excellente journ&eacute;e :::</i></span>
		</center>
		<hr style="border:2px dotted #000;"/>
		<br/>
		<table width="100%" height="400px" align="center">
			<tr>
				<td valign="top" width="30%">
					<span>
						<b>INSTITUT PRIVE ECOLEPLUS</b><br/>
						<div style="float:left;"><img src="images/logo_.jpg" width="90px" height="50px"></div>
						<div style="float:left;"> 
							<b>« Travail - Discipline - Réussite »</b><br/>
							B.P : Lom&eacute; - TOGO <br/>
							TEL : 00 00 00 00  / 00 00 00 00
						</div>
					</span>
				</td>
				<td valign="top" width="30%">
					<h2><u>RE&Ccedil;U DE VENTE</u></h2>     
				</td>
				<td valign="top" align="center" width="30%">
					<div style="border-radius:5px;border:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<center><b>REPUBLIQUE TOGOLAISE</b></center>
							<center><b>Travail - Liberte - Patrie</b></center>
							<b>Ann&eacute;e scolaire : </b><?php echo $_SESSION['libelleanneescolaire'];?><br/>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="left">
					<div style="border-radius:5px;border-bottom:1px solid #000;border-top:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<b>N° re&ccedil;u :</b> <?php echo $NumSortie;?>&nbsp;&nbsp;<br/>
							<b>Date de paiement :</b> <?php echo $DateSortie_;?>&nbsp;&nbsp;<br/>
							<b>Caissi&egrave;r(e) :</b> <?php echo $NomUser;?>&nbsp;&nbsp;<br/>
						</span>
					</div>
				</td>
				<td></td>	
				<td></td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Identit&eacute; du client :</b> <?php echo $NomPrenomEleve;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"><b>Montant pay&eacute; :</b> <?php echo number_format($Montant,0,""," ");?>&nbsp;&nbsp;FCFA</td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b></b></td>
				<td></td>	
				<td style="border-bottom:3px dotted #000;"><b>Fait &agrave; Lom&eacute; , le </b><?php echo date('d/m/Y');?></td>
			</tr>
			<tr>
				<td colspan="3">
				<?php 
					ListByVenteForPrint($idArticleSortie,$pdo);
				?>
				</td>
			</tr>
			<tr>
				<td><b>Signature & Cachet Caissi&egrave;r(e)</b></td>
				<td></td>
				<td><b>Nom & Signature Payeur</b></td>
			</tr>
		</table>
		<div style="float:left;"></div>
		<br/><br/><br/><br/>
		<center>
			<span style="font-size:11px;font-weight:bold;text-align:center;"><i>::: NB : Tous les frais payés sont non remboursables. Nous vous remercions pour le paiement. Excellente journ&eacute;e :::</i></span>
		</center>
    </body>
	<script>window.print();</script>
</html>