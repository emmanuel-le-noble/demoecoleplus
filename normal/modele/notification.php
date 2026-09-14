<?php

function getAllTypeNotification($pdo)
{	
    ?>	
	<select class="select2_single form-control" tabindex="-1" required="required" name="typenotification" id="typenotification" style="font-size:12px;" onchange="makeRequest('NotificationPeriode.php','typenotification','periode')">
		<option value="0"></option>
		<option value="1">Bulletin(s)</option>
		<option value="2">Absence(s)</option>
		<option value="3">Particuli&egrave;re(s)</option>
	</select><?php
}

function getAllTypeNotificationSlected($notification)
{	
    ?>	
	<select class="select2_single form-control" tabindex="-1" required="required" name="typenotification" id="typenotification" style="font-size:12px;" onchange="makeRequest('NotificationPeriode.php','typenotification','periode')">
		<?php
		if($notification=="Bulletin(s)")
		{
			?>
			<option value="0"></option>
			<option value="1" selected="selected">Bulletin(s)</option>
			<option value="2">Absence(s)</option>
			<option value="3">Particuli&egrave;re(s)</option>
			<?php
		}
		elseif($notification=="Absence(s)")
		{
			?>
			<option value="0"></option>
			<option value="1">Bulletin(s)</option>
			<option value="2" selected="selected">Absence(s)</option>
			<option value="3">Particuli&egrave;re(s)</option>
			<?php
		}
		elseif($notification=="Particuli&egrave;re(s)")
		{
			?>
			<option value="0"></option>
			<option value="1">Bulletin(s)</option>
			<option value="2">Absence(s)</option>
			<option value="3" selected="selected">Particuli&egrave;re(s)</option>
			<?php
		}
		else
		{
			?>
			<option value="0"></option>
			<option value="1">Bulletin(s)</option>
			<option value="2">Absence(s)</option>
			<option value="3">Particuli&egrave;re(s)</option>
			<?php
		}
	?>
	</select>
	<?php
}

function getAllPosition($notification,$pdo)
{
	$req=(' SELECT  position.idposition as idposition,
	                position.libposition as libposition

			FROM position');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idposition" id="idposition" style="font-size:12px;" onchange="makeRequest('NotificationListe.php','idposition','liste')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			echo '<option value="'.$idposition.'*'.$notification.'">'.$libposition.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPositionSelected($position,$notification,$pdo)
{
	$req=(' SELECT  position.idposition as idposition,
	                position.libposition as libposition

			FROM position');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idposition" id="idposition" style="font-size:12px;" onchange="makeRequest('NotificationListe.php','idposition','liste')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			if($idposition==$position)
			{
				echo '<option value="'.$idposition.'*'.$notification.'" selected="selected">'.$libposition.'</option>';
			}
			else
			{
				echo '<option value="'.$idposition.'*'.$notification.'">'.$libposition.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllClasse($pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idsalle" id="idsalle" required="required" style="font-size:12px;" onchange="makeRequest('NotificationNumeroSpecifique.php','idsalle','numerospecifique')">
	<option value="-1"></option>
	<option value="0">Numero(s) specifique(s)</option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			
			echo '<option value="'.$id.'">'.$codesalle.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getallMatiere($dateenreg,$idelevesalle,$idsalle,$idanneescolaire,$idposition,$pdo)
{
	
	$req=(" SELECT 	matiere.code_matiere as codematiere,
					absences.nbreabsence as nbreabsence
					
			FROM matiere,absences
			WHERE 
			matiere.id_matiere=absences.idmatiere
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idposition=:idposition
			AND
			absences.idelevesalle=:idelevesalle
			AND
			absences.idsalle=:idsalle
			AND
			absences.dateenreg=:dateenreg");
	
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':dateenreg', $dateenreg, PDO::PARAM_STR);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['codematiere'].':'.$donnees['nbreabsence'].'<br/>'.$resultat;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getidEnvoiSms($idanneescolaire,$idposition,$typenotification,$objet,$pdo)
{
	$req=(' SELECT envoisms.id as id
			FROM envoisms
			WHERE 
			envoisms.idanneescolaire=:idanneescolaire
			AND
			envoisms.idposition=:idposition
			AND
			envoisms.typenotification=:typenotification
			AND
			envoisms.objet=:objet');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':typenotification', $typenotification, PDO::PARAM_STR);
	$stmt->bindParam(':objet', $objet, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function AjouterEnvoiSms($idanneescolaire,$idposition,$objetoperation,$typenotification,$iduser,$pdo)
{
	
    $id=getidEnvoiSms($idanneescolaire,$idposition,$typenotification,$objetoperation,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO envoisms(idanneescolaire,idposition,objet,dateenvoi,created_id,typenotification) VALUES(:idanneescolaire,:idposition,:objet,sysdate(),:created_id,:typenotification)";
		
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':objet', $objetoperation, PDO::PARAM_STR);
		$stmt->bindParam(':typenotification', $typenotification, PDO::PARAM_STR);
		$stmt->bindParam(':created_id', $iduser, PDO::PARAM_INT);
		$stmt->execute();		
		$stmt->closeCursor();
		$stmt=NULL;
		
		$id=getidEnvoiSms($idanneescolaire,$idposition,$typenotification,$objetoperation,$pdo);
		return $id.'*1';
	}
	else
	{
		return $id.'*0';
	}
}

function getidEnvoiSmsDetail($idenvoisms,$dateoperation,$idelevesalle,$valeur,$teltuteur,$pdo)
{

	$req=(' SELECT count(envoismsdetail.id) as exist
			FROM envoismsdetail
			WHERE 
			envoismsdetail.idenvoisms=:idenvoisms
			AND
			envoismsdetail.dateoperation=:dateoperation');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':dateoperation', $dateoperation, PDO::PARAM_STR);
	$stmt->bindParam(':idenvoisms', $idenvoisms, PDO::PARAM_INT);
	$stmt->execute();
	$resultat="";
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['exist'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function AjouterEnvoiSmsDetail($idenvoisms,$dateoperation,$idelevesalle,$valeur,$teltuteur,$pdo)
{
	
	$requete="INSERT INTO envoismsdetail(idenvoisms,dateoperation,idelevesalle,valeur,teltuteur) VALUES(:idenvoisms,:dateoperation,:idelevesalle,:valeur,:teltuteur)";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':idenvoisms',$idenvoisms,PDO::PARAM_INT);
	$stmt->bindParam(':dateoperation',$dateoperation,PDO::PARAM_STR);
	$stmt->bindParam(':idelevesalle',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':valeur',$valeur,PDO::PARAM_STR);
	$stmt->bindParam(':teltuteur',$teltuteur,PDO::PARAM_STR);
	$stmt->execute();		
	$stmt->closeCursor();
	$stmt=NULL;
}

function ListAbsenceSend($idposition,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleve.teltuteur as teltuteur,
					position.idposition as idposition,
					position.libposition as libposition,
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					salle.id as idsalle,
					salle.codesalle as codesalle,
					absences.idelevesalle as idelevesalle,
					absences.dateenreg as dateenreg
					
			FROM    eleve,eleveanneescolaire,anneescolaire,elevestatutclasse,elevestatutetablissement,elevesalle,absences,position,salle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			elevesalle.idsalle=salle.id
			AND
			elevesalle.id=absences.idelevesalle
			AND
			position.idposition=absences.idposition
			AND
			anneescolaire.id=absences.idanneescolaire
			AND
			absences.idsalle=salle.id
			AND
			position.idposition=:idposition
			AND
			anneescolaire.id=:idanneescolaire
			AND
			absences.idmatiere is not null
			AND
			absences.dateenreg NOT IN (SELECT envoismsdetail.dateoperation FROM envoismsdetail)

			ORDER BY eleve.nom_eleve desc');	
    ?>
	<div>
		<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
		::: Liste des absences enregistr&eacute;es au cours de la p&eacute;riode :::
		</span></div><br/>
	<table class="table table-striped projects" width="100%" style="font-size:12px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">CODE SALLE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">NOM & PRENOM</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TEL TUTEUR</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">DATE D'ABSENCE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">MATIERE/NBRE ABSENCE</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->execute();
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$nom = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$idsalle = $donnees['idsalle'];
			$idelevesalle = $donnees['idelevesalle'];
			$nomprenomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$teltuteur = $donnees['teltuteur'];
			$codesalle = $donnees['codesalle'];	
			$dateenreg = $donnees['dateenreg'];
			$tab = explode("-",trim($dateenreg));
			$dateenreg = $tab[2]."/".$tab[1]."/".$tab[0];
			$allmatiere = getallMatiere($donnees['dateenreg'],$idelevesalle,$idsalle,$idanneescolaire,$idposition,$pdo);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $dateenreg.'*'.$nom.'*'.$idelevesalle.'*'.$allmatiere.'*'.$teltuteur;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<a><?php echo $nomprenomeleve;?></a>
				</td>
				<td>
					<a><?php echo $teltuteur;?></a>
				</td>
				<td>
					<a><?php echo $dateenreg;?></a>
				</td>
				<td>
					<a><?php echo getallMatiere($donnees['dateenreg'],$idelevesalle,$idsalle,$idanneescolaire,$idposition,$pdo);?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbretotalligne" value="<?php echo $ligne;?>"/>
	<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
	<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
	<div align="center" class="col-md-6 col-md-offset-3">
		<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EnvoiSms.php'"/>
		<input type="submit" class="btn btn-round btn-success" name="EnregistrerEnvoirSms" value="Enregistrer"/> 
	</div><?php
}

function ListBulletinSend($idposition,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleve.teltuteur as teltuteur,
					elevesalle.id as idelevesalle,
					position.idposition as idposition,
					position.libposition as libposition,
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					salle.id as idsalle,
					salle.codesalle as codesalle,
					bulletin.rang as rangbulletin,
					bulletin.moyenne_gene as moyen_gene,
					bulletin.dategeneration as dategeneration

			FROM    eleve,eleveanneescolaire,anneescolaire,elevestatutclasse,elevestatutetablissement,elevesalle,bulletin,position,salle
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			elevesalle.idsalle=salle.id
			AND
			elevesalle.id=bulletin.ideleve
			AND
			position.idposition=bulletin.idposition
			AND
			anneescolaire.id=bulletin.idanneescolaire
			AND
			bulletin.idsalle=salle.id
			AND
			position.idposition=:idposition
			AND
			anneescolaire.id=:idanneescolaire
			AND
			bulletin.dategeneration NOT IN (SELECT envoismsdetail.dateoperation FROM envoismsdetail)

			ORDER BY eleve.nom_eleve asc');	
    ?>
	<div>
		<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
		::: R&eacute;sultat des &eacute;valuations de la p&eacute;riode :::
		</span></div><br/>
	<table class="table table-striped projects" width="100%" style="font-size:12px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">CODE SALLE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">NOM & PRENOM</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TEL TUTEUR</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">MOYEN TRIM/SEM</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">RANG</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->execute();
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$ideleve = $donnees['ideleve'];
			$idelevesalle = $donnees['idelevesalle'];
			$nom = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$teltuteur = $donnees['teltuteur'];
			$moyen_gene = $donnees['moyen_gene'];
			$rang = $donnees['rangbulletin'];
			$codesalle = $donnees['codesalle'];
			$libposition = $donnees['libposition'];
			$dategeneration = $donnees['dategeneration'];
			$all = $libposition.'<br/> Moyenne : '.$moyen_gene.'<br/> Rang : '.$rang;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $dategeneration.'*'.$nom.'*'.$idelevesalle.'*'.$all.'*'.$teltuteur;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo $teltuteur;?></a>
				</td>
				<td>
					<a><?php echo $moyen_gene;?></a>
				</td>
				<td>
					<a><?php echo $rang;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrebulletin" value="<?php echo $ligne;?>"/>
	<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
	<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
	<div align="center" class="col-md-6 col-md-offset-3">
		<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EnvoiSms.php'"/>
		<input type="submit" class="btn btn-round btn-success" name="EnregistrerEnvoirSms" value="Enregistrer"/> 
	</div><?php
}

function ListMailSend($idanneescolaire,$pdo)
{
	
	$req=(' SELECT  
	                distinct 
					envoimail.id as id,
					envoimail.typenotification as notification,
					envoimail.objet as objet,
					envoimail.dateenvoi as dateenvoi,
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					position.idposition as idposition,
					position.libposition as libposition,
					utilisateur.nom_user as nomuser,
					utilisateur.prenom_user as prenomuser

			FROM    envoimail,utilisateur,anneescolaire,position
			WHERE
			envoimail.created_id=utilisateur.id
			AND
			envoimail.idanneescolaire=anneescolaire.id
			AND
			envoimail.idposition=position.idposition
			
			ORDER BY  envoimail.id desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-size:11px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ANN&Eacute;E SCOLAIRE</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">POSITION</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TYPE NOTIFICATION</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">OBJET MAIL</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">DATE ENVOI</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ENVOY&Eacute; PAR</th>
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
			$notification = $donnees['notification'];
			$objet = $donnees['objet'];
			$dateenvoi = $donnees['dateenvoi'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			$nomuser = $donnees['nomuser'].' '.$donnees['prenomuser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $libposition;?></a>
				</td>
				<td>
					<a><?php echo $notification;?></a>
				</td>
				<td>
					<a><?php echo $objet;?></a>
				</td>
				<td>
					<a><?php echo $dateenvoi;?></a>
				</td>
				<td>
					<a><?php echo $nomuser;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbremailsend" value="<?php echo $ligne;?>"/><?php
}

function ListSmsSend($pdo)
{
	
	$req=(' SELECT  
	                distinct 
					envoisms.id as id,
					envoisms.typenotification as notification,
					envoisms.objet as objet,
					envoisms.dateenvoi as dateenvoi,
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					position.idposition as idposition,
					position.libposition as libposition,
					utilisateur.nom_user as nomuser,
					utilisateur.prenom_user as prenomuser

			FROM    envoisms,utilisateur,anneescolaire,position
			WHERE
			envoisms.created_id=utilisateur.id
			AND
			envoisms.idanneescolaire=anneescolaire.id
			AND
			envoisms.idposition=position.idposition
			
			ORDER BY  envoisms.id desc');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-size:11px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ANN&Eacute;E SCOLAIRE</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">POSITION</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TYPE NOTIFICATION</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">OBJET SMS</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">DATE ENVOI</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ENVOY&Eacute; PAR</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		while($donnees = $stmt->fetch())
		{
			
			$id = $donnees['id'];
			$notification = $donnees['notification'];
			if($notification==1)
			{
				$notification ="Bulletin(s)";
			}
			elseif($notification==2)
			{
				$notification ="Absence(s)";
			}
			else
			{
				$notification ="R&eacute;union(s)";
			}
			$objet = $donnees['objet'];
			$dateenvoi = $donnees['dateenvoi'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			$nomuser = $donnees['nomuser'].' '.$donnees['prenomuser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $libposition;?></a>
				</td>
				<td>
					<a><?php echo $notification;?></a>
				</td>
				<td>
					<a><?php echo $objet;?></a>
				</td>
				<td>
					<a><?php echo $dateenvoi;?></a>
				</td>
				<td>
					<a><?php echo $nomuser;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresmssend" value="<?php echo $ligne;?>"/><?php
}

function ListSmsSenddetail($idnotification,$pdo)
{
	
	$req=(' SELECT  
	                distinct 
					envoismsdetail.id as idenvoismsdetail,
					envoismsdetail.dateoperation as dateoperation,
					envoismsdetail.nomprenomeleve as nomprenomeleve,
					envoismsdetail.teltuteur as teltuteur,
					envoismsdetail.valeur as valeur
					
			FROM    envoismsdetail
			WHERE
			envoismsdetail.idenvoisms=:idenvoisms');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-size:11px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">DATE OP&Eacute;RATION</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">NOM & PR&Eacute;NOM</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TEL TUTEUR</th>
				<th style="width:13%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">CONTENU ENVOY&Eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		while($donnees = $stmt->fetch())
		{
			
			$idenvoismsdetail = $donnees['idenvoismsdetail'];
			$dateoperation = $donnees['dateoperation'];
			$tab = explode("-",trim($dateoperation));
			$dateoperation = $tab[2]."/".$tab[1]."/".$tab[0];
			$nomprenomeleve = $donnees['nomprenomeleve'];
			$teltuteur = $donnees['teltuteur'];
			$valeur = $donnees['valeur'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" checked="checked"/>
				</td>
				<td>
					<a><?php echo $dateoperation;?></a>
				</td>
				<td>
					<a><?php echo $nomprenomeleve;?></a>
				</td>
				<td>
					<a><?php echo $teltuteur;?></a>
				</td>
				<td>
					<a><?php echo $valeur;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table><?php
}
?>