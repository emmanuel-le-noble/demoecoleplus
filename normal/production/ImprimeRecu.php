ï»¿<?php 
	session_name("ecoleplus");
	session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/paiementfrais.php");

	$idpaiementfrais = trim($_REQUEST["idpaiementfrais"]);
	
	$req = 'SELECT DISTINCT
				eleve.nom_eleve AS NomEleve,
				eleve.prenom_eleve AS PrenomEleve,
				eleve.matricule AS Matricule,
				paiementfrais.montant AS Montant,
				paiementfrais.date AS DatePaiement,
				paiementfrais.nompayeur AS NomPayeur,
				classe.idclasse AS idclasse,
				classe.codeclasse AS CodeClasse,
				anneescolaire.id AS IdAnneeScolaire,
				anneescolaire.libelle AS AnneeScolaire,
				paiementtype.libelle AS Libelle,
				paiementtype.id AS idPaiementType,
				paiementfrais.ideleveanneescolaire AS Ideleveanneescolaire,
				paiementtypeclasse.montant AS MontantTypePaiement,
				paiementfrais.idpaiementtypeclasse AS Idpaiementtypeclasse,
				utilisateur.nom_user AS NomUser,
				utilisateur.prenom_user AS PrenomUser,
				eleveanneescolaire.boursier AS Boursier
				
			FROM paiementfrais
			INNER JOIN utilisateur ON paiementfrais.iduserajout = utilisateur.id
			INNER JOIN eleveanneescolaire ON paiementfrais.ideleveanneescolaire = eleveanneescolaire.id
			INNER JOIN eleve ON eleve.id_eleve = eleveanneescolaire.ideleve
			INNER JOIN classe ON eleveanneescolaire.idclasse = classe.idclasse
			INNER JOIN anneescolaire ON eleveanneescolaire.idanneescolaire = anneescolaire.id
			INNER JOIN paiementtypeclasse ON paiementfrais.idpaiementtypeclasse = paiementtypeclasse.id
			INNER JOIN paiementtype ON paiementtypeclasse.idpaiementtype = paiementtype.id
			WHERE paiementfrais.id = :idpaiementfrais';
	
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpaiementfrais', $idpaiementfrais, PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	$NomEleve = "";
	$NomPayeur = "";
	$Montant = "";
	$DatePaiement = "";
	$CodeClasse = "";
	$AnneeScolaire = "";
	$Libelle = "";
	$Directeur = "";
	$SommeRestant = "";
	if($donnees = $stmt->fetch())
	{
		$idclasse = $donnees['idclasse'];
		$Boursier = (float)$donnees['Boursier'];
		$NomEleve = $donnees['NomEleve'];
		$PrenomEleve = $donnees['PrenomEleve'];
		$Matricule = $donnees['Matricule'];
		$NomPayeur = $donnees['NomPayeur'];
		$Montant = $donnees['Montant'];
		$DatePaiement = $donnees['DatePaiement'];
		$tab = explode("-",$DatePaiement);
		$DatePaiement = $tab[2]."/".$tab[1]."/".$tab[0];
		$CodeClasse = $donnees['CodeClasse'];
		$AnneeScolaire = $donnees['AnneeScolaire'];
		$Libelle = $donnees['Libelle'];
		$Idanneescolaire = $donnees['IdAnneeScolaire'];
		$MontantTypePaiement = $donnees['MontantTypePaiement']-$Boursier;
		$Ideleveanneescolaire = $donnees['Ideleveanneescolaire'];
		$Idpaiementtypeclasse = $donnees['Idpaiementtypeclasse'];
        $idPaiementType = $donnees['idPaiementType'];
		$NomUtilisateur = $donnees['NomUser'].' '.$donnees['PrenomUser'];
		$TotalPayer = getMontantEleveAnneeScolaire($Idpaiementtypeclasse,$Ideleveanneescolaire,$pdo);
		$SommeRestant = $MontantTypePaiement-$TotalPayer;
		$StatutAnneescolaire = getstatutAnneeScolairee($Idanneescolaire,$pdo);
		$Directeur = getDirecteur($Idanneescolaire,$StatutAnneescolaire,$pdo);
	}
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
					<h2><u>RE&Ccedil;U DE PAIEMENT</u></h2>     
				</td>
				<td valign="top" align="center" width="30%">
					<div style="border-radius:5px;border:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<center><b>REPUBLIQUE TOGOLAISE</b></center>
							<center><b>Travail - Liberte - Patrie</b></center>
							<b>Ann&eacute;e scolaire : </b><?php echo $AnneeScolaire;?><br/>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="left">
					<div style="border-radius:5px;border-bottom:1px solid #000;border-top:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<b>N° re&ccedil;u :</b> <?php echo genererNumeroRecu($_SESSION['libelleanneescolaire'], $_SESSION['idanneescolaire'], $pdo);?> &nbsp;&nbsp;<br/>
							<b>Date de paiement :</b> <?php echo $DatePaiement;?>&nbsp;&nbsp;<br/>
							<b>Caissi&egrave;r(e) :</b> <?php echo $NomUtilisateur;?>&nbsp;&nbsp;<br/>
						</span>
					</div>
				</td>
				<td></td>	
				<td></td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Classe :</b> <?php echo $CodeClasse;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"></td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Nom &eacute;l&egrave;ve :</b> <?php echo $NomEleve;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"><b>Montant pay&eacute; :</b> <?php echo number_format($Montant,0,""," ");?>&nbsp;&nbsp;FCFA</td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Pr&eacute;nom(s) &eacute;l&egrave;ve :</b> <?php echo $PrenomEleve;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"><b>Montant restant :</b> <?php echo number_format($SommeRestant,0,""," ");?>&nbsp;&nbsp;FCFA	</td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Num&eacute;ro matricule : </b> <?php echo $Matricule;?></td>
				<td></td>	
				<td style="border-bottom:3px dotted #000;"><b>Fait &agrave; Lom&eacute; , le </b><?php echo date('d/m/Y');?></td>
			</tr>
			<tr>
				<td colspan="3">
				<?php 
					if($idPaiementType==1) {
					ListPaiementFraisParTranche($Ideleveanneescolaire,$Idanneescolaire,$idclasse,$Idpaiementtypeclasse,$Boursier,$pdo);
				}
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
					<h2><u>RE&Ccedil;U DE PAIEMENT</u></h2>     
				</td>
				<td valign="top" align="center" width="30%">
					<div style="border-radius:5px;border:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<center><b>REPUBLIQUE TOGOLAISE</b></center>
							<center><b>Travail - Liberte - Patrie</b></center>
							<b>Ann&eacute;e scolaire : </b><?php echo $AnneeScolaire;?><br/>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="left">
					<div style="border-radius:5px;border-bottom:1px solid #000;border-top:1px solid #000;padding:5px;background-color:#eee;">
						<span>
							<b>N° re&ccedil;u :</b> <?php echo genererNumeroRecu($_SESSION['libelleanneescolaire'], $_SESSION['idanneescolaire'], $pdo);?>&nbsp;&nbsp;<br/>
							<b>Date de paiement :</b> <?php echo $DatePaiement;?>&nbsp;&nbsp;<br/>
							<b>Caissi&egrave;r(e) :</b> <?php echo $NomUtilisateur;?>&nbsp;&nbsp;<br/>
						</span>
					</div>
				</td>
				<td></td>	
				<td></td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Classe :</b> <?php echo $CodeClasse;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"></td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Nom &eacute;l&egrave;ve :</b> <?php echo $NomEleve;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"><b>Montant pay&eacute; :</b> <?php echo number_format($Montant,0,""," ");?>&nbsp;&nbsp;FCFA</td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Pr&eacute;nom(s) &eacute;l&egrave;ve :</b> <?php echo $PrenomEleve;?></td>
				<td></td>
				<td style="border-bottom:3px dotted #000;"><b>Montant restant :</b> <?php echo number_format($SommeRestant,0,""," ");?>&nbsp;&nbsp;FCFA	</td>										
			</tr>
			<tr>
				<td style="border-bottom:3px dotted #000;"><b>Num&eacute;ro matricule : </b> <?php echo $Matricule;?></td>
				<td></td>	
				<td style="border-bottom:3px dotted #000;"><b>Fait &agrave; Lom&eacute; , le </b><?php echo date('d/m/Y');?></td>
			</tr>
			<tr>
				<td colspan="3">
				<?php 
					if($idPaiementType==1) {
					ListPaiementFraisParTranche($Ideleveanneescolaire,$Idanneescolaire,$idclasse,$Idpaiementtypeclasse,$Boursier,$pdo);
					}
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
		<br/><br/><br/><br/><br/><br/>
		<center>
			<span style="font-size:11px;font-weight:bold;text-align:center;"><i>::: NB : Tous les frais payés sont non remboursables. Nous vous remercions pour le paiement. Excellente journ&eacute;e :::</i></span>
		</center>
    </body>
	<script>window.print();</script>
</html>