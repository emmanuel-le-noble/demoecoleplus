<?php

function generate_date($d)
{
	$mois=array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
	$moispos=array("01","02","03","04","05","06","07","08","09","10","11","12");
	for($i=0;$i<12;$i++)
	{
		if($mois[$i]==substr($d,3,strlen($d)-8))
		{
			return substr($d,strlen($d)-4,4).'-'.$moispos[$i].'-'.substr($d,0,2);break;
		}
	}
}

function getAllProfesseurPersonnel($pdo)
{
	$req=(' SELECT  distinct professeur.id,
	                professeur.nom
					
			FROM professeur,professeurdonneepaie
            WHERE
			professeur.statut=1
			AND
			professeur.modepaiement is not null
			AND
			professeur.corps=2
			AND
			professeur.id=professeurdonneepaie.idpers
			AND
			professeurdonneepaie.salairebase is not null
			
			UNION
			
			SELECT  distinct professeur.id,
	                professeur.nom
					
			FROM professeur,professeurdonneepaie
            WHERE
			professeur.statut=1
			AND
			professeur.modepaiement is null
			AND
			professeur.corps=1
			AND
			professeur.id=professeurdonneepaie.idpers
			AND
			professeurdonneepaie.salairebase is null');
			
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
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllProfesseurPersonnelSelected($idpers,$pdo)
{
	$req=(' SELECT  distinct professeur.id,
	                professeur.nom
					
			FROM professeur,professeurdonneepaie
            WHERE
			professeur.statut=1
			AND
			professeur.modepaiement is not null
			AND
			professeur.corps=2
			AND
			professeur.id=professeurdonneepaie.idpers
			AND
			professeurdonneepaie.salairebase is not null
			
			UNION
			
			SELECT  distinct professeur.id,
	                professeur.nom
					
			FROM professeur,professeurdonneepaie
            WHERE
			professeur.statut=1
			AND
			professeur.modepaiement is null
			AND
			professeur.corps=1
			AND
			professeur.id=professeurdonneepaie.idpers
			AND
			professeurdonneepaie.salairebase is null');
			
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
	</select><?php
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
	<select class="select2_single form-control" tabindex="-1" name="idcompte" required="required" id="idcompte" onchange="makeRequest('PretCompteDisponibilite.php','idcompte','disponibilite')">
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

function getAllCompteSelected($idcompteselected,$pdo)
{
	$req=(' SELECT  distinct
			compte.id as idcompte,
	                compte.libelle as libellecompte

			FROM compte
			WHERE
			compte.statut=1');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idcompte" required="required" id="idcompte" onchange="makeRequest('PretCompteDisponibilite.php','idcompte','disponibilite')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idcompte = $donnees['idcompte'];
			$libellecompte = $donnees['libellecompte'];
			if($idcompte==$idcompteselected)
			{
				echo '<option value="'.$idcompte.'" selected="selected">'.$libellecompte.'</option>';
			}
			else
			{
				echo '<option value="'.$idcompte.'">'.$libellecompte.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPretPersonnel($pdo)
{
	$req=(' SELECT  distinct
			professeur.id as idprofesseur,
	                professeur.nom as nomprofesseur,
			pret.id as idpret
					
			FROM professeur,pret
			WHERE
			professeur.id=pret.idpersonnel
			AND
			pret.statut in (0,1)

			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idpret" id="idpret" onchange="makeRequest('PretTableauAmortissement.php','idpret','tableauamortissement')" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idpret = $donnees['idpret'];
			$idprofesseur = $donnees['idprofesseur'];
			$nomprofesseur = $donnees['nomprofesseur'];

			echo '<option value="'.$idpret.'*'.$nomprofesseur.'">'.$nomprofesseur.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPretPersonnelSelected($idprets,$pdo)
{
	$req=(' SELECT  distinct
			professeur.id as idprofesseur,
	                professeur.nom as nomprofesseur,
			pret.id as idpret
					
			FROM professeur,pret
			WHERE
			professeur.id=pret.idpersonnel
			AND
			pret.statut in (0,1)

			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idpret">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idpret = $donnees['idpret'];
			$idprofesseur = $donnees['idprofesseur'];
			$nomprofesseur = $donnees['nomprofesseur'];
			if($idpret==$idprets)
			{
				echo '<option value="'.$idpret.'" selected="selected">'.$nom.'</option>';
			}
			else
			{
				echo '<option value="'.$idpret.'">'.$nom.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getentreecompte($idanneescolaire,$idcompte,$pdo)
{
	$req=(' SELECT sum(entreesortie.montant) as montant
	
			FROM entreesortie
			WHERE 
			entreesortie.comptemouvement=:idcompte
			AND
			entreesortie.idanneescolaire=:idanneescolaire
			AND
			entreesortie.statut=1
			AND
			entreesortie.idtypeentreesortie=1');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idcompte', $idcompte, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
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

function getsortiecompte($idanneescolaire,$idcompte,$pdo)
{
	$req=(' SELECT sum(entreesortie.montant) as montant
	
			FROM entreesortie
			WHERE 
			entreesortie.comptemouvement=:idcompte
			AND
			entreesortie.idanneescolaire=:idanneescolaire
			AND
			entreesortie.statut=1
			AND
			entreesortie.idtypeentreesortie=2');
			
	$stmt = $pdo->prepare($req);
    $stmt->bindParam(':idcompte', $idcompte, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
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

function getidPret($idanneescolaire,$idpersonnel,$montantpret,$pdo)
{
	$req=(' SELECT pret.id as id
			FROM pret
			WHERE 
			pret.montantpret=:montantpret
			AND
			pret.idpersonnel=:idpersonnel
			AND
			pret.statut=1');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':montantpret', $montantpret, PDO::PARAM_STR);
	$stmt->bindParam(':idpersonnel', $idpersonnel, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function AjouterPret($idanneescolaire,$idpersonnel,$libelle,$montantpret,$montantpreleve,$debut,$fin,$dateoperation,$idcompte,$fichier,$iduser,$pdo)
{
	
    $id=getidPret($idanneescolaire,$idpersonnel,$montantpret,$pdo);
	if($id==0)
	{
		
		$requete="INSERT INTO pret(idanneescolaire,idpersonnel,libelle,montantpret,montantpreleve,debut,fin,dateoperation,datesaisie,fichier,create_id,statut,idcompte) 
					VALUES(:idanneescolaire,:idpersonnel,:libelle,:montantpret,:montantpreleve,:debut,:fin,:dateoperation,sysdate(),:fichier,:create_id,1,:idcompte)";
		
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idpersonnel',$idpersonnel,PDO::PARAM_STR);
		$stmt->bindParam(':libelle',$libelle,PDO::PARAM_STR);
		$stmt->bindParam(':montantpret',$montantpret, PDO::PARAM_STR);
		$stmt->bindParam(':montantpreleve',$montantpreleve, PDO::PARAM_STR);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':dateoperation',$dateoperation,PDO::PARAM_STR);
		$stmt->bindParam(':fichier',$fichier,PDO::PARAM_STR);
		$stmt->bindParam(':create_id',$iduser,PDO::PARAM_INT);
		$stmt->bindParam(':idcompte',$idcompte,PDO::PARAM_INT);
		$stmt->execute();		
		$stmt->closeCursor();
		$stmt=NULL;
		
		$idpret=getidPret($idanneescolaire,$idpersonnel,$montantpret,$pdo);
		CreerTableauAmortissment($idpret,$debut,$fin,$montantpreleve,$iduser,$pdo);
		AjouterEntreeSortie($idpret,$idanneescolaire,2,$libelle,$montantpret,$dateoperation,$idcompte,$iduser,$iduser,$fichier,$pdo);
		
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

function UpdatePret($idpret,$idanneescolaire,$idpersonnel,$libelle,$montantpret,$montantpreleve,$debut,$fin,$dateoperation,$fichier,$pdo)
{

	$requete="	UPDATE 
				pret
				SET 
				idanneescolaire=:idanneescolaire,
				idpersonnel=:idpersonnel,
				libelle=:libelle,
				montantpret=:montantpret,
				montantpreleve=:montantpreleve,
				debut=:debut,
				fin=:fin,
				dateoperation=:dateoperation,
				fichier=:fichier
				
				WHERE
				pret.id=:idpret";

	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idpersonnel',$idpersonnel,PDO::PARAM_INT);
	$stmt->bindParam(':libelle',$libelle,PDO::PARAM_STR);
	$stmt->bindParam(':montantpret',$montantpret, PDO::PARAM_STR);
	$stmt->bindParam(':montantpreleve',$montantpreleve, PDO::PARAM_STR);
	$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
	$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
	$stmt->bindParam(':dateoperation',$dateoperation,PDO::PARAM_STR);
	$stmt->bindParam(':fichier',$fichier,PDO::PARAM_STR);
	$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
	$stmt->execute();		
	$stmt->closeCursor();
	$stmt=NULL;
	
	$success="Mise &agrave; jour effectu&eacute;e avec succ&egrave;s";
	$error="";
}

function DeletePret($idpret,$pdo)
{
	
	$req=' DELETE FROM pret WHERE pret.id=:idpret';
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	
	$req_=' DELETE FROM pretremboursement WHERE pretremboursement.idpret=:idpret';
			
    $stmt_ = $pdo->prepare($req_);
	$stmt_->bindParam(':idpret',$idpret,PDO::PARAM_INT);
    $stmt_->execute();			
	$stmt_->closeCursor();
	$stmt_=NULL;
	
	$req_=' DELETE FROM entreesortie WHERE entreesortie.idpret=:idpret';
			
    $stmt_ = $pdo->prepare($req_);
	$stmt_->bindParam(':idpret',$idpret,PDO::PARAM_INT);
    $stmt_->execute();			
	$stmt_->closeCursor();
	$stmt_=NULL;
}

function UpdateStatutPret($idpret,$pdo)
{
	$req=' UPDATE pret SET statut=2 WHERE pret.id=:idpret';
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getidRemboursement($idpret,$mois,$annee,$montant,$pdo)
{
	
	$req=(' SELECT pretremboursement.id as id
	
			FROM pretremboursement
			WHERE 
			pretremboursement.idpret=:idpret
			AND
			pretremboursement.mois=:mois
			AND
			pretremboursement.annee=:annee
			AND
			pretremboursement.montant_prelever=:montant_prelever');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
	$stmt->bindParam(':mois',$mois,PDO::PARAM_INT);
	$stmt->bindParam(':annee',$annee,PDO::PARAM_INT);
	$stmt->bindParam(':montant_prelever',$montant,PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getPretMontant($idpret,$pdo)
{
	
	$req=(' SELECT pret.montantpret as montantpret FROM pret WHERE pret.id=:idpret');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['montantpret'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getPretMontantRemboursement($idpret,$pdo)
{
	
	$req=(' SELECT sum(pretremboursement.montant_rembourser) as montantrembourser FROM pretremboursement WHERE pretremboursement.idpret=:idpret');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['montantrembourser'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function AjouterPretRemboursement($idpret,$nom,$idcompte,$dateoperation,$idremboursement,$montantrembourser,$mois,$annee,$fichier,$idanneescolaire,$iduser,$pdo)
{
	
	$req=' UPDATE pretremboursement SET fichier=:fichier,idanneescolaire=:idanneescolaire,idcompte=:idcompte,dateoperation=:dateoperation,montant_rembourser=:montant_rembourser,create_id=:create_id WHERE pretremboursement.id=:id';
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id',$idremboursement,PDO::PARAM_INT);
	$stmt->bindParam(':idcompte',$idcompte,PDO::PARAM_INT);
	$stmt->bindParam(':dateoperation',$dateoperation,PDO::PARAM_STR);
	$stmt->bindParam(':fichier',$fichier,PDO::PARAM_STR);
	$stmt->bindParam(':create_id',$iduser,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':montant_rembourser',$montantrembourser,PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;
	
	$statut=0;
	if(getPretMontantRemboursement($idpret,$pdo)=="" OR getPretMontantRemboursement($idpret,$pdo)==0)
	{
		$statut=0;
	}
	elseif(getPretMontantRemboursement($idpret,$pdo)<getPretMontant($idpret,$pdo))
	{
		$statut=1;
	}
	elseif(getPretMontantRemboursement($idpret,$pdo)==getPretMontant($idpret,$pdo))
	{
		$statut=2;
	}
	
	$req_1=' UPDATE pret SET statut=:statut WHERE pret.id=:idpret';
    $stmt_1 = $pdo->prepare($req_1);
	$stmt_1->bindParam(':idpret',$idpret,PDO::PARAM_INT);
	$stmt_1->bindParam(':statut',$statut,PDO::PARAM_INT);
    $stmt_1->execute();			
	$stmt_1->closeCursor();
	$stmt_1=NULL;
	
	$libelle="Remoursement de prêt N° ".$idpret." ".$nom."<br/>Mois de ".$mois." ".$annee;
	AjouterEntreeSortie($idremboursement,$idanneescolaire,1,$libelle,$montantrembourser,$dateoperation,$idcompte,$iduser,$iduser,$fichier,$pdo);
	$success="Enregistrement effectu&eacute;e avec succ&egrave;s";
	$error="";
	
	return $success.'*'.$error; 
}

function DeletePretRemboursement($idpret,$idremboursement,$pdo)
{
	
	$req=' UPDATE pretremboursement SET montant_rembourser=null,create_id=null,idcompte=null,dateoperation=null,fichier=null WHERE pretremboursement.id=:id';	
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id',$idremboursement,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;
	
	$statut=0;
	if(getPretMontantRemboursement($idpret,$pdo)=="" OR getPretMontantRemboursement($idpret,$pdo)==0)
	{
		$statut=0;
	}
	elseif(getPretMontantRemboursement($idpret,$pdo)<getPretMontant($idpret,$pdo))
	{
		$statut=1;
	}
	elseif(getPretMontantRemboursement($idpret,$pdo)==getPretMontant($idpret,$pdo))
	{
		$statut=2;
	}
	
	$req_1=' UPDATE pret SET statut=:statut WHERE pret.id=:idpret';
    $stmt_1 = $pdo->prepare($req_1);
	$stmt_1->bindParam(':idpret',$idpret,PDO::PARAM_INT);
	$stmt_1->bindParam(':statut',$statut,PDO::PARAM_INT);
    $stmt_1->execute();			
	$stmt_1->closeCursor();
	$stmt_1=NULL;
	
	$req_=' DELETE FROM entreesortie WHERE entreesortie.idpretremboursement=:idpretremboursement';
    $stmt_ = $pdo->prepare($req_);
	$stmt_->bindParam(':idpretremboursement',$idremboursement,PDO::PARAM_INT);
    $stmt_->execute();			
	$stmt_->closeCursor();
	$stmt_=NULL;
}

function CreerTableauAmortissment($idpret,$debut,$fin,$montant,$iduser,$pdo)
{
	
	$debut=new DateTime($debut);
	$fin=new DateTime($fin);
	while($debut<=$fin)
	{
		$debut->add(new DateInterval("P1D"));
		$mois=$debut->format('m');
		$annee=$debut->format('y');
		$id=getidRemboursement($idpret,$mois,$annee,$montant,$pdo);
		if($id==0)
		{
			$requete="INSERT INTO pretremboursement(idpret,mois,annee,montant_prelever,create_id) VALUES(:idpret,:mois,:annee,:montant_prelever,:create_id)";
			
			$stmt = $pdo->prepare($requete);
			$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
			$stmt->bindParam(':mois',$mois,PDO::PARAM_INT);
			$stmt->bindParam(':annee',$annee,PDO::PARAM_INT);
			$stmt->bindParam(':montant_prelever',$montant, PDO::PARAM_STR);
			$stmt->bindParam(':create_id',$iduser,PDO::PARAM_STR);
			$stmt->execute();		
			$stmt->closeCursor();
			$stmt=NULL;
		}	
	}
}

function AjouterEntreeSortie($idpret,$idanneescolaire,$typeoperation,$libelleoperation,$montant,$dateoperation,$idcompte,$iduser,$iduser2,$fichier,$pdo)
{

	$etatcompteacuel=(getentreecompte($idanneescolaire,$idcompte,$pdo)-getsortiecompte($idanneescolaire,$idcompte,$pdo))-$montant;
	
	$requete="INSERT INTO entreesortie(idpret,idanneescolaire,montant,libelle,statut,iduserajout,iduserauto,dateoperation,comptemouvement,idtypeentreesortie,montantcompte,ficheattache,datesaisie) 
				VALUES(:idpret,:idanneescolaire,:montant,:libelle,1,:iduserajout,:iduserauto,:dateoperation,:comptemouvement,:idtypeentreesortie,:montantcompte,:ficheattache,sysdate())";
	
	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':idpret', $idpret, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
	$stmt->bindParam(':libelle', $libelleoperation, PDO::PARAM_STR);
	$stmt->bindParam(':iduserajout', $iduser2, PDO::PARAM_INT);
	$stmt->bindParam(':iduserauto', $iduser, PDO::PARAM_INT);
	$stmt->bindParam(':dateoperation', $dateoperation, PDO::PARAM_STR);
	$stmt->bindParam(':comptemouvement', $idcompte, PDO::PARAM_INT);
	$stmt->bindParam(':idtypeentreesortie', $typeoperation, PDO::PARAM_INT);
	$stmt->bindParam(':montantcompte', $etatcompteacuel, PDO::PARAM_STR);
	$stmt->bindParam(':ficheattache', $fichier, PDO::PARAM_STR);
	$stmt->execute();		
	$stmt->closeCursor();
	$stmt=NULL; 
}

function ListPret($pdo)
{
	
	$req=(' SELECT  
					distinct 
					professeur.nom as nom,
					pret.libelle as libelle,
					pret.id as idpret,
					pret.montantpret as montantpret,
					pret.montantpreleve as montantpreleve,
					pret.dateoperation as dateoperation,
					pret.debut as debut,
					pret.fin as fin,
					pret.fichier as fichier,
					pret.statut as statut,
					utilisateur.nom_user as nomutilisateur,
					utilisateur.prenom_user as prenomutilisateur,
					anneescolaire.libelle as anneescolaire
					
			FROM professeur,pret,utilisateur,anneescolaire
			WHERE
			anneescolaire.id=pret.idanneescolaire
			AND
			pret.create_id=utilisateur.id
			AND
			pret.idpersonnel=professeur.id
			AND
			anneescolaire.statut=1
			
			ORDER BY pret.id DESC');
			
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
			    <th style="width:5%;text-align:center;">#</th>
				<th style="width:13%;font-weight:bold;color:#000">Statut rembours.</th>
				<th style="width:14%;font-weight:bold;color:#000">Objet du prêt</th>
				<th style="width:15%;font-weight:bold;color:#000">Personnel</th>
				<th style="width:13%;font-weight:bold;color:#000">Montant Octroi</th>
				<th style="width:13%;font-weight:bold;color:#000">P&eacute;riode</th>
				<th style="width:13%;font-weight:bold;color:#000">Date prêt</th>
				<th style="width:10%;font-weight:bold;color:#000">Pi&egrave;ce justifi.</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$nompersonnel = $donnees['nom'];
			$statut = $donnees['statut'];
			$libelle = $donnees['libelle'];
			$nom = $donnees['nomutilisateur'].' '.$donnees['prenomutilisateur'];
			$idpret = $donnees['idpret'];
			$fichier = $donnees['fichier'];
			$anneescolaire = $donnees['anneescolaire'];
			$montantpret = $donnees['montantpret'];
			$montantpreleve = $donnees['montantpreleve'];
			$dateoperation = $donnees['dateoperation'];
			$tab = explode("-",$dateoperation);
			$dateoperation = $tab[2]."/".$tab[1]."/".$tab[0];
			$debut = $donnees['debut'];
			$tab_ = explode("-",$debut);
			$debut = $tab_[2]."/".$tab_[1]."/".$tab_[0];
			$fin = $donnees['fin'];
			$tab__ = explode("-",$fin);
			$fin = $tab__[2]."/".$tab__[1]."/".$tab__[0];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idpret<?php echo $ligne;?>" value="<?php echo $idpret.'*'.$statut;?>"/>						
				</td>
				<td align="left">
					<?php 
						if($statut==1)
						{
							?>
							<div align="center">
								<a href="#" class="btn btn-default btn-xs">
									<i class="fa fa-check"></i><span style="font-size:11px;">&nbsp;Non d&eacute;marr&eacute;</span>
								</a>
							</div><?php
						}
						elseif($statut==2)
						{
							?>
							<div align="center">
								<a href="#" class="btn btn-info btn-xs">
									<i class="fa fa-check"></i><span style="font-size:11px;">&nbsp;Remboursement en cours</span>
								</a>
							</div><?php
						}
						elseif($statut==3)
						{
							?>
							<div align="center">
								<a href="#" class="btn btn-info btn-xs">
									<i class="fa fa-check"></i><span style="font-size:11px;">&nbsp;Remboursement termin&eacute;</span>
								</a>
							</div><?php
						}
					?>
					</a>
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $nompersonnel;?></a>
				</td>
				<td>
					<a><?php echo number_format($montantpret,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo $debut.'-'.$fin;?></a>
				</td>
				<td>
					<a><?php echo $dateoperation;?></a>
				</td>
				<td>
					<?php
					if($fichier!="")
					{
						?>
						<a href="pret/<?php echo $fichier;?>" target="_blank"><span style="color:red;">[T&eacute;l&eacute;charger]</span></a>
						<?php
					}
					?>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrepret" value="<?php echo $ligne;?>"/><?php
}

function TableauAmortissment_1($idpret,$pdo)
{
	
	$req=(' SELECT  
					distinct 
					pretremboursement.id as idremboursement,
					pretremboursement.mois as mois,
					pretremboursement.annee as annee,
					pretremboursement.montant_prelever as montant_prelever,
					pretremboursement.montant_rembourser as montant_rembourser
					
			FROM pretremboursement
			WHERE
			pretremboursement.idpret=:idpret
			
			ORDER BY pretremboursement.id ASC');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
			    <th style="width:5%;text-align:center;font-family:comic sans ms;">#</th>
				<th style="width:13%;color:#000;font-family:comic sans ms;">MOIS</th>
				<th style="width:13%;color:#000;font-family:comic sans ms;">ANN&Eacute;E</th>
				<th style="width:13%;color:#000;font-family:comic sans ms;">MONTANT PR&Eacute;LEV&Eacute; (FCFA)</th>
				<th style="width:13%;color:red;font-family:comic sans ms;">MONTANT REMBOURS&Eacute; (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
		$stmt->execute();
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			
			$ligne++;
			$idremboursement = $donnees['idremboursement'];
			$mois = $donnees['mois'];
			if($mois+0==1)
			{
				$libelle="Janvier";
			}
			elseif($mois+0==2)
			{
				$libelle="Fevrier";
			}
			elseif($mois+0==3)
			{
				$libelle="Mars";
			}
			elseif($mois+0==4)
			{
				$libelle="Avril";
			}
			elseif($mois+0==5)
			{
				$libelle="Mai";
			}
			elseif($mois+0==6)
			{
				$libelle="Juin";
			}
			elseif($mois+0==7)
			{
				$libelle="Juillet";
			}
			elseif($mois+0==8)
			{
				$libelle="Août";
			}
			elseif($mois+0==9)
			{
				$libelle="Septembre";
			}
			elseif($mois+0==10)
			{
				$libelle="Octobre";
			}
			elseif($mois+0==11)
			{
				$libelle="Novembre";
			}
			elseif($mois+0==12)
			{
				$libelle="Decembre";
			}
			$annee = "20".$donnees['annee'];
			$montant_prelever = $donnees['montant_prelever'];
			$montant_rembourser = $donnees['montant_rembourser'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>					
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $annee;?></a>
				</td>
				<td>
					<a><?php echo number_format($montant_prelever,0,""," ");?></a>
				</td>
				<td>
					<a>
						<?php 
							if($montant_rembourser!="")
							{
								echo number_format($montant_rembourser,0,""," ");
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
	<input type="hidden" name="nbretableauamortissement" value="<?php echo $ligne;?>"/><?php
}

function TableauAmortissment_2($idpret,$pdo)
{
	
	$req=(' SELECT  
					distinct 
					pretremboursement.id as idremboursement,
					pretremboursement.mois as mois,
					pretremboursement.annee as annee,
					pretremboursement.montant_prelever as montantprelever,
					pretremboursement.montant_rembourser as montantrembourser
					
			FROM pretremboursement
			WHERE
			pretremboursement.idpret=:idpret
			AND
			pretremboursement.montant_rembourser is null
			
			ORDER BY pretremboursement.id ASC');
			
    ?>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;" class="control-label col-md-12 col-sm-12 col-xs-12">
	[ Veuillez cocher les mois &agrave; rembourser ]
	</label><br/><br/>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color: #eee;">
			    <th style="width:5%;text-align:center;font-family:comic sans ms;">#</th>
				<th style="width:31%;font-family:comic sans ms;">Mois</th>
				<th style="width:31%;font-family:comic sans ms;">Ann&eacute;e</th>
				<th style="width:31%;font-family:comic sans ms;">Montant pr&eacute;lev&eacute;(FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
		$stmt->execute();	
		while($donnees = $stmt->fetch())
		{
			
			$idremboursement = $donnees['idremboursement'];
			$mois = $donnees['mois'];
			if($mois+0==1)
			{
				$libelle="Janvier";
			}
			elseif($mois+0==2)
			{
				$libelle="Fevrier";
			}
			elseif($mois+0==3)
			{
				$libelle="Mars";
			}
			elseif($mois+0==4)
			{
				$libelle="Avril";
			}
			elseif($mois+0==5)
			{
				$libelle="Mai";
			}
			elseif($mois+0==6)
			{
				$libelle="Juin";
			}
			elseif($mois+0==7)
			{
				$libelle="Juillet";
			}
			elseif($mois+0==8)
			{
				$libelle="Août";
			}
			elseif($mois+0==9)
			{
				$libelle="Septembre";
			}
			elseif($mois+0==10)
			{
				$libelle="Octobre";
			}
			elseif($mois+0==11)
			{
				$libelle="Novembre";
			}
			elseif($mois+0==12)
			{
				$libelle="Decembre";
			}
			$annee = "20".$donnees['annee'];
			$montantprelever = $donnees['montantprelever'];
			$montantrembourser = $donnees['montantrembourser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idremboursement<?php echo $ligne;?>" value="<?php echo $idremboursement.'*'.$montantprelever.'*'.$libelle.'*'.$annee;?>"/>						
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $annee;?></a>
				</td>
				<td>
					<a><?php echo number_format($montantprelever,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbretableauamortissement" value="<?php echo $ligne;?>"/><?php
}

function TableauAmortissment_3($idpret,$pdo)
{
	
	$req=(' SELECT  
					distinct 
					pretremboursement.id as idremboursement,
					pretremboursement.mois as mois,
					pretremboursement.annee as annee,
					pretremboursement.montant_prelever as montantprelever,
					pretremboursement.montant_rembourser as montantrembourser
					
			FROM pretremboursement
			WHERE
			pretremboursement.idpret=:idpret
			AND
			pretremboursement.montant_prelever=pretremboursement.montant_rembourser
			
			ORDER BY pretremboursement.id ASC');
			
    ?>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;" class="control-label col-md-12 col-sm-12 col-xs-12" >
	[ Historique de remboursement ]
	</label><br/><br/>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color: #eee;">
			    <th style="width:5%;text-align:center;font-family:comic sans ms;">#</th>
				<th style="width:31%;font-family:comic sans ms;">Mois</th>
				<th style="width:31%;font-family:comic sans ms;">Ann&eacute;e</th>
				<th style="width:31%;color:red;font-family:comic sans ms;">Montant rembours&eacute; (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
		$stmt->execute();	
		while($donnees = $stmt->fetch())
		{
			
			$idremboursement = $donnees['idremboursement'];
			$mois = $donnees['mois'];
			if($mois+0==1)
			{
				$libelle="Janvier";
			}
			elseif($mois+0==2)
			{
				$libelle="Fevrier";
			}
			elseif($mois+0==3)
			{
				$libelle="Mars";
			}
			elseif($mois+0==4)
			{
				$libelle="Avril";
			}
			elseif($mois+0==5)
			{
				$libelle="Mai";
			}
			elseif($mois+0==6)
			{
				$libelle="Juin";
			}
			elseif($mois+0==7)
			{
				$libelle="Juillet";
			}
			elseif($mois+0==8)
			{
				$libelle="Août";
			}
			elseif($mois+0==9)
			{
				$libelle="Septembre";
			}
			elseif($mois+0==10)
			{
				$libelle="Octobre";
			}
			elseif($mois+0==11)
			{
				$libelle="Novembre";
			}
			elseif($mois+0==12)
			{
				$libelle="Decembre";
			}
			$annee = "20".$donnees['annee'];
			$montant_prelever = $donnees['montant_prelever'];
			$montant_rembourser = $donnees['montant_rembourser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idremboursement<?php echo $ligne;?>" value="<?php echo $idremboursement;?>"/>						
				</td>
				<td>
					<a><?php echo $libelle;?></a>
				</td>
				<td>
					<a><?php echo $annee;?></a>
				</td>
				<td>
					<a>
						<?php 
							if($montant_rembourser!="")
							{
								echo number_format($montant_rembourser,0,""," ");
							}
						?>
					</a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table><?php
}

function ListPretRemboursement($pdo)
{
	
	$req=(' SELECT  
					distinct 
					professeur.nom as nom,
					pret.id as idpret,
					pretremboursement.id as idpretremboursement,
					pretremboursement.mois as mois,
					pretremboursement.annee as annee,
					pretremboursement.montant_prelever as montantprelever,
					pretremboursement.montant_rembourser as montantrembourser,
					pretremboursement.dateoperation as dateoperation,
					compte.libelle as compte,
					utilisateur.nom_user as nomutilisateur,
					utilisateur.prenom_user as prenomutilisateur
					
			FROM pretremboursement,pret,professeur,anneescolaire,compte,utilisateur
			WHERE
			professeur.id=pret.idpersonnel
			AND
			pret.id=pretremboursement.idpret
			AND
			pretremboursement.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			compte.id=pretremboursement.idcompte
			AND
			pretremboursement.create_id=utilisateur.id

			ORDER BY pretremboursement.id DESC');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
			    <th style="width:5%;text-align:center;color:#000">#</th>
				<th style="width:13%;color:#000">DATE REMBOURSEMENT</th>
				<th style="width:13%;color:#000">N° PRET</th>
				<th style="width:13%;color:#000">MOIS & ANN&Eacute;E</th>
				<th style="width:13%;color:#000">MONTANT REMBOURS&Eacute;</th>
				<th style="width:13%;color:#000">COMPTE OP&Eacute;RATION</th>
				<th style="width:13%;color:#000">PI&Egrave;CE JUSTIFI.</th>
				<th style="width:13%;color:#000">ENREGISTR&Eacute; PAR</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();
		$ligne=0;		
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$nom = $donnees['nom'];
			$idpret = $donnees['idpret'];
			$idpretremboursement = $donnees['idpretremboursement'];
			$mois = $donnees['mois'];
			$annee = "20".$donnees['annee'];
			$montantprelever = $donnees['montantprelever'];
			$montantrembourser = $donnees['montantrembourser'];
			$dateoperation = $donnees['dateoperation'];
			$tab = explode("-",$dateoperation);
			$dateoperation = $tab[2]."/".$tab[1]."/".$tab[0];
			$num = "N° : ".$idpret."<br/> Nom : ".$nom."<br/> Montant pr&eacute;lev&eacute; : ".$montantprelever;
			$compte = $donnees['compte'];
			$utilisateur = $donnees['nomutilisateur'].' '.$donnees['prenomutilisateur'];
			$mois = $donnees['mois'];
			if($mois+0==1)
			{
				$libelle="Janvier";
			}
			elseif($mois+0==2)
			{
				$libelle="Fevrier";
			}
			elseif($mois+0==3)
			{
				$libelle="Mars";
			}
			elseif($mois+0==4)
			{
				$libelle="Avril";
			}
			elseif($mois+0==5)
			{
				$libelle="Mai";
			}
			elseif($mois+0==6)
			{
				$libelle="Juin";
			}
			elseif($mois+0==7)
			{
				$libelle="Juillet";
			}
			elseif($mois+0==8)
			{
				$libelle="Août";
			}
			elseif($mois+0==9)
			{
				$libelle="Septembre";
			}
			elseif($mois+0==10)
			{
				$libelle="Octobre";
			}
			elseif($mois+0==11)
			{
				$libelle="Novembre";
			}
			elseif($mois+0==12)
			{
				$libelle="Decembre";
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idpretremboursement<?php echo $idpretremboursement;?>" value="<?php echo $idpretremboursement.'*'.$idpret;?>"/>						
				</td>
				<td>
					<a><?php echo $dateoperation;?></a>
				</td>
				<td>
					<a><?php echo $num;?></a>
				</td>
				<td>
					<a><?php echo $libelle.' '.$annee;?></a>
				</td>
				<td>
					<a>
						<?php 
							if($montantrembourser!="")
							{
								echo number_format($montantrembourser,0,""," ");
							}
						?>
					</a>
				</td>
				<td>
					<a><?php echo $compte;?></a>
				</td>
				<td>
					<?php
					$dossier = 'pretremboursement/';
					if($fichier!="")
					{
						if(file_exists($dossier.$fichier)) 
						{
							?><a href="pretremboursement/<?php echo $fichier;?>" target="_blank" class="btn btn-info btn-xs">[T&eacute;l&eacute;charger]</a><?php
						} 
						else 
						{
							?><a href="#" target="_blank"><span class="btn btn-danger btn-xs">[Pi&egrave;ce non disponible]</span></a><?php
						}
					}
					?>
			    </td>
				<td>
					<a><?php echo $utilisateur;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreremboursement" value="<?php echo $ligne;?>"/><?php
}