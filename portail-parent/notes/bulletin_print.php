<?php
// ==========================================================================
// INITIALISATION PORTAIL-PARENT (sécurité + session)
// ==========================================================================
require_once __DIR__ . '/../includes/session.php';

$id_bulletin = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id_bulletin) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'><h3>Erreur : ID du bulletin manquant.</h3></div>");
}

$enfant_actif_id = intval($_SESSION['active_eleve_id'] ?? 0);

$stmt = $pdo->prepare("SELECT b.ID, b.IDSALLE, b.IDANNEESCOLAIRE, b.IDPOSITION, b.IDELEVE
    FROM bulletin b WHERE b.ID = ? AND b.IDELEVE = ? LIMIT 1");
$stmt->execute([$id_bulletin, $enfant_actif_id]);
$bulletinInfo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bulletinInfo) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'><h3>Erreur : Bulletin introuvable ou accès non autorisé.</h3></div>");
}

$idsalle = $bulletinInfo['IDSALLE'];
$idanneescolaire = $bulletinInfo['IDANNEESCOLAIRE'];
$idposition = $bulletinInfo['IDPOSITION'];
$idelevesalle = $bulletinInfo['IDELEVE'];

require_once __DIR__ . '/../modele/function.php';
require_once __DIR__ . '/../modele/droit.php';

$idclasse = getIdClasseForSalle($idsalle,$pdo);
$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
$profTitulaire = getProfTitulaire($idanneescolaire,$statutanneescolaire,$idsalle,$pdo);
$directeur = getDirecteur($idanneescolaire,$statutanneescolaire,$pdo);

$idbulletin = $id_bulletin;

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
		$stmt->execute();
		$donnees = $stmt->fetch();

if (!$donnees) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'><h3>Erreur : Données du bulletin introuvables.</h3></div>");
}

		$ligne=0;
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
		$nbreAbsence = getNbreAbsenceForAnneescolaire($ideleve,$idposition,$idanneescolaire,$pdo);
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
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Bulletin de Notes</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --primary:#111827;
      --accent:#0f766e;
      --muted:#6b7280;
      --border:#e5e7eb;
    }
    @page { size: A4; margin: 8mm; }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      font-family: "Poppins", sans-serif;
      color:var(--primary);
      margin:0;
      background:#fff;
      -webkit-print-color-adjust:exact; print-color-adjust:exact;
    }
    .sheet{width:100%; margin:0 auto;}
    .header{display:grid; grid-template-columns:1fr 1fr; gap:0mm; align-items: baseline; margin-bottom:8mm;}
    .left h2{margin:.2rem 0; font-size:10.5px; text-transform:uppercase;}
    .left .bloc{font-size:10.5px; color:var(--primary)}
    .motto{font-style:italic; color:var(--muted); margin-top:2mm}

    .right{text-align:center}
    .right .rep{ font-weight:600; text-transform:uppercase; }
    .right .rep small{font-weight:400; color:var(--muted); }

    .title{
      text-align:center;
      font-weight:700; font-size:10.5px;text-transform:uppercase;
      background:linear-gradient(180deg,#f9fafb, #f3f4f6);
    }

    .meta{ display:grid; grid-template-columns: repeat(4, 1fr); gap:0mm; margin-bottom:0mm; font-size:10.5px; }
    .meta .cell{ padding:0mm 0mm; border:1px solid var(--border); border-radius:6px; }
    .meta b{ font-size:10.5px; color:var(--muted); font-weight:600; text-transform:uppercase; }

    table{ width:100%; border-collapse:collapse; font-size:10.5px; margin:0mm 0 0mm; }
    /*th, td{ border:1px solid #000; padding:0mm 0mm; }*/
    th{text-transform:uppercase; font-size:10.5px; text-transform:capitalize;}
    td.num, th.num{ text-align:center; white-space:nowrap; font-weight:700;}
    tfoot td{ font-weight:700; }
    .section{ margin-top:0mm; }
    .section h3{ 
      background:#ecfeff; border:1px solid #ccfbf1; color:#065f46; padding:0mm 0mm; border-radius:6px;
      margin:0 0 0mm; font-size:10.5px; text-transform:uppercase;
    }

    .grid-2{ display:grid; grid-template-columns:1fr 1fr;}
    .grid-3{ display:grid; grid-template-columns:repeat(3,1fr);}

    .panel{ border:1px solid var(--border); border-radius:6px; padding:2mm; background:#fff; }
    .panel h4{font-size:10.5px; text-transform:uppercase; color:var(--muted); }
    .key{ display:grid; grid-template-columns: 1fr auto;font-size:10px; }

    .foot{display:grid; grid-template-columns: 1fr 1fr; gap:0mm; align-items:end; }
	.sign_left{ text-align:left;margin-left:5mm}
    .sign_left b{ display:block; margin-top:2mm; }
    .sign_right{ text-align:right;margin-right:5mm}
    .sign_right b{ display:block; margin-top:2mm; }

    .badges{ display:flex;align-items:center; }
    .badge{ border:1px dashed var(--border);border-radius:6px; font-size:10.5px; }

    .tiny{ font-size:10.5px; color:var(--muted); }

    /* Print tweaks */
    @media print {
      .sheet{max-width:none}
      .title{ -webkit-print-color-adjust:exact; }
      a[href^="http"]:after{ content:"" }
    }
	
	.Cadre_haut td{
		border:2px solid #000;
		padding:3px;
	}
	
	.Cadre_corps {
	  border: 1px solid #000;       /* Bordure extérieure du tableau */
	  border-collapse: collapse;    /* Fusionne les bordures */
	  width: 100%;
	}
	
    .Cadre_corps th,
	.Cadre_corps td {
	  border: 1px solid #000;       /* Bordure des cellules internes */
	  padding:1px 3px;
	}
	
	.Cadre_corps th[colspan="14"] {
	  font-size: 12px;
	  font-weight: bold;
	  padding: 2px 0;
	  line-height: 1.3;
	  background: #e0f7fa;
	  text-transform: capitalize;
	}

	.Cadre_corps {
	  position: relative; /* nécessaire pour positionner le filigrane */
	}

	.table-wrapper {
	  position: relative; /* conteneur relatif pour l'image */
	}

	.watermark {
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  width: 250px;       /* agrandir selon besoin */
	  height: auto;
	  opacity: 0.1;       /* transparence du filigrane */
	  transform: translate(-50%, -50%);
	  z-index: 0;         /* derrière le tableau */
	}

	.Cadre_corps {
	  position: relative;
	  z-index: 1;         /* au-dessus du filigrane */
	}
	
	.row_entete {
	  background-color:#007bff;
	}
  </style>
</head>
<body>
	  <main class="sheet">
		<table width="100%">
			<tr>
				<td width="33%" align="center">
					<div class="left">
						<div class="bloc">
						  <div><b>MINISTERE DES ENSEIGNEMENTS PRIMAIRE, SECONDAIRE ET TECHNIQUE</b></div>
						  <div><b>DRE – GRAND LOMÉ</b></div>
						  <div><b>IESG – GLO</b></div>
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
		<div class="title">Bulletin de notes du <?php echo $libposition;?></div>
		<!-- Informations élève -->
		<table width="100%">
			<tr>
				<td width="10%">
					<section class="meta">
					  <div class="cell"><img src="images/user.png" width="100px" height="100px"></div>
					</section>
				</td>
				<td width="50%">
					<table width="100%">
						<tr>
							<td style="font-size:13px;"><b><?php echo $nom;?></b></td>
						</tr>
						<tr>
							<td><b>Sexe : <?php echo $sexe_eleve;?></b></td>
						</tr>
						<tr>
							<td><b>N° matricule : <?php echo $matricule;?></b></td>
						</tr>
						<tr>
							<td><b>Statut: <?php echo $etat_eleve;?></b></td>
						</tr>
					</table>
				</td>
				<td width="20%">
					<table width="100%" class="Cadre_haut">
						<tr>
							<td><b>Année scolaire</b></td>
							<td><b><?php echo $LibelleAnneeScolaire;?></b></td>
						</tr>
						<tr>
							<td><b>Classe</b></td>
							<td><b><?php echo $codesalle;?></b></td>
						</tr>
						<tr>
							<td><b>Effectif</b></td>
							<td><b><?php echo $effectif;?></b></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- En-têtes communs du tableau -->
		<!-- 1) Littéraires -->
		<div class="table-wrapper">
		<img src="images/logo_.jpg" class="watermark"/>
		<table class="Cadre_corps">
		  <thead>
			<tr class="row_entete">
			  <th rowspan="2">Matieres</th>
			  <th class="num" colspan="3">Devoirs</th>
			  <th class="num" rowspan="2">Moy Classe</th>
			  <th class="num" rowspan="2">Comp</th>
			  <th class="num" rowspan="2">Moy Trim</th>
			  <th class="num" rowspan="2">Coef</th>
			  <th class="num" rowspan="2">Moy Coef</th>
			  <th class="num" rowspan="2">Rang</th>
			  <th rowspan="2">Appréciations</th>
			  <th rowspan="2">Professeurs</th>
			  <th rowspan="2" width="10%">Visa</th>
			</tr>
			<tr class="row_entete">		
			  <th class="num">1</th>
			  <th class="num">2</th>
			  <th class="num">3</th>
			</tr>
			<tr colspan="14">
			  <th colspan="14">1) Matières littéraires</th>
			</tr>
		  </thead>
		  <tbody>
			<tr>
				<td><b>Français</b></td>
				<td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td><?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></td>
			</tr>
			<tr>
				<td><b>Anglais</b></td>
				<td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td><?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></td>
			</tr>
			<tr>
				<td><b>Histo-Géo</b></td>
				<td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td><?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></td>
			</tr>
			<tr>
				<td><b>ECM</b></td>
				<td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td><?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></td>
			</tr>
			<tr>
				<td><b>PHILO</b></td>
				<td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td><?php echo BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
				<td>&nbsp;<?php echo BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);?></td>
			</tr>
        <tr>
          <td colspan="7" align="right"><b>Total</b></td>
          <td class="num">
          <?php
          echo
          $TotalCoef_1 =
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);
          ?>
          </td>
          <td class="num">
          <?php
          echo
          $Totalmoy_1 =
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,35,$pdo);
          ?>
          </td>
          <td colspan="3">
            <b>Moy (1) : <?php if($TotalCoef_1!=0) echo round($Totalmoy_1/$TotalCoef_1,2);?></b>
          </td>
        </tr>
        <tr>
          <th colspan="14">2) Matières scientifiques</th>
        </tr>
        <tr>
          <td><b>Mathématiques</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>Phys-Chim-Tech</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>SVT</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>Sc. numériques</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td colspan="7" align="right"><b>Total</b></td>
          <td class="num">
          <?php
          echo
          $TotalCoef_2 =
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);
          ?>
          </td>
          <td class="num">
          <?php
          echo
          $Totalmoy_2 =
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,36,$pdo);
          ?>
          </td>
          <td colspan="3"><b>Moy (2) : <?php if($TotalCoef_2!=0) echo round(($Totalmoy_2/$TotalCoef_2),2);?></b></td>
        </tr>
        <tr>
          <th colspan="14">3) Matières spécifiques</th>
        </tr>
        <tr>
          <td><b>EPS</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>Musique</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td colspan="7" align="right"><b>Total</b></td>
          <td class="num">
          <?php
          echo
          $TotalCoef_3 =
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);
          ?>
          </td>
          <td class="num">
          <?php
          echo
          $Totalmoy_3 =
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,9,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,31,$pdo);
          ?>
          </td>
          <td colspan="3"><b>Moy (3) : <?php if($TotalCoef_3!=0) echo round(($Totalmoy_3/$TotalCoef_3),2);?></b></td>
        </tr>
        <tr>
          <th colspan="14">4) Matières optionnelles</th>
        </tr>
        <tr>
          <td><b>Init. Droit</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>Allemand</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>STRATEGIE</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>CHINOIS</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td><b>Portugais</b></td>
          <td class="num">&nbsp;<?php echo BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td class="num">&nbsp;<?php echo BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td><?php echo htmlspecialchars(BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo), ENT_QUOTES, 'UTF-8');?></td>
          <td>&nbsp;<?php echo htmlspecialchars(BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo), ENT_QUOTES, 'UTF-8');?></td>
        </tr>
        <tr>
          <td colspan="7" align="right"><b>Total</b></td>
          <td class="num">
          <?php
          echo
          $TotalCoef_4 =
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo)+
          (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);
          ?>
          </td>
          <td class="num">
          <?php
          echo
          $Totalmoy_4 =
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,30,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,27,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,29,$pdo)+
          (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,28,$pdo);
          ?>
          </td>
          <td colspan="3"><b>Moy (4) : <?php if($TotalCoef_4!=0) echo round(($Totalmoy_4/$TotalCoef_4),2);?></b></td>
        </tr>
        <tr>
          <td colspan="7" align="left"><b>&nbsp;Total</b></td>
          <td class="num"><?php echo $TotalCoef_1+$TotalCoef_2+$TotalCoef_3+$TotalCoef_4;?></td>
          <td class="num"><?php echo round(($Totalmoy_1+$Totalmoy_2+$Totalmoy_3+$Totalmoy_4),2);?></td>
          <td colspan="3"><b></b></td>
        </tr>
       </tbody>
    </table>
    </div>
    <table width="100%">
      <tr>
        <td style="padding:2mm;" valign="top">
          <table width="100%" class="Cadre_haut">
            <tr>
              <td>
              <?php
                if($idposition==1)
                {
                  ?><b>Moy. 1<sup>er</sup> Trim :</b><?php
                }
                elseif($idposition==2)
                {
                  ?><b>Moy. 2<sup>&egrave;m</sup> Trim :</b><?php
                }
                elseif($idposition==3)
                {
                  ?><b>Moy. 3<sup>&egrave;m</sup> Trim :</b><?php
                }
              ?>
              </td>
              <td><b><?php echo $MoyenneTrimestre = round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,$idposition,$pdo)),2);?></b></td>
            </tr>
            <tr>
              <td><b>Moy. en lettres</b></td>
              <td><b><?php echo nombreEnLettre($MoyenneTrimestre);?></b></td>
            </tr>
            <tr>
              <td><b>Rang</b></td>
              <td><b><?php echo getRangTrimestre_NV($idanneescolaire,$idsalle,$ideleve,$idposition,$pdo);?></b></td>
            </tr>
          </table>
          <?php
          if($idposition==3)
          {
            ?>
            <table class="Cadre_haut">
              <tr style="background-color:#007bff;">
                <td></td>
                <td><div><b>3<sup>&egrave;m</sup> Trim</b></div></td>
                <td><div><b>Annuelle</b></div></td>
              </tr>
              <tr>
                <td><div><b>Moy. la plus forte</b></div></td>
                <td><div><b><?php echo round(trim(getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo)),2);?></b></div></td>
                <td><div><b></b></div></td>
              </tr>
              <tr>
                <td><div><b>Moy. la plus faible</b></div></td>
                <td><div><b><?php echo round(getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo),2);?></b></div></td>
                <td><div><b></b></div></td>
              </tr>
              <tr>
                <td><div><b>Moy. générale de classe</b></div></td>
                <td><div><b><?php echo round(getMoyenneGeneralTrimestre($idanneescolaire,$idposition,$idsalle,$pdo),2);?></b></div></td>
                <td><div><b></b></div></td>
              </tr>
            </table>
            <?php
          }
          else
          {
            ?>
            <table class="Cadre_haut">
              <tr>
                <td><div><b>Moy. la plus forte</b></div></td>
                <td><div><b><?php echo round(trim(getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo)),2);?></b></div></td>
                <td><div><b></b></div></td>
              </tr>
              <tr>
                <td><div><b>Moy. la plus faible</b></div></td>
                <td><div><b><?php echo round(getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo),2);?></b></div></td>
                <td><div><b></b></div></td>
              </tr>
              <tr>
                <td><div><b>Moy. générale de classe</b></div></td>
                <td><div><b><?php echo round(getMoyenneGeneralTrimestre($idanneescolaire,$idposition,$idsalle,$pdo),2);?></b></div></td>
                <td><div><b></b></div></td>
              </tr>
            </table>
            <?php
          }
          ?>
        </td>
        <td valign="top">
           <table>
            <tr>
              <td style="padding:2mm;">
                <?php
                if($idposition==1)
                {
                  ?>
                  <table width="100%" class="Cadre_haut">
                    <tr>
                      <td><b>Moy. Moy. 1<sup>er</sup> Trim</b></td>
                      <td><b><?php echo round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,1,$pdo)),2);?></b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b>1</b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                  </table>
                  <?php
                }
                elseif($idposition==2)
                {
                  ?>
                  <table width="100%" class="Cadre_haut">
                    <tr>
                      <td><b>Moy. Moy. 1<sup>er</sup> Trim</b></td>
                      <td><b><?php echo round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,1,$pdo)),2);?></b></td>
                    </tr>
                    <tr>
                      <td><b>Moy. 2e trim</b></td>
                      <td><b><?php echo round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,2,$pdo)),2);?></b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                    <tr>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                  </table>
                  <?php
                }
                elseif($idposition==3)
                {
                  ?>
                  <table width="100%" class="Cadre_haut">
                    <tr>
                      <td><b>Moy. Moy. 1<sup>er</sup> Trim</b></td>
                      <td><b><?php echo round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,1,$pdo)),2);?></b></td>
                    </tr>
                    <tr>
                      <td><b>Moy. 2e trim</b></td>
                      <td><b><?php echo round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,2,$pdo)),2);?></b></td>
                    </tr>
                    <tr>
                      <td><b>Moy. 3e trim</b></td>
                      <td><b><?php echo round(trim(getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,3,$pdo)),2);?></b></td>
                    </tr>
                    <tr>
                      <td><b>Moy. annuelle</b></td>
                      <td><b><?php echo ($Moyen_ann!="" && $Moyen_ann!==null) ? round($Moyen_ann,2) : 'En cours';?></b></td>
                    </tr>
                    <tr>
                      <td><b>Rang annuel</b></td>
                      <td><b><?php echo ($Rang_ann!="" && $Rang_ann!==null) ? $Rang_ann : 'N/A';?></b></td>
                    </tr>
                  </table>
                  <?php
                }
                ?>
              </td>
              <td style="padding:2mm;" valign="top">
                <table>
                  <tr>
                    <td><div class="key" align="left"><input type="radio" name="tableauHonneur" value="1" <?php if ($tableauHonneur!= "") echo "checked"; ?> /></div></td>
                    <td><div class="key" align="left"><b>Tableau d'honneur</b></div></td>
                  </tr>
                  <tr>
                    <td><div class="key" align="left"><input type="radio" name="Encouragement" value="" <?php if ($Encouragement!= "") echo "checked"; ?>/></div></td>
                    <td><div class="key" align="left">Encouragements</div></td>
                  </tr>
                  <tr>
                    <td><div class="key" align="left"><input type="radio" name="" value="Felicitation" <?php if ($Felicitation!= "") echo "checked"; ?> /></div></td>
                    <td><div class="key" align="left">Félicitations</div></td>
                  </tr>
                  <tr>
                    <td colspan="2"><div style="margin-left:auto" class="tiny"><b>Avertissement</b></div></td>
                  </tr>
                  <tr>
                    <td><div class="key" align="left"><input type="radio" name="" value=""/></div></td>
                    <td><div class="key" align="left"> Travail</div></td>
                  </tr>
                  <tr>
                    <td><div class="key" align="left"><input type="radio" name="" value=""/></div></td>
                    <td><div class="key" align="left"> Disciple</div></td>
                  </tr>
                  <tr>
                    <td colspan="2"><div style="margin-left:auto" class="tiny">Retards : <b> </b> &nbsp;&nbsp; Absences : <b> <?php echo $nbreAbsence;?></b></div></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div style="text-align:left;padding:2px;"><b>Observations et décision du conseil</b></div>
                <div class="panel" style="padding:2mm;border:1px solid #000;">
                  <div>
                    <span>
                    <?php
                      echo htmlspecialchars($observationchef, ENT_QUOTES, 'UTF-8');?></span>
                  </div>
                </div>
                <div style="text-align:right;padding:2px;">
                  <span style="font-size:10px;"><b><i>Lom&eacute; le : </i></b> <?php echo date('d/m/Y');?></span>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="left">
          <div valign="top" class="sign_left">
            <div><b>Le Principal de classe</b></div>
            <b><?php echo htmlspecialchars(utf8_decode(htmlentities($profTitulaire)), ENT_QUOTES, 'UTF-8');?></b>
          </div>
        </td>
        <td align="right" colspan="2">
          <div valign="top" class="sign_right">
            <div><b>Le Proviseur</b></div>
              <b><?php echo htmlspecialchars(utf8_decode(htmlentities($directeur)), ENT_QUOTES, 'UTF-8');?></b>
          </div>
        </td>
      </tr>
    </table>
  </main>
  <script>
    window.addEventListener('DOMContentLoaded', function() {
      setTimeout(function() {
        window.print();
      }, 400);
    });
  </script>
</body>
</html>
