<?php
function getidArticle($nom,$pdo)
{
	$req=(' SELECT article.id FROM article WHERE article.nom=:nom');
	
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateArticle($idcategorie,$nom,$prixunitaire,$qtedispo,$pdo)
{   
    $idArticle = getidArticle($nom,$pdo);
	if($idArticle==0)
	{
		$requete="INSERT INTO article(idcategorie,nom,prixunitaire,qtedispo,statut) VALUES(:idcategorie,:nom,:prixunitaire,:qtedispo,1)";

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