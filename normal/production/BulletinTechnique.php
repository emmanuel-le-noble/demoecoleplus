<?php 
	session_name("ecoleplus");
	session_start();
	
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");

	$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
	$idposition = trim($_REQUEST["idposition"]);
	$idsalle = trim($_REQUEST["idsalle"]);
	$idclasse = getIdClasseForSalle($idsalle,$pdo);
	$idbulletin = trim($_REQUEST["idbulletin"]);
	$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
	$profTitulaire = getProfTitulaire($idanneescolaire,$statutanneescolaire,$idsalle,$pdo);
	$directeur = getDirecteur($idanneescolaire,$statutanneescolaire,$pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="designneww.css" rel="stylesheet"/>
</head>
	<body>
	<?php
	//OPTION G2
	if($idclasse==14 OR $idclasse==15 OR $idclasse==17)
	{
		if($idbulletin==0)
		{
			$req=(' SELECT  distinct
							eleve.id_eleve as IdEleve,
							eleve.nom_eleve as NomEleve,
							eleve.sexe_eleve,
							eleve.etat_eleve,
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
							eleve.id_eleve as IdEleve,
							eleve.nom_eleve as NomEleve,
							eleve.sexe_eleve,
							eleve.etat_eleve,
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
			$nom = $donnees['NomEleve'];
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
			
			$effectif = getNombreBulletinTrimestre($idsalle,$idanneescolaire,$idposition,$pdo);
			codebarre($idbulletin);
			$avertissement="";
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
			}
			?>
			<div id="filigrane" style="padding-top:90px;margin-bottom:90px;">
				<table style="width:100%" align="center">
					<tr>
						<td valign="top">
							<span style="color:black; text-shadow: black 0.1em 0.1em 0.2em;font-size:11px;font-weight:bold;">
								<b>&nbsp;&nbsp;DIRECTION DIOCESAINE DE L'ENSEIGNEMENT CATHOLIQUE / KPALIM&Eacute;</b><br/><br/>
								<div style="float:left;"><img src="images/logo.jpg" width="90px" height="50px"></div>
								<div style="float:left;"> 
									<b>COLLEGE POLYVALENT SAINT - ESPRIT DE KPALIM&Eacute;</b><br/>
									BP : 30 LOME - KPALIM&Eacute; <br/>
									CEL : 90 76 44 89 
								</div>
							</span>
						</td>
						<td valign="top" width="100px" height="70px">
							<img src="images/user.png" width="100px" height="70px">
						</td>
						<td valign="top">
							<span>
								<b>BULLETIN DU <?php echo strtoupper($libposition);?></b><br/><br/>
								NOM & PRENOM : <span style="font-weight:bold;color:#000;"><?php echo utf8_decode(htmlentities($nom));?> </span><br/>
								SEXE : <b><?php echo $sexe_eleve;?></b> &nbsp;&nbsp;&nbsp;CLASSE : <b><?php echo utf8_decode(htmlentities($codesalle));?></b><br/>
								EFFECTIF : <b><?php echo $effectif;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;STATUT : <b><?php echo $etat_eleve;?></b>
							</span>
						</td>
						<td valign="top" align="center">
							<div style="border-radius:5px;border:1px solid #000;padding:5px;background-color:#eee;">
								<span>
									<center><b>REPUBLIQUE TOGOLAISE</b></center>
									<center><b>Travail - Libert&eacute; - Patrie</b></center>
									<b>Ann&eacute;e Scolaire : </b><?php echo $LibelleAnneeScolaire;?><br/>
									<b>Num&eacute;ro Bulletin : </b><?php echo $idbulletin;?>
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="4">
							<table width="100%" class="design" style="border-spacing:0;border-collapse:collapse;">
								<tr style="background-color:#eee;">
									<td align="center"><b>MATIERES</b></td>
									<td align="center">
										<table width="100%" style="border-spacing:0;border-collapse:collapse;">
											<tr><td colspan="3" align="center" style="border:1px solid #fff;"><b>Notes de classes</b></td></tr>
											<tr>
												<td align="center" style="border:1px solid #fff;">INT</td>
												<td align="center" style="border:1px solid #fff;">D.S</td>
											</tr>
										</table>
									</td>
									<td align="center"><b>Moy. Classe</b></td>
									<td align="center"><b>Notes Comp.</b></td>
									<td align="center"><b>Moy. Trimes.</b></td>
									<td align="center"><b>Coef.</b></td>
									<td align="center"><b>Moy. Ponderee</b></td>
									<td align="center"><b>Rang</b></td>	
									<td align="center"><b>Observations</b></td>
									<td align="center"><b>Nom des professeurs</b></td>
									<td align="center"><b>Signature</b></td>												
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">FR</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">ANG</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">HISTO-G&Eacute;O</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">ECM</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">COMPTA</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">EOE</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">EG</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">MATH. GENE</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">MATH.FIN</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,38,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">INFO</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">DROIT</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">EPS</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;"></td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"></td>
												<td align="center" style="border:1px solid #fff;width:50%;"></td>
											</tr>
										</table>
									</td>
									<td align="center"></td><td align="center"></td><td align="center"></td><td align="center"></td>
									<td align="center"></td><td align="center"></td><td align="center">&nbsp;</td><td align="left">&nbsp;</td><td align="center">&nbsp;<img src='photo_user/neutre.jpg' width='100px' height='20px'/></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;"></td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"></td>
												<td align="center" style="border:1px solid #fff;width:50%;"></td>
											</tr>
										</table>
									</td>
									<td align="center"></td><td align="center"></td><td align="center"></td><td align="center"></td>
									<td align="center"></td><td align="center"></td><td align="center">&nbsp;</td><td align="left">&nbsp;</td><td align="center">&nbsp;<img src='photo_user/neutre.jpg' width='100px' height='20px'/></td>
								</tr>
								<tr>
									<td align="center" colspan="5">TOTAL DEFINITIF</td>
									<td align="center"><b><?php echo round(getTotalCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo),2);?></b></td>
									<td align="center"><b><?php echo round(getTotalDefinitif($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo),2);?></b></td>
									<td align="center"></td>
									<td style="padding-left:5px;"> Moy. 1<sup>er</sup> Sem : <b>
									<?php 
										echo round(trim(getMoyenneSemestre_1($idanneescolaire,$idsalle,$ideleve,$pdo)),2);
									?></b></td>
									<td style="padding-left:5px;"> Rang : <b><?php echo getRangSemestre_1($idanneescolaire,$idsalle,$ideleve,$pdo);?></b></td>
									<td style="padding-left:5px;"> Moy. forte : <?php echo round(trim(getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo)),2);?></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Nbre d'absences (en heure) : <b><?php echo getNbreAbsenceForAnneescolaire($ideleve,$idposition,$idanneescolaire,$pdo);?></b></td>
									<td colspan="4" style="padding-left:5px;"></td>
									<td style="padding-left:5px;">
									<?php 
										if($idposition==5)
										{
											?> Moy. 2<sup>&egrave;</sup> Sem : <b><?php
											echo round(trim(getMoyenneSemestre_2($idanneescolaire,$idsalle,$ideleve,$pdo)),2);
										}
									?></b></td>
									<td style="padding-left:5px;">
									<?php 
										if($idposition==5)
										{
											?> Rang : <b><?php
											echo getRangSemestre_2($idanneescolaire,$idsalle,$ideleve,$pdo);
										}
										?></b></td>
									<td style="padding-left:5px;"> Moy. faible : <?php echo round(getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo),2);?></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Tableau d'honneur : <?php echo $tableauHonneur;?></td>
									<td colspan="4" style="padding-left:5px;"></td>
									<td style="padding-left:5px;"><b></b></td>
									<td style="padding-left:5px;"></td>
									<td style="padding-left:5px;"> Moy. gle sem : <?php echo round(getMoyenneGeneralSemestre($idanneescolaire,$idposition,$idsalle,$pdo),2);?></td>											
								</tr>								
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">F&eacute;licitation : <?php echo $Felicitation;?></td>
									<td colspan="4" style="padding-left:5px;"></td>
									<td style="padding-left:5px;"><?php 
									if($idposition==5)
									{
										?> Moy. Ann : <b><?php
										echo round(getMoyAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo),2);
										$Annu=round(getMoyAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo),2);
									}
									?></b></td>
									<td style="padding-left:5px;"><?php 
									if($idposition==5)
									{
										?> Rang : <b><?php
										echo round(getRangAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo),2);
									}
									?></b></td>
									<td style="padding-left:5px;"><?php 
									if($idposition==5)
									{
										?> Moy. gale Ann : <?php
										echo round(getMoyenneGeneralAnnuelle($idanneescolaire,5,$idsalle,$pdo),2);
									}
									?></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Encouragement : <?php echo $Encouragement;?></td>
									<td colspan="4" rowspan="7" align="center" style="padding-left:5px;"><img src="images/logo.jpg" width="200px" height="110px"></td>
									<td align="center" rowspan="8" valign="top" style="padding-top:5px;font-weight:bold;">
										<i>Nom/Signature Titulaire</i>
										<br/>
										<br/>
										<br/>
										<?php echo getProfTitulaireSignature($idanneescolaire,$statutanneescolaire,$idsalle,$pdo);?>
										<br/>
										<br/>
										<br/>
										<span style="font-size:12px;text-align:center;"><?php echo utf8_decode(htmlentities($profTitulaire));?></span>
									</td>
									<td align="center" colspan="2" rowspan="8" valign="top" style="padding-top:5px;font-weight:bold;">
										<i>Observations du Chef d'&eacute;tablissement</i>
										<br/>
										<br/><?php
										if($idposition==5)
										{
											?>
											<span style="font-size:12px;text-align:center;">
												Travail semestriel :
											<?php echo htmlentities($observationchef);?></span>
											<br/>
											<span style="font-size:12px;text-align:center;">
												Travail Annuel :
											<?php echo htmlentities(AppreciationAnnuelle(round($Annu)));?></span><?php
										}
										else
										{
											?>
											<span style="font-size:12px;text-align:center;">
											<?php 
												echo htmlentities($observationchef);?></span>
											<?php
										}
										?>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<p style="font-size:12px;text-align:right;margin:10px;"><?php echo utf8_decode(htmlentities($directeur));?></p>
									</td>									
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Avertissement : <?php echo $avertissement;?></td>										
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Blame</td>					
								</tr>
								<tr>
									<td colspan="4" rowspan="1" style="padding-left:5px;font-weight:bold;"><i>DECISION DU CONSEIL DE CLASSE</i></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;"></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="height:70px;"><img src="images/331385.png"></td>
								</tr>
								<tr>
									<td colspan="4" ></td>
									<td colspan="4"	 style="border-top:1px solid #fff;"><span style="font-size:10px;"><b><i>Kpalim&eacute; le : </i></b> <?php echo date('d/m/Y');?></span></td>							
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<hr/>
			</div><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
	}
	//OPTION G1
	elseif($idclasse==11 OR $idclasse==12 OR $idclasse==13)
	{
		if($idbulletin==0)
		{
			$req=(' SELECT  distinct
							eleve.id_eleve as IdEleve,
							eleve.nom_eleve as NomEleve,
							eleve.sexe_eleve,
							eleve.etat_eleve,
							elevesalle.id as IdeleveSalle,
							bulletin.id as IdBulletin,
							salle.codesalle as CodeSalle,
							anneescolaire.libelle as LibelleAnneeScolaire,
							bulletin.moyenne_gene as MoyenneGene,
							bulletin.rang as Rang,
							bulletin.moyen_ann as Moyen_ann,
							bulletin.rang_ann as Rang_ann,
							position.libposition as LibPosition,
							bulletin.observation as observationchef 
							 
					FROM eleve,eleveanneescolaire,elevesalle,bulletin,position,anneescolaire,salle
					WHERE
					eleve.id_eleve=eleveanneescolaire.ideleve
					AND
					eleveanneescolaire.idanneescolaire=anneescolaire.id
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
							eleve.id_eleve as IdEleve,
							eleve.nom_eleve as NomEleve,
							eleve.sexe_eleve,
							eleve.etat_eleve,
							elevesalle.id as IdeleveSalle,
							bulletin.id as IdBulletin,
							salle.codesalle as CodeSalle,
							anneescolaire.libelle as LibelleAnneeScolaire,
							bulletin.moyenne_gene as MoyenneGene,
							bulletin.rang as Rang,
							bulletin.moyen_ann as Moyen_ann,
							bulletin.rang_ann as Rang_ann,
							position.libposition as LibPosition,
							bulletin.observation as observationchef
							 
					FROM eleve,eleveanneescolaire,elevesalle,bulletin,position,anneescolaire,salle
					WHERE
					eleve.id_eleve=eleveanneescolaire.ideleve
					AND
					eleveanneescolaire.idanneescolaire=anneescolaire.id
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
			$nom = $donnees['NomEleve'];
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
			$etat_eleve = $donnees['etat_eleve'];
			$observationchef = $donnees['observationchef'];
			
			$effectif = getNombreBulletinTrimestre($idsalle,$idanneescolaire,$idposition,$pdo);
			codebarre($idbulletin);
			$avertissement="";
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
			}
			?>
			<div id="filigrane" style="padding-top:90px;margin-bottom:90px;">
				<table style="width:100%" align="center">
					<tr>
						<td valign="top">
							<span style="color:black; text-shadow: black 0.1em 0.1em 0.2em;font-size:11px;">
								<b>&nbsp;&nbsp;DIRECTION DIOCESAINE DE L'ENSEIGNEMENT CATHOLIQUE / KPALIM&Eacute;</b><br/><br/>
								<div style="float:left;"><img src="images/logo.jpg" width="90px" height="50px"></div>
								<div style="float:left;"> 
									<b>INSTITUT TECHNIQUE SAINT - ESPRIT DE KPALIME</b><br/>
									CEL : 90 76 44 89 <br/>
									LOME - KPALIM&Eacute;
								</div>
							</span>
						</td>
						<td valign="top" width="100px" height="70px">
							<img src="images/user.png" width="100px" height="70px">
						</td>
						<td valign="top">
							<span>
								<b>BULLETIN DU <?php echo strtoupper($libposition);?></b><br/><br/>
								NOM & PRENOM : <span style="font-weight:bold;color:#000;"><?php echo utf8_decode(htmlentities($nom));?> </span><br/>
								SEXE : <b><?php echo $sexe_eleve;?></b> &nbsp;&nbsp;&nbsp;CLASSE : <b><?php echo utf8_decode(htmlentities($codesalle));?></b><br/>
								EFFECTIF : <b><?php echo $effectif;?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;STATUT : <b><?php echo $etat_eleve;?></b>
							</span>
						</td>
						<td valign="top" align="center">
							<div style="border-radius:5px;border:1px solid #000;padding:5px;background-color:#eee;">
								<span>
									<center><b>REPUBLIQUE TOGOLAISE</b></center>
									<center><b>Travail - Libert&eacute; - Patrie</b></center>
									<b>Ann&eacute;e Scolaire : </b><?php echo $LibelleAnneeScolaire;?><br/>
									<b>Num&eacute;ro Bulletin : </b><?php echo $idbulletin;?>
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="4">
							<table width="100%" class="design" style="border-spacing:0;border-collapse:collapse;">
								<tr style="background-color:#eee;">
									<td align="center"><b>MATIERES</b></td>
									<td align="center">
										<table width="100%" style="border-spacing:0;border-collapse:collapse;">
											<tr><td colspan="3" align="center" style="border:1px solid #fff;"><b>Notes de classes</b></td></tr>
											<tr>
												<td align="center" style="border:1px solid #fff;">INT</td>
												<td align="center" style="border:1px solid #fff;">D.S</td>
											</tr>
										</table>
									</td>
									<td align="center"><b>Moy. Classe</b></td>
									<td align="center"><b>Notes Comp.</b></td>
									<td align="center"><b>Moy. Trimes.</b></td>
									<td align="center"><b>Coef.</b></td>
									<td align="center"><b>Moy. Ponderee</b></td>
									<td align="center"><b>Rang</b></td>	
									<td align="center"><b>Observations</b></td>
									<td align="center"><b>Nom des professeurs</b></td>
									<td align="center"><b>Signature</b></td>												
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">FR</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,23,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">ANG</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">HISTO-G&Eacute;O</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">ECM</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">ALL</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">OMA</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">EG</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">INFO</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,32,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">BURO</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">DROIT</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,33,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">EP</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,34,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">STENO</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">DACTYLO</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">MATH. G&Eacute;N&Eacute;.</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
								</tr>
								<tr>
									<td style="padding-left:5px;font-weight:bold;">EPS</td>
									<td>
										<table width="100%" style="border-spacing:0;border-collapse:collapse;border:1px solid #fff;">
											<tr>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
												<td align="center" style="border:1px solid #fff;width:50%;"><?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
											</tr>
										</table>
									</td>
									<td align="center"><?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
									<td align="center"><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center"><?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="left">&nbsp;<?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td><td align="center">&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
								</tr>
								<tr>
									<td align="center" colspan="5">TOTAL DEFINITIF</td>
									<td align="center"><b><?php echo round(getTotalCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo),2);?></b></td>
									<td align="center"><b><?php echo round(getTotalDefinitif($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo),2);?></b></td>
									<td align="center"></td>
									<td style="padding-left:5px;"> Moy. 1<sup>er</sup> Sem : <b>
									<?php 
										echo round(trim(getMoyenneSemestre_1($idanneescolaire,$idsalle,$ideleve,$pdo)),2);
									?></b></td>
									<td style="padding-left:5px;"> Rang : <b><?php echo getRangSemestre_1($idanneescolaire,$idsalle,$ideleve,$pdo);?></b></td>
									<td style="padding-left:5px;"> Moy. forte : <?php echo round(trim(getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo)),2);?></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Nbre d'absences (en heure) : <b><?php echo getNbreAbsenceForAnneescolaire($ideleve,$idposition,$idanneescolaire,$pdo);?></b></td>
									<td colspan="4" style="padding-left:5px;"></td>
									<td style="padding-left:5px;">
									<?php 
										if($idposition==5)
										{
											?> Moy. 2<sup>&egrave;</sup> Sem : <b><?php
											echo round(trim(getMoyenneSemestre_2($idanneescolaire,$idsalle,$ideleve,$pdo)),2);
										}
									?></b></td>
									<td style="padding-left:5px;">
									<?php 
										if($idposition==5)
										{
											?> Rang : <b><?php
											echo getRangSemestre_2($idanneescolaire,$idsalle,$ideleve,$pdo);
										}
										?></b></td>
									<td style="padding-left:5px;"> Moy. faible : <?php echo round(getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo),2);?></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Tableau d'honneur : <?php echo $tableauHonneur;?></td>
									<td colspan="4" style="padding-left:5px;"></td>
									<td style="padding-left:5px;"><b></b></td>
									<td style="padding-left:5px;"></td>
									<td style="padding-left:5px;"> Moy. gle sem : <?php echo round(getMoyenneGeneralSemestre($idanneescolaire,$idposition,$idsalle,$pdo),2);?></td>											
								</tr>								
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">F&eacute;licitation : <?php echo $Felicitation;?></td>
									<td colspan="4" style="padding-left:5px;"></td>
									<td style="padding-left:5px;"><?php 
									if($idposition==5)
									{
										?> Moy. Ann : <b><?php
										echo round(getMoyAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo),2);
										$Annu=round(getMoyAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo),2);
									}
									?></b></td>
									<td style="padding-left:5px;"><?php 
									if($idposition==5)
									{
										?> Rang : <b><?php
										echo round(getRangAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo),2);
									}
									?></b></td>
									<td style="padding-left:5px;"><?php 
									if($idposition==5)
									{
										?> Moy. gale Ann : <?php
										echo round(getMoyenneGeneralAnnuelle($idanneescolaire,5,$idsalle,$pdo),2);
									}
									?></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Encouragement : <?php echo $Encouragement;?></td>
									<td colspan="4" rowspan="7" align="center" style="padding-left:5px;"><img src="images/logo.jpg" width="200px" height="110px"></td>
									<td align="center" rowspan="8" valign="top" style="padding-top:5px;font-weight:bold;">
										<i>Nom/Signature Titulaire</i>
										<br/>
										<br/>
										<br/>
										<?php echo getProfTitulaireSignature($idanneescolaire,$statutanneescolaire,$idsalle,$pdo);?>
										<br/>
										<br/>
										<br/>
										<span style="font-size:12px;text-align:center;"><?php echo utf8_decode(htmlentities($profTitulaire));?></span>
									</td>
									<td align="center" colspan="2" rowspan="8" valign="top" style="padding-top:5px;font-weight:bold;">
										<i>Observations du Chef d'&eacute;tablissement</i>
										<br/>
										<br/><?php
										if($idposition==5)
										{
											?>
											<span style="font-size:12px;text-align:center;">
												Travail semestriel :
											<?php echo htmlentities($observationchef);?></span>
											<br/>
											<span style="font-size:12px;text-align:center;">
												Travail Annuel :
											<?php echo htmlentities(AppreciationAnnuelle(round($Annu)));?></span><?php
										}
										else
										{
											?>
											<span style="font-size:12px;text-align:center;">
											<?php 
												echo htmlentities($observationchef);?></span>
											<?php
										}
										?>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<p style="font-size:12px;text-align:right;margin:10px;"><?php echo utf8_decode(htmlentities($directeur));?></p>
									</td>									
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Avertissement : <?php echo $avertissement;?></td>										
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;font-weight:bold;">Blame</td>					
								</tr>
								<tr>
									<td colspan="4" rowspan="1" style="padding-left:5px;font-weight:bold;"><i>DECISION DU CONSEIL DE CLASSE</i></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;"></td>											
								</tr>
								<tr>
									<td colspan="4" style="padding-left:5px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="height:70px;"><img src="codebarre/<?php echo $idbulletin.".png";?>"/></td>
								</tr>
								<tr>
									<td colspan="4" ></td>
									<td colspan="4"	 style="border-top:1px solid #fff;"><span style="font-size:10px;"><b><i>Kpalim&eacute; le : </i></b> <?php echo date('d/m/Y');?></span></td>							
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<hr/>
			</div><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
	}	
	?>
    </body>
</html>