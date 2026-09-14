<?php

function ListeEffecifClasse($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					salle.id as idsalle,
					salle.codesalle as codesalle,
					count(elevesalle.id) as nbreelevesalle

			FROM classe,salle,anneescolaire,elevesalle,eleveanneescolaire
			WHERE
			classe.idclasse=salle.idclasse
			AND
			salle.id=elevesalle.idsalle
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.statut!=""
			AND
			eleveanneescolaire.etat!=""
			AND
			anneescolaire.id=:idanneescolaire
			
			GROUP BY classe.idclasse,salle.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
				<th style="width:19%;font-weight:bold;color:#000">Ann&eacute;e scol.</th>
				<th style="width:19%;font-weight:bold;color:#000">Niveau</th>
				<th style="width:19%;font-weight:bold;color:#000">Classe</th>
				<th style="width:19%;font-weight:bold;color:#000">Effectif</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$fichier ="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$idsalle = $donnees['idsalle'];
			$codesalle = $donnees['codesalle'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
			$nbreelevesalle = $donnees['nbreelevesalle'];
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idanneescolaire.'*'.$libelleanneescolaire.'*'.$idsalle.'*'.$codesalle;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo $codesalle;?></a>
			    </td>
				<td>
					<a><?php echo $nbreelevesalle;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?>
	</table>
	<input type="hidden" name="nbreeffectifclasse" value="<?php echo $ligne;?>"/><?php
}

function EffectifParClasse($idsalle,$idanneescolaire,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			salle.id=:idsalle

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<br/>
	<table class="table table-striped projects" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Statut</th>
				<th style="width:10%;">Matricule</th>
				<th style="width:15%;">Nom</th>
				<th style="width:15%;">Pr&eacute;nom</th>
				<th style="width:10%;">Date Naissance</th>
				<th style="width:10%;">Sexe</th>
				<th style="width:10%;">Statut Classe</th>
				<th style="width:10%;">Statut Etab.</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td>
					<a>
						<?php 
							if($statut==0)
							{
								echo 'Abandon';
							}
							else
							{
								echo 'Actif';
							}
						?>
					</a>
				</td>
				<td>
					<a><?php echo $matricule;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $datenaissance_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td>
					<a><?php echo $elevestatutclasse;?></a>
				</td>
				<td>
					<a><?php echo $elevestatutetablissement;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ImprimeFicheNotes($idsalle,$idanneescolaire,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			salle.id=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:15%;">Nom</th>
				<th style="width:19%;">Pr&eacute;nom</th>
				<th colspan="3" style="width:15%;">Interrogations</th>
				<th style="width:7%;">DST</th>
				<th style="width:7%;">M.C</th>
				<th style="width:7%;">COMP</th>
				<th style="width:7%;">M.G</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td><a></a></td>
				<td><a></a></td>
				<td><a></a></td>
				<td><a></a></td>
				<td><a></a></td>
				<td><a></a></td>
				<td><a></a></td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ImprimeFicheClasses($idsalle,$idanneescolaire,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					eleve.teltuteur as teltuteur,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			salle.id=:idsalle
			AND
			elevesalle.statut=1

			ORDER BY  eleve.nom_eleve, eleve.prenom_eleve asc');	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Matricule</th>
				<th style="width:16%;">Nom</th>
				<th style="width:16%;">Pr&eacute;nom</th>
				<th style="width:10%;">Date naissance</th>
				<th style="width:10%;">Sexe</th>
				<th style="width:13%;">Contact parent/tuteur</th>
				<th style="width:16%;">Observations</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			$teltuteur = $donnees['teltuteur'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $matricule;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $datenaissance_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $teltuteur;?></a>
				</td>
				<td><a></a></td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ReclamationPaiement($etat,$idpaiementtype,$idanneescolaire,$idclasse,$tranche,$pdo)
{
	
	$req=(' SELECT  
			distinct 
			eleve.nom_eleve as nomeleve,
			eleve.prenom_eleve as prenomeleve,
			eleveanneescolaire.id as ideleve,
			eleveanneescolaire.idclasse as idclasse,
			eleveanneescolaire.inscrit as inscrit,
			eleveanneescolaire.etat as etat,
			0 as boursier,
			anneescolaire.libelle as anneescolaire,
			classe.codeclasse AS codeclasse
			
	FROM eleve,eleveanneescolaire,anneescolaire,classe
	WHERE
	eleve.id_eleve=eleveanneescolaire.ideleve
	AND
	eleveanneescolaire.idanneescolaire=anneescolaire.id
	AND
	anneescolaire.id=:idanneescolaire
	AND
	eleveanneescolaire.statut=1
	AND
	eleveanneescolaire.idclasse=classe.idclasse
	AND
	classe.idclasse=:idclasse

	ORDER BY eleve.nom_eleve,eleve.prenom_eleve ASC');
	
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color: #eee;">
			    <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">Classe</th>
				<th style="width:11%;">Nom & Pr&eacute;nom</th>
				<th style="width:10%;">Statut</th>
				<th style="width:10%;">Frais Scolarit&eacute;</th>
				<th style="width:10%;color:red;">Tranche</th>
				<th style="width:10%;">Total Vers&eacute;</th>
				<th style="width:10%;">Total Restant</th>
				<th style="width:10%;color:#000;">Statut Paiement</th>
		    </tr>
	    </thead>
		<?php		
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ideleve = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$anneescolaire = $donnees['anneescolaire'];
			$boursier = $donnees['boursier'];
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$inscrit = $donnees['inscrit'];
			if($inscrit==1)
			{
				$libelleinscrit="Nouveau";
			}
			else
			{
				$libelleinscrit="Ancien";
			}
			$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$boursier,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			$montantpaiementeleve = getMontantPaiementFraisEleveAnneeScolaire($idpaiementtypeclasse,$ideleve,$pdo);
			$montantrestant = $montantpaiementtypeclasse-$montantpaiementeleve;
			if($etat==1)
			{
				$statut="En r&egrave;gle";
				if($montantpaiementeleve>=$tranche)
				{
					?>
					<tr>
						<td>
							<input class="flat" type="checkbox"/>						
						</td>
						<td>
							<a><?php echo $anneescolaire;?></a>
						</td>
						<td>
							<a><?php echo $codeclasse;?></a>
						</td>
						<td>
							<a><?php echo $nomeleve;?></a>
						</td>
						<td>
							<a><?php echo $libelleinscrit;?></a>
						</td>
						<td>
							<a><?php echo number_format($montantpaiementtypeclasse,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo number_format($tranche,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo number_format($montantpaiementeleve,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo number_format($montantrestant,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo $statut;?></a>
						</td>
					</tr><?php
				}
			}
			else
			{
				$statut="Non en r&egrave;gle";
				if($tranche>$montantpaiementeleve)
				{
					?>
					<tr>
						<td>
							<input class="flat" type="checkbox"/>						
						</td>
						<td>
							<a><?php echo $anneescolaire;?></a>
						</td>
						<td>
							<a><?php echo $codeclasse;?></a>
						</td>
						<td>
							<a><?php echo $nomeleve.' ('.$libelleinscrit.')';?></a>
						</td>
						<td>
							<a><?php echo $libelleinscrit;?></a>
						</td>
						<td>
							<a><?php echo number_format($montantpaiementtypeclasse,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo number_format($tranche,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo number_format($montantpaiementeleve,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo number_format($montantrestant,0,""," ");?></a>
						</td>
						<td>
							<a><?php echo $statut;?></a>
						</td>
					</tr><?php
				}
			}
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrereclamation" value="<?php echo $ligne;?>"/><?php
}

function RecetteJournalierePaiementFrais($Debut,$Fin,$idAnneeScolaire,$pdo)
{
	
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as Libelle,
					paiementfrais.montant as Montant,
					paiementfrais.date as Datepaiement,
					paiementfrais.id as IdPaiementFrais,
					utilisateur.nom_user as NomUser,
					utilisateur.prenom_user as PrenomUser,
					classe.codeclasse as CodeClasse,
					eleve.id_eleve as Ideleve,
					eleve.nom_eleve as Nomeleve,
					eleve.prenom_eleve as PrenomEleve,
					anneescolaire.libelle as Libelleanneescolaire
					
			FROM paiementtype,paiementtypeclasse,classe,paiementfrais,eleve,eleveanneescolaire,anneescolaire,utilisateur
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			AND
			paiementfrais.ideleveanneescolaire=eleveanneescolaire.id
			AND
			eleveanneescolaire.statut=1
			AND
			paiementtypeclasse.id=paiementfrais.idpaiementtypeclasse
			AND
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idclasse=classe.idclasse
			AND
			paiementfrais.iduserajout=utilisateur.id
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			paiementfrais.statut=1
			AND
			paiementfrais.date between :DateDebut AND :DateFin
			AND
			paiementfrais.id NOT IN (SELECT caissepaiement.idpaiementfrais FROM caissepaiement WHERE caissepaiement.type="PaiementFraisScolarite")
			
			ORDER BY paiementfrais.id DESC');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;"> 
				<th style="width:15%">Nom & pr&eacute;nom</th>
				<th style="width:12%">Classe</th>
				<th style="width:15%">Montant pay&eacute; (FCFA)</th>
				<th style="width:10%">Date paiement</th>
				<th style="width:10%">Frais scolarit&eacute;</th>
				<th style="width:10%">Ann&eacute;e scolaire</th>
				<th style="width:10%">Enregistr&eacute; par</th>
				<th style="width:8%">Action(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':DateDebut',$Debut,PDO::PARAM_STR);
		$stmt->bindParam(':DateFin',$Fin,PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$Total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Nom = $donnees['NomUser'].' '.$donnees['PrenomUser'];
			$Libelle = $donnees['Libelle'];
			$Libelleanneescolaire = $donnees['Libelleanneescolaire'];
			$Montant = $donnees['Montant'];
			$IdPaiementFrais = $donnees['IdPaiementFrais'];
			$Ideleve = $donnees['Ideleve'];
			$Nomeleve = $donnees['Nomeleve'];
			$PrenomEleve = $donnees['PrenomEleve'];
			$CodeClasse = $donnees['CodeClasse'];
			$Datepaiement = $donnees['Datepaiement'];
			$tab = explode("-",$Datepaiement);
			$Total=$Total+$Montant;
			?>
			<tr>
				<td>
					<a><?php echo $Nomeleve.' '.$PrenomEleve;?></a>
					<input type="hidden" name="IdPaiementFrais<?php echo $ligne;?>" value="<?php echo $IdPaiementFrais;?>"/>
			    </td>
				<td>
					<a><?php echo $CodeClasse;?></a>
			    </td>
				<td>
					<a><?php echo number_format($Montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $Libelle;?></a>
			    </td>
			    <td>
					<a><?php echo $Libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $Nom;?></a>
			    </td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs" onclick='window.open("ImprimeRecu.php?&idpaiementfrais=<?php echo $IdPaiementFrais;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						<span class="fa fa-print"></span> Imprimer Recu
					</a></div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?> 
		<tr>
			<td>
				<a style="text-align:center;font-weight:bold;color:#000;font-size:16px;">
					&nbsp;&nbsp;TOTAL PAIEMENT
				</a>
			</td>
			<td></td>
			<td>
				<a style="text-align:center;font-weight:bold;color:#000;font-size:16px;">
					<?php echo number_format($Total,"0",""," ");?>&nbsp;&nbsp;FCFA
				</a>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<input type="hidden" name="NbrePaiement" value="<?php echo $ligne;?>"/>
	<input type="hidden" name="TotalPaiement" value="<?php echo $Total;?>"/>
	<?php
	
	$req=(' SELECT  
					 distinct
					 article.id AS id,
					 article.nom AS nom,
					 article.prixunitaire AS prixunitaire,
					 article.qtedispo AS qtedispo,
					 article.statut AS statut,
					 articlecategorie.libelle AS libellecategorie,
					 articlesortie.id AS idarticlesortie,
					 articlesortie.num_recu AS num_recu,
					 articlesortie.date_sortie AS date_sortie,
					 articlesortie_article.qte_sortie AS qte_sortie,
					 articlesortie_article.montant AS montant,
					 articlesortie_article.id AS idarticlesortie_article,
					 utilisateur.nom_user as NomUser,
					 utilisateur.prenom_user as PrenomUser,
					 classe.codeclasse as CodeClasse,
					 eleve.id_eleve as Ideleve,
					 eleve.nom_eleve as Nomeleve,
					 eleve.prenom_eleve as PrenomEleve,
					 anneescolaire.libelle as Libelleanneescolaire
				
			FROM articlecategorie,article,articlesortie_article,articlesortie,utilisateur,eleve,eleveanneescolaire,anneescolaire,classe
			WHERE
			article.idcategorie=articlecategorie.id
			AND
			article.id=articlesortie_article.idarticle
			AND
			articlesortie_article.id_articlesortie=articlesortie.id
			AND
			articlesortie.id_user=utilisateur.id
			AND
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			AND
			articlesortie.id_eleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			articlesortie.date_sortie between :DateDebut AND :DateFin
			AND
			articlesortie.id NOT IN (SELECT caissepaiement.idpaiementfrais FROM caissepaiement WHERE caissepaiement.type="VenteArticle")
			
			ORDER BY article.nom asc');	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;"> 
				<th style="width:15%">Nom & pr&eacute;nom</th>
				<th style="width:12%">Classe</th>
				<th style="width:15%">Montant pay&eacute; (FCFA)</th>
				<th style="width:10%">Date vente</th>
				<th style="width:10%">Type d'article(s)</th>
				<th style="width:10%">Ann&eacute;e scolaire</th>
				<th style="width:10%">Enregistr&eacute; par</th>
				<th style="width:8%">Action(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':DateDebut',$Debut,PDO::PARAM_STR);
		$stmt->bindParam(':DateFin',$Fin,PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$Total_=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$NomUser = $donnees['NomUser'].' '.$donnees['PrenomUser'];
			$Libelleanneescolaire = $donnees['Libelleanneescolaire'];
			$montant = $donnees['montant'];
			$idarticlesortie = $donnees['idarticlesortie'];
			$nom = $donnees['nom'];
			$num_recu = $donnees['num_recu'];
			$Ideleve = $donnees['Ideleve'];
			$Nomeleve = $donnees['Nomeleve'];
			$PrenomEleve = $donnees['PrenomEleve'];
			$CodeClasse = $donnees['CodeClasse'];
			$date_sortie = $donnees['date_sortie'];
			$tab = explode("-",$date_sortie);
			$Total_=$Total_+$montant;
			?>
			<tr>
				<td>
					<a><?php echo $Nomeleve.' '.$PrenomEleve;?></a>
					<input type="hidden" name="idarticlesortie<?php echo $ligne;?>" value="<?php echo $idarticlesortie;?>"/>
			    </td>
				<td>
					<a><?php echo $CodeClasse;?></a>
			    </td>
				<td>
					<a><?php echo number_format($montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $nom;?></a>
			    </td>
			    <td>
					<a><?php echo $Libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $NomUser;?></a>
			    </td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs" onclick='window.open("ImprimeRecu_1.php?&id=<?php echo $idarticlesortie.'*'.$Ideleve.'*'.$num_recu.'*'.$date_sortie.'*'.$Montant;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						---> Imprimer Recu.
					</a></div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?> 
		<tr>
			<td>
				<a style="text-align:center;font-weight:bold;color:#000;font-size:16px;">
					&nbsp;&nbsp;TOTAL VENTE
				</a>
			</td>
			<td></td>
			<td>
				<a style="text-align:center;font-weight:bold;color:#000;font-size:16px;">
					<?php echo number_format($Total_,"0",""," ");?>&nbsp;&nbsp;FCFA
				</a>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<input type="hidden" name="NbreVente" value="<?php echo $ligne;?>"/>
	<input type="hidden" name="TotalVente" value="<?php echo $Total_;?>"/>
	<?php
}

function ListeEntreeSortie($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					compte.id as idCompte,
					compte.libelle as LibelleCompte,
					entreesortie.id as idEntreeSortie,
					entreesortie.libelle as libelleEntreeSortie,
					entreesortie.montant as MontantEntreeSortie,
					entreesortie.dateoperation as dateEntreeSortie,
					entreesortie.idtypeentreesortie as idTypeEntreeSortie,
					entreesortie.ficheattache as FicheAttache,
					entreesortie.datesaisie as DateSaisie,
				    user.nom_user as NomUser,
				    user.prenom_user as PrenomUser,
				    user2.nom_user as NomUser2,
				    user2.prenom_user as PrenomUser2,
					anneescolaire.id as idAnneeScolaire,
					anneescolaire.libelle as LibelleAnneeScolaire,
					soustypeoperation.libelle as SousTypeOperation
					
			FROM 	compte,entreesortie left join soustypeoperation on entreesortie.idsoustypeentreesortie=soustypeoperation.id,utilisateur as user,utilisateur as user2,anneescolaire
			
			WHERE
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.id=entreesortie.idanneescolaire
			AND
			compte.id=entreesortie.comptemouvement
			AND
			entreesortie.iduserajout=user.id
			AND
			entreesortie.iduserauto=user2.id
			AND
			entreesortie.statut=1

			ORDER BY entreesortie.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:11%;font-weight:bold;color:#000">Ann&eacute;e scol.</th>
				<th style="width:12%;font-weight:bold;color:#000">Type op&eacute;ration</th>
				<th style="width:20%;font-weight:bold;color:#000">Libell&eacute; op&eacute;ration</th>
				<th style="width:10%;font-weight:bold;color:#000">Montant</th>
				<th style="width:10%;font-weight:bold;color:#000">Date op&eacute;ration</th>
				<th style="width:12%;font-weight:bold;color:#000">Compte op&eacute;ration</th>
				<th style="width:10%;font-weight:bold;color:#000">Solde compte</th>
				<th style="width:10%;font-weight:bold;color:#000" align="center">Pi&egrave;ce justificative</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idCompte = $donnees['idCompte'];
			$SousTypeOperation = $donnees['SousTypeOperation'];
			$idAnneeScolaire = $donnees['idAnneeScolaire'];
			$LibelleAnneeScolaire = $donnees['LibelleAnneeScolaire'];
			$LibelleCompte = $donnees['LibelleCompte'];
			$idEntreeSortie = $donnees['idEntreeSortie'];
			$libelleEntreeSortie = $donnees['libelleEntreeSortie'];
			$MontantEntreeSortie = str_replace(" ","",$donnees['MontantEntreeSortie']);
			$DateSaisie = $donnees['DateSaisie'];
			$dateEntreeSortie = $donnees['dateEntreeSortie'];
			$tab = explode("-",$dateEntreeSortie);
			$Nom = $donnees['NomUser'].' '.$donnees['PrenomUser'];
			$Nom2 = $donnees['NomUser2'].' '.$donnees['PrenomUser2'];
			$idTypeEntreeSortie = $donnees['idTypeEntreeSortie'];
			$FicheAttache = $donnees['FicheAttache'];
			if($idTypeEntreeSortie==2)
			{
				$TypeEntreeSortie="Sortie";
			}
			else
			{
				$TypeEntreeSortie="Entr&eacute;e";
			}
			$etatCompteAcuel=getEntreeCompte($idAnneeScolaire,$idCompte,$DateSaisie,$pdo)-getSortieCompte($idAnneeScolaire,$idCompte,$DateSaisie,$pdo);
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="idEntreeSortie<?php echo $ligne;?>" value="<?php echo $idEntreeSortie;?>"/>
				</td>
				<td>
					<a><?php echo $LibelleAnneeScolaire;?></a>
			    </td>
				<td>
					<a><?php echo $SousTypeOperation;?></a>
			    </td>
				<td>
					<a><?php echo $libelleEntreeSortie;?></a>
			    </td>
				<td>
					<a><?php echo number_format($MontantEntreeSortie,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $LibelleCompte;?></a>
			    </td>
				<td>
					<a><?php echo number_format($etatCompteAcuel,"0",""," ");?></a>
			    </td>
				<td align="center">
					<?php
					$dossier = 'entreesorties/';
					if($FicheAttache!="")
					{
						if(file_exists($dossier.$FicheAttache)) 
						{
							?><a href="entreesorties/<?php echo $FicheAttache;?>" target="_blank" class="btn btn-info btn-xs">[T&eacute;l&eacute;charger]</a><?php
						} 
						else 
						{
							?>
							<span class="btn btn-danger btn-xs" onclick="new PNotify({
                                  title: 'Oh Non!',
                                  text: 'La pi&egrave;ce justificative n a pas pu &ecirc;tre transf&eacute;r&eacute; sur le serveur. Veuillez la r&eacute; attacher',
                                  type: 'info',
                                  styling: 'bootstrap3',
                                  addclass: 'dark'
                              });">[Pi&egrave;ce non disponible]</span><?php
						}
					}
					?>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?> 
	</table>
	<input type="hidden" name="nbreEntreeSortie" value="<?php echo $ligne;?>"/><?php
}

function HistoriqueRecetteJournaliere($idAnneeScolaire,$pdo)
{
	$req=(" SELECT  distinct
	                caisse.id as idCaisse,
					caisse.debut as Debut,
					caisse.fin as Fin,
					anneescolaire.libelle as LibelleAnneeScolaire,
					concat(utilisateur.nom_user,' ',utilisateur.prenom_user) as Utilisateur,
					caisse.montant as Montant,
					caisse.type AS TypeCaisse

			FROM caisse,anneescolaire,utilisateur
			WHERE
			caisse.idanneescolaire=anneescolaire.id
			AND
			utilisateur.id=caisse.iduser
			AND
			anneescolaire.id=:idanneescolaire
			
			GROUP BY caisse.type
			
			ORDER BY caisse.id DESC");	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th> 
				<th style="width:15%;">Ann&eacute;e scolaire</th>
				<th style="width:15%;">Date d&eacute;but (Journée)</th>
				<th style="width:15%;">Date fin (Journée)</th>
				<th style="width:15%;">Type d'opération</th>
				<th style="width:15%;">Total arrêt&eacute; (FCFA)</th>
				<th style="width:15%;">Clôtur&eacute;er par</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idAnneeScolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$Debut = $donnees['Debut'];
			$tab = explode("-",trim($Debut));
			$DateDebut_ = $tab[2]."/".$tab[1]."/".$tab[0];
			$Fin = $donnees['Fin'];
			$tab = explode("-",trim($Fin));
			$DateFin_ = $tab[2]."/".$tab[1]."/".$tab[0];
			$Montant = $donnees['Montant'];
			$LibelleAnneeScolaire = $donnees['LibelleAnneeScolaire'];
			$Utilisateur = $donnees['Utilisateur'];
			$idCaisse = $donnees['idCaisse'];
			$TypeCaisse = $donnees['TypeCaisse'];
			if($TypeCaisse=="PaiementFraisScolarite")
			{
				
			}
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="idCaisse<?php echo $ligne;?>" value="<?php echo $idCaisse;?>"/>					
				</td>
				<td>
					<a><?php echo $LibelleAnneeScolaire;?></a>
			    </td>
				<td>
					<a><?php echo $DateDebut_;?></a>
			    </td>
				<td>
					<a><?php echo $DateFin_;?></a>
			    </td>
				<td>
					<a>
					<?php 
						if($TypeCaisse=="PaiementFraisScolarite")
						{
							?><a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> Frais de scolarite </a><?php
						}
						else
						{
							?><a href="#" class="btn btn-info btn-xs"><i class="fa fa-folder"></i> Vente d'article(s) </a><?php
						}
					?>
					</a>
			    </td>
			    <td>
					<a><?php echo number_format($Montant,"0",""," ");?></a>
			    </td>
			    <td>
					<a><?php echo $Utilisateur;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbreCaisse" value="<?php echo $ligne;?>"/>
	<?php
}

function ListePaiementFrais($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as Libelle,
					paiementfrais.montant as Montant,
					paiementfrais.date as Datepaiement,
					paiementfrais.id as Idpaiementfrais,
					utilisateur.nom_user as NomUser,
					utilisateur.prenom_user as PrenomUser,
					classe.codeclasse as CodeClasse,
					eleve.id_eleve as Ideleve,
					eleve.nom_eleve as Nomeleve,
					eleve.prenom_eleve as PrenomEleve,
					anneescolaire.libelle as Libelleanneescolaire
					
			FROM paiementtype,paiementtypeclasse,classe,paiementfrais,eleve,eleveanneescolaire,anneescolaire,utilisateur
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			AND
			paiementfrais.ideleveanneescolaire=eleveanneescolaire.id
			AND
			paiementtypeclasse.id=paiementfrais.idpaiementtypeclasse
			AND
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idclasse=classe.idclasse
			AND
			paiementfrais.iduserajout=utilisateur.id
			AND
			paiementfrais.idanneescolaire=anneescolaire.id
			AND
			paiementfrais.statut=1
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire

			ORDER BY paiementfrais.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color: #eee;">
			    <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:14px;">#</th> 
				<th style="width:15%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">NOM & PR&Eacute;NOM &Eacute;L&Egrave;VE</th>
				<th style="width:12%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">CLASSE</th>
				<th style="width:15%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">MONTANT PAY&Eacute; (FCFA)</th>
				<th style="width:10%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">DATE PAIEMENT</th>
				<th style="width:10%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">TYPE DE FRAIS</th>
				<th style="width:10%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">ANN&Eacute;E SCOLAIRE</th>
				<th style="width:10%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">ENREGISTR&Eacute; PAR</th>
				<th style="width:8%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-size:12px;">ACTION(S)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Nom = $donnees['NomUser'].' '.$donnees['PrenomUser'];
			$Libelle = $donnees['Libelle'];
			$Libelleanneescolaire = $donnees['Libelleanneescolaire'];
			$Montant = $donnees['Montant'];
			$Idpaiementfrais = $donnees['Idpaiementfrais'];
			$Ideleve = $donnees['Ideleve'];
			$Nomeleve = $donnees['Nomeleve'].' '.$donnees['PrenomEleve'];
			$CodeClasse = $donnees['CodeClasse'];
			$Datepaiement = $donnees['Datepaiement'];
			$tab = explode("-",$Datepaiement);
			$verifCaissePaiement = verifCaissePaiement($Idpaiementfrais,$pdo);
			?>
			<tr>
				<td>
				    <?php 
						if($verifCaissePaiement==0)
						{
							?><input class="flat" type="checkbox" name="idPaiementFrais<?php echo $ligne;?>" value="<?php echo $Idpaiementfrais;?>"/><?php
						}
						else
						{
							?><input class="flat" type="checkbox" disabled="disabled" name="idPaiementFrais<?php echo $ligne;?>" value="<?php echo $Idpaiementfrais;?>"/><?php
						}
                    ?>						
				</td>
				<td>
					<a><?php echo $Nomeleve;?></a>
			    </td>
				<td>
					<a><?php echo $CodeClasse;?></a>
			    </td>
				<td>
					<a><?php echo number_format($Montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]." ".$tab[1]." ".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $Libelle;?></a>
			    </td>
			    <td>
					<a><?php echo $Libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $Nom;?></a>
			    </td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs" onclick='window.open("ImprimeRecu.php?&Idpaiementfrais=<?php echo $Idpaiementfrais;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						---> Imprimer Recu.
					</a></div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbrePaiement" value="<?php echo $ligne;?>"/>
	<?php
}

function ScolariteParametres($pdo)
{
	$req=(' SELECT  
					distinct
					paiementtypeclasse.id as id,
					paiementtype.libelle as paiementtype,
					paiementtypeclasse.montant as montant,
					classe.codeclasse as codeclasse,
					anneescolaire.libelle as anneescolaire,
					paiementtypeclasse.statut as statut
					
			FROM    paiementtype,paiementtypeclasse,classe,anneescolaire
			WHERE
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idclasse=classe.idclasse
			AND
			paiementtypeclasse.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			paiementtypeclasse.ideleveanneescolaire is null
			
			ORDER BY paiementtypeclasse.id desc
		');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:20%;">Ann&eacute; scolaire</th>
		        <th style="width:20%;">Frais</th>  
				<th style="width:20%;">Classe</th>
				<th style="width:23%;">Montant &agrave; payer (FCFA)</th>
				<th style="width:10%;">Statut</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$paiementtype = $donnees['paiementtype'];
			$montant = $donnees['montant'];
			$codeclasse = $donnees['codeclasse'];
			$anneescolaire = $donnees['anneescolaire'];
			$statut = $donnees['statut'];
			if($statut==1)
			{
				$etat="Actif";
			}
			else
			{
				$etat="<font color='red'>Desactiv&eacute;</font>";
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id.'*'.$statut;?>"/>
				</td>
				<td>
				    <a><?php echo $anneescolaire;?></a>
				</td>
				<td>
				    <a><?php echo $paiementtype;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo number_format($montant,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo $etat;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrefraisscolarite" value="<?php echo $ligne;?>"/><?php
}

function DossierEleveParClasse($idAnneeScolaire,$idClasse,$idSalle,$sexeEleve,$idEleveStatutClasse,$idEleveStatutEtablissement,$pdo)
{
	
	if($idAnneeScolaire!="" AND $idClasse!="" AND $idSalle=="" AND $sexeEleve=="" AND $idEleveStatutClasse=="" AND $idEleveStatutEtablissement=="")
	{
		$req=(' SELECT  distinct
				eleve.nom_eleve as nomEleve,
				eleve.prenom_eleve as prenomEleve,
				eleve.sexe_eleve as sexeEleve,
				eleve.etat_eleve as etatEleve,	
				eleve.datenaissance_eleve as datenaissanceEleve,					
				anneescolaire.libelle as Libelle,
				salle.codesalle as codeSalle,
				classe.codeclasse as codeClasse,
				eleveanneescolaire.statut as Statut,
				eleveanneescolaire.inscrit as Inscrit,
				eleveanneescolaire.etat as etat,
				elevestatutclasse.libelle as elevestatutclasse,
				elevestatutetablissement.libelle as elevestatutetablissement
				
		FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,classe,elevestatutclasse,elevestatutetablissement
		WHERE
		eleve.id_eleve=eleveanneescolaire.ideleve
		AND
		eleveanneescolaire.idanneescolaire=anneescolaire.id
		AND
		eleveanneescolaire.inscrit=elevestatutetablissement.id
		AND
		eleveanneescolaire.etat=elevestatutclasse.id
		AND
		anneescolaire.id=:idAnneeScolaire
		AND
		eleveanneescolaire.id=elevesalle.ideleve
		AND
		elevesalle.idsalle=salle.id
		AND
		salle.idclasse=classe.idclasse
		AND
		classe.idclasse=:idClasse

		ORDER BY  eleve.nom_eleve,eleve.prenom_eleve asc');	
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
		$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
		$stmt->execute();
	}
	elseif($idAnneeScolaire!="" AND $idClasse!="" AND $idSalle!="" AND $sexeEleve=="" AND $idEleveStatutClasse=="" AND $idEleveStatutEtablissement=="")
	{
		$req=(' SELECT  distinct
				eleve.nom_eleve as nomEleve,
				eleve.prenom_eleve as prenomEleve,
				eleve.sexe_eleve as sexeEleve,
				eleve.etat_eleve as etatEleve,	
				eleve.datenaissance_eleve as datenaissanceEleve,					
				anneescolaire.libelle as Libelle,
				salle.codesalle as codeSalle,
				classe.codeclasse as codeClasse,
				eleveanneescolaire.statut as Statut,
				eleveanneescolaire.inscrit as Inscrit,
				eleveanneescolaire.etat as etat,
				elevestatutclasse.libelle as elevestatutclasse,
				elevestatutetablissement.libelle as elevestatutetablissement

		FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,classe,elevestatutclasse,elevestatutetablissement
		WHERE
		eleve.id_eleve=eleveanneescolaire.ideleve
		AND
		eleveanneescolaire.idanneescolaire=anneescolaire.id
		AND
		eleveanneescolaire.inscrit=elevestatutetablissement.id
		AND
		eleveanneescolaire.etat=elevestatutclasse.id
		AND
		anneescolaire.id=:idAnneeScolaire
		AND
		eleveanneescolaire.id=elevesalle.ideleve
		AND
		elevesalle.idsalle=salle.id
		AND
		salle.idclasse=classe.idclasse
		AND
		classe.idclasse=:idClasse
		AND
		salle.id=:idSalle

		ORDER BY  eleve.nom_eleve,eleve.prenom_eleve asc');	
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idSalle', $idSalle, PDO::PARAM_INT);
		$stmt->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
		$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
		$stmt->execute();
	}
	elseif($idAnneeScolaire!="" AND $idClasse!="" AND $idSalle!="" AND $sexeEleve!="" AND $idEleveStatutClasse=="" AND $idEleveStatutEtablissement=="")
	{
		$req=(' SELECT  distinct
				eleve.nom_eleve as nomEleve,
				eleve.prenom_eleve as prenomEleve,
				eleve.sexe_eleve as sexeEleve,
				eleve.etat_eleve as etatEleve,	
				eleve.datenaissance_eleve as datenaissanceEleve,					
				anneescolaire.libelle as Libelle,
				salle.codesalle as codeSalle,
				classe.codeclasse as codeClasse,
				eleveanneescolaire.statut as Statut,
				eleveanneescolaire.inscrit as Inscrit,
				eleveanneescolaire.etat as etat,
				elevestatutclasse.libelle as elevestatutclasse,
				elevestatutetablissement.libelle as elevestatutetablissement

		FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,classe,elevestatutclasse,elevestatutetablissement
		WHERE
		eleve.id_eleve=eleveanneescolaire.ideleve
		AND
		eleveanneescolaire.idanneescolaire=anneescolaire.id
		AND
		eleveanneescolaire.inscrit=elevestatutetablissement.id
		AND
		eleveanneescolaire.etat=elevestatutclasse.id
		AND
		anneescolaire.id=:idAnneeScolaire
		AND
		eleveanneescolaire.id=elevesalle.ideleve
		AND
		elevesalle.idsalle=salle.id
		AND
		salle.idclasse=classe.idclasse
		AND
		classe.idclasse=:idClasse
		AND
		salle.id=:idSalle
		AND
		eleve.sexe_eleve=:sexeEleve

		ORDER BY  eleve.nom_eleve,eleve.prenom_eleve asc');	
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idSalle', $idSalle, PDO::PARAM_INT);
		$stmt->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
		$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
		$stmt->bindParam(':sexeEleve', $sexeEleve, PDO::PARAM_STR);
		$stmt->execute();
	}
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:10%;font-weight:bold;color:#000;">Ann&eacute;e Scol.</th>
				<th style="width:9%;font-weight:bold;color:#000;">Classe</th>
				<th style="width:9%;font-weight:bold;color:#000;">Salle</th>
				<th style="width:14%;font-weight:bold;color:#000;">Nom</th>
				<th style="width:13%;font-weight:bold;color:#000;">Pr&eacute;nom</th>
				<th style="width:9%;font-weight:bold;color:#000;">Date Naiss.</th>
				<th style="width:9%;font-weight:bold;color:#000;">Sexe</th>
				<th style="width:10%;font-weight:bold;color:#000;">Statut en cours d'ann&eacute;e scol.</th>
				<th style="width:9%;font-weight:bold;color:#000;">Statut dans la classe</th>
				<th style="width:9%;font-weight:bold;color:#000;">Statut dans l'&eacute;tabliss.</th>
		    </tr>
	    </thead>
		<?php			
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$nomEleve = $donnees['nomEleve'];
			$prenomEleve = $donnees['prenomEleve'];
			$sexeEleve = $donnees['sexeEleve'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$datenaissanceEleve = $donnees['datenaissanceEleve'];
			if($datenaissanceEleve!="")
			{
				$tab = explode("-",trim($datenaissanceEleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissanceEleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$codeSalle = $donnees['codeSalle'];
			$codeClasse = $donnees['codeClasse'];
			$Statut = $donnees['Statut'];
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			?>
			<tr>
				
				<td>
					<a><?php echo $Libelle;?></a>
				</td>
				<td>
					<a><?php echo $codeClasse;?></a>
				</td>
				<td>
					<a><?php echo $codeSalle;?></a>
				</td>
				<td>
					<a><?php echo $nomEleve;?></a>
				</td>
				<td>
					<a><?php echo $prenomEleve;?></a>
				</td>
				<td>
					<a><?php echo $datenaissanceEleve;?></a>
				</td>
				<td>
					<a><?php echo $sexeEleve;?></a>
				</td>
				<td>
					<a>
					<?php 
						if($Statut==0)
						{
							echo "Abandon";
						}
						else
						{
							echo "Actif";
						}
					?>
					</a>
				</td>
				<td>
					<a><?php echo $elevestatutclasse;?></a>
				</td>
				<td>
					<a><?php echo $elevestatutetablissement;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ListEleveSalle($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:19%;">Note int&eacute;rro. N° 1</th>
				<th style="width:19%;">Note int&eacute;rro. N° 2</th>
				<th style="width:19%;">Note de devoir</th>
				<th style="width:19%;">Note composition</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			
			$inte = getNoteInt($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$ds = getNoteDs($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$dn = getNoteDn($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$comp = getNoteComp($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom_eleve.' '.$prenom_eleve;?></a>
				</td>
				<td>
					<input type="number" autocomplete="off" id="noteint_1<?php echo $ligne;?>" name="noteint_1<?php echo $ligne;?>" value="<?php echo $inte;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="number" autocomplete="off" id="noteint_2<?php echo $ligne;?>" name="noteint_2<?php echo $ligne;?>" value="<?php echo $ds;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="number" autocomplete="off" id="noteds<?php echo $ligne;?>" name="noteds<?php echo $ligne;?>" value="<?php echo $dn;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="number" autocomplete="off" id="notecomp<?php echo $ligne;?>" name="notecomp<?php echo $ligne;?>" value="<?php echo $comp;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListEleveSalleCycleSup($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:23%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:23%;">Note Devoir N° 1</th>
				<th style="width:23%;">Note Devoir N° 2</th>
				<th style="width:23%;">Note Exam</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			
			$inte = "";
			$ds = getNoteDs($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$dn = getNoteDn($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$comp = getNoteComp($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom_eleve.' '.$prenom_eleve;?></a>
				</td>
				<td>
					<input type="number" autocomplete="off" id="noteint_2<?php echo $ligne;?>" name="noteint_2<?php echo $ligne;?>" value="<?php echo $ds;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="number" autocomplete="off" id="noteds<?php echo $ligne;?>" name="noteds<?php echo $ligne;?>" value="<?php echo $dn;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="number" autocomplete="off" id="notecomp<?php echo $ligne;?>" name="notecomp<?php echo $ligne;?>" value="<?php echo $comp;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListEleveSalle_($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle,
					numero_evaluation.numero_anonymat as numero_anonymat

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,numero_evaluation
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:10%;">N° Anonymat</th>
				<th style="width:26%;">Nom</th>
				<th style="width:33%;">Pr&eacute;nom(s)</th>
				<th style="width:23%;">Note/20</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$numero_anonymat = $donnees['numero_anonymat'];
			$comp = getNoteComp($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $numero_anonymat;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<input type="number" autocomplete="off" id="notecomp<?php echo $ligne;?>" name="notecomp<?php echo $ligne;?>" value="<?php echo $comp;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function getRangMatierePosition($idposition,$idanneescolaire,$idmatiere,$idsalle,$pdo)
{
	$req=(' SELECT  
					elevesalle.id as idelevesalle,
					note.id as idnote,
					note.moyen as moyen
					
			FROM note,elevesalle
			WHERE
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			elevesalle.id=noteideleve
			AND
			elevesalle.idsalle=:idsalle

			ORDER BY  note.moyen desc'
		);
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
		$ligne++;
	    $idnote = $donnees['idnote'];
		//
		$rq='  UPDATE note
				SET 
				note.rang=:rang
				WHERE
				note.id=:idnote';
					
		$stmt_ = $pdo->prepare($rq);
		$stmt_ ->bindParam(':idnote', $idnote, PDO::PARAM_INT);
		$stmt_ ->bindParam(':rang', $ligne, PDO::PARAM_INT);
		$stmt_->execute();			
		$stmt_->closeCursor();
		$stmt_=NULL;
	}
	
	$stmt->closeCursor();
	$stmt=NULL;
}

//
function SyntheseNotes($pdo)
{
	$req=(' SELECT  distinct 
	                anneescolaire.libelle AS Anneescolaire,
	                position.libposition AS Position,
					salle.codesalle AS Salle,
					matiere.code_matiere AS Matiere,
					count(note.id) AS nbreNote,
					note.idanneescolaire AS idanneescolaire,
					note.idsalle AS idsalle,
					note.idposition AS idposition,
                    note.idmatiere AS idmatiere,
					professeur.nom AS nom
					
			FROM anneescolaire,salle,position,matiere,note,professeur
			WHERE
			anneescolaire.id=note.idanneescolaire
			AND
			salle.id=note.idsalle
			AND
			position.idposition=note.idposition
			AND
			matiere.id_matiere=note.idmatiere
			AND
			note.idprofesseur=professeur.id
			AND
			anneescolaire.statut=1

			GROUP BY note.idanneescolaire,note.idsalle,note.idposition,note.idmatiere,professeur.nom');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:10%;font-weight:bold;color:#000">Ann&eacute;e Scolaire</th>
				<th style="width:15%;font-weight:bold;color:#000">P&eacute;riode</th>
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:15%;font-weight:bold;color:#000">Mati&egrave;re</th>
				<th style="width:15%;font-weight:bold;color:#000">Professeur</th>
				<th style="width:15%;font-weight:bold;color:#000">Progression d'enreg.</th>
				<th style="width:10%;font-weight:bold;color:#000">Fiche note(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];
			$Matiere = $donnees['Matiere'];
			$nbreNote = $donnees['nbreNote'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			$idmatiere = $donnees['idmatiere'];
			$nom = $donnees['nom'];
			$sallenbreeleve = getSalleNbreEleve($idanneescolaire,$idsalle,$pdo);
			$pourcentage = round(($nbreNote/$sallenbreeleve)*100); 
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/>
				</td>
				<td>
					<a><?php echo $Anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>
				<td>
					<a><?php echo $Salle;?></a>
				</td>
				<td>
					<a><?php echo $Matiere;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td class="project_progress">
	                <div class="progress progress_sm">
	                  	<div class="progress-bar bg-green" role="progressbar" 
	                  	data-transitiongoal="<?php echo $pourcentage;?>"></div>
	                </div>
	                <center><?php echo $pourcentage;?> %</center>
             	</td>
				<td>
	                <a href="#" class="btn btn-round btn-info" onclick='window.open("ImprimeFicheNotesMatiere.php?&matiere=<?php echo $Matiere;?>&nom=<?php echo $nom;?>&idposition=<?php echo $idposition;?>&idmatiere=<?php echo $idmatiere;?>&codesalle=<?php echo $Salle;?>&idsalle=<?php echo $idsalle;?>&idanneescolaire=<?php echo $idanneescolaire;?>","", "fullscreen=yes, scrollbars=auto");'>
						<i class="fa fa-print"></i> Imprimer
					</a>
             	</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresallenote" value="<?php echo $ligne;?>"/>
	<?php
}

//
function SyntheseNotesCycleSup($pdo)
{
	$req=(' SELECT  distinct 
	                anneescolaire.libelle AS Anneescolaire,
	                position.libposition AS Position,
					salle.codesalle AS Salle,
					matiere.code_matiere AS Matiere,
					count(note.id) AS nbreNote,
					note.idanneescolaire AS idanneescolaire,
					note.idsalle AS idsalle,
					note.idposition AS idposition,
                    note.idmatiere AS idmatiere,
					professeur.nom AS nom
					
			FROM anneescolaire,salle,position,matiere,note,professeur
			WHERE
			anneescolaire.id=note.idanneescolaire
			AND
			salle.id=note.idsalle
			AND
			position.idposition=note.idposition
			AND
			matiere.id_matiere=note.idmatiere
			AND
			note.idprofesseur=professeur.id
			AND
			anneescolaire.statut=1

			GROUP BY note.idanneescolaire,note.idsalle,note.idposition,note.idmatiere,professeur.nom');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:12%;font-weight:bold;color:#000">Ann&eacute;e Scol.</th>
				<th style="width:13%;font-weight:bold;color:#000">P&eacute;riode</th>
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:15%;font-weight:bold;color:#000">Mati&egrave;re</th>
				<th style="width:15%;font-weight:bold;color:#000">Professeur</th>
				<th style="width:15%;font-weight:bold;color:#000">Prog. d'enreg.</th>
				<th style="width:10%;font-weight:bold;color:#000">Fiche note(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];
			$Matiere = $donnees['Matiere'];
			$nbreNote = $donnees['nbreNote'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			$idmatiere = $donnees['idmatiere'];
			$nom = $donnees['nom'];
			$sallenbreeleve = getSalleNbreEleve($idanneescolaire,$idsalle,$pdo);
			$pourcentage = round(($nbreNote/$sallenbreeleve)*100); 
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/>
				</td>
				<td>
					<a><?php echo $Anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>
				<td>
					<a><?php echo $Salle;?></a>
				</td>
				<td>
					<a><?php echo $Matiere;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td class="project_progress">
	                <div class="progress progress_sm">
	                  	<div class="progress-bar bg-green" role="progressbar" 
	                  	data-transitiongoal="<?php echo $pourcentage;?>"></div>
	                </div>
	                <center><?php echo $pourcentage;?> %</center>
             	</td>
				<td>
	                <a href="#" class="btn btn-round btn-info" onclick='window.open("ImprimeFicheNotesMatiere.php?&matiere=<?php echo $Matiere;?>&nom=<?php echo $nom;?>&idposition=<?php echo $idposition;?>&idmatiere=<?php echo $idmatiere;?>&codesalle=<?php echo $Salle;?>&idsalle=<?php echo $idsalle;?>&idanneescolaire=<?php echo $idanneescolaire;?>","", "fullscreen=yes, scrollbars=auto");'>
						<i class="fa fa-print"></i> Imprimer
					</a>
             	</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresallenote" value="<?php echo $ligne;?>"/>
	<?php
}

//
function SyntheseNotesProf($idprof,$pdo)
{
	
	$req=(' SELECT  distinct 
	                anneescolaire.libelle as Anneescolaire,
	                position.libposition as Position,
					salle.codesalle as Salle,
					matiere.code_matiere as Matiere,
					count(note.id) as nbreNote,
					note.idanneescolaire as idanneescolaire,
					note.idsalle as idsalle,
					note.idposition as idposition,
                    note.idmatiere as idmatiere,
                    professeur.nom AS nom
					
			FROM anneescolaire,salle,position,matiere,note,professeur
			WHERE
			anneescolaire.id=note.idanneescolaire
			AND
			salle.id=note.idsalle
			AND
			position.idposition=note.idposition
			AND
			matiere.id_matiere=note.idmatiere
			AND
			note.idprofesseur=:idprof
			AND
			anneescolaire.statut=1
			AND
			note.idprofesseur=professeur.id

			GROUP BY note.idanneescolaire,note.idsalle,note.idposition,note.idmatiere'
		);	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:10%;font-weight:bold;color:#000">Ann&eacute;e Scolaire</th>
				<th style="width:15%;font-weight:bold;color:#000">P&eacute;riode</th>
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:15%;font-weight:bold;color:#000">Mati&egrave;re</th>
				<th style="width:15%;font-weight:bold;color:#000">Professeur</th>
				<th style="width:15%;font-weight:bold;color:#000">Progression d'enreg.</th>
				<th style="width:10%;font-weight:bold;color:#000">Fiche note(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idprof',$idprof,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];
			$Matiere = $donnees['Matiere'];
			$nbreNote = $donnees['nbreNote'];
			
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			$idmatiere = $donnees['idmatiere'];
            $nom = $donnees['nom'];
			$sallenbreeleve = getSalleNbreEleve($idanneescolaire,$idsalle,$pdo);
			$pourcentage = round(($nbreNote/$sallenbreeleve)*100);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/>
				</td>
				<td>
					<a><?php echo $Anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>
				<td>
					<a><?php echo $Salle;?></a>
				</td>
				<td>
					<a><?php echo $Matiere;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td class="project_progress">
	                <div class="progress progress_sm">
	                  	<div class="progress-bar bg-green" role="progressbar" 
	                  	data-transitiongoal="<?php echo $pourcentage;?>"></div>
	                </div>
             	</td>
             	<td>
	                <a href="#" class="btn btn-round btn-info" onclick='window.open("ImprimeFicheNotesMatiere.php?&matiere=<?php echo $Matiere;?>&nom=<?php echo $nom;?>&idposition=<?php echo $idposition;?>&idmatiere=<?php echo $idmatiere;?>&codesalle=<?php echo $Salle;?>&idsalle=<?php echo $idsalle;?>&idanneescolaire=<?php echo $idanneescolaire;?>","", "fullscreen=yes, scrollbars=auto");'>
						<i class="fa fa-print"></i> Imprimer
					</a>
             	</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresallenote" value="<?php echo $ligne;?>"/><?php
}

//
function SyntheseNotesByClasse($idmatiere,$idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT  distinct 
					matiere.code_matiere,
					matiere.id_matiere
					
			FROM matiere,note
			WHERE
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			note.idsalle=:idsalle
			AND
			matiere.id_matiere=note.idmatiere

			ORDER BY matiere.code_matiere'
		);	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:95%;text-align:center">Mati&egrave;re</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$code_matiere = $donnees['code_matiere'];
			$id_matiere = $donnees['id_matiere'];
			?>
			<tr>
				<td>
				<?php
					if($idmatiere==$id_matiere)
					{
						?>
						<input class="flat" type="checkbox" checked="checked" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$id_matiere;?>"/><?php
					}
					else
					{
						?>
						<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$id_matiere;?>"/><?php
					}
                ?>				
				</td>
				<td>
					<a><?php echo $code_matiere;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresallenote" value="<?php echo $ligne;?>"/><?php
}

//
function SyntheseNotesByClasseProf($idmatiere,$idsalle,$idposition,$idanneescolaire,$idprof,$pdo)
{
	$req=(' SELECT  distinct 
					matiere.code_matiere,
					matiere.id_matiere
					
			FROM matiere,note
			WHERE
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			note.idsalle=:idsalle
			AND
			matiere.id_matiere=note.idmatiere
            AND
			note.idprofesseur=:idprof
			
			ORDER BY matiere.code_matiere'
		);	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:95%;text-align:center">Mati&egrave;re</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$code_matiere = $donnees['code_matiere'];
			$id_matiere = $donnees['id_matiere'];
			?>
			<tr>
				<td>
				<?php
					if($idmatiere==$id_matiere)
					{
						?>
						<input class="flat" type="checkbox" checked="checked" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$id_matiere;?>"/><?php
					}
					else
					{
						?>
						<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$id_matiere;?>"/><?php
					}
                ?>				
				</td>
				<td>
					<a><?php echo $code_matiere;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresallenote" value="<?php echo $ligne;?>"/><?php
}

//
function ListeNotesEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT  distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					elevesalle.id as idelevesalle,
					note.noteint as noteint,
					note.noteds as noteds,
					note.notedn as notedn,
					note.notecomp as notecomp
	
			FROM eleve,eleveanneescolaire,elevesalle,note
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.id=note.ideleve
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle

			ORDER BY eleve.nom_eleve'
		);	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr>
				<th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;">Nom & Pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:19%;">Note int&eacute;rro. N° 1</th>
				<th style="width:19%;">Note int&eacute;rro. N° 2</th>
				<th style="width:19%;">Moyenne des devoirs</th>
				<th style="width:19%;">Note composition</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$noteint = $donnees['noteint'];
			$noteds = $donnees['noteds'];
			$notedn = $donnees['notedn'];
			$notecomp = $donnees['notecomp'];

			?>
			<tr>
				<td>
				<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<input type="text" readonly="yes" id="noteint_1<?php echo $ligne;?>" name="noteint_1<?php echo $ligne;?>" value="<?php echo $noteint;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="text" readonly="yes" id="noteint_2<?php echo $ligne;?>" name="noteint_2<?php echo $ligne;?>" value="<?php echo $noteds;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="text" readonly="yes" id="noteds<?php echo $ligne;?>" name="noteds<?php echo $ligne;?>" value="<?php echo $notedn;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="text" readonly="yes" id="notecomp<?php echo $ligne;?>" name="notecomp<?php echo $ligne;?>" value="<?php echo $notecomp;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbreNotesEleveAfterAdd" value="<?php echo $ligne;?>"/><?php
}

//
function ListeNotesEleveAfterAddCycleSup($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT  distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle,
					note.noteint as noteint,
					note.noteds as noteds,
					note.notedn as notedn,
					note.notecomp as notecomp
	
			FROM eleve,eleveanneescolaire,elevesalle,note
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.id=note.ideleve
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle

			ORDER BY eleve.nom_eleve'
		);	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr>
				<th style="width:5%;text-align:center;">#</th> 
				<th style="width:23%;">Nom & Pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:23%;">Note Devoirs N° 1</th>
				<th style="width:23%;">Note Devoirs N° 2</th>
				<th style="width:23%;">Note Examen</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$noteint = "";
			$noteds = $donnees['noteds'];
			$notedn = $donnees['notedn'];
			$notecomp = $donnees['notecomp'];

			?>
			<tr>
				<td>
				<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<input type="text" readonly="yes" id="noteint_2<?php echo $ligne;?>" name="noteint_2<?php echo $ligne;?>" value="<?php echo $noteds;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="text" readonly="yes" id="noteds<?php echo $ligne;?>" name="noteds<?php echo $ligne;?>" value="<?php echo $notedn;?>" class="form-control col-md-7 col-xs-12"/>
				</td>
				<td>
					<input type="text" readonly="yes" id="notecomp<?php echo $ligne;?>" name="notecomp<?php echo $ligne;?>" value="<?php echo $notecomp;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbreNotesEleveAfterAdd" value="<?php echo $ligne;?>"/><?php
}

//
function ListeNotesEleveAfterAdd_($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	
	$req=(' SELECT  distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle,
					numero_evaluation.numero_anonymat as numero_anonymat,
					note.noteint as noteint,
					note.noteds as noteds,
					note.notedn as notedn,
					note.notecomp as notecomp
	
			FROM eleve,eleveanneescolaire,elevesalle,note,numero_evaluation
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.id=note.ideleve
			AND
			elevesalle.id=numero_evaluation.idEleveSalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle

			ORDER BY eleve.nom_eleve'
		);	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr>
				<th style="width:5%;text-align:center;">#</th> 
				<th style="width:10%;">N° Anonymat</th>
				<th style="width:26%;">Nom</th>
				<th style="width:33%;">Pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:19%;">Note/20</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$noteint = $donnees['noteint'];
			$noteds = $donnees['noteds'];
			$notedn = $donnees['notedn'];
			$notecomp = $donnees['notecomp'];
			$numero_anonymat = $donnees['numero_anonymat'];
			?>
			<tr>
				<td>
				<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $numero_anonymat;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<input type="text" readonly="yes" id="notecomp<?php echo $ligne;?>" name="notecomp<?php echo $ligne;?>" value="<?php echo $notecomp;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbreNotesEleveAfterAdd" value="<?php echo $ligne;?>"/><?php
}

//[PAIEMENT DES FRAIS DE SCOLARITE]
function PaiementEleve($ideleveanneescolaire,$boursier,$inscrit,$idclasse,$idanneescolaire,$pdo)
{
	if($boursier=="Oui")
	{
		$req=(' SELECT  
					distinct
	                paiementtype.id as idpaiementtype,
					paiementtype.libelle as libellepaiementtype,
					paiementtypeclasse.id as idpaiementtypeclasse,
					paiementtypeclasse.montant as montantclasse,
					paiementtypeclasse.remise as remise,
					eleveanneescolaire.inscrit as inscrit,
					eleveanneescolaire.boursier as boursier,
					anneescolaire.libelle as libelleanneescolaire,
					classe.codeclasse as codeclasse,
					eleveanneescolaire.id as ideleveanneescolaire,
					anneescolaire.id as idanneescolaire
					
			FROM eleve,eleveanneescolaire,paiementtype,paiementtypeclasse,classe,anneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=:ideleveanneescolaire
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			AND
			classe.idclasse=paiementtypeclasse.idclasse
			AND
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idanneescolaire=anneescolaire.id
			AND
			paiementtypeclasse.ideleveanneescolaire=:ideleveanneescolaire
			
			ORDER BY anneescolaire.id DESC');	
	}
	else
	{
		$req=(' SELECT  
						distinct
						paiementtype.id as idpaiementtype,
						paiementtype.libelle as libellepaiementtype,
						paiementtypeclasse.id as idpaiementtypeclasse,
						paiementtypeclasse.montant as montantclasse,
						paiementtypeclasse.remise as remise
						anneescolaire.libelle as libelleanneescolaire,
						classe.codeclasse as codeclasse,
						anneescolaire.id as idanneescolaire
					
			FROM paiementtype,paiementtypeclasse,classe,anneescolaire
			WHERE
			classe.idclasse=paiementtypeclasse.idclasse
			AND
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			classe.idclasse=:idclasse

			ORDER BY paiementtype.id ASC');
	}
    ?>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;" class="control-label col-md-12 col-sm-12 col-xs-12">
		<i>::: Veuillez cocher les frais &agrave; payer :::</i></label><br/><br/>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">#</th> 
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Ann&eacute;e scol.</th>
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Classe</th>
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Frais</th>
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">M. Associ&eacute;</th>
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">M. R&eacute;gl&eacute;</th>
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">M. Restant</th>
				<th style="width:13%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Montant</th>
		    </tr>
	    </thead>
		<tbody>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':boursier', $boursier, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$idpaiementtype = $donnees['idpaiementtype'];
			$libellepaiementtype = $donnees['libellepaiementtype'];
			$idpaiementtypeclasse = $donnees['idpaiementtypeclasse'];
			$remise = $donnees['remise'];
			if($remise==0 OR $remise=="")
			{
				$montantclasse = $donnees['montantclasse'];
			}
			else
			{
				$montantclasse = $donnees['montantclasse']*$remise/100;
			}
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$codeclasse = $donnees['codeclasse'];
			$idanneescolaire = $donnees['idanneescolaire'];
			//$montant = getMontantEleveAnneeScolaire($idpaiementtypeclasse,$ideleveanneescolaire,$pdo);
			$montant = getMontantEleveAnneeScolaire($idpaiementtype,$ideleveanneescolaire,$pdo);
			?>
			<tr>
				<td align="center">
					<?php 
						if($$remise==0 AND $idpaiementtype==3)
						{
							?>
							<input class="flat" disabled="disabled" type="checkbox" name="idpaiementtypeclasse<?php echo $ligne;?>" value="<?php echo $idpaiementtypeclasse;?>"/>
							<input type="hidden" name="ideleveanneescolaire<?php echo $ligne;?>" value="<?php echo $ideleveanneescolaire;?>"/>
							<input type="hidden" name="idanneescolaire<?php echo $ligne;?>" value="<?php echo $idanneescolaire;?>"/>
							<?php
						}
						else
						{
							?>
							<input class="flat" type="checkbox" name="idpaiementtypeclasse<?php echo $ligne;?>" value="<?php echo $idpaiementtypeclasse;?>"/>
							<input type="hidden" name="ideleveanneescolaire<?php echo $ligne;?>" value="<?php echo $ideleveanneescolaire;?>"/>
							<input type="hidden" name="idanneescolaire<?php echo $ligne;?>" value="<?php echo $idanneescolaire;?>"/>
							<?php
						}
					?>
				</td>
				<td align="center">
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td align="center">
					<a><?php echo $codeclasse;?></a>
				</td>
				<td align="center">
					<a><?php echo $libellepaiementtype;?></a>
				</td>
				<td align="center">
					<a><?php echo number_format($montantclasse,"0",""," ");?></a>
				</td>
				<td align="center">
					<a><?php echo number_format($montant,"0",""," ");?></a>
				</td>
				<td align="center">
					<a><?php echo number_format(($montantclasse-$montant),"0",""," ");?></a>
				</td>
				<td align="center">
					<div>
						<?php 
						if($inscrit==0 AND $idpaiementtype==3)
						{
							?><input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" disabled="disabled"/><?php
						}
						else
						{
							if($montantclasse==$montant)
							{
								?><input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" readonly="yes"/><?php
							}
							else
							{
								?><input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" autocomplete="off"/><?php
							}
						}
						?>
					</div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?></tbody>
	</table>
	<input type="hidden" name="nbrepaiementtype" value="<?php echo $ligne;?>"/><?php
}
//
function PaiementEleveSelected($idelevesalle,$id,$mont,$pdo)
{
	$req=(' SELECT  
	                paiementtype.id,
					paiementtype.libelle,
					paiementtypeclasse.montant as montantclasse
					
			FROM paiementtype,paiementtypeclasse,salle,elevesalle
			WHERE
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idclasse=salle.idclasse
			AND
			salle.id=elevesalle.idsalle
			AND
			elevesalle.id=:idelevesalle');	
    ?>
	<label class="control-label col-md-12 col-sm-12 col-xs-12"  style="text-align:left;color:#000;">
		<i>::: Veuillez choisir le service que l'&eacute;l&egrave;ve veut r&eacute;gler :::</i></label><br/><br/>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;text-align:center;">Type Service</th>
				<th style="width:19%;text-align:center;">M. Associ&eacute;</th>
				<th style="width:19%;text-align:center;">M. R&eacute;gl&eacute;</th>
				<th style="width:19%;text-align:center;">M. Restant</th>
				<th style="width:19%;text-align:center;">Montant</th>
		    </tr>
	    </thead>
		<tbody>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idpaiementtype = $donnees['id'];
			$libelle = $donnees['libelle'];
			$montantclasse = $donnees['montantclasse'];
			$montant = getMontantEleve($idpaiementtype,$idelevesalle,$pdo);
			if($idpaiementtype==$id)
			{
				?>
				<tr>
					<td align="center">
						<input class="flat" type="checkbox" name="idpaiementtype<?php echo $ligne;?>" 
						value="<?php echo $idpaiementtype;?>" checked="checked"/>
					</td>
					<td align="center">
						<a><?php echo $libelle;?></a>
					</td>
					<td align="center">
						<a><?php echo number_format($montantclasse,"0",""," ");?></a>
					</td>
					<td align="center">
						<a><?php echo number_format($montant,"0",""," ");?></a>
					</td>
					<td align="center">
						<a><?php echo number_format(($montantclasse-$montant),"0",""," ");?></a>
					</td>
					<td align="center">
						<div>
							<?php 
								if($montantclasse==$montant)
								{
									?><input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" readonly="yes"/><?php
								}
								else
								{
									?><input type="text" class="form-control" data-inputmask="'mask':'999999'" 
									name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" 
									value="<?php echo $mont;?>"/><?php
								}							
							?>
						</div>
					</td>
				</tr><?php
			}
			else
			{
				?>
				<tr>
					<td align="center">
						<input class="flat" type="checkbox" name="idpaiementtype<?php echo $ligne;?>" 
						value="<?php echo $idpaiementtype;?>" readonly="yes"/>
					</td>
					<td align="center">
						<a><?php echo $libelle;?></a>
					</td>
					<td align="center">
						<a><?php echo number_format($montantclasse,"0",""," ");?></a>
					</td>
					<td align="center">
						<a><?php echo number_format($montant,"0",""," ");?></a>
					</td>
					<td align="center">
						<a><?php echo number_format(($montantclasse-$montant),"0",""," ");?></a>
					</td>
					<td align="center">
						<div>
							<?php 
								if($montantclasse==$montant)
								{
									?><input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" readonly="yes"/><?php
								}
								else
								{
									?><input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" readonly="yes"/><?php
								}							
							?>
						</div>
					</td>
				</tr><?php
			}
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?></tbody>
	</table>
	<input type="hidden" name="nbre_paiement_type" value="<?php echo $ligne;?>"/><?php
}

function ListeEtudiantPaiementFrais($ideleveanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as libellePaiementType,
					paiementfrais.montant as Montant,
					paiementfrais.date as Date,
					paiementfrais.id as idPaiementFrais
					
			FROM paiementtype,paiementfrais,paiementtypeclasse
			WHERE
			paiementtypeclasse.id=paiementfrais.idpaiementtypeclasse
			AND
			paiementfrais.ideleveanneescolaire=:ideleveanneescolaire
			AND
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementfrais.statut=1

			ORDER BY paiementfrais.id DESC');	
    ?>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;" class="control-label col-md-12 col-sm-12 col-xs-12" >
		<i>::: Historique de paiement pour l'ann&eacute;e en cours:::</i></label><br/><br/>
	<table class="table table-striped table-bordered">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">#</th> 
				<th style="text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Montant Pay&eacute; (FCFA)</th>
				<th style="text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Date paiement</th>
				<th style="text-align:center;font-weight:bold;color:#000;font-family:comic sans ms;font-size:13px;">Type de Frais</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$libellePaiementType = $donnees['libellePaiementType'];
			$Montant = $donnees['Montant'];
			$idPaiementFrais = $donnees['idPaiementFrais'];
			$Date = $donnees['Date'];
			$tab = explode("-",$Date);
			?>
			<tbody>
				<tr>
				    <td>
						<input class="flat" type="checkbox" name="idpaiementfrais<?php echo $ligne;?>" value="<?php echo $idPaiementFrais;?>"/>						
					</td>
					<td>
						<a><?php echo number_format($Montant,"0",""," ");?></a>
				    </td>
					<td>
						<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
				    </td>
					<td>
						<a><?php echo $libellePaiementType;?></a>
				    </td>
				</tr>
			</tbody><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ListeVersement($pdo)
{
	$req=(" SELECT  distinct
					versement.num as numbordereau,
					modepaiement.designat as libellecompte,
					versement.id as idversement,
					versement.dateversement as dateversement,
					sum(paiementfrais.montant) as montantversement,
					concat(user.nom_user,' ',user.prenom_user) as nom,
					concat(user2.nom_user,' ',user2.prenom_user) as nom2

			FROM modepaiement,versement,versementdetail,paiementfrais,utilisateur as user,utilisateur as user2
			WHERE
			modepaiement.id=versement.idcompte
			AND
			versement.id=versementdetail.idversement
			AND
			versement.iduserajout=user.id
			AND
			versement.iduserauto=user2.id
			AND
			versement.statut=1
			AND
			versementdetail.idpaiementfrais=paiementfrais.id

			GROUP BY modepaiement.id DESC");	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:15%;text-align:center;">Compte</th>
				<th style="width:15%;text-align:center;">Montant vers&eacute;</th>
				<th style="width:15%;text-align:center;">Date versement</th>
				<th style="width:15%;text-align:center;">Num. bordereau</th>
				<th style="width:15%;text-align:center;">Vers&eacute; par</th>
				<th style="width:15%;text-align:center;">Enregistr&eacute; par</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$idversement = $donnees['idversement'];
			$numbordereau = $donnees['numbordereau'];
			$libellecompte = $donnees['libellecompte'];
			$montantversement = $donnees['montantversement'];
			$nom = $donnees['nom'];
			$nom2 = $donnees['nom2'];
			$dateversement = $donnees['dateversement'];
			$montantversement = $donnees['montantversement'];
			$tab = explode("-",$dateversement);
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="idversement<?php echo $ligne;?>" value="<?php echo $idversement;?>"/>					
				</td>
				<td>
					<a><?php echo $libellecompte;?></a>
			    </td>
			    <td>
					<a><?php echo number_format($montantversement,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
			    <td>
					<a><?php echo $numbordereau;?></a>
			    </td>
			    <td>
					<a><?php echo $nom2;?></a>
			    </td>
			    <td>
					<a><?php echo $nom;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbreVersement" value="<?php echo $ligne;?>"/>
	<?php
}
//
function ListePaiementFraisNonVerse($pdo)
{
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as Libelle,
					paiementfrais.montant as Montant,
					paiementfrais.date as Datepaiement,
					paiementfrais.id as Idpaiementfrais,
					salle.codesalle as Codesalle,
					eleve.id_eleve as Ideleve,
					eleve.nom_eleve as Nomeleve,
					anneescolaire.libelle as Libelleanneescolaire
					
			FROM paiementtype,paiementtypeclasse,salle,elevesalle,paiementfrais,eleve,eleveanneescolaire,anneescolaire
			WHERE
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.id=paiementfrais.idpaiementtype
			AND
			paiementfrais.idelevesalle=elevesalle.id
			AND
			elevesalle.idsalle=salle.id
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.ideleve=eleve.id_eleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			paiementfrais.id NOT IN
			(
				SELECT versementdetail.idpaiementfrais
				FROM versementdetail
			)

			ORDER BY paiementfrais.id DESC');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color: #eee;">
			    <th style="width:5%;text-align:center;">#</th>
				<th style="width:20%;">Nom & pr&eacute;nom de &eacute;l&egrave;ve</th>
				<th style="width:18%;">Classe</th>
				<th style="width:16%;">Montant Pay&eacute; (FCFA)</th>
				<th style="width:10%;">Date paiement</th>
				<th style="width:14%;">Type de Frais</th>
				<th style="width:14%;">Ann&eacute;e scolaire</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Libelle = $donnees['Libelle'];
			$Libelleanneescolaire = $donnees['Libelleanneescolaire'];
			$Montant = $donnees['Montant'];
			$Idpaiementfrais = $donnees['Idpaiementfrais'];
			$Ideleve = $donnees['Ideleve'];
			$Nomeleve = $donnees['Nomeleve'];
			$Codesalle = $donnees['Codesalle'];
			$Datepaiement = $donnees['Datepaiement'];
			$tab = explode("-",$Datepaiement);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" checked="checked" name="idpaiementfrais<?php echo $ligne;?>" value="<?php echo $Idpaiementfrais;?>"/>						
				</td>
				<td>
					<a><?php echo $Nomeleve;?></a>
			    </td>
				<td>
					<a><?php echo $Codesalle;?></a>
			    </td>
				<td>
					<a><?php echo number_format($Montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]." ".$tab[1]." ".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $Libelle;?></a>
			    </td>
			    <td>
					<a><?php echo $Libelleanneescolaire;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbrePaiement" value="<?php echo $ligne;?>"/>
	<?php
}
//
function ListePaiementFraisByVersement($idversement,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as Libelle,
					paiementfrais.montant as Montant,
					paiementfrais.date as Datepaiement,
					paiementfrais.id as Idpaiementfrais,
					salle.codesalle as Codesalle,
					eleve.id_eleve as Ideleve,
					eleve.nom_eleve as Nomeleve,
					anneescolaire.libelle as Libelleanneescolaire
					
			FROM paiementtype,paiementtypeclasse,salle,elevesalle,paiementfrais,eleve,eleveanneescolaire,anneescolaire,
				 versementdetail
			WHERE
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.id=paiementfrais.idpaiementtype
			AND
			paiementfrais.idelevesalle=elevesalle.id
			AND
			elevesalle.idsalle=salle.id
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.ideleve=eleve.id_eleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			paiementfrais.id=versementdetail.idpaiementfrais
			AND
			versementdetail.idversement=:idversement

			ORDER BY paiementfrais.id DESC');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color: #eee;">
			    <th style="width:5%;text-align:center;">#</th>
				<th style="width:20%;">Nom & pr&eacute;nom de &eacute;l&egrave;ve</th>
				<th style="width:18%;">Classe</th>
				<th style="width:16%;">Montant Pay&eacute; (FCFA)</th>
				<th style="width:10%;">Date paiement</th>
				<th style="width:14%;">Type de Frais</th>
				<th style="width:14%;">Ann&eacute;e scolaire</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idversement',$idversement,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Libelle = $donnees['Libelle'];
			$Libelleanneescolaire = $donnees['Libelleanneescolaire'];
			$Montant = $donnees['Montant'];
			$Idpaiementfrais = $donnees['Idpaiementfrais'];
			$Ideleve = $donnees['Ideleve'];
			$Nomeleve = $donnees['Nomeleve'];
			$Codesalle = $donnees['Codesalle'];
			$Datepaiement = $donnees['Datepaiement'];
			$tab = explode("-",$Datepaiement);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" checked="checked" name="idpaiementfrais<?php echo $ligne;?>" value="<?php echo $Idpaiementfrais;?>"/>						
				</td>
				<td>
					<a><?php echo $Nomeleve;?></a>
			    </td>
				<td>
					<a><?php echo $Codesalle;?></a>
			    </td>
				<td>
					<a><?php echo number_format($Montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]." ".$tab[1]." ".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $Libelle;?></a>
			    </td>
			    <td>
					<a><?php echo $Libelleanneescolaire;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="NbrePaiement" value="<?php echo $ligne;?>"/>
	<?php
}

//[DEPENSE]
function ListeDepense($pdo)
{
	$req=(' SELECT  
					depense.id as id,
	                depense.montant as montant,
					depense.motif as motif,
					depense.datedepense as datedepense,
				    modepaiement.designat as designat,
				    user.nom_user as nom_user,
				    user.prenom_user as prenom_user,
				    user2.nom_user as nom_user2,
				    user2.prenom_user as prenom_user2

			FROM depense,modepaiement,utilisateur as user,utilisateur as user2
			WHERE
			depense.statut=1
			AND
			depense.modregl=modepaiement.id
			AND
			depense.iduserajout=user.id
			AND
			depense.iduserauto=user2.id

			ORDER BY depense.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:30%;">Objet</th>
				<th style="width:12%;">Montant</th>
				<th style="width:12%;">Date d&eacute;pense</th>
				<th style="width:15%;">D&eacute;falqu&eacute; sur</th>
				<th style="width:13%;">Autoris&eacute;e par</th>
				<th style="width:13%;">Enregistr&eacute;e</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$designat = $donnees['designat'];
			$iddepense = $donnees['id'];
			$montant = $donnees['montant'];
			$motif = $donnees['motif'];
			$datedepense = $donnees['datedepense'];
			$tab = explode("-",$datedepense);
			$nom = $donnees['nom_user'].' '.$donnees['prenom_user'];
			$nom2 = $donnees['nom_user2'].' '.$donnees['prenom_user2'];
			?>
			<tr>
			    <td>
				<input class="flat" type="checkbox" name="iddepense<?php echo $ligne;?>" value="<?php echo $iddepense;?>"/>
				</td>
				<td>
					<a><?php echo $motif;?></a>
			    </td>
				<td>
					<a><?php echo number_format($montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $designat;?></a>
			    </td>
			    <td>
					<a><?php echo $nom;?></a>
			    </td>
			    <td>
					<a><?php echo $nom2;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?> 
	</table>
	<input type="hidden" name="nbreDepense" value="<?php echo $ligne;?>"/><?php
}

//[PRETS]
function ListePret($pdo)
{
	$req=(' SELECT  
	                professeur.nom,
					pret.id,
					pret.montant,
					pret.montantmensuel,
					pret.date,
					pret.datedebut,
					pret.datefin,
					modepaiement.designat,
					modepaiement.id as idmodepaiement,
					modepaiement.montant as montantmodepaiement

			FROM professeur,pret,modepaiement
			WHERE
			professeur.id=pret.idprof
			AND
			pret.statut=1
            AND
			pret.idmodepaiement=modepaiement.id
	
			ORDER BY pret.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:13%;">Personnel</th>
				<th style="width:13%;">Montant pret</th>
				<th style="width:13%;">Montant mensuel</th>
				<th style="width:13%;">Date</th>
				<th style="width:13%;text-align:center;">P&eacute;riode Remb.</th>
				<th style="width:13%;">Moyen d'octroi</th>
				<th style="width:13%;">Etat d'avancement</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idmodepaiement = $donnees['idmodepaiement'];
			$montantmodepaiement = $donnees['montantmodepaiement'];
			$designat = $donnees['designat'];
			$idpret = $donnees['id'];
			$nom = $donnees['nom'];
			$montant = $donnees['montant'];
			$montantmensuel = $donnees['montantmensuel'];
			$motif = $donnees['motif'];
			$date = $donnees['date'];
			$tab = explode("-",$date);
			
			$datedebut = $donnees['datedebut'];
			$tabdebut = explode("-",$datedebut);
			
			$datefin = $donnees['datefin'];
			$tabfin = explode("-",$datefin);

			?>
			<tbody>
				<tr>
				    <td>
					    <input class="flat" type="checkbox" name="idpret<?php echo $ligne;?>" value="<?php echo $idpret.'-'.$montant.'-'.$idmodepaiement.'-'.$designat.'-'.$montantmodepaiement;?>"/>
					</td>
					<td>
						<a><?php echo $nom;?></a>
				    </td>
					<td>
						<a><?php echo number_format($montant,"0",""," ");?></a>
				    </td>
					<td>
						<a><?php echo number_format($montantmensuel,"0",""," ");?></a>
				    </td>
					<td>
						<a><?php echo $tab[2]." ".$tab[1]." ".$tab[0];?></a>
				    </td>
					<td>
						<a>D&eacute;but : <?php echo $tabdebut[2]." ".$tabdebut[1]." ".$tabdebut[0];;?></a><br/>
						<small style="color:#ff0000;"> Fin : <?php echo $tabfin[2]." ".$tabfin[1]." ".$tabfin[0];;?></small>
				    </td>
					<td>
						<a><?php echo $designat;?></a>
				    </td>
					<td>
						<a></a>
				    </td>
				</tr>
			</tbody><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?><input type="hidden" name="nbre_pret" value="<?php echo $ligne;?>"/> 
	</table><?php
}

//[INSCRIPTION]
function ListeInscrit($pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
					eleve.etat_eleve as etat_eleve,
					eleveanneescolaire.id as id,
					classe.codeclasse as codeclasse,
					anneescolaire.libelle as libelle,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement
					
			FROM    eleve,eleveanneescolaire,anneescolaire,classe,elevestatutclasse,elevestatutetablissement
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.statut=1
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			AND
			eleveanneescolaire.id NOT IN 

			(SELECT elevesalle.ideleve FROM elevesalle)

			ORDER BY  eleveanneescolaire.id desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:10%;">Ann&eacute;e Scolaire</th>
				<th style="width:19%;">Nom</th>
				<th style="width:19%;">Pr&eacute;nom</th>
				<th style="width:10%;">Sexe</th>
				<th style="width:10%;">Etat</th>
				<th style="width:10%;">Classe</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$codeclasse = $donnees['codeclasse'];
			$libelle = $donnees['libelle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td>
					<a><?php echo $elevestatutclasse;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreinscrit" value="<?php echo $ligne;?>"/><?php
}

function ListeInscrit_($idclasse,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.sexe_eleve,
					eleve.etat_eleve,
					eleveanneescolaire.id as id,
					classe.codeclasse as codeclasse,
					anneescolaire.libelle as libelle,
					eleveanneescolaire.idclasse as idclasse
					
			FROM    eleve,eleveanneescolaire,anneescolaire,classe
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			AND
			eleveanneescolaire.statut=1
			AND
			classe.idclasse=:idclasse
			AND
			eleveanneescolaire.id 
			NOT IN 
			(SELECT elevesalle.ideleve FROM elevesalle)

			ORDER BY  eleveanneescolaire.id desc');	
    ?>
	<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
	[ Liste des &eacute;l&egrave;ves inscrits ne disposant pas de salle de classe ]</span></div><br/>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th>
                <th style="width:15%;">Ann&eacute;e Scolaire</th>				
				<th style="width:15%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:15%;">Sexe</th>
				<th style="width:15%;">Statut dans la classe</th>
				<th style="width:15%;">Niveau inscrit</th>
				<th style="width:15%;">Choisissez la classe</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$codeclasse = $donnees['codeclasse'];
			$libelle = $donnees['libelle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td>
					<a><?php echo $etat_eleve;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<?php getAllSalle_($idclasse,$ligne,$pdo);?>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreinscrit" value="<?php echo $ligne;?>"/><?php
}

function ListAffectationSalle($pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve,
					eleve.etat_eleve,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevestatutclasse.libelle as elevestatutclasse

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
            AND
			elevesalle.statut!=-1
			AND
			eleveanneescolaire.statut!=-1
            AND
            anneescolaire.statut=1
			
			ORDER BY elevesalle.id desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;font-weight:bold;color:#000">#</th> 
				<th style="width:15%;font-weight:bold;color:#000">Ann&eacute;e scol.</th>				
				<th style="width:20%;font-weight:bold;color:#000">Nom</th>
				<th style="width:18%;font-weight:bold;color:#000">Pr&eacute;nom</th>
				<th style="width:10%;font-weight:bold;color:#000">Sexe</th>
				<th style="width:17%;font-weight:bold;color:#000">Statut dans la classe</th>
				<th style="width:15%;font-weight:bold;color:#000">Classe affect&eacute;e</th>
		    </tr>
	    </thead>
		<tbody>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $Libelle;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td>
					<a><?php echo $elevestatutclasse;?></a>
				</td>
				<td>
					<a><?php echo $CodeSalle;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?></tbody>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/> 
	</table><?php
}

function DossierEleve($idanneescolaire,$pdo)
{
	
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,					
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id

			ORDER BY  elevesalle.id desc');	
    ?>
	
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th> 
				<th style="width:5%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">STATUT</th>
				<th style="width:15%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ANN&Eacute;E SCOLAIRE</th>
				<th style="width:20%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">NOM</th>
				<th style="width:15%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">PR&Eacute;NOM</th>
				<th style="width:10%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">SEXE</th>
				<th style="width:15%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">STATUT CLASSE</th>
				<th style="width:15%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">CLASSE AFFECT&Eacute;E</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<?php 
						if($statut==1)
						{
							?><span style="color:red;font-size:13px;">Actif</span><?php
						}
						elseif($statut==0)
						{
							?><span style="color:red;font-size:13px;">Abandon</span><?php
						}
					?>
				</td>
				<td>
					<a><?php echo $Libelle;?></a>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td>
					<a><?php echo $elevestatutclasse;?></a>
				</td>
				<td>
					<a><?php echo $CodeSalle;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListePersonnel($pdo)
{
	$req=(' SELECT  
                    professeur.id,
					professeur.nom as nompers,
					professeurtitre.nom as nom,
					professeur.contact as contact,
					professeur.dateembauche as dateembauche,
					professeur.datenaissance as datenaissance,
					professeur.lieunaissance as lieunaissance,
					professeur.numcnss as numcnss,
					professeur.personneacharge as personneacharge
					
			FROM professeur,professeurtitre
			WHERE
			professeur.titre=professeurtitre.id
			AND
			professeur.corps=2
			AND
			professeur.statut=1
			
			ORDER BY professeur.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
				<th style="width:13%;font-weight:bold;color:#000">Num CNSS</th>
				<th style="width:13%;font-weight:bold;color:#000">Nom</th>
				<th style="width:13%;font-weight:bold;color:#000">Titre</th>
				<th style="width:13%;font-weight:bold;color:#000">Contact</th>
				<th style="width:13%;font-weight:bold;color:#000">Date naissance</th>
				<th style="width:13%;font-weight:bold;color:#000">Date d'embauche</th>
				<th style="width:13%;font-weight:bold;color:#000">Nbre pers.</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$nompers = $donnees['nompers'];
			$nom = $donnees['nom'];
			$contact = $donnees['contact'];
			$dateembauche = $donnees['dateembauche'];
			if($dateembauche!="")
			{
				$tab = explode("-",$dateembauche);
				$dateembauche = $tab[2].'-'.$tab[1].'-'.$tab[0];
			}
			$datenaissance = $donnees['datenaissance'];
			if($datenaissance!="")
			{
				$tab = explode("-",$datenaissance);
				$datenaissance = $tab[2].'-'.$tab[1].'-'.$tab[0];
			}
			$lieunaissance = $donnees['lieunaissance'];
			$numcnss = $donnees['numcnss'];
			$personneacharge = $donnees['personneacharge'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $numcnss;?></a>
				</td>
				<td>
					<a><?php echo $nompers;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $contact;?></a>
				</td>
				<td>
					<a><?php echo $datenaissance;?></a>
				</td>
				<td>
					<a><?php echo $dateembauche;?></a>
				</td>
				<td>
					<a><?php echo $personneacharge;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrepersonnel" value="<?php echo $ligne;?>"/><?php
}

function ListPersonnelDonneePaie($pdo)
{
	$req=(' SELECT  
					anneescolaire.libelle as libelle,
					professeur.nom as nom,
					professeurdonneepaie.id as id,
					professeur.dateembauche as dateembauche,
					professeurdonneepaie.salairebase as salairebase,
					professeurdonneepaie.salairebrute as salairebrute
					
			FROM professeur,professeurdonneepaie,anneescolaire
			WHERE
			professeurdonneepaie.idanneescolaire=anneescolaire.id
			AND
			professeur.id=professeurdonneepaie.idpers
			AND
			professeur.corps=2
			AND
			professeurdonneepaie.statut=1
			
			ORDER BY professeurdonneepaie.id desc');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Ann&eacute;e scolaire</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Employ&eacute;</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Date embauche</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Salaire base</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Salaire brute</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$libelle = $donnees['libelle'];
			$dateembauche = $donnees['dateembauche'];
			$dateembauche = $donnees['dateembauche'];
			if($dateembauche!="")
			{
				$tab = explode("-",$dateembauche);
				$dateembauche = $tab[2].'-'.$tab[1].'-'.$tab[0];
			}
			$salairebase = $donnees['salairebase'];
			$salairebrute = $donnees['salairebrute'];
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td align="left">
					<a><?php echo $libelle;?></a>
				</td>
				<td align="left">
					<a><?php echo $nom;?></a>
				</td>
				<td align="left">
					<a><?php echo $dateembauche;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairebase,0,""," ");?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairebrute,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbrepersonneldonneepaie" value="<?php echo $ligne;?>"/><?php
}

function ListeProfesseur($pdo)
{
	
	$req=(' SELECT  
                    professeur.id,
					professeur.nom as nomprof,
					professeurtitre.nom,
					professeur.contact,
					professeur.signature
					
			FROM professeur,professeurtitre
			WHERE
			professeur.titre=professeurtitre.id
            AND
            professeur.statut=1
			AND
			professeurtitre.id IN (1,2,3,4)
			
			ORDER BY professeur.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:23%;font-weight:bold;color:#000">Nom</th>
				<th style="width:23%;font-weight:bold;color:#000">Titre</th>
				<th style="width:23%;font-weight:bold;color:#000">Contact</th>
				<th style="width:23%;font-weight:bold;color:#000">Signature</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$nomprof = $donnees['nomprof'];
			$titre = $donnees['nom'];
			$contact = $donnees['contact'];
			$signature = $donnees['signature'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $nomprof;?></a>
				</td>
				<td>
					<a><?php echo $titre;?></a>
				</td>
				<td>
					<a><?php echo $contact;?></a>
				</td>
				<td>
				    <?php
						if($signature!="")
						{
							?><a><img src="photo_user/<?php echo $signature;?>" width="100px" height="20px;"/></a><?php
						}
					?>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreprofesseur" value="<?php echo $ligne;?>"/><?php
}

function ListeProfesseurLierMatiere($pdo)
{
	$req=(' SELECT  
					distinct
	                professeur.id as idprof,
					professeur.nom as nomprof,
                    matiere.id_matiere as id_matiere,
					matiere.code_matiere as code_matiere,
					salle.codesalle as codesalle,
					anneescolaire.libelle as libelle,
					professeursallemat.id as id,
					professeursallemat.idsalle as idsalle,
					professeursallemat.idmat as idmat,
					professeursallemat.idprof as idprof,
					professeurtitre.nom as nom,
					professeursallemat.statut as statut
					
			FROM    professeur,salle,anneescolaire,matiere,professeursallemat,professeurtitre 
			WHERE
			professeur.id=professeursallemat.idprof
			AND
			salle.id=professeursallemat.idsalle
			AND
			professeursallemat.idmat=matiere.id_matiere
			AND
			professeursallemat.idanneescolaire=anneescolaire.id
			AND
			professeursallemat.idtitre= professeurtitre.id

			ORDER BY professeursallemat.id desc
		');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">Statut</th>  
				<th style="width:19%;font-weight:bold;color:#000">Nom Professeur</th>
				<th style="width:19%;font-weight:bold;color:#000">Mati&egrave;re</th>
				<th style="width:19%;font-weight:bold;color:#000">Classe</th>
				<th style="width:19%;font-weight:bold;color:#000">Titre</th>
				<th style="width:14%;font-weight:bold;color:#000">Ann&eacute;e Scolaire</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$nomprof = $donnees['nomprof'];
			$codesalle = $donnees['codesalle'];
			$code_matiere = $donnees['code_matiere'];
			$libelle = $donnees['libelle'];
			$nom = $donnees['nom'];
			$idsalle = $donnees['idsalle'];
			$idprof = $donnees['idprof'];
			$idmat = $donnees['idmat'];
			$statut = $donnees['statut'];
			if($statut==0)
			{
				$statut_="Desactiv&eacute;";
			}
			else
			{
				$statut_="Actif";
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" 
					value="<?php echo $id.'*'.$idsalle.'*'.$idprof.'*'.$idmat.'*'.$statut;?>"/>
				</td>
				<td>
					<a style="color:red;"><?php echo $statut_;?></a>
				</td>
				<td>
					<a><?php echo $nomprof;?></a>
				</td>
				<td>
					<a><?php echo $code_matiere;?></a>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreprofesseurliermatiere" value="<?php echo $ligne;?>"/><?php
}

function ListeMatSansClasse($pdo)
{
	$req=(' SELECT  
	                matiere.id,
					professeur.nom		
			FROM professeur
			WHERE
			professeur.id NOT IN 
			(
				SELECT  
						professeursallemat.idprof
				FROM professeursallemat
			)
		');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:31%;">Nom Professeur</th>
				<th style="width:31%;">Classe</th>
				<th style="width:31%;">Mati&egrave;re</th>
		    </tr>
	    </thead>
		<tbody>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<?php getAllSalle_2($ligne,$pdo);?>
				</td>
				<td>
					<?php getAllMatiere_($ligne,$pdo);?>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?></tbody>
	<input type="hidden" name="nbreprofesseursanssalle" value="<?php echo $ligne;?>"/> 
	</table><?php
}

function ListeAnneeScolaire($pdo)
{
	$req=(' SELECT  
                    anneescolaire.id,
					anneescolaire.libelle,
					anneescolaire.statut
					
			FROM anneescolaire
			
			ORDER BY anneescolaire.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:85%;font-weight:bold;color:#000">Ann&eacute;e Scolaire</th>
				<th style="width:10%;font-weight:bold;color:#000">Statut</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$anneescolaire = $donnees['libelle'];
			$statut = $donnees['statut'];
			?>
			<tr>
			    <td>
				    <input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $anneescolaire;?></a>
			    </td>
				<td>
					<a>
					<?php 
						if($statut==1)
						{
							?><button type="button" class="btn btn-success btn-xs">En cours</button><?php
						}
						else
						{
							?><button type="button" class="btn btn-danger btn-xs">Clôturée</button><?php
						}
					?>
					</a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreanneescolaire" value="<?php echo $ligne;?>"/><?php
}

function ListeSalle($pdo)
{
	$req=(' SELECT  
                    classe.idclasse as idclasse,
					classe.codeclasse,
					salle.id as idsalle,
					salle.codesalle,
					salle.nomsalle
					
			FROM classe,salle
			WHERE
			classe.idclasse=salle.idclasse
			
			ORDER BY salle.id asc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:31%;font-weight:bold;color:#000">Code Classe</th>
				<th style="width:31%;font-weight:bold;color:#000">Code Salle</th>
				<th style="width:31%;font-weight:bold;color:#000">Nom Salle</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$idsalle = $donnees['idsalle'];
			$codesalle = $donnees['codesalle'];
			$nomsalle = $donnees['nomsalle'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<a><?php echo $nomsalle;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbresalle" value="<?php echo $ligne;?>"/><?php
}

function ListeDomaine($pdo)
{
	$req=(' SELECT  
                    domaine.id as iddomaine,
					domaine.nom as nomdomaine,
					domaine.statut as statutdomaine,
					domaine.tel as teldomaine,
					domaine.bp as bpdomaine,
					domaine.logo as logodomaine
					
			FROM domaine
			WHERE
			domaine.statut=1
			
			ORDER BY domaine.id desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:23%;font-weight:bold;color:#000">Nom</th>
				<th style="width:23%;font-weight:bold;color:#000">Contact</th>
				<th style="width:23%;font-weight:bold;color:#000">Boîte postale</th>
				<th style="width:23%;font-weight:bold;color:#000">Logo associ&eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$iddomaine = $donnees['iddomaine'];
			$nomdomaine = $donnees['nomdomaine'];
			$statutdomaine = $donnees['statutdomaine'];
			$teldomaine = $donnees['teldomaine'];
			$bpdomaine = $donnees['bpdomaine'];
			$logodomaine = $donnees['logodomaine'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $iddomaine;?>"/>
				</td>
				<td>
					<a><?php echo $nomdomaine;?></a>
				</td>
				<td>
					<a><?php echo $teldomaine;?></a>
				</td>
				<td>
					<a><?php echo $bpdomaine;?></a>
				</td>
				<td>
				    <?php
						if($logodomaine!="")
						{
							?><a><img src="domainelogo/<?php echo $logodomaine;?>" width="100px" height="20px;"/></a><?php
						}
					?>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbredomaine" value="<?php echo $ligne;?>"/><?php
}

function ListeNiveau($pdo)
{
	
	$req=(' SELECT  
                    classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					classe.nomclasse as nomclasse,
					domaine.nom as nomdomaine
					
			FROM classe,domaine
			WHERE
			classe.iddomaine=domaine.id
			AND
			classe.statut=1
			AND
			domaine.statut=1
			
			ORDER BY classe.idclasse desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:23%;font-weight:bold;color:#000">Domaine</th>
				<th style="width:23%;font-weight:bold;color:#000">Code Niveau/Option</th>
				<th style="width:23%;font-weight:bold;color:#000">Libell&eacute; Niveau/Option</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$nomclasse = $donnees['nomclasse'];
			$nomdomaine = $donnees['nomdomaine'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idclasse;?>"/>
				</td>
				<td>
					<a><?php echo $nomdomaine;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo $nomclasse;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbreclasse" value="<?php echo $ligne;?>"/><?php
}

function ListeTrimestreExamen($pdo)
{
	$req=(' SELECT  
                    position.idposition as idposition,
					position.codeposition as codeposition,
					position.libposition as libposition
					
			FROM position
			
			ORDER BY position.idposition desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:23%;font-weight:bold;color:#000">Code</th>
				<th style="width:23%;font-weight:bold;color:#000">Libell&eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idposition = $donnees['idposition'];
			$codeposition = $donnees['codeposition'];
			$libposition = $donnees['libposition'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idposition;?>"/>
				</td>
				<td>
					<a><?php echo $codeposition;?></a>
				</td>
				<td>
					<a><?php echo $libposition;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbreposition" value="<?php echo $ligne;?>"/><?php
}

function ListeMatiere($pdo)
{
	$req=(' SELECT  
                    matiere.id_matiere,
					matiere.code_matiere,
					matiere.nom_matiere
					
			FROM matiere
			WHERE
			matiere.statut_matiere=1

			ORDER BY matiere.code_matiere ASC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:47.5%;font-weight:bold;color:#000">Code Matiere</th>
				<th style="width:47.5%;font-weight:bold;color:#000">Nom Matiere</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];
			?>
			<tr>
			    <td>
				<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id_matiere;?>"/>
				</td>
				<td>
					<a><?php echo $code_matiere;?></a>
			    </td>
				<td>
					<a><?php echo $nom_matiere;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
	</table>
	<input type="hidden" name="nbrematiere" value="<?php echo $ligne;?>"/><?php
}

function ListeMatiereCoefficient($pdo)
{
	$req=(' SELECT  distinct
                    matiere.id_matiere as id_matiere,
					matiere.code_matiere as code_matiere,
					matiere.nom_matiere as nom_matiere,
					matierecoefficient.coefficient as coefficient,
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					matierecoefficient.id as id,
					matierecoefficient.statut as statut,
					matierecoefficient.etat as etat

			FROM matiere,matierecoefficient,classe
            WHERE
			matiere.id_matiere=matierecoefficient.idmatiere
			AND
			matierecoefficient.idclasse=classe.idclasse
			AND
			matierecoefficient.coefficient<>0
			
			ORDER BY matierecoefficient.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
		        <th style="width:10%;font-weight:bold;color:#000">Statut</th> 
				<th style="width:30%;font-weight:bold;color:#000">Code Matiere</th>
				<th style="width:30%;font-weight:bold;color:#000">Code Classe</th>
				<th style="width:11%;font-weight:bold;color:#000">Coefficient</th>
				<th style="width:12%;font-weight:bold;color:#000">Etat</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$codeclasse = $donnees['codeclasse'];
			$id = $donnees['id'];
			$idclasse = $donnees['idclasse'];
			$coefficient = $donnees['coefficient'];
			$statut = $donnees['statut'];
			$etat = $donnees['etat'];
			if($etat==1)
			{
				$etat_="Obligatoire";
			}
			else
			{
				$etat_="Facultative";
			}

			if($statut==1)
			{
				$statut_="Actif";
			}
			else
			{
				$statut_="D&eacute;sactiv&eacute;";
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" 
					value="<?php echo $id.'*'.$id_matiere.'*'.$idclasse.'*'.$coefficient;?>"/>
				</td>
				<td>
					<a style="color:red;"><?php echo $statut_;?></a>
				</td>
				<td>
					<a><?php echo $code_matiere;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo $coefficient;?></a>
				</td>
				<td>
					<a><?php echo $etat_;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;?> 
	</table>
	<input type="hidden" name="nbrematierecoefficient" value="<?php echo $ligne;?>"/><?php
}

//UTILISATEUR
function Utilisateur($pdo)
{
	$req=(' SELECT  
					utilisateur.id as id,
					utilisateur.nom_user as nomuser,
					utilisateur.prenom_user as prenomuser,
					utilisateur.login_user as loginuser,
					utilisateur.mtpass_user as mtpassuser,
					utilisateur.profil as profil,
					utilisateur.statut as statut
					
			FROM utilisateur

			ORDER BY utilisateur.id desc'
		);
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
				<th style="width:10%;font-weight:bold;color:#000">Statut</th>
				<th style="width:19%;font-weight:bold;color:#000">Code d'acc&egrave;s</th>
				<th style="width:%;28font-weight:bold;color:#000">Nom / Pr&eacute;nom</th>
				<th style="width:19%;font-weight:bold;color:#000">Identifiant</th>
				<th style="width:19%;font-weight:bold;color:#000">Profil</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$nomuser = $donnees['nomuser'];
			$prenomuser = $donnees['prenomuser'];
			$loginuser = $donnees['loginuser'];
			$profil = $donnees['profil'];
			$statut = $donnees['statut'];
		    ?>
			<tr>
			    <td><input class="flat" type="checkbox" name="id_user<?php echo $ligne;?>" value="<?php echo $id;?>"/></td>
				<td>
					<a>
						<?php 
							if($statut==1)
							{
								?>
								<div align="center">
									<a href="#" class="btn btn-default btn-xs">
										<i class="fa fa-check"></i>&nbsp;Compte activ&eacute;
									</a>
								</div><?php
							}
							else
							{
								?>
								<div align="center">
									<a href="#" class="btn btn-danger btn-xs">
										<i class="fa fa-check"></i>&nbsp;Compte d&eacute;sactiv&eacute;
									</a>
								</div><?php
							}
						?>
					</a>
				</td>
				<td><a><?php echo $id;?></a></td>
				<td><a><?php echo $nomuser.' '.$prenomuser;?></a></td>
				<td><a><?php echo $loginuser;?></a></td>	
				<td><a><?php echo $profil;?></a></td>					
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbre_user" value="<?php echo $ligne;?>"/><?php
}

function generation_bulletins($idanneescolaire,$idposition,$idsalle,$pdo)
{	
	$idclasse = getIdClasseForSalle($idsalle,$pdo);
	$iddomaine = getDomaineSalle($idsalle,$pdo);
	
	$req=(' SELECT  
					eleve.id_eleve,
					eleve.nom_eleve,
					eleve.prenom_eleve,
					bulletin.moyenne_gene,
					bulletin.rang,
					bulletin.id

			FROM bulletin,eleve,eleveanneescolaire,elevesalle
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.ideleve=elevesalle.id
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleve.id_eleve=eleveanneescolaire.ideleve

			ORDER BY bulletin.moyenne_gene DESC');

	//OPTION COLLEGE
	if($iddomaine==3)
	{		
    ?>
	<div align="right" style="margin-bottom:5px;">
		<?php
		if($idposition>=4)
		{
			?>
			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les relev&eacute;s" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) releve(s) ?');"/>
			&nbsp;&nbsp;&nbsp;
			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs" onclick='window.open("ImprimerReleveNotes.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
				<i class="fa fa-pencil"></i> Imprimer tous les relev&eacute;s de notes 
			</a><?php
		}
		else
		{
			?>
			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les bulletins" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) bulletin(s) ?');"/>
			&nbsp;&nbsp;&nbsp;
			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs" onclick='window.open("ImprimerBulletinCollege.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
				<i class="fa fa-pencil"></i> Imprimer tous les bulletins de notes 
			</a><?php
		}
		?>
	</div>
	<table class="table table-striped projects">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;"><input type="checkbox" id="check-all" class="flat"></th> 
				<th style="width:38%;">Nom & pr&eacute;nom</th>
				<th style="width:19%;">Rang occup&eacute;</th>
				<th style="width:19%;">Moyenne &eacute;valuation</th>
				<th style="width:19%;" colspan="2">Action(s)</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id_eleve'];
			$nom = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$moyenne_gene = $donnees['moyenne_gene'];
			$idbulletin = $donnees['id'];
			$rang = $donnees['rang'];
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $idbulletin;?>"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_gene;?></a>
				</td>
				<td>
				    <?php
            		if($idposition>=4)
            		{
            			?>
            			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs" onclick='window.open("ImprimerReleveNotes.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=<?php echo $idbulletin;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
            				<i class="fa fa-pencil"></i> Impression individuelle 
            			</a><?php
            		}
            		else
            		{
            			?>
            			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs" onclick='window.open("ImprimerBulletinCollege.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=<?php echo $idbulletin;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
            				<i class="fa fa-pencil"></i> Impression individuelle 
            			</a><?php
            		}
            		?>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
	?>
	</table>
	<input type="hidden" name="nbre_bulletin" value="<?php echo $ligne;?>"/><?php
	}
	elseif($iddomaine==6)
	{		
    ?>
	<div align="right" style="margin-bottom:5px;">
		<?php
		if($idposition>=4)
		{
			?>
			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les relev&eacute;s" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) releve(s) ?');"/>
			&nbsp;&nbsp;&nbsp;
			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs" onclick='window.open("ImprimerReleveNotes.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
				<i class="fa fa-pencil"></i> Imprimer tous les relev&eacute;s de notes 
			</a><?php
		}
		else
		{
			?>
			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les bulletins" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) bulletin(s) ?');"/>
			&nbsp;&nbsp;&nbsp;
			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs" onclick='window.open("ImprimerBulletinLycee.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
				<i class="fa fa-pencil"></i> Imprimer tous les bulletins de notes 
			</a><?php
		}
		?>
	</div>
	<table class="table table-striped projects">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;"><input type="checkbox" id="check-all" class="flat"></th> 
				<th style="width:40%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:20%;">Rang occup&eacute;</th>
				<th style="width:20%;">Moyenne trimestrielle</th>
				<th style="width:15%;">Action(s)</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id_eleve'];
			$nom = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$moyenne_gene = $donnees['moyenne_gene'];
			$idbulletin = $donnees['id'];
			$rang = $donnees['rang'];
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $idbulletin;?>"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_gene;?></a>
				</td>
				<td>
					<?php
            		if($idposition>=4)
            		{
            			?>
            			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs" onclick='window.open("ImprimerReleveNotes.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=<?php echo $idbulletin;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
            				<i class="fa fa-pencil"></i> Impression individuelle 
            			</a><?php
            		}
            		else
            		{
            			?>
            			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs" onclick='window.open("ImprimerBulletinLycee.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=<?php echo $idbulletin;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
            				<i class="fa fa-pencil"></i> Impression individuelle 
            			</a><?php
            		}
            		?>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
	?>
	</table>
	<input type="hidden" name="nbre_bulletin" value="<?php echo $ligne;?>"/><?php
	}
	elseif($iddomaine==7)
	{		
		?>
		<div align="right" style="margin-bottom:5px;">
			<?php
			if($idposition>=4)
    		{
    			?>
    			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les relev&eacute;s" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) releve(s) ?');"/>
    			&nbsp;&nbsp;&nbsp;
    			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs">
    				<i class="fa fa-pencil"></i> Imprimer tous les relev&eacute;s de notes 
    			</a><?php
    		}
    		else
    		{
    			?>
    			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les bulletins" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) bulletin(s) ?');"/>
    			&nbsp;&nbsp;&nbsp;
    			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs">
    				<i class="fa fa-pencil"></i> Imprimer tous les bulletins de notes 
    			</a><?php
    		}
			?>
		</div>
		<table class="table table-striped projects">
			<thead>
				<tr style="background-color:#eee;">
					<th style="width:5%;text-align:center;"><input type="checkbox" id="check-all" class="flat"></th> 
					<th style="width:40%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
					<th style="width:20%;">Rang occup&eacute;</th>
					<th style="width:20%;">Moyenne trimestrielle</th>
					<th style="width:15%;">Action(s)</th>
				</tr>
			</thead>
			<?php		
			$stmt = $pdo->prepare($req);
			$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
			$stmt->execute();	
			$ligne=0;
			while($donnees = $stmt->fetch())
			{
				$ligne++;
				
				$id = $donnees['id_eleve'];
				$nom = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
				$moyenne_gene = $donnees['moyenne_gene'];
				$idbulletin = $donnees['id'];
				$rang = $donnees['rang'];
				?>
				<tr>
					<td align="center">
						<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $idbulletin;?>"/>
					</td>
					<td>
						<a><?php echo $nom;?></a>
					</td>
					<td>
						<a><?php echo $rang;?></a>
					</td>
					<td>
						<a><?php echo $moyenne_gene;?></a>
					</td>
					<td>
						<?php
                		if($idposition>=4)
                		{
                			?>
                			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs">
                				<i class="fa fa-pencil"></i> Impression individuelle 
                			</a><?php
                		}
                		else
                		{
                			?>
                			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs">
                				<i class="fa fa-pencil"></i> Impression individuelle 
                			</a><?php
                		}
                		?>
					</td>
				</tr><?php
			}
			$stmt->closeCursor();
			$stmt=NULL;
		?>
		</table>
		<input type="hidden" name="nbre_bulletin" value="<?php echo $ligne;?>"/><?php
	}
	elseif($iddomaine==4 || $iddomaine==5)
	{		
		?>
		<div align="right" style="margin-bottom:5px;">
			<?php
			if($idposition>=4)
    		{
    			?>
    			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les relev&eacute;s" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) releve(s) ?');"/>
    			&nbsp;&nbsp;&nbsp;
    			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs" onclick='window.open("ImprimerReleveNotes.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
    				<i class="fa fa-pencil"></i> Imprimer tous les relev&eacute;s de notes 
    			</a><?php
    		}
    		else
    		{
    			?>
    			<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les bulletins" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) bulletin(s) ?');"/>
    			&nbsp;&nbsp;&nbsp;
    			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs">
    				<i class="fa fa-pencil"></i> Imprimer tous les bulletins de notes 
    			</a><?php
    		}
			?>
		</div>
		<table class="table table-striped projects">
			<thead>
				<tr style="background-color:#eee;">
					<th style="width:5%;text-align:center;"><input type="checkbox" id="check-all" class="flat"></th> 
					<th style="width:40%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
					<th style="width:20%;">Rang occup&eacute;</th>
					<th style="width:20%;">Moyenne Obtenue</th>
					<th style="width:15%;">Action(s)</th>
				</tr>
			</thead>
			<?php		
			$stmt = $pdo->prepare($req);
			$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
			$stmt->execute();	
			$ligne=0;
			while($donnees = $stmt->fetch())
			{
				$ligne++;
				
				$id = $donnees['id_eleve'];
				$nom = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
				$moyenne_gene = $donnees['moyenne_gene'];
				$idbulletin = $donnees['id'];
				$rang = $donnees['rang'];
				?>
				<tr>
					<td align="center">
						<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $idbulletin;?>"/>
					</td>
					<td>
						<a><?php echo $nom;?></a>
					</td>
					<td>
						<a><?php echo $rang;?></a>
					</td>
					<td>
						<a><?php echo $moyenne_gene;?></a>
					</td>
					<td>
						<?php
                		if($idposition>=4)
                		{
                			?>
                			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs" onclick='window.open("ImprimerReleveNotes.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=<?php echo $idbulletin;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
                				<i class="fa fa-pencil"></i> Impression individuelle 
                			</a><?php
                		}
                		else
                		{
                			?>
                			<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs">
                				<i class="fa fa-pencil"></i> Impression individuelle 
                			</a><?php
                		}
                		?>
					</td>
				</tr><?php
			}
			$stmt->closeCursor();
			$stmt=NULL;
		?>
		</table>
		<input type="hidden" name="nbre_bulletin" value="<?php echo $ligne;?>"/><?php
	}
}

function generation_bulletins_CycleSup($idanneescolaire,$idposition,$idsalle,$pdo)
{	
	$idclasse = getIdClasseForSalle($idsalle,$pdo);
	$iddomaine = getDomaineSalle($idsalle,$pdo);
	
	$req=(' SELECT  
					eleve.id_eleve,
					eleve.nom_eleve,
					eleve.prenom_eleve,
					bulletin.moyenne_gene,
					bulletin.rang,
					bulletin.id

			FROM bulletin,eleve,eleveanneescolaire,elevesalle
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.ideleve=elevesalle.id
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleve.id_eleve=eleveanneescolaire.ideleve

			ORDER BY bulletin.moyenne_gene DESC');		
    ?>
	<div align="right" style="margin-bottom:5px;">
		<input type="submit" style="width:200px;padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="delete_bulletin_eleve" value="Supprimer les bulletins" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) bulletin(s) ?');"/>
		&nbsp;&nbsp;&nbsp;
		<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-warning btn-xs" onclick='window.open("ImprimerBulletinCollege.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
			<i class="fa fa-pencil"></i> Imprimer tous les bulletins de notes 
		</a>
	</div>
	<table class="table table-striped projects">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;"><input type="checkbox" id="check-all" class="flat"></th> 
				<th style="width:38%;">Nom & pr&eacute;nom</th>
				<th style="width:19%;">Rang occup&eacute;</th>
				<th style="width:19%;">Moyenne &eacute;valuation</th>
				<th style="width:19%;" colspan="2">Action(s)</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id_eleve'];
			$nom = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$moyenne_gene = $donnees['moyenne_gene'];
			$idbulletin = $donnees['id'];
			$rang = $donnees['rang'];
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $idbulletin;?>"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_gene;?></a>
				</td>
				<td>
					<a href="#" style="padding:10px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-info btn-xs" onclick='window.open("ImprimerBulletinCycleSup.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&idsalle=<?php echo $idsalle;?>&idbulletin=<?php echo $idbulletin;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						<i class="fa fa-pencil"></i> Impression individuelle 
					</a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
	?>
	</table>
	<input type="hidden" name="nbre_bulletin" value="<?php echo $ligne;?>"/><?php
}

function SyntheseBulletins($pdo)
{
	$req=(' SELECT  distinct 
	                anneescolaire.id as idanneescolaire,
	                anneescolaire.libelle as Anneescolaire,
					position.idposition as idposition,
	                position.libposition as Position,
					salle.id as idsalle,
					salle.codesalle as Salle
					
			FROM anneescolaire,salle,position,bulletin
			WHERE
			anneescolaire.id=bulletin.idanneescolaire
			AND
			salle.id=bulletin.idsalle
			AND
			position.idposition=bulletin.idposition

			ORDER BY anneescolaire.id DESC'
		);	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:31%;font-weight:bold;color:#000;font-size:16px;">Ann&eacute;e Scolaire</th>
				<th style="width:31%;font-weight:bold;color:#000;font-size:16px;">&Eacute;valuation</th>
				<th style="width:31%;font-weight:bold;color:#000;font-size:16px;">Classe</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];

			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $Anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>
				<td>
					<a><?php echo $Salle;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrebulletins" value="<?php echo $ligne;?>"/><?php
}

function SyntheseBulletinsCycleSup($pdo)
{
	$req=(' SELECT  distinct 
	                anneescolaire.id as idanneescolaire,
	                anneescolaire.libelle as Anneescolaire,
					position.idposition as idposition,
	                position.libposition as Position,
					salle.id as idsalle,
					salle.codesalle as Salle
					
			FROM anneescolaire,salle,position,bulletin
			WHERE
			anneescolaire.id=bulletin.idanneescolaire
			AND
			salle.id=bulletin.idsalle
			AND
			position.idposition=bulletin.idposition
			AND
			position.idposition IN (4,5)
			
			ORDER BY anneescolaire.id DESC'
		);	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:31%;font-weight:bold;color:#000;font-size:16px;">Ann&eacute;e Scolaire</th>
				<th style="width:31%;font-weight:bold;color:#000;font-size:16px;">&Eacute;valuation</th>
				<th style="width:31%;font-weight:bold;color:#000;font-size:16px;">Classe</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];

			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $Anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>
				<td>
					<a><?php echo $Salle;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrebulletins" value="<?php echo $ligne;?>"/><?php
}

function TauxReussite($idanneescolaire,$idposition,$idsalle,$idetat,$libelleetat,$moyenne,$print,$pdo)
{
	if($idetat==1)
	{
		$req=(' SELECT  
						position.idposition as idposition,
						position.libposition as libelleposition,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire,
						eleve.id_eleve as id_eleve,
						eleve.nom_eleve as nom_eleve,
						eleve.prenom_eleve as prenom_eleve,
						bulletin.moyenne_gene as moyenne_gene,
						bulletin.id as id,
						bulletin.rang as rang,
						salle.codesalle as codesalle

				FROM bulletin,eleve,eleveanneescolaire,elevesalle,salle,position,anneescolaire
				WHERE
				elevesalle.idsalle=salle.id
				AND
				bulletin.idanneescolaire=anneescolaire.id
				AND
				anneescolaire.id=:idanneescolaire
				AND
				bulletin.idsalle=salle.id
				AND
				salle.id=:idsalle
				AND
				bulletin.idposition=position.idposition
				AND
				position.idposition=:idposition
				AND
				bulletin.ideleve=elevesalle.id
				AND
				elevesalle.ideleve=eleveanneescolaire.id
				AND
				eleve.id_eleve=eleveanneescolaire.ideleve
				AND
				bulletin.moyenne_gene>=:moyenne
				
				ORDER BY bulletin.moyenne_gene DESC');
    }
	else
	{
		$req=(' SELECT  
						position.idposition as idposition,
						position.libposition as libelleposition,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire,
						eleve.id_eleve as id_eleve,
						eleve.nom_eleve as nom_eleve,
						eleve.prenom_eleve as prenom_eleve,
						bulletin.moyenne_gene as moyenne_gene,
						bulletin.id as id,
						bulletin.rang as rang,
						salle.codesalle as codesalle

				FROM bulletin,eleve,eleveanneescolaire,elevesalle,salle,position,anneescolaire
				WHERE
				elevesalle.idsalle=salle.id
				AND
				bulletin.idanneescolaire=anneescolaire.id
				AND
				anneescolaire.id=:idanneescolaire
				AND
				bulletin.idsalle=salle.id
				AND
				salle.id=:idsalle
				AND
				bulletin.ideleve=elevesalle.id
				AND
				elevesalle.ideleve=eleveanneescolaire.id
				AND
				eleve.id_eleve=eleveanneescolaire.ideleve
				AND
				bulletin.moyenne_gene<=:moyenne
				AND
				bulletin.idposition=position.idposition
				AND
				position.idposition=:idposition
				
				ORDER BY bulletin.moyenne_gene DESC');
    }
	$effectif = getNombreBulletinTrimestre($idsalle,$idanneescolaire,$idposition,$pdo);
    ?>
	<div style="border-radius:5px;border:1px dotted #000;padding:5px;background-color:#eee;width:400px;float:left;">
		<span>
			<a style="color:#000;"> - Effectif de la classe : </a> <?php echo $effectif;?><br/><br/>
			<a style="color:#000;"> - Moyenne plus forte : </a> <?php echo getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo);?><br/><br/>
			<a style="color:#000;"> - Moyenne plus faible : </a> <?php echo getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo);?>
		</span>
	</div>
	<br/>
	<?php
	if($print=="")
	{
		?>
		<div style="border-radius:5px;padding:5px;width:400px;float:right;">
			<button class="btn btn-round" onclick='window.open("DeliberationEvaluationRapport.php?&libelleetat=<?php echo $libelleetat;?>&idetat=<?php echo $idetat;?>&idsalle=<?php echo $idsalle;?>&idanneescolaire=<?php echo $idanneescolaire;?>&idposition=<?php echo $idposition;?>&moyenne=<?php echo $moyenne;?>","", "fullscreen=yes, scrollbars=auto");'>
				<i class="fa fa-print"></i>&nbsp;<a style="font-weight:bold;color:#000;">Imprimer la liste</a>
			</button>
		</div><?php
	}
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;font-size:15px;">
				<th style="width:5%;text-align:center;">#</th>
				<th style="width:15%;color:#000;">Ann&eacute; scolaire</th> 
				<th style="width:15%;color:#000;">Position</th> 
				<th style="width:10%;color:#000;">Classe</th> 
				<th style="width:20%;color:#000;">Nom & pr&eacute;nom</th>
				<th style="width:15%;color:#000;">Rang occup&eacute;</th>
				<th style="width:15%;color:#000;">Moyenne</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':moyenne', $moyenne, PDO::PARAM_INT);
		$stmt->execute();
		$ligne = 0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idposition = $donnees['idposition'];
			$libelleposition = $donnees['libelleposition'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$id = $donnees['id_eleve'];
			$nom = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$moyenne_gene = $donnees['moyenne_gene'];
			$idbulletin = $donnees['id'];
			$rang = $donnees['rang'];
			$codesalle = $donnees['codesalle'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $libelleposition;?></a>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_gene;?></a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
	</table><?php
}

function EvaluationAnnuelleCollege($idanneescolaire,$idposition,$idsalle,$pdo)
{

	$req=(' SELECT  
					distinct
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.sexe_eleve as sexe_eleve,
					eleve.etat_eleve as etat_eleve,
					elevesalle.id as idelevesalle

			FROM eleve,eleveanneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1

			ORDER BY eleve.nom_eleve ASC');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:2%;text-align:center;"></th> 
				<th style="width:20%;">Nom & pr&eacute;nom de l'&eacute;l&egrave;ve</th>
				<th style="width:10%;">Moy. 1<sup>er</sup>Trim.</th>
				<th style="width:10%;">Rang 1<sup>er</sup>Trim.</th>
				<th style="width:10%;">Moy. 2<sup>eme</sup>Trim.</th>
				<th style="width:10%;">Rang 2<sup>eme</sup>Trim.</th>
				<th style="width:10%;">Moy. 3<sup>eme</sup>Trim.</th>
				<th style="width:10%;">Rang 3<sup>eme</sup>Trim.</th>
				<th style="width:10%;">Moy. Ann.</th>
				<th style="width:10%;">Rang Ann.</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$idelevesalle = $donnees['idelevesalle'];
			$moyenne_1 = getMoyenneTrimestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang_1 = getRangTrimestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$moyenne_2 = getMoyenneTrimestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang_2 = getRangTrimestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$moyenne_3 = getMoyenneTrimestre_3($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang_3 = getRangTrimestre_3($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$moy = getMoyAnnu($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang = getRangAnnu($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_1;?></a>
				</td>
				<td>
					<a><?php echo $rang_1;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_2;?></a>
				</td>
				<td>
					<a><?php echo $rang_2;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_3;?></a>
				</td>
				<td>
					<a><?php echo $rang_3;?></a>
				</td>
				<td>
					<a><?php echo $moy;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
	</table><?php
}

function EvaluationAnnuelleLycee($idanneescolaire,$idposition,$idsalle,$pdo)
{

	$req=(' SELECT  
					distinct
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.sexe_eleve as sexe_eleve,
					eleve.etat_eleve as etat_eleve,
					elevesalle.id as idelevesalle

			FROM eleve,eleveanneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			elevesalle.idsalle=:idsalle

			ORDER BY eleve.nom_eleve ASC');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:2%;text-align:center;"></th> 
				<th style="width:20%;">Nom & pr&eacute;nom de l'&eacute;l&egrave;ve</th>
				<th style="width:13%;">Moyen 1<sup>er</sup>Sem.</th>
				<th style="width:13%;">Rang 1<sup>er</sup>Sem.</th>
				<th style="width:13%;">Moyen 2<sup>eme</sup>Sem.</th>
				<th style="width:13%;">Rang 2<sup>eme</sup>Sem.</th>
				<th style="width:13%;">Moy. Ann</th>
				<th style="width:13%;">Rang Ann</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$idelevesalle = $donnees['idelevesalle'];
			$moyenne_1 = getMoyenneSemestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang_1 = getRangSemestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$moyenne_2 = getMoyenneSemestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang_2 = getRangSemestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$moy = getMoyAnnu_($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$rang = getRangAnnu_($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_1;?></a>
				</td>
				<td>
					<a><?php echo $rang_1;?></a>
				</td>
				<td>
					<a><?php echo $moyenne_2;?></a>
				</td>
				<td>
					<a><?php echo $rang_2;?></a>
				</td>
				<td>
					<a><?php echo $moy;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
	</table><?php
}

function MatiereEvaluation($idanneescolaire,$idposition,$idsalle,$idmatiere,$pdo)
{

	$req=(' SELECT  
					distinct
					eleve.id_eleve as idEleve,
					eleve.nom_eleve as NomEleve,
					eleve.prenom_eleve as PrenomEleve,
					eleve.sexe_eleve as SexeEleve,
					eleve.etat_eleve as EtatEleve,
					elevesalle.id as idEleveSalle,
					note.id as idNote,
					note.moyenpondere as MoyenPondere,
					note.rang as Rang,
					matiere.code_matiere as CodeMatiere,
					salle.codesalle as CodeSalle,
					position.libposition as LibPosition,
					anneescolaire.libelle as LibelleAnneeScolaire,
					note.observation as Observation

			FROM eleve,eleveanneescolaire,elevesalle,note,matiere,position,anneescolaire,salle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			elevesalle.id=note.ideleve
			AND
			matiere.id_matiere=note.idmatiere
			AND
			salle.id=note.idsalle
			AND
			anneescolaire.id=note.idanneescolaire
			AND
			position.idposition=note.idposition
			AND
			note.idposition=:idposition
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle

			ORDER BY note.moyenpondere DESC');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;"></th>
				<th style="width:10%;">Mati&egrave;re</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">P&eacute;riode</th>
				<th style="width:10%;">Classe</th>
				<th style="width:20%;">Nom & pr&eacute;nom de l'&eacute;l&egrave;ve</th>
				<th style="width:10%;">Rang</th>
				<th style="width:10%;">Moyenne pond&eacute;re</th>
				<th style="width:10%;">Appr&eacute;ciation</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idEleve = $donnees['idEleve'];
			$NomEleve = $donnees['NomEleve'];
			$PrenomEleve = $donnees['PrenomEleve'];
			$SexeEleve = $donnees['SexeEleve'];
			$MoyenPondere = $donnees['MoyenPondere'];
			$Rang = $donnees['Rang'];
			$CodeMatiere = $donnees['CodeMatiere'];
			$CodeSalle = $donnees['CodeSalle'];
			$LibPosition = $donnees['LibPosition'];
			$LibelleAnneeScolaire = $donnees['LibelleAnneeScolaire'];
			$Observation = $donnees['Observation'];
			
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox"/>
				</td>
				<td style="font-weight:bold;">
					<a><?php echo $CodeMatiere;?></a>
				</td>
				<td>
					<a><?php echo $LibelleAnneeScolaire;?></a>
				</td>
				<td>
					<a><?php echo $LibPosition;?></a>
				</td>
				<td>
					<a><?php echo $CodeSalle;?></a>
				</td>
				<td>
					<a><?php echo $NomEleve.' '.$PrenomEleve;?></a>
				</td>
				<td>
					<a><?php echo $Rang;?></a>
				</td>
				<td>
					<a><?php echo $MoyenPondere;?></a>
				</td>
				<td>
					<a><?php echo $Observation;?></a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
	</table><?php
}

//ABSENCES
function ListeAbsences($pdo)
{
	
	$req=(' SELECT  distinct 
	                anneescolaire.libelle as Anneescolaire,
	                position.libposition as Position,
					salle.codesalle as Salle,
					sum(absences.nbreabsence) as nbreAbsences,
					absences.idanneescolaire as idanneescolaire,
					absences.idsalle as idsalle,
					absences.idposition as idposition
					
			FROM anneescolaire,salle,position,absences
			WHERE
			anneescolaire.id=absences.idanneescolaire
			AND
			salle.id=absences.idsalle
			AND
			position.idposition=absences.idposition
			AND
			anneescolaire.statut=1

			GROUP BY absences.idanneescolaire,absences.idsalle,absences.idposition');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;">Ann&eacute;e Scolaire</th>
				<th style="width:19%;">P&eacute;riode</th>
				<th style="width:19%;">Classe</th>
				<th style="width:19%;">Nbre d'absences (en heure)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];
			$nbreAbsences = $donnees['nbreAbsences'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $Anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>
				<td>
					<a><?php echo $Salle;?></a>
				</td>
				<td>
					<a><?php echo $nbreAbsences;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresabsences" value="<?php echo $ligne;?>"/><?php
}
//
function ListeAbsencesParSalle($idsalle,$pdo)
{
	$req=(' SELECT  distinct 
	                anneescolaire.libelle as Anneescolaire,
	                position.libposition as Position,
					salle.codesalle as Salle,
					count(absences.id) as nbreAbsences,
					absences.idanneescolaire as idanneescolaire,
					absences.idsalle as idsalle,
					absences.idposition as idposition
					
			FROM anneescolaire,salle,position,absences
			WHERE
			anneescolaire.id=absences.idanneescolaire
			AND
			salle.id=absences.idsalle
			AND
			position.idposition=absences.idposition
			AND
			anneescolaire.statut=1
			AND
			salle.id=:idsalle

			GROUP BY absences.idanneescolaire,absences.idsalle,absences.idposition');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:95%;">P&eacute;riode</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];
			$nbreAbsences = $donnees['nbreAbsences'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresabsences" value="<?php echo $ligne;?>"/><?php
}
//
function ListEleveSalleForEnregistrementAbsences($idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:55%;">Nom & pr&eacute;nom(s) &eacute;l&egrave;ve</th>
				<th style="width:40%;">Nombre(s) absence(s) (en heure)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$nbreAbsence = getNbreAbsencesEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$pdo);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<input type="number" autocomplete="off" id="nbreabsence<?php echo $ligne;?>" name="nbreabsence<?php echo $ligne;?>" value="<?php echo $nbreAbsence;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListeAbsencesEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle,
					absences.nbreAbsence

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,absences
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			elevesalle.id=absences.idelevesalle
			AND
			absences.idsalle=:idsalle
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idposition=:idposition

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:55%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:40%;">Nombre(s) absence(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$nbreAbsence = $donnees['nbreAbsence'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<input type="number" readonly="yes" autocomplete="off" id="nbreabsence<?php echo $ligne;?>" name="nbreabsence<?php echo $ligne;?>" value="<?php echo $nbreAbsence;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListeProfesseurDonneePaie($pdo)
{
	
	$req=(' SELECT  
					anneescolaire.libelle as libelle,
					professeur.nom as nom,
					professeurdonneepaie.id as id,
					professeur.dateembauche as dateembauche,
					professeurdonneepaie.vol_horaire as vol_horaire,
					professeurdonneepaie.cout_honoraire as cout_honoraire,
					professeurdonneepaie.statut as statut
					
			FROM professeur,professeurdonneepaie,anneescolaire
			WHERE
			professeurdonneepaie.idanneescolaire=anneescolaire.id
			AND
			professeur.id=professeurdonneepaie.idpers
			AND
			professeur.corps=1
			AND
			professeurdonneepaie.statut=1
			
			GROUP BY professeurdonneepaie.id desc');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" >
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th> 
				<th style="width:10%;text-align:left;font-weight:bold;color:#000">Statut</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Ann&eacute;e scolaire</th>
				<th style="width:28%;text-align:left;font-weight:bold;color:#000">Employ&eacute;</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Date embauche</th>
				<th style="width:19%;text-align:left;font-weight:bold;color:#000">Cout honoraire (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$libelle = $donnees['libelle'];
			$dateembauche = $donnees['dateembauche'];
			$dateembauche = $donnees['dateembauche'];
			if($dateembauche!="")
			{
				$tab = explode("-",$dateembauche);
				$dateembauche = $tab[2].'-'.$tab[1].'-'.$tab[0];
			}
			$cout_honoraire = $donnees['cout_honoraire'];
			$statut = $donnees['statut'];
			$etat="";
			if($statut==0)
			{
				$etat="Desactiv&eacute;";
			}
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td align="left">
					<a style="color:red;font-weight:bold;"><?php echo $etat;?></a>
				</td>
				<td align="left">
					<a><?php echo $libelle;?></a>
				</td>
				<td align="left">
					<a><?php echo $nom;?></a>
				</td>
				<td align="left">
					<a><?php echo $dateembauche;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($cout_honoraire,0," ","");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbreprofesseurdonneepaie" value="<?php echo $ligne;?>"/><?php
}

function EvaluationPerformance($idanneescolaire,$idposition,$iddomaine,$moyenne,$pdo)
{
	$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
	//
	$req=(' SELECT  
					distinct
					classe.codeclasse as codeclasse,
					classe.idclasse as idclasse
					
			FROM classe
			WHERE
			classe.iddomaine=:iddomaine
			
			ORDER BY classe.codeclasse DESC');
			
	//
	$req_=(' SELECT  
					distinct
					matiere.code_matiere as code_matiere,
					matiere.id_matiere as id_matiere
					
			FROM matiere,classe,matierecoefficient
			WHERE
			classe.iddomaine=:iddomaine
			AND
			matierecoefficient.idclasse=classe.idclasse
			AND
			matierecoefficient.idmatiere=matiere.id_matiere
			AND
			matierecoefficient.statut=1
			AND
			matierecoefficient.coefficient<>0
			
			ORDER BY matiere.code_matiere DESC');
    ?>
	<table class="table table-striped projects" width="100%" style="font-size:12px;border1px solid #000;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="color:#000;">Ann&eacute;e scol.</th>
				<th style="color:#000;">Niveau(x)</th>
				<th style="color:#000;">Garcon(s)</th>
				<th style="color:#000;">Fille(s)</th>
				<?php		
				$stmt_ = $pdo->prepare($req_);
				$stmt_->bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
				$stmt_->execute();	
				$ligne=0;
				while($donnees_ = $stmt_->fetch())
				{
					$ligne++;
					$code_matiere = $donnees_['code_matiere'];
					$id_matiere = $donnees_['id_matiere'];
					?>
					<th style="color:#000;"><?php echo $code_matiere;?></th><?php
				}
				?>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$codeclasse = $donnees['codeclasse'];
			$idclasse = $donnees['idclasse'];
			?>
			<tr>
				<td style="font-weight:bold;">
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo getNbreEleveParNiveau($idanneescolaire,1,"Masculin",$idclasse,$pdo);?></a>
				</td>
				<td>
					<a><?php echo getNbreEleveParNiveau($idanneescolaire,1,"Feminin",$idclasse,$pdo);?></a>
				</td>
				<?php		
				$stmt_ = $pdo->prepare($req_);
				$stmt_->bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
				$stmt_->execute();	
				$ligne=0;
				while($donnees_ = $stmt_->fetch())
				{
					$ligne++;
					$code_matiere = $donnees_['code_matiere'];
					$id_matiere = $donnees_['id_matiere'];
					$nbregarcon = getNbreMoyenneNiveauParSexe($idanneescolaire,$idposition,$idclasse,$id_matiere,$moyenne,"Masculin",$pdo);
					$nbrefille = getNbreMoyenneNiveauParSexe($idanneescolaire,$idposition,$idclasse,$id_matiere,$moyenne,"Feminin",$pdo);
					?>
					<th>
						<table width="100%">
						    <tr>
							    <td width="50%" style="border-right:1px dotted #000;text-align:center;"><?php echo $nbregarcon;?></td>
								<td width="50%" style="text-align:center;"><?php echo $nbrefille;?></td>
							</tr>
						</table>
					</th><?php
				}
				?>
			</tr><?php
		}
		$stmt_->closeCursor();
		$stmt_=NULL;
		$stmt->closeCursor();
		$stmt=NULL;
		?>
	</table><?php
}

function RapportActivite($idanneescolaire,$idposition,$structure,$moyenne,$pdo)
{
	//
	$req=(' SELECT  
					distinct
					classe.codeclasse as codeclasse,
					classe.idclasse as idclasse
					
			FROM classe
			WHERE
			classe.structure=:structure
			
			ORDER BY classe.codeclasse DESC');
			
	//
	$req_=(' SELECT  
					distinct
					matiere.code_matiere as code_matiere,
					matiere.id_matiere as id_matiere
					
			FROM matiere,classe,matierecoefficient
			WHERE
			classe.structure=:structure
			AND
			matierecoefficient.idclasse=classe.idclasse
			AND
			matierecoefficient.idmatiere=matiere.id_matiere
			AND
			matierecoefficient.statut=1
			AND
			matierecoefficient.coefficient<>0
			
			ORDER BY matiere.code_matiere DESC');
			
    ?>
	<table class="table table-striped projects" width="100%" style="font-size:11px;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="color:#000;">Ann&eacute;e scol.</th>
				<th style="color:#000;">Niveau(x)</th>
				<th style="color:#000;">Garcon(s)</th>
				<th style="color:#000;">Fille(s)</th>
				<?php		
				$stmt_ = $pdo->prepare($req_);
				$stmt_->bindParam(':structure', $structure, PDO::PARAM_STR);
				$stmt_->execute();	
				$ligne=0;
				while($donnees_ = $stmt_->fetch())
				{
					$ligne++;
					$code_matiere = $donnees_['code_matiere'];
					$id_matiere = $donnees_['id_matiere'];
					?>
					<th style="color:#000;"><?php echo $code_matiere;?></th><?php
				}
				?>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':structure', $structure, PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$codeclasse = $donnees['codeclasse'];
			$idclasse = $donnees['idclasse'];
			?>
			<tr>
				<td style="font-weight:bold;">
					<a></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo getNbreEleveParNiveau($idanneescolaire,1,"Masculin",$idclasse,$pdo);?></a>
				</td>
				<td>
					<a><?php echo getNbreEleveParNiveau($idanneescolaire,1,"Feminin",$idclasse,$pdo);?></a>
				</td>
				<?php		
				$stmt_ = $pdo->prepare($req_);
				$stmt_->bindParam(':structure', $structure, PDO::PARAM_STR);
				$stmt_->execute();	
				$ligne=0;
				while($donnees_ = $stmt_->fetch())
				{
					$ligne++;
					$code_matiere = $donnees_['code_matiere'];
					$id_matiere = $donnees_['id_matiere'];
					$nbregarcon = getNbreMoyenneNiveauParSexe($idanneescolaire,$idposition,$idclasse,$id_matiere,$moyenne,"Masculin",$pdo);
					$nbrefille = getNbreMoyenneNiveauParSexe($idanneescolaire,$idposition,$idclasse,$id_matiere,$moyenne,"Feminin",$pdo);
					?>
					<th>
						<table width="100%">
						    <tr>
							    <td width="50%" style="border-right:1px dotted #000;text-align:center;"><?php echo $nbregarcon;?></td>
								<td width="50%" style="text-align:center;"><?php echo $nbrefille;?></td>
							</tr>
						</table>
					</th><?php
				}
				?>
			</tr><?php
		}
		$stmt_->closeCursor();
		$stmt_=NULL;
		$stmt->closeCursor();
		$stmt=NULL;
		?>
	</table><?php
}

function getEntreeCompte($idAnneeScolaire,$idCompte,$DateSaisie,$pdo)
{
	$req=(' SELECT sum(entreesortie.montant) as Montant
	
			FROM entreesortie
			WHERE 
			entreesortie.comptemouvement=:idCompte
			AND
			entreesortie.idanneescolaire=:idAnneeScolaire
			AND
			entreesortie.statut=1
			AND
			entreesortie.idtypeentreesortie=1
			AND
			entreesortie.datesaisie<=:datesaisie');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
	$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
	$stmt->bindParam(':datesaisie', $DateSaisie, PDO::PARAM_STR);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['Montant'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getSortieCompte($idAnneeScolaire,$idCompte,$DateSaisie,$pdo)
{
	$req=(' SELECT sum(entreesortie.montant) as Montant
	
			FROM entreesortie
			WHERE 
			entreesortie.comptemouvement=:idCompte
			AND
			entreesortie.idanneescolaire=:idAnneeScolaire
			AND
			entreesortie.statut=1
			AND
			entreesortie.idtypeentreesortie=2
			AND
			entreesortie.datesaisie<=:datesaisie');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idCompte',$idCompte,PDO::PARAM_INT);
	$stmt->bindParam(':idAnneeScolaire',$idAnneeScolaire,PDO::PARAM_INT);
	$stmt->bindParam(':datesaisie',$DateSaisie,PDO::PARAM_STR);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['Montant'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function EtatRecouvrement($idpaiementtype,$libellepaiementtype,$libelleanneescolaire,$idanneescolaire,$pdo)
{
	$req=(' SELECT  distinct
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse
					
			FROM classe

			ORDER BY classe.idclasse,classe.codeclasse DESC');	
    ?>
	<table class="table table-striped" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;font-weight:bold;color:#000">Ann&eacute;e scolaire</th>
				<th style="width:10%;font-weight:bold;color:#000">Type de frais</th>
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:10%;font-weight:bold;color:#000">Effectif</th>
				<th style="width:10%;font-weight:bold;color:#000">Boursier</th>
				<th style="width:10%;font-weight:bold;color:#000">Total attendu</th>
				<th style="width:13%;font-weight:bold;color:#000">Total recouvr&eacute;</th>
				<th style="width:13%;font-weight:bold;color:#000">Total restant</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$total_totalattendu=0;
		$total_totalrecouvre=0;
		$total_totalrestant=0;
		$total_abandon=0;
		$total_effectif=0;
		$total_boursier=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$statutanneescolaire = 1;
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,0,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			
			$response_ = getMontantPaiementTypeClasseEffectif($idanneescolaire,$idclasse,$idpaiementtype,$montantpaiementtypeclasse,$pdo);
			$tab_ = explode("*",trim($response_));
			$totalattendu = $tab_[0];
			$nbrenonboursier = $tab_[1];
			$nbreouiboursier = $tab_[2];
			
			$totalrecouvre = getMontantPaiementFraisAnneeScolaire($idpaiementtypeclasse,$idanneescolaire,$pdo);
			$totalrestant = $totalattendu-$totalrecouvre;
			
			$total_totalattendu = $total_totalattendu+$totalattendu;
			$total_totalrecouvre = $total_totalrecouvre+$totalrecouvre;
			$total_totalrestant = $total_totalrestant+$totalrestant;
			$total_effectif = $total_effectif+$nbrenonboursier;
			$total_boursier = $total_boursier+$nbreouiboursier;
			?>
			<tr>
			    <td>
					<a href="#" onclick='window.open("EtatRecouvrementResultatDetailRapport.php?id=<?php echo $idclasse.'*'.$idpaiementtype.'*'.$idanneescolaire.'*'.$codeclasse;?>","", "fullscreen=yes, scrollbars=auto");' class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> Voir détail</a>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $libellepaiementtype;?></a>
			    </td>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo $nbrenonboursier;?></a>
			    </td>
				<td>
					<a><?php echo $nbreouiboursier;?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalattendu,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalrecouvre,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalrestant,"0",""," ");?></a>
			    </td>
			</tr><?php
        }
		?>
		<tr>
			<td colspan="2">
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					Total
				</h6>
			</td>
			<td></td>
			<td></td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_effectif,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_boursier,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_totalattendu,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_totalrecouvre,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_totalrestant,"0",""," ");?>
				</h6>
			</td>
		</tr><?php
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?>
	</table>
	<input type="hidden" name="nbreetatrecouvrement" value="<?php echo $ligne;?>"/>
	<?php
}

function EtatRecouvrementImprime($idpaiementtype,$libellepaiementtype,$libelleanneescolaire,$idanneescolaire,$pdo)
{
	$req=(' SELECT  distinct
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse
					
			FROM classe,salle,elevesalle,eleveanneescolaire
			WHERE
			classe.idclasse=salle.idclasse
			AND
			salle.id=elevesalle.idsalle
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			
			GROUP BY classe.idclasse,classe.codeclasse DESC');	
    ?>
	<table class="table table-striped" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:10%;font-weight:bold;color:#000">Effectif</th>
				<th style="width:10%;font-weight:bold;color:#000">Boursier</th>
				<th style="width:20%;font-weight:bold;color:#000">Total attendu</th>
				<th style="width:23%;font-weight:bold;color:#000">Total recouvr&eacute;</th>
				<th style="width:18%;font-weight:bold;color:#000">Total restant</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$total_totalattendu=0;
		$total_totalrecouvre=0;
		$total_totalrestant=0;
		$total_abandon=0;
		$total_effectif=0;
		$total_boursier=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$statutanneescolaire = 1;
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,0,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			
			$response_ = getMontantPaiementTypeClasseEffectif($idanneescolaire,$idclasse,$idpaiementtype,$montantpaiementtypeclasse,$pdo);
			$tab_ = explode("*",trim($response_));
			$totalattendu = $tab_[0];
			$nbrenonboursier = $tab_[1];
			$nbreouiboursier = $tab_[2];

			$totalrecouvre = getMontantPaiementFraisAnneeScolaire($idpaiementtypeclasse,$idanneescolaire,$pdo);
			$totalrestant = $totalattendu-$totalrecouvre;
			
			$total_totalattendu = $total_totalattendu+$totalattendu;
			$total_totalrecouvre = $total_totalrecouvre+$totalrecouvre;
			$total_totalrestant = $total_totalrestant+$totalrestant;
			$total_effectif = $total_effectif+$nbrenonboursier;
			$total_boursier = $total_boursier+$nbreouiboursier;
			?>
			<tr>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo $nbrenonboursier;?></a>
			    </td>
				<td>
					<a><?php echo $nbreouiboursier;?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalattendu,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalrecouvre,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalrestant,"0",""," ");?></a>
			    </td>
			</tr><?php
        }
		?>
		<tr>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					Total
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_effectif,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_boursier,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_totalattendu,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_totalrecouvre,"0",""," ");?>
				</h6>
			</td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($total_totalrestant,"0",""," ");?>
				</h6>
			</td>
		</tr><?php
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?>
	</table>
	<input type="hidden" name="nbreetatrecouvrement" value="<?php echo $ligne;?>"/>
	<?php
}

function EvaluationClasse($libelleanneescolaire,$idanneescolaire,$idposition,$iddomaine,$moyenne,$pdo)
{
	
	$req=(' SELECT  
					distinct
					salle.id as idsalle,
					salle.idclasse as idclasse,
					salle.codesalle as codesalle
					
			FROM bulletin,salle,classe
			WHERE
			bulletin.idposition=:idposition
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			classe.iddomaine=:iddomaine
		
			ORDER BY salle.id DESC');
    ?>
	<table class="table table-striped projects" width="100%" border=1>
		<thead>
			<tr style="background-color:#eee;">
				<th style="color:#000;width:10%">Classe</th>
				<th style="color:#000;width:10%;text-align:center;">Eff. Comp</th>
				<th style="color:#000;width:10%;text-align:center;">Nbre de moyenne</th>
				<th style="color:#000;width:20%;text-align:center;">Pourcentage (%)</th>
				<th style="color:#000;width:10%;text-align:center;">Moy. gale</th>
				<th style="color:#000;width:20%;text-align:center;">Premi&egrave;re de la classe</th>
				<th style="color:#000;width:20%;text-align:center;">Derni&egrave;re de la classe</th>
			</tr>
			<tr>
				<th style="color:#000;width:10%"></th>
				<th style="color:#000;width:10%">
				    <table width="100%">
					    <tr>
						    <td width="33%">G</td>
							<td width="33%">F</td>
							<td width="33%">T</td>
						</tr>
					</table>	
				</th>
				<th style="color:#000;width:10%">
				    <table width="100%">
					    <tr>
						    <td width="33%">G</td>
							<td width="33%">F</td>
							<td width="33%">T</td>
						</tr>
					</table>	
				</th>
				<th style="color:#000;width:20%">
				    <table width="100%">
					    <tr>
						    <td width="33%">G</td>
							<td width="33%">F</td>
							<td width="33%">T</td>
						</tr>
					</table>	
				</th>
				<th style="color:#000;width:10%"></th>
				<th style="color:#000;width:20%">
				    <table width="100%">
					    <tr>
						    <td width="80%">Nom & pr&eacute;nom(s)</td>
							<td width="20%">Moy.</td>
						</tr>
					</table>	
				</th>
				<th style="color:#000;width:20%">
				    <table width="100%">
					    <tr>
						    <td width="80%">Nom & pr&eacute;nom(s)</td>
							<td width="20%">Moy.</td>
						</tr>
					</table>	
				</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':iddomaine',$iddomaine,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$codesalle=$donnees['codesalle'];
			$idclasse=$donnees['idclasse'];
			$idsalle=$donnees['idsalle'];
			$Eff_Garcon=getNbreEleveParClasseParSexe(1,"Masculin",$idsalle,$idposition,$idanneescolaire,$pdo);
			if($Eff_Garcon==0)
			{
				$Eff_Garcon=1;
			}
			$Eff_Fille=getNbreEleveParClasseParSexe(1,"Feminin",$idsalle,$idposition,$idanneescolaire,$pdo);
			$Eff_Total=$Eff_Garcon+$Eff_Fille;
			
			$NbreMoyenneGarcon = getNbreMoyenneParClasseParSexe($idanneescolaire,$idposition,$idsalle,$moyenne,"Masculin",$pdo);
			$NbreMoyenneFille = getNbreMoyenneParClasseParSexe($idanneescolaire,$idposition,$idsalle,$moyenne,"Feminin",$pdo);
			$NbreMoyenne_Total=$NbreMoyenneGarcon+$NbreMoyenneFille;
			
			$PourcentageGarcon=round(($NbreMoyenneGarcon/$Eff_Garcon)*100,2);
			$PourcentageFille=round(($NbreMoyenneFille/$Eff_Fille)*100,2);
			$PourcentageTotal=round(($NbreMoyenne_Total/$Eff_Total)*100,2);
			
			$MoyenneGeneral=round(getMoyenneGeneralTrimestre($idanneescolaire,$idposition,$idsalle,$pdo),2);
			$MoyennePlusForte=getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo);
			$MoyennePlusFaible=round(trim(getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo)),2);
			
			$EleveMoyennePlusForteSalle=getEleveMoyennePlusForteSalle($idanneescolaire,$idposition,$idsalle,$pdo);
			$EleveMoyennePlusFaibleSalle=getEleveMoyennePlusFaibleSalle($idanneescolaire,$idposition,$idsalle,$pdo);
			?>
			<tr>
				<th style="width:10%"><?php echo $codesalle;?></th>
				<th style="width:10%">
				    <table width="100%">
					    <tr>
						    <td width="33%"><?php echo $Eff_Garcon;?></td>
							<td width="33%"><?php echo $Eff_Fille;?></td>
							<td width="33%"><?php echo $Eff_Total;?></td>
						</tr>
					</table>	
				</th>
				<th style="width:10%">
				    <table width="100%">
					    <tr>
						    <td width="33%"><?php echo $NbreMoyenneGarcon;?></td>
							<td width="33%"><?php echo $NbreMoyenneFille;?></td>
							<td width="33%"><?php echo $NbreMoyenne_Total;?></td>
						</tr>
					</table>	
				</th>
				<th style="width:20%">
				    <table width="100%">
					    <tr>
						    <td width="33%"><?php echo $PourcentageGarcon;?></td>
							<td width="33%"><?php echo $PourcentageFille;?></td>
							<td width="33%"><?php echo $PourcentageTotal;?></td>
						</tr>
					</table>	
				</th>
				<th style="width:10%"><?php echo $MoyenneGeneral;?></th>
				<th style="width:20%">
				    <table width="100%">
					    <tr>
						    <td width="80%"><?php echo $EleveMoyennePlusForteSalle;?></td>
							<td width="20%"><?php echo $MoyennePlusForte;?></td>
						</tr>
					</table>	
				</th>
				<th style="width:20%">
				    <table width="100%">
					    <tr>
						    <td width="80%"><?php echo $EleveMoyennePlusFaibleSalle;?></td>
							<td width="20%"><?php echo $MoyennePlusFaible;?></td>
						</tr>
					</table>	
				</th>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
		<tr style="background-color:#eee;">
			<th style="width:10%"></th>
			<th style="width:10%"></th>
			<th style="width:10%"></th>
			<th style="width:20%"></th>
			<th style="width:10%"></th>
			<th style="width:20%"></th>
			<th style="width:20%"></th>
		</tr>
	</table><?php
}

function ListNumeroEvaluation($pdo)
{
	$req=(' SELECT  
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					salle.id as idsalle,
					salle.codesalle as codesalle,
					position.idposition as idposition,
					position.libposition as libposition,
					count(elevesalle.id) as nbreelevesalle,
					count(numero_evaluation.id) as nbrenumero

			FROM classe,salle,anneescolaire,elevesalle,eleveanneescolaire,numero_evaluation,position
			WHERE
			classe.idclasse=salle.idclasse
			AND
			salle.id=elevesalle.idsalle
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			numero_evaluation.idposition=position.idposition
			
			GROUP BY anneescolaire.id,classe.idclasse,salle.id,position.idposition
			
			ORDER BY salle.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
				<th style="width:13%;font-weight:bold;color:#000">Ann&eacute;e scol.</th>
				<th style="width:13%;font-weight:bold;color:#000">&Eacute;valuation</th>
				<th style="width:13%;font-weight:bold;color:#000">Niveau</th>
				<th style="width:13%;font-weight:bold;color:#000">Classe</th>
				<th style="width:13%;font-weight:bold;color:#000">Effectif</th>
				<th style="width:13%;font-weight:bold;color:#000">Numéro(s) généré(s)</th>
				<th style="width:13%;font-weight:bold;color:#000">Imprimer Fiche(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$fichier ="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$idsalle = $donnees['idsalle'];
			$codesalle = $donnees['codesalle'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			$nbreelevesalle = $donnees['nbreelevesalle'];
			$nbrenumero = $donnees['nbrenumero'];
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idanneescolaire.'*'.$libelleanneescolaire.'*'.$idsalle.'*'.$codesalle;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $libposition;?></a>
			    </td>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo $codesalle;?></a>
			    </td>
				<td>
					<a><?php echo $nbreelevesalle;?></a>
			    </td>
				<td>
					<a><?php echo $nbrenumero;?></a>
			    </td>
				<td>
					<a href="#" onclick='window.open("EtatFicheNumeroAnonymat.php?id=<?php echo $idclasse.'*'.$idpaiementtype.'*'.$idanneescolaire.'*'.$codeclasse;?>","", "fullscreen=yes, scrollbars=auto");' class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> N° Anonymat</a>
					&nbsp;&nbsp;
					<a href="#" onclick='window.open("EtatFicheNumeroTable.php?id=<?php echo $idclasse.'*'.$idpaiementtype.'*'.$idanneescolaire.'*'.$codeclasse;?>","", "fullscreen=yes, scrollbars=auto");' class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> N° Table</a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?>
	</table>
	<input type="hidden" name="nbrenumerogenere" value="<?php echo $ligne;?>"/><?php
}

function AnalyseResultat($idanneescolaire,$idposition,$idclasse,$pdo)
{
	$libelleclasse = getNomClasse($idclasse,$pdo);
	
	$req=(' SELECT matiere.id_matiere,
				   matiere.code_matiere
				   
			FROM matiere
			WHERE
			matiere.id_matiere in (16,6,5,3,8,7,15)');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="background-color:#fff;font-size:13px;">
		<tr style="background-color:#eee;">
			<th style="color:#000;"></th>
			<th style="color:#000;">Classe</th>
			<th style="color:#000;">Matière(s)</th>
			<th style="color:#000;text-align:center"></th>
			<th style="color:#000;text-align:center"></th>
			<th style="color:#000;text-align:center"></th>
			<th style="color:#000;text-align:center"></th>
			<th style="color:#000;text-align:center" colspan="2">0 &le; N &lt; 6</th>
			<th style="color:#000;text-align:center" colspan="2">6 &le; N &lt; 10</th>
			<th style="color:#000;text-align:center" colspan="2">10 &le; N &lt; 15</th>
			<th style="color:#000;text-align:center" colspan="2">15 &le; N &lt; 20</th>
		</tr>
		<tr style="background-color:#eee;">
			<th style="color:#000;"></th>
			<th style="color:#000;"></th>
			<th style="color:#000;"></th>
			<th style="color:#000;">COMPOSANTS</th>
			<th style="color:#000;">N. MIN</th>
			<th style="color:#000;">N. MAX</th>
			<th style="color:#000;">Moy. Notes</th>
			<th style="color:#000;">EFFECT</th>
			<th style="color:#000;">%</th>
			<th style="color:#000;">EFFECT</th>
			<th style="color:#000;">%</th>
			<th style="color:#000;">EFFECT</th>
			<th style="color:#000;">%</th>
			<th style="color:#000;">EFFECT</th>
			<th style="color:#000;">%</th>
		</tr>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;		
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];

			$NbreComposantes = getNbreComposantes($idanneescolaire,$idposition,$id_matiere,$idclasse,$pdo);
			$NbreNMin = getNbreNoteMinimal($idanneescolaire,$idposition,$id_matiere,$idclasse,$pdo);
			$NbreNMax = getNbreNoteMaximal($idanneescolaire,$idposition,$id_matiere,$idclasse,$pdo);
			$SumNote = getSumNote($idanneescolaire,$idposition,$id_matiere,$idclasse,$pdo);
			$Effect_0_6 = getNbreNoteParInterval($idanneescolaire,$idposition,$id_matiere,$idclasse,0,6,$pdo);
			$Effect_6_10 = getNbreNoteParInterval($idanneescolaire,$idposition,$id_matiere,$idclasse,6,10,$pdo);
			$Effect_10_15 = getNbreNoteParInterval($idanneescolaire,$idposition,$id_matiere,$idclasse,10,15,$pdo);
			$Effect_15_20 = getNbreNoteParInterval($idanneescolaire,$idposition,$id_matiere,$idclasse,15,20,$pdo);
			?>
			<tr>
				<td style="font-weight:bold;">
					<a></a>
				</td>
				<td style="font-weight:bold;">
					<a><?php echo $libelleclasse;?></a>
				</td>
				<td style="font-weight:bold;">
					<a><?php echo $code_matiere;?></a>
				</td>
				<td>
					<a><?php echo $NbreComposantes;?></a>
				</td>
				<td>
					<a><?php echo $NbreNMin;?></a>
				</td>
				<td>
					<a><?php echo $NbreNMax;?></a>
				</td>
				<td>
					<a><?php if($NbreComposantes!=0) echo (round(($SumNote/$NbreComposantes)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php echo $Effect_0_6;?></a>
				</td>
				<td>
					<a><?php if($NbreComposantes!=0) echo (round(($Effect_0_6/$NbreComposantes)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php echo $Effect_6_10;?></a>
				</td>
				<td>
					<a><?php if($NbreComposantes!=0) echo (round(($Effect_6_10/$NbreComposantes)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php echo $Effect_10_15;?></a>
				</td>
				<td>
					<a><?php if($NbreComposantes!=0) echo (round(($Effect_10_15/$NbreComposantes)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php echo $Effect_15_20;?></a>
				</td>
				<td>
					<a><?php if($NbreComposantes!=0) echo (round(($Effect_15_20/$NbreComposantes)*100,2)).'%';?></a>
				</td>
			</tr><?php
		}
		?>
	</table><?php
}

function EvaluationNoteCompositionCollege($idanneescolaire,$idposition,$idsalle,$codesalle,$pdo)
{
    ?>
	<table class="table table-striped table-bordered" width="100%" style="background-color:#fff;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;"><?php echo $codesalle;?></th>
				<th style="width:5%;">0</th>
				<th style="width:5%;">1</th>
				<th style="width:5%;">2</th>
				<th style="width:5%;">3</th>
				<th style="width:5%;">4</th>
				<th style="width:5%;">5</th>
				<th style="width:5%;">6</th>
				<th style="width:5%;">7</th>
				<th style="width:5%;">8</th>
				<th style="width:5%;">9</th>
				<th style="width:5%;">10</th>
				<th style="width:5%;">11</th>
				<th style="width:5%;">12</th>
				<th style="width:5%;">13</th>
				<th style="width:5%;">14</th>
				<th style="width:5%;">15</th>
				<th style="width:5%;">16</th>
				<th style="width:5%;">17</th>
				<th style="width:5%;">18</th>
				<th style="width:5%;">19</th>
				<th style="width:5%;">20</th>
				<th style="width:5%;">Total</th>
			</tr>
		</thead>
		<?php			
		$FR_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,0,$pdo);
		$FR_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,1,$pdo);
		$FR_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,2,$pdo);
		$FR_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,3,$pdo);
		$FR_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,4,$pdo);
		$FR_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,5,$pdo);
		$FR_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,6,$pdo);
		$FR_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,7,$pdo);
		$FR_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,8,$pdo);
		$FR_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,9,$pdo);
		$FR_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,10,$pdo);
		$FR_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,11,$pdo);
		$FR_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,12,$pdo);
		$FR_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,13,$pdo);
		$FR_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,14,$pdo);
		$FR_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,15,$pdo);
		$FR_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,16,$pdo);
		$FR_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,17,$pdo);
		$FR_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,18,$pdo);
		$FR_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,19,$pdo);
		$FR_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,16,$idsalle,20,$pdo);
		
		$FR_Nbre_0_Total_L = $FR_Nbre_0+$FR_Nbre_1+$FR_Nbre_2+$FR_Nbre_3+$FR_Nbre_4+$FR_Nbre_5+$FR_Nbre_6+$FR_Nbre_7+$FR_Nbre_8+$FR_Nbre_9+$FR_Nbre_10+
							 $FR_Nbre_11+$FR_Nbre_12+$FR_Nbre_13+$FR_Nbre_14+$FR_Nbre_15+$FR_Nbre_16+$FR_Nbre_17+$FR_Nbre_18+$FR_Nbre_19+$FR_Nbre_20;

		$ANG_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,0,$pdo);
		$ANG_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,1,$pdo);
		$ANG_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,2,$pdo);
		$ANG_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,3,$pdo);
		$ANG_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,4,$pdo);
		$ANG_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,5,$pdo);
		$ANG_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,6,$pdo);
		$ANG_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,7,$pdo);
		$ANG_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,8,$pdo);
		$ANG_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,9,$pdo);
		$ANG_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,10,$pdo);
		$ANG_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,11,$pdo);
		$ANG_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,12,$pdo);
		$ANG_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,13,$pdo);
		$ANG_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,14,$pdo);
		$ANG_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,15,$pdo);
		$ANG_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,16,$pdo);
		$ANG_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,17,$pdo);
		$ANG_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,18,$pdo);
		$ANG_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,19,$pdo);
		$ANG_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,20,$pdo);
		
		$ANG_Nbre_0_Total_L = $ANG_Nbre_0+$ANG_Nbre_1+$ANG_Nbre_2+$ANG_Nbre_3+$ANG_Nbre_4+$ANG_Nbre_5+$ANG_Nbre_6+$ANG_Nbre_7+$ANG_Nbre_8+$ANG_Nbre_9+$ANG_Nbre_10+
							  $ANG_Nbre_11+$ANG_Nbre_12+$ANG_Nbre_13+$ANG_Nbre_14+$ANG_Nbre_15+$ANG_Nbre_16+$ANG_Nbre_17+$ANG_Nbre_18+$ANG_Nbre_19+$ANG_Nbre_20;
							 
		$HG_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,0,$pdo);
		$HG_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,1,$pdo);
		$HG_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,2,$pdo);
		$HG_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,3,$pdo);
		$HG_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,4,$pdo);
		$HG_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,5,$pdo);
		$HG_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,6,$pdo);
		$HG_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,7,$pdo);
		$HG_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,8,$pdo);
		$HG_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,9,$pdo);
		$HG_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,10,$pdo);
		$HG_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,11,$pdo);
		$HG_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,12,$pdo);
		$HG_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,13,$pdo);
		$HG_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,14,$pdo);
		$HG_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,15,$pdo);
		$HG_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,16,$pdo);
		$HG_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,17,$pdo);
		$HG_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,18,$pdo);
		$HG_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,19,$pdo);
		$HG_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,20,$pdo);
		
		$HG_Nbre_0_Total_L = $HG_Nbre_0+$HG_Nbre_1+$HG_Nbre_2+$HG_Nbre_3+$HG_Nbre_4+$HG_Nbre_5+$HG_Nbre_6+$HG_Nbre_7+$HG_Nbre_8+$HG_Nbre_9+$HG_Nbre_10+
							 $HG_Nbre_11+$HG_Nbre_12+$HG_Nbre_13+$HG_Nbre_14+$HG_Nbre_15+$HG_Nbre_16+$HG_Nbre_17+$HG_Nbre_18+$HG_Nbre_19+$HG_Nbre_20;
		
		$MATHS_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,0,$pdo);
		$MATHS_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,1,$pdo);
		$MATHS_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,2,$pdo);
		$MATHS_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,3,$pdo);
		$MATHS_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,4,$pdo);
		$MATHS_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,5,$pdo);
		$MATHS_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,6,$pdo);
		$MATHS_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,7,$pdo);
		$MATHS_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,8,$pdo);
		$MATHS_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,9,$pdo);
		$MATHS_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,10,$pdo);
		$MATHS_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,11,$pdo);
		$MATHS_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,12,$pdo);
		$MATHS_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,13,$pdo);
		$MATHS_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,14,$pdo);
		$MATHS_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,15,$pdo);
		$MATHS_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,16,$pdo);
		$MATHS_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,17,$pdo);
		$MATHS_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,18,$pdo);
		$MATHS_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,19,$pdo);
		$MATHS_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,20,$pdo);
		
		$MATHS_Nbre_0_Total_L = $MATHS_Nbre_0+$MATHS_Nbre_1+$MATHS_Nbre_2+$MATHS_Nbre_3+$MATHS_Nbre_4+$MATHS_Nbre_5+$MATHS_Nbre_6+$MATHS_Nbre_7+$MATHS_Nbre_8+$MATHS_Nbre_9+$MATHS_Nbre_10+
								$MATHS_Nbre_11+$MATHS_Nbre_12+$MATHS_Nbre_13+$MATHS_Nbre_14+$MATHS_Nbre_15+$MATHS_Nbre_16+$MATHS_Nbre_17+$MATHS_Nbre_18+$MATHS_Nbre_19+$MATHS_Nbre_20;
							 
		$PCT_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,0,$pdo);
		$PCT_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,1,$pdo);
		$PCT_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,2,$pdo);
		$PCT_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,3,$pdo);
		$PCT_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,4,$pdo);
		$PCT_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,5,$pdo);
		$PCT_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,6,$pdo);
		$PCT_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,7,$pdo);
		$PCT_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,8,$pdo);
		$PCT_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,9,$pdo);
		$PCT_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,10,$pdo);
		$PCT_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,11,$pdo);
		$PCT_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,12,$pdo);
		$PCT_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,13,$pdo);
		$PCT_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,14,$pdo);
		$PCT_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,15,$pdo);
		$PCT_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,16,$pdo);
		$PCT_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,17,$pdo);
		$PCT_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,18,$pdo);
		$PCT_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,19,$pdo);
		$PCT_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,20,$pdo);
		
		$PCT_Nbre_0_Total_L = $PCT_Nbre_0+$PCT_Nbre_1+$PCT_Nbre_2+$PCT_Nbre_3+$PCT_Nbre_4+$PCT_Nbre_5+$PCT_Nbre_6+$PCT_Nbre_7+$PCT_Nbre_8+$PCT_Nbre_9+$PCT_Nbre_10+
							  $PCT_Nbre_11+$PCT_Nbre_12+$PCT_Nbre_13+$PCT_Nbre_14+$PCT_Nbre_15+$PCT_Nbre_16+$PCT_Nbre_17+$PCT_Nbre_18+$PCT_Nbre_19+$PCT_Nbre_20;
		
		$SVT_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,0,$pdo);
		$SVT_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,1,$pdo);
		$SVT_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,2,$pdo);
		$SVT_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,3,$pdo);
		$SVT_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,4,$pdo);
		$SVT_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,5,$pdo);
		$SVT_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,6,$pdo);
		$SVT_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,7,$pdo);
		$SVT_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,8,$pdo);
		$SVT_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,9,$pdo);
		$SVT_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,10,$pdo);
		$SVT_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,11,$pdo);
		$SVT_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,12,$pdo);
		$SVT_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,13,$pdo);
		$SVT_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,14,$pdo);
		$SVT_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,15,$pdo);
		$SVT_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,16,$pdo);
		$SVT_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,17,$pdo);
		$SVT_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,18,$pdo);
		$SVT_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,19,$pdo);
		$SVT_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,20,$pdo);
		
		$SVT_Nbre_0_Total_L = $SVT_Nbre_0+$SVT_Nbre_1+$SVT_Nbre_2+$SVT_Nbre_3+$SVT_Nbre_4+$SVT_Nbre_5+$SVT_Nbre_6+$SVT_Nbre_7+$SVT_Nbre_8+$SVT_Nbre_9+$SVT_Nbre_10+
							  $SVT_Nbre_11+$SVT_Nbre_12+$SVT_Nbre_13+$SVT_Nbre_14+$SVT_Nbre_15+$SVT_Nbre_16+$SVT_Nbre_17+$SVT_Nbre_18+$SVT_Nbre_19+$SVT_Nbre_20;
		
		$ECM_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,0,$pdo);
		$ECM_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,1,$pdo);
		$ECM_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,2,$pdo);
		$ECM_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,3,$pdo);
		$ECM_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,4,$pdo);
		$ECM_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,5,$pdo);
		$ECM_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,6,$pdo);
		$ECM_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,7,$pdo);
		$ECM_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,8,$pdo);
		$ECM_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,9,$pdo);
		$ECM_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,10,$pdo);
		$ECM_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,11,$pdo);
		$ECM_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,12,$pdo);
		$ECM_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,13,$pdo);
		$ECM_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,14,$pdo);
		$ECM_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,15,$pdo);
		$ECM_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,16,$pdo);
		$ECM_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,17,$pdo);
		$ECM_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,18,$pdo);
		$ECM_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,19,$pdo);
		$ECM_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,20,$pdo);
		
		$ECM_Nbre_0_Total_L = $ECM_Nbre_0+$ECM_Nbre_1+$ECM_Nbre_2+$ECM_Nbre_3+$ECM_Nbre_4+$ECM_Nbre_5+$ECM_Nbre_6+$ECM_Nbre_7+$ECM_Nbre_8+$ECM_Nbre_9+$ECM_Nbre_10+
							  $ECM_Nbre_11+$ECM_Nbre_12+$ECM_Nbre_13+$ECM_Nbre_14+$ECM_Nbre_15+$ECM_Nbre_16+$ECM_Nbre_17+$ECM_Nbre_18+$ECM_Nbre_19+$ECM_Nbre_20;
		
		$Nbre_0_Total_C = $FR_Nbre_0+$ANG_Nbre_0+$HG_Nbre_0+$ECM_Nbre_0+$MATHS_Nbre_0+$PCT_Nbre_0+$SVT_Nbre_0;
		$Nbre_1_Total_C = $FR_Nbre_1+$ANG_Nbre_1+$HG_Nbre_1+$ECM_Nbre_1+$MATHS_Nbre_1+$PCT_Nbre_1+$SVT_Nbre_1;
		$Nbre_2_Total_C = $FR_Nbre_2+$ANG_Nbre_2+$HG_Nbre_2+$ECM_Nbre_2+$MATHS_Nbre_2+$PCT_Nbre_2+$SVT_Nbre_2;
		$Nbre_3_Total_C = $FR_Nbre_3+$ANG_Nbre_3+$HG_Nbre_3+$ECM_Nbre_3+$MATHS_Nbre_3+$PCT_Nbre_3+$SVT_Nbre_3;
		$Nbre_4_Total_C = $FR_Nbre_4+$ANG_Nbre_4+$HG_Nbre_4+$ECM_Nbre_4+$MATHS_Nbre_4+$PCT_Nbre_4+$SVT_Nbre_4;
		$Nbre_5_Total_C = $FR_Nbre_5+$ANG_Nbre_5+$HG_Nbre_5+$ECM_Nbre_5+$MATHS_Nbre_5+$PCT_Nbre_5+$SVT_Nbre_5;
		$Nbre_6_Total_C = $FR_Nbre_6+$ANG_Nbre_6+$HG_Nbre_6+$ECM_Nbre_6+$MATHS_Nbre_6+$PCT_Nbre_6+$SVT_Nbre_6;
		$Nbre_7_Total_C = $FR_Nbre_7+$ANG_Nbre_7+$HG_Nbre_7+$ECM_Nbre_7+$MATHS_Nbre_7+$PCT_Nbre_7+$SVT_Nbre_7;
		$Nbre_8_Total_C = $FR_Nbre_8+$ANG_Nbre_8+$HG_Nbre_8+$ECM_Nbre_8+$MATHS_Nbre_8+$PCT_Nbre_8+$SVT_Nbre_8;
		$Nbre_9_Total_C = $FR_Nbre_9+$ANG_Nbre_9+$HG_Nbre_9+$ECM_Nbre_9+$MATHS_Nbre_9+$PCT_Nbre_9+$SVT_Nbre_9;
		$Nbre_10_Total_C = $FR_Nbre_10+$ANG_Nbre_10+$HG_Nbre_10+$ECM_Nbre_10+$MATHS_Nbre_0+$PCT_Nbre_10+$SVT_Nbre_10;
		$Nbre_11_Total_C = $FR_Nbre_11+$ANG_Nbre_11+$HG_Nbre_11+$ECM_Nbre_11+$MATHS_Nbre_11+$PCT_Nbre_11+$SVT_Nbre_11;
		$Nbre_12_Total_C = $FR_Nbre_12+$ANG_Nbre_12+$HG_Nbre_12+$ECM_Nbre_12+$MATHS_Nbre_12+$PCT_Nbre_12+$SVT_Nbre_12;
		$Nbre_13_Total_C = $FR_Nbre_13+$ANG_Nbre_13+$HG_Nbre_13+$ECM_Nbre_13+$MATHS_Nbre_13+$PCT_Nbre_13+$SVT_Nbre_13;
		$Nbre_14_Total_C = $FR_Nbre_14+$ANG_Nbre_14+$HG_Nbre_14+$ECM_Nbre_14+$MATHS_Nbre_14+$PCT_Nbre_14+$SVT_Nbre_14;
		$Nbre_15_Total_C = $FR_Nbre_15+$ANG_Nbre_15+$HG_Nbre_15+$ECM_Nbre_15+$MATHS_Nbre_15+$PCT_Nbre_15+$SVT_Nbre_15;
		$Nbre_16_Total_C = $FR_Nbre_16+$ANG_Nbre_16+$HG_Nbre_16+$ECM_Nbre_16+$MATHS_Nbre_16+$PCT_Nbre_16+$SVT_Nbre_16;
		$Nbre_17_Total_C = $FR_Nbre_17+$ANG_Nbre_17+$HG_Nbre_17+$ECM_Nbre_17+$MATHS_Nbre_17+$PCT_Nbre_17+$SVT_Nbre_17;
		$Nbre_18_Total_C = $FR_Nbre_18+$ANG_Nbre_18+$HG_Nbre_18+$ECM_Nbre_18+$MATHS_Nbre_18+$PCT_Nbre_18+$SVT_Nbre_18;
		$Nbre_19_Total_C = $FR_Nbre_19+$ANG_Nbre_19+$HG_Nbre_19+$ECM_Nbre_19+$MATHS_Nbre_19+$PCT_Nbre_19+$SVT_Nbre_19;
		$Nbre_20_Total_C = $FR_Nbre_20+$ANG_Nbre_20+$HG_Nbre_20+$ECM_Nbre_20+$MATHS_Nbre_20+$PCT_Nbre_20+$SVT_Nbre_20;
			
		?>
		<tr>
			<td>
				<a>FR</a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>ANG</a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>HG</a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>ECM</a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>MATHS</a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>PCT</a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>SVT</a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>TOTAL</a>
			</td>
			<td>
				<a><?php echo $Nbre_0_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_1_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_2_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_3_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_4_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_5_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_6_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_7_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_8_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_9_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_10_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_11_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_12_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_13_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_14_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_15_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_16_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_17_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_18_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_19_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_20_Total_C;?></a>
			</td>
		</tr>
	</table><?php
}

function EvaluationNoteCompositionLycee($idanneescolaire,$idposition,$idsalle,$codesalle,$pdo)
{	
    ?>
	<table class="table table-striped table-bordered" width="100%" style="background-color:#fff;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;"><?php echo $codesalle;?></th>
				<th style="width:5%;">0</th>
				<th style="width:5%;">1</th>
				<th style="width:5%;">2</th>
				<th style="width:5%;">3</th>
				<th style="width:5%;">4</th>
				<th style="width:5%;">5</th>
				<th style="width:5%;">6</th>
				<th style="width:5%;">7</th>
				<th style="width:5%;">8</th>
				<th style="width:5%;">9</th>
				<th style="width:5%;">10</th>
				<th style="width:5%;">11</th>
				<th style="width:5%;">12</th>
				<th style="width:5%;">13</th>
				<th style="width:5%;">14</th>
				<th style="width:5%;">15</th>
				<th style="width:5%;">16</th>
				<th style="width:5%;">17</th>
				<th style="width:5%;">18</th>
				<th style="width:5%;">19</th>
				<th style="width:5%;">20</th>
				<th style="width:5%;">Total</th>
			</tr>
		</thead>
		<?php		
		$ligne=0;
		$ligne_N_0=0;
		$ligne_N_1=0;
		$ligne_N_2=0;
		$ligne_N_3=0;
		$ligne_N_4=0;
		$ligne_N_5=0;
		$ligne_N_6=0;
		$ligne_N_7=0;
		$ligne_N_8=0;
		$ligne_N_9=0;
		$ligne_N_10=0;
		$ligne_N_11=0;
		$ligne_N_12=0;
		$ligne_N_13=0;
		$ligne_N_14=0;
		$ligne_N_15=0;
		$ligne_N_16=0;
		$ligne_N_17=0;
		$ligne_N_18=0;
		$ligne_N_19=0;
		$ligne_N_20=0;
		
		$ligne++;
			
		$FR_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,0,$pdo);
		$FR_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,1,$pdo);
		$FR_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,2,$pdo);
		$FR_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,3,$pdo);
		$FR_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,4,$pdo);
		$FR_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,5,$pdo);
		$FR_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,6,$pdo);
		$FR_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,7,$pdo);
		$FR_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,8,$pdo);
		$FR_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,9,$pdo);
		$FR_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,10,$pdo);
		$FR_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,11,$pdo);
		$FR_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,12,$pdo);
		$FR_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,13,$pdo);
		$FR_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,14,$pdo);
		$FR_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,15,$pdo);
		$FR_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,16,$pdo);
		$FR_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,17,$pdo);
		$FR_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,18,$pdo);
		$FR_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,19,$pdo);
		$FR_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,23,$idsalle,20,$pdo);
		
		$FR_Nbre_0_Total_L = $FR_Nbre_0+$FR_Nbre_1+$FR_Nbre_2+$FR_Nbre_3+$FR_Nbre_4+$FR_Nbre_5+$FR_Nbre_6+$FR_Nbre_7+$FR_Nbre_8+$FR_Nbre_9+$FR_Nbre_10+
							 $FR_Nbre_11+$FR_Nbre_12+$FR_Nbre_13+$FR_Nbre_14+$FR_Nbre_15+$FR_Nbre_16+$FR_Nbre_17+$FR_Nbre_18+$FR_Nbre_19+$FR_Nbre_20;

		$ANG_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,0,$pdo);
		$ANG_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,1,$pdo);
		$ANG_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,2,$pdo);
		$ANG_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,3,$pdo);
		$ANG_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,4,$pdo);
		$ANG_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,5,$pdo);
		$ANG_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,6,$pdo);
		$ANG_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,7,$pdo);
		$ANG_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,8,$pdo);
		$ANG_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,9,$pdo);
		$ANG_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,10,$pdo);
		$ANG_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,11,$pdo);
		$ANG_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,12,$pdo);
		$ANG_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,13,$pdo);
		$ANG_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,14,$pdo);
		$ANG_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,15,$pdo);
		$ANG_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,16,$pdo);
		$ANG_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,17,$pdo);
		$ANG_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,18,$pdo);
		$ANG_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,19,$pdo);
		$ANG_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,3,$idsalle,20,$pdo);
		
		$ANG_Nbre_0_Total_L = $ANG_Nbre_0+$ANG_Nbre_1+$ANG_Nbre_2+$ANG_Nbre_3+$ANG_Nbre_4+$ANG_Nbre_5+$ANG_Nbre_6+$ANG_Nbre_7+$ANG_Nbre_8+$ANG_Nbre_9+$ANG_Nbre_10+
							  $ANG_Nbre_11+$ANG_Nbre_12+$ANG_Nbre_13+$ANG_Nbre_14+$ANG_Nbre_15+$ANG_Nbre_16+$ANG_Nbre_17+$ANG_Nbre_18+$ANG_Nbre_19+$ANG_Nbre_20;
							 
		$HG_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,0,$pdo);
		$HG_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,1,$pdo);
		$HG_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,2,$pdo);
		$HG_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,3,$pdo);
		$HG_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,4,$pdo);
		$HG_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,5,$pdo);
		$HG_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,6,$pdo);
		$HG_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,7,$pdo);
		$HG_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,8,$pdo);
		$HG_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,9,$pdo);
		$HG_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,10,$pdo);
		$HG_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,11,$pdo);
		$HG_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,12,$pdo);
		$HG_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,13,$pdo);
		$HG_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,14,$pdo);
		$HG_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,15,$pdo);
		$HG_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,16,$pdo);
		$HG_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,17,$pdo);
		$HG_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,18,$pdo);
		$HG_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,19,$pdo);
		$HG_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,5,$idsalle,20,$pdo);
		
		$HG_Nbre_0_Total_L = $HG_Nbre_0+$HG_Nbre_1+$HG_Nbre_2+$HG_Nbre_3+$HG_Nbre_4+$HG_Nbre_5+$HG_Nbre_6+$HG_Nbre_7+$HG_Nbre_8+$HG_Nbre_9+$HG_Nbre_10+
							 $HG_Nbre_11+$HG_Nbre_12+$HG_Nbre_13+$HG_Nbre_14+$HG_Nbre_15+$HG_Nbre_16+$HG_Nbre_17+$HG_Nbre_18+$HG_Nbre_19+$HG_Nbre_20;
		
		$MATHS_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,0,$pdo);
		$MATHS_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,1,$pdo);
		$MATHS_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,2,$pdo);
		$MATHS_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,3,$pdo);
		$MATHS_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,4,$pdo);
		$MATHS_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,5,$pdo);
		$MATHS_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,6,$pdo);
		$MATHS_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,7,$pdo);
		$MATHS_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,8,$pdo);
		$MATHS_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,9,$pdo);
		$MATHS_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,10,$pdo);
		$MATHS_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,11,$pdo);
		$MATHS_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,12,$pdo);
		$MATHS_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,13,$pdo);
		$MATHS_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,14,$pdo);
		$MATHS_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,15,$pdo);
		$MATHS_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,16,$pdo);
		$MATHS_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,17,$pdo);
		$MATHS_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,18,$pdo);
		$MATHS_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,19,$pdo);
		$MATHS_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,6,$idsalle,20,$pdo);
		
		$MATHS_Nbre_0_Total_L = $MATHS_Nbre_0+$MATHS_Nbre_1+$MATHS_Nbre_2+$MATHS_Nbre_3+$MATHS_Nbre_4+$MATHS_Nbre_5+$MATHS_Nbre_6+$MATHS_Nbre_7+$MATHS_Nbre_8+$MATHS_Nbre_9+$MATHS_Nbre_10+
								$MATHS_Nbre_11+$MATHS_Nbre_12+$MATHS_Nbre_13+$MATHS_Nbre_14+$MATHS_Nbre_15+$MATHS_Nbre_16+$MATHS_Nbre_17+$MATHS_Nbre_18+$MATHS_Nbre_19+$MATHS_Nbre_20;
							 
		$PCT_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,0,$pdo);
		$PCT_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,1,$pdo);
		$PCT_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,2,$pdo);
		$PCT_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,3,$pdo);
		$PCT_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,4,$pdo);
		$PCT_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,5,$pdo);
		$PCT_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,6,$pdo);
		$PCT_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,7,$pdo);
		$PCT_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,8,$pdo);
		$PCT_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,9,$pdo);
		$PCT_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,10,$pdo);
		$PCT_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,11,$pdo);
		$PCT_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,12,$pdo);
		$PCT_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,13,$pdo);
		$PCT_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,14,$pdo);
		$PCT_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,15,$pdo);
		$PCT_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,16,$pdo);
		$PCT_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,17,$pdo);
		$PCT_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,18,$pdo);
		$PCT_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,19,$pdo);
		$PCT_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,7,$idsalle,20,$pdo);
		
		$PCT_Nbre_0_Total_L = $PCT_Nbre_0+$PCT_Nbre_1+$PCT_Nbre_2+$PCT_Nbre_3+$PCT_Nbre_4+$PCT_Nbre_5+$PCT_Nbre_6+$PCT_Nbre_7+$PCT_Nbre_8+$PCT_Nbre_9+$PCT_Nbre_10+
							  $PCT_Nbre_11+$PCT_Nbre_12+$PCT_Nbre_13+$PCT_Nbre_14+$PCT_Nbre_15+$PCT_Nbre_16+$PCT_Nbre_17+$PCT_Nbre_18+$PCT_Nbre_19+$PCT_Nbre_20;
		
		$SVT_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,0,$pdo);
		$SVT_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,1,$pdo);
		$SVT_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,2,$pdo);
		$SVT_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,3,$pdo);
		$SVT_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,4,$pdo);
		$SVT_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,5,$pdo);
		$SVT_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,6,$pdo);
		$SVT_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,7,$pdo);
		$SVT_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,8,$pdo);
		$SVT_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,9,$pdo);
		$SVT_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,10,$pdo);
		$SVT_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,11,$pdo);
		$SVT_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,12,$pdo);
		$SVT_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,13,$pdo);
		$SVT_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,14,$pdo);
		$SVT_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,15,$pdo);
		$SVT_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,16,$pdo);
		$SVT_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,17,$pdo);
		$SVT_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,18,$pdo);
		$SVT_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,19,$pdo);
		$SVT_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,8,$idsalle,20,$pdo);
		
		$SVT_Nbre_0_Total_L = $SVT_Nbre_0+$SVT_Nbre_1+$SVT_Nbre_2+$SVT_Nbre_3+$SVT_Nbre_4+$SVT_Nbre_5+$SVT_Nbre_6+$SVT_Nbre_7+$SVT_Nbre_8+$SVT_Nbre_9+$SVT_Nbre_10+
							  $SVT_Nbre_11+$SVT_Nbre_12+$SVT_Nbre_13+$SVT_Nbre_14+$SVT_Nbre_15+$SVT_Nbre_16+$SVT_Nbre_17+$SVT_Nbre_18+$SVT_Nbre_19+$SVT_Nbre_20;
		
		$ALL_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,0,$pdo);
		$ALL_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,1,$pdo);
		$ALL_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,2,$pdo);
		$ALL_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,3,$pdo);
		$ALL_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,4,$pdo);
		$ALL_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,5,$pdo);
		$ALL_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,6,$pdo);
		$ALL_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,7,$pdo);
		$ALL_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,8,$pdo);
		$ALL_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,9,$pdo);
		$ALL_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,10,$pdo);
		$ALL_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,11,$pdo);
		$ALL_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,12,$pdo);
		$ALL_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,13,$pdo);
		$ALL_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,14,$pdo);
		$ALL_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,15,$pdo);
		$ALL_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,16,$pdo);
		$ALL_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,17,$pdo);
		$ALL_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,18,$pdo);
		$ALL_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,19,$pdo);
		$ALL_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,24,$idsalle,20,$pdo);
		
		$ALL_Nbre_0_Total_L = $ALL_Nbre_0+$ALL_Nbre_1+$ALL_Nbre_2+$ALL_Nbre_3+$ALL_Nbre_4+$ALL_Nbre_5+$ALL_Nbre_6+$ALL_Nbre_7+$ALL_Nbre_8+$ALL_Nbre_9+$ALL_Nbre_10+
							  $ALL_Nbre_11+$ALL_Nbre_12+$ALL_Nbre_13+$ALL_Nbre_14+$ALL_Nbre_15+$ALL_Nbre_16+$ALL_Nbre_17+$ALL_Nbre_18+$ALL_Nbre_19+$ALL_Nbre_20;
		
		$PHILO_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,0,$pdo);
		$PHILO_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,1,$pdo);
		$PHILO_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,2,$pdo);
		$PHILO_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,3,$pdo);
		$PHILO_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,4,$pdo);
		$PHILO_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,5,$pdo);
		$PHILO_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,6,$pdo);
		$PHILO_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,7,$pdo);
		$PHILO_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,8,$pdo);
		$PHILO_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,9,$pdo);
		$PHILO_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,10,$pdo);
		$PHILO_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,11,$pdo);
		$PHILO_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,12,$pdo);
		$PHILO_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,13,$pdo);
		$PHILO_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,14,$pdo);
		$PHILO_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,15,$pdo);
		$PHILO_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,16,$pdo);
		$PHILO_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,17,$pdo);
		$PHILO_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,18,$pdo);
		$PHILO_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,19,$pdo);
		$PHILO_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,22,$idsalle,20,$pdo);
		
		$PHILO_Nbre_0_Total_L = $PHILO_Nbre_0+$PHILO_Nbre_1+$PHILO_Nbre_2+$PHILO_Nbre_3+$PHILO_Nbre_4+$PHILO_Nbre_5+$PHILO_Nbre_6+$PHILO_Nbre_7+$PHILO_Nbre_8+$PHILO_Nbre_9+$PHILO_Nbre_10+
								$PHILO_Nbre_11+$PHILO_Nbre_12+$PHILO_Nbre_13+$PHILO_Nbre_14+$PHILO_Nbre_15+$PHILO_Nbre_16+$PHILO_Nbre_17+$PHILO_Nbre_18+$PHILO_Nbre_19+$PHILO_Nbre_20;
		
		$ECM_Nbre_0 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,0,$pdo);
		$ECM_Nbre_1 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,1,$pdo);
		$ECM_Nbre_2 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,2,$pdo);
		$ECM_Nbre_3 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,3,$pdo);
		$ECM_Nbre_4 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,4,$pdo);
		$ECM_Nbre_5 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,5,$pdo);
		$ECM_Nbre_6 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,6,$pdo);
		$ECM_Nbre_7 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,7,$pdo);
		$ECM_Nbre_8 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,8,$pdo);
		$ECM_Nbre_9 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,9,$pdo);
		$ECM_Nbre_10 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,10,$pdo);
		$ECM_Nbre_11 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,11,$pdo);
		$ECM_Nbre_12 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,12,$pdo);
		$ECM_Nbre_13 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,13,$pdo);
		$ECM_Nbre_14 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,14,$pdo);
		$ECM_Nbre_15 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,15,$pdo);
		$ECM_Nbre_16 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,16,$pdo);
		$ECM_Nbre_17 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,17,$pdo);
		$ECM_Nbre_18 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,18,$pdo);
		$ECM_Nbre_19 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,19,$pdo);
		$ECM_Nbre_20 = getNbreNoteComp($idanneescolaire,$idposition,15,$idsalle,20,$pdo);
		
		$ECM_Nbre_0_Total_L = $ECM_Nbre_0+$ECM_Nbre_1+$ECM_Nbre_2+$ECM_Nbre_3+$ECM_Nbre_4+$ECM_Nbre_5+$ECM_Nbre_6+$ECM_Nbre_7+$ECM_Nbre_8+$ECM_Nbre_9+$ECM_Nbre_10+
							  $ECM_Nbre_11+$ECM_Nbre_12+$ECM_Nbre_13+$ECM_Nbre_14+$ECM_Nbre_15+$ECM_Nbre_16+$ECM_Nbre_17+$ECM_Nbre_18+$ECM_Nbre_19+$ECM_Nbre_20;
								
		$Nbre_0_Total_C = $FR_Nbre_0+$ANG_Nbre_0+$HG_Nbre_0+$ALL_Nbre_0+$MATHS_Nbre_0+$PCT_Nbre_0+$SVT_Nbre_0+$PHILO_Nbre_0+$ECM_Nbre_0;
		$Nbre_1_Total_C = $FR_Nbre_1+$ANG_Nbre_1+$HG_Nbre_1+$ALL_Nbre_1+$MATHS_Nbre_1+$PCT_Nbre_1+$SVT_Nbre_1+$PHILO_Nbre_1+$ECM_Nbre_1;
		$Nbre_2_Total_C = $FR_Nbre_2+$ANG_Nbre_2+$HG_Nbre_2+$ALL_Nbre_2+$MATHS_Nbre_2+$PCT_Nbre_2+$SVT_Nbre_2+$PHILO_Nbre_2+$ECM_Nbre_2;
		$Nbre_3_Total_C = $FR_Nbre_3+$ANG_Nbre_3+$HG_Nbre_3+$ALL_Nbre_3+$MATHS_Nbre_3+$PCT_Nbre_3+$SVT_Nbre_3+$PHILO_Nbre_3+$ECM_Nbre_3;
		$Nbre_4_Total_C = $FR_Nbre_4+$ANG_Nbre_4+$HG_Nbre_4+$ALL_Nbre_4+$MATHS_Nbre_4+$PCT_Nbre_4+$SVT_Nbre_4+$PHILO_Nbre_4+$ECM_Nbre_4;
		$Nbre_5_Total_C = $FR_Nbre_5+$ANG_Nbre_5+$HG_Nbre_5+$ALL_Nbre_5+$MATHS_Nbre_5+$PCT_Nbre_5+$SVT_Nbre_5+$PHILO_Nbre_5+$ECM_Nbre_5;
		$Nbre_6_Total_C = $FR_Nbre_6+$ANG_Nbre_6+$HG_Nbre_6+$ALL_Nbre_6+$MATHS_Nbre_6+$PCT_Nbre_6+$SVT_Nbre_6+$PHILO_Nbre_6+$ECM_Nbre_6;
		$Nbre_7_Total_C = $FR_Nbre_7+$ANG_Nbre_7+$HG_Nbre_7+$ALL_Nbre_7+$MATHS_Nbre_7+$PCT_Nbre_7+$SVT_Nbre_7+$PHILO_Nbre_7+$ECM_Nbre_7;
		$Nbre_8_Total_C = $FR_Nbre_8+$ANG_Nbre_8+$HG_Nbre_8+$ALL_Nbre_8+$MATHS_Nbre_8+$PCT_Nbre_8+$SVT_Nbre_8+$PHILO_Nbre_8+$ECM_Nbre_8;
		$Nbre_9_Total_C = $FR_Nbre_9+$ANG_Nbre_9+$HG_Nbre_9+$ALL_Nbre_9+$MATHS_Nbre_9+$PCT_Nbre_9+$SVT_Nbre_9+$PHILO_Nbre_9+$ECM_Nbre_9;
		$Nbre_10_Total_C = $FR_Nbre_10+$ANG_Nbre_10+$HG_Nbre_10+$ALL_Nbre_10+$MATHS_Nbre_10+$PCT_Nbre_10+$SVT_Nbre_10+$PHILO_Nbre_10+$ECM_Nbre_10;
		$Nbre_11_Total_C = $FR_Nbre_11+$ANG_Nbre_11+$HG_Nbre_11+$ALL_Nbre_11+$MATHS_Nbre_11+$PCT_Nbre_11+$SVT_Nbre_11+$PHILO_Nbre_11+$ECM_Nbre_11;
		$Nbre_12_Total_C = $FR_Nbre_12+$ANG_Nbre_12+$HG_Nbre_12+$ALL_Nbre_12+$MATHS_Nbre_12+$PCT_Nbre_12+$SVT_Nbre_12+$PHILO_Nbre_12+$ECM_Nbre_12;
		$Nbre_13_Total_C = $FR_Nbre_13+$ANG_Nbre_13+$HG_Nbre_13+$ALL_Nbre_13+$MATHS_Nbre_13+$PCT_Nbre_13+$SVT_Nbre_13+$PHILO_Nbre_13+$ECM_Nbre_13;
		$Nbre_14_Total_C = $FR_Nbre_14+$ANG_Nbre_14+$HG_Nbre_14+$ALL_Nbre_14+$MATHS_Nbre_14+$PCT_Nbre_14+$SVT_Nbre_14+$PHILO_Nbre_14+$ECM_Nbre_14;
		$Nbre_15_Total_C = $FR_Nbre_15+$ANG_Nbre_15+$HG_Nbre_15+$ALL_Nbre_15+$MATHS_Nbre_15+$PCT_Nbre_15+$SVT_Nbre_15+$PHILO_Nbre_15+$ECM_Nbre_15;
		$Nbre_16_Total_C = $FR_Nbre_16+$ANG_Nbre_16+$HG_Nbre_16+$ALL_Nbre_16+$MATHS_Nbre_16+$PCT_Nbre_16+$SVT_Nbre_16+$PHILO_Nbre_16+$ECM_Nbre_16;
		$Nbre_17_Total_C = $FR_Nbre_17+$ANG_Nbre_17+$HG_Nbre_17+$ALL_Nbre_17+$MATHS_Nbre_17+$PCT_Nbre_17+$SVT_Nbre_17+$PHILO_Nbre_17+$ECM_Nbre_17;
		$Nbre_18_Total_C = $FR_Nbre_18+$ANG_Nbre_18+$HG_Nbre_18+$ALL_Nbre_18+$MATHS_Nbre_18+$PCT_Nbre_18+$SVT_Nbre_18+$PHILO_Nbre_18+$ECM_Nbre_18;
		$Nbre_19_Total_C = $FR_Nbre_19+$ANG_Nbre_19+$HG_Nbre_19+$ALL_Nbre_19+$MATHS_Nbre_19+$PCT_Nbre_19+$SVT_Nbre_19+$PHILO_Nbre_19+$ECM_Nbre_19;
		$Nbre_20_Total_C = $FR_Nbre_20+$ANG_Nbre_20+$HG_Nbre_20+$ALL_Nbre_20+$MATHS_Nbre_20+$PCT_Nbre_20+$SVT_Nbre_20+$PHILO_Nbre_20+$ECM_Nbre_20;
		
		$Nbre_Total_C_L = $Nbre_0_Total_C+$Nbre_1_Total_C+$Nbre_2_Total_C+$Nbre_3_Total_C+$Nbre_4_Total_C+$Nbre_5_Total_C+$Nbre_6_Total_C+$Nbre_7_Total_C+$Nbre_8_Total_C+$Nbre_9_Total_C+$Nbre_10_Total_C+
						  $Nbre_11_Total_C+$Nbre_12_Total_C+$Nbre_13_Total_C+$Nbre_14_Total_C+$Nbre_15_Total_C+$Nbre_16_Total_C+$Nbre_17_Total_C+$Nbre_18_Total_C+$Nbre_19_Total_C+$Nbre_20_Total_C;
		?>
		<tr>
			<td>
				<a>FR</a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $FR_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>ALL</a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $ALL_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>PHILO</a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $PHILO_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>ANG</a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $ANG_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>HG</a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $HG_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>ECM</a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $ECM_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>MATHS</a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $MATHS_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>PCT</a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $PCT_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>SVT</a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_0;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_1;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_2;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_3;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_4;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_5;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_6;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_7;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_8;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_9;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_10;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_11;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_12;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_13;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_14;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_15;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_16;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_17;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_18;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_19;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_20;?></a>
			</td>
			<td>
				<a><?php echo $SVT_Nbre_0_Total_L;?></a>
			</td>
		</tr>
		<tr>
			<td>
				<a>TOTAL</a>
			</td>
			<td>
				<a><?php echo $Nbre_0_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_1_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_2_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_3_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_4_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_5_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_6_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_7_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_8_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_9_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_10_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_11_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_12_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_13_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_14_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_15_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_16_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_17_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_18_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_19_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_20_Total_C;?></a>
			</td>
			<td>
				<a><?php echo $Nbre_Total_C_L;?></a>
			</td>
		</tr>
	</table><?php
}

function RapportActiviteCollege($idanneescolaire,$idposition,$iddomaine,$moyenne,$pdo)
{
	$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
	$req=(' SELECT  
					distinct
					classe.codeclasse as codeclasse,
					classe.idclasse as idclasse
					
			FROM classe
			WHERE
			classe.iddomaine=:iddomaine
			
			ORDER BY classe.codeclasse DESC');
    ?>
	<table class="table table-striped table-bordered" width="100%" style="background-color:#fff">
		<thead>
			<tr style="background-color:#eee;">
				<th style="color:#000;">Ann&eacute;e scol.</th>
				<th style="color:#000;">Niveau(x)</th>
				<th style="color:#000;" colspan="3" align="center">INSCRITS</th>
				<th style="color:#000;" colspan="3" align="center">PRESENTS</th>
				<th style="color:#000;" colspan="6" align="center">QUI ONT LA MOYENNE EN COMP</th>
				<th style="color:#000;" colspan="6" align="center">QUI ONT LA MOYENNE TRIM.</th>
			</tr>
			<tr style="background-color:#eee;">
				<th style="color:#000;"></th>
				<th style="color:#000;"></th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				<th style="color:#000;">%G</th>
				<th style="color:#000;">%F</th>
				<th style="color:#000;">%T</th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				<th style="color:#000;">%G</th>
				<th style="color:#000;">%F</th>
				<th style="color:#000;">%T</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$ligneTotalMasculin=0;
		$ligneTotalFeminin=0;
		$ligneTotal=0;
		$ligneTotal_1=0;
		$ligneTotal_2=0;
		$ligneTotal_3=0;
		$ligneTotal_4=0;
		$ligneTotal_5=0;
		$ligneTotal_6=0;
		$ligneTotal_7=0;
		$ligneTotal_8=0;
		$ligneTotal_9=0;
		$ligneTotal_10=0;
		$ligneTotal_11=0;
		$ligneTotalCompMasculin=0;
		$ligneTotalCompFeminin=0;
		$ligneTotalComp=0;
		$ligneTotalMoyComp=0;
		
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$codeclasse = $donnees['codeclasse'];
			$idclasse = $donnees['idclasse'];
			//
			$NbreEleveParNiveauMasculin = getNbreEleveParNiveau($idanneescolaire,1,"Masculin",$idclasse,$pdo);
			$ligneTotalMasculin = $ligneTotalMasculin + $NbreEleveParNiveauMasculin;
			$NbreEleveParNiveauFeminin = getNbreEleveParNiveau($idanneescolaire,1,"Feminin",$idclasse,$pdo);
			$ligneTotalFeminin = $ligneTotalFeminin + $NbreEleveParNiveauFeminin;
			$ligneTotal_2 = $ligneTotal_2 + ($NbreEleveParNiveauMasculin + $NbreEleveParNiveauFeminin);
			//
			$NbreEleveParNiveauAyantCompMasculin = getNbreEleveParNiveauAyantComp($idanneescolaire,$idposition,$idclasse,"Masculin",$pdo);
			$ligneTotalCompMasculin = $ligneTotalCompMasculin + $NbreEleveParNiveauAyantCompMasculin;
			$NbreEleveParNiveauAyantCompFeminin = getNbreEleveParNiveauAyantComp($idanneescolaire,$idposition,$idclasse,"Feminin",$pdo);
			$ligneTotalCompFeminin = $ligneTotalCompFeminin + $NbreEleveParNiveauAyantCompFeminin;
			$ligneTotal_3 = $ligneTotal_3 + ($NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin);
			//
			$NbreEleveParNiveauAyantMoyCompMasculin = getMoyenneCompParClasse($idanneescolaire,$idposition,$idclasse,"Masculin",$moyenne,$pdo);
			$ligneTotal_4 = $ligneTotal_4 + $NbreEleveParNiveauAyantMoyCompMasculin;
			$NbreEleveParNiveauAyantMoyCompFeminin = getMoyenneCompParClasse($idanneescolaire,$idposition,$idclasse,"Feminin",$moyenne,$pdo);
			$ligneTotal_5 = $ligneTotal_5 + $NbreEleveParNiveauAyantMoyCompFeminin;
			$ligneTotal_6 = $ligneTotal_6 + ($NbreEleveParNiveauAyantMoyCompMasculin + $NbreEleveParNiveauAyantMoyCompFeminin);
			//
			$NbreMoyenneTrimestreMasculin = getNbreMoyenneTrimestre($idanneescolaire,$idposition,$idclasse,"Masculin",$moyenne,$pdo);
			$ligneTotal_8 = $ligneTotal_8 + $NbreMoyenneTrimestreMasculin;
			$NbreMoyenneTrimestreFeminin = getNbreMoyenneTrimestre($idanneescolaire,$idposition,$idclasse,"Feminin",$moyenne,$pdo);
			$ligneTotal_9 = $ligneTotal_9 + $NbreMoyenneTrimestreFeminin;
			$ligneTotal_11 = $ligneTotal_11 + ($NbreMoyenneTrimestreMasculin + $NbreMoyenneTrimestreFeminin);
			?>
			<tr>
				<td style="font-weight:bold;">
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauMasculin+$NbreEleveParNiveauFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantCompMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantCompFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantMoyCompMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantMoyCompFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantMoyCompMasculin + $NbreEleveParNiveauAyantMoyCompFeminin;?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompMasculin!=0) echo (round(($NbreEleveParNiveauAyantMoyCompMasculin/$NbreEleveParNiveauAyantCompMasculin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0) echo (round(($NbreEleveParNiveauAyantMoyCompFeminin/$NbreEleveParNiveauAyantCompFeminin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0 AND $NbreEleveParNiveauAyantCompMasculin!=0) echo (round((($NbreEleveParNiveauAyantMoyCompMasculin+$NbreEleveParNiveauAyantMoyCompFeminin)/($NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin))*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php echo $NbreMoyenneTrimestreMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreMoyenneTrimestreFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreMoyenneTrimestreMasculin + $NbreMoyenneTrimestreFeminin;?></a>
				</td>
		
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompMasculin!=0) echo (round(($NbreMoyenneTrimestreMasculin/$NbreEleveParNiveauAyantCompMasculin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0) echo (round(($NbreMoyenneTrimestreFeminin/$NbreEleveParNiveauAyantCompFeminin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0 AND $NbreEleveParNiveauAyantCompMasculin!=0) echo (round((($NbreMoyenneTrimestreMasculin+$NbreMoyenneTrimestreFeminin)/($NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin))*100,2)).'%';?></a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
		<tr>
			<td style="font-weight:bold;">
				<a>TOTAL</a>
			</td>
			<td>
				<a></a>
			</td>
			<td>
				<a><?php echo $ligneTotalMasculin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotalFeminin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_2;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotalCompMasculin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotalCompFeminin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_3;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_4;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_5;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_6;?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompMasculin!=0) echo (round(($ligneTotal_4/$ligneTotalCompMasculin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompFeminin!=0) echo (round(($ligneTotal_5/$ligneTotalCompFeminin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotal_3!=0) echo (round((($ligneTotal_6)/$ligneTotal_3)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_8;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_9;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_11;?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompMasculin!=0) echo (round(($ligneTotal_8/$ligneTotalCompMasculin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompFeminin!=0) echo (round(($ligneTotal_9/$ligneTotalCompFeminin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotal_3!=0) echo (round((($ligneTotal_11)/$ligneTotal_3)*100,2)).'%';?></a>
			</td>
		</tr>
	</table><?php
}

function RapportActiviteLycee($idanneescolaire,$idposition,$iddomaine,$moyenne,$pdo)
{
	$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
	$req=(' SELECT  
					distinct
					classe.codeclasse as codeclasse,
					classe.idclasse as idclasse
					
			FROM classe
			WHERE
			classe.iddomaine=:iddomaine
			
			ORDER BY classe.codeclasse DESC');
    ?>
	<table class="table table-striped table-bordered" width="100%" style="background-color:#fff">
		<thead>
			<tr style="background-color:#eee;">
				<th style="color:#000;">Ann&eacute;e scol.</th>
				<th style="color:#000;">Niveau(x)</th>
				<th style="color:#000;" colspan="3" align="center">INSCRITS</th>
				<th style="color:#000;" colspan="3" align="center">PRESENTS</th>
				<th style="color:#000;" colspan="6" align="center">QUI ONT LA MOYENNE EN COMP</th>
				<th style="color:#000;" colspan="6" align="center">QUI ONT LA MOYENNE SEM.</th>
			</tr>
			<tr style="background-color:#eee;">
				<th style="color:#000;"></th>
				<th style="color:#000;"></th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				<th style="color:#000;">%G</th>
				<th style="color:#000;">%F</th>
				<th style="color:#000;">%T</th>
				
				<th style="color:#000;">G</th>
				<th style="color:#000;">F</th>
				<th style="color:#000;">T</th>
				<th style="color:#000;">%G</th>
				<th style="color:#000;">%F</th>
				<th style="color:#000;">%T</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$ligneTotalMasculin=0;
		$ligneTotalFeminin=0;
		$ligneTotal=0;
		$ligneTotal_1=0;
		$ligneTotal_2=0;
		$ligneTotal_3=0;
		$ligneTotal_4=0;
		$ligneTotal_5=0;
		$ligneTotal_6=0;
		$ligneTotal_7=0;
		$ligneTotal_8=0;
		$ligneTotal_9=0;
		$ligneTotal_10=0;
		$ligneTotal_11=0;
		$ligneTotalCompMasculin=0;
		$ligneTotalCompFeminin=0;
		$ligneTotalComp=0;
		$ligneTotalMoyComp=0;
		
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$codeclasse = $donnees['codeclasse'];
			$idclasse = $donnees['idclasse'];
			
			$NbreEleveParNiveauMasculin = getNbreEleveParNiveau($idanneescolaire,1,"Masculin",$idclasse,$pdo);
			$ligneTotalMasculin = $ligneTotalMasculin + $NbreEleveParNiveauMasculin;
			$NbreEleveParNiveauFeminin = getNbreEleveParNiveau($idanneescolaire,1,"Feminin",$idclasse,$pdo);
			$ligneTotalFeminin = $ligneTotalFeminin + $NbreEleveParNiveauFeminin;
			$ligneTotal_2 = $ligneTotal_2 + ($NbreEleveParNiveauMasculin + $NbreEleveParNiveauFeminin);
			
			$NbreEleveParNiveauAyantCompMasculin = getNbreEleveParNiveauAyantComp($idanneescolaire,$idposition,$idclasse,"Masculin",$pdo);
			$ligneTotalCompMasculin = $ligneTotalCompMasculin + $NbreEleveParNiveauAyantCompMasculin;
			$NbreEleveParNiveauAyantCompFeminin = getNbreEleveParNiveauAyantComp($idanneescolaire,$idposition,$idclasse,"Feminin",$pdo);
			$ligneTotalCompFeminin = $ligneTotalCompFeminin + $NbreEleveParNiveauAyantCompFeminin;
			$ligneTotal_3 = $ligneTotal_3 + ($NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin);
			
			$NbreEleveParNiveauAyantMoyCompMasculin = getMoyenneCompParClasse($idanneescolaire,$idposition,$idclasse,"Masculin",$moyenne,$pdo);
			$ligneTotal_4 = $ligneTotal_4 + $NbreEleveParNiveauAyantMoyCompMasculin;
			$NbreEleveParNiveauAyantMoyCompFeminin = getMoyenneCompParClasse($idanneescolaire,$idposition,$idclasse,"Feminin",$moyenne,$pdo);
			$ligneTotal_5 = $ligneTotal_5 + $NbreEleveParNiveauAyantMoyCompFeminin;
			$ligneTotal_6 = $ligneTotal_6 + ($NbreEleveParNiveauAyantMoyCompMasculin + $NbreEleveParNiveauAyantMoyCompFeminin);
			
			$NbreMoyenneTrimestreMasculin = getNbreMoyenneTrimestre($idanneescolaire,$idposition,$idclasse,"Masculin",$moyenne,$pdo);
			$ligneTotal_8 = $ligneTotal_8 + $NbreMoyenneTrimestreMasculin;
			$NbreMoyenneTrimestreFeminin = getNbreMoyenneTrimestre($idanneescolaire,$idposition,$idclasse,"Feminin",$moyenne,$pdo);
			$ligneTotal_9 = $ligneTotal_9 + $NbreMoyenneTrimestreFeminin;
			$ligneTotal_11 = $ligneTotal_11 + ($NbreMoyenneTrimestreMasculin + $NbreMoyenneTrimestreFeminin);
			?>
			<tr>
				<td style="font-weight:bold;">
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauMasculin+$NbreEleveParNiveauFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantCompMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantCompFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantMoyCompMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantMoyCompFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreEleveParNiveauAyantMoyCompMasculin + $NbreEleveParNiveauAyantMoyCompFeminin;?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompMasculin!=0) echo (round(($NbreEleveParNiveauAyantMoyCompMasculin/$NbreEleveParNiveauAyantCompMasculin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0) echo (round(($NbreEleveParNiveauAyantMoyCompFeminin/$NbreEleveParNiveauAyantCompFeminin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0 AND $NbreEleveParNiveauAyantCompMasculin!=0) echo (round((($NbreEleveParNiveauAyantMoyCompMasculin+$NbreEleveParNiveauAyantMoyCompFeminin)/($NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin))*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php echo $NbreMoyenneTrimestreMasculin;?></a>
				</td>
				<td>
					<a><?php echo $NbreMoyenneTrimestreFeminin;?></a>
				</td>
				<td>
					<a><?php echo $NbreMoyenneTrimestreMasculin + $NbreMoyenneTrimestreFeminin;?></a>
				</td>
		
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompMasculin!=0) echo (round(($NbreMoyenneTrimestreMasculin/$NbreEleveParNiveauAyantCompMasculin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0) echo (round(($NbreMoyenneTrimestreFeminin/$NbreEleveParNiveauAyantCompFeminin)*100,2)).'%';?></a>
				</td>
				<td>
					<a><?php if($NbreEleveParNiveauAyantCompFeminin!=0 AND $NbreEleveParNiveauAyantCompMasculin!=0) echo (round((($NbreMoyenneTrimestreMasculin+$NbreMoyenneTrimestreFeminin)/($NbreEleveParNiveauAyantCompMasculin + $NbreEleveParNiveauAyantCompFeminin))*100,2)).'%';?></a>
				</td>
			</tr><?php
		}
		$stmt->closeCursor();
		$stmt=NULL;
		?>
		<tr>
			<td style="font-weight:bold;">
				<a>TOTAL</a>
			</td>
			<td>
				<a></a>
			</td>
			<td>
				<a><?php echo $ligneTotalMasculin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotalFeminin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_2;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotalCompMasculin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotalCompFeminin;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_3;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_4;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_5;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_6;?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompMasculin!=0) echo (round(($ligneTotal_4/$ligneTotalCompMasculin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompFeminin!=0) echo (round(($ligneTotal_5/$ligneTotalCompFeminin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotal_3!=0) echo (round((($ligneTotal_6)/$ligneTotal_3)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_8;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_9;?></a>
			</td>
			<td>
				<a><?php echo $ligneTotal_11;?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompMasculin!=0) echo (round(($ligneTotal_8/$ligneTotalCompMasculin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotalCompFeminin!=0) echo (round(($ligneTotal_9/$ligneTotalCompFeminin)*100,2)).'%';?></a>
			</td>
			<td>
				<a><?php if($ligneTotal_3!=0) echo (round((($ligneTotal_11)/$ligneTotal_3)*100,2)).'%';?></a>
			</td>
		</tr>
	</table><?php
}

function ImprimeFicheNotesMatiere($idsalle,$idanneescolaire,$idposition,$idmatiere,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			salle.id=:idsalle

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:15%;">Nom</th>
				<th style="width:19%;">Pr&eacute;nom</th>
				<th colspan="3" style="width:15%;text-align:center">Devoirs</th>
				<th style="width:7%;text-align:center">M.C</th>
				<th style="width:7%;text-align:center">COMP</th>
				<th style="width:7%;text-align:center">M.G</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$idelevesalle = $donnees['idelevesalle'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			$NoteInt = getNoteInt($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$NoteDn = getNoteDn($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$NoteDs = getNoteDs($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$NoteComp = getNoteComp($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			$NoteMoyClass = BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo);
			$NoteMoyTrimes = BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo);
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td><a><?php echo $NoteInt;?></a></td>
				<td><a><?php echo $NoteDn;?></a></td>
				<td><a><?php echo $NoteDs;?></a></td>
				<td><a><?php echo $NoteMoyClass;?></a></td>
				<td><a><?php echo $NoteComp;?></a></td>
				<td><a><?php echo $NoteMoyTrimes;?></a></td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ImprimeReleveNotesLycee($idsalle,$idanneescolaire,$idposition,$idelevesalle,$rang,$effectif,$pdo)
{	
    ?>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:16%;">MATIERE</th>
				<th style="width:16%;">NOTES SUR 20</th>
				<th style="width:16%;">COEF</th>
				<th style="width:22%;">POINTS OBTENUS</th>
				<th style="width:10%;">SUR</th>
				<th style="width:16%;">APPRECIATION</th>
		    </tr>
	    </thead>
		<tr>
			<td align="left">
				<a>&nbsp;Anglais</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Philo</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Français</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Histo -Géo</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Mathématiques</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;SVT</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;PCT</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Total</a>
			</td>
			<td align="left">
				<a></a>
			</td>
			<td>
			<?php
				$Total =
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);
				
				$Coef =
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);
				
				$TotalCoef =
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)*20;
			?>
			<a><?php echo round($Coef,2);?></a>
			</td>
			<td><a><?php echo round($Total,2);?></a></td>
			<td><a><?php echo round($TotalCoef,2);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Moyenne</a>
			</td>
			<td>
			<a><?php if($Coef!=0) echo round(($Total/$Coef),2);?></a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Rang</a>
			</td>
			<td>
			<a><?php echo $rang;?></a>
			</td>
			<td><a>/<?php echo $effectif;?></a></td>
			<td><a></a></td>
		</tr>
	</table>
	<table width="100%" align="center">
		<?php
		if($Coef!=0)
		{
			if(intval($Total/$Coef)>=10)
			{
				?>
				<tr>
					<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
					<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
					<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	<?php
}

function ImprimeReleveNotesPrimaire($idsalle,$idanneescolaire,$idposition,$idelevesalle,$rang,$effectif,$pdo)
{	
    ?>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:25%;">DISCIPLINES</th>
				<th style="width:25%;">NOTES</th>
				<th style="width:25%;">SUR</th>
				<th style="width:25%;">APPR&Eacute;CIATIONS</th>
		    </tr>
	    </thead>
		<tr>
			<td align="left">
				<a>&nbsp;Dictée</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,42,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,42,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,42,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Questions</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,43,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,43,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,43,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Rédaction</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,17,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,17,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,17,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Etude de texte</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,44,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,44,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,44,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Calcul mental</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,45,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,45,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,45,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Mathématiques</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Problème</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,47,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,47,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,47,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Sciences et technologies</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,48,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,48,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,48,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Sciences Humaines</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,49,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,49,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,49,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Education sociale</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Dessin</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,10,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,10,$pdo);?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,10,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Total</a>
			</td>
			<td>
			<?php
				$Total =
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,42,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,43,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,44,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,17,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,45,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,49,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,47,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,48,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,10,$pdo)+
				(float)BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);
				
				$Coef =
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,42,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,43,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,44,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,17,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,45,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,49,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,47,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,48,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,10,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);
			?>
			<a><?php echo round($Total,2);?></a>
			</td>
			<td><a><?php echo round($Coef,2);?></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Moyenne</a>
			</td>
			<td>
			<a><?php echo round(($Total/$Coef),2);?></a>
			</td>
			<td><a>/10</a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Rang</a>
			</td>
			<td>
			<a><?php echo $rang;?></a>
			</td>
			<td><a>/<?php echo $effectif;?></a></td>
			<td><a></a></td>
		</tr>
	</table>
	<table width="100%" align="center">
		<?php
		if($Total>=($Coef/2))
		{
			?>
			<tr>
				<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
				<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
			</tr>
			<?php
		}
		else
		{
			?>
			<tr>
				<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
				<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
}

function ImprimeReleveNotesCollege($idsalle,$idanneescolaire,$idposition,$idelevesalle,$rang,$effectif,$pdo)
{	
    ?>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:16%;">MATIERE</th>
				<th style="width:16%;">NOTES SUR 20</th>
				<th style="width:16%;">COEF</th>
				<th style="width:22%;">POINTS OBTENUS</th>
				<th style="width:10%;">SUR</th>
				<th style="width:16%;">APPRECIATION</th>
		    </tr>
	    </thead>
		<tr>
			<td align="left">
				<a>&nbsp;Anglais</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;ECM</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Français</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Histo -Géo</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Mathématiques</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;SVT</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;PCT</a>
			</td>
			<td><a><?php echo BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
			<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
			<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
			<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)*20;?></a></td>
			<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Total</a>
			</td>
			<td align="left">
				<a></a>
			</td>
			<td>
			<?php
				$Total =
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
				(float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);
				
				$Coef =
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,15,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo);
				
				$TotalCoef =
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,37,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,16,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,5,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,6,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,8,$pdo)*20+
				(float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,7,$pdo)*20;
			?>
			<a><?php echo round($Coef,2);?></a>
			</td>
			<td><a><?php echo round($Total,2);?></a></td>
			<td><a><?php echo round($TotalCoef,2);?></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Moyenne</a>
			</td>
			<td>
			<a><?php if($Coef!=0) echo round(($Total/$Coef),2);?></a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="left">
				<a>&nbsp;Rang</a>
			</td>
			<td>
			<a><?php echo $rang;?></a>
			</td>
			<td><a>/<?php echo $effectif;?></a></td>
			<td><a></a></td>
		</tr>
	</table>
	<table width="100%" align="center">
		<?php
		if($Coef!=0)
		{
			if(intval($Total/$Coef)>=10)
			{
				?>
				<tr>
					<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
					<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
					<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	<?php
}

function ImprimeReleveNotesCycleSup($idsalle,$idanneescolaire,$idposition,$idelevesalle,$rang,$effectif,$pdo)
{	
	$req=(' SELECT  distinct
	                matiere.id_matiere,
					matiere.code_matiere,
					matiere.nom_matiere,
					matierecoefficient.coefficient,
					matierecoefficient.etat
					
			FROM note,matiere,matierecoefficient,salle
			WHERE
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			note.ideleve=:ideleve
			AND
			note.idsalle=salle.id
			AND
			salle.id=:idsalle
			AND
			note.idmatiere=matiere.id_matiere
			AND
			matierecoefficient.idclasse=salle.idclasse
			AND
			matierecoefficient.idmatiere=matiere.id_matiere
			AND
			matierecoefficient.coefficient!=0
			AND
			matierecoefficient.idanneescolaire=:idanneescolaire
			
			ORDER BY matiere.id_matiere DESC');	
	
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':ideleve', $idelevesalle, PDO::PARAM_INT);
	$stmt->execute();
    ?>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:5%;">N°</th>
				<th style="width:13%;">Code</th>
				<th style="width:13%;">UE</th>
				<th style="width:13%;">Type d'UE</th>
				<th style="width:13%;">Crédit</th>
				<th style="width:13%;">Note/20</th>
				<th style="width:13%;">Validation(Oui/Non)</th>
				<th style="width:13%;">Appréciation</th>
		    </tr>
	    </thead>
		<?php
		$ligne = 0;
		$Total = 0;
		$Coef = 0;
		$TotalCoef = 0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];
			$coefficient = $donnees['coefficient'];
			$etat = $donnees['etat'];
			$Total = $Total + (float)BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,$id_matiere,$pdo);
			$Coef = $Coef + (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,$id_matiere,$pdo);
			$TotalCoef = $TotalCoef + (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,$id_matiere,$pdo)*20;
			?>
			<tr>
				<td align="left">
					<a>&nbsp;<?php echo $ligne;?></a>
				</td>
				<td align="left">
					<a>&nbsp;<?php echo $code_matiere;?></a>
				</td>
				<td align="left">
					<a>&nbsp;<?php echo $nom_matiere;?></a>
				</td>
				<td><a><?php echo $etat;?></a></td>
				<td><a><?php echo BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
				<td><a><?php echo BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
				<td><a><?php echo (float)BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo)*20;?></a></td>
				<td><a><?php echo BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,3,$pdo);?></a></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td align="left">
				<a>&nbsp;Total</a>
			</td>
			<td align="left">
				<a></a>
			</td>
			<td align="left">
				<a></a>
			</td>
			<td align="left">
				<a></a>
			</td>
			<td>
				<a><?php echo round($Coef,2);?></a>
			</td>
			<td>
				<a><?php echo round($Total,2);?></a>
			</td>
			<td>
				<a><?php echo round($TotalCoef,2);?></a>
			</td>
		</tr>
		<tr>
			<td align="left" colspan="4">
				<a>&nbsp;Crédits valable sur acquis : 30/<?php echo round($TotalCoef,2);?></a>
			</td>
			<td colspan="4">
				<a>Moyenne du semestre : <?php if($Coef!=0) echo round(($Total/$Coef),2);?></a>
			</td>
		</tr>
		<tr>
			<td align="left" colspan="4">
				<a>&nbsp;Total de crédits acquis : <?php echo round($Coef,2);?>/<?php echo round($TotalCoef,2);?></a>
			</td>
			<td colspan="4">
			</td>
		</tr>
	</table>
	<table width="100%" align="center">
		<?php
		if($Coef!=0)
		{
			if(intval($Total/$Coef)>=10)
			{
				?>
				<tr>
					<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
					<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td width="50%" align="center">&nbsp;&nbsp;<b>ADMIS(E)</b>&nbsp;&nbsp;<input type="checkbox" name=""/></td>
					<td width="50%" align="center">&nbsp;&nbsp;<b>AJOURN&Eacute;E</b>&nbsp;&nbsp;<input type="checkbox" checked="checked" name=""/></td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	<?php
}