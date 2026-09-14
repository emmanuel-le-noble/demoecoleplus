<?php

function getAllAnneeScolaire($pdo)
{
	$req=(' SELECT  anneescolaire.id,
	                anneescolaire.libelle
					
			FROM anneescolaire
			
			ORDER BY anneescolaire.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idanneescolaire" style="font-size:12px;" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			echo '<option value="'.$id.'">'.$libelle.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllAnneeScolaireSelected($anneescolaire,$pdo)
{
	$req=(' SELECT  anneescolaire.id,
	                anneescolaire.libelle
					
			FROM anneescolaire
			
			ORDER BY anneescolaire.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idanneescolaire" style="font-size:12px;" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			if($id==$anneescolaire)
			{
				echo '<option value="'.$id.'" selected="selected">'.$libelle.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$libelle.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPosition($pdo)
{
	$req=(' SELECT  position.idposition,
	                position.libposition

			FROM position');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idposition" style="font-size:12px;" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			
			echo '<option value="'.$idposition.'">'.$libposition.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPositionSelected($position,$pdo)
{
	$req=(' SELECT  position.idposition,
	                position.libposition

			FROM position');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idposition" style="font-size:12px;" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			
			if($idposition==$position)
			{
				echo '<option value="'.$idposition.'" selected="selected">'.$libposition.'</option>';
			}
			else
			{
				echo '<option value="'.$idposition.'">'.$libposition.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllClasseFiliere($pdo)
{
	$req=(' SELECT  classe.idclasse,
	                classe.nomclasse,
					classe.codeclasse

			FROM classe');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idclasse" style="font-size:12px;" required="required">
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

function getAllClasseFiliereSelected($classe,$pdo)
{
	
	$req=(' SELECT  classe.idclasse,
	                classe.nomclasse,
					classe.codeclasse

			FROM classe');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idclasse" style="font-size:12px;" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idclasse = $donnees['idclasse'];
			$nomclasse = $donnees['nomclasse'];
			$codeclasse = $donnees['codeclasse'];
			if($idclasse==$classe)
			{
				echo '<option value="'.$idclasse.'" selected="selected">'.$codeclasse.'</option>';	
			}
			else
			{
				echo '<option value="'.$idclasse.'">'.$codeclasse.'</option>';	
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getidDecision($idposition,$idanneescolaire,$dateconseil,$idclasse,$moyenne,$fichier,$pdo)
{
	$req=(' SELECT decision.id as id
			
			FROM decision
			WHERE 
			decision.idposition=:idposition
			AND
			decision.idanneescolaire=:idanneescolaire
			AND
			decision.datedecision=:dateconseil
			AND
			decision.idclasse=:idclasse
			AND
			decision.moyenne=:moyenne');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':dateconseil', $dateconseil, PDO::PARAM_STR);
	$stmt->bindParam(':moyenne', $moyenne, PDO::PARAM_STR);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt->execute();	
	$resultat=0;
	if($donnees=$stmt->fetch())
	{
	    $resultat=$donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateDecision($idposition,$idanneescolaire,$dateconseil,$idclasse,$moyenne,$fichier,$pdo)
{
	
    $id=getidDecision($idposition,$idanneescolaire,$dateconseil,$idclasse,$moyenne,$fichier,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO decision(idposition,idanneescolaire,datedecision,idclasse,moyenne,fichier) VALUES(:idposition,:idanneescolaire,:datedecision,:idclasse,:moyenne,:fichier)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':datedecision', $dateconseil, PDO::PARAM_STR);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt->bindParam(':moyenne', $moyenne, PDO::PARAM_STR);
		$stmt->bindParam(':fichier', $fichier, PDO::PARAM_STR);
		$stmt->execute();		
        $stmt->closeCursor();
		$stmt=NULL;
		$success="D&eacute;cision enregistr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement. Cette D&eacute;cision existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error;
}

function UpdateDecision($id,$idposition,$idanneescolaire,$datedecision,$idclasse,$moyenne,$fichier,$pdo)
{
	
	$requete="	UPDATE 	
				decision 
				SET 	
				decision.idposition=:idposition,
				decision.idanneescolaire=:idanneescolaire,
				decision.datedecision=:datedecision,
				decision.idclasse=:idclasse,
				decision.fichier=:fichier,
				decision.moyenne=:moyenne
	
				WHERE 
				decision.id=:id";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':datedecision', $datedecision, PDO::PARAM_STR);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt->bindParam(':moyenne', $moyenne, PDO::PARAM_STR);
	$stmt->bindParam(':fichier', $fichier, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt ->execute();		
    $stmt ->closeCursor();
	$stmt =NULL;
	$success="D&eacute;cision mise &agrave; jour avec succ&egrave;s";
	$error="";
	return $success.'*'.$error;
}

function DeleteDecision($id,$pdo)
{
	$req=' DELETE FROM decision WHERE decision.id=:iddecision';
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':iddecision', $id, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;		
}

function getClasseSuperieur($classepriorite,$pdo)
{
	
	$classepriorite=$classepriorite-1;
	
	$req=(' SELECT distinct classe.idclasse as id FROM classe WHERE classe.priorite=:priorite');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':priorite', $classepriorite, PDO::PARAM_INT);
	$stmt->execute();	
	$resultat=0;
	if($donnees=$stmt->fetch())
	{
	    $resultat=$donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getSalleSuperieur($idclasse,$sallepriorite,$pdo)
{
	$req=(' SELECT salle.id as idsalle
			FROM salle
			WHERE 
			salle.idclasse=:idclasse
			AND
			salle.priorite=:priorite');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':priorite',$sallepriorite,PDO::PARAM_INT);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->execute();	
	$resultat=0;
	if($donnees=$stmt->fetch())
	{
	    $resultat=$donnees['idsalle'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
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
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_STR);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	else
	{
	    $resultat=0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function TransfertClassSuperieur($idanneescolaire,$idnouvelleanneescolaire,$idclasse,$idposition,$classepriorite,$moyenne,$pdo)
{
	$etatetablissement=2;
	$etatclasse=1;
	
	$req=(' SELECT  distinct
					eleve.id_eleve as ideleve,
					salle.priorite as sallepriorite
					
			FROM eleve,eleveanneescolaire,elevesalle,salle,bulletin
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.idclasse=:idclasse
			AND
			elevesalle.id=bulletin.ideleve
			AND
			bulletin.moyen_ann>=:moyen_ann
			AND
			bulletin.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':moyen_ann',$moyenne,PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	while($donnees = $stmt->fetch())
	{

	    $ideleve=$donnees['ideleve'];
		$sallepriorite=$donnees['sallepriorite'];
		$idclassesuperieur=getClasseSuperieur($classepriorite,$pdo);
		$idsallesuperieur=getSalleSuperieur($idclassesuperieur,$sallepriorite,$pdo);
		
		$ideleveeleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idnouvelleanneescolaire,$pdo);
		if($ideleveeleveanneescolaire==0)
		{
			$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat)";
			$stmt_ = $pdo->prepare($requete);
			$stmt_ -> bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
			$stmt_ -> bindParam(':idanneescolaire',$idnouvelleanneescolaire,PDO::PARAM_INT);
			$stmt_ -> bindParam(':idclasse',$idclassesuperieur,PDO::PARAM_INT);
			$stmt_ -> bindParam(':inscrit',$etatetablissement,PDO::PARAM_INT);
			$stmt_ -> bindParam(':etat',$etatclasse,PDO::PARAM_INT);
			$stmt_ -> execute();
			$stmt_ -> closeCursor();
			$stmt_ = NULL;
			
			$ideleveeleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idnouvelleanneescolaire,$pdo);
			CreateEleveSalle($ideleveeleveanneescolaire,$idsallesuperieur,$idnouvelleanneescolaire,$pdo);
			$ligne++;
		}
	}
	$requete="UPDATE decision SET passageclassesup=:passageclassesup WHERE decision.id=:iddecision";
	$stmt=$pdo->prepare($requete);
	$stmt->bindParam(':iddecision',$iddecision,PDO::PARAM_INT);
	$stmt->bindParam(':passageclassesup',$ligne,PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function getidAnneeScolaire($pdo)
{
	$req=(' SELECT max(anneescolaire.id) as idanneescolaire
			FROM anneescolaire
			WHERE 
			anneescolaire.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['idanneescolaire'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function ListDecision($pdo)
{
	
	$req=(" SELECT  distinct
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelle,
					position.libposition as libposition,
					classe.idclasse as idclasse,
					classe.priorite as classepriorite,
					classe.codeclasse as codeclasse,
					decision.id as iddecision,
					decision.datedecision as datedecision,
					decision.moyenne as moyenne,
					decision.fichier as fichier,
					decision.passageclassesup as passageclassesup,
					decision.idposition as idposition
					
			FROM anneescolaire,position,decision,classe
			WHERE
			anneescolaire.id=decision.idanneescolaire
			AND
			position.idposition=decision.idposition
			AND
			decision.idclasse=classe.idclasse

			ORDER BY decision.id DESC");	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-size:12px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">#</th>
				<th style="width:14%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">DATE CONSEIL</th>
				<th style="width:14%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">ANN&Eacute; SCOLAIRE</th>
				<th style="width:14%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">POSITION</th>
				<th style="width:14%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">CLASSE</th>
				<th style="width:14%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">MOYENNE R&Eacute;USSITE</th>
				<th style="width:14%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75)">FICHIER D&Eacute;CISION</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idanneescolaire = trim($donnees['idanneescolaire']);
			$libelle = trim($donnees['libelle']);
			$libposition = trim($donnees['libposition']);
			$iddecision = trim($donnees['iddecision']);
			$datedecision = trim($donnees['datedecision']);
			$tab = explode("-",trim($datedecision));
			$datedecision = $tab[2]."/".$tab[1]."/".$tab[0];
			$codeclasse = trim($donnees['codeclasse']);
			$moyenne = trim($donnees['moyenne']);
			$fichier = trim($donnees['fichier']);
			$idclasse = trim($donnees['idclasse']);
			$classepriorite = trim($donnees['classepriorite']);
			$passageclassesup = trim($donnees['passageclassesup']);
			$idposition = trim($donnees['idposition']);
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="iddecision<?php echo $ligne;?>" value="<?php echo $iddecision.'*'.$idclasse.'*'.$classepriorite.'*'.$idanneescolaire.'*'.$moyenne.'*'.$passageclassesup.'*'.$idposition;?>"/>					
				</td>
				<td>
					<a><?php echo $datedecision;?></a>
			    </td>
				<td>
					<a><?php echo $libelle;?></a>
			    </td>
				<td>
					<a><?php echo $libposition;?></a>
			    </td>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo $moyenne;?></a>
			    </td>
				<td>
					<a>
					<?php
						if($fichier!="")
						{
							?><a href="decisiondocument/<?php echo $fichier;?>"><span style="font-size:11px;color:red;">[T&eacute;l&eacute;charger le fichier]</span></a><?php
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
	<input type="hidden" name="nbredecision" value="<?php echo $ligne;?>"/><?php
}

function getAllSalleClasse($idclasse,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle
			WHERE
			salle.idclasse=:idclasse
			AND
			salle.statut=0');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="idsalle" style="font-size:12px;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			
			echo '<option value="'.$id.'">'.$codesalle.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllSalleClasseSuperieur($idclasse,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle
			WHERE
			salle.idclasse=:idclasse
			AND
			salle.statut=0');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="idsalle" style="font-size:12px;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			
			echo '<option value="'.$id.'">'.$codesalle.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function ListDecisionConseilPassageEnClasseSuperieur($iddecision,$pdo)
{
	
	$req=(" SELECT  distinct
					salledepart.codesalle as codesalledepart,
					sallearrive.codesalle as codesallearrive,
					decisiondetail.id as iddecisiondetail

			FROM salle as salledepart,salle as sallearrive,decisiondetail
			WHERE
			decisiondetail.idsalledepart=salledepart.id
			AND
			decisiondetail.idsalledepart=salledepart.id
			AND
			decisiondetail.iddecision=:iddecision");	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:left;font-weight:bold;color:#000;">#</th>
				<th style="width:47.5%;text-align:left;font-weight:bold;color:#000;">Classe D&eacute;part</th>
				<th style="width:47.5%;text-align:left;font-weight:bold;color:#000;">Classe Arriv&eacute;e</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':iddecision',$iddecision,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$codesalledepart = trim($donnees['codesalledepart']);
			$codesallearrive = trim($donnees['codesallearrive']);
			$iddecisiondetail = trim($donnees['iddecisiondetail']);
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="iddecisiondetail<?php echo $ligne;?>" value="<?php echo $iddecisiondetail;?>"/>					
				</td>
				<td>
					<a><?php echo $codesalledepart;?></a>
			    </td>
				<td>
					<a><?php echo $codesallearrive;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbredecisiondetail" value="<?php echo $ligne;?>"/><?php
}

function ListHoraireSaisieNote($pdo)
{
	$req=(" SELECT  
	                horairesaisienote.id as id,
					horairesaisienote.datedebut as datedebut_,
					horairesaisienote.datefin as datefin_,
					DATE_FORMAT(horairesaisienote.datedebut, '%d/%m/%Y') AS datedebut,
					DATE_FORMAT(horairesaisienote.datefin, '%d/%m/%Y') as datefin,
					utilisateur.nom_user as nomuser,
					utilisateur.prenom_user as prenom_user
	
			FROM horairesaisienote,utilisateur
			WHERE
			utilisateur.id=horairesaisienote.created_id
			
			ORDER BY horairesaisienote.id DESC");	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:left;font-weight:bold;color:#000;">#</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000;">Date D&eacute;but</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000;">Date Fin</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000;">Statut</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000;">Cr&eacute;e par</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = trim($donnees['id']);
			$datedebut = trim($donnees['datedebut']);
			$datefin = trim($donnees['datefin']);
			$datedebut_ = trim($donnees['datedebut_']);
			$datefin_ = trim($donnees['datefin_']);
			$aujourdhui = date('Y-m-d');
			$nomuser = trim($donnees['nomuser']);
			$prenom_user = trim($donnees['prenom_user']);
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>					
				</td>
				<td>
					<a><?php echo $datedebut;?></a>
			    </td>
				<td>
					<a><?php echo $datefin;?></a>
			    </td>
				<td>
				<?php
					if ($datefin_ >= $aujourdhui) 
					{
						$statut = "En cours";
						?><a><span class="label label-info"><?php echo $statut;?></span></a><?php
					} 
					elseif ($datefin_ < $aujourdhui) 
					{
						$statut = "Clôturée";
						?><a><span class="label label-danger"><?php echo $statut;?></span></a><?php
					} 
				?>
			    </td>
				<td>
					<a><?php echo $nomuser.' '.$prenom_user;?></a>
			    </td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrehoraire" value="<?php echo $ligne;?>"/><?php
}

function getidHoraire($datedebut,$datefin,$pdo)
{
	$req=(' SELECT horairesaisienote.id as id
			FROM horairesaisienote
			WHERE 
			horairesaisienote.datedebut=:datedebut
			AND
			horairesaisienote.datefin=:datefin');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':datedebut', $datedebut, PDO::PARAM_STR);
	$stmt->bindParam(':datefin', $datefin, PDO::PARAM_STR);
	$stmt->execute();	
	$resultat=0;
	if($donnees=$stmt->fetch())
	{
	    $resultat=$donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateHoraire($datedebut,$datefin,$iduser,$pdo)
{	
    $id=getidHoraire($datedebut,$datefin,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO horairesaisienote(datedebut,datefin,statut,created_id) VALUES(:datedebut,:datefin,1,:created_id)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':datedebut', $datedebut, PDO::PARAM_STR);
		$stmt->bindParam(':datefin', $datefin, PDO::PARAM_STR);
		$stmt->bindParam(':created_id', $iduser, PDO::PARAM_STR);
		$stmt->execute();		
        $stmt->closeCursor();
		$stmt=NULL;
		$success="Horaire enregistr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement. Cette Horaire existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error;
}

function DeleteHoraire($id,$pdo)
{
	$req=' DELETE FROM horairesaisienote WHERE horairesaisienote.id=:id';
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;		
}