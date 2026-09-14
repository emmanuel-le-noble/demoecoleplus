<?php
function CreatePaiementTypeClasseTranche($idpaiementtypeclasse,$idpaiementtranche,$dateecheance,$montant,$pdo)
{   
    $idPaiementTypeClasseTranche = GetPaiementTypeClasseTranche($idpaiementtypeclasse,$idpaiementtranche,$dateecheance,$montant,$pdo);
	if($idPaiementTypeClasseTranche==0)
	{
		$requete="INSERT INTO paiementtypeclassetranche(idpaiementtypeclasse,idpaiementtranche,montant,dateecheance) VALUES(:idpaiementtypeclasse,:idpaiementtranche,:montant,:dateecheance)";

		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':idpaiementtypeclasse', $idpaiementtypeclasse, PDO::PARAM_INT);
		$stmt -> bindParam(':idpaiementtranche', $idpaiementtranche, PDO::PARAM_INT);
		$stmt -> bindParam(':montant', $montant, PDO::PARAM_STR);
		$stmt -> bindParam(':dateecheance', $dateecheance, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		//
		$idPaiementTypeClasseTranche = GetPaiementTypeClasseTranche($idpaiementtypeclasse,$idpaiementtranche,$dateecheance,$montant,$pdo);
	}
	return $idPaiementTypeClasseTranche;
}

function CreatePaiementTypeClasse($idanneescolaire,$idtypepaiement,$idclasse,$montant,$pdo)
{   
    $idPaiementTypeClasse = getidPaiementTypeClasse($idtypepaiement,$idclasse,$idanneescolaire,$pdo);
	if($idPaiementTypeClasse==0)
	{
		$requete="INSERT INTO paiementtypeclasse(idpaiementtype,idclasse,montant,idanneescolaire) VALUES(:idpaiementtype,:idclasse,:montant,:idanneescolaire)";

		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':idpaiementtype', $idtypepaiement, PDO::PARAM_INT);
		$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt -> bindParam(':montant', $montant, PDO::PARAM_INT);
		$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Enregistrement r&eacute;ussi avec succ&egrave;s";
		$error="";
		//
		$idPaiementTypeClasse = getidPaiementTypeClasse($idtypepaiement,$idclasse,$idanneescolaire,$pdo);
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement.";
		//
		$idPaiementTypeClasse = getidPaiementTypeClasse($idtypepaiement,$idclasse,$idanneescolaire,$pdo);
	}
	return $success.'*'.$error.'*'.$idPaiementTypeClasse;
}

function GetPaiementTypeClasseTranche($idpaiementtypeclasse,$idpaiementtranche,$dateecheance,$montant,$pdo)
{
	$req=(' SELECT paiementtypeclassetranche.id
			FROM paiementtypeclassetranche
			WHERE 
			paiementtypeclassetranche.idpaiementtypeclasse=:idpaiementtypeclasse
			AND
			paiementtypeclassetranche.idpaiementtranche=:idpaiementtranche
			AND
			paiementtypeclassetranche.montant=:montant
			AND
			paiementtypeclassetranche.dateecheance=:dateecheance
			AND
			paiementtypeclassetranche.statut=1');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpaiementtypeclasse', $idpaiementtypeclasse, PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtranche', $idpaiementtranche, PDO::PARAM_INT);
	$stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
	$stmt->bindParam(':dateecheance', $dateecheance, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getidPaiementTypeClasse($idtypepaiement,$idclasse,$idanneescolaire,$pdo)
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
			paiementtypeclasse.ideleveanneescolaire is null');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpaiementtype', $idtypepaiement, PDO::PARAM_INT);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function UpdatePaiementTypeClasse($id,$idanneescolaire,$idtypepaiement,$idclasse,$montant,$pdo)
{   
	$requete="UPDATE paiementtypeclasse SET idpaiementtype=:idpaiementtype,idclasse=:idclasse,montant=:montant,idanneescolaire=:idanneescolaire WHERE id=:id";

	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':idpaiementtype', $idtypepaiement, PDO::PARAM_INT);
	$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt -> bindParam(':montant', $montant, PDO::PARAM_INT);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	$success="Enregistrement r&eacute;ussie avec succ&egrave;s";
	$error="";
		
	return $success.'*'.$error;
}

function UpdatePaiementTypeClasseTranche($idpaiementtypeclasse,$idpaiementtranche,$dateecheance,$montant,$pdo)
{   
	$requete="UPDATE paiementtypeclassetranche SET montant=:montant,dateecheance=:dateecheance WHERE idpaiementtranche=:idpaiementtranche AND idpaiementtypeclasse=:idpaiementtypeclasse";

	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':montant', $montant, PDO::PARAM_STR);
	$stmt -> bindParam(':dateecheance', $dateecheance, PDO::PARAM_STR);
	$stmt -> bindParam(':idpaiementtranche', $idpaiementtranche, PDO::PARAM_INT);
	$stmt -> bindParam(':idpaiementtypeclasse', $idpaiementtypeclasse, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function DeletePaiementTypeClasse($id,$pdo)
{   
	$requete="DELETE FROM paiementtypeclasse WHERE id=:id";
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	
	$requete="DELETE FROM paiementtypeclassetranche WHERE idpaiementtypeclasse=:id";
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function DesactivePaiementTypeClasse($id,$pdo)
{   
	$requete="UPDATE paiementtypeclasse SET statut=0 WHERE id=:id";

	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	$success="D&eacute;sactivation r&eacute;ussie avec succ&egrave;s";
	$error="";
		
	return $success.'*'.$error;
}

function ActivePaiementTypeClasse($id,$pdo)
{   
	$requete="UPDATE paiementtypeclasse SET statut=1 WHERE id=:id";

	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	$success="R&eacute;activation r&eacute;ussie avec succ&egrave;s";
	$error="";
		
	return $success.'*'.$error;
}

function getidEntreeSortie($idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$pdo)
{
	$req=(' SELECT entreesortie.id as id
			FROM entreesortie
			WHERE 
			entreesortie.idanneescolaire=:idanneescolaire
			AND
			entreesortie.montant=:montant
			AND
			entreesortie.dateoperation=:dateoperation
			AND
			entreesortie.libelle=:libelle
			AND
			entreesortie.comptemouvement=:comptemouvement
			AND
			entreesortie.idtypeentreesortie=:idtypeentreesortie');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idtypeentreesortie', $TypeOperation, PDO::PARAM_INT);
	$stmt->bindParam(':comptemouvement', $idCompte, PDO::PARAM_STR);
	$stmt->bindParam(':libelle', $LibelleOperation, PDO::PARAM_STR);
	$stmt->bindParam(':dateoperation', $DateOperation, PDO::PARAM_STR);
	$stmt->bindParam(':montant', $Montant, PDO::PARAM_STR);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function AjouterEntreeSortie($idsoustypeoperation,$idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$idUser,$idUser2,$Fichier,$pdo)
{
    $id = getidEntreeSortie($idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO entreesortie(idanneescolaire,montant,libelle,statut,iduserajout,iduserauto,dateoperation,comptemouvement,idtypeentreesortie,ficheattache,datesaisie,idsoustypeentreesortie) 
					VALUES(:idanneescolaire,:montant,:libelle,1,:iduserajout,:iduserauto,:dateoperation,:comptemouvement,:idtypeentreesortie,:ficheattache,sysdate(),:idsoustypeoperation)";
		
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':montant', $Montant, PDO::PARAM_STR);
		$stmt->bindParam(':libelle', $LibelleOperation, PDO::PARAM_STR);
		$stmt->bindParam(':iduserajout', $idUser2, PDO::PARAM_INT);
		$stmt->bindParam(':iduserauto', $idUser, PDO::PARAM_INT);
		$stmt->bindParam(':dateoperation', $DateOperation, PDO::PARAM_STR);
		$stmt->bindParam(':comptemouvement', $idCompte, PDO::PARAM_INT);
		$stmt->bindParam(':idtypeentreesortie', $TypeOperation, PDO::PARAM_INT);
		$stmt->bindParam(':ficheattache', $Fichier, PDO::PARAM_STR);
		$stmt->bindParam(':idsoustypeoperation', $idsoustypeoperation, PDO::PARAM_INT);
		$stmt->execute();		
		$stmt->closeCursor();
		$stmt=NULL;
		
		$success="Enregistrement effectu&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Cette op&eacute;ration existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function UpdateEntreeSortie($idEntreeSortie,$idsoustypeoperation,$idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$idUser,$idUser2,$Fichier,$pdo)
{
	$requete="	UPDATE 
				entreesortie
				SET 
				idanneescolaire=:idanneescolaire,
				montant=:montant,
				libelle=:libelle,
				iduserauto=:iduserauto,
				iduserajout=:iduserajout,
				dateoperation=:dateoperation,
				comptemouvement=:comptemouvement,
				idtypeentreesortie=:idtypeentreesortie,
				ficheattache=:ficheattache,
				idsoustypeentreesortie=:idsoustypeoperation
				
				WHERE
				entreesortie.id=:identreesortie";
	
	$stmt = $pdo->prepare($requete);
	$stmt ->bindParam(':identreesortie', $idEntreeSortie, PDO::PARAM_STR);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt->bindParam(':montant', $Montant, PDO::PARAM_STR);
	$stmt->bindParam(':libelle', $LibelleOperation, PDO::PARAM_STR);
	$stmt->bindParam(':iduserajout', $idUser2, PDO::PARAM_STR);
	$stmt->bindParam(':iduserauto', $idUser, PDO::PARAM_STR);
	$stmt->bindParam(':dateoperation', $DateOperation, PDO::PARAM_STR);
	$stmt->bindParam(':comptemouvement', $idCompte, PDO::PARAM_STR);
	$stmt->bindParam(':idtypeentreesortie', $TypeOperation, PDO::PARAM_STR);
	$stmt->bindParam(':ficheattache', $Fichier, PDO::PARAM_STR);
	$stmt->bindParam(':idsoustypeoperation', $idsoustypeoperation, PDO::PARAM_STR);
	$stmt->execute();		
	$stmt->closeCursor();
	$stmt=NULL;
}

function DeleteEntreeSortie($idEntreeSortie,$pdo)
{
	$req=' DELETE FROM entreesortie WHERE entreesortie.id=:identreesortie';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':identreesortie',$idEntreeSortie,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getSousTypeOperation($TypeOperation,$pdo)
{
	$req=(' SELECT soustypeoperation.id,soustypeoperation.libelle FROM soustypeoperation WHERE idtype=:idtype');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idtype',$TypeOperation,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" required="required" name="idSousTypePaiement" >
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

function getSousTypeOperationSelected($TypeOperation,$idSousTypeOperation,$pdo)
{
	$req=(' SELECT soustypeoperation.id,soustypeoperation.libelle FROM soustypeoperation WHERE idtype=:idtype');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idtype',$TypeOperation,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" required="required" name="idSousTypePaiement" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			if($id==$idSousTypeOperation)
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

function getTypePaiementSelected($TypePaiement,$pdo)
{
	$req=(' SELECT  paiementtype.id,paiementtype.libelle FROM paiementtype');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12 form-control-grand" name="idTypePaiement" style="font-size:12px;border: 1px dotted #A2C600;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			if($id==$TypePaiement)
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

function getListTypePaiement($pdo)
{
	$req=(' SELECT  paiementtype.id,paiementtype.libelle FROM paiementtype');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12 form-control-grand" name="idTypePaiement" style="font-size:12px;border: 1px dotted #A2C600;">
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

function getEleveStatutEtablissementSelected_2($idEleveStatutEtablissement,$pdo)
{
	$req=(' SELECT elevestatutetablissement.id,elevestatutetablissement.libelle FROM elevestatutetablissement');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutEtablissement">
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

function getEleveStatutClasseSelected_2($idEleveStatutClasse,$pdo)
{
	$req=(' SELECT elevestatutclasse.id,elevestatutclasse.libelle FROM elevestatutclasse');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutClasse">
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

function getEleveStatutEtablissement_2($pdo)
{
	$req=(' SELECT elevestatutetablissement.id,elevestatutetablissement.libelle FROM elevestatutetablissement');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutEtablissement">
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

function getEleveStatutClasse_2($pdo)
{
	$req=(' SELECT elevestatutclasse.id,elevestatutclasse.libelle FROM elevestatutclasse');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutClasse">
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

function getAllSalle_3($pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle

			ORDER BY salle.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle">
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

//
function getAllSalleSelected_3($idsalle,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle

			ORDER BY salle.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			
			if($id==$idsalle)
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

function getEleveStatutClasse($pdo)
{
	$req=(' SELECT elevestatutclasse.id,elevestatutclasse.libelle FROM elevestatutclasse');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutClasse" required="required">
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
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutEtablissement" required="required">
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

function getIdEleveStatutClasse($etat,$pdo)
{
	$req=(' SELECT elevestatutclasse.id
			FROM elevestatutclasse
			WHERE 
			elevestatutclasse.libelle=:libelle');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':libelle', $etat, PDO::PARAM_STR);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['id'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getIdEleveStatutEtablissement($etat,$pdo)
{
	$req=(' SELECT elevestatutetablissement.id
			FROM elevestatutetablissement
			WHERE 
			elevestatutetablissement.libelle=:libelle');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':libelle', $etat, PDO::PARAM_STR);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['id'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getEleveStatutClasseSelected($idEleveStatutClasse,$pdo)
{
	$req=(' SELECT elevestatutclasse.id,elevestatutclasse.libelle FROM elevestatutclasse');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutClasse" required="required">
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
	<Select class="form-control col-md-2 col-xs-12" name="idEleveStatutEtablissement" required="required">
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

//
// FUNCTION ACCUEIL 
//
function getNbreInscritTotal($idanneescolaire,$pdo)
{
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreInscrit
			FROM eleveanneescolaire,eleve,elevesalle,salle
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id

			GROUP BY eleveanneescolaire.idanneescolaire');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreInscrit'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreInscritParSexe($idanneescolaire,$statut,$sexeeleve,$pdo)
{
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreInscrit
			FROM eleveanneescolaire,eleve,elevesalle,salle
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleve.sexe_eleve=:sexeeleve
			AND
			eleveanneescolaire.inscrit!=0
			AND
			eleveanneescolaire.etat!=0
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id

			GROUP BY eleveanneescolaire.idanneescolaire');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt_->bindParam(':sexeeleve', $sexeeleve, PDO::PARAM_STR);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreInscrit'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreEleveAbandonne($idanneescolaire,$statut,$pdo)
{
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreInscrit
			FROM eleveanneescolaire,elevesalle,eleve,salle
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.inscrit!=0
			AND
			eleveanneescolaire.etat!=0
			AND
			eleveanneescolaire.statut=1
			AND
			elevesalle.statut=:statut
			AND
			elevesalle.idsalle=salle.id
			
			GROUP BY eleveanneescolaire.idanneescolaire');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt_->bindParam(':statut', $statut, PDO::PARAM_INT);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreInscrit'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreMoyenneNiveauParSexe($idanneescolaire,$idposition,$idclasse,$idmatiere,$moyenne,$sexe,$pdo)
{
	//
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreInscrit
			
			FROM eleveanneescolaire,eleve,elevesalle,salle,note,classe
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleve.sexe_eleve=:sexeeleve
			AND
			eleveanneescolaire.inscrit!=0
			AND
			eleveanneescolaire.etat!=0
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.idclasse=:idclasse
			AND
			elevesalle.id=note.ideleve
			AND
			note.moyentrimes>=:moyenne
			AND
			note.idmatiere=:idmatiere
			AND
			note.idposition=:idposition

			GROUP BY eleveanneescolaire.idanneescolaire');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt_->bindParam(':sexeeleve', $sexe, PDO::PARAM_STR);
	$stmt_->bindParam(':idclasse', $idclasse, PDO::PARAM_STR);
	$stmt_->bindParam(':moyenne', $moyenne, PDO::PARAM_STR);
	$stmt_->bindParam(':idmatiere', $idmatiere, PDO::PARAM_STR);
	$stmt_->bindParam(':idposition', $idposition, PDO::PARAM_STR);
	
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreInscrit'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreEleveParNiveau($idanneescolaire,$statut,$sexeeleve,$idclasse,$pdo)
{
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreInscrit
			FROM eleveanneescolaire,eleve,elevesalle,salle
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleve.sexe_eleve=:sexeeleve
			AND
			eleveanneescolaire.inscrit!=0
			AND
			eleveanneescolaire.etat!=0
			AND
			eleveanneescolaire.statut=:statut
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.idclasse=:idclasse
			
			GROUP BY eleveanneescolaire.idanneescolaire');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt_->bindParam(':sexeeleve', $sexeeleve, PDO::PARAM_STR);
	$stmt_->bindParam(':idclasse', $idclasse, PDO::PARAM_STR);
	$stmt_->bindParam(':statut', $statut, PDO::PARAM_STR);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreInscrit'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getEtatMatiere()
{	
    ?>	
	<select class="form-control" name="etat" required="required" >
	<option></option>
	<option value="1">Obligatoire</option>
	<option value="2">Facultative</option><?php		
	?>
	</select><?php
}

function getEtatMatiereSelected($etat)
{	
    ?>	
	<select class="form-control" name="etat" required="required" >
	<option></option><?php
		if($etat=="1")
		{
			?>
			<option selected="selected" value="1">Obligatoire</option>
			<option value="2">Facultative</option><?php
		}
		else
		{
			?>
			<option selected="selected" value="2">Facultative</option>
			<option value="1">Obligatoire</option><?php
		}		
	?>
	</select><?php
}

function Gracia($pdo)
{
	$req=(' SELECT  note.noteint,
					note.noteds,
					note.notedn,
					note.notecomp,
					note.moyclass,
					note.observation,
					note.idprofesseur,
					note.moyentrimes,
					note.moyenpondere,
					note.coef,
					note.idmatiere,
					note.id
					
			FROM note
			WHERE
			note.moyentrimes=""');
	
	$stmt = $pdo->prepare($req);
	$stmt->execute();				
	while($donnees = $stmt->fetch())
	{
		$int = trim($donnees['noteint']);
		$ds = trim($donnees['noteds']);
		$dn = trim($donnees['notedn']);
		$comp = trim($donnees['notecomp']);
		$coef = trim($donnees['coef']);
		$id = trim($donnees['id']);
		
		$moylclasse="";
		if($int!="" AND $ds!="" AND $dn!="")
		{
			$moylclasse = round(($int+$ds+$dn)/3,2);
		}
		elseif($int!="" AND $ds!="" AND $dn=="")
		{
			$moylclasse = round(($int+$ds)/2,2);		
		}	
		elseif($int!="" AND $ds=="" AND $dn=="")
		{
			$moylclasse = round(($int),2);
		}
		elseif($int=="" AND $ds!="" AND $dn=="")
		{
			$moylclasse = round(($ds),2);
		}	
		elseif($int=="" AND $ds=="" AND $dn!="")
		{
			$moylclasse = round(($dn),2);
		}
		elseif($int!="" AND $ds=="" AND $dn!="")
		{
			$moylclasse = round(($int+$dn)/2,2);
		}
		elseif($int=="" AND $ds!="" AND $dn!="")
		{
			$moylclasse = round(($ds+$dn)/2,2);
		}
	
		$moyltrimestre="";
		$moyenne_trimestre_1="";
		$moylclasse=trim($moylclasse); 
		//Calcul de la moyenne trimestre
		if($comp!="" AND $moylclasse!="")
		{
			$moyltrimestre = trim(round(($moylclasse+$comp)/2,2));
			$moyenne_trimestre_1 = trim(intval(round(($moylclasse+$comp)/2)));
		}
		elseif($comp=="" AND $moylclasse!="")
		{
			$moyltrimestre=trim(round(($moylclasse),2));
			$moyenne_trimestre_1 = trim(intval(round(($moylclasse)/1)));
		}	
		elseif($comp!="" AND $moylclasse=="")
		{
			$moyltrimestre=trim(round(($comp),2));
			$moyenne_trimestre_1 = trim(intval(round(($comp)/1)));
		}
		
		$moyenpondere="";
		if($moyltrimestre!="")
		{
			$moyenpondere = trim($moyltrimestre*$coef);
		}

		if($moyenne_trimestre_1=="4")
		{
			$appre = "T.Faible";						
		}
		
		if($moyenne_trimestre_1=="5" OR $moyenne_trimestre_1=="6")
		{
			$appre = "Faible";						
		}		
		
		if($moyenne_trimestre_1=="7")
		{
			$appre = "T.insuffisant";						
		}	
		
		if($moyenne_trimestre_1=="8" OR $moyenne_trimestre_1=="9")
		{
			$appre = "Insuffisant";						
		}				
		
		if($moyenne_trimestre_1=="10" OR $moyenne_trimestre_1=="11")
		{
			$appre = "Passable";						
		}			
		
		if($moyenne_trimestre_1=="12" OR $moyenne_trimestre_1=="13")
		{
			$appre = "Assez-Bien";						
		}
		
		if($moyenne_trimestre_1=="14" OR $moyenne_trimestre_1=="15")
		{
			$appre = "Bien";						
		}	
		
		if($moyenne_trimestre_1=="16" OR $moyenne_trimestre_1=="17")
		{
			$appre = "Tres Bien";						
		}		
		
		if($moyenne_trimestre_1=="18" OR $moyenne_trimestre_1=="19" OR $moyenne_trimestre_1=="20")
		{
			$appre = "Excellent";						
		}
		
		if($moyenne_trimestre_1=="3")
		{
			$appre = "T.Faible";						
		}	

		if($moyenne_trimestre_1=="1")
		{
			$appre = "T.Faible";						
		}

		if($moyenne_trimestre_1=="2")
		{
			$appre = "T.Faible";						
		}	

		if($moyenne_trimestre_1=="0")
		{
			$appre = "T.Faible";						
		}

		if($moyenne_trimestre_1=="")
		{
			$appre = "";						
		}
	
		$req="	UPDATE note SET 
		                    noteint=:noteint,
                            noteds=:noteds,
                            notedn=:notedn,
                            moyclass=:moyclass,
                            notecomp=:notecomp,
                            moyentrimes=:moyentrimes,
                            coef=:coef,
                            moyenpondere=:moyenpondere,
							observation=:observation
							
                	WHERE
                    note.id=:id";
					
		$stmt_1 = $pdo->prepare($req);
		$stmt_1->bindParam(':noteint', $int, PDO::PARAM_INT);
		$stmt_1->bindParam(':noteds', $ds, PDO::PARAM_INT);
		$stmt_1->bindParam(':notedn', $dn, PDO::PARAM_INT);
		$stmt_1->bindParam(':notecomp', $comp, PDO::PARAM_INT);
		$stmt_1->bindParam(':moyclass', $moylclasse, PDO::PARAM_INT);
		$stmt_1->bindParam(':moyentrimes', $moyltrimestre, PDO::PARAM_INT);
		$stmt_1->bindParam(':coef', $coef, PDO::PARAM_INT);
		$stmt_1->bindParam(':moyenpondere', $moyenpondere, PDO::PARAM_INT);
		$stmt_1->bindParam(':observation', $appre, PDO::PARAM_INT);
		$stmt_1->bindParam(':id', $id, PDO::PARAM_INT);
		
		$stmt_1 ->execute();		
		$stmt_1 ->closeCursor();
		$stmt_1 =NULL;
	}		
}

function JeanPaul($pdo)
{
	$req=(' SELECT eleveanneescolaire.id as ideleveeleveanneescolaire FROM eleveanneescolaire');
	$stmt = $pdo->prepare($req);
    $stmt->execute();
	while($donnees = $stmt->fetch())
	{
		$ideleveeleveanneescolaire = $donnees['ideleveeleveanneescolaire'];
		$nbre=Sylvie($ideleveeleveanneescolaire,$pdo);
		if($nbre==2)
		{
			$ideleve=Hermann($ideleveeleveanneescolaire,$pdo);
			Achille($ideleve,$pdo);
		}
	}
	return $nbre; 
}

function Sylvie($ideleve,$pdo)
{
	$req=(' SELECT count(*) as nbre
            FROM elevesalle
            WHERE
            elevesalle.ideleve=:ideleve');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
    $stmt->execute();
	if($donnees = $stmt->fetch())
	{
		$nbre = $donnees['nbre'];
	}
	return $nbre; 
}

function Hermann($ideleve,$pdo)
{
	$req=(' SELECT id
            FROM elevesalle
            WHERE
            elevesalle.ideleve=:ideleve
			
			ORDER BY elevesalle.id DESC limit 0,1
		');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
    $stmt->execute();
	if($donnees = $stmt->fetch())
	{
		$id = $donnees['id'];
	}
	return $id; 
}

function Achille($idelevesalle,$pdo)
{
	$req=(' DELETE FROM elevesalle
            WHERE
            elevesalle.id=:idelevesalle
		');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
    $stmt->execute(); 
}

function getAllAnneeScolaire($pdo)
{
	$req=(' SELECT  anneescolaire.id,
	                anneescolaire.libelle
					
			FROM anneescolaire
			
			ORDER BY anneescolaire.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="idanneescolaire">
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
	<select class="form-control col-md-2 col-xs-12" required="required" name="idanneescolaire">
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
	<select class="form-control col-md-2 col-xs-12" name="idposition" >
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
	<select class="form-control col-md-2 col-xs-12" name="idposition" >
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

//
function getAllSalle($pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle" required="required" >
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

//
function getAllSalleSelected($idsalle,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			
			if($id==$idsalle)
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

//
function getAllDomaine($pdo)
{
	$req=(' SELECT distinct domaine.id,domaine.nom FROM domaine WHERE domaine.statut=1');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="iddomaine" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$nom = $donnees['nom'];
			$id = $donnees['id'];
			
			echo '<option value="'.$id.'">'.$nom.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function getAllDomaineSelected($domaineselected,$pdo)
{
	$req=(' SELECT distinct domaine.id,domaine.nom FROM domaine WHERE domaine.statut=1');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="iddomaine" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$nom = $donnees['nom'];
			$id = $donnees['id'];
			
			if($id==$domaineselected)
			{
				echo '<option value="'.$id.'" selected="selected">'.$nom.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$nom.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function getAllSalleProf($idprof,$idanneescolaire,$statutanneescolaire,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT 	distinct
						salle.id as id,
	                	salle.codesalle as codesalle,
						salle.nomsalle as nomsalle
					
				FROM salle,professeursallemat
				WHERE
				professeursallemat.statut=1
				AND
				professeursallemat.idsalle=salle.id
				AND
				professeursallemat.idprof=:idprof');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT  distinct
						salle.id as id,
	                	salle.codesalle as codesalle,
						salle.nomsalle as nomsalle
					
				FROM salle,professeursallemat
				WHERE
				professeursallemat.idanneescolaire=:idanneescolaire
				AND
				professeursallemat.idsalle=salle.id
				AND
				professeursallemat.idprof=:idprof');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	}
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle">
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

function getAllSalleProfSelect($idprof,$idsalle,$idanneescolaire,$statutanneescolaire,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT 	distinct
						salle.id as id,
	                	salle.codesalle as codesalle,
						salle.nomsalle as nomsalle
					
				FROM salle,professeursallemat
				WHERE
				professeursallemat.statut=1
				AND
				professeursallemat.idsalle=salle.id
				AND
				professeursallemat.idprof=:idprof');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT  distinct
						salle.id as id,
	                	salle.codesalle as codesalle,
						salle.nomsalle as nomsalle
					
				FROM salle,professeursallemat
				WHERE
				professeursallemat.idanneescolaire=:idanneescolaire
				AND
				professeursallemat.idsalle=salle.id
				AND
				professeursallemat.idprof=:idprof');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	}
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nomsalle = $donnees['nomsalle'];
			$codesalle = $donnees['codesalle'];
			
			if($id==$idsalle)
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

function getAllClasse($pdo)
{
	$req=(' SELECT  classe.idclasse,
	                classe.codeclasse

			FROM classe
			ORDER BY classe.idclasse desc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12 form-control-grand" name="idclasse" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];

			echo '<option value="'.$id.'">'.$codeclasse.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllClasseSelected($idclasse,$pdo)
{
	$req=(' SELECT  classe.idclasse,
	                classe.codeclasse

			FROM classe
			ORDER BY classe.idclasse desc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12 form-control-grand" name="idclasse">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			if($id==$idclasse)
			{
				echo '<option value="'.$id.'" selected="selected">'.$codeclasse.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$codeclasse.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllTitre($pdo)
{
	$req=(' SELECT  professeurtitre.id,
	                professeurtitre.nom

			FROM professeurtitre');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idtitre" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			echo '<option value="'.$id.'">'.$nom.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllTitreSelect($idprof,$pdo)
{
	$req=(' SELECT  professeurtitre.id,
	                professeurtitre.nom

			FROM professeurtitre');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idtitre" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			if($id==$idprof)
			{
				echo '<option value="'.$id.'" selected="selected">'.$nom.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'">'.$nom.'</option>';	
			}				
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllSalle_($idclasse,$ligne,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle
			WHERE
			salle.idclasse=:idclasse');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="idsalle<?php echo $ligne;?>" >
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

function getAllSalle_2($ligne,$pdo)
{
	$req=(' SELECT  salle.id,
	                salle.codesalle as codesalle,
					salle.nomsalle as nomsalle
					
			FROM salle
			WHERE
			salle.idclasse=:idclasse');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idsalle<?php echo $ligne;?>" >
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

//
function getAllMatiere($idsalle,$idanneescolaire,$idposition,$statutanneescolaire,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT  distinct matiere.id_matiere,
		                matiere.code_matiere,
						matiere.nom_matiere,
						matierecoefficient.coefficient,
						matierecoefficient.etat

				FROM matiere,matierecoefficient,salle,professeursallemat,professeur
				WHERE
				matiere.id_matiere=matierecoefficient.idmatiere
				AND
				matierecoefficient.idclasse=salle.idclasse
				AND
				matierecoefficient.statut=1
				AND
				salle.id=:idsalle
				AND
				matiere.id_matiere=professeursallemat.idmat
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idprof=professeur.id
				AND
				matierecoefficient.coefficient<>0');
	}
	else
	{
		$req=(' SELECT  distinct matiere.id_matiere,
		                matiere.code_matiere,
						matiere.nom_matiere,
						matierecoefficient.coefficient,
						matierecoefficient.etat

				FROM matiere,matierecoefficient,salle,professeursallemat,professeur
				WHERE
				matiere.id_matiere=matierecoefficient.idmatiere
				AND
				matierecoefficient.idclasse=salle.idclasse
				AND
				matierecoefficient.idanneescolaire=:idanneescolaire
				AND
				salle.id=:idsalle
				AND
				matiere.id_matiere=professeursallemat.idmat
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idprof=professeur.id
				AND
				matierecoefficient.coefficient<>0');
	}
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idmatiere" id="idmatiere" onchange="makeRequest('Resultat.php','idmatiere','resultat')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];
			$coefficient = $donnees['coefficient'];
			$etat = $donnees['etat'];
			
			echo '<option value="'.$id_matiere.'*'.$idsalle.'*'.$idanneescolaire.'*'.$coefficient.'*'.$idposition.'*'.$etat.'">'.$code_matiere.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function getAllMatiereProf($idsalle,$idanneescolaire,$statutanneescolaire,$idposition,$idprof,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT  distinct 
						matiere.id_matiere,
		                matiere.code_matiere,
						matiere.nom_matiere,
						matierecoefficient.coefficient,
						matierecoefficient.etat

				FROM matiere,matierecoefficient,salle,professeursallemat,professeur
				WHERE
				matiere.id_matiere=matierecoefficient.idmatiere
				AND
				matierecoefficient.idclasse=salle.idclasse
				AND
				matierecoefficient.statut=1
				AND
				salle.id=:idsalle
				AND
				matiere.id_matiere=professeursallemat.idmat
				AND
				professeursallemat.statut=1
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idprof=professeur.id
				AND
				matierecoefficient.coefficient<>0
				AND
				professeur.id=:idprof');

		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idprof',$idprof,PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT  distinct matiere.id_matiere,
		                matiere.code_matiere,
						matiere.nom_matiere,
						matierecoefficient.coefficient,
						matierecoefficient.etat

				FROM matiere,matierecoefficient,salle,professeursallemat,professeur
				WHERE
				matiere.id_matiere=matierecoefficient.idmatiere
				AND
				matierecoefficient.idclasse=salle.idclasse
				AND
				matierecoefficient.idanneescolaire=:idanneescolaire
				AND
				salle.id=:idsalle
				AND
				matiere.id_matiere=professeursallemat.idmat
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idprof=professeur.id
				AND
				professeursallemat.idanneescolaire=:idanneescolaire
				AND
				matierecoefficient.coefficient<>0
				AND
				professeur.id=:idprof');

		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idprof',$idprof,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	}		
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idmatiere" id="idmatiere" onchange="makeRequest('Resultat.php','idmatiere','resultat')" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];
			$coefficient = $donnees['coefficient'];
			$etat = $donnees['etat'];
			
			echo '<option value="'.$id_matiere.'*'.$idsalle.'*'.$idanneescolaire.'*'.$coefficient.'*'.$idposition.'*'.$etat.'">'.$code_matiere.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function getAllMatiere_($ligne,$pdo)
{
	$req=(' SELECT  matiere.id_matiere,
	                matiere.code_matiere,
					matiere.nom_matiere
					
			FROM matiere');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idmatiere<?php echo $ligne;?>" id="idmatiere<?php echo $ligne;?>" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];

			echo '<option value="'.$id_matiere.'">'.$code_matiere.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllMatiereSelected_($idmatiere,$ligne,$pdo)
{
	$req=(' SELECT  matiere.id_matiere,
	                matiere.code_matiere,
					matiere.nom_matiere
					
			FROM matiere');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idmatiere<?php echo $ligne;?>" id="idmatiere<?php echo $ligne;?>" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];

			if($id_matiere==$idmatiere)
			{
				echo '<option value="'.$id_matiere.'" selected="selected">'.$code_matiere.'</option>';
			}
			else
			{
				echo '<option value="'.$id_matiere.'">'.$code_matiere.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function getAllMatiereSelected($idsalle,$idanneescolaire,$idposition,$idmatiere,$pdo)
{
	$req=(' SELECT  matiere.id_matiere,
	                matiere.code_matiere,
					matiere.nom_matiere,
					matierecoefficient.coefficient

			FROM matiere,matierecoefficient,salle
			WHERE
			matiere.id_matiere=matierecoefficient.idmatiere
			AND
			matierecoefficient.idclasse=salle.idclasse
			AND
			salle.id="'.$idsalle.'"
			AND
			matierecoefficient.coefficient<>0');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idmatiere" id="idmatiere" onchange="makeRequest('Resultat.php','idmatiere','resultat')" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_matiere = $donnees['id_matiere'];
			$code_matiere = $donnees['code_matiere'];
			$nom_matiere = $donnees['nom_matiere'];
			$coefficient = $donnees['coefficient'];
			
			if($id_matiere==$idmatiere)
			{
				echo '<option value="'.$id_matiere.'-'.$idsalle.'-'.$idanneescolaire.'-'.$coefficient.'-'.$idposition.'" selected="selected">'.$code_matiere.'</option>';
            }
            else
			{
				echo '<option value="'.$id_matiere.'-'.$idsalle.'-'.$idanneescolaire.'-'.$coefficient.'-'.$idposition.'">'.$code_matiere.'</option>';
            }				
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function CreateNote($idelevesalle,$int,$ds,$dn,$comp,$idposition,$idsalle,$idanneescolaire,$idprofesseur,$idmatiere,$coef,$etat,$pdo)
{
	$moylclasse="";
	if($int!="" AND $ds!="" AND $dn!="")
    {
        $moylclasse = round(($int+$ds+$dn)/3,2);
    }
	elseif($int!="" AND $ds!="" AND $dn=="")
    {
        $moylclasse = round(($int+$ds)/2,2);		
    }	
	elseif($int!="" AND $ds=="" AND $dn=="")
    {
        $moylclasse = round(($int),2);
    }
	elseif($int=="" AND $ds!="" AND $dn=="")
    {
        $moylclasse = round(($ds),2);
    }	
	elseif($int=="" AND $ds=="" AND $dn!="")
    {
        $moylclasse = round(($dn),2);
    }
	elseif($int!="" AND $ds=="" AND $dn!="")
    {
        $moylclasse = round(($int+$dn)/2,2);
    }
	elseif($int=="" AND $ds!="" AND $dn!="")
    {
        $moylclasse = round(($ds+$dn)/2,2);
    }
	
	$moyltrimestre="";
	$moyenne_trimestre_1="";
	$moylclasse=trim($moylclasse);
	if($comp!="" AND $moylclasse!="")
	{
		$moyltrimestre = trim(round(($moylclasse+$comp)/2,2));
		$moyenne_trimestre_1 = trim(intval((($moylclasse+$comp)/2)));
	}
	elseif($comp=="" AND $moylclasse!="")
	{
		$moyltrimestre=trim(round(($moylclasse),2));
		$moyenne_trimestre_1 = trim(intval((($moylclasse)/1)));
	}
	elseif($comp!="" AND $moylclasse=="")
	{
		$moyltrimestre=trim(round(($comp),2));
		$moyenne_trimestre_1 = trim(intval((($comp)/1)));
	}
	
	$moyenpondere="";
	if($moyltrimestre!="")
	{
		if($etat==1)
		{
			$moyenpondere = trim($moyltrimestre*$coef);
		}
		else
		{
			$moyenpondere = trim($moyltrimestre-10);
			$coef="";
		}
	}

	if($moyenne_trimestre_1=="4")
	{
		$appre = "T.Faible";						
	}
	
	if($moyenne_trimestre_1=="5" OR $moyenne_trimestre_1=="6")
	{
		$appre = "Faible";						
	}		
	
	if($moyenne_trimestre_1=="7")
	{
		$appre = "T.insuffisant";						
	}	
	
	if($moyenne_trimestre_1=="8" OR $moyenne_trimestre_1=="9")
	{
		$appre = "Insuffisant";						
	}				
	
	if($moyenne_trimestre_1=="10" OR $moyenne_trimestre_1=="11")
	{
		$appre = "Passable";						
	}			
	
	if($moyenne_trimestre_1=="12" OR $moyenne_trimestre_1=="13")
	{
		$appre = "Assez-Bien";						
	}
	
	if($moyenne_trimestre_1=="14" OR $moyenne_trimestre_1=="15")
	{
		$appre = "Bien";						
	}	
	
	if($moyenne_trimestre_1=="16" OR $moyenne_trimestre_1=="17")
	{
		$appre = "Tres Bien";						
	}		
	
	if($moyenne_trimestre_1=="18" OR $moyenne_trimestre_1=="19" OR $moyenne_trimestre_1=="20")
	{
		$appre = "Excellent";						
	}
	
	if($moyenne_trimestre_1=="3")
	{
		$appre = "T.Faible";						
	}	

	if($moyenne_trimestre_1=="1")
	{
		$appre = "T.Faible";						
	}

	if($moyenne_trimestre_1=="2")
	{
		$appre = "T.Faible";						
	}	

	if($moyenne_trimestre_1=="0")
	{
		$appre = "T.Faible";						
	}

	if($moyenne_trimestre_1=="")
	{
		$appre = "";						
	}
	
	$estPublie = 1;
	$requete="INSERT INTO note(noteint,noteds,notedn,moyclass,notecomp,moyentrimes,coef,moyenpondere,rang,idprofesseur,observation,ideleve,idmatiere,idsalle,idposition,idanneescolaire,est_publie) 
				VALUES(:noteint,:noteds,:notedn,:moyclass,:notecomp,:moyentrimes,:coef,:moyenpondere,null,:idprofesseur,:observation,:ideleve,:idmatiere,:idsalle,:idposition,:idanneescolaire,:est_publie)";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':noteint', $int, PDO::PARAM_STR);
	$stmt->bindParam(':noteds', $ds, PDO::PARAM_STR);
	$stmt->bindParam(':notedn', $dn, PDO::PARAM_STR);
	$stmt->bindParam(':notecomp', $comp, PDO::PARAM_STR);
	$stmt->bindParam(':moyclass', $moylclasse, PDO::PARAM_STR);
	$stmt->bindParam(':moyentrimes', $moyltrimestre, PDO::PARAM_STR);
	$stmt->bindParam(':coef', $coef, PDO::PARAM_STR);
	$stmt->bindParam(':moyenpondere', $moyenpondere, PDO::PARAM_STR);
	$stmt->bindParam(':observation', $appre, PDO::PARAM_STR);
	$stmt->bindParam(':ideleve', $idelevesalle, PDO::PARAM_STR);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_STR);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_STR);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_STR);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt->bindParam(':idprofesseur', $idprofesseur, PDO::PARAM_STR);
	$stmt->bindParam(':est_publie', $estPublie, PDO::PARAM_INT);
	$stmt ->execute();		
	$stmt ->closeCursor();
	$stmt =NULL;
}

function CreateNoteCycleSup($idelevesalle,$int,$ds,$dn,$comp,$idposition,$idsalle,$idanneescolaire,$idprofesseur,$idmatiere,$coef,$etat,$pdo)
{
	$moylclasse="";
	if($int=="" AND $ds!="" AND $dn=="")
    {
		$moylclasse = round(((float)$ds*0.3),2);
    }	
	elseif($int=="" AND $ds=="" AND $dn!="")
    {
        $moylclasse = round(((float)$dn*0.3),2);
    }
	elseif($int=="" AND $ds!="" AND $dn!="")
    {
        $moylclasse = round((((float)$ds*0.3)+((float)$dn*0.3)),2);
    }
	
	$moyltrimestre="";
	$moyenne_trimestre_1="";
	$moylclasse=trim($moylclasse);
	if($comp!="" AND $moylclasse!="")
	{
		$moyltrimestre = trim(round(((float)$moylclasse+((float)$comp*0.4)),2));
		$moyenne_trimestre_1 = trim(intval(((float)$moylclasse+((float)$comp*0.4))));
	}
	elseif($comp=="" AND $moylclasse!="")
	{
		$moyltrimestre=trim(round(($moylclasse),2));
		$moyenne_trimestre_1 = trim(intval((((float)$moylclasse))));
	}
	elseif($comp!="" AND $moylclasse=="")
	{
		$moyltrimestre=trim(round(($comp),2));
		$moyenne_trimestre_1 = trim(intval((((float)$comp*0.4))));
	}
	
	$moyenpondere="";
	if($moyltrimestre!="")
	{
		if($etat==1)
		{
			$moyenpondere = trim($moyltrimestre*$coef);
		}
		else
		{
			$moyenpondere = trim($moyltrimestre-10);
			$coef="";
		}
	}

	if($moyenne_trimestre_1=="4")
	{
		$appre = "";						
	}
	
	if($moyenne_trimestre_1=="5" OR $moyenne_trimestre_1=="6")
	{
		$appre = "";						
	}		
	
	if($moyenne_trimestre_1=="7")
	{
		$appre = "";						
	}	
	
	if($moyenne_trimestre_1=="8" OR $moyenne_trimestre_1=="9")
	{
		$appre = "";						
	}				
	
	if($moyenne_trimestre_1=="10" OR $moyenne_trimestre_1=="11")
	{
		$appre = "Passable";						
	}			
	
	if($moyenne_trimestre_1=="12" OR $moyenne_trimestre_1=="13")
	{
		$appre = "Assez-Bien";						
	}
	
	if($moyenne_trimestre_1=="14" OR $moyenne_trimestre_1=="15")
	{
		$appre = "Bien";						
	}	
	
	if($moyenne_trimestre_1=="16" OR $moyenne_trimestre_1=="17")
	{
		$appre = "Très Bien";						
	}		
	
	if($moyenne_trimestre_1=="18" OR $moyenne_trimestre_1=="19" OR $moyenne_trimestre_1=="20")
	{
		$appre = "Excellent";						
	}
	
	if($moyenne_trimestre_1=="3")
	{
		$appre = "";						
	}	

	if($moyenne_trimestre_1=="1")
	{
		$appre = "";						
	}

	if($moyenne_trimestre_1=="2")
	{
		$appre = "";						
	}	

	if($moyenne_trimestre_1=="0")
	{
		$appre = "";						
	}

	if($moyenne_trimestre_1=="")
	{
		$appre = "";						
	}

	$estPublie = 1;
	$requete="INSERT INTO note(noteint,noteds,notedn,moyclass,notecomp,moyentrimes,coef,moyenpondere,rang,idprofesseur,observation,ideleve,idmatiere,idsalle,idposition,idanneescolaire,est_publie) 
				VALUES(:noteint,:noteds,:notedn,:moyclass,:notecomp,:moyentrimes,:coef,:moyenpondere,null,:idprofesseur,:observation,:ideleve,:idmatiere,:idsalle,:idposition,:idanneescolaire,:est_publie)";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':noteint', $int, PDO::PARAM_STR);
	$stmt->bindParam(':noteds', $ds, PDO::PARAM_STR);
	$stmt->bindParam(':notedn', $dn, PDO::PARAM_STR);
	$stmt->bindParam(':notecomp', $comp, PDO::PARAM_STR);
	$stmt->bindParam(':moyclass', $moylclasse, PDO::PARAM_STR);
	$stmt->bindParam(':moyentrimes', $moyltrimestre, PDO::PARAM_STR);
	$stmt->bindParam(':coef', $coef, PDO::PARAM_STR);
	$stmt->bindParam(':moyenpondere', $moyenpondere, PDO::PARAM_STR);
	$stmt->bindParam(':observation', $appre, PDO::PARAM_STR);
	$stmt->bindParam(':ideleve', $idelevesalle, PDO::PARAM_STR);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_STR);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_STR);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_STR);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt->bindParam(':idprofesseur', $idprofesseur, PDO::PARAM_STR);
	$stmt->bindParam(':est_publie', $estPublie, PDO::PARAM_INT);
	$stmt ->execute();		
	$stmt ->closeCursor();
	$stmt =NULL;
}

function CreateNotePrimaire($idelevesalle,$int,$ds,$dn,$comp,$idposition,$idsalle,$idanneescolaire,$idprofesseur,$idmatiere,$coef,$etat,$pdo)
{
	$moylclasse="";
	$moyltrimestre="";
	$moyenne_trimestre_1="";
	$appre="";
	$moyenpondere="";
	
	if($comp=="0" OR $comp=="2")
	{
		$appre = "Faible ";						
	}
	
	if($comp=="3")
	{
		$appre = "Très insuffisant ";						
	}		
	
	if($comp=="4")
	{
		$appre = "Insuffisant";						
	}				
	
	if($comp=="5")
	{
		$appre = "Passable";						
	}			
	
	if($comp=="6")
	{
		$appre = "Assez bien";						
	}
	
	if($comp=="7")
	{
		$appre = "Bien";						
	}	
	
	if($comp=="8")
	{
		$appre = "Très bien";						
	}		
	
	if($comp=="9")
	{
		$appre = "Excellent ";						
	}
	
	if($comp=="10")
	{
		$appre = "Excellent ";						
	}	

	if($comp=="")
	{
		$appre = "";						
	}
	
	$estPublie = 1;
	$requete="INSERT INTO note(noteint,noteds,notedn,moyclass,notecomp,moyentrimes,coef,moyenpondere,rang,idprofesseur,observation,ideleve,idmatiere,idsalle,idposition,idanneescolaire,est_publie) 
				VALUES(:noteint,:noteds,:notedn,:moyclass,:notecomp,:moyentrimes,:coef,:moyenpondere,null,:idprofesseur,:observation,:ideleve,:idmatiere,:idsalle,:idposition,:idanneescolaire,:est_publie)";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':noteint', $int, PDO::PARAM_STR);
	$stmt->bindParam(':noteds', $ds, PDO::PARAM_STR);
	$stmt->bindParam(':notedn', $dn, PDO::PARAM_STR);
	$stmt->bindParam(':notecomp', $comp, PDO::PARAM_STR);
	$stmt->bindParam(':moyclass', $moylclasse, PDO::PARAM_STR);
	$stmt->bindParam(':moyentrimes', $moyltrimestre, PDO::PARAM_STR);
	$stmt->bindParam(':coef', $coef, PDO::PARAM_STR);
	$stmt->bindParam(':moyenpondere', $moyenpondere, PDO::PARAM_STR);
	$stmt->bindParam(':observation', $appre, PDO::PARAM_STR);
	$stmt->bindParam(':ideleve', $idelevesalle, PDO::PARAM_STR);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_STR);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_STR);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_STR);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt->bindParam(':idprofesseur', $idprofesseur, PDO::PARAM_STR);
	$stmt->bindParam(':est_publie', $estPublie, PDO::PARAM_INT);
	$stmt ->execute();		
	$stmt ->closeCursor();
	$stmt =NULL;
}

function getidNoteEleve($ideleve,$idposition,$idanneescolaire,$idmatiere,$idsalle,$pdo)
{
	$req=(' SELECT note.id
			FROM note
			WHERE 
			note.ideleve=:ideleve
			AND
			note.idmatiere=:idmatiere
			AND
			note.idposition=:idposition
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve', $ideleve, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
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

function EviteDeleteCoefficientMatiere($idmatiere,$idclasse,$coefficient,$pdo)
{
	$req=(' SELECT note.id
			FROM note,salle,classe
			WHERE 
			note.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			classe.idclasse=:idclasse
			AND
			note.coef=:coef
			AND
			note.idmatiere=:idmatiere');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':coef', $coefficient, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
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

function ExisteProfesseurMatiere($idprof,$idmatiere,$idsalle,$pdo)
{
	$req=(' SELECT note.id
			FROM note
			WHERE 
			note.idprofesseur=:idprof
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
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

function DeleteNote($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' DELETE FROM note WHERE note.idsalle=:idsalle AND note.idposition=:idposition AND note.idanneescolaire=:idanneescolaire AND note.idmatiere=:idmatiere');
	
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function getDirecteur($idanneescolaire,$statutanneescolaire,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof
				FROM professeur
				WHERE
				professeur.titre=1
				AND
				professeur.statut=1');
			
		$stmt = $pdo->prepare($req);
	}
	else
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof
				FROM professeur
				WHERE
				professeur.titre=1
				AND
				professeur.statut=0
				
				ORDER BY professeur.id DESC LIMIT 0,1');
			
		$stmt = $pdo->prepare($req);
	}
	$stmt = $pdo->prepare($req);
  	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nomprof'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getProfTitulaire($idanneescolaire,$statutanneescolaire,$idsalle,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof
				FROM professeur,professeursallemat
				WHERE
				professeur.id=professeursallemat.idprof
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.statut=1
				AND
				professeursallemat.idtitre=2');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof
				FROM professeur,professeursallemat
				WHERE
				professeur.id=professeursallemat.idprof
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idtitre=2
				AND
				professeursallemat.statut=0
					
				ORDER BY professeur.id DESC LIMIT 0,1');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	}
  	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nomprof'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getProfTitulaireSignature($idanneescolaire,$statutanneescolaire,$idsalle,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof,professeur.signature as signature
				FROM professeur,professeursallemat
				WHERE
				professeur.id=professeursallemat.idprof
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.statut=1
				AND
				professeursallemat.idtitre=2');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof,professeur.signature as signature
				FROM professeur,professeursallemat
				WHERE
				professeur.id=professeursallemat.idprof
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idtitre=2
				AND
				professeursallemat.statut=0
				
				ORDER BY professeur.id DESC LIMIT 0,1');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	}
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
  	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    if($donnees['signature']!="")
		{
			$resultat ="<img src='photo_user/".$donnees['signature']."' width='110px' height='30px'/>";
		}
		else
		{
			$resultat ="";
		}
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMatProf($idanneescolaire,$statutanneescolaire,$idsalle,$idmatiere,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT distinct professeur.nom as nomprof,professeur.id as idprof,professeursallemat.id as id
				FROM professeur,professeursallemat
				WHERE
				professeur.id=professeursallemat.idprof
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idmat=:idmat
				AND
				professeursallemat.statut=1

				ORDER BY professeursallemat.id DESC LIMIT 0,1');
		$resultat="";
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idmat',$idmatiere,PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT distinct professeur.nom as nomprof,professeur.id as idprof,professeursallemat.id as id
				FROM professeur,professeursallemat
				WHERE
				professeur.id=professeursallemat.idprof
				AND
				professeursallemat.idsalle=:idsalle
				AND
				professeursallemat.idmat=:idmat
				AND
				professeursallemat.idanneescolaire=:idanneescolaire

				ORDER BY professeursallemat.id DESC LIMIT 0,1');

		$resultat="";
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idmat',$idmatiere,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	}
	$resultat="*";
  	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nomprof'].'*'.$donnees['idprof'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyObservation($moy,$pdo)
{
	$req=(' SELECT observation.libelle,observation.moy
			FROM observation');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
		$libelle = $donnees['libelle'];
		if($moy<=4)
		{
		    return $libelle;
            break;			
		}
		elseif($moy>4 || $moy<=6)
		{
		    return $libelle;
            break;			
		}
		elseif($moy>6 || $moy<=7)
		{
		    return $libelle;	
			break;
		}
		elseif($moy>7 || $moy<=9)
		{
		    return $libelle;
            break;			
		}
		elseif($moy>9 || $moy<=11)
		{
		    return $libelle;
            break;			
		}
		elseif($moy>11 || $moy<=13)
		{
		    return $libelle;	
			break;
		}
		elseif($moy>13 || $moy<=15)
		{
		    return $libelle;
			break;			
		}
		elseif($moy>15 || $moy<=17)
		{
		    return $libelle;
            break;			
		}
		elseif($moy>17 || $moy<=20)
		{
		    return $libelle;
            break;			
		}
	}
	$stmt->closeCursor();
	$stmt=NULL;
}

function getLibelleAnneeScolaire($id,$pdo)
{
	$req=(' SELECT anneescolaire.libelle
			FROM anneescolaire
			WHERE
			anneescolaire.id=:idanneescolaire');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$id,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$libelle = $donnees['libelle'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $libelle;
}

function getCodeSalle($id,$pdo)
{
	$req=(' SELECT salle.codesalle
			FROM salle
			WHERE
			salle.id=:idsalle');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$id,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$codesalle = $donnees['codesalle'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $codesalle;
}

function getDomaineSalle($id,$pdo)
{
	$req=(' SELECT classe.iddomaine
			FROM salle,classe
			WHERE
			salle.idclasse=classe.idclasse
			AND
			salle.id=:idsalle');
	$iddomaine = "";
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$id,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$iddomaine = $donnees['iddomaine'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $iddomaine;
}

function getCodeMatiereANDCoefficient($idanneescolaire,$statutanneescolaire,$idsalle,$idmatiere,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT distinct matiere.code_matiere,matierecoefficient.coefficient,matierecoefficient.id,
						matierecoefficient.etat
			FROM matiere,matierecoefficient,salle
			WHERE
			matiere.id_matiere=matierecoefficient.idmatiere
			AND
			matierecoefficient.idclasse=salle.idclasse
			AND
			salle.id=:idsalle
			AND
			matiere.id_matiere=:id_matiere
			AND
			matierecoefficient.coefficient<>0
			AND
			matierecoefficient.statut=1

			ORDER BY matierecoefficient.id DESC LIMIT 0,1');
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id_matiere',$idmatiere,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT distinct matiere.code_matiere,matierecoefficient.coefficient,matierecoefficient.id,
								matierecoefficient.etat
				FROM matiere,matierecoefficient,salle
				WHERE
				matiere.id_matiere=matierecoefficient.idmatiere
				AND
				matierecoefficient.idclasse=salle.idclasse
				AND
				salle.id=:idsalle
				AND
				matiere.id_matiere=:id_matiere
				AND
				matierecoefficient.idanneescolaire=:idanneescolaire
				AND
				matierecoefficient.coefficient<>0

				ORDER BY matierecoefficient.id DESC LIMIT 0,1');
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id_matiere',$idmatiere,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	}
	$resultat="*";
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$coefficient = $donnees['coefficient'];
		$code_matiere = $donnees['code_matiere'];
		$etat = $donnees['etat'];
		$resultat = $code_matiere.'*'.$coefficient.'*'.$etat;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getCodeMatiereANDCoefficient_($idsalle,$idmatiere,$pdo)
{
	$req=(' SELECT distinct matiere.code_matiere,matierecoefficient.coefficient,matierecoefficient.id
			FROM matiere,matierecoefficient,salle
			WHERE
			matiere.id_matiere=matierecoefficient.idmatiere
			AND
			matierecoefficient.idclasse=salle.idclasse
			AND
			salle.id=:idsalle
			AND
			matiere.id_matiere=:id_matiere
			AND
			matierecoefficient.coefficient<>0

			ORDER BY matierecoefficient.id DESC LIMIT 0,1');

	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':id_matiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$coefficient = $donnees['coefficient'];
		$code_matiere = $donnees['code_matiere'];
		$resultat = $code_matiere.'-'.$coefficient;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getLibellePosition($id,$pdo)
{
	$req=(' SELECT position.libposition
			FROM position
			WHERE
			position.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idposition',$id,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$libposition = $donnees['libposition'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $libposition;
}

function getNoteInt($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT note.noteint as inte
			FROM note
			WHERE
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$inte = $donnees['inte'];
	}
	else
	{
		$inte="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $inte;
}

function getNoteDs($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT note.noteds as ds
			FROM note
			WHERE
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$ds = $donnees['ds'];
	}
	else
	{
		$ds="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $ds;
}

function getNoteDn($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT note.notedn as dn
			FROM note
			WHERE
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$dn = $donnees['dn'];
	}
	else
	{
		$dn="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $dn;
}

function getNoteComp($idelevesalle,$idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT note.notecomp as comp
			FROM note
			WHERE
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$comp = $donnees['comp'];
	}
	else
	{
		$comp = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $comp;
}

function ExistNoteMatiere($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' SELECT  count(*) as 
			FROM note
			WHERE
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere
			AND
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$comp = $donnees['comp'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $comp;
}

//
function RangMatiereSurbulletin_($idanneescolaire,$idposition,$idsalle,$pdo)
{	
	$req=('   	SELECT distinct note.idmatiere
			    FROM note
				WHERE
				note.idanneescolaire=:idanneescolaire
				AND
				note.idposition=:idposition
				AND
				note.idsalle=:idsalle');
	$stmt= $pdo->prepare($req);
	$stmt-> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt-> bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt-> bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt-> execute();	
	while($donnees= $stmt->fetch())
	{
		$idmatiere = $donnees['idmatiere'];

		$req_1=('   SELECT note.id
					FROM note
					WHERE
					note.idanneescolaire=:idanneescolaire
					AND
					note.idposition=:idposition
					AND
					note.idsalle=:idsalle
					AND
					note.idmatiere=:idmatiere

					ORDER BY note.moyenpondere DESC');
			
		$stmt_1 = $pdo->prepare($req_1);
		$stmt_1 -> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt_1 -> bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt_1 -> bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt_1 -> bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
		$stmt_1 -> execute();	
		$ligne = 0;
		while($donnees_1 = $stmt_1->fetch())
		{
			$ligne++;

			$idnote = $donnees_1['id'];
			$requete =' UPDATE note SET note.rang=:rang WHERE note.id=:id';
			$stmt_2 = $pdo->prepare($requete);
			$stmt_2 -> bindParam(':id', $idnote, PDO::PARAM_INT);
			$stmt_2 -> bindParam(':rang', $ligne, PDO::PARAM_INT);
			$stmt_2 -> execute();			
			$stmt_2 -> closeCursor();
			$stmt_2 = NULL;
		}
		$stmt_1->closeCursor();
		$stmt_1=NULL;
	}
	$stmt->closeCursor();
	$stmt=NULL;
}

function RangMatiereSurbulletin($idanneescolaire,$idposition,$idsalle,$idmatiere,$pdo)
{	
	$req_1=('   SELECT note.id
				FROM note
				WHERE
				note.idanneescolaire=:idanneescolaire
				AND
				note.idposition=:idposition
				AND
				note.idsalle=:idsalle
				AND
				note.idmatiere=:idmatiere

				ORDER BY note.moyenpondere DESC');
		
	$stmt_1 = $pdo->prepare($req_1);
	$stmt_1 -> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt_1 -> bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt_1 -> bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt_1 -> bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt_1 -> execute();	
	$ligne = 0;
	while($donnees_1 = $stmt_1->fetch())
	{
		$ligne++;

		$idnote = $donnees_1['id'];
		$requete =' UPDATE note SET note.rang=:rang WHERE note.id=:id';
		$stmt_2 = $pdo->prepare($requete);
		$stmt_2 -> bindParam(':id', $idnote, PDO::PARAM_INT);
		$stmt_2 -> bindParam(':rang', $ligne, PDO::PARAM_INT);
		$stmt_2 -> execute();			
		$stmt_2 -> closeCursor();
		$stmt_2 = NULL;
	}
	$stmt_1->closeCursor();
	$stmt_1=NULL;
}

//
function MoyenTrimestre($idelevesalle,$idanneescolaire,$idposition,$idsalle,$pdo)
{	
	$req=(' SELECT  sum(note.moyenpondere) as moyenpondere,
	                sum(note.coef) as coefficient 
			FROM note
			WHERE
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			note.ideleve=:ideleve
			
			GROUP BY note.idsalle,note.idanneescolaire,note.idposition,note.ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$MoyenTrimestre = ($donnees['moyenpondere']/$donnees['coefficient']);
	}
	$stmt->closeCursor();
	$stmt=NULL;	
	
	return $MoyenTrimestre;
}

//
function RangSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo)
{	
	$req=(' SELECT bulletin.moyenne_gene,bulletin.id
			FROM bulletin
			WHERE
			bulletin.idsalle=:idsalle
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition

			ORDER BY bulletin.moyenne_gene desc');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	while($donnees = $stmt->fetch())
	{
		$ligne++;
		$id = $donnees['id'];
		
		$requete =' UPDATE bulletin
					SET 
					bulletin.rang=:rang

					WHERE
					bulletin.id=:id';
			
		$stmt_ = $pdo->prepare($requete);
		$stmt_ -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt_ -> bindParam(':rang', $ligne, PDO::PARAM_INT);
		$stmt_ -> execute();			
		$stmt_ -> closeCursor();
		$stmt_ = NULL;
	
	}
	$stmt->closeCursor();
	$stmt=NULL;	
}

function RangAnnuelSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo)
{	
	$req=(' SELECT bulletin.moyen_ann,bulletin.id
			FROM bulletin
			WHERE
			bulletin.idsalle=:idsalle
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition

			ORDER BY bulletin.moyen_ann desc');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	while($donnees = $stmt->fetch())
	{
		$ligne++;
		$id = $donnees['id'];
		
		$requete =' UPDATE bulletin
					SET 
					bulletin.rang_ann=:rang_ann

					WHERE
					bulletin.id=:id';
			
		$stmt_ = $pdo->prepare($requete);
		$stmt_ -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt_ -> bindParam(':rang_ann', $ligne, PDO::PARAM_INT);
		$stmt_ -> execute();			
		$stmt_ -> closeCursor();
		$stmt_ = NULL;
	
	}
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getMoyennePlusForteSalle($idanneescolaire,$idsalle,$idposition,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=:idsalle

			ORDER BY bulletin.moyenne_gene DESC LIMIT 0,1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyennePlusFaibleSalle($idanneescolaire,$idsalle,$idposition,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=:idsalle

			ORDER BY bulletin.moyenne_gene ASC LIMIT 0,1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneTrimestre_NV($idanneescolaire,$idsalle,$ideleve,$idposition,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneTrimestre_1($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=1
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneSemestre_1($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=4
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneSemestre_2($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=5
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneTrimestre_2($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=2
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneTrimestre_3($idanneescolaire,$idsalle,$idelevesalle,$pdo)
{
	$req=(' SELECT bulletin.moyenne_gene as moyen
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
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['moyen'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getRangTrimestre_NV($idanneescolaire,$idsalle,$ideleve,$idposition,$pdo)
{
	$req=(' SELECT bulletin.rang as rang
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getRangTrimestre_1($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.rang as rang
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=1
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getRangSemestre_1($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.rang as rang
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=4
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getRangSemestre_2($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.rang as rang
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=5
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getRangTrimestre_2($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.rang as rang
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=2
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$ideleve,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getRangTrimestre_3($idanneescolaire,$idsalle,$idelevesalle,$pdo)
{
	$req=(' SELECT bulletin.rang as rang
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
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);

	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMoyenneGeneralTrimestre($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT sum(bulletin.moyenne_gene) as total,count(bulletin.id) as nbre
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['total']/$donnees['nbre'];
	}
	$stmt->closeCursor();
	$stmt=NULL;

	return $resultat;
}

function getMoyenneGeneralSemestre($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT sum(bulletin.moyenne_gene) as total,count(bulletin.id) as nbre
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['total']/$donnees['nbre'];
	}
	$stmt->closeCursor();
	$stmt=NULL;

	return $resultat;
}

function getMoyenneGeneralAnnuelle($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT sum(bulletin.moyen_ann) as total,count(bulletin.id) as nbre
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['total']/$donnees['nbre'];
	}
	$stmt->closeCursor();
	$stmt=NULL;

	return $resultat;
}

function EleveDisposeBulletin($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)
{	
	$req=(' SELECT bulletin.id
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=:idsalle
			AND
			bulletin.ideleve=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$id_bulletin = $donnees['id'];
	}
	else
	{
	    $id_bulletin=0;
	}
	
	$stmt->closeCursor();
	$stmt=NULL;

    return $id_bulletin;	
}
/*
function GenererBulletin($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT  distinct
	                note.ideleve as idelevesalle  
			FROM note 
			WHERE
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
		$idelevesalle = $donnees['idelevesalle'];
		
		if(EleveDisposeBulletin($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)==0)
		{
			$Moyenne = round(MoyenTrimestre($idelevesalle,$idanneescolaire,$idposition,$idsalle,$pdo),2);
			$Moyenne_1 = intval($Moyenne);
			
			if($Moyenne_1=="4")
			{
				$appre = "Travail très Faible";						
			}
			
			if($Moyenne_1=="5" OR $Moyenne_1=="6")
			{
				$appre = "Travail faible";						
			}		
			
			if($Moyenne_1=="7")
			{
				$appre = "Travail très insuffisant";						
			}	
			
			if($Moyenne_1=="8" OR $Moyenne_1=="9")
			{
				$appre = "Travail insuffisant";						
			}				
			
			if($Moyenne_1=="10" OR $Moyenne_1=="11")
			{
				$appre = "Travail passable";						
			}			
			
			if($Moyenne_1=="12" OR $Moyenne_1=="13")
			{
				$appre = "Assez-Bien";						
			}
			
			if($Moyenne_1=="14" OR $Moyenne_1=="15")
			{
				$appre = "Bien";						
			}	
			
			if($Moyenne_1=="16" OR $Moyenne_1=="17")
			{
				$appre = "Tres Bien";						
			}		
			
			if($Moyenne_1=="18" OR $Moyenne_1=="19" OR $Moyenne_1=="20")
			{
				$appre = "Excellent";						
			}
			
			if($Moyenne_1=="3")
			{
				$appre = "T.Faible";						
			}	

			if($Moyenne_1=="1")
			{
				$appre = "Travail très faible";						
			}

			if($Moyenne_1=="2")
			{
				$appre = "Travail très faible";						
			}	

			if($Moyenne_1=="0")
			{
				$appre = "Travail très faible";						
			}

			if($Moyenne_1=="")
			{
				$appre = "";						
			}
		
			$requete="INSERT INTO bulletin(idposition,ideleve,idanneescolaire,idsalle,moyenne_gene,rang,moyen_ann,rang_ann,observation) VALUES(:idposition,:ideleve,:idanneescolaire,:idsalle,:moyenne_gene,0,null,null,:observation)";

			$stmt_1 = $pdo->prepare($requete);
			$stmt_1 -> bindParam(':idposition', $idposition, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':ideleve', $idelevesalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':moyenne_gene', $Moyenne, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':observation', $appre, PDO::PARAM_STR);
			
			$stmt_1 ->execute();		
			$stmt_1 ->closeCursor();
			$stmt_1 =NULL;
			
			$req_1=('   SELECT bulletin.id
						FROM bulletin
						WHERE
						bulletin.idposition=:idposition
						AND
						bulletin.idanneescolaire=:idanneescolaire
						AND
						bulletin.ideleve=:ideleve
						AND
						bulletin.idsalle=:idsalle');
			
			$stmt_2 = $pdo->prepare($req_1);
			$stmt_2 -> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':idposition',$idposition,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_2-> execute();	
			if($donnees_2 = $stmt_2->fetch())
			{
				$idbulletin = $donnees_2['id'];	
				
				//Contenu du bulletin
				$req_2=(' 	SELECT  note.noteint,
				                    note.noteds,
									note.notedn,
									note.notecomp,
									note.moyclass,
									note.observation,
									note.idprofesseur,
									note.moyentrimes,
									note.moyenpondere,
									note.coef,
									note.idmatiere
									
							FROM note
							WHERE
							note.idsalle=:idsalle
							AND
							note.idposition=:idposition
							AND
							note.idanneescolaire=:idanneescolaire
							AND
							note.ideleve=:ideleve');
				
				$stmt_3 = $pdo->prepare($req_2);
				$stmt_3 -> bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
				$stmt_3 -> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
				$stmt_3 -> bindParam(':idposition',$idposition,PDO::PARAM_INT);
				$stmt_3 -> bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
				$stmt_3 ->execute();				
				while($donnees_3 = $stmt_3->fetch())
				{
					$noteint = trim($donnees_3['noteint']);
					$noteds = trim($donnees_3['noteds']);
					$notedn = trim($donnees_3['notedn']);
					$notecomp = trim($donnees_3['notecomp']);
					$moyenclass = trim($donnees_3['moyclass']);
					$moyentrimes = trim($donnees_3['moyentrimes']);
					$moyenpondere = trim($donnees_3['moyenpondere']);
					$observation = trim($donnees_3['observation']);
					$idprofesseur = trim($donnees_3['idprofesseur']);
					$idmatiere = trim($donnees_3['idmatiere']);
					$coef = trim($donnees_3['coef']);

					$requete_1="INSERT INTO bulletincontenu(idbulletin,idmatiere,inte,ds,dn,moy_classe,notes_comp,moy_trimes,coef,moy_pondere,professeur,appreciation) 
					VALUES(:idbulletin,:idmatiere,:intt,:ds,:dn,:moy_classe,:notes_comp,:moy_trimes,:coef,:moy_pondere,:professeur,:appreciation)";

					$stmt_4 = $pdo->prepare($requete_1);
					$stmt_4 -> bindParam(':idbulletin', $idbulletin, PDO::PARAM_INT);
					$stmt_4 -> bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
					$stmt_4 -> bindParam(':intt', $noteint, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':ds', $noteds, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':dn', $notedn, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':moy_classe', $moyenclass, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':notes_comp', $notecomp, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':moy_trimes', $moyentrimes, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':coef', $coef, PDO::PARAM_INT);
					$stmt_4 -> bindParam(':moy_pondere', $moyenpondere, PDO::PARAM_STR);
					$stmt_4 -> bindParam(':professeur', $idprofesseur, PDO::PARAM_INT);
					$stmt_4 -> bindParam(':appreciation', $observation, PDO::PARAM_STR);

					$stmt_4 -> execute();		
					$stmt_4 -> closeCursor();
					$stmt_4 = NULL;
					//
					RangMatiereSurbulletin($idanneescolaire,$idposition,$idsalle,$idmatiere,$pdo);
				}
				$stmt_3 -> execute();		
				$stmt_3 -> closeCursor();
				$stmt_3 = NULL;

			}
			$stmt_2 -> execute();		
			$stmt_2 -> closeCursor();
			$stmt_2 = NULL;
			
			//Moyenne du 3eme Trimestre
			$_1_moyenne_trimestre = getMoyenneTrimestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_2_moyenne_trimestre = getMoyenneTrimestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_3_moyenne_trimestre = getMoyenneTrimestre_3($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			
			if($_3_moyenne_trimestre>0)
			{
				if($_1_moyenne_trimestre==0 && $_2_moyenne_trimestre!=0)
				{
					$moyenne_annuelle = ($_2_moyenne_trimestre+$_3_moyenne_trimestre)/2;
				}
				elseif($_1_moyenne_trimestre==0 && $_2_moyenne_trimestre==0)
				{
					$moyenne_annuelle = ($_3_moyenne_trimestre)/1;
				}
				else
				{
					$moyenne_annuelle = ($_1_moyenne_trimestre+$_2_moyenne_trimestre+$_3_moyenne_trimestre)/3;
				}			
				
				$reqqq =' 	UPDATE bulletin
							SET 
							bulletin.moyen_ann=:moyen_ann

							WHERE
							bulletin.id=:id';
					
				$stmt_reqqq = $pdo->prepare($reqqq);
				$stmt_reqqq ->bindParam(':id', $idbulletin, PDO::PARAM_INT);
				$stmt_reqqq->bindParam(':moyen_ann', $moyenne_annuelle, PDO::PARAM_INT);

				$stmt_reqqq -> execute();			
				$stmt_reqqq -> closeCursor();
				$stmt_reqqq = NULL;
				
				//
				RangAnnuelSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
			}
        }			
	}
	$stmt->closeCursor();
	$stmt=NULL;	

	//
	//RangMatiereSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
	RangSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
}
*/

function GenererBulletin($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT  sum(note.moyenpondere)/sum(note.coef) as moyenpondere,
                	sum(note.coef) as coefficient,
                	note.ideleve as idelevesalle

			FROM note
			WHERE
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition

			GROUP BY note.idsalle,note.idanneescolaire,note.idposition,note.ideleve');

	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
		$idelevesalle = $donnees['idelevesalle'];
		$MoyenTrimestre = ($donnees['moyenpondere']);
		
		if(EleveDisposeBulletin($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)==0)
		{
			$Moyenne = round($MoyenTrimestre,2);
			$Moyenne_1 = intval($Moyenne);

			if($Moyenne_1=="4")
			{
				$appre = "Travail très Faible";						
			}
			
			if($Moyenne_1=="5" OR $Moyenne_1=="6")
			{
				$appre = "Travail faible";						
			}		
			
			if($Moyenne_1=="7")
			{
				$appre = "Travail très insuffisant";						
			}	
			
			if($Moyenne_1=="8" OR $Moyenne_1=="9")
			{
				$appre = "Travail insuffisant";						
			}				
			
			if($Moyenne_1=="10" OR $Moyenne_1=="11")
			{
				$appre = "Travail passable";						
			}			
			
			if($Moyenne_1=="12" OR $Moyenne_1=="13")
			{
				$appre = "Assez-Bien";						
			}
			
			if($Moyenne_1=="14" OR $Moyenne_1=="15")
			{
				$appre = "Bien";						
			}	
			
			if($Moyenne_1=="16" OR $Moyenne_1=="17")
			{
				$appre = "Tres Bien";						
			}		
			
			if($Moyenne_1=="18" OR $Moyenne_1=="19" OR $Moyenne_1=="20")
			{
				$appre = "Excellent";						
			}
			
			if($Moyenne_1=="3")
			{
				$appre = "T.Faible";						
			}	

			if($Moyenne_1=="1")
			{
				$appre = "Travail très faible";						
			}

			if($Moyenne_1=="2")
			{
				$appre = "Travail très faible";						
			}	

			if($Moyenne_1=="0")
			{
				$appre = "Travail très faible";						
			}

			if($Moyenne_1=="")
			{
				$appre = "";						
			}
		
			$requete="INSERT INTO bulletin(idposition,ideleve,idanneescolaire,idsalle,moyenne_gene,rang,moyen_ann,rang_ann,observation) VALUES(:idposition,:ideleve,:idanneescolaire,:idsalle,:moyenne_gene,0,null,null,:observation)";

			$stmt_1 = $pdo->prepare($requete);
			$stmt_1 -> bindParam(':idposition', $idposition, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':ideleve', $idelevesalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':moyenne_gene', $Moyenne, PDO::PARAM_STR);
			$stmt_1 -> bindParam(':observation', $appre, PDO::PARAM_STR);
			$stmt_1 ->execute();
			
			$req_1=('   SELECT bulletin.id
						FROM bulletin
						WHERE
						bulletin.idposition=:idposition
						AND
						bulletin.idanneescolaire=:idanneescolaire
						AND
						bulletin.ideleve=:ideleve
						AND
						bulletin.idsalle=:idsalle');
			
			$stmt_2 = $pdo->prepare($req_1);
			$stmt_2 -> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':idposition',$idposition,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_2-> execute();	
			if($donnees_2 = $stmt_2->fetch())
			{
				$idbulletin = $donnees_2['id'];	
			}
		
			//
			$_1_moyenne_trimestre = getMoyenneTrimestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_2_moyenne_trimestre = getMoyenneTrimestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_3_moyenne_trimestre = getMoyenneTrimestre_3($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_1_moyenne_semestre = getMoyenneSemestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_2_moyenne_semestre = getMoyenneSemestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			
			
				if($_3_moyenne_trimestre>0)
				{
				  
					if($_1_moyenne_trimestre==0 && $_2_moyenne_trimestre!=0)
					{
						$moyenne_annuelle = ($_2_moyenne_trimestre+$_3_moyenne_trimestre)/2;
					}
					elseif($_1_moyenne_trimestre==0 && $_2_moyenne_trimestre==0)
					{
						$moyenne_annuelle = ($_3_moyenne_trimestre)/1;
					}
					else
					{
						$moyenne_annuelle = ($_1_moyenne_trimestre+$_2_moyenne_trimestre+$_3_moyenne_trimestre)/3;
					}			
					
					$reqqq =' 	UPDATE bulletin
								SET 
								bulletin.moyen_ann=:moyen_ann

								WHERE
								bulletin.id=:id';
						
					$stmt_reqqq = $pdo->prepare($reqqq);
					$stmt_reqqq ->bindParam(':id', $idbulletin, PDO::PARAM_INT);
					$stmt_reqqq->bindParam(':moyen_ann', $moyenne_annuelle, PDO::PARAM_STR);
					$stmt_reqqq -> execute();			

					RangAnnuelSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
				}
				elseif($_2_moyenne_semestre>0)
				{
				
					if($_1_moyenne_semestre==0)
					{
						$moyenne_annuelle = ($_2_moyenne_semestre)/1;
					}
					else
					{
						$moyenne_annuelle = ($_2_moyenne_semestre+$_1_moyenne_semestre)/2;
					}			
					
					$reqqq =' 	UPDATE bulletin
								SET 
								bulletin.moyen_ann=:moyen_ann

								WHERE
								bulletin.id=:id';
						
					$stmt_reqqq = $pdo->prepare($reqqq);
					$stmt_reqqq ->bindParam(':id', $idbulletin, PDO::PARAM_INT);
					$stmt_reqqq->bindParam(':moyen_ann', $moyenne_annuelle, PDO::PARAM_STR);
					$stmt_reqqq -> execute();			

					RangAnnuelSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
				}
		}
	}
	$publier = $pdo->prepare("UPDATE note SET est_publie = 1 WHERE idanneescolaire = ? AND idsalle = ? AND idposition = ?");
	$publier->execute([$idanneescolaire, $idsalle, $idposition]);

	RangMatiereSurbulletin_($idanneescolaire,$idposition,$idsalle,$pdo);		
	RangSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
}

function GenererBulletinCycleSup($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT  sum(note.moyenpondere)/sum(note.coef) as moyenpondere,
                	sum(note.coef) as coefficient,
                	note.ideleve as idelevesalle

			FROM note
			WHERE
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition

			GROUP BY note.idsalle,note.idanneescolaire,note.idposition,note.ideleve');

	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
		$idelevesalle = $donnees['idelevesalle'];
		$MoyenTrimestre = ($donnees['moyenpondere']);
		
		if(EleveDisposeBulletin($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)==0)
		{
			$Moyenne = round($MoyenTrimestre,2);
			$Moyenne_1 = intval($Moyenne);

			if($Moyenne_1=="4")
			{
				$appre = "";						
			}
			
			if($Moyenne_1=="5" OR $Moyenne_1=="6")
			{
				$appre = "";						
			}		
			
			if($Moyenne_1=="7")
			{
				$appre = "";						
			}	
			
			if($Moyenne_1=="8" OR $Moyenne_1=="9")
			{
				$appre = "";						
			}				
			
			if($Moyenne_1=="10" OR $Moyenne_1=="11")
			{
				$appre = "Travail passable";						
			}			
			
			if($Moyenne_1=="12" OR $Moyenne_1=="13")
			{
				$appre = "Assez-Bien";						
			}
			
			if($Moyenne_1=="14" OR $Moyenne_1=="15")
			{
				$appre = "Bien";						
			}	
			
			if($Moyenne_1=="16" OR $Moyenne_1=="17")
			{
				$appre = "Tres Bien";						
			}		
			
			if($Moyenne_1=="18" OR $Moyenne_1=="19" OR $Moyenne_1=="20")
			{
				$appre = "Excellent";						
			}
			
			if($Moyenne_1=="3")
			{
				$appre = "";						
			}	

			if($Moyenne_1=="1")
			{
				$appre = "";						
			}

			if($Moyenne_1=="2")
			{
				$appre = "";						
			}	

			if($Moyenne_1=="0")
			{
				$appre = "";						
			}

			if($Moyenne_1=="")
			{
				$appre = "";						
			}
		
			$requete="INSERT INTO bulletin(idposition,ideleve,idanneescolaire,idsalle,moyenne_gene,rang,moyen_ann,rang_ann,observation) VALUES(:idposition,:ideleve,:idanneescolaire,:idsalle,:moyenne_gene,0,null,null,:observation)";

			$stmt_1 = $pdo->prepare($requete);
			$stmt_1 -> bindParam(':idposition', $idposition, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':ideleve', $idelevesalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':moyenne_gene', $Moyenne, PDO::PARAM_STR);
			$stmt_1 -> bindParam(':observation', $appre, PDO::PARAM_STR);
			$stmt_1 ->execute();
			
			$req_1=('   SELECT bulletin.id
						FROM bulletin
						WHERE
						bulletin.idposition=:idposition
						AND
						bulletin.idanneescolaire=:idanneescolaire
						AND
						bulletin.ideleve=:ideleve
						AND
						bulletin.idsalle=:idsalle');
			
			$stmt_2 = $pdo->prepare($req_1);
			$stmt_2 -> bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':idposition',$idposition,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
			$stmt_2 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_2-> execute();	
			if($donnees_2 = $stmt_2->fetch())
			{
				$idbulletin = $donnees_2['id'];	
			}

			//
			$_1_moyenne_semestre = getMoyenneSemestre_1($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			$_2_moyenne_semestre = getMoyenneSemestre_2($idanneescolaire,$idsalle,$idelevesalle,$pdo);
			
			if($_2_moyenne_semestre>0)
			{
			
				if($_1_moyenne_semestre==0)
				{
					$moyenne_annuelle = ($_2_moyenne_semestre)/1;
				}
				else
				{
					$moyenne_annuelle = ($_2_moyenne_semestre+$_1_moyenne_semestre)/2;
				}			
				
				$reqqq =' 	UPDATE bulletin
							SET 
							bulletin.moyen_ann=:moyen_ann

							WHERE
							bulletin.id=:id';
					
				$stmt_reqqq = $pdo->prepare($reqqq);
				$stmt_reqqq ->bindParam(':id', $idbulletin, PDO::PARAM_INT);
				$stmt_reqqq->bindParam(':moyen_ann', $moyenne_annuelle, PDO::PARAM_STR);
				$stmt_reqqq -> execute();			

				RangAnnuelSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
			}
		}
	}
	RangMatiereSurbulletin_($idanneescolaire,$idposition,$idsalle,$pdo);		
	RangSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
}

function GenererBulletinPrimaire($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' SELECT  sum(note.notecomp) as moyenpondere,
					sum(note.coef) as coef,
                	note.ideleve as idelevesalle

			FROM note
			WHERE
			note.idsalle=:idsalle
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition

			GROUP BY note.idsalle,note.idanneescolaire,note.idposition,note.ideleve');

	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();
	while($donnees = $stmt->fetch())
	{
		$idelevesalle = $donnees['idelevesalle'];
		$MoyenTrimestre = ($donnees['moyenpondere']);
		//$coef = ($donnees['coef']);
		$coef = 90;
		
		if(EleveDisposeBulletin($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)==0)
		{
			$Moyenne = round($MoyenTrimestre,2);
			$Moyenne_1 = intval($Moyenne);

			if($Moyenne_1>=($coef/2))
			{
				$appre = "ADMIS(E)";						
			}
			elseif($Moyenne_1<($coef/2))
			{
				$appre = "AJOURN&Eacute;E";
			}
			else
			{
				$appre = "";
			}
		
			$requete="INSERT INTO bulletin(idposition,ideleve,idanneescolaire,idsalle,moyenne_gene,rang,moyen_ann,rang_ann,observation) VALUES(:idposition,:ideleve,:idanneescolaire,:idsalle,:moyenne_gene,0,null,null,:observation)";

			$stmt_1 = $pdo->prepare($requete);
			$stmt_1 -> bindParam(':idposition', $idposition, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':ideleve', $idelevesalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
			$stmt_1 -> bindParam(':moyenne_gene', $Moyenne, PDO::PARAM_STR);
			$stmt_1 -> bindParam(':observation', $appre, PDO::PARAM_STR);
			$stmt_1 ->execute();
		}
	}		
	RangSurbulletin($idanneescolaire,$idposition,$idsalle,$pdo);
}

function getNombreBulletinTrimestre($idsalle,$idanneescolaire,$idposition,$pdo)
{	
	$req=(' SELECT count(bulletin.id) as NbreBulletin
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=:idsalle');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$NbreBulletin = $donnees['NbreBulletin'];
	}
	else
	{
	    $NbreBulletin=0;
	}
	
	$stmt->closeCursor();
	$stmt=NULL;

    return $NbreBulletin;	
}

//
function BulletinNoteInt($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.noteint as inte
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['inte'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinNoteDs($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.noteds as inte
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['inte'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinNoteDn($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.notedn as notedn
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['notedn'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinNoteComp($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.notecomp as notecomp
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['notecomp'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinMoy_Class($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.moyclass as moyclass
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['moyclass'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinMoy_Trimes($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.moyentrimes as moyentrimes
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['moyentrimes'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.coef as coef
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['coef'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinCoef_($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.coef as coef
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['coef'];
	}
	else
	{
		$resultat = 0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinMoy_Pondere($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.moyenpondere as moyenpondere
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['moyenpondere'];
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinMoy_Pondere_($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.moyenpondere as moyenpondere
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['moyenpondere'];
	}
	else
	{
		$resultat = 0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinRang($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.rang as rang
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['rang'];
		if($resultat==1)
		{
			$resultat = $donnees['rang']."<sup>er(e)</sup>";
		}
		elseif($resultat>1)
		{
			$resultat = $donnees['rang']."<sup>&egrave;m(e)</sup>";
		}
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinProfesseur($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT professeur.nom as nomprofesseur
			FROM note,professeur
			WHERE
			note.idprofesseur=professeur.id
			AND
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = htmlentities($donnees['nomprofesseur']);
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinSignature($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT professeur.signature as signature
			FROM note,professeur
			WHERE
			note.idprofesseur=professeur.id
			AND
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	$resultat = "";
	if($donnees = $stmt->fetch())
	{
		if($donnees['signature']!="" && file_exists('photo_user/'.$donnees['signature']))
		{
			$resultat ="<img src='photo_user/".$donnees['signature']."' width='100px' height='10px'/>";
		}
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function BulletinAppreciation($idanneescolaire,$idposition,$idsalle,$idelevesalle,$idmatiere,$pdo)
{
	$req=(' SELECT note.observation
			FROM note
			WHERE
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition
			AND
			note.idmatiere=:idmatiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = htmlentities($donnees['observation']);
	}
	else
	{
		$resultat = "";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getTotalDefinitif($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)
{
	$req=(' SELECT sum(note.moyenpondere) as moy_pondere
			FROM note
			WHERE 
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = ($donnees['moy_pondere']);
	}
	else
	{
	    $resultat=0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getTotalCoef($idanneescolaire,$idposition,$idsalle,$idelevesalle,$pdo)
{
	$req=(' SELECT sum(note.coef) as coef
			FROM note
			WHERE 
			note.ideleve=:ideleve
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idsalle=:idsalle
			AND
			note.idposition=:idposition');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['coef'];
	}
	else
	{
	    $resultat=0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}


function getRangAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.rang_ann as rang_ann
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=5
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
	    $resultat = $donnees['rang_ann'];
	}

	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}


function getRangAnnu($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.rang_ann as rang_ann
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
	    $resultat = $donnees['rang_ann'];
	}

	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
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

function getMoyAnnu_($idanneescolaire,$idsalle,$ideleve,$pdo)
{
	$req=(' SELECT bulletin.moyen_ann as moyen_ann
			FROM bulletin
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=5
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

//[PAIEMENT DES FRAIS DE SCOLARITE]

function getAllEleve($idAnneeScolaire,$pdo)
{
	$req=(' SELECT  
	                distinct
	                eleve.id_eleve as idEleve,
					eleve.nom_eleve as NomEleve,
					eleve.prenom_eleve as PrenomEleve,
					eleveanneescolaire.id as idEleveAnneeScolaire,
					eleveanneescolaire.idclasse as idClasse

			FROM    eleve,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idAnneeScolaire

			ORDER BY  eleve.nom_eleve asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idAnneeScolaire',$idAnneeScolaire,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idEleve" id="idEleve" onchange="makeRequest('AjouterPaiementEleve.php','idEleve','Resultat')" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idEleve = $donnees['idEleve'];
			$NomEleve = $donnees['NomEleve'].' '.$donnees['PrenomEleve'];
			$idEleveAnneeScolaire = $donnees['idEleveAnneeScolaire'];
			$idClasse = $donnees['idClasse'];
			
			echo '<option value="'.$idEleve.'-'.$idEleveAnneeScolaire.'-'.$idClasse.'">'.$NomEleve.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllEleveForAbonnement($idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct
	                eleve.id_eleve,
					eleve.nom_eleve,
					eleveanneescolaire.id as ideleveanneescolaire

			FROM    eleve,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire

			ORDER BY  eleve.nom_eleve asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idEleveAnneeScolaire" id="idEleveAnneeScolaire">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_eleve = $donnees['id_eleve'];
			$eleve = $donnees['nom_eleve'];
			$id = $donnees['ideleveanneescolaire'];

			echo '<option value="'.$id.'">'.$eleve.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllEleveForAbonnementSelected($idEleveAnneeScolaire,$idAnneeScolaire,$pdo)
{
	$req=(' SELECT  
	                distinct
	                eleve.id_eleve,
					eleve.nom_eleve,
					eleveanneescolaire.id as ideleveanneescolaire

			FROM    eleve,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire

			ORDER BY  eleve.nom_eleve asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idAnneeScolaire,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idEleveAnneeScolaire" id="idEleveAnneeScolaire">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_eleve = $donnees['id_eleve'];
			$eleve = $donnees['nom_eleve'];
			$id = $donnees['ideleveanneescolaire'];

			if($id==$idEleveAnneeScolaire)
			{
				echo '<option value="'.$id.'" selected="selected">'.$eleve.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'">'.$eleve.'</option>';	
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function getMontantEleveAnneeScolaire($idtype,$ideleveanneescolaire,$pdo)
{
	$req=(' SELECT sum(paiementfrais.montant)as montant
			FROM paiementfrais,paiementtypeclasse
			WHERE 
			paiementfrais.idpaiementtypeclasse=paiementtypeclasse.id
			AND
			paiementtypeclasse.idpaiementtype=:idtype
			AND
			paiementfrais.ideleveanneescolaire=:ideleveanneescolaire
			AND
			paiementfrais.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleveanneescolaire',$ideleveanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idtype',$idtype,PDO::PARAM_INT);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['montant'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$boursier,$pdo)
{
	$req=(' SELECT paiementtypeclasse.montant as montant,paiementtypeclasse.remise as remise,paiementtypeclasse.id as id
	
			FROM paiementtypeclasse
			WHERE 
			paiementtypeclasse.idclasse=:idclasse
			AND
			paiementtypeclasse.idpaiementtype=:idpaiementtype
			AND
			paiementtypeclasse.statut=1
			AND
			paiementtypeclasse.idanneescolaire=:idanneescolaire');
				
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtype',$idpaiementtype,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->execute();
	$resultat="*";
	if($donnees = $stmt->fetch())
	{
		$id = $donnees['id'];
		$montant = $donnees['montant'];
		$montantclasse = $montant-$boursier;
		$resultat = $montantclasse.'*'.$id;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

//
function getMontantPaiementFraisEleveAnneeScolaire($id,$idEleveAnneeScolaire,$pdo)
{
	$req=(' SELECT sum(paiementfrais.montant)as Montant
			FROM paiementfrais
			WHERE 
			paiementfrais.idpaiementtypeclasse=:idpaiementtypeclasse
			AND
			paiementfrais.ideleveanneescolaire=:idEleveAnneeScolaire
			AND
			paiementfrais.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idEleveAnneeScolaire',$idEleveAnneeScolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtypeclasse',$id,PDO::PARAM_INT);
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

//[PAIEMENT DES FRAIS DE SCOLARITE]
function getAllEleveSelected($idanneescolaire,$ideleveanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct
	                eleve.id_eleve,
					eleve.nom_eleve,
					eleveanneescolaire.id as ideleveanneescolaire

			FROM    eleve,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire

			ORDER BY  eleve.nom_eleve asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="ideleveanneescolaire" id="ideleveanneescolaire" onchange="makeRequest('AjouterPaiementEleve.php','ideleveanneescolaire','resultat')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_eleve = $donnees['id_eleve'];
			$eleve = $donnees['nom_eleve'];
			$id = $donnees['ideleveanneescolaire'];

			if($id==$ideleveanneescolaire)
			{
				echo '<option value="'.$id.'" selected="selected">'.$eleve.'</option>';	
			}
			else
			{
				echo '<option value="'.$id.'">'.$eleve.'</option>';	
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//
function verifCaissePaiement($idpaiementfrais,$pdo)
{
	$req=(' SELECT count(*) as exist
			FROM caissepaiement
			WHERE 
			caissepaiement.idpaiementfrais=:idpaiementfrais');
	$resultat=0;
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpaiementfrais',$idpaiementfrais,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['exist'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getidElevePaiement($ideleveanneescolaire,$montant_paye,$date_paiement,$idpaiementtypeclasse,$pdo)
{
	$req=(' SELECT paiementfrais.id
			FROM paiementfrais
			WHERE 
			paiementfrais.ideleveanneescolaire=:ideleveanneescolaire
			AND
			paiementfrais.idpaiementtypeclasse=:idpaiementtypeclasse
			AND
			paiementfrais.montant=:montant
			AND
			paiementfrais.date=:date_paiement');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt_->bindParam(':idpaiementtypeclasse', $idpaiementtypeclasse, PDO::PARAM_INT);
	$stmt_->bindParam(':montant', $montant_paye, PDO::PARAM_INT);
    $stmt_->bindParam(':date_paiement', $date_paiement, PDO::PARAM_STR);
	$stmt_->execute();	
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['id'];
	}
	else
	{
	    $resultat=0;
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function CreateElevePaiement($ideleveanneescolaire,$montant_paye,$date_paiement,$idpaiementtype,$nompayeur,$telpayeur,$iduser,$idanneescolaire,$pdo)
{
    $id=getidElevePaiement($ideleveanneescolaire,$montant_paye,$date_paiement,$idpaiementtype,$pdo);

	if($id==0)
	{
		$requete="INSERT INTO paiementfrais(idpaiementtypeclasse,ideleveanneescolaire,montant,date,statut,iduserajout,iduserdelete,nompayeur,idanneescolaire,telpayeur) VALUES(:idpaiementtypeclasse,:ideleveanneescolaire,:montant,:date,1,:iduserajout,0,:nompayeur,:idanneescolaire,:telpayeur)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idpaiementtypeclasse', $idpaiementtype, PDO::PARAM_INT);
		$stmt->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':iduserajout', $iduser, PDO::PARAM_INT);
		$stmt->bindParam(':montant', $montant_paye, PDO::PARAM_INT);
		$stmt->bindParam(':date', $date_paiement, PDO::PARAM_STR);
		$stmt->bindParam(':nompayeur', $nompayeur, PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
		$stmt->bindParam(':telpayeur', $telpayeur, PDO::PARAM_STR);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;
	}
}

function UpdateElevePaiement($id,$montant_paye,$date_paiement,$nompayeur,$telpayeur,$pdo)
{
	
	$requete="	UPDATE 	
				paiementfrais 
				SET 	
				paiementfrais.montant=:montant,
				paiementfrais.date=:datepaiement,
				paiementfrais.nompayeur=:nompayeur,
				paiementfrais.telpayeur=:telpayeur
					
				WHERE 
				paiementfrais.id=:id";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':montant', $montant_paye, PDO::PARAM_INT);
	$stmt->bindParam(':datepaiement', $date_paiement, PDO::PARAM_STR);
	$stmt->bindParam(':nompayeur', $nompayeur, PDO::PARAM_STR);
	$stmt->bindParam(':telpayeur', $telpayeur, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_STR);
	$stmt ->execute();		
    $stmt ->closeCursor();
	$stmt =NULL;
}

function DeleteElevePaiement($idpaiementfrais,$iduser,$pdo)
{
	$req=' UPDATE paiementfrais SET statut=0,iduserdelete=:iduserdelete WHERE paiementfrais.id=:id';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id', $idpaiementfrais, PDO::PARAM_INT);
    $stmt ->bindParam(':iduserdelete', $iduser, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

//FINANCE
function getidCaisse($DateDebut,$DateFin,$Type,$pdo)
{
	$req=(' SELECT caisse.id as id
			FROM caisse
			WHERE 
			caisse.debut=:debut
			AND
			caisse.fin=:fin
			AND
			caisse.type=:type');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':debut', $DateDebut, PDO::PARAM_STR);
	$stmt->bindParam(':fin', $DateFin, PDO::PARAM_STR);
	$stmt->bindParam(':type', $Type, PDO::PARAM_STR);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function ExistLigneCaisse($idCaisse,$idPaiementFrais,$Type,$pdo)
{
	$req=(' SELECT count(*) as Exist
			FROM caissepaiement
			WHERE 
			caissepaiement.idcaisse=:idCaisse
			AND
			caissepaiement.idpaiementfrais=:idPaiementFrais
			AND
			caissepaiement.type=:type');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idCaisse', $idCaisse, PDO::PARAM_INT);
	$stmt->bindParam(':idPaiementFrais', $idPaiementFrais, PDO::PARAM_INT);
	$stmt->bindParam(':type', $Type, PDO::PARAM_STR);
	$stmt->execute();	
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['Exist'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateCaisse($DateDebut,$DateFin,$Montant,$Type,$idAnneeScolaire,$idUser,$pdo)
{
    $id=getidCaisse($DateDebut,$DateFin,$Type,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO caisse(debut,fin,iduser,statut,montant,idanneescolaire,type) VALUES(:debut,:fin,:iduser,1,:montant,:idanneescolaire,:type)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':debut', $DateDebut, PDO::PARAM_STR);
		$stmt->bindParam(':fin', $DateFin, PDO::PARAM_STR);
		$stmt->bindParam(':iduser', $idUser, PDO::PARAM_INT);
		$stmt->bindParam(':montant', $Montant, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idAnneeScolaire, PDO::PARAM_INT);
		$stmt->bindParam(':type', $Type, PDO::PARAM_STR);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;
		
		$id=getidCaisse($DateDebut,$DateFin,$Type,$pdo);
		$error="";
		$success="Op&eacute;ration d'enregistrement effectu&eacute;e avec succ&egrave;s";
		return $id.'*'.$success.'*'.$error;
	}
	else
	{
		$error="Echec d'enregistrement. Journ&eacute;e d&eacute;j&agrave; prise en compte.";
		$success="";
		return $id.'*'.$success.'*'.$error;
	}
}

function CreateLigneCaisse($idpaiementfrais,$idcaisse,$Type,$pdo)
{
    $exist=ExistLigneCaisse($idcaisse,$idpaiementfrais,$Type,$pdo);
	if($exist==0)
	{
		$requete="INSERT INTO caissepaiement(idpaiementfrais,idcaisse,type) VALUES(:idpaiementfrais,:idcaisse,:type)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idpaiementfrais', $idpaiementfrais, PDO::PARAM_STR);
		$stmt->bindParam(':idcaisse', $idcaisse, PDO::PARAM_STR);
		$stmt->bindParam(':type', $Type, PDO::PARAM_STR);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;
	}
}

function DeleteCaisse($idCaisse,$pdo)
{
	$req=' DELETE FROM caissepaiement WHERE caissepaiement.idcaisse=:idcaisse';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idcaisse', $idCaisse, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	
	$req_=' DELETE FROM caisse WHERE caisse.id=:idcaisse';
    $stmt_ = $pdo->prepare($req_);
	$stmt_ ->bindParam(':idcaisse', $idCaisse, PDO::PARAM_INT);
    $stmt_->execute();			
	$stmt_->closeCursor();
	$stmt_=NULL;
}

function getAllModePaiement($pdo)
{
	$req=(' SELECT 
					modepaiement.id as idmodepaiement,
	                modepaiement.libelle as libellemodepaiement

			FROM modepaiement');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<SELECT class="form-control" name="idmodepaiement" required="required" >
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idmodepaiement = $donnees['idmodepaiement'];
			$libellemodepaiement = $donnees['libellemodepaiement'];

			echo '<option value="'.$idmodepaiement.'">'.$libellemodepaiement.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllModePaiementSelected($idModePaiementSelected,$pdo)
{
	$req=(' SELECT 
					modepaiement.id as idmodepaiement,
	                modepaiement.libelle as libellemodepaiement

			FROM modepaiement');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<SELECT class="form-control" name="idmodepaiement" required="required" >
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idmodepaiement = $donnees['idmodepaiement'];
			$libellemodepaiement = $donnees['libellemodepaiement'];

			if($idModePaiementSelected==$idmodepaiement)
			{
				echo '<option value="'.$idmodepaiement.'" selected="selected">'.$libellemodepaiement.'</option>';
			}
			else
			{
				echo '<option value="'.$idmodepaiement.'">'.$libellemodepaiement.'</option>';
			}
				
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllCompte($pdo)
{
	$req=(' SELECT 
					compte.id as idCompte,
	                compte.libelle as libelleCompte

			FROM compte
			WHERE
			compte.statut=1');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control" name="idCompte" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idCompte = $donnees['idCompte'];
			$libelleCompte = $donnees['libelleCompte'];

			echo '<option value="'.$idCompte.'">'.$libelleCompte.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllCompteSelected($idCompteSelected,$pdo)
{
	$req=(' SELECT 
					compte.id as idCompte,
	                compte.libelle as libelleCompte

			FROM compte
			WHERE
			compte.statut=1');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control" name="idCompte" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idCompte = $donnees['idCompte'];
			$libelleCompte = $donnees['libelleCompte'];
			
			if($idCompte==$idCompteSelected)
			{
				echo '<option value="'.$idCompte.'"selected="selected">'.$libelleCompte.'</option>';
			}
			else
			{
				echo '<option value="'.$idCompte.'">'.$libelleCompte.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllBanque($pdo)
{
	$req=(' SELECT 
					banque.id as idBanque,
	                banque.libelle as libelleBanque

			FROM banque');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<SELECT class="form-control" name="idBanque" required="required" >
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idBanque = $donnees['idBanque'];
			$libelleBanque = $donnees['libelleBanque'];

			echo '<option value="'.$idBanque.'">'.$libelleBanque.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllBanqueSelected($idBanqueSelected,$pdo)
{
	$req=(' SELECT 
					banque.id as idBanque,
	                banque.libelle as libelleBanque

			FROM banque');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<SELECT class="form-control" name="idBanque" required="required" >
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idBanque = $donnees['idBanque'];
			$libelleBanque = $donnees['libelleBanque'];

			if($idBanque==$idBanqueSelected)
			{
				echo '<option value="'.$idBanque.'" selected="selected">'.$libelleBanque.'</option>';
			}
			else
			{
				echo '<option value="'.$idBanque.'">'.$libelleBanque.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPaiementType($pdo)
{
	$req=(' SELECT  paiementtype.id as idPaiementType,
	                paiementtype.libelle as libellePaiementType
					
			FROM paiementtype');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idPaiementType" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idPaiementType = $donnees['idPaiementType'];
			$libellePaiementType = $donnees['libellePaiementType'];
			
			echo '<option value="'.$idPaiementType.'">'.$libellePaiementType.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPaiementTypeSelected($idPaiementTypeSelected,$pdo)
{
	$req=(' SELECT  paiementtype.id as idPaiementType,
	                paiementtype.libelle as libellePaiementType
					
			FROM paiementtype');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idPaiementType" required="required" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idPaiementType = $donnees['idPaiementType'];
			$libellePaiementType = $donnees['libellePaiementType'];
			if($idPaiementType==$idPaiementTypeSelected)
			{
				echo '<option value="'.$idPaiementType.'" selected="selected">'.$libellePaiementType.'</option>';
			}
			else
			{
				echo '<option value="'.$idPaiementType.'">'.$libellePaiementType.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getEntreeCompteEtat($idAnneeScolaire,$idCompte,$pdo)
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
			entreesortie.idtypeentreesortie=1');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
	$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
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

function getSortieCompteEtat($idAnneeScolaire,$idCompte,$pdo)
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
			entreesortie.idtypeentreesortie=2');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
	$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
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

function getAllProfesseur($pdo)
{
	
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
		
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			echo '<option value="'.$id.'">'.$nom.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllProfesseurSelected($idprof,$pdo)
{
	
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			
			
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			if($id==$idprof)
			{
				echo '<option value="'.$id.'" selected="selected">'.$nom.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$nom.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPersonnel($pdo)
{
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			WHERE
			professeur.corps=2
			AND
			professeur.statut=1
			
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idpers" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			echo '<option value="'.$id.'">'.$nom.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPersonnelSelected($idpers,$pdo)
{
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			WHERE
			professeur.corps=2
			AND
			professeur.statut=1
			
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idpers" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			if($id==$idpers)
			{
				echo '<option value="'.$id.'" selected="selected">'.$nom.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$nom.'</option>';
			}	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

//[INSCRIPTION]
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

function UpdateEtudiant($image_name,$matricule,$mailtuteur,$teltuteur,$idelevesalle,$id_eleve,$nom_eleve,$prenom_eleve,$sexe,
							$date_naissance,$idsalle,$etat_eleve,$idtypeinscription,$ideleveanneescolaire,$pdo)
{
	if($image_name!="")
	{
		$req='  UPDATE eleve
		        SET 
				eleve.nom_eleve=:nom_eleve,
				eleve.prenom_eleve=:prenom_eleve,
				eleve.sexe_eleve=:sexe_eleve,
				eleve.datenaissance_eleve=:datenaissance_eleve,
				eleve.matricule=:matricule,
				eleve.teltuteur=:teltuteur,
				eleve.mailtuteur=:mailtuteur,
				eleve.photo=:photo
				
				WHERE
				eleve.id_eleve=:id_eleve';
			
	    $stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_eleve', $id_eleve, PDO::PARAM_INT);
		$stmt->bindParam(':nom_eleve', $nom_eleve, PDO::PARAM_STR);
		$stmt->bindParam(':prenom_eleve', $prenom_eleve, PDO::PARAM_STR);
		$stmt->bindParam(':sexe_eleve', $sexe, PDO::PARAM_STR);
		$stmt->bindParam(':datenaissance_eleve', $date_naissance, PDO::PARAM_STR);
		$stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		$stmt->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
		$stmt->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
		$stmt->bindParam(':photo', $image_name, PDO::PARAM_STR);
	    $stmt->execute();			
		$stmt->closeCursor();
		$stmt=NULL;		
	}
	else
	{
		$req='  UPDATE eleve
		        SET 
				eleve.nom_eleve=:nom_eleve,
				eleve.prenom_eleve=:prenom_eleve,
				eleve.sexe_eleve=:sexe_eleve,
				eleve.datenaissance_eleve=:datenaissance_eleve,
				eleve.matricule=:matricule,
				eleve.teltuteur=:teltuteur,
				eleve.mailtuteur=:mailtuteur
				
				WHERE
				eleve.id_eleve=:id_eleve';
			
	    $stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_eleve', $id_eleve, PDO::PARAM_INT);
		$stmt->bindParam(':nom_eleve', $nom_eleve, PDO::PARAM_STR);
		$stmt->bindParam(':prenom_eleve', $prenom_eleve, PDO::PARAM_STR);
		$stmt->bindParam(':sexe_eleve', $sexe, PDO::PARAM_STR);
		$stmt->bindParam(':datenaissance_eleve', $date_naissance, PDO::PARAM_STR);
		$stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		$stmt->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
		$stmt->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
	    $stmt->execute();			
		$stmt->closeCursor();
		$stmt=NULL;
	}
	//
	$req='  UPDATE eleveanneescolaire
	        SET 
			eleveanneescolaire.inscrit=:inscrit,
			eleveanneescolaire.etat=:etat
			
			WHERE
			eleveanneescolaire.id=:ideleveanneescolaire';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':ideleveanneescolaire', $ideleveanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':inscrit', $idtypeinscription, PDO::PARAM_INT);
	$stmt->bindParam(':etat', $etat_eleve, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	//
	$req='  UPDATE elevesalle
	        SET 
			elevesalle.idsalle=:idsalle
			
			WHERE
			elevesalle.id=:idelevesalle';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

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

//IMPORT DES ELEVES
function ImportEleve($photo,$matricule,$mailtuteur,$teltuteur,$nom_eleve,$prenom_eleve,$sexe,$etatclasse,$etatetablissement,$date_naissance,$idclasse,$idsalle,$idanneescolaire,$pdo)
{   
    
    $id_eleve = getidEleve($nom_eleve,$prenom_eleve,$date_naissance,$pdo);
	if($id_eleve==0)
	{
		$requete="INSERT INTO eleve(nom_eleve,prenom_eleve,sexe_eleve,datenaissance_eleve,matricule,teltuteur,mailtuteur,photo) VALUES(:nom_eleve,:prenom_eleve,:sexe_eleve,:datenaissance_eleve,:matricule,:teltuteur,:mailtuteur,:photo)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':nom_eleve', $nom_eleve, PDO::PARAM_STR);
		$stmt ->bindParam(':prenom_eleve', $prenom_eleve, PDO::PARAM_STR);
		$stmt ->bindParam(':datenaissance_eleve', $date_naissance, PDO::PARAM_STR);
		$stmt ->bindParam(':sexe_eleve', $sexe, PDO::PARAM_STR);
        $stmt ->bindParam(':matricule', $matricule, PDO::PARAM_STR);
        $stmt ->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
        $stmt ->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
        $stmt ->bindParam(':photo', $photo, PDO::PARAM_STR);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;
		
		$id_eleve = getidEleve($nom_eleve,$prenom_eleve,$date_naissance,$pdo);
		$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':ideleve', $id_eleve, PDO::PARAM_INT);
        $stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
        $stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt ->bindParam(':inscrit', $etatetablissement, PDO::PARAM_INT);
		$stmt ->bindParam(':etat', $etatclasse, PDO::PARAM_INT);
		$stmt ->execute();
		$stmt ->closeCursor();
		$stmt =NULL;
		
		$ideleveeleveanneescolaire = getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
		CreateEleveSalle($ideleveeleveanneescolaire,$idsalle,$idanneescolaire,$pdo);
		
		$success="Inscription effectu&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$ideleveeleveanneescolaire = getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
		if($ideleveeleveanneescolaire==0)
		{
			$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat)";
			$stmt = $pdo->prepare($requete);
			$stmt ->bindParam(':ideleve', $id_eleve, PDO::PARAM_INT);
			$stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
			$stmt ->bindParam(':inscrit', $etatetablissement, PDO::PARAM_INT);
			$stmt ->bindParam(':etat', $etatclasse, PDO::PARAM_INT);
			$stmt ->execute();
			$stmt ->closeCursor();
			$stmt =NULL;
			
			$ideleveeleveanneescolaire = getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
			CreateEleveSalle($ideleveeleveanneescolaire,$idsalle,$idanneescolaire,$pdo);
			$success="Inscription effectu&eacute;e avec succ&egrave;s";
			$error="";
		}
		else
		{
			$success="";
			$error="Echec d'enregistrement de l'inscription.";
		}
	}
	return $success.'*'.$error;
}

//INSCRIPTION DE L'ELEVE
function CreateEtudiant($photo,$matricule,$mailtuteur,$teltuteur,$nom_eleve,$prenom_eleve,$sexe,$etatclasse,$date_naissance,$idclasse,$idanneescolaire,$etatetablissement,$pdo)
{   
	$etat_eleve="";
    $id_eleve = getidEleve($nom_eleve,$prenom_eleve,$date_naissance,$pdo);
	if($id_eleve==0)
	{

		$requete="INSERT INTO eleve(nom_eleve,prenom_eleve,sexe_eleve,etat_eleve,datenaissance_eleve,matricule,teltuteur,mailtuteur,photo) VALUES(:nom_eleve,:prenom_eleve,:sexe_eleve,:etat_eleve,:datenaissance_eleve,:matricule,:teltuteur,:mailtuteur,:photo)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':nom_eleve', $nom_eleve, PDO::PARAM_STR);
		$stmt ->bindParam(':prenom_eleve', $prenom_eleve, PDO::PARAM_STR);
		$stmt ->bindParam(':datenaissance_eleve', $date_naissance, PDO::PARAM_STR);
		$stmt ->bindParam(':sexe_eleve', $sexe, PDO::PARAM_STR);
		$stmt ->bindParam(':etat_eleve', $etat_eleve, PDO::PARAM_STR);
        	$stmt ->bindParam(':matricule', $matricule, PDO::PARAM_STR);
        	$stmt ->bindParam(':teltuteur', $teltuteur, PDO::PARAM_STR);
        	$stmt ->bindParam(':mailtuteur', $mailtuteur, PDO::PARAM_STR);
        	$stmt ->bindParam(':photo', $photo, PDO::PARAM_STR);
		$stmt ->execute();		
        	$stmt ->closeCursor();
		$stmt =NULL;

		$id_eleve = getidEleve($nom_eleve,$prenom_eleve,$date_naissance,$pdo);
		$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat)";
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':ideleve', $id_eleve, PDO::PARAM_INT);
        	$stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
        	$stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt ->bindParam(':inscrit', $etatetablissement, PDO::PARAM_INT);
		$stmt ->bindParam(':etat', $etatclasse, PDO::PARAM_INT);
		$stmt ->execute();
		$stmt ->closeCursor();
		$stmt =NULL;
		
		//$ideleveeleveanneescolaire = getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
		//CreateEleveSalle($ideleveeleveanneescolaire,$idsalle,$idanneescolaire,$pdo);
		$success="Inscription effectu&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$ideleveeleveanneescolaire = getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
		if($ideleveeleveanneescolaire==0)
		{
			$requete="INSERT INTO eleveanneescolaire(ideleve,idanneescolaire,idclasse,statut,inscrit,etat) VALUES(:ideleve,:idanneescolaire,:idclasse,1,:inscrit,:etat)";
			$stmt = $pdo->prepare($requete);
			$stmt ->bindParam(':ideleve', $id_eleve, PDO::PARAM_INT);
			$stmt ->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
			$stmt ->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
			$stmt ->bindParam(':inscrit', $etatetablissement, PDO::PARAM_INT);
			$stmt ->bindParam(':etat', $etatclasse, PDO::PARAM_INT);
			$stmt ->execute();
			$stmt ->closeCursor();
			$stmt =NULL;
			
			//$ideleveeleveanneescolaire = getidEleveAnneeScolaire($id_eleve,$idanneescolaire,$pdo);
			//CreateEleveSalle($ideleveeleveanneescolaire,$idsalle,$idanneescolaire,$pdo);
			$success="Inscription effectu&eacute;e avec succ&egrave;s";
			$error="";
		}
		else
		{
			$success="";
			$error="Echec d'enregistrement de l'inscription.";
		}
	}
	return $success.'*'.$error;
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

function getAllClasseFiliere($pdo)
{
	$req=(' SELECT  classe.idclasse,
	                classe.nomclasse,
					classe.codeclasse

			FROM classe');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idclasse" required="required" onchange="makeRequest('MontantInscription.php','idclasse','AfficheMontantInscription')">
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

function getAllEtat($pdo)
{
	$req=(' SELECT 	etat.id as id,
	                etat.nom as nom
					
			FROM etat');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="etat" id="etat" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			echo '<option value="'.$id.'*'.$nom.'">'.$nom.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllEtatSelected($etat,$pdo)
{
	$req=(' SELECT 	etat.id,
	                etat.nom
					
			FROM etat');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="etat" id="etat">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			if($id==$etat)
			{
				echo '<option value="'.$id.'*'.$nom.'" selected="selected">'.$nom.'</option>';
            }
            else
			{
				echo '<option value="'.$id.'*'.$nom.'">'.$nom.'</option>';
            }				
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function DeleteInscription($id,$pdo)
{
	$req='  DELETE FROM eleveanneescolaire WHERE eleveanneescolaire.id=:id';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;		
}

function DeleteEleveSalle($id,$statut,$pdo)
{
	$req='  UPDATE elevesalle
	        SET 
			elevesalle.statut=:statut
			WHERE
			elevesalle.id=:id';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;		
}

function getAllClasseFiliere_($pdo)
{
	$req=(' SELECT  classe.idclasse,
	                classe.nomclasse,
					classe.codeclasse

			FROM classe');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idclasse" id="idclasse" onchange="makeRequest('ListeAffectation.php','idclasse','affectation')">
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

function getAllExercice($pdo)
{
	$req=(' SELECT  exercice.id_exercice,
	                exercice.libelle_exercice

			FROM exercice');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="exercice">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id_exercice = $donnees['id_exercice'];
			$libelle_exercice = $donnees['libelle_exercice'];
			
			echo '<option value="'.$id_exercice.'">'.$libelle_exercice.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getidPersonnel($nom,$pdo)
{
	$req=(' SELECT professeur.id
			FROM professeur
			WHERE 
			professeur.nom=:nom
			AND
			professeur.corps=2
			AND
			professeur.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':nom', $nom, PDO::PARAM_STR);
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

function CreatePersonnel($idanneescolaire,$nom,$titre,$contact,$numcnss,$dateembauche,$datenaissance,$lieunaissance,$perscharge,$numcomptebancaire,$idbanque,$debutcontrat,$fincontrat,$modepaiement,$fichier,$iduser,$pdo)
{   
	$statut=1;
	$corps=2;
    $idPersonnel = getidPersonnel($nom,$pdo);
	if($idPersonnel==0)
	{
		$requete="INSERT INTO professeur(nom,titre,contact,signature,statut,idanneescolaire,corps,dateembauche,datenaissance,personneacharge,lieunaissance,numcnss,numcomptebancaire,idbanque,debutcontrat,fincontrat,modepaiement,create_id) 
					VALUES(:nom,:titre,:contact,:signature,:statut,:idanneescolaire,:corps,:dateembauche,:datenaissance,:personneacharge,:lieunaissance,:numcnss,:numcomptebancaire,:idbanque,:debutcontrat,:fincontrat,:modepaiement,:create_id)";

		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
		$stmt -> bindParam(':titre', $titre, PDO::PARAM_STR);
		$stmt -> bindParam(':contact', $contact, PDO::PARAM_STR);
		$stmt -> bindParam(':signature', $fichier, PDO::PARAM_STR);
		$stmt -> bindParam(':statut', $statut, PDO::PARAM_STR);
		$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
		$stmt -> bindParam(':corps', $corps, PDO::PARAM_STR);
		$stmt -> bindParam(':dateembauche', $dateembauche, PDO::PARAM_STR);
		$stmt -> bindParam(':datenaissance', $datenaissance, PDO::PARAM_STR);
		$stmt -> bindParam(':personneacharge', $perscharge, PDO::PARAM_STR);
		$stmt -> bindParam(':lieunaissance', $lieunaissance, PDO::PARAM_STR);
		$stmt -> bindParam(':numcnss', $numcnss, PDO::PARAM_STR);
		$stmt -> bindParam(':numcomptebancaire', $numcomptebancaire, PDO::PARAM_STR);
		$stmt -> bindParam(':idbanque', $idbanque, PDO::PARAM_STR);
		$stmt -> bindParam(':debutcontrat', $debutcontrat, PDO::PARAM_STR);
		$stmt -> bindParam(':fincontrat', $fincontrat, PDO::PARAM_STR);
		$stmt -> bindParam(':modepaiement', $modepaiement, PDO::PARAM_STR);
		$stmt -> bindParam(':create_id', $iduser, PDO::PARAM_STR);
		$stmt -> execute();		
        	$stmt -> closeCursor();
		$stmt = NULL;
		$success="Employ&eacute; cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Cet employ&eacute; existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function DeletePersonnel($id,$pdo)
{   
	$requete="DELETE FROM professeur WHERE professeur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function ChangeStatutPersonnel($id,$statut,$iduser,$pdo)
{   
	$requete="UPDATE professeur SET statut=:statut,delete_id=:delete_id WHERE professeur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':statut', $statut, PDO::PARAM_INT);
	$stmt -> bindParam(':delete_id', $iduser, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function AnnulerDesactivationPersonnel($id,$pdo)
{   
	$requete="UPDATE professeur SET statut=1 WHERE professeur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdatePersonnel($idanneescolaire,$id,$nom,$titre,$contact,$numcnss,$dateembauche,$datenaissance,$lieunaissance,$perscharge,$numcomptebancaire,$idbanque,$debutcontrat,$fincontrat,$modepaiement,$fichier,$pdo)
{ 
	//
	$requete="	UPDATE professeur SET 	nom=:nom,
	                                    titre=:titre,
										contact=:contact,
										signature=:signature,
										numcnss=:numcnss,
										dateembauche=:dateembauche,
										datenaissance=:datenaissance,
										lieunaissance=:lieunaissance,
										personneacharge=:perscharge,
										numcomptebancaire=:numcomptebancaire,
										idbanque=:idbanque,
										debutcontrat=:debutcontrat,
										fincontrat=:fincontrat,
										modepaiement=:modepaiement,
										idanneescolaire=:idanneescolaire
	
				WHERE professeur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
	$stmt -> bindParam(':titre', $titre, PDO::PARAM_STR);
	$stmt -> bindParam(':contact', $contact, PDO::PARAM_STR);
	$stmt -> bindParam(':signature', $fichier, PDO::PARAM_STR);
	$stmt -> bindParam(':numcnss', $numcnss, PDO::PARAM_STR);
	$stmt -> bindParam(':dateembauche', $dateembauche, PDO::PARAM_STR);
	$stmt -> bindParam(':datenaissance', $datenaissance, PDO::PARAM_STR);
	$stmt -> bindParam(':lieunaissance', $lieunaissance, PDO::PARAM_STR);
	$stmt -> bindParam(':perscharge', $perscharge, PDO::PARAM_STR);
	$stmt -> bindParam(':numcomptebancaire', $numcomptebancaire, PDO::PARAM_STR);
	$stmt -> bindParam(':idbanque', $idbanque, PDO::PARAM_STR);
	$stmt -> bindParam(':debutcontrat', $debutcontrat, PDO::PARAM_STR);
	$stmt -> bindParam(':fincontrat', $fincontrat, PDO::PARAM_STR);
	$stmt -> bindParam(':modepaiement', $modepaiement, PDO::PARAM_STR);
	$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	
	$success="Op&eacute;ration de mise &egrave; jour effectu&eacute;e avec succ&egrave;s";
	$error="";
	
	return $success.'*'.$error;;
}

function getidProfesseur($nom,$pdo)
{
	$req=(' SELECT professeur.id
			FROM professeur
			WHERE 
			professeur.nom=:nom');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getidProfesseur_($nom,$pdo)
{
	$req=(' SELECT professeur.id
			FROM professeur
			WHERE 
			professeur.nom like "%'.$nom.'%"');
	
	$resultat=0;
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateProfesseur($id,$nom,$titre,$contact,$fichier,$pdo)
{   
    $idProfesseur = getidProfesseur($nom,$pdo);
	if($idProfesseur==0)
	{
		$requete="INSERT INTO professeur(id,nom,titre,contact,signature) VALUES(:id,:nom,:titre,:contact,:signature)";
		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':id', $id, PDO::PARAM_STR);
		$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
		$stmt -> bindParam(':titre', $titre, PDO::PARAM_STR);
		$stmt -> bindParam(':contact', $contact, PDO::PARAM_STR);
		$stmt -> bindParam(':signature', $fichier, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Professeur cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Ce Professeur existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function DeleteProfesseur($id,$pdo)
{   
	$requete="UPDATE professeur SET statut=0 WHERE professeur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdateProfesseur($id,$nom,$titre,$contact,$fichier,$pdo)
{   
	$requete="UPDATE professeur SET nom=:nom,titre=:titre,contact=:contact,signature=:signature WHERE professeur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
	$stmt -> bindParam(':titre', $titre, PDO::PARAM_STR);
	$stmt -> bindParam(':contact', $contact, PDO::PARAM_STR);
	$stmt -> bindParam(':signature', $fichier, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidDomaine($nom,$pdo)
{
	$req=(' SELECT domaine.id
			FROM domaine
			WHERE 
			domaine.nom=:nom');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateDomaine($nom,$logo,$tel,$bp,$create_id,$pdo)
{   
    $idDomaine = getidDomaine($nom,$pdo);
	if($idDomaine==0)
	{
		$requete="INSERT INTO domaine(nom,logo,tel,bp,create_id,statut) VALUES(:nom,:logo,:tel,:bp,:create_id,1)";
		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
		$stmt -> bindParam(':logo', $logo, PDO::PARAM_STR);
		$stmt -> bindParam(':tel', $tel, PDO::PARAM_STR);
		$stmt -> bindParam(':bp', $bp, PDO::PARAM_STR);
		$stmt -> bindParam(':create_id', $create_id, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Domaine cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Ce Domaine existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function DeleteDomaine($id,$pdo)
{   
	$requete="DELETE FROM domaine WHERE domaine.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdateDomaine($id,$nom,$logo,$tel,$bp,$pdo)
{   
	$requete="UPDATE domaine SET nom=:nom,bp=:bp,tel=:tel,logo=:logo WHERE domaine.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':nom', $nom, PDO::PARAM_STR);
	$stmt -> bindParam(':logo', $logo, PDO::PARAM_STR);
	$stmt -> bindParam(':tel', $tel, PDO::PARAM_STR);
	$stmt -> bindParam(':bp', $bp, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	
	$success="Domaine modifi&eacute; avec succ&egrave;s";
	$error="";
	
	return $success.'*'.$error;
}

function getidNiveau($codeclasse,$pdo)
{
	$req=(' SELECT classe.idclasse
			FROM classe
			WHERE 
			classe.codeclasse=:codeclasse');
			
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt -> bindParam(':codeclasse', $codeclasse, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['idclasse'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateNiveau($iddomaine,$code,$nom,$create_id,$pdo)
{   
    $idNiveau = getidNiveau($code,$pdo);
	if($idNiveau==0)
	{
		$requete="INSERT INTO classe(codeclasse,nomclasse,iddomaine,create_id,statut) VALUES(:codeclasse,:nomclasse,:iddomaine,:create_id,1)";
		
		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':codeclasse', $code, PDO::PARAM_STR);
		$stmt -> bindParam(':nomclasse', $nom, PDO::PARAM_STR);
		$stmt -> bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
		$stmt -> bindParam(':create_id', $create_id, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Niveau/Option cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement. Le niveau/Option existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function DeleteNiveau($id,$pdo)
{   
	$requete="DELETE FROM classe WHERE classe.idclasse=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdateNiveau($id,$iddomaine,$code,$nom,$pdo)
{   
	$requete="UPDATE classe SET codeclasse=:codeclasse,nomclasse=:nomclasse,iddomaine=:iddomaine WHERE classe.idclasse=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':codeclasse', $code, PDO::PARAM_STR);
	$stmt -> bindParam(':nomclasse', $nom, PDO::PARAM_STR);
	$stmt -> bindParam(':iddomaine', $iddomaine, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	
	$success="Niveau/Option modifi&eacute; avec succ&egrave;s";
	$error="";
	
	return $success.'*'.$error;
}

function getidTrimestreExamen($libelle,$pdo)
{
	$req=(' SELECT position.idposition
			FROM position
			WHERE 
			position.libposition=:libposition');
			
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt -> bindParam(':libposition', $libelle, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['idposition'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateTrimestreExamen($code,$libelle,$create_id,$pdo)
{   
    $idTrimestreExamen = getidTrimestreExamen($libelle,$pdo);
	if($idTrimestreExamen=="")
	{
		$requete="INSERT INTO `position`(codeposition,libposition,datecreate,create_id) VALUES(:codeposition,:libposition,sysdate(),:create_id)";
		
		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':codeposition', $code, PDO::PARAM_STR);
		$stmt -> bindParam(':libposition', $libelle, PDO::PARAM_STR);
		$stmt -> bindParam(':create_id', $create_id, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Trimestre/Examen cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement. Le Trimestre/Examen existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function DeleteTrimestreExamen($id,$pdo)
{   
	$requete="DELETE FROM position WHERE position.idposition=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdateTrimestreExamen($id,$code,$libelle,$create_id,$pdo)
{   
	$requete="UPDATE position SET codeposition=:codeposition,libposition=:libposition,create_id=:create_id WHERE position.idposition=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':codeposition', $code, PDO::PARAM_STR);
	$stmt -> bindParam(':libposition', $libelle, PDO::PARAM_STR);
	$stmt -> bindParam(':create_id', $create_id, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	
	$success="Le Trimestre/Examen modifi&eacute; avec succ&egrave;s";
	$error="";
	
	return $success.'*'.$error;
}

function DeleteProfesseurMatiere($id,$pdo)
{   
	$requete="DELETE FROM professeursallemat WHERE professeursallemat.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidProfesseurSalleMat($idprof,$idsalle,$idmat,$idanneescolaire,$pdo)
{
	$req=(' SELECT professeursallemat.id
			FROM professeursallemat
			WHERE 
			professeursallemat.idprof=:idprof
			AND
			professeursallemat.idsalle=:idsalle
			AND
			professeursallemat.idmat=:idmat
			AND
			professeursallemat.idanneescolaire=:idanneescolaire
			AND
			professeursallemat.statut=1
			');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idmat', $idmat, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
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

function EviteDeleteProfesseur($idprof,$pdo)
{
	$req=(' SELECT professeursallemat.id
			FROM professeursallemat
			WHERE 
			professeursallemat.idprof=:idprof');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
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

function CreateProfesseurSalleMat($idprof,$idsalle,$idmat,$idanneescolaire,$idtitre,$pdo)
{   
    $idProfesseurSalleMat = getidProfesseurSalleMat($idprof,$idsalle,$idmat,$idanneescolaire,$pdo);
	if($idProfesseurSalleMat==0)
	{
		$requete="INSERT INTO professeursallemat(idprof,idsalle,idmat,idanneescolaire,idtitre,statut) VALUES(:idprof,:idsalle,:idmat,:idanneescolaire,:idtitre,1)";

		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt -> bindParam(':idmat', $idmat, PDO::PARAM_INT);
		$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt -> bindParam(':idtitre', $idtitre, PDO::PARAM_INT);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Enregistrement r&eacute;ussie avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement.";
	}
	return $success.'*'.$error;
}

function UpdateProfesseurSalleMat($id,$idprof,$idsalle,$idmat,$idtitre,$pdo)
{   
	$requete="	UPDATE professeursallemat SET 
						idprof=:idprof,
						idsalle=:idsalle,
						idmat=:idmat,
						idtitre=:idtitre 

			  	WHERE professeursallemat.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':idprof', $idprof, PDO::PARAM_INT);
	$stmt -> bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt -> bindParam(':idmat', $idmat, PDO::PARAM_INT);
	$stmt -> bindParam(':idtitre', $idtitre, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;

	$success="Mise &agrave; jour effectu&eacute; avec succ&egrave;s";
	$error="";

	return $success.'*'.$error;
}

function UpdateStatutProfesseurSalleMat($id,$statut,$pdo)
{   
	$requete="	UPDATE professeursallemat SET professeursallemat.statut=:statut 
				WHERE professeursallemat.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':statut', $statut, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidMatiere($code_matiere,$nom_matiere,$pdo)
{
	$req=(' SELECT  matiere.id_matiere
	
			FROM matiere
			WHERE 
			matiere.code_matiere=:code_matiere
			AND
			matiere.nom_matiere=:nom_matiere');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':code_matiere', $code_matiere, PDO::PARAM_STR);
	$stmt->bindParam(':nom_matiere', $nom_matiere, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id_matiere'];
	}
	else
	{
	    $resultat=0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateMatiere($code_matiere,$nom_matiere,$pdo)
{   
    $idMatiere = getidMatiere($code_matiere,$nom_matiere,$pdo);
	if($idMatiere==0)
	{
		$requete="INSERT INTO matiere(code_matiere,nom_matiere,statut_matiere) VALUES(:code_matiere,:nom_matiere,1)";
		$stmt = $pdo->prepare($requete);
		$stmt -> bindParam(':code_matiere', $code_matiere, PDO::PARAM_STR);
		$stmt -> bindParam(':nom_matiere', $nom_matiere, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Mati&egrave;re cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Cette mati&egrave;re existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error;
}

function UpdateMatiere($id,$code,$nom,$pdo)
{   
	$requete="UPDATE matiere SET code_matiere=:code_matiere, nom_matiere=:nom_matiere WHERE matiere.id_matiere=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':code_matiere', $code, PDO::PARAM_STR);
	$stmt -> bindParam(':nom_matiere', $nom, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	$success="Mati&egrave;re modifi&eacute;e avec succ&egrave;s";
	$error="";
	return $success.'*'.$error; 
}

function DeleteMatiere($id,$pdo)
{   
	$requete="DELETE FROM matiere WHERE matiere.id_matiere=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidMatClasse($idmat,$idclasse,$pdo)
{
	$req=(' SELECT matierecoefficient.id
			FROM matierecoefficient
			WHERE 
			matierecoefficient.idmatiere=:idmatiere
			AND
			matierecoefficient.idclasse=:idclasse
			AND
			matierecoefficient.coefficient<>0');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
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

function CreateMatClasse($idmat,$idclasse,$coef,$idanneescolaire,$etat,$pdo)
{   
    $id = getidMatClasse($idmat,$idclasse,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO matierecoefficient(idmatiere,idclasse,coefficient,idanneescolaire,statut,etat) VALUES(:idmatiere,:idclasse,:coefficient,:idanneescolaire,1,:etat)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idmatiere', $idmat, PDO::PARAM_INT);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt -> bindParam(':coefficient', $coef, PDO::PARAM_INT);
		$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt -> bindParam(':etat', $etat, PDO::PARAM_INT);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Enregistrement effectu&eacute; avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Echec d'enregistrement. Mati&egrave;re d&eacute;j&agrave; configur&eacute;e.";
	}
	return $success.'*'.$error;
}

function DeleteMatClasse($id,$pdo)
{   
	$requete="DELETE FROM matierecoefficient WHERE matierecoefficient.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdateCoefMatClasse($id,$statut,$pdo)
{   
	$requete="UPDATE matierecoefficient SET statut=:statut WHERE matierecoefficient.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':statut', $statut, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function UpdateCoefMatClasse_($id,$idmatiere,$idclasse,$coefficient,$etat,$pdo)
{   
	$requete="	UPDATE matierecoefficient 
				SET 
					idmatiere=:idmatiere,
					idclasse=:idclasse,
					coefficient=:coefficient,
					etat=:etat
				WHERE 
				matierecoefficient.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt -> bindParam(':coefficient', $coefficient, PDO::PARAM_INT);
	$stmt -> bindParam(':etat', $etat, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidSalle($codesalle,$pdo)
{
	$req=(' SELECT salle.id
			FROM salle
			WHERE 
			salle.codesalle=:codesalle');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':codesalle', $codesalle, PDO::PARAM_STR);
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

function CreateSalle($idclasse,$codesalle,$nomsalle,$pdo)
{   
    $id = getidSalle($codesalle,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO salle(idclasse,codesalle,nomsalle) VALUES(:idclasse,:codesalle,:nomsalle)";
		
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_STR);
		$stmt->bindParam(':codesalle', $codesalle, PDO::PARAM_STR);
		$stmt->bindParam(':nomsalle', $nomsalle, PDO::PARAM_STR);
		$stmt->execute();		
        $stmt->closeCursor();
		$stmt = NULL;
		$success="Salle de classe cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Cette salle de classe existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error;
}

function UpdateSalle($id,$idclasse,$codesalle,$nomsalle,$pdo)
{   
	$requete="UPDATE salle SET salle.idclasse=:idclasse,salle.codesalle=:codesalle,salle.nomsalle=:nomsalle WHERE salle.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
	$stmt -> bindParam(':codesalle', $codesalle, PDO::PARAM_STR);
	$stmt -> bindParam(':nomsalle', $nomsalle, PDO::PARAM_STR);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
	$success="Salle de classe modifi&eacute;e avec succ&egrave;s";
	$error="";
	return $success.'*'.$error;
}

function DeleteSalle($id,$pdo)
{   
	$requete="DELETE FROM salle WHERE salle.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidAnneeScolaire($libelle,$pdo)
{
	$req=(' SELECT anneescolaire.id
			FROM anneescolaire
			WHERE 
			anneescolaire.libelle=:libelle');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
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

function CreateAnneeScolaire($libelle,$pdo)
{   
    $id = getidAnneeScolaire($libelle,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO anneescolaire(libelle,statut) VALUES(:libelle,1)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':libelle', $libelle, PDO::PARAM_STR);
		$stmt -> execute();		
        $stmt -> closeCursor();
		$stmt = NULL;
		$success="Ann&eacute;e scolaire cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Cette ann&eacute;e scolaire existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error;
}

function DeleteAnneeScolaire($id,$pdo)
{   
	$requete="DELETE FROM anneescolaire WHERE anneescolaire.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function ClotureAnneeScolaire($id,$pdo)
{   
	$requete="UPDATE anneescolaire SET statut=0 WHERE anneescolaire.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function AnnulerClotureAnneeScolaire($id,$pdo)
{   
	$requete="UPDATE anneescolaire SET statut=1 WHERE anneescolaire.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function getidUser($login_user,$pdo)
{
	$req=(' SELECT utilisateur.id
			FROM utilisateur
			WHERE 
			utilisateur.login_user=:login_user');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':login_user', $login_user, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $id = $donnees['id'];
	}
	else
	{
	    $id=0;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $id;
}

function CreateUser($nom_user,$prenom_user,$login_user,$mtpass_user,$profil,$id,$typeutilisateur,$pdo)
{
    $id_user=getidUser($login_user,$pdo);
	if($id_user==0)
	{
		$requete="INSERT INTO utilisateur(id,nom_user,prenom_user,login_user,mtpass_user,profil,type) 
		VALUES(:id_user,:nom_user,:prenom_user,:login_user,:mtpass_user,:profil,:type)";
		
		$stmt = $pdo->prepare($requete);
		$stmt ->bindParam(':id_user', $id, PDO::PARAM_STR);
		$stmt ->bindParam(':nom_user', $nom_user, PDO::PARAM_STR);
		$stmt ->bindParam(':prenom_user', $prenom_user, PDO::PARAM_STR);
		$stmt ->bindParam(':login_user', $login_user, PDO::PARAM_STR);
		$stmt ->bindParam(':mtpass_user', $mtpass_user, PDO::PARAM_STR);
        $stmt ->bindParam(':profil', $profil, PDO::PARAM_STR);
		$stmt ->bindParam(':type', $typeutilisateur, PDO::PARAM_STR);
		$stmt ->execute();		
        $stmt ->closeCursor();
		$stmt =NULL;
		$success="Compte utilisateur cr&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Ce compte utilisateur existe d&eacute;j&agrave;";
	}
	return $success.'*'.$error; 
}

function UpdateUser($nom_user,$prenom_user,$login_user,$mtpass_user,$profil,$id,$typeutilisateur,$pdo)
{
	$requete="	UPDATE 
				utilisateur 
				SET 
				nom_user=:nom_user,
				prenom_user=:prenom_user,
				login_user=:login_user,
				mtpass_user=:mtpass_user,
				profil=:profil,
				type=:type
				WHERE
				utilisateur.id=:id_user";
	
	$stmt = $pdo->prepare($requete);
	$stmt ->bindParam(':id_user', $id, PDO::PARAM_STR);
	$stmt ->bindParam(':nom_user', $nom_user, PDO::PARAM_STR);
	$stmt ->bindParam(':prenom_user', $prenom_user, PDO::PARAM_STR);
	$stmt ->bindParam(':login_user', $login_user, PDO::PARAM_STR);
	$stmt ->bindParam(':mtpass_user', $mtpass_user, PDO::PARAM_STR);
    $stmt ->bindParam(':profil', $profil, PDO::PARAM_STR);
	$stmt ->bindParam(':type', $typeutilisateur, PDO::PARAM_STR);
	$stmt ->execute();		
    $stmt ->closeCursor();
	$stmt =NULL;
}

function DeleteUser($id,$pdo)
{   
	$requete="UPDATE utilisateur SET statut=0 WHERE utilisateur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function ActiveUser($id,$pdo)
{   
	$requete="UPDATE utilisateur SET statut=1 WHERE utilisateur.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function AppreciationAnnuelle($moyenne_trimestre_1)
{
	if($moyenne_trimestre_1=="4")
	{
		$appre = "T.Faible";						
	}
	
	if($moyenne_trimestre_1=="5" OR $moyenne_trimestre_1=="6")
	{
		$appre = "Faible";						
	}		
	
	if($moyenne_trimestre_1=="7")
	{
		$appre = "T.insuffisant";						
	}	
	
	if($moyenne_trimestre_1=="8" OR $moyenne_trimestre_1=="9")
	{
		$appre = "Insuffisant";						
	}				
	
	if($moyenne_trimestre_1=="10" OR $moyenne_trimestre_1=="11")
	{
		$appre = "Passable";						
	}			
	
	if($moyenne_trimestre_1=="12" OR $moyenne_trimestre_1=="13")
	{
		$appre = "Assez-Bien";						
	}
	
	if($moyenne_trimestre_1=="14" OR $moyenne_trimestre_1=="15")
	{
		$appre = "Bien";						
	}	
	
	if($moyenne_trimestre_1=="16" OR $moyenne_trimestre_1=="17")
	{
		$appre = "Tres Bien";						
	}		
	
	if($moyenne_trimestre_1=="18" OR $moyenne_trimestre_1=="19" OR $moyenne_trimestre_1=="20")
	{
		$appre = "Excellent";						
	}
	
	if($moyenne_trimestre_1=="3")
	{
		$appre = "T.Faible";						
	}	

	if($moyenne_trimestre_1=="1")
	{
		$appre = "T.Faible";						
	}

	if($moyenne_trimestre_1=="2")
	{
		$appre = "T.Faible";						
	}	

	if($moyenne_trimestre_1=="0")
	{
		$appre = "T.Faible";						
	}

	if($moyenne_trimestre_1=="")
	{
		$appre = "";						
	}

	return $appre; 
}

//
function getiddepense($idanneescolaire,$montant,$motif,$datedepense,$moderegl,$pdo)
{
	$req=(' SELECT depense.id
			FROM depense
			WHERE 
			depense.idanneescolaire=:idanneescolaire
			AND
			depense.montant=:montant
			AND
			depense.motif=:motif
			AND
			depense.datedepense=:datedepense
			AND
			depense.modregl=:moderegl');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
	$stmt->bindParam(':motif', $motif, PDO::PARAM_STR);
	$stmt->bindParam(':datedepense', $datedepense, PDO::PARAM_STR);
	$stmt->bindParam(':moderegl', $moderegl, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function AjouterDepense($idanneescolaire,$iduserauto,$montant,$motif,$datedepense,$idmodepaiement,$iduser,$pdo)
{
    $id = getiddepense($idanneescolaire,$montant,$motif,$datedepense,$idmodepaiement,$pdo);
	
	if($id==0)
	{
		$requete="INSERT INTO depense(idanneescolaire,montant,motif,statut,iduserajout,iduserauto,datedepense,modregl) 
					VALUES(:idanneescolaire,:montant,:motif,1,:iduserajout,:iduserauto,:datedepense,:idmodepaiement)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
		$stmt->bindParam(':motif', $motif, PDO::PARAM_STR);
		$stmt->bindParam(':datedepense', $datedepense, PDO::PARAM_STR);
		$stmt->bindParam(':idmodepaiement', $idmodepaiement, PDO::PARAM_INT);
		$stmt->bindParam(':iduserajout', $iduser, PDO::PARAM_INT);
		$stmt->bindParam(':iduserauto', $iduserauto, PDO::PARAM_INT);
		$stmt ->execute();		

		$stmt ->closeCursor();
		$stmt =NULL;
	}
}

function UpdateDepense($iddepense,$iduserauto,$montant,$motif,$datedepense,$idmodepaiement,$iduser,$pdo)
{
	//
	$requete="	UPDATE 
					depense
				SET 
					montant=:montant,
					motif=:motif,
					datedepense=:datedepense,
					modregl=:moderegl,
					iduserauto=:iduserauto
				WHERE
					depense.id=:iddepense";
	
	$stmt = $pdo->prepare($requete);
	$stmt ->bindParam(':iddepense', $iddepense, PDO::PARAM_STR);
	$stmt ->bindParam(':montant', $montant, PDO::PARAM_STR);
	$stmt ->bindParam(':motif', $motif, PDO::PARAM_STR);
	$stmt ->bindParam(':datedepense', $datedepense, PDO::PARAM_STR);
    $stmt ->bindParam(':moderegl', $idmodepaiement, PDO::PARAM_STR);
    $stmt->bindParam(':iduserauto', $iduserauto, PDO::PARAM_INT);
	$stmt ->execute();		
    $stmt ->closeCursor();
	$stmt =NULL;
}

function DeleteDepense($iddepense,$iduser,$pdo)
{
	$req=' UPDATE depense SET statut=0,iduserdelete=:iduserdelete WHERE depense.id=:iddepense';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':iddepense',$iddepense,PDO::PARAM_INT);
    $stmt ->bindParam(':iduserdelete',$iduser,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getAllUser($pdo)
{
	$req=(' SELECT  
					utilisateur.id as id,
	                utilisateur.nom_user as nom,
					utilisateur.prenom_user as prenom
					
			FROM utilisateur
			WHERE
			utilisateur.statut=1
			AND
			utilisateur.profil!="Professeur"');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="iduser" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'].' '.$donnees['prenom'];
			echo '<option value="'.$id.'">'.$nom.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllUserSelected($iduserauto,$pdo)
{
	$req=(' SELECT  
					utilisateur.id as id,
	                utilisateur.nom_user as nom,
					utilisateur.prenom_user as prenom
					
			FROM utilisateur
			WHERE
			utilisateur.statut=1
			AND
			utilisateur.profil!="Professeur"');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" required="required" name="iduser" >
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'].' '.$donnees['prenom'];
			if($id==$iduserauto)
			{
				echo '<option value="'.$id.'" selected="selected">'.$nom.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'">'.$nom.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getidversement($idanneescolaire,$num,$pdo)
{
	$req=(' SELECT versement.id
			FROM versement
			WHERE 
			versement.idanneescolaire=:idanneescolaire
			AND
			versement.num=:num');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':num',$num, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}
//
function getidversementdetail($idversement,$idpaiementfrais,$pdo)
{
	$req=(' SELECT versementdetail.id
			FROM versementdetail
			WHERE 
			versementdetail.idversement=:idversement
			AND
			versementdetail.idpaiementfrais=:idpaiementfrais');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idversement', $idversement, PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementfrais',$idpaiementfrais, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}
//
function AjouterVersement($idanneescolaire,$iduserauto,$num,$dateversement,$idmodepaiement,$iduser,$pdo)
{
    $id = getidversement($idanneescolaire,$num,$pdo);
	if($id==0)
	{
		$requete="INSERT INTO versement(idcompte,idanneescolaire,num,dateversement,iduserajout,iduserauto) 
					VALUES(:idcompte,:idanneescolaire,:num,:dateversement,:iduserajout,:iduserauto)";
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idcompte', $idmodepaiement, PDO::PARAM_STR);
		$stmt->bindParam(':num', $num, PDO::PARAM_STR);
		$stmt->bindParam(':dateversement', $dateversement, PDO::PARAM_STR);
		$stmt->bindParam(':iduserajout', $iduser, PDO::PARAM_INT);
		$stmt->bindParam(':iduserauto', $iduserauto, PDO::PARAM_INT);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
		$id = getidversement($idanneescolaire,$num,$pdo);
		$error="";
		$success="Op&eacute;ration d'enregistrement effectu&eacute;e avec succ&egrave;s";
	}
	else
	{
		$error="Echec d'enregistrement. Le num&eacute;ro du bordereau existe d&eacute;j&agrave;.";
		$success="";
	}
	return $id.'*'.$success.'*'.$error;
}
//
function UpdateVersement($idversement,$iduserauto,$num,$dateversement,$idmodepaiement,$pdo)
{
	$requete="	UPDATE versement
				SET 
					versement.num=:num,
					versement.dateversement=:dateversement,
					versement.idcompte=:idcompte,
					versement.iduserauto=:iduserauto
				WHERE
				versement.id=:idversement";
	
	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':idversement', $idversement, PDO::PARAM_INT);
	$stmt->bindParam(':idcompte', $idmodepaiement, PDO::PARAM_STR);
	$stmt->bindParam(':num', $num, PDO::PARAM_STR);
	$stmt->bindParam(':dateversement', $dateversement, PDO::PARAM_STR);
	$stmt->bindParam(':iduserauto', $iduserauto, PDO::PARAM_INT);
	$stmt ->execute();		
    $stmt ->closeCursor();
	$stmt =NULL;
	//
	DeleteVersementDetail($idversement,$pdo);
	//
	$error="";
	$success="Op&eacute;ration de modification effectu&eacute;e avec succ&egrave;s";
	//
	return $success.'*'.$error;
}
//
function AjouterVersementDetail($idpaiementfrais,$idversement,$pdo)
{
    $id = getidversementdetail($idversement,$idpaiementfrais,$pdo);
	
	if($id==0)
	{
		$requete="INSERT INTO versementdetail(idversement,idpaiementfrais) VALUES(:idversement,:idpaiementfrais)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idversement', $idversement, PDO::PARAM_INT);
		$stmt->bindParam(':idpaiementfrais', $idpaiementfrais, PDO::PARAM_STR);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
	}
}
//
function DeleteVersement($idversement,$iduser,$pdo)
{
	$req=' UPDATE versement SET statut=0,iduserdelete=:iduserdelete WHERE versement.id=:idversement';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idversement',$idversement,PDO::PARAM_INT);
    $stmt ->bindParam(':iduserdelete',$iduser,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}
//
function DeleteVersementDetail($idversement,$pdo)
{
	$req=' DELETE FROM versementdetail WHERE versementdetail.idversement=:idversement';
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idversement',$idversement,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getSalleNbreEleve($idanneescolaire,$idsalle,$pdo)
{
	$req=(' SELECT count(distinct elevesalle.id) as nbre
            FROM eleveanneescolaire,elevesalle
            WHERE
            eleveanneescolaire.id=elevesalle.ideleve
            AND
            eleveanneescolaire.idanneescolaire=:idanneescolaire
            AND
            elevesalle.idsalle=:idsalle
            AND
            elevesalle.statut=1');
	$nbre = 0;
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
    $stmt->execute();
	if($donnees = $stmt->fetch())
	{
		$nbre = $donnees['nbre'];
	}
	return $nbre; 
}

function codebarre($num) 
{
    include('pi_barcode.php');
	
	$code=sprintf('%10d',$num);
	
	// instanciation
	$bc = new pi_barcode();
	  
	//Le code a générer
	//$code=$code;
	$bc->setCode($code);
	// Type de code : EAN, UPC, C39...
	$bc->setType('C128');
	//taille de l'image (hauteur, largeur, zone calme)
	//Hauteur mini=15px
	//Largeur de l'image (ne peut être inférieure a
	//l'espace nécessaire au code barres
	//Zones Calmes (mini=10px) à gauche et à droite
	//des barres
	$bc->setSize(30,150,10);
	  
	// Texte sous les barres :
	//    'AUTO' : affiche la valeur du codes barres
	//    '' : n'affiche pas de texte sous le code
	//    'texte a afficher' : affiche un texte libre
	//        sous les barres
	//$bc->setText('AUTO');
	  
	// Si elle est appelée, cette méthode désactive
	// l'impression du Type de code (EAN, C128...)
	$bc->hideCodeType();
	  
	// Couleurs des Barres, et du Fond au
	// format '#rrggbb'
	$bc->setColors('#123456','#F9F9F9');
	// Type de fichier : GIF ou PNG (par défaut)
	$bc->setFiletype('PNG');
	  
	// envoie l'image dans un fichier
	$bc->writeBarcodeFile("codebarre/".trim($num).'.png');
	// ou envoie l'image au navigateur
	//$bc->showBarcodeImage();
	  
	/* ***************************************** */
	return $code;
}

//ABSENCE(S)
function DeleteAbsences($idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' DELETE FROM absences WHERE absences.idsalle=:idsalle AND absences.idposition=:idposition AND absences.idanneescolaire=:idanneescolaire');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function CreateAbsences($idelevesalle,$nbreabsence,$idposition,$idsalle,$idanneescolaire,$idusercreate,$pdo)
{
	//
	if(getidAbsencesEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$pdo)==0)
	{
		$requete="INSERT INTO absences(idelevesalle,idsalle,idanneescolaire,idposition,nbreabsence,dateenreg,idusercreate) 
					VALUES(:idelevesalle,:idsalle,:idanneescolaire,:idposition,:nbreabsence,sysdate(),:idusercreate)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':nbreabsence', $nbreabsence, PDO::PARAM_INT);
		$stmt->bindParam(':idusercreate', $idusercreate, PDO::PARAM_INT);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
	}
}

function getidAbsencesEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$pdo)
{
	$req=(' SELECT absences.id
			FROM absences
			WHERE 
			absences.idelevesalle=:idelevesalle
			AND
			absences.idposition=:idposition
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idsalle=:idsalle');
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getNbreAbsencesEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$pdo)
{
	$req=(' SELECT distinct absences.nbreAbsence
			FROM absences
			WHERE 
			absences.idelevesalle=:idelevesalle
			AND
			absences.idposition=:idposition
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idsalle=:idsalle');
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nbreAbsence'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getNbreAbsenceForAnneescolaire($idelevesalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT absences.nbreabsence as nbreabsences
			FROM absences
			WHERE
			absences.idposition=:idposition
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idelevesalle=:ideleve');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':ideleve',$idelevesalle,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$nbreabsences = $donnees['nbreabsences'];
	}
	else
	{
		$nbreabsences="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $nbreabsences;
}

//
function CreateDonneePaieCorpsAdministratif($idpers,$salairebase,$sursalaire,$indemnitefonction,$primesujetion,$primeinterim,$indemnitelogement,$indemnitetransport,$primecaisse,$salairebrute,$idanneescolaire,$create_id,$pdo)
{
	
	try
	{
	
	    $pdo->beginTransaction();
		
		$requete=(' SELECT count(*) as exist
		
					FROM professeurdonneepaie
					WHERE 
					professeurdonneepaie.idpers=:idpers
					AND
					professeurdonneepaie.salairebrute=:salairebrute');	
							
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':salairebrute', $salairebrute, PDO::PARAM_STR);
		$stmt->bindParam(':idpers', $idpers, PDO::PARAM_STR);
		$stmt->execute();		
		if ($donnees = $stmt->fetch())
		{
			$exist = $donnees['exist'];
		}	
		$stmt->closeCursor();

		if($exist==0)
		{
			$req='INSERT INTO professeurdonneepaie(idpers,salairebase,sursalaire,indemnitefonction,primesujetion,primeinterim,indemnitelogement,indemnitetransport,primecaisse,salairebrute,idanneescolaire,create_id,statut) 
					VALUES(:idpers,:salairebase,:sursalaire,:indemnitefonction,:primesujetion,:primeinterim,:indemnitelogement,:indemnitetransport,:primecaisse,:salairebrute,:idanneescolaire,:create_id,1)';

			$stmt = $pdo->prepare($req);
			$stmt ->bindParam(':idpers', $idpers);
			$stmt ->bindParam(':salairebase', $salairebase);
			$stmt ->bindParam(':sursalaire', $sursalaire);
			$stmt ->bindParam(':indemnitefonction', $indemnitefonction);
			$stmt ->bindParam(':primesujetion', $primesujetion);
			$stmt ->bindParam(':primeinterim', $primeinterim);		
			$stmt ->bindParam(':indemnitelogement', $indemnitelogement);
			$stmt ->bindParam(':indemnitetransport', $indemnitetransport);
			$stmt ->bindParam(':primecaisse', $primecaisse);
			$stmt ->bindParam(':salairebrute', $salairebrute);
			$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
			$stmt ->bindParam(':create_id', $create_id);
			$stmt ->execute();
			$stmt ->closeCursor();
		
			$pdo->commit();
			//
			$success="Op&eacute;ration d'enregistrement effectu&eacute;e avec succ&egrave;s";
			$error="";
		
			return $success.'*'.$error;
		}
		else
		{
			$success="";
			$error="Echec de l'op&eacute;ration d'enregistrement. Les donn&eacute;es de paie existent d&eacute;j&agrave;.";
		
			return $success.'*'.$error;
		}
		
	}
	catch(Exception $e) //en cas d'erreur
	{
	    //on annule la transation
		$pdo->rollback();
		//on affiche un message d'erreur ainsi que les erreurs
		//echo 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
		//echo 'Erreur : '.$e->getMessage().'<br />';
		//echo 'N° : '.$e->getCode();
		//on arrête l'exécution s'il y a du code après
		//exit();
		
		$success="";
		$error="Echec de l'op&eacute;ration d'enregistrement.";
		
		return $success.'*'.$error;
	}
}

function UpdateDonneePaieCorpsAdministratif($id,$idpers,$salairebase,$sursalaire,$indemnitefonction,$primesujetion,$primeinterim,$indemnitelogement,$indemnitetransport,$primecaisse,$salairebrute,$idanneescolaire,$pdo)
{
	
	try
	{
	
	    $pdo->beginTransaction();
		
		$req='	UPDATE 	professeurdonneepaie 
						SET
						salairebase=:salairebase,
						sursalaire=:sursalaire,
						indemnitefonction=:indemnitefonction,
						primesujetion=:primesujetion,
						primeinterim=:primeinterim,
						indemnitelogement=:indemnitelogement,
						indemnitetransport=:indemnitetransport,
						primecaisse=:primecaisse,
						salairebrute=:salairebrute,
						idanneescolaire=:idanneescolaire,
						idpers=:idpers
						
				WHERE
				professeurdonneepaie.id=:id';

		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id', $id);
		$stmt ->bindParam(':idpers', $idpers);
		$stmt ->bindParam(':salairebase', $salairebase);
		$stmt ->bindParam(':sursalaire', $sursalaire);
		$stmt ->bindParam(':indemnitefonction', $indemnitefonction);
		$stmt ->bindParam(':primesujetion', $primesujetion);
		$stmt ->bindParam(':primeinterim', $primeinterim);		
		$stmt ->bindParam(':indemnitelogement', $indemnitelogement);
		$stmt ->bindParam(':indemnitetransport', $indemnitetransport);
		$stmt ->bindParam(':primecaisse', $primecaisse);
		$stmt ->bindParam(':salairebrute', $salairebrute);
		$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
		$stmt ->execute();
		$stmt ->closeCursor();
		
	    $pdo->commit();
		//
		$success="Op&eacute;ration de mise &agrave; jour effectu&eacute;e avec succ&egrave;s";
		$error="";
		
		return $success.'*'.$error;
	}
	catch(Exception $e) //en cas d'erreur
	{
	    //on annule la transation
		$pdo->rollback();
		//on affiche un message d'erreur ainsi que les erreurs
		echo 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
		echo 'Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		//on arrête l'exécution s'il y a du code après
		//exit();
		
		$success="Echec de l'op&eacute;ration de mise &agrave; jour.";
		$error="";
		
		return $success.'*'.$error;
	}
}

function DesactiverPersonnelDonneesPaie($id,$iduser,$pdo)
{   

	$requete="UPDATE professeurdonneepaie SET statut=0,create_id=:create_id WHERE professeurdonneepaie.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> bindParam(':create_id', $iduser, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

function AnnulerDesactiverPersonnelDonneesPaie($id,$pdo)
{   

	$requete="UPDATE professeurdonneepaie SET statut=1,create_id=null WHERE professeurdonneepaie.id=:id";
	
	$stmt = $pdo->prepare($requete);
	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();		
	$stmt -> closeCursor();
	$stmt = NULL;
}

//
function CreateDonneePaieCorpsProfessorat($idpers,$vol_horaire,$cout_honoraire,$idanneescolaire,$create_id,$pdo)
{
	
	try
	{
	
	    $pdo->beginTransaction();
		
		$requete=(' SELECT count(*) as exist
		
					FROM professeurdonneepaie
					WHERE 
					professeurdonneepaie.idpers=:idpers
					AND
					professeurdonneepaie.cout_honoraire=:cout_honoraire
					AND
					professeurdonneepaie.statut=1');	
							
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':cout_honoraire', $cout_honoraire, PDO::PARAM_STR);
		$stmt->bindParam(':idpers', $idpers, PDO::PARAM_STR);
		$stmt->execute();		
		if ($donnees = $stmt->fetch())
		{
			$exist = $donnees['exist'];
		}	
		$stmt->closeCursor();

		if($exist==0)
		{
			$req='INSERT INTO professeurdonneepaie(idpers,cout_honoraire,idanneescolaire,create_id,statut) VALUES(:idpers,:cout_honoraire,:idanneescolaire,:create_id,1)';

			$stmt = $pdo->prepare($req);
			$stmt ->bindParam(':idpers', $idpers);
			$stmt ->bindParam(':cout_honoraire', $cout_honoraire);
			$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
			$stmt ->bindParam(':create_id', $create_id);
			$stmt ->execute();
			$stmt ->closeCursor();
		
			$pdo->commit();
			//
			$success="Op&eacute;ration d'enregistrement effectu&eacute;e avec succ&egrave;s";
			$error="";
		
			return $success.'*'.$error;
		}
		else
		{
			$success="";
			$error="Echec de l'op&eacute;ration d'enregistrement. Les donn&eacute;es de paie existent d&eacute;j&agrave;.";
		
			return $success.'*'.$error;
		}
		
	}
	catch(Exception $e) //en cas d'erreur
	{
	    //on annule la transation
		$pdo->rollback();
		//on affiche un message d'erreur ainsi que les erreurs
		//echo 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
		//echo 'Erreur : '.$e->getMessage().'<br />';
		//echo 'N° : '.$e->getCode();
		//on arrête l'exécution s'il y a du code après
		//exit();
		
		$success="";
		$error="Echec de l'op&eacute;ration d'enregistrement.";
		
		return $success.'*'.$error;
	}
}

function UpdateDonneePaieCorpsProfessorat($id,$idpers,$vol_horaire,$cout_honoraire,$idanneescolaire,$pdo)
{
	
	try
	{
	
	    $pdo->beginTransaction();
		
		$req='	UPDATE 	professeurdonneepaie 
						SET
						vol_horaire=:vol_horaire,
						cout_honoraire=:cout_honoraire,
						idanneescolaire=:idanneescolaire,
						idpers=:idpers
						
				WHERE
				professeurdonneepaie.id=:id';

		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id', $id);
		$stmt ->bindParam(':idpers', $idpers);
		$stmt ->bindParam(':vol_horaire', $vol_horaire);
		$stmt ->bindParam(':cout_honoraire', $cout_honoraire);
		$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
		$stmt ->execute();
		$stmt ->closeCursor();
		
	    $pdo->commit();
		//
		$success="Op&eacute;ration de mise &agrave; joutr effectu&eacute;e avec succ&egrave;s";
		$error="";
		
		return $success.'*'.$error;
	}
	catch(Exception $e) //en cas d'erreur
	{
	    //on annule la transation
		$pdo->rollback();
		//on affiche un message d'erreur ainsi que les erreurs
		echo 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
		echo 'Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		//on arrête l'exécution s'il y a du code après
		//exit();
		
		$success="Echec de l'op&eacute;ration de mise &agrave; jour.";
		$error="";
		
		return $success.'*'.$error;
	}
}

function ListeEntreeSortieAA($pdo)
{
	
	$req=(' SELECT * FROM entreesortie ORDER BY entreesortie.id DESC');	
		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			echo $ligne;
			
		}
}

function ListTranche($pdo)
{
	
	$req=(' SELECT  
					distinct 
					paiementtranche.id AS idpaiementtranche,
					paiementtranche.libelle AS libellepaiementtranche
					
			FROM paiementtranche
			WHERE
			paiementtranche.statut=1

			ORDER BY paiementtranche.id ASC');	
    ?>
	<br/>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;font-size:14px;" class="btn btn-info btn-xs">
	::: Paiement par tranche :::</label><br/><br/>
	<table class="table table-striped projects"width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th>
				<th>Tranche</th>
				<th>Montant total de la tranche</th>
				<th>Date Echéance
			</tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> execute();	
		$ligne = 0;
		while($donnees = $stmt->fetch())
		{
			
			$ligne++;
			$idpaiementtranche = $donnees['idpaiementtranche'];
			$libellepaiementtranche = $donnees['libellepaiementtranche'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" checked="checked" name="idpaiementtranche<?php echo $ligne;?>" value="<?php echo $idpaiementtranche;?>"/>					
				</td>
				<td>
					<a><?php echo $libellepaiementtranche;?></a>						
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="number" class="form-control form-control-grand" name="montant<?php echo $ligne;?>" id="montant<?php echo $ligne;?>" style="border-radius:6px;"/>
						</div>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="date" class="form-control form-control-grand" name="dateecheance<?php echo $ligne;?>" style="border-radius:6px;"/>
						</div>
					</div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="totalligne" value="<?php echo $ligne;?>"/>
	<?php
}

function ListTrancheSelected($idpaiementtypeclasse,$pdo)
{
	
	$req=(' SELECT  
					distinct 
					paiementtranche.id AS idpaiementtranche,
					paiementtranche.libelle AS libellepaiementtranche,
					paiementtypeclassetranche.montant AS montant,
					paiementtypeclassetranche.dateecheance AS dateecheance
				
			FROM paiementtranche,paiementtypeclassetranche
			WHERE
			paiementtranche.id=paiementtypeclassetranche.idpaiementtranche
			AND
			paiementtypeclassetranche.idpaiementtypeclasse=:idpaiementtypeclasse
			AND
			paiementtranche.statut=1

			ORDER BY paiementtranche.id ASC');	
    ?>
	<br/>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;font-size:14px;" class="btn btn-info btn-xs">
	::: Paiement par tranche :::</label><br/><br/>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th>
				<th>Tranche</th>
				<th>Montant total de la tranche</th>
				<th>Date Echéance
			</tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':idpaiementtypeclasse', $idpaiementtypeclasse);
		$stmt -> execute();	
		$ligne = 0;
		while($donnees = $stmt->fetch())
		{
			
			$ligne++;
			$idpaiementtranche = $donnees['idpaiementtranche'];
			$libellepaiementtranche = $donnees['libellepaiementtranche'];
			$montant = $donnees['montant'];
			$dateecheance = $donnees['dateecheance'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" checked="checked" name="idpaiementtranche<?php echo $ligne;?>" value="<?php echo $idpaiementtranche;?>"/>					
				</td>
				<td>
					<a><?php echo $libellepaiementtranche;?></a>						
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="number" class="form-control form-control-grand" name="montant<?php echo $ligne;?>" value="<?php echo $montant;?>" style="border-radius:6px;"/>
						</div>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="date" class="form-control form-control-grand" name="dateecheance<?php echo $ligne;?>" value="<?php echo $dateecheance;?>" style="border-radius:6px;"/>
						</div>
					</div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="totalligne" value="<?php echo $ligne;?>"/>
	<?php
}

function genererNumeroRecu($libelleanneescolaire, $idanneescolaire, $pdo) {
	
    // Séparer l'année scolaire en deux parties
    list($anneeDebut, $anneeFin) = explode('-', $libelleanneescolaire);
    $shortDebut = substr($anneeDebut, -2);
    $shortFin   = substr($anneeFin, -2);
    // Préfixe attendu dans la base (exemple : /25-26)
    $suffixe = '/' . $shortDebut . '-' . $shortFin;
    // Récupérer le dernier numéro existant pour cette année scolaire
    $req=(' SELECT count(distinct paiementfrais.id) as nbre 
	        FROM paiementfrais 
			WHERE paiementfrais.idanneescolaire=:idanneescolaire');
	$resultat = "";		
	$stmt = $pdo->prepare($req);
	$stmt->execute();
	$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['nbre']+1;
	}
    // Formater le compteur sur 6 chiffres
    $numeroFormate = str_pad($resultat, 6, '0', STR_PAD_LEFT);

    // Retourner le numéro complet
    return $numeroFormate . $suffixe;
}

function getlibelletypepaiement($id,$pdo)
{
	$req=(' SELECT paiementtype.libelle
			FROM paiementtype
			WHERE 
			paiementtype.id=:id');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt->execute();
	$resultat="";
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['libelle'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getNbreNouveauInscrit($idanneescolaire,$idclasse,$pdo)
{
	$req=(' SELECT count(distinct eleveanneescolaire.id) as nbrenouveau
			FROM eleveanneescolaire,elevestatutclasse,elevestatutetablissement
			WHERE 
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			elevestatutetablissement.id=1
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt_->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt_->execute();
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['nbrenouveau'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreOuiBoursier($idanneescolaire,$idclasse,$pdo)
{
	$req=(' SELECT count(distinct eleveanneescolaire.id) as nbreboursier	
			FROM eleveanneescolaire	
			WHERE 
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			(eleveanneescolaire.boursier is not null AND eleveanneescolaire.boursier!="")
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt_->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt_->execute();
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['nbreboursier'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getMontantPaiementTypeClasseOuiBoursier($idanneescolaire,$idclasse,$idpaiementtype,$montantclasse,$pdo)
{
	$req=(' SELECT distinct eleveanneescolaire.boursier as boursier
			FROM eleveanneescolaire	
			WHERE 
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->execute();
	$resultat=0;
	while($donnees = $stmt->fetch())
	{
		$boursier = $donnees['boursier'];
		if (is_numeric($boursier)) {
			$boursier = (int)$boursier;
		} else {
			$boursier = 0;
		}
		
	    $resultat = $resultat+($montantclasse - $boursier);
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMontantPaiementTypeClasseOuiBoursier_($idanneescolaire,$idclasse,$idpaiementtype,$ideleveanneescolaire,$pdo)
{
	$req=(' SELECT paiementtypeclasse.montant as montant,paiementtypeclasse.id as id,paiementtypeclasse.remise as remise
	
			FROM paiementtypeclasse
			WHERE 
			paiementtypeclasse.idclasse=:idclasse
			AND
			paiementtypeclasse.idpaiementtype=:idpaiementtype
			AND
			paiementtypeclasse.statut=1
			AND
			paiementtypeclasse.ideleveanneescolaire=:ideleveanneescolaire
			AND
			paiementtypeclasse.idanneescolaire=:idanneescolaire
			
			ORDER BY paiementtypeclasse.id DESC LIMIT 0,1');
							
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtype',$idpaiementtype,PDO::PARAM_INT);
	$stmt->bindParam(':ideleveanneescolaire',$ideleveanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['montant'].'*'.$donnees['id'].'*'.$donnees['remise'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMontantPaiementFraisOuiBoursier($idclasse,$idanneescolaire,$idpaiementtype,$pdo)
{
	$req=(' SELECT sum(paiementfrais.montant) as montant
	
			FROM eleveanneescolaire,paiementtypeclasse,paiementfrais
			WHERE 
			eleveanneescolaire.id=paiementtypeclasse.ideleveanneescolaire
			AND
			paiementtypeclasse.idpaiementtype=:idpaiementtype
			AND
			paiementfrais.idpaiementtypeclasse=paiementtypeclasse.id
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.boursier="Oui"
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtype',$idpaiementtype,PDO::PARAM_INT);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['montant'];
		if($resultat=="")
		{
			$resultat=0;
		}
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getNbreNonBoursier($idanneescolaire,$idclasse,$pdo)
{
	$req=(' SELECT count(distinct eleveanneescolaire.id) as nbreboursier
			FROM eleveanneescolaire
			WHERE 
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			(eleveanneescolaire.boursier="" OR eleveanneescolaire.boursier is null)
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nbreboursier'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMontantPaiementFraisAnneeScolaire($id,$idanneescolaire,$pdo)
{
	$req=(' SELECT sum(paiementfrais.montant) as Montant
			FROM paiementfrais
			WHERE 
			paiementfrais.idanneescolaire=:idanneescolaire
			AND
			paiementfrais.idpaiementtypeclasse=:id
			AND
			paiementfrais.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
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

function genererNumero($dernierNumero) {
	
    // Récupérer l'année courante sur 2 chiffres
    $anneeCourante = date("y"); // exemple : 25 pour 2025

    // Séparer le numéro et l'année du dernier enregistrement
    list($compteur, $annee) = explode("-", $dernierNumero);

    if ($annee == $anneeCourante) {
        // Même année → on incrémente
        $compteur = intval($compteur) + 1;
    } else {
        // Nouvelle année → on réinitialise à 1
        $compteur = 1;
    }

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

function getMontantPaiementTypeClasseEffectif($idanneescolaire,$idclasse,$idpaiementtype,$montantclasse,$pdo)
{
	$req=(' SELECT distinct eleveanneescolaire.id as id,eleveanneescolaire.boursier as boursier
			FROM eleveanneescolaire	
			WHERE 
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->execute();
	$resultat = 0;
	$nbre_non_boursier = 0;
	$nbre_oui_boursier = 0;
	while($donnees = $stmt->fetch())
	{
		$boursier = $donnees['boursier'];
		if (is_numeric($boursier)) {
			$boursier = (int)$boursier;
			$resultat = $resultat+($montantclasse - $boursier);
			$nbre_oui_boursier++;
		} else {
			$boursier = 0;
			$resultat = $resultat+($montantclasse - $boursier);
			$nbre_non_boursier++;
		}
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat.'*'.$nbre_non_boursier.'*'.$nbre_oui_boursier;
}

function prendreSiSuperieurA10(int $nombre): ?int {
    return ($nombre > 10) ? $nombre : null;
}

function nombreEnLettre($nombre) {
    $unites = [
        0=>"zéro",1=>"un",2=>"deux",3=>"trois",4=>"quatre",5=>"cinq",
        6=>"six",7=>"sept",8=>"huit",9=>"neuf",10=>"dix",11=>"onze",
        12=>"douze",13=>"treize",14=>"quatorze",15=>"quinze",16=>"seize",
        17=>"dix-sept",18=>"dix-huit",19=>"dix-neuf"
    ];

    $dizaines = [
        20=>"vingt",30=>"trente",40=>"quarante",50=>"cinquante",
        60=>"soixante",70=>"soixante-dix",80=>"quatre-vingt",90=>"quatre-vingt-dix"
    ];

    // Fonction récursive
    $convertir = function($n) use (&$convertir, $unites, $dizaines) {
        if ($n < 20) return $unites[$n];
        if ($n < 100) {
            $d = (int)($n/10)*10;
            $u = $n % 10;
            return $dizaines[$d] . ($u ? "-".$unites[$u] : "");
        }
        if ($n < 1000) {
            $c = (int)($n/100);
            $r = $n % 100;
            $txt = ($c > 1 ? $unites[$c]." cent" : "cent");
            return $txt . ($r ? " ".$convertir($r) : "");
        }
        if ($n < 1000000) {
            $m = (int)($n/1000);
            $r = $n % 1000;
            $txt = ($m > 1 ? $convertir($m)." mille" : "mille");
            return $txt . ($r ? " ".$convertir($r) : "");
        }
        return (string)$n; // au-delà → brut
    };

    // Séparer entier/décimal
    $parties = explode('.', (string)$nombre);
    $entier = (int)$parties[0];
    $entierLettre = $convertir($entier);

    if (count($parties) === 2 && (int)$parties[1] > 0) {
        $decimal = (int) rtrim($parties[1], "0");
        $decimalLettre = $convertir($decimal);
        return $entierLettre . " virgule " . $decimalLettre;
    }

    return $entierLettre;
}

function getNbreEleveParClasseParSexe($statut,$sexeeleve,$idclasse,$idposition,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreEleve
			FROM eleve,eleveanneescolaire,elevesalle,salle,classe,elevestatutclasse,elevestatutetablissement,bulletin
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleve.sexe_eleve=:sexeeleve
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=:statut
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			elevesalle.statut=:statut
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.ideleve=elevesalle.id
			AND
			bulletin.idsalle=salle.id
			AND
			salle.id=:idclasse
			
			GROUP BY salle.id');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt_->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt_->bindParam(':sexeeleve',$sexeeleve,PDO::PARAM_STR);
	$stmt_->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt_->bindParam(':statut',$statut,PDO::PARAM_INT);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreEleve'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreMoyenneParClasseParSexe($idanneescolaire,$idposition,$idclasse,$moyenne,$sexe,$pdo)
{
	
	$req=(' SELECT count(distinct eleve.id_eleve) as NbreEleve
			FROM eleve,eleveanneescolaire,elevesalle,salle,classe,elevestatutclasse,elevestatutetablissement,bulletin
			WHERE 
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleve.sexe_eleve=:sexeeleve
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			elevesalle.statut=1
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.ideleve=elevesalle.id
			AND
			bulletin.moyenne_gene>=:moyenne
			AND
			bulletin.idsalle=salle.id
			AND
			salle.id=:idclasse
			
			GROUP BY salle.id');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt_->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt_->bindParam(':sexeeleve',$sexe,PDO::PARAM_STR);
	$stmt_->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt_->bindParam(':moyenne',$moyenne,PDO::PARAM_STR);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['NbreEleve'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getEleveMoyennePlusForteSalle($idanneescolaire,$idposition,$idclasse,$pdo)
{
	
	$req=(' SELECT distinct eleve.nom_eleve,eleve.prenom_eleve
			FROM eleve,eleveanneescolaire,elevesalle,salle,bulletin
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.id=bulletin.ideleve
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=salle.id
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.id=:idclasse

			ORDER BY bulletin.moyenne_gene DESC LIMIT 0,1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$resultat="";
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getEleveMoyennePlusFaibleSalle($idanneescolaire,$idposition,$idclasse,$pdo)
{
	$req=(' SELECT distinct eleve.nom_eleve,eleve.prenom_eleve
			FROM eleve,eleveanneescolaire,elevesalle,salle,bulletin
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.id=bulletin.ideleve
			AND
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=salle.id
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.id=:idclasse

			ORDER BY bulletin.moyenne_gene ASC LIMIT 0,1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$resultat="";
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getnumeroEvaluation($idelevesalle,$idposition,$idanneescolaire,$numerotable,$numeroanonymat,$pdo)
{
	$req=(' SELECT numero_evaluation.id as id
			FROM numero_evaluation
			WHERE 
			numero_evaluation.idelevesalle=:idelevesalle
			AND
			numero_evaluation.idposition=:idposition
			AND
			numero_evaluation.idanneescolaire=:idanneescolaire
			AND
			numero_evaluation.numero_anonymat=:numero_anonymat
			AND
			numero_evaluation.numero_table=:numero_table');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idelevesalle',$idelevesalle,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':numero_table',$numerotable,PDO::PARAM_STR);
	$stmt->bindParam(':numero_anonymat',$numeroanonymat,PDO::PARAM_STR);
	$stmt->execute();
	$resultat="";
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function DeleteNumeroEvaluation($id,$pdo)
{
	$req=(' DELETE FROM numero_evaluation WHERE numero_evaluation.id=:id');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $idsalle, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function CreateNumeroEvaluation($idelevesalle,$idperiode,$numerotable,$numeroanonymat,$idusercreate,$pdo)
{
	//
	if(getnumeroEvaluation($idelevesalle,$idperiode,$numerotable,$numeroanonymat,$pdo)==0)
	{
		$requete="INSERT INTO numero_evaluation(idelevesalle,idposition,idanneescolaire,numero_anonymat,numero_table,datecreate,idusercreate) 
					VALUES(:idelevesalle,:idposition,:idanneescolaire,:numero_anonymat,:numero_table,sysdate(),:idusercreate)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
		$stmt->bindParam(':numero_anonymat', $numero_anonymat, PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':numero_table', $numero_table, PDO::PARAM_STR);
		$stmt->bindParam(':idusercreate', $idusercreate, PDO::PARAM_INT);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
	}
}

function genererNumeroTable($codeposition, $numero) {
    // Format : EX{idExamen}-T{numeroPlace}
    return 'EX' . str_pad($codeposition, 3, '0', STR_PAD_LEFT) . '-T' . str_pad($numero, 3, '0', STR_PAD_LEFT);
}

function genererNumeroAnonymat($codeposition, $idelevesalle) {
    return strtoupper(substr(md5($codeposition . $idelevesalle . time()), 0, 8));
}

function int2str($a)
{
	$joakim = explode(' ',$a);
	if(isset($joakim[1]) && $joakim[1]!='')
	{
		return int2str($joakim[0]).' virgule '.int2str($joakim[1]) ;
	}
	if ($a<0) return 'moins '.int2str(-$a);
	if ($a<17)
	{
		switch ($a)
		{
			//case 0: return 'zero';
			case 1: return 'un';
			case 2: return 'deux';
			case 3: return 'trois';
			case 4: return 'quatre';
			case 5: return 'cinq';
			case 6: return 'six';
			case 7: return 'sept';
			case 8: return 'huit';
			case 9: return 'neuf';
			case 10: return 'dix';
			case 11: return 'onze';
			case 12: return 'douze';
			case 13: return 'treize';
			case 14: return 'quatorze';
			case 15: return 'quinze';
			case 16: return 'seize';
		}
	} 
	else if ($a<20)
	{
		return 'dix-'.int2str($a-10);
	} 
	else if ($a<100)
	{
	if ($a%10==0)
	{
		switch ($a)
		{
			case 20: return 'vingt';
			case 30: return 'trente';
			case 40: return 'quarante';
			case 50: return 'cinquante';
			case 60: return 'soixante';
			case 70: return 'soixante-dix';
			case 80: return 'quatre-vingt';
			case 90: return 'quatre-vingt-dix';
		}
	} 
	elseif (substr($a, -1)==1)
	{
		if( ((int)($a/10)*10)<70 ){
		return int2str((int)($a/10)*10).'-et-un';
	} elseif ($a==71) {
	return 'soixante-et-onze';
	} elseif ($a==81) {
	return 'quatre-vingt-un';
	} elseif ($a==91) {
	return 'quatre-vingt-onze';
	}
	} elseif ($a<70){
	return int2str($a-$a%10).'-'.int2str($a%10);
	} elseif ($a<80){
	return int2str(60).'-'.int2str($a%20);
	} else{
	return int2str(80).'-'.int2str($a%20);
	}
	} else if ($a==100){
	return 'cent';
	} else if ($a<200){
	return int2str(100).' '.int2str($a%100);
	} else if ($a<1000){
	return int2str((int)($a/100)).' '.int2str(100).' '.int2str($a%100);
	} else if ($a==1000){
	return 'mille';
	} else if ($a<2000){
	return int2str(1000).' '.int2str($a%1000).' ';
	} else if ($a<1000000){
	return int2str((int)($a/1000)).' '.int2str(1000).' '.int2str($a%1000);
	} else if ($a==1000000){
	return 'million';
	} else if ($a<1000000000){
	return int2str((int)($a/1000000)).' '.int2str(1000000).' '.int2str($a%1000000);
	}
}

function getAllProfesseur_($pdo)
{
	$req=(' SELECT  professeur.id,professeur.nom FROM professeur ORDER BY professeur.nom asc');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			echo '<option value="'.$id.'*'.$nom.'">'.$nom.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllProfesseurSelected_($idprof,$pdo)
{
	$req=(' SELECT  professeur.id,professeur.nom FROM professeur ORDER BY professeur.nom asc');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			if($id==$idprof)
			{
				echo '<option value="'.$id.'*'.$nom.'" selected="selected">'.$nom.'</option>';
			}
			else
			{
				echo '<option value="'.$id.'*'.$nom.'">'.$nom.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getNomClasse($idclasse,$pdo)
{
	$req=(' SELECT classe.codeclasse
			FROM classe
			WHERE
			classe.idclasse=:idclasse');
			
	$codeclasse="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$codeclasse = $donnees['codeclasse'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $codeclasse;
}

function getNbreComposantes($idanneescolaire,$idposition,$idmatiere,$idclasse,$pdo)
{
	$req=(' SELECT count(distinct note.id) as Nbre
			FROM note,salle
			WHERE 
			salle.idclasse="'.$idclasse.'"
			AND
			salle.id=note.idsalle
			AND
			note.idanneescolaire="'.$idanneescolaire.'"
			AND
			note.idposition="'.$idposition.'"
			AND
			note.idmatiere="'.$idmatiere.'"
			AND
			note.notecomp<>""');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['Nbre'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreNoteMinimal($idanneescolaire,$idposition,$idmatiere,$idclasse,$pdo)
{
	$req=(' SELECT note.notecomp as notecomp
			FROM note,salle
			WHERE 
			salle.idclasse="'.$idclasse.'"
			AND
			salle.id=note.idsalle
			AND
			note.idanneescolaire="'.$idanneescolaire.'"
			AND
			note.idposition="'.$idposition.'"
			AND
			note.idmatiere="'.$idmatiere.'"
			AND
			note.notecomp<>""
			
			ORDER BY CAST(note.notecomp AS DECIMAL(10,2)) ASC LIMIT 1');
			
	$resultat = "";		
	$stmt_ = $pdo->prepare($req);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['notecomp'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreNoteMaximal($idanneescolaire,$idposition,$idmatiere,$idclasse,$pdo)
{
	$req=(' SELECT note.notecomp as notecomp
			FROM note,salle
			WHERE 
			salle.idclasse="'.$idclasse.'"
			AND
			salle.id=note.idsalle
			AND
			note.idanneescolaire="'.$idanneescolaire.'"
			AND
			note.idposition="'.$idposition.'"
			AND
			note.idmatiere="'.$idmatiere.'"
			AND
			note.notecomp<>""
			
			ORDER BY CAST(note.notecomp AS DECIMAL(10,2)) DESC LIMIT 1');
			
	$resultat = "";		
	$stmt_ = $pdo->prepare($req);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['notecomp'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreNoteParInterval($idanneescolaire,$idposition,$idmatiere,$idclasse,$born_inf,$born_sup,$pdo)
{
	$req=(' SELECT count(distinct note.id) as Nbre
			FROM note,salle
			WHERE 
			salle.idclasse="'.$idclasse.'"
			AND
			salle.id=note.idsalle
			AND
			note.idanneescolaire="'.$idanneescolaire.'"
			AND
			note.idposition="'.$idposition.'"
			AND
			note.idmatiere="'.$idmatiere.'"
			AND
			note.notecomp<>""
			AND
			CAST(note.notecomp AS DECIMAL(10,2))>="'.$born_inf.'"
			AND
			CAST(note.notecomp AS DECIMAL(10,2))<="'.$born_sup.'"');
			
	$resultat = 0;		
	$stmt_ = $pdo->prepare($req);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['Nbre'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getSumNote($idanneescolaire,$idposition,$idmatiere,$idclasse,$pdo)
{
	$req=(' SELECT sum(note.notecomp) as notecomp
			FROM note,salle
			WHERE 
			salle.idclasse="'.$idclasse.'"
			AND
			salle.id=note.idsalle
			AND
			note.idanneescolaire="'.$idanneescolaire.'"
			AND
			note.idposition="'.$idposition.'"
			AND
			note.idmatiere="'.$idmatiere.'"
			AND
			note.notecomp<>""');
			
	$resultat = "";		
	$stmt_ = $pdo->prepare($req);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['notecomp'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreNoteComp($idanneescolaire,$idposition,$idmatiere,$idsalle,$notecomp,$pdo)
{
	$req=(' SELECT count(distinct note.id) as Nbre
			FROM note
			WHERE 
			note.idanneescolaire="'.$idanneescolaire.'"
			AND
			note.idposition="'.$idposition.'"
			AND
			note.idsalle="'.$idsalle.'"
			AND
			note.notecomp="'.$notecomp.'"
			AND
			note.idmatiere="'.$idmatiere.'"');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->execute();	
	$resultat=0;
	if($donnees_ = $stmt_->fetch())
	{
	    $resultat = $donnees_['Nbre'];
	}
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	return $resultat;
}

function getNbreEleveParNiveauAyantComp($idanneescolaire,$idposition,$idclasse,$sexeeleve,$pdo)
{
	$req=(' SELECT count(distinct note.ideleve) as nbreeleve
					
			FROM note,salle,classe,eleve,elevesalle,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleve.sexe_eleve=:sexe_eleve
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			note.ideleve=elevesalle.id
			AND
			note.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			classe.idclasse=:idclasse

			GROUP BY classe.idclasse,note.idanneescolaire,note.idposition,eleve.sexe_eleve');

	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':sexe_eleve',$sexeeleve,PDO::PARAM_STR);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['nbreeleve'];
	}
	return $resultat;
}

function getMoyenneCompParClasse($idanneescolaire,$idposition,$idclasse,$sexeeleve,$moyenne,$pdo)
{
	$req=(' SELECT note.ideleve,CAST(sum(note.notecomp*note.coef)/sum(note.coef) AS DECIMAL(10,2)) as moyencomp
	
			FROM note,salle,classe,eleve,elevesalle,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleve.sexe_eleve=:sexe_eleve
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			note.ideleve=elevesalle.id
			AND
			note.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			note.idanneescolaire=:idanneescolaire
			AND
			note.idposition=:idposition
			AND
			classe.idclasse=:idclasse

			GROUP BY note.ideleve');

	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':sexe_eleve',$sexeeleve,PDO::PARAM_STR);
	$stmt->execute();
	$resultat=0;
	while($donnees = $stmt->fetch())
	{
		$moyencomp = $donnees['moyencomp'];
		if($moyencomp>=$moyenne)
		{
			$resultat++;
		}
	}
	return $resultat;
}

function getNbreMoyenneTrimestre($idanneescolaire,$idposition,$idclasse,$sexeeleve,$moyenne,$pdo)
{
	$req=(' SELECT count(distinct bulletin.ideleve) as NbreMoyenne
			FROM bulletin,salle,classe,eleve,elevesalle,eleveanneescolaire
			WHERE
			bulletin.idanneescolaire=:idanneescolaire
			AND
			bulletin.idposition=:idposition
			AND
			bulletin.idsalle=salle.id
			AND
			salle.idclasse=classe.idclasse
			AND
			classe.idclasse=:idclasse
			AND
			bulletin.ideleve=elevesalle.id
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.ideleve=eleve.id_eleve
			AND
			eleve.sexe_eleve=:sexe_eleve
			AND
			bulletin.moyenne_gene>=:moyenne');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
	$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':sexe_eleve',$sexeeleve,PDO::PARAM_STR);
	$stmt->bindParam(':moyenne',$moyenne,PDO::PARAM_INT);
	$stmt->execute();	
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['NbreMoyenne'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}