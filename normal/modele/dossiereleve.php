<?php

require_once __DIR__ . '/invitation_service.php';

function DesactiveEleve($idelevesalle,$statut,$pdo)
{
	$req='  UPDATE elevesalle
	        SET 
			elevesalle.statut=:statut

			WHERE
			elevesalle.id=:idelevesalle';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getAllStatutBourse($pdo)
{	
    ?>	
	<select class="select2_single form-control" tabindex="-1" required="required" name="boursier">
		<option></option>
		<option>Oui</option>
		<option>Non</option>
	</select><?php
}

function getAllStatutBourseSlected($bourse)
{	
    ?>	
	<select class="select2_single form-control" tabindex="-1" required="required" name="boursier">
		<?php
		if($bourse=="Oui")
		{
			?>
			<option></option>
			<option selected="selected">Oui</option>
			<option>Non</option>
			<?php
		}
		elseif($bourse=="Non")
		{
			?>
			<option></option>
			<option>Oui</option>
			<option selected="selected">Non</option>
			<?php
		}
		else
		{
			?>
			<option></option>
			<option>Oui</option>
			<option>Non</option><?php
		}
	?>
	</select>
	<?php
}

function getAllSalle($pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle,
					salle.idclasse as idclasse
					
			FROM salle');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle" id="idsalle" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			$idclasse = $donnees['idclasse'];
			
			echo '<option value="'.$id.'*'.$idclasse.'">'.$codesalle.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllSalleSelected($idsalle,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle,
					salle.idclasse as idclasse
					
			FROM salle');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle" id="idsalle">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			$idclasse = $donnees['idclasse'];
			if($id==$idsalle)
			{
				echo '<option value="'.$id.'*'.$idclasse.'" selected="selected">'.$codesalle.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'*'.$idclasse.'">'.$codesalle.'</option>';	
			}		
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getEleveStatutClasse($pdo)
{
	$req=(' SELECT elevestatutclasse.id,elevestatutclasse.libelle FROM elevestatutclasse');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idelevestatutclasse" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			echo '<option value="'.$id.'">'.$libelle.'</option>';	
		}		
	?>
	</Select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getEleveStatutEtablissement($pdo)
{
	$req=(' SELECT elevestatutetablissement.id,elevestatutetablissement.libelle FROM elevestatutetablissement');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idelevestatutetablissement" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			echo '<option value="'.$id.'">'.$libelle.'</option>';	
		}		
	?>
	</Select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getEleveStatutClasseSelected($idEleveStatutClasse,$pdo)
{
	$req=(' SELECT elevestatutclasse.id,elevestatutclasse.libelle FROM elevestatutclasse');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idelevestatutclasse" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			if($id==$idEleveStatutClasse)
			{
				echo '<option value="'.$id.'" selected="selected">'.$libelle.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'">'.$libelle.'</option>';	
			}	
		}		
	?>
	</Select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getEleveStatutEtablissementSelected($idEleveStatutEtablissement,$pdo)
{
	$req=(' SELECT elevestatutetablissement.id,elevestatutetablissement.libelle FROM elevestatutetablissement');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idelevestatutetablissement" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			if($id==$idEleveStatutEtablissement)
			{
				echo '<option value="'.$id.'" selected="selected">'.$libelle.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'">'.$libelle.'</option>';	
			}	
		}		
	?>
	</Select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getidPaiementTypeClasse($idtypepaiement,$idclasse,$idanneescolaire,$ideleveanneescolaire,$pdo)
{
	$req=(' SELECT paiementtypeclasse.id
			FROM paiementtypeclasse
			WHERE 
			paiementtypeclasse.idpaiementtype=:idpaiementtype
			AND
			paiementtypeclasse.idclasse=:idclasse
			AND
			paiementtypeclasse.idanneescolaire=:idanneescolaire
			AND
			paiementtypeclasse.statut=1
			AND
			paiementtypeclasse.ideleveanneescolaire=:ideleveanneescolaire');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpaiementtype', $idtypepaiement, PDO::PARAM_INT);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreatePaiementTypeClasse($idanneescolaire,$idpaiementtype,$idclasse,$montant,$ideleveanneescolaire,$remise,$pdo)
{   
    $idpaiementtypeclasse = getidPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$ideleveanneescolaire,$pdo);
	if($idpaiementtypeclasse==0)
	{
		$requete="INSERT INTO paiementtypeclasse(idpaiementtype,idclasse,montant,idanneescolaire,ideleveanneescolaire,remise) VALUES(:idpaiementtype,:idclasse,:montant,:idanneescolaire,:ideleveanneescolaire,:remise)";

		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':idpaiementtype', $idpaiementtype, PDO::PARAM_INT);
		$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt -> bindParam(':montant', $montant, PDO::PARAM_INT);
		$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt -> bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
		$stmt -> bindParam(':remise', $remise, PDO::PARAM_INT);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
	}
	else
	{
		UpdatePaiementTypeClasse($idpaiementtypeclasse,$idpaiementtype,$idclasse,$idanneescolaire,$montant,$remise,$pdo);
	}
}

function UpdatePaiementTypeClasse($id,$idpaiementtype,$idclasse,$idanneescolaire,$montant,$remise,$pdo)
{  
	
	$requete="UPDATE paiementtypeclasse SET idpaiementtype=:idpaiementtype,idclasse=:idclasse,montant=:montant,idanneescolaire=:idanneescolaire,remise=:remise WHERE paiementtypeclasse.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':idpaiementtype', $idpaiementtype, PDO::PARAM_INT);
	$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt -> bindParam(':montant', $montant, PDO::PARAM_INT);
	$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':remise', $remise, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function CreateDossier($photo,$matricule,$mailtuteur,$teltuteur,$nomeleve,$prenomeleve,$sexe,$etatclasse,$datenaissance,$lieunaissance,$idclasse,$idsalle,$idanneescolaire,$etatetablissement,$dossierinscription,$commentaire,$boursier,$remise_frais,$pdo)
{   
	$etateleve="";
    $ideleve = getidEleve($nomeleve,$prenomeleve,$datenaissance,$pdo);
	if($ideleve==0)
	{
		if($idclasse>=23)
		{
			$matricule = "IS-".$matricule;
		}
		$requete="INSERT INTO eleve(nom_eleve,prenom_eleve,sexe_eleve,etat_eleve,datenaissance_eleve,lieunaissance_eleve,matricule,teltuteur,mailtuteur,photo) VALUES(:nom_eleve,:prenom_eleve,:sexe_eleve,:etat_eleve,:datenaissance_eleve,:lieunaissance_eleve,:matricule,:teltuteur,:mailtuteur,:photo)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':nom_eleve', $nomeleve, PDO::PARAM_STR);
		$stmt ->bindParam(':prenom_eleve', $prenomeleve, PDO::PARAM_STR);
		$stmt ->bindParam(':datenaissance_eleve', $datenaissance, PDO::PARAM_STR);
		$stmt ->bindParam(':lieunaissance_eleve', $lieunaissance, PDO::PARAM_STR);
		$stmt ->bindParam(':sexe_eleve', $sexe, PDO::PARAM_STR);
		$stmt ->bindParam(':etat_eleve', $etateleve, PDO::PARAM_STR);
        $stmt ->bindParam(':matricule', $matricule, PDO::PARAM_STR);
        $stmt ->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
        $stmt ->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
        $stmt ->bindParam(':photo', $photo, PDO::PARAM_STR);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;

		$ideleve = getidEleve($nomeleve,$prenomeleve,$datenaissance,$pdo);
		$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat,dossierinscription,boursier,commentaire,etatremise) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat,:dossierinscription,:boursier,:commentaire,:etatremise)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
        $stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
        $stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt ->bindParam(':inscrit', $etatetablissement, PDO::PARAM_INT);
		$stmt ->bindParam(':etat', $etatclasse, PDO::PARAM_INT);
		$stmt ->bindParam(':dossierinscription', $dossierinscription, PDO::PARAM_STR);
		$stmt ->bindParam(':boursier', $boursier, PDO::PARAM_STR);
		$stmt ->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
		$stmt ->bindParam(':etatremise', $remise_frais, PDO::PARAM_STR);
		$stmt ->execute();
		$stmt ->closeCursor();
		$stmt =NULL;
		
		$ideleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo);
		$requete="INSERT INTO elevesalle(ideleve,idsalle,statut) VALUES(:ideleve,:idsalle,1)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':ideleve', $ideleveanneescolaire, PDO::PARAM_INT);
        $stmt ->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt ->execute();
		$stmt ->closeCursor();
		$stmt =NULL;
		
		envoyerInvitationParent($pdo, $ideleve, $mailtuteur, $teltuteur, $prenomeleve, $nomeleve);
		
		return getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo);
	}
	else
	{
		$ideleveeleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo);
		if($ideleveeleveanneescolaire==0)
		{
			$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat,dossierinscription,boursier,commentaire,etatremise) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat,:dossierinscription,:boursier,:commentaire,:etatremise)";
			$stmt = $pdo->prepare($requete);
			$stmt ->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
			$stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
			$stmt ->bindParam(':inscrit', $etatetablissement, PDO::PARAM_INT);
			$stmt ->bindParam(':etat', $etatclasse, PDO::PARAM_INT);
			$stmt ->bindParam(':dossierinscription', $dossierinscription, PDO::PARAM_STR);
			$stmt ->bindParam(':boursier', $boursier, PDO::PARAM_STR);
			$stmt ->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
			$stmt ->bindParam(':etatremise', $remise_frais, PDO::PARAM_STR);
			$stmt ->execute();
			$stmt ->closeCursor();
			$stmt =NULL;
			
			$ideleveanneescolaire = getidEleveAnneeScolaire($ideleve,$idanneescolaire,$pdo);
			$requete="INSERT INTO elevesalle(ideleve,idsalle,statut) VALUES(:ideleve,:idsalle,1)";
			$stmt = $pdo->prepare($requete);
			$stmt ->bindParam(':ideleve', $ideleveanneescolaire, PDO::PARAM_INT);
            $stmt ->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt ->execute();
			$stmt ->closeCursor();
			$stmt =NULL;
		
			envoyerInvitationParent($pdo, $ideleve, $mailtuteur, $teltuteur, $prenomeleve, $nomeleve);
		
			return getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
		}
		else
		{
			return 0;
		}
	}
}

function UpdateDossier($imagename,$matricule,$mailtuteur,$teltuteur,$ideleve,$nomeleve,$prenomeleve,$sexeeleve,$datenaissance,$lieunaissance,$idclasse,$idsalle,$etateleve,$inscrit,$ideleveanneescolaire,$idelevesalle,$dossierinscription,$commentaire,$boursier,$etatremise,$idpaiementtypeclasse,$pdo)
{
	if($imagename!="")
	{
		$req='  UPDATE eleve
		        SET 
				eleve.nom_eleve=:nomeleve,
				eleve.prenom_eleve=:prenomeleve,
				eleve.sexe_eleve=:sexeeleve,
				eleve.datenaissance_eleve=:datenaissanceeleve,
				eleve.lieunaissance_eleve=:lieunaissanceeleve,
				eleve.matricule=:matricule,
				eleve.teltuteur=:teltuteur,
				eleve.mailtuteur=:mailtuteur,
				eleve.photo=:photo
				
				WHERE
				eleve.id_eleve=:ideleve';
			
	    $stmt = $pdo->prepare($req);
		$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
		$stmt->bindParam(':nomeleve', $nomeleve, PDO::PARAM_STR);
		$stmt->bindParam(':prenomeleve', $prenomeleve, PDO::PARAM_STR);
		$stmt->bindParam(':sexeeleve', $sexeeleve, PDO::PARAM_STR);
		$stmt->bindParam(':datenaissanceeleve', $datenaissance, PDO::PARAM_STR);
		$stmt->bindParam(':lieunaissanceeleve', $lieunaissance, PDO::PARAM_STR);
		$stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		$stmt->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
		$stmt->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
		$stmt->bindParam(':photo', $imagename, PDO::PARAM_STR);
	    $stmt->execute();			
		$stmt->closeCursor();
		$stmt=NULL;		
	}
	else
	{
		$req='  UPDATE eleve
		        SET 
				eleve.nom_eleve=:nomeleve,
				eleve.prenom_eleve=:prenomeleve,
				eleve.sexe_eleve=:sexeeleve,
				eleve.datenaissance_eleve=:datenaissanceeleve,
				eleve.lieunaissance_eleve=:lieunaissanceeleve,
				eleve.matricule=:matricule,
				eleve.teltuteur=:teltuteur,
				eleve.mailtuteur=:mailtuteur
				
				WHERE
				eleve.id_eleve=:ideleve';
			
	    $stmt = $pdo->prepare($req);
		$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
		$stmt->bindParam(':nomeleve', $nomeleve, PDO::PARAM_STR);
		$stmt->bindParam(':prenomeleve', $prenomeleve, PDO::PARAM_STR);
		$stmt->bindParam(':sexeeleve', $sexeeleve, PDO::PARAM_STR);
		$stmt->bindParam(':datenaissanceeleve', $datenaissance, PDO::PARAM_STR);
		$stmt->bindParam(':lieunaissanceeleve', $lieunaissance, PDO::PARAM_STR);
		$stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		$stmt->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
		$stmt->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
	    $stmt->execute();			
		$stmt->closeCursor();
		$stmt=NULL;	
	}
	
	$req='  UPDATE eleveanneescolaire SET idclasse=:idclasse,inscrit=:inscrit,etat=:etat,dossierinscription=:dossierinscription,commentaire=:commentaire,boursier=:boursier,etatremise=:etatremise WHERE eleveanneescolaire.id=:ideleveanneescolaire';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':inscrit', $inscrit, PDO::PARAM_INT);
	$stmt->bindParam(':etat', $etateleve, PDO::PARAM_INT);
	$stmt->bindParam(':dossierinscription', $dossierinscription, PDO::PARAM_STR);
	$stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
	$stmt->bindParam(':boursier', $boursier, PDO::PARAM_STR);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_STR);
	$stmt->bindParam(':etatremise', $etatremise, PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	
	$req='  UPDATE elevesalle SET ideleve=:ideleve,idsalle=:idsalle WHERE elevesalle.id=:idelevesalle';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':ideleve', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	
	$req='  UPDATE paiementfrais SET idpaiementtypeclasse=:idpaiementtypeclasse WHERE paiementfrais.ideleveanneescolaire=:ideleveanneescolaire';
	
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idpaiementtypeclasse', $idpaiementtypeclasse, PDO::PARAM_INT);
	$stmt->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;
}

function DeleteInscription($ideleveanneescolaire,$ideleve,$pdo)
{
	$req='  DELETE FROM eleveanneescolaire WHERE eleveanneescolaire.id=:id';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id', $ideleveanneescolaire, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	

	$req='  DELETE FROM eleve WHERE eleve.id_eleve=:ideleve';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	
	$req='  DELETE FROM paiementtypeclasse WHERE paiementtypeclasse.ideleveanneescolaire=:ideleveanneescolaire';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getidEleve($nom_eleve,$prenom_eleve,$datenaissance_eleve,$pdo)
{
	$req=(' SELECT eleve.id_eleve
			FROM eleve
			WHERE 
			eleve.nom_eleve=:nom_eleve
			AND
			eleve.prenom_eleve=:prenom_eleve
			AND
			eleve.datenaissance_eleve=:datenaissance_eleve');
	$resultat=0;
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':nom_eleve', $nom_eleve, PDO::PARAM_STR);
	$stmt->bindParam(':prenom_eleve', $prenom_eleve, PDO::PARAM_STR);
	$stmt->bindParam(':datenaissance_eleve', $datenaissance_eleve, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id_eleve'];
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

function getTauxRemise($ideleveanneescolaire,$idpaiementtype,$idanneescolaire,$pdo)
{
	$req=(' SELECT distinct paiementtypeclasse.remise as remise
			FROM paiementtypeclasse
			WHERE 
			paiementtypeclasse.ideleveanneescolaire=:ideleveanneescolaire
			AND
			paiementtypeclasse.idpaiementtype=:idpaiementtype
			AND
			paiementtypeclasse.idanneescolaire=:idanneescolaire');
			
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleveanneescolaire',$ideleveanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtype',$idpaiementtype, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['remise'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	return $resultat;
}

function InscriptionDefinitionPaiement($idclasse,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  
					distinct
	                paiementtype.id as idpaiementtype,
					paiementtype.libelle as libellepaiementtype,
					paiementtypeclasse.id as idpaiementtypeclasse,
					paiementtypeclasse.montant as montantclasse,
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
			paiementtypeclasse.statut=1
			AND
			classe.idclasse=:idclasse
			AND
			anneescolaire.id=:idanneescolaire
			AND
			paiementtypeclasse.ideleveanneescolaire is null
			
			ORDER BY anneescolaire.id DESC');	
    ?>
	<label style="color:#000;font-weight:bold;font-family:comic sans ms;text-align:left;" class="control-label col-md-12 col-sm-12 col-xs-12">
	<i>::: Veuillez saisir les taux de r&eacute;duction s'il s'agit d'un boursier :::</i></label><br/><br/>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th> 
				<th style="width:19%;">Ann&eacute;e scolaire</th>
				<th style="width:13%;">Classe</th>
				<th style="width:19%;">Frais</th>
				<th style="width:19%;">M. Associ&eacute;</th>
				<th style="width:25%;">Taux (%)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$idpaiementtype = $donnees['idpaiementtype'];
			$libellepaiementtype = $donnees['libellepaiementtype'];
			$idpaiementtypeclasse = $donnees['idpaiementtypeclasse'];
			$montantclasse = $donnees['montantclasse'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$codeclasse = $donnees['codeclasse'];
			$idanneescolaire = $donnees['idanneescolaire'];
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox" checked="checked" name="idpaiementtype<?php echo $ligne;?>" value="<?php echo $idpaiementtype.'*'.$montantclasse.'*'.$idclasse.'*'.$idanneescolaire;?>"/>
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
					<input type="text" class="form-control" name="remise<?php echo $ligne;?>" style="background-color:#fbbc05;"/>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbrepaiementtypeclasse" value="<?php echo $ligne;?>"/><?php
}

function InscriptionDefinitionPaiementSelected($idclasse,$ideleveanneescolaire,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  
					distinct
	                paiementtype.id as idpaiementtype,
					paiementtype.libelle as libellepaiementtype,
					paiementtypeclasse.montant as montantclasse,
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
			paiementtypeclasse.statut=1
			AND
			classe.idclasse=:idclasse
			AND
			anneescolaire.id=:idanneescolaire
			AND
			paiementtypeclasse.ideleveanneescolaire is null
			
			ORDER BY anneescolaire.id DESC');	
    ?>
	<label style="color:#000;font-weight:bold;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms;text-align:left;" class="control-label col-md-12 col-sm-12 col-xs-12">
		<i>::: Veuillez saisir les taux de r&eacute;duction s'il s'agit d'un boursier :::</i></label><br/><br/>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th> 
				<th style="width:19%;">Ann&eacute;e scolaire</th>
				<th style="width:19%;">Classe</th>
				<th style="width:19%;">Frais</th>
				<th style="width:19%;">M. Associ&eacute;</th>
				<th style="width:19%;">Taux (%)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$remise="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$idpaiementtype = $donnees['idpaiementtype'];
			$libellepaiementtype = $donnees['libellepaiementtype'];
			$montantclasse = $donnees['montantclasse'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$codeclasse = $donnees['codeclasse'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$remise = getTauxRemise($ideleveanneescolaire,$idpaiementtype,$idanneescolaire,$pdo);
			if($remise==0)
			{
				$remise="";
			}	
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox" checked="checked" name="idpaiementtype<?php echo $ligne;?>" value="<?php echo $idpaiementtype.'*'.$montantclasse.'*'.$idclasse.'*'.$idanneescolaire;?>"/>
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
					<input type="text" class="form-control" name="remise<?php echo $ligne;?>" style="background-color:#fbbc05;" value="<?php echo $remise;?>"/>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbrepaiementtypeclasse" value="<?php echo $ligne;?>"/><?php
}

function DossierEleve($idanneescolaire,$pdo)
{
	$req=(' SELECT 
	            DISTINCT
				e.id_eleve,
				e.nom_eleve,
				e.prenom_eleve,
				e.sexe_eleve,
				e.etat_eleve,
				e.datenaissance_eleve,
				e.matricule,
				es.id AS idelevesalle,
				a.libelle AS Libelle,
				s.codesalle AS CodeSalle,
				es.statut AS statut,
				ec.libelle AS elevestatutclasse,
				ea.etatremise AS etatRemise
				
			FROM eleve e
			INNER JOIN eleveanneescolaire ea 
					ON e.id_eleve = ea.ideleve
			INNER JOIN anneescolaire a 
					ON ea.idanneescolaire = a.id
			INNER JOIN elevesalle es 
					ON ea.id = es.ideleve
			INNER JOIN salle s 
					ON es.idsalle = s.id
			INNER JOIN elevestatutclasse ec 
					ON ea.etat = ec.id
			WHERE a.id = :idanneescolaire
			ORDER BY es.id DESC;');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;font-weight:bold;color:#000">#</th> 
				<th style="width:5%;font-weight:bold;color:#000">Statut</th>
				<th style="width:10%;font-weight:bold;color:#000">Matricule</th>
				<th style="width:10%;font-weight:bold;color:#000">Ann&eacute;e scol.</th>
				<th style="width:15%;font-weight:bold;color:#000">Nom</th>
				<th style="width:15%;font-weight:bold;color:#000">Pr&eacute;nom</th>
				<th style="width:10%;font-weight:bold;color:#000">Sexe</th>
				<th style="width:10%;font-weight:bold;color:#000">Statut Etab.</th>
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:10%;font-weight:bold;color:#000">Remise ?</th>
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
			$etatRemise = $donnees['etatRemise'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<?php 
						if($statut==1)
						{
							?><span class="label label-success" style="font-size:12px;">Actif</span><?php
						}
						elseif($statut==0)
						{
							?><span class="label label-danger" style="font-size:12px;">Abandon</span><?php
						}
					?>
				</td>
				<td>
					<a><?php echo $matricule;?></a>
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
				<td>
					<a><span class="label label-warning" style="font-size:11px;"><?php echo $etatRemise;?></span></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function InscriptionPiece($pdo)
{
	$req=(' SELECT  
					distinct
					piece.id AS idpiece,
					piece.nom AS nompiece
				
			FROM piece
			WHERE
			piece.statut=1
			
			ORDER BY piece.nom DESC');	
    ?>
	<label style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms;text-align:left;" class="control-label col-md-12 col-sm-12 col-xs-12">
	[ Veuillez s&eacute;lectionner les pi&egrave;ces d'inscription ]</label><br/><br/>
	<table class="table table-striped projects" style="background-color:#fff;" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center">#</th> 
				<th style="width:47.5%;text-align:center">Nom</th>
				<th style="width:47.5%;text-align:center">Fichier scann&eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$idpiece = $donnees['idpiece'];
			$nompiece = $donnees['nompiece'];
			?>
			<tr>
				<td align="center">
					<input class="flat" type="checkbox" name="idpiece<?php echo $ligne;?>" value="<?php echo $idpiece;?>"/>
				</td>
				<td>
					<a><?php echo $nompiece;?></a>
				</td>
				<td align="center">
					<input type="file" class="form-control" name="fichier<?php echo $ligne;?>"/>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbrepiece" value="<?php echo $ligne;?>"/><?php
}

function InscriptionPieceSelected($ideleveanneescolaire, $pdo)
{
    $req = 'SELECT DISTINCT
                piece.id AS idpiece,
                piece.nom AS nompiece
            FROM piece
            WHERE piece.statut = 1
            ORDER BY piece.nom DESC';

    $stmt = $pdo->prepare($req);
    $stmt->execute();
    $ligne = 0;
    ?>
    <label style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms;text-align:left;" class="control-label col-md-12 col-sm-12 col-xs-12">
    [ Veuillez s&eacute;lectionner les pi&egrave;ces d'inscription ]
    </label>
    <br/><br/>
    <table class="table table-striped projects" style="background-color:#fff;" width="100%">
        <thead>
            <tr style="background-color:#eee;">
                <th style="width:5%;">#</th>
                <th style="width:31%;">Nom</th>
                <th style="width:31%;">Fichier scann&eacute;</th>
                <th style="width:31%;"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($donnees = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ligne++;
            $idpiece = $donnees['idpiece'];
            $nompiece = $donnees['nompiece'];

            // Récupération des données liées à l'élève pour cette pièce
            $tab = explode("*", getidPieceEleveanneescolaire($ideleveanneescolaire, $idpiece, $pdo));
            $id = $tab[0] ?? '';
            $nomFichier = $tab[1] ?? '';

            $checked = !empty($nomFichier) ? 'checked' : '';
            ?>
            <tr>
                <td align="center">
                    <input class="flat" type="checkbox" <?= $checked ?> name="idpiece<?= $ligne ?>" value="<?= htmlspecialchars($idpiece) ?>" />
                </td>
                <td align="center">
                    <?= htmlspecialchars($nompiece) ?>
                </td>
                <td align="center">
                    <input type="file" class="form-control" name="fichier<?= $ligne ?>"/>
					<input type="hidden" class="form-control" name="fichierold<?= $ligne ?>"/>
                </td>
                <td align="center">
                    <?php if (!empty($nomFichier)) : ?>
                        <a href="dossierinscription/<?= $nomFichier ?>" target="_blank">
                            <em style="color:red;font-weight:bold;">T&eacute;l&eacute;charger le fichier</em>
                        </a>
                    <?php else : ?>
                        <em>Aucun fichier</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <input type="hidden" name="nbrepiece" value="<?= $ligne ?>"/>
    <?php
    $stmt->closeCursor();
}

function getidPieceEleveanneescolaire($ideleveanneescolaire,$idpiece,$pdo)
{
	$req=(' SELECT distinct pieceeleveanneescolaire.id AS idpieceeleveanneescolaire,
	                        pieceeleveanneescolaire.nompiece AS nompiecepieceeleveanneescolaire
							
			FROM pieceeleveanneescolaire
			WHERE 
			pieceeleveanneescolaire.ideleveanneescolaire=:ideleveanneescolaire
			AND
			pieceeleveanneescolaire.idpiece=:idpiece');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idpiece', $idpiece, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['idpieceeleveanneescolaire'].'*'.$donnees['nompiecepieceeleveanneescolaire'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	return $resultat;
}

function CreatePieceEleveanneescolaireInscription($ideleveanneescolaire,$idpiece,$nompiece,$pdo)
{   
    $idPieceEleveanneescolaire = getidPieceEleveanneescolaire($ideleveanneescolaire,$idpiece,$pdo);
	if($idPieceEleveanneescolaire==0)
	{
		$requete="INSERT INTO pieceeleveanneescolaire(ideleveanneescolaire,idpiece,nompiece) VALUES(:ideleveanneescolaire,:idpiece,:nompiece)";

		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
		$stmt -> bindParam(':idpiece', $idpiece, PDO::PARAM_INT);
		$stmt -> bindParam(':nompiece', $nompiece, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
	}
	else
	{
		UpdatePieceEleveanneescolaireInscription($ideleveanneescolaire,$idpiece,$nompiece,$pdo);
	}
}

function UpdatePieceEleveanneescolaireInscription($id,$ideleveanneescolaire,$idpiece,$nompiece,$pdo)
{  
	$requete="UPDATE pieceeleveanneescolaire SET idpiece=:idpiece,nompiece=:nompiece WHERE id.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':idpiece', $idpiece, PDO::PARAM_INT);
	$stmt -> bindParam(':nompiece', $nompiece, PDO::PARAM_STR);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function DeletePieceEleveanneescolaireInscription($ideleveanneescolaire,$idpiece,$pdo)
{  
	$requete="DELETE FROM pieceeleveanneescolaire WHERE ideleveanneescolaire=:id AND idpiece=:idpiece";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt -> bindParam(':idpiece', $idpiece, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function genererNumero($dernierNumero) {
	
    // Récupérer l'année courante sur 2 chiffres
    $anneeCourante = date("y"); // exemple : 25 pour 2025
    // Séparer le numéro et l'année du dernier enregistrement
    list($compteur, $annee) = explode("-", $dernierNumero);
	$compteur = intval($compteur) + 1;
	
    /*if ($annee == $anneeCourante) {
        // Même année → on incrémente
        $compteur = intval($compteur) + 1;
    } else {
        // Nouvelle année → on réinitialise à 1
        $compteur = 1;
    }*/
    // Formatage avec 4 chiffres (ex : 0001, 1139, etc.)
    $compteurFormate = str_pad($compteur, 4, "0", STR_PAD_LEFT);

    return $compteurFormate . "-" . $anneeCourante;
}

function getLastMatricule($pdo)
{
	$req=(' SELECT eleve.matricule FROM eleve ORDER BY id_eleve DESC LIMIT 0,1');
	$stmt = $pdo->prepare($req);
	$stmt->execute();
	$resultat="";
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['matricule'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$pdo)
{	
	$req=(' SELECT paiementtypeclasse.montant as montant,paiementtypeclasse.id as id
	
			FROM paiementtypeclasse,anneescolaire
			WHERE 
			paiementtypeclasse.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			paiementtypeclasse.idclasse=:idclasse
			AND
			paiementtypeclasse.idpaiementtype=:idpaiementtype
			AND
			paiementtypeclasse.statut=1
			AND
			paiementtypeclasse.ideleveanneescolaire is null
			
			ORDER BY paiementtypeclasse.id DESC LIMIT 0,1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtype',$idpaiementtype,PDO::PARAM_INT);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['montant'].'*'.$donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}