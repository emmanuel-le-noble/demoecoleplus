<?php

function getAllClasseFiliere($pdo)
{
	$req=(' SELECT  classe.idclasse,
	                classe.nomclasse,
					classe.codeclasse

			FROM classe');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idclasse" id="idclasse" onchange="makeRequest('TransfertEleveList.php','idclasse','afficherlist')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idclasse = $donnees['idclasse'];
			$nomclasse = $donnees['nomclasse'];
			$codeclasse = $donnees['codeclasse'];
			
			echo '<option value="'.$idclasse.'">'.$codeclasse.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getMoyAnnu($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.moyen_ann as moyen_ann
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=3
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$resultat="";
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen_ann'];
	}

	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getidDerniereAnneeScolaire($pdo)
{
	
	$req=(' SELECT anneescolaire.id as id,
		  		   anneescolaire.libelle as libelle

			FROM anneescolaire
			WHERE
			anneescolaire.statut=0
			
			ORDER BY anneescolaire.id DESC LIMIT 0,1');
	$resultat="*";		
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'].'*'.$donnees['libelle'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getClasseSuperieur($classepriorite,$sallepriorite,$iddomaine,$pdo)
{
	
	$req=(' SELECT 	salle.id as id,
					salle.codesalle as codesalle
	
			FROM salle,classe
			WHERE
			salle.idclasse=classe.idclasse
			AND
			classe.iddomaine=:iddomaine
			AND
			classe.priorite=:classepriorite
			AND
			salle.priorite=:sallepriorite');
			
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':iddomaine', $iddomaine, PDO::PARAM_INT);
	$stmt->bindParam(':classepriorite', $classepriorite, PDO::PARAM_INT);
	$stmt->bindParam(':sallepriorite', $sallepriorite, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getAllSalle($idsalleselected,$iddomaine,$ligne,$pdo)
{
	
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle,classe
			WHERE
			salle.idclasse=classe.idclasse
			AND
			salle.statut=0');
			
    $stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="idsalle<?php echo $ligne;?>" style="font-size:12px;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			if($id==$idsalleselected)
			{
				echo '<option value="'.$id.'" selected="selected">'.$codesalle.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$codesalle.'</option>';
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT distinct eleveanneescolaire.id as ideleveeleveanneescolaire
			FROM eleveanneescolaire
			WHERE 
			eleveanneescolaire.ideleve=:ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.statut=1');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['ideleveeleveanneescolaire'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	return $resultat;
}

function ValiderTransfert($ideleve,$idsalle,$idclasse,$dossierinscription,$commentaire,$idanneescolaire,$pdo)
{
	$elevestatutetablissement=2;
	$elevestatutclasse=1;
	$ideleveeleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo);
	if($ideleveeleveanneescolaire==0)
	{
		$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat,dossierinscription,commentaire) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat,:dossierinscription,:commentaire)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
		$stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt ->bindParam(':inscrit', $elevestatutetablissement, PDO::PARAM_INT);
		$stmt ->bindParam(':etat', $elevestatutclasse, PDO::PARAM_INT);
		$stmt ->bindParam(':dossierinscription', $dossierinscription, PDO::PARAM_STR);
		$stmt ->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
		$stmt ->execute();
		$stmt ->closeCursor();
		$stmt =NULL;
		
		$ideleveeleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo);
		CreateEleveSalle($ideleveeleveanneescolaire,$idsalle,$idanneescolaire,$pdo);
	}
}

function CreateEleveSalle($id,$idsalle,$idanneescolaire,$pdo)
{   
    $idelevesalle = getidEleveSalle($id,$idsalle,$idanneescolaire,$pdo);
	if($idelevesalle==0)
	{
		$requete="INSERT INTO elevesalle(ideleve,idsalle,statut) VALUES(:ideleve,:idsalle,1)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':ideleve', $id, PDO::PARAM_INT);
		$stmt ->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;
	}
}

function getidEleveSalle($id,$idsalle,$idanneescolaire,$pdo)
{
	$req=(' SELECT elevesalle.id
			FROM elevesalle
			WHERE 
			elevesalle.ideleve=:ideleve
			AND
			elevesalle.idsalle=:idsalle');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_STR);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function DeleteTransfert($ideleveeleveanneescolaire,$pdo)
{
	
	$req=' DELETE FROM elevesalle WHERE elevesalle.ideleve=:ideleve';
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleveeleveanneescolaire, PDO::PARAM_INT);
    $stmt->execute();	

	$req=' DELETE FROM eleveanneescolaire WHERE eleveanneescolaire.id=:ideleve';
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleveeleveanneescolaire, PDO::PARAM_INT);
    $stmt->execute();

	$stmt->closeCursor();
	$stmt=NULL;	
}

function getClasseMoyenneReussite($idclasse,$idanneescolaire,$pdo)
{
	$req=(' SELECT decision.moyenne
	
			FROM decision
			WHERE 
			decision.idclasse=:idclasse
			AND
			decision.idanneescolaire=:idanneescolaire');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyenne'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getIdClasseForSalle($idsalle,$pdo)
{
	$req=(' SELECT salle.idclasse
			FROM salle
			WHERE 
			salle.id=:id');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':id', $idsalle, PDO::PARAM_INT);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['idclasse'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function ListEleveATransferer($moyenne_reussite,$idclasse,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  distinct
	                eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleve.sexe_eleve as sexeeleve,
					eleveanneescolaire.id as id,
					eleveanneescolaire.commentaire as commentaire,
					eleveanneescolaire.dossierinscription as dossierinscription,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as libelle,
					classe.priorite as classepriorite,
					classe.iddomaine as iddomaine,
					salle.id as idsalle,
					salle.codesalle as codesalle,
					salle.priorite as sallepriorite
					
			FROM    eleve,eleveanneescolaire,anneescolaire,classe,elevesalle,salle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.idclasse=classe.idclasse
			
			ORDER BY  eleveanneescolaire.id desc');	
    ?>
	<div style="border:1px dotted #cfcfcf;padding:10px;border-radius:10px;width:600px;background-color:#eee;">
		<span style="font-family:comic sans ms;font-weight:bold;font-size:16px;">
			[ Liste des &eacute;l&egrave;ves &agrave; valider et &agrave; transferer dans la classe sup&eacute;rieure ]
		</span>
	</div><br/>
	<table id="datatable-buttons" class="table table-striped projects" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
                <th style="width:15%;font-weight:bold;color:#000">Ann&eacute;e scolaire</th>				
				<th style="width:15%;font-weight:bold;color:#000">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:15%;font-weight:bold;color:#000">Sexe</th>
				<th style="width:15%;font-weight:bold;color:#000">Classe ant&eacute;rieure</th>
				<th style="width:15%;font-weight:bold;color:#000">Moyenne annuelle obtenue</th>
				<th style="width:15%;font-weight:bold;color:red">Nouvele classe &agrave; valider</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$classesuperieur="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$ideleve = $donnees['ideleve'];
			$idelevesalle = $donnees['idelevesalle'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$prenomeleve = $donnees['prenomeleve'];
			$sexeeleve = $donnees['sexeeleve'];
			$libelle = $donnees['libelle'];
			$classepriorite = $donnees['classepriorite'];
			$sallepriorite = $donnees['sallepriorite'];
			$iddomaine = $donnees['iddomaine'];
			$idsalle = $donnees['idsalle'];
			$codesalle = $donnees['codesalle'];
			$commentaire = $donnees['commentaire'];
			$dossierinscription = $donnees['dossierinscription'];
			$moyenneobtenu = round(getMoyAnnu($idanneescolaire,$idsalle,$idelevesalle,$pdo),2);
			if($moyenneobtenu>=$moyenne_reussite)
			{
				$idsallesuperieur = getClasseSuperieur(($classepriorite+1),$sallepriorite,$iddomaine,$pdo);
			}
			else
			{
				$idsallesuperieur = $idsalle;
			}
			?>
			<tr>
				<td style="padding-left:30px;">
					<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $ideleve.'*'.$dossierinscription.'*'.$commentaire;?>"/>
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $nomeleve;?></a>
				</td>
				<td>
					<a><?php echo $sexeeleve;?></a>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<a><?php echo $moyenneobtenu;?></a>
				</td>
				<td>
					<?php 
						getAllSalle($idsallesuperieur,$iddomaine,$ligne,$pdo);
						
					?>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreeleveatransferer" value="<?php echo $ligne;?>"/><?php
}

function ListTransfertEleve($pdo)
{
	
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
					eleve.datenaissance_eleve as datenaissance_eleve,
					anneescolaire.libelle as libelle,
					salle.codesalle as codesalle,
					eleveanneescolaire.id as ideleveeleveanneescolaire

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement
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
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.statut=1
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			
			ORDER BY  elevesalle.id desc');	
    ?>
	
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;">#</th> 
				<th style="width:15%;font-weight:bold;color:#000">Ann&eacute;e scolaire</th>				
				<th style="width:20%;font-weight:bold;color:#000">Nom</th>
				<th style="width:18%;font-weight:bold;color:#000">Pr&eacute;nom</th>
				<th style="width:10%;font-weight:bold;color:#000">Sexe</th>
				<th style="width:17%;font-weight:bold;color:#000">Date de naissance</th>
				<th style="width:15%;font-weight:bold;color:red">Nouvelle Classe</th>
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

			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$libelle = $donnees['libelle'];
			$codesalle = $donnees['codesalle'];
			$ideleveeleveanneescolaire = $donnees['ideleveeleveanneescolaire'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			?>
			<tr>
				<td style="padding-left:20px;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $ideleveeleveanneescolaire;?>"/>
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
					<a><?php echo $datenaissance_eleve;?></a>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?></tbody>
	<input type="hidden" name="nbreelevetransferer" value="<?php echo $ligne;?>"/> 
	</table><?php
}