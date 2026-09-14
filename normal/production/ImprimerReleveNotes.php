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
	
	$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
	$idposition = trim($_REQUEST["idposition"]);
	$idsalle = trim($_REQUEST["idsalle"]);
	$iddomaine = getDomaineSalle($idsalle,$pdo);
	$idclasse = getIdClasseForSalle($idsalle,$pdo);
	$idbulletin = trim($_REQUEST["idbulletin"]);
	$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
	$profTitulaire = getProfTitulaire($idanneescolaire,$statutanneescolaire,$idsalle,$pdo);
	$directeur = getDirecteur($idanneescolaire,$statutanneescolaire,$pdo);
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
<body style="background-color:#fff;">
	<?php
	if($idbulletin==0)
	{
		$req=(' SELECT  distinct
						eleve.photo as Photo,
						eleve.id_eleve as IdEleve,
						eleve.nom_eleve as NomEleve,
						eleve.prenom_eleve as PrenomEleve,
						eleve.sexe_eleve,
						eleve.etat_eleve,
						eleve.matricule as Matricule,
						elevesalle.id as IdeleveSalle,
						bulletin.id as IdBulletin,
						salle.codesalle as CodeSalle,
						anneescolaire.libelle as LibelleAnneeScolaire,
						bulletin.moyenne_gene as MoyenneGene,
						bulletin.rang as Rang,
						bulletin.moyen_ann as Moyen_ann,
						bulletin.rang_ann as Rang_ann,
						position.libposition as LibPosition,
						bulletin.observation as observationchef,
						elevestatutclasse.libelle as LibelleEleveStatutClasse
						 
				FROM eleve,eleveanneescolaire,elevesalle,bulletin,position,anneescolaire,salle,elevestatutclasse
				WHERE
				eleve.id_eleve=eleveanneescolaire.ideleve
				AND
				eleveanneescolaire.idanneescolaire=anneescolaire.id
				AND
				elevestatutclasse.id=eleveanneescolaire.etat
				AND
				eleveanneescolaire.id=elevesalle.ideleve
				AND
				elevesalle.idsalle=salle.id
				AND
				elevesalle.statut=1
				AND
				anneescolaire.id=:idanneescolaire
				AND
				bulletin.idanneescolaire=anneescolaire.id
				AND
				bulletin.idsalle=salle.id
				AND
				bulletin.ideleve=elevesalle.id
				AND
				bulletin.idposition=position.idposition
				AND
				position.idposition=:idposition
				AND
				salle.id=:idsalle

				ORDER BY bulletin.moyenne_gene DESC');	
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT  distinct
						eleve.photo as Photo,
						eleve.id_eleve as IdEleve,
						eleve.nom_eleve as NomEleve,
						eleve.prenom_eleve as PrenomEleve,
						eleve.sexe_eleve,
						eleve.etat_eleve,
						eleve.matricule as Matricule,
						elevesalle.id as IdeleveSalle,
						bulletin.id as IdBulletin,
						salle.codesalle as CodeSalle,
						anneescolaire.libelle as LibelleAnneeScolaire,
						bulletin.moyenne_gene as MoyenneGene,
						bulletin.rang as Rang,
						bulletin.moyen_ann as Moyen_ann,
						bulletin.rang_ann as Rang_ann,
						position.libposition as LibPosition,
						bulletin.observation as observationchef,
						elevestatutclasse.libelle as LibelleEleveStatutClasse
						 
				FROM eleve,eleveanneescolaire,elevesalle,bulletin,position,anneescolaire,salle,elevestatutclasse
				WHERE
				eleve.id_eleve=eleveanneescolaire.ideleve
				AND
				eleveanneescolaire.idanneescolaire=anneescolaire.id
				AND
				elevestatutclasse.id=eleveanneescolaire.etat
				AND
				eleveanneescolaire.id=elevesalle.ideleve
				AND
				elevesalle.idsalle=salle.id
				AND
				elevesalle.statut=1
				AND
				anneescolaire.id=:idanneescolaire
				AND
				bulletin.idanneescolaire=anneescolaire.id
				AND
				bulletin.idsalle=salle.id
				AND
				bulletin.ideleve=elevesalle.id
				AND
				bulletin.idposition=position.idposition
				AND
				position.idposition=:idposition
				AND
				salle.id=:idsalle
				AND
				bulletin.id=:idbulletin

				ORDER BY bulletin.moyenne_gene DESC');	
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idbulletin', $idbulletin, PDO::PARAM_INT);
	}
	$stmt->execute();	
	$ligne=0;
	while($donnees = $stmt->fetch())
	{
		$ligne++;

		$IdEleve = $donnees['IdEleve'];
		$Photo = $donnees['Photo'];
		$nom = $donnees['NomEleve'].' '.$donnees['PrenomEleve'];
		$ideleve = $donnees['IdeleveSalle'];
		$idelevesalle = $donnees['IdeleveSalle'];
		$idbulletin = $donnees['IdBulletin'];
		$codesalle = $donnees['CodeSalle'];
		$LibelleAnneeScolaire = $donnees['LibelleAnneeScolaire'];
		$MoyenneGene = $donnees['MoyenneGene'];
		$Rang = $donnees['Rang'];
		$Moyen_ann = $donnees['Moyen_ann'];
		$Rang_ann = $donnees['Rang_ann'];
		$libposition = $donnees['LibPosition'];
		$sexe_eleve = $donnees['sexe_eleve'];
		$etat_eleve = $donnees['LibelleEleveStatutClasse'];
		$observationchef = $donnees['observationchef'];
		$matricule = $donnees['Matricule'];
		
		$effectif = getNombreBulletinTrimestre($idsalle,$idanneescolaire,$idposition,$pdo);
		//codebarre($idbulletin);
		/*$avertissement="";
		if(intval($MoyenneGene)<="9"  && ($idposition==1 OR $idposition==2))
		{
			$avertissement="Averti(e) pour le travail.";
		}
		$tableauHonneur="";
		if(intval($MoyenneGene)>=12 && intval($MoyenneGene)<=13)
		{
			$tableauHonneur="Oui";
		}
		$Felicitation="";
		if(intval($MoyenneGene)>=14 && intval($MoyenneGene)<=15)
		{
			$Felicitation="Oui";
			$tableauHonneur="Oui";
		}
		$Encouragement="";
		if(intval($MoyenneGene)>=16)
		{
			$Encouragement="Oui";
			$tableauHonneur="Oui";
			$Felicitation="Oui";
		}*/
	  ?>
	<div>
		<table width="100%" style="font-size:11px;">
			<tr>
				<td width="33%" align="center">
					<div class="left">
						<div class="bloc">
						  <div><b>MINISTERE DE L'EDUCATION NATIONALE</b></div>
						  <div style="margin-top:2mm"><b>INSTITUT PRIVÉ ECOLEPLUS</b></div>
						  <div>Lomé – TOGO</div>
						  <div>TEL: 00 00 00 00 / 00 00 00 00</div>
						</div>
					</div>
				</td>
				<td width="33%" valign="top" align="center">
					<div class="cell"><img src="images/logo_.jpg" width="70px" height="70px"></div>
					<div class="motto">« Travail - Discipline - Réussite »</div>
				</td>
				<td width="33%" valign="top" align="center">
					<div class="right">
						<div class="rep">République Togolaise<br/><small>Travail – Liberté – Patrie</small></div>
					 </div>
				</td>
			</tr>
		</table>
		<center><h1>RELEVE DE NOTES</h1></center>
		<table width="100%" align="center" style="border-collapse: collapse;">
			<tr>
				<td style="padding:10px;" width="65%" colspan="2">
					EVALUATION : <b><?php echo $libposition;?></b>
				</td>
				<td width="35%"></td>
			</tr>
			<tr>
				<td style="padding: 10px;" width="30%">
					CLASSE : <b><?php echo $codesalle;?></b>
				</td>
				<td width="35%">
					
				</td>
				<td style="padding: 10px;" width="35%">
					ANN&Eacute;E SCOLAIRE : <b><?php echo $LibelleAnneeScolaire;?></b>
				</td>
			</tr>
			<tr>
				<td style="padding: 10px;" width="30%">
					NOM : <b><?php echo $donnees['NomEleve'];?></b>
				</td>
				<td style="padding: 10px;" width="35%">
					PRENOM(s) : <b><?php echo $donnees['PrenomEleve'];?></b>
				</td>
				<td style="padding: 10px;" width="35%">
					SEXE : <b><?php echo $sexe_eleve;?></b>
				</td>
			</tr>
		</table>
		<hr style="border:1px dotted #000;"/>
		<?php 
			if($iddomaine==3)
			{
				ImprimeReleveNotesCollege($idsalle,$idanneescolaire,$idposition,$idelevesalle,$Rang,$effectif,$pdo);
			}
			elseif($iddomaine==6)
			{
				ImprimeReleveNotesLycee($idsalle,$idanneescolaire,$idposition,$idelevesalle,$Rang,$effectif,$pdo);
			}
			elseif($iddomaine==4)
			{
				ImprimeReleveNotesPrimaire($idsalle,$idanneescolaire,$idposition,$idelevesalle,$Rang,$effectif,$pdo);
			}
		?>
		<hr style="border:1px dotted #000;"/>
		<table width="100%" align="center">
			<tr>
				<td width="50%" align="left"><span style="font-size:10px;font-weight:bold;color:#000;"><b><i>Lom&eacute; le : </i></b> <?php echo date('d/m/Y');?></span></td>
				<td width="50%" align="center">SIGNATURE ET CACHET DU DIRECTEUR</td>
			</tr>
		</table>
		<!-- Signatures -->
		<div class="signatures">
			<div class="sign-box">
				<div class="sign-line"></div>
				<span></span>
			</div>
			<div class="sign-box">
				<div class="sign-line"></div>
				<span></span>
			</div>
		</div>
	</div>
	<!-- Mention de sécurité -->
	<?php
		// Génération d'un code de vérification unique
		$code_verif = strtoupper(substr(md5($idbulletin . $idanneescolaire . $idsalle . $matricule), 0, 8));
	?>
	<div style="position: fixed; bottom: 5mm; left: 0; right: 0; text-align: center; font-size: 9px; color: #444;">
		<i>
			Ce relevé de notes est délivré par l’<b>INSTITUT PRIVÉ ECOLEPLUS</b>.
			Toute altération, falsification ou reproduction non autorisée est strictement interdite 
			et passible de poursuites conformément à la loi.
		</i>
	</div><?php
	}
	?>
</body>
<script>window.print();</script>
</html>