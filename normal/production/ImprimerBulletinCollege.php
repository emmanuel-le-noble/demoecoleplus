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
						eleve.datenaissance_eleve as date_naissance,
						eleve.lieunaissance_eleve as lieu_naissance,
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
						eleve.datenaissance_eleve as date_naissance,
						eleve.lieunaissance_eleve as lieu_naissance,
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
		$date_naissance = $donnees['date_naissance'];
		$lieu_naissance = $donnees['lieu_naissance'];

		if($date_naissance!="")
		{
			$tab = explode("-",trim($date_naissance));
			$annee = $tab[0];
			$mois = $tab[1];
			$jour = $tab[2];
			$date_naissance = $jour.'/'.$mois.'/'.$annee;
			$date_et_lieunaissance = $date_naissance.' à '.$lieu_naissance;
		}
		else
		{
			$date_et_lieunaissance="";
		}

		$effectif = getNombreBulletinTrimestre($idsalle,$idanneescolaire,$idposition,$pdo);

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

		// Titre de la periode
		if($idposition==1) $titrePeriode = '1er Trimestre';
		elseif($idposition==2) $titrePeriode = '2ème Trimestre';
		else $titrePeriode = '3ème Trimestre';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bulletin - <?= htmlspecialchars($matricule, ENT_QUOTES, 'UTF-8') ?></title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style>
		body {
			background-color: #525659;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			margin: 0;
			padding: 0;
		}

		.a4-page {
			width: 210mm;
			min-height: 297mm;
			padding: 12mm 14mm 12mm 14mm;
			margin: 20px auto;
			background: #ffffff;
			box-shadow: 0 0 15px rgba(0,0,0,0.5);
			color: #000000;
			position: relative;
			box-sizing: border-box;
		}

		.bulletin-header {
			border-bottom: 2px solid #000000;
			padding-bottom: 10px;
		}

		.student-box {
			border: 1px solid #000000;
			background-color: #f8f9fa;
			padding: 10px 12px;
			font-size: 12px;
		}

		.table-bulletin {
			border-color: #000000 !important;
		}

		.table-bulletin th {
			background-color: #eaedf0 !important;
			color: #000000 !important;
			font-size: 10px;
			font-weight: bold;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border: 1px solid #000000 !important;
			vertical-align: middle;
		}

		.table-bulletin td {
			font-size: 11px;
			border: 1px solid #000000 !important;
			vertical-align: middle;
			color: #000000;
		}

		.section-group-header {
			background-color: #d6e4f0 !important;
			color: #000000 !important;
		}

		.decision-box {
			border: 1px solid #000000;
			padding: 10px 12px;
			background-color: #f8f9fa;
			min-height: 80px;
		}

		.signature-section {
			margin-top: 30px;
		}

		.signature-container {
			border: 1px dashed #666666;
			height: 100px;
			padding: 8px;
			font-size: 11px;
			font-weight: bold;
		}

		@media print {
			@page {
				size: A4 portrait;
				margin: 0;
			}

			body {
				background-color: #ffffff !important;
				margin: 0 !important;
				padding: 0 !important;
			}

			.a4-page {
				margin: 0 !important;
				box-shadow: none !important;
				width: 210mm;
				min-height: 297mm;
				padding: 12mm 14mm !important;
				page-break-after: avoid;
				page-break-inside: avoid;
			}

			.table-bulletin th {
				background-color: #eaedf0 !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
			}

			.student-box, .decision-box {
				background-color: #f8f9fa !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
			}
		}
	</style>
</head>
<body>

	<div class="a4-page">

		<!-- HEADER -->
		<div class="bulletin-header mb-3">
			<div class="row align-items-center">
				<div class="col-5">
					<h6 class="fw-bold m-0 text-uppercase" style="font-size:14px; letter-spacing:0.5px;">Institut Privé ECOLEPLUS</h6>
					<small class="text-muted d-block" style="font-size:10px;">Lomé – TOGO</small>
					<small class="text-dark d-block" style="font-size:10px;"><i class="fa-solid fa-phone me-1"></i> Tél : 00 00 00 00 / 00 00 00 00</small>
				</div>
				<div class="col-2 text-center">
					<img src="images/logo_.jpg" alt="Logo" style="width:65px; height:65px; border-radius:4px;">
					<div class="mt-1" style="font-size:9px; font-style:italic;">« Travail - Discipline - Réussite »</div>
				</div>
				<div class="col-5 text-end">
					<div class="fw-bold mb-1" style="font-size:12px;">Année Scolaire : <?= htmlspecialchars($LibelleAnneeScolaire, ENT_QUOTES, 'UTF-8') ?></div>
					<span class="badge bg-dark rounded-1 text-uppercase px-3 py-2 fw-bold" style="font-size:11px; letter-spacing:1px;">
						<?= htmlspecialchars($libposition, ENT_QUOTES, 'UTF-8') ?>
					</span>
				</div>
			</div>
		</div>

		<!-- TITRE -->
		<div class="text-center my-3">
			<h4 class="fw-bold text-uppercase m-0" style="letter-spacing:2px; text-decoration:underline; font-size:17px;">Bulletin de Notes</h4>
		</div>

		<!-- INFOS ELEVE -->
		<div class="student-box mb-3">
			<div class="row g-2">
				<div class="col-7">
					<div class="mb-1"><b>Nom & Prénom :</b> <span class="text-uppercase fw-bold"><?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?></span></div>
					<div class="mb-1"><b>Matricule :</b> <span class="font-monospace fw-bold"><?= htmlspecialchars($matricule, ENT_QUOTES, 'UTF-8') ?></span></div>
					<div class="mb-1"><b>Sexe :</b> <?= htmlspecialchars($sexe_eleve, ENT_QUOTES, 'UTF-8') ?></div>
					<?php if($etat_eleve): ?>
					<div><b>Statut :</b> <?= htmlspecialchars($etat_eleve, ENT_QUOTES, 'UTF-8') ?></div>
					<?php endif; ?>
				</div>
				<div class="col-5 text-end">
					<div class="mb-1"><b>Classe :</b> <span class="fw-bold"><?= htmlspecialchars($codesalle, ENT_QUOTES, 'UTF-8') ?></span></div>
					<div class="mb-1"><b>Effectif :</b> <?= $effectif ?></div>
					<?php if($date_naissance): ?>
					<div><b>Date de naissance :</b> <?= htmlspecialchars($date_et_lieunaissance, ENT_QUOTES, 'UTF-8') ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- TABLEAU DE NOTES -->
		<table class="table table-bordered table-bulletin align-middle text-center mb-3">
			<thead>
				<tr>
					<th class="text-start" style="width:28%;">Matières</th>
					<th style="width:8%;">INTE</th>
					<th style="width:8%;">DS</th>
					<th style="width:8%;">DN</th>
					<th style="width:7%;">Coef</th>
					<th style="width:10%;">Moy. Trim</th>
					<th class="text-start" style="width:18%;">Appréciation</th>
				</tr>
			</thead>
			<tbody>
				<!-- 1) Matières littéraires -->
				<tr>
					<th colspan="7" class="section-group-header text-start">1) Matières littéraires</th>
				</tr>
				<tr>
					<td class="text-start fw-bold">Français</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Anglais</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Histo-Géo</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">ECM</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr class="table-active">
					<td colspan="4" class="text-end fw-bold">Total Littéraires</td>
					<td class="fw-bold">
						<?php
						$TotalCoef_1 =
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);
						echo $TotalCoef_1;
						?>
					</td>
					<td class="fw-bold">
						<?php
						$Totalmoy_1 =
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);
						echo $Totalmoy_1;
						?>
					</td>
					<td class="text-start fw-bold">Moy : <?php if($TotalCoef_1!=0) echo round($Totalmoy_1/$TotalCoef_1,2); ?></td>
				</tr>

				<!-- 2) Matières scientifiques -->
				<tr>
					<th colspan="7" class="section-group-header text-start">2) Matières scientifiques</th>
				</tr>
				<tr>
					<td class="text-start fw-bold">Mathématiques</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Phys-Chim-Tech</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">SVT</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Sc. numériques</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr class="table-active">
					<td colspan="4" class="text-end fw-bold">Total Scientifiques</td>
					<td class="fw-bold">
						<?php
						$TotalCoef_2 =
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);
						echo $TotalCoef_2;
						?>
					</td>
					<td class="fw-bold">
						<?php
						$Totalmoy_2 =
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,24,$pdo);
						echo $Totalmoy_2;
						?>
					</td>
					<td class="text-start fw-bold">Moy : <?php if($TotalCoef_2!=0) echo round(($Totalmoy_2/$TotalCoef_2),2); ?></td>
				</tr>

				<!-- 3) Matières spécifiques -->
				<tr>
					<th colspan="7" class="section-group-header text-start">3) Matières spécifiques</th>
				</tr>
				<tr>
					<td class="text-start fw-bold">EPS</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Musique</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Init. AGRI</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr class="table-active">
					<td colspan="4" class="text-end fw-bold">Total Spécifiques</td>
					<td class="fw-bold">
						<?php
						$TotalCoef_3 =
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);
						echo $TotalCoef_3;
						?>
					</td>
					<td class="fw-bold">
						<?php
						$Totalmoy_3 =
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,40,$pdo);
						echo $Totalmoy_3;
						?>
					</td>
					<td class="text-start fw-bold">Moy : <?php if($TotalCoef_3!=0) echo round(($Totalmoy_3/$TotalCoef_3),2); ?></td>
				</tr>

				<!-- 4) Matières optionnelles -->
				<tr>
					<th colspan="7" class="section-group-header text-start">4) Matières optionnelles</th>
				</tr>
				<tr>
					<td class="text-start fw-bold">Init. Droit</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Allemand</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr>
					<td class="text-start fw-bold">Ewe</td>
					<td><?= BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo) ?></td>
					<td><?= BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo) ?></td>
					<td><?= BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo) ?></td>
					<td class="fw-bold"><?= BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo) ?></td>
					<td class="text-start fst-italic small"><?= htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo), ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
				<tr class="table-active">
					<td colspan="4" class="text-end fw-bold">Total Optionnelles</td>
					<td class="fw-bold">
						<?php
						$TotalCoef_4 =
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo)+
						(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);
						echo $TotalCoef_4;
						?>
					</td>
					<td class="fw-bold">
						<?php
						$Totalmoy_4 =
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo)+
						(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,41,$pdo);
						echo $Totalmoy_4;
						?>
					</td>
					<td class="text-start fw-bold">Moy : <?php if($TotalCoef_4!=0) echo round(($Totalmoy_4/$TotalCoef_4),2); ?></td>
				</tr>

				<!-- TOTAL GENERAL -->
				<tr class="table-dark">
					<td colspan="4" class="text-end fw-bold text-white">TOTAL GÉNÉRAL</td>
					<td class="fw-bold text-white"><?= $TotalCoef_1+$TotalCoef_2+$TotalCoef_3+$TotalCoef_4; ?></td>
					<td class="fw-bold text-white"><?= round(($Totalmoy_1+$Totalmoy_2+$Totalmoy_3+$Totalmoy_4),2); ?></td>
					<td class="text-start fw-bold text-white"></td>
				</tr>
			</tbody>
		</table>

		<!-- SYNTHÈSE & DÉCISION -->
		<div class="row g-3 align-items-stretch mb-3">
			<div class="col-6">
				<table class="table table-bordered table-bulletin h-100 m-0">
					<thead>
						<tr>
							<th colspan="2" class="text-center py-2">Synthèse de la période</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="fw-bold py-2 small" style="width:60%;">
								<?php
								$MoyenneTrimestre = round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,$idposition,$pdo)),2);
								if($idposition==1) echo 'MOYENNE 1er TRIMESTRE';
								elseif($idposition==2) echo 'MOYENNE 2ème TRIMESTRE';
								else echo 'MOYENNE 3ème TRIMESTRE';
								?>
							</td>
							<td class="fw-bold fs-6 text-center bg-light py-2" style="width:40%;"><?= $MoyenneTrimestre ?> / 20</td>
						</tr>
						<tr>
							<td class="small py-2">Moyenne en lettres</td>
							<td class="fw-bold text-center py-2"><?= nombreEnLettre($MoyenneTrimestre) ?></td>
						</tr>
						<tr>
							<td class="small py-2">Rang Périodique</td>
							<td class="fw-bold text-center py-2"><?= getRangTrimestre_NV($idanneescolaire,$idsalle,$ideleve,$idposition,$pdo) ?></td>
						</tr>
						<?php if($idposition==3): ?>
						<tr>
							<td class="small py-2">Moyenne Annuelle</td>
							<td class="text-center py-2 fw-bold"><?= ($Moyen_ann!="" && $Moyen_ann!==null) ? round($Moyen_ann,2).' / 20' : 'En cours' ?></td>
						</tr>
						<tr>
							<td class="small py-2">Rang Annuel</td>
							<td class="text-center py-2 fw-bold"><?= ($Rang_ann!="" && $Rang_ann!==null) ? $Rang_ann : 'N/A' ?></td>
						</tr>
						<?php endif; ?>
						<tr>
							<td class="small py-2">Moyenne la plus haute</td>
							<td class="text-center py-2 fw-bold"><?= round(trim(getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo)),2) ?></td>
						</tr>
						<tr>
							<td class="small py-2">Moyenne la plus basse</td>
							<td class="text-center py-2 fw-bold"><?= round(getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo),2) ?></td>
						</tr>
						<tr>
							<td class="small py-2">Moyenne générale de classe</td>
							<td class="text-center py-2 fw-bold"><?= round(getMoyenneGeneralTrimestre($idanneescolaire,$idposition,$idsalle,$pdo),2) ?></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="col-6">
				<div class="decision-box h-100">
					<span class="fw-bold d-block text-uppercase mb-2" style="font-size:10px; letter-spacing:0.5px;">
						<i class="fa-solid fa-comments me-1"></i>Avis et Décision du Conseil de Classe :
					</span>
					<p class="m-0 text-dark small font-monospace" style="line-height:1.4;">
						<?= htmlspecialchars($observationchef ?: 'Aucune observation enregistrée.', ENT_QUOTES, 'UTF-8') ?>
					</p>
				</div>
			</div>
		</div>

		<!-- SIGNATURES -->
		<div class="row signature-section text-center g-3">
			<div class="col-4">
				<div class="signature-container">
					Le Principal de classe<br>
					<small class="text-muted fw-normal"><?= htmlspecialchars(utf8_decode(htmlentities($profTitulaire)), ENT_QUOTES, 'UTF-8') ?></small>
				</div>
			</div>
			<div class="col-4">
				<div class="signature-container">
					Le Parent d'Élève
				</div>
			</div>
			<div class="col-4">
				<div class="signature-container">
					Le Proviseur<br>
					<small class="text-muted fw-normal"><?= htmlspecialchars(utf8_decode(htmlentities($directeur)), ENT_QUOTES, 'UTF-8') ?></small>
				</div>
			</div>
		</div>

	</div>

	<script>
		window.addEventListener('DOMContentLoaded', function() {
			setTimeout(function() {
				window.print();
			}, 400);
		});
	</script>
</body>
</html>
<?php } ?>
