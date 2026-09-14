<?php

function getNbreEntreeSortieSansJustificatif($idanneescolaire,$pdo)
{
	$req=(' SELECT count(entreesortie.id) as nbreentreesortiesansfichierattache
			FROM entreesortie
			WHERE 
			entreesortie.idanneescolaire=:idanneescolaire
			AND
			(entreesortie.ficheattache is null OR entreesortie.ficheattache="")
			AND
			entreesortie.statut=1');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nbreentreesortiesansfichierattache'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getNbrePretSansJustificatif($idanneescolaire,$pdo)
{
	$req=(' SELECT count(pret.id) as nbrepret
			FROM pret
			WHERE 
			pret.idanneescolaire=:idanneescolaire
			AND
			(pret.fichier is null OR pret.fichier="")
			AND
			pret.statut=1');

	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nbrepret'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getJourneeNonCloture($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementfrais.date as Datepaiement,
					sum(paiementfrais.montant) as Montant
					
			FROM paiementfrais,eleveanneescolaire,anneescolaire
			WHERE
			paiementfrais.ideleveanneescolaire=eleveanneescolaire.id
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			paiementfrais.statut=1
			AND
			paiementfrais.id NOT IN (SELECT caissepaiement.idpaiementfrais FROM caissepaiement)
			
			GROUP BY paiementfrais.date');	
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
		$Montant = $donnees['Montant'];
		$Datepaiement = $donnees['Datepaiement'];
		$tab = explode("-",$Datepaiement);
		$Datepaiement = $tab[2]."/".$tab[1]."/".$tab[0];
		?>
		<span>
			<span style="color:orange;font-weight:bold;">Cl&ocirc;ture de journ&eacute;e non effectu&eacute;e :</span>
		</span>
		<span class="message">
			<?php echo $Datepaiement." : ".number_format($Montant,0,""," ");?>
		</span>
		<?php
	}
	$stmt->closeCursor();
	$stmt=NULL;
}

function getNbreJourneeNonCloture($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementfrais.date as Datepaiement,
					sum(paiementfrais.montant) as Montant
					
			FROM paiementfrais,eleveanneescolaire,anneescolaire
			WHERE
			paiementfrais.ideleveanneescolaire=eleveanneescolaire.id
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			paiementfrais.statut=1
			AND
			paiementfrais.id NOT IN (SELECT caissepaiement.idpaiementfrais FROM caissepaiement)
			
			GROUP BY paiementfrais.date');	
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	while($donnees = $stmt->fetch())
	{
		$ligne++;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $ligne;
}

function getNbreInscritNonPaye($idanneescolaire,$idpaiementtype,$pdo)
{
	
	$req=(' SELECT  
					eleveanneescolaire.id as ideleveanneescolaire
					
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
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id 
			NOT IN 
				(
					SELECT paiementfrais.ideleveanneescolaire 
					FROM paiementfrais,paiementtypeclasse,paiementtype,classe
					WHERE 
					paiementfrais.idpaiementtypeclasse=paiementtypeclasse.id
					AND
					paiementtypeclasse.idclasse=eleveanneescolaire.idclasse
					AND
					eleveanneescolaire.idclasse=classe.idclasse
					AND
					paiementtypeclasse.idpaiementtype=paiementtype.id
					AND
					paiementtype.id=:idpaiementtype
				)
		');	
		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtype', $idpaiementtype, PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	while($donnees = $stmt->fetch())
	{
		$ligne++;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $ligne;
}

function getEntreeCompteEtat_1($idanneescolaire,$idcompte,$pdo)
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

function getSortieCompteEtat_1($idanneescolaire,$idcompte,$pdo)
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

function getEtatCaisse_($idanneescolaire,$idcompte,$pdo)
{
	return $disponibilite = getEntreeCompteEtat_1($idanneescolaire,$idcompte,$pdo)-getSortieCompteEtat_1($idanneescolaire,$idcompte,$pdo);
}

function gettitreUser($id,$pdo)
{
	$req="  SELECT professeur.titre
		    FROM professeur
			WHERE
			professeur.id=:id";
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$titre = $donnees['titre'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $titre;			
}

function getstatututilisateur($id,$pdo)
{
	$req="  SELECT utilisateur.etat_user
		    FROM utilisateur
			WHERE
			utilisateur.id=:id";
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$etat_user = $donnees['etat_user'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $etat_user;			
}

function idUser_($nom_user,$prenom_user,$pdo)
{
	$req=(' SELECT utilisateur.id
			FROM utilisateur
			WHERE 
			utilisateur.nom_user=:nom_user
			AND
			utilisateur.prenom_user=:prenom_user');
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt -> bindParam(':nom_user', $nom_user, PDO::PARAM_STR);
	$stmt -> bindParam(':prenom_user', $prenom_user, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getidutilisateur($login,$password,$pdo)
{
	$req='  SELECT utilisateur.id
		    FROM utilisateur
			WHERE
			utilisateur.login_user = :login
			AND
			utilisateur.mtpass_user = :password';
			
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$id = $donnees['id'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $id; 		
}

function verif_utilisateur($login,$password,$pdo)
{
	$req='  SELECT utilisateur.profil
		    FROM utilisateur
			WHERE
			utilisateur.login_user = :login
			AND
			utilisateur.mtpass_user = :password
			AND
			utilisateur.statut = 1';
	$profil = "";
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$profil = $donnees['profil'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $profil; 		
}

function getnom($id,$pdo)
{
	$req="  SELECT nom_user
		    FROM utilisateur
			WHERE
			utilisateur.id=:id";
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$nom_user = $donnees['nom_user'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $nom_user;			
}

function getprenom($id,$pdo)
{
	$req="  SELECT prenom_user
		    FROM utilisateur
			WHERE
			utilisateur.id=:id";
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$prenom_user = $donnees['prenom_user'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $prenom_user;
}

function getidprofil($id,$pdo)
{
	$req="  SELECT profil
		    FROM utilisateur
			WHERE
			utilisateur.id=:id";
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$profil = $donnees['profil'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $profil;
}

function getphoto($id,$pdo)
{
	$req="  SELECT photo_user
		    FROM utilisateur
			WHERE
			utilisateur.id=:id";
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();		
	if ($donnees = $stmt->fetch())
	{
		$photo_user = $donnees['photo_user'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $photo_user;
}

function getidAnneeScolairee($pdo)
{
	$req=(' SELECT anneescolaire.id as id,
		  		   anneescolaire.libelle as libelle

			FROM anneescolaire
			WHERE
			anneescolaire.statut=1
			
			ORDER BY anneescolaire.id DESC LIMIT 0,1');
	$resultat=0;		
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

function getstatutAnneeScolairee($idanneescolaire,$pdo)
{
	$req=(' SELECT anneescolaire.statut as statut
			FROM anneescolaire
			WHERE
			anneescolaire.id=:idanneescolaire');
	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['statut'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function CreateJournalisation($iduser,$action,$valeur,$pdo)
{
	$requete="INSERT INTO journalisation(iduser,action,valeur,dateaction) VALUES(:iduser,:action,:valeur,sysdate())";

	$stmt = $pdo->prepare($requete);
	$stmt ->bindParam(':iduser',$iduser,PDO::PARAM_INT);
	$stmt ->bindParam(':action',$action,PDO::PARAM_STR);
    $stmt ->bindParam(':valeur',$valeur,PDO::PARAM_STR);
	$stmt ->execute();		
	$stmt ->closeCursor();
	$stmt =NULL;
}

function Journalisation($pdo)
{
	
	$req=(' SELECT  
	                journalisation.id as id,
					journalisation.iduser as iduser,
					journalisation.action as action,
					journalisation.valeur as valeur,
					utilisateur.nom_user as nomuser,
					utilisateur.prenom_user as prenomuser,
					journalisation.dateaction as dateaction

			FROM journalisation,utilisateur
			WHERE
			utilisateur.id=journalisation.iduser
			
			ORDER BY journalisation.id desc'
		);
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-size:12px">
	    <thead>
		    <tr style="background-color: #eee;">
		        <th style="width:5%;">#</th> 
				<th style="width:20%;">UTILISATEUR</th>
				<th style="width:20%;">ACTION</th>
				<th style="width:20%;">OP&Eacute;RATION</th>
		        <th style="width:15%;">DATE EFFECTU&Eacute;E</th>
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
			$iduser = $donnees['iduser'];
			$action = $donnees['action'];
			$valeur = $donnees['valeur'];
			$nom = $donnees['nomuser'].' '.$donnees['prenomuser'];
			$dateaction = $donnees['dateaction'];
			$tab = explode(" ",$dateaction);
			$tab_ = explode("-",$tab[0]);
			?>
			<tr>
			    <td>
				    <input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
			    </td>
			    <td>
					<a><?php echo $action;?></a>
			    </td>
				<td>
					<a><?php echo $valeur;?></a>
			    </td>
				<td>
					<a>
						<?php 
							echo $tab_[2].'/'.$tab_[1].'/'.$tab_[0].' '.$tab[1];
						?>
					</a>
			    </td>						
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrejournalisation" value="<?php echo $ligne;?>"/><?php
}

function getverif_Horaire($pdo)
{
	$req=(' SELECT count(*) as exist
			FROM horairesaisienote h
			WHERE 
			CURRENT_DATE BETWEEN h.datedebut AND h.datefin
			ORDER BY h.id DESC
			LIMIT 0,1');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	$resultat=0;
	if($donnees=$stmt->fetch())
	{
	    $resultat=$donnees['exist'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}