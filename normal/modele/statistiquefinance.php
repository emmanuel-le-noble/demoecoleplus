<?php

function ListeEntree($debut,$fin,$idanneescolaire,$idcompte,$pdo)
{
	if($idcompte=="")
	{
		
		$req=(' SELECT  
						distinct
						compte.id as idcompte,
						compte.libelle as libellecompte,
						entreesortie.id as identreesortie,
						entreesortie.libelle as libelleentreesortie,
						entreesortie.montant as montantentreesortie,
						entreesortie.dateoperation as dateentreesortie,
						entreesortie.datesaisie as datesaisie,
						entreesortie.idtypeentreesortie as idtypeentreesortie,
						entreesortie.montantcompte as montantcompte,
						entreesortie.ficheattache as ficheattache,
						user.nom_user as nomuser,
						user.prenom_user as prenomuser,
						user2.nom_user as nomuser2,
						user2.prenom_user as prenomuser2,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire

				FROM compte,entreesortie,utilisateur as user,utilisateur as user2,anneescolaire
				
				WHERE
				anneescolaire.id=:idanneescolaire
				AND
				entreesortie.idanneescolaire=anneescolaire.id
				AND
				compte.id=entreesortie.comptemouvement
				AND
				entreesortie.iduserajout=user.id
				AND
				entreesortie.iduserauto=user2.id
				AND
				entreesortie.statut=1
				AND
				entreesortie.idtypeentreesortie=1
				AND
				entreesortie.dateoperation BETWEEN :debut AND :fin

				ORDER BY entreesortie.dateoperation ASC');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
	}
	else
	{
		
		$req=(' SELECT  
						distinct
						compte.id as idcompte,
						compte.libelle as libellecompte,
						entreesortie.id as identreesortie,
						entreesortie.libelle as libelleentreesortie,
						entreesortie.montant as montantentreesortie,
						entreesortie.dateoperation as dateentreesortie,
						entreesortie.datesaisie as datesaisie,
						entreesortie.idtypeentreesortie as idtypeentreesortie,
						entreesortie.montantcompte as montantcompte,
						entreesortie.ficheattache as ficheattache,
						user.nom_user as nomuser,
						user.prenom_user as prenomuser,
						user2.nom_user as nomuser2,
						user2.prenom_user as prenomuser2,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire

				FROM compte,entreesortie,utilisateur as user,utilisateur as user2,anneescolaire
				
				WHERE
				compte.id=:idcompte
				AND
				anneescolaire.id=:idanneescolaire
				AND
				entreesortie.idanneescolaire=anneescolaire.id
				AND
				compte.id=entreesortie.comptemouvement
				AND
				entreesortie.iduserajout=user.id
				AND
				entreesortie.iduserauto=user2.id
				AND
				entreesortie.statut=1
				AND
				entreesortie.idtypeentreesortie=1
				AND
				entreesortie.dateoperation BETWEEN :debut AND :fin

				ORDER BY entreesortie.dateoperation ASC');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
		$stmt->bindParam(':idcompte',$idcompte,PDO::PARAM_STR);
	}	
    ?>
	<table class="table table-striped table-bordered" style="font-size:12px" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">Type Op&eacute;ration</th>
				<th style="width:15%;">Libelle</th>
				<th style="width:10%;">Montant</th>
				<th style="width:10%;">Cumul</th>
				<th style="width:11%;">Date Op&eacute;ration</th>
				<th style="width:11%;">Compte Mouvement&eacute;</th>
				<th style="width:11%;">Fichier attach&eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt->execute();	
		$ligne=0;
		$totalentree=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idcompte = $donnees['idcompte'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$libellecompte = $donnees['libellecompte'];
			$identreesortie = $donnees['identreesortie'];
			$libelleentreesortie = $donnees['libelleentreesortie'];
			$montantentreesortie = $donnees['montantentreesortie'];
			$totalentree = $totalentree + $montantentreesortie;
			$datesaisie = $donnees['datesaisie'];
			$dateentreesortie = $donnees['dateentreesortie'];
			$tab = explode("-",$dateentreesortie);
			$nom = $donnees['nomuser'].' '.$donnees['prenomuser'];
			$nom2 = $donnees['nomuser2'].' '.$donnees['prenomuser2'];
			$idtypeentreesortie = $donnees['idtypeentreesortie'];
			$ficheattache = $donnees['ficheattache'];
			if($idtypeentreesortie==2)
			{
				$typeentreesortie="Sortie";
			}
			else
			{
				$typeentreesortie="Entr&eacute;e";
			}
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="identreesortie<?php echo $ligne;?>" value="<?php echo $identreesortie;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $typeentreesortie;?></a>
			    </td>
				<td>
					<a><?php echo $libelleentreesortie;?></a>
			    </td>
				<td>
					<a><?php echo number_format($montantentreesortie,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalentree,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $libellecompte;?></a>
			    </td>
				<td>
					<?php
					if($ficheattache!="")
					{
						?><a href="entreesorties/<?php echo $ficheattache;?>" target="_blank"><span style="color:red;">[T&eacute;l&eacute;charger]</span></a><?php
					}
					?>
			    </td>
			</tr><?php
		}
		?>
		<tr>
			<td colspan="2">
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					Total entr&eacute;e (FCFA)
				</h6>
			</td>
			<td></td>
			<td></td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($totalentree,"0",""," ");?>
				</h6>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?> 
	</table>
	<input type="hidden" name="nbreentree" value="<?php echo $ligne;?>"/><?php
}

function ListeSortie($debut,$fin,$idanneescolaire,$idcompte,$pdo)
{
	if($idcompte=="")
	{
		
		$req=(' SELECT  
						distinct
						compte.id as idcompte,
						compte.libelle as libellecompte,
						entreesortie.id as identreesortie,
						entreesortie.libelle as libelleentreesortie,
						entreesortie.montant as montantentreesortie,
						entreesortie.dateoperation as dateentreesortie,
						entreesortie.datesaisie as datesaisie,
						entreesortie.idtypeentreesortie as idtypeentreesortie,
						entreesortie.montantcompte as montantcompte,
						entreesortie.ficheattache as ficheattache,
						user.nom_user as nomuser,
						user.prenom_user as prenomuser,
						user2.nom_user as nomuser2,
						user2.prenom_user as prenomuser2,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire

				FROM compte,entreesortie,utilisateur as user,utilisateur as user2,anneescolaire
				
				WHERE
				anneescolaire.id=:idanneescolaire
				AND
				entreesortie.idanneescolaire=anneescolaire.id
				AND
				compte.id=entreesortie.comptemouvement
				AND
				entreesortie.iduserajout=user.id
				AND
				entreesortie.iduserauto=user2.id
				AND
				entreesortie.statut=1
				AND
				entreesortie.idtypeentreesortie=2
				AND
				entreesortie.dateoperation BETWEEN :debut AND :fin

				ORDER BY entreesortie.dateoperation ASC');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
	}
	else
	{
		
		$req=(' SELECT  
						distinct
						compte.id as idcompte,
						compte.libelle as libellecompte,
						entreesortie.id as identreesortie,
						entreesortie.libelle as libelleentreesortie,
						entreesortie.montant as montantentreesortie,
						entreesortie.dateoperation as dateentreesortie,
						entreesortie.datesaisie as datesaisie,
						entreesortie.idtypeentreesortie as idtypeentreesortie,
						entreesortie.montantcompte as montantcompte,
						entreesortie.ficheattache as ficheattache,
						user.nom_user as nomuser,
						user.prenom_user as prenomuser,
						user2.nom_user as nomuser2,
						user2.prenom_user as prenomuser2,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire

				FROM compte,entreesortie,utilisateur as user,utilisateur as user2,anneescolaire
				
				WHERE
				compte.id=:idcompte
				AND
				anneescolaire.id=:idanneescolaire
				AND
				entreesortie.idanneescolaire=anneescolaire.id
				AND
				compte.id=entreesortie.comptemouvement
				AND
				entreesortie.iduserajout=user.id
				AND
				entreesortie.iduserauto=user2.id
				AND
				entreesortie.statut=1
				AND
				entreesortie.idtypeentreesortie=2
				AND
				entreesortie.dateoperation BETWEEN :debut AND :fin

				ORDER BY entreesortie.dateoperation ASC');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
		$stmt->bindParam(':idcompte',$idcompte,PDO::PARAM_STR);
	}	
    ?>
	<table class="table table-striped table-bordered" style="font-size:11px" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">Type Op&eacute;ration</th>
				<th style="width:15%;">Libelle</th>
				<th style="width:10%;">Montant</th>
				<th style="width:10%;">Cumul</th>
				<th style="width:11%;">Date Op&eacute;ration</th>
				<th style="width:11%;">Compte mouvement&eacute;</th>
				<th style="width:11%;">Fichier attach&eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt->execute();	
		$ligne=0;
		$totalsortie=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idcompte = $donnees['idcompte'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$libellecompte = $donnees['libellecompte'];
			$identreesortie = $donnees['identreesortie'];
			$libelleentreesortie = $donnees['libelleentreesortie'];
			$montantentreesortie = $donnees['montantentreesortie'];
			$totalsortie = $totalsortie + $montantentreesortie;
			$datesaisie = $donnees['datesaisie'];
			$dateentreesortie = $donnees['dateentreesortie'];
			$tab = explode("-",$dateentreesortie);
			$nom = $donnees['nomuser'].' '.$donnees['prenomuser'];
			$nom2 = $donnees['nomuser2'].' '.$donnees['prenomuser2'];
			$idtypeentreesortie = $donnees['idtypeentreesortie'];
			$ficheattache = $donnees['ficheattache'];
			if($idtypeentreesortie==2)
			{
				$typeentreesortie="Sortie";
			}
			else
			{
				$typeentreesortie="Entr&eacute;e";
			}
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="identreesortie<?php echo $ligne;?>" value="<?php echo $identreesortie;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $typeentreesortie;?></a>
			    </td>
				<td>
					<a><?php echo $libelleentreesortie;?></a>
			    </td>
				<td>
					<a><?php echo number_format($montantentreesortie,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo number_format($totalsortie,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $libellecompte;?></a>
			    </td>
				<td>
					<?php
					if($ficheattache!="")
					{
						?><a href="entreesorties/<?php echo $ficheattache;?>" target="_blank"><span style="color:red;">[T&eacute;l&eacute;charger]</span></a><?php
					}
					?>
			    </td>
			</tr><?php
		}
		?>
		<tr>
			<td colspan="2">
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					Total sortie (FCFA)
				</h6>
			</td>
			<td></td>
			<td></td>
			<td>
				<h6 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					<?php echo number_format($totalsortie,"0",""," ");?>
				</h6>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?> 
	</table>
	<input type="hidden" name="nbresortie" value="<?php echo $ligne;?>"/><?php
}

function ListeEntreeSortie($debut,$fin,$idanneescolaire,$idcompte,$pdo)
{
	if($idcompte=="")
	{
		
		$req=(' SELECT  
						distinct
						compte.id as idcompte,
						compte.libelle as libellecompte,
						entreesortie.id as identreesortie,
						entreesortie.libelle as libelleentreesortie,
						entreesortie.montant as montantentreesortie,
						entreesortie.dateoperation as dateentreesortie,
						entreesortie.datesaisie as datesaisie,
						entreesortie.idtypeentreesortie as idtypeentreesortie,
						entreesortie.montantcompte as montantcompte,
						entreesortie.ficheattache as ficheattache,
						user.nom_user as nomuser,
						user.prenom_user as prenomuser,
						user2.nom_user as nomuser2,
						user2.prenom_user as prenomuser2,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire

				FROM compte,entreesortie,utilisateur as user,utilisateur as user2,anneescolaire
				
				WHERE
				anneescolaire.id=:idanneescolaire
				AND
				entreesortie.idanneescolaire=anneescolaire.id
				AND
				compte.id=entreesortie.comptemouvement
				AND
				entreesortie.iduserajout=user.id
				AND
				entreesortie.iduserauto=user2.id
				AND
				entreesortie.statut=1
				AND
				entreesortie.idtypeentreesortie=2
				AND
				entreesortie.dateoperation BETWEEN :debut AND :fin

				ORDER BY entreesortie.dateoperation ASC');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
	}
	else
	{
		
		$req=(' SELECT  
						distinct
						compte.id as idcompte,
						compte.libelle as libellecompte,
						entreesortie.id as identreesortie,
						entreesortie.libelle as libelleentreesortie,
						entreesortie.montant as montantentreesortie,
						entreesortie.dateoperation as dateentreesortie,
						entreesortie.datesaisie as datesaisie,
						entreesortie.idtypeentreesortie as idtypeentreesortie,
						entreesortie.montantcompte as montantcompte,
						entreesortie.ficheattache as ficheattache,
						user.nom_user as nomuser,
						user.prenom_user as prenomuser,
						user2.nom_user as nomuser2,
						user2.prenom_user as prenomuser2,
						anneescolaire.id as idanneescolaire,
						anneescolaire.libelle as libelleanneescolaire

				FROM compte,entreesortie,utilisateur as user,utilisateur as user2,anneescolaire
				
				WHERE
				compte.id=:idcompte
				AND
				anneescolaire.id=:idanneescolaire
				AND
				entreesortie.idanneescolaire=anneescolaire.id
				AND
				compte.id=entreesortie.comptemouvement
				AND
				entreesortie.iduserajout=user.id
				AND
				entreesortie.iduserauto=user2.id
				AND
				entreesortie.statut=1
				AND
				entreesortie.idtypeentreesortie=2
				AND
				entreesortie.dateoperation BETWEEN :debut AND :fin

				ORDER BY entreesortie.dateoperation ASC');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':debut',$debut,PDO::PARAM_STR);
		$stmt->bindParam(':fin',$fin,PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
		$stmt->bindParam(':idcompte',$idcompte,PDO::PARAM_STR);
	}
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" style="font-size:12px" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">Type op&eacute;ration</th>
				<th style="width:15%;">Libelle</th>
				<th style="width:10%;">Montant</th>
				<th style="width:10%;">Date op&eacute;ration</th>
				<th style="width:11%;">Compte mouvement&eacute;</th>
				<th style="width:11%;">Solde</th>
				<th style="width:11%;">Fichier attach&eacute;</th>
		    </tr>
	    </thead>
		<?php		
		$stmt->execute();	
		$ligne=0;
		$totalentreesortie=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idcompte = $donnees['idcompte'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$libellecompte = $donnees['libellecompte'];
			$identreesortie = $donnees['identreesortie'];
			$libelleentreesortie = $donnees['libelleentreesortie'];
			$montantentreesortie = $donnees['montantentreesortie'];
			$totalentreesortie = $totalentreesortie + $montantentreesortie;
			$datesaisie = $donnees['datesaisie'];
			$dateentreesortie = $donnees['dateentreesortie'];
			$tab = explode("-",$dateentreesortie);
			$nom = $donnees['nomuser'].' '.$donnees['prenomuser'];
			$nom2 = $donnees['nomuser2'].' '.$donnees['prenomuser2'];
			$idtypeentreesortie = $donnees['idtypeentreesortie'];
			$ficheattache = $donnees['ficheattache'];
			if($idtypeentreesortie==2)
			{
				$typeentreesortie="Sortie";
			}
			else
			{
				$typeentreesortie="Entr&eacute;e";
			}
			$solde=getEntreeCompteEtat($idanneescolaire,$idcompte,$datesaisie,$pdo)-getSortieCompteEtat($idanneescolaire,$idcompte,$datesaisie,$pdo);
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="identreesortie<?php echo $ligne;?>" value="<?php echo $identreesortie;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $typeentreesortie;?></a>
			    </td>
				<td>
					<a><?php echo $libelleentreesortie;?></a>
			    </td>
				<td>
					<a><?php echo number_format($montantentreesortie,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $libellecompte;?></a>
			    </td>
				<td>
					<a><?php echo number_format($solde,"0",""," ");?></a>
			    </td>
				<td>
					<?php
					if($ficheattache!="")
					{
						?><a href="entreesorties/<?php echo $ficheattache;?>" target="_blank"><span style="color:red;">[T&eacute;l&eacute;charger]</span></a><?php
					}
					?>
			    </td>
			</tr><?php
        }
		?>
		<tr>
			<td colspan="2"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?> 
	</table>
	<input type="hidden" name="nbreentreesortie" value="<?php echo $ligne;?>"/><?php
}

function EtatRecouvrement($idpaiementtype,$libellepaiementtype,$libelleanneescolaire,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					count(elevesalle.id) as nbreelevesalle

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
	<table class="table table-striped table-bordered" style="font-size:12px" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">Type de frais</th>
				<th style="width:10%;">Niveau</th>
				<th style="width:10%;">Effectif (r&eacute;el)</th>
				<th style="width:10%;">Effectif (Boursier)</th>
				<th style="width:10%;">Total attendu (FCFA)</th>
				<th style="width:13%;">Total recouvr&eacute; (FCFA)</th>
				<th style="width:13%;">Total restant (FCFA)</th>
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
			$nbreelevesalle = $donnees['nbreelevesalle'];
			$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			$nbreouiboursier = getNbreOuiBoursier($idanneescolaire,$idclasse,$pdo);
			$nbrenonboursier = getNbreNonBoursier($idanneescolaire,$idclasse,$pdo);
			$montantpaiementtypeclasseouiboursier = getMontantPaiementTypeClasseOuiBoursier($idanneescolaire,$idclasse,$idpaiementtype,$pdo);

			$totalattendu = ($montantpaiementtypeclasse*$nbrenonboursier)+$montantpaiementtypeclasseouiboursier;
			$totalrecouvre = getMontantPaiementFraisAnneeScolaire($idpaiementtypeclasse,$idanneescolaire,$pdo)+getMontantPaiementFraisOuiBoursier($idclasse,$idanneescolaire,$idpaiementtype,$pdo);
			$totalrestant = $totalattendu-$totalrecouvre;
			
			$total_totalattendu = $total_totalattendu+$totalattendu;
			$total_totalrecouvre = $total_totalrecouvre+$totalrecouvre;
			$total_totalrestant = $total_totalrestant+$totalrestant;
			$total_effectif = $total_effectif+$nbreelevesalle;
			$total_boursier = $total_boursier+$nbreouiboursier;
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idclasse.'*'.$idpaiementtype.'*'.$idanneescolaire.'*'.$codeclasse;?>"/>
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
					Totaux (FCFA)
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

function EtatRecouvrementInscription($idpaiementtype,$libellepaiementtype,$libelleanneescolaire,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					count(eleveanneescolaire.id) as nbreeleveanneescolaire

			FROM eleveanneescolaire,classe,elevestatutclasse,elevestatutetablissement
			WHERE
			classe.idclasse=eleveanneescolaire.idclasse
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.inscrit=elevestatutetablissement.id
			
			GROUP BY classe.idclasse,classe.codeclasse DESC');	
    ?>
	<table class="table table-striped table-bordered" style="font-size:12px" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">Ann&eacute;e scolaire</th>
				<th style="width:10%;">Type de frais</th>
				<th style="width:10%;">Niveau</th>
				<th style="width:10%;">Effectif (r&eacute;el)</th>
				<th style="width:10%;">Effectif (Nouveau)</th>
				<th style="width:10%;">Total attendu (FCFA)</th>
				<th style="width:13%;">Total recouvr&eacute; (FCFA)</th>
				<th style="width:13%;">Total restant (FCFA)</th>
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
		$total_nouveau_inscrit=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$nbreeleveanneescolaire = $donnees['nbreeleveanneescolaire'];
			$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			$nbreouiboursier = getNbreOuiBoursier($idanneescolaire,$idclasse,$pdo);
			$nbrenonboursier = getNbreNonBoursier($idanneescolaire,$idclasse,$pdo);
			$nbrenouveauinscrit = getNbreNouveauInscrit($idanneescolaire,$idclasse,$pdo);
			$montantpaiementtypeclasseouiboursier = getMontantPaiementTypeClasseOuiBoursier($idanneescolaire,$idclasse,$idpaiementtype,$pdo);
			$totalattendu = ($montantpaiementtypeclasse*$nbrenonboursier)+$montantpaiementtypeclasseouiboursier;
			$totalrecouvre = getMontantPaiementFraisAnneeScolaire($idpaiementtypeclasse,$idanneescolaire,$pdo)+getMontantPaiementFraisOuiBoursier($idclasse,$idanneescolaire,$idpaiementtype,$pdo);
			$totalrestant = $totalattendu-$totalrecouvre;
			
			$total_totalattendu = $total_totalattendu+$totalattendu;
			$total_totalrecouvre = $total_totalrecouvre+$totalrecouvre;
			$total_totalrestant = $total_totalrestant+$totalrestant;
			$total_effectif = $total_effectif+$nbreeleveanneescolaire;
			$total_nouveau_inscrit = $total_nouveau_inscrit+$nbrenouveauinscrit;
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idclasse.'*'.$idpaiementtype.'*'.$idanneescolaire.'*'.$codeclasse;?>"/>
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
					<a><?php echo $nbreeleveanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $nbrenouveauinscrit;?></a>
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
					Totaux (FCFA)
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
					<?php echo number_format($total_nouveau_inscrit,"0",""," ");?>
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

function EtatRecouvrementDetail($idpaiementtype,$idanneescolaire,$idclasse,$pdo)
{
	$req=(' SELECT  
					distinct 
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleveanneescolaire.id as ideleveanneescolaire,
					eleveanneescolaire.inscrit as inscrit,
					eleveanneescolaire.etat as etat,
					eleveanneescolaire.boursier as boursier,
					elevesalle.statut as statut,
					salle.codesalle as codesalle,
					anneescolaire.libelle as anneescolaire
					
			FROM eleve,eleveanneescolaire,elevesalle,salle,anneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.id=:idanneescolaire
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			salle.idclasse=:idclasse
			
			ORDER BY eleve.nom_eleve,eleve.prenom_eleve ASC');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color: #eee;">
				<th style="width:22%;">Nom & Pr&eacute;nom</th>
				<th style="width:11%;">Statut Etab.</th>
				<th style="width:17%;">Total Doit</th>
				<th style="width:16%;">Total Vers&eacute;</th>
				<th style="width:11%;">Total Restant</th>
				<th style="width:16%">Statut Paiement</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$statutclasse="";
		$statutpaiement="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$codesalle = $donnees['codesalle'];
			$anneescolaire = $donnees['anneescolaire'];
			$boursier = $donnees['boursier'];
			$statut = $donnees['statut'];
			if($statut==0 AND $boursier=="Oui")
			{
				$statutclasse="Boursier-Abandon";
			}
			elseif($statut==0 AND $boursier=="")
			{
				$statutclasse="Abandon";
			}	
			elseif($statut==1 AND $boursier=="Oui")
			{
				$statutclasse="Boursier-Actif";
			}
			else
			{
				$statutclasse="Actif";
			}
			$montantpaiementtypeclasse = 0;
			if($boursier=="Oui")
			{
				$response = getMontantPaiementTypeClasseOuiBoursier_($idanneescolaire,$idclasse,$idpaiementtype,$ideleveanneescolaire,$pdo);
				$tab = explode("*",trim($response));
				$montantpaiementtypeclasse = $tab[0];
				$idpaiementtypeclasse = $tab[1];
				$remise = $tab[2];
				$montantpaiementtypeclasse = $montantpaiementtypeclasse-($montantpaiementtypeclasse*$remise/100);
				$montantpaiementeleve = getMontantPaiementFraisEleveAnneeScolaire($idpaiementtypeclasse,$ideleveanneescolaire,$pdo);
			}
			else
			{
				$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);
				$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$pdo);
				$tab = explode("*",trim($response));
				$montantpaiementtypeclasse = $tab[0];
				$idpaiementtypeclasse = $tab[1];
				$montantpaiementeleve = getMontantPaiementFraisEleveAnneeScolaire($idpaiementtypeclasse,$ideleveanneescolaire,$pdo);
			}
			$montantrestant = $montantpaiementtypeclasse-$montantpaiementeleve;
			if($montantrestant==0)
			{
				$statutpaiement="En r&egrave;gle";
			}
			else
			{
				$statutpaiement="Non en r&egrave;gle";
			}
			?>
			<tr>
				<td>
					<a><?php echo $nomeleve;?></a>
				</td>
				<td>
					<a><?php echo $statutclasse;?></a>
				</td>
				<td>
					<a><?php echo number_format($montantpaiementtypeclasse,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($montantpaiementeleve,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($montantrestant,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo $statutpaiement;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreetatrecouvrementdetail" value="<?php echo $ligne;?>"/><?php
}

function getMontantPaiementTypeClasse($idpaiementtype,$idclasse,$idanneescolaire,$statutanneescolaire,$pdo)
{	
	if($statutanneescolaire==1)
	{
		$req=(' SELECT paiementtypeclasse.montant as montant,paiementtypeclasse.id as id
		
				FROM paiementtypeclasse
				WHERE 
				paiementtypeclasse.idclasse=:idclasse
				AND
				paiementtypeclasse.idpaiementtype=:idpaiementtype
				AND
				paiementtypeclasse.statut=1
				AND
				paiementtypeclasse.ideleveanneescolaire is null
				
				ORDER BY paiementtypeclasse.id DESC LIMIT 0,1');
	}
	else
	{
		$req=(' SELECT paiementtypeclasse.montant as montant,paiementtypeclasse.id as id
		
				FROM paiementtypeclasse
				WHERE 
				paiementtypeclasse.idclasse=:idclasse
				AND
				paiementtypeclasse.idpaiementtype=:idpaiementtype
				AND
				paiementtypeclasse.statut=0
				AND
				paiementtypeclasse.ideleveanneescolaire is null
				
				ORDER BY paiementtypeclasse.id DESC LIMIT 0,1');
	}			
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

function getMontantPaiementFraisEleveAnneeScolaire($id,$idEleveAnneeScolaire,$pdo)
{
	$req=(' SELECT sum(paiementfrais.montant)as Montant
			FROM paiementfrais
			WHERE 
			paiementfrais.ideleveanneescolaire=:idEleveAnneeScolaire
			AND
			paiementfrais.idpaiementtypeclasse=:id
			AND
			paiementfrais.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idEleveAnneeScolaire',$idEleveAnneeScolaire,PDO::PARAM_INT);
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
			FROM eleveanneescolaire,elevesalle	
			WHERE 
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			eleveanneescolaire.boursier="Oui"
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.statut=1');
			
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

function getMontantPaiementTypeClasseOuiBoursier($idanneescolaire,$idclasse,$idpaiementtype,$pdo)
{
	$req=(' SELECT distinct
					paiementtypeclasse.montant as montant,
					paiementtypeclasse.remise as remise,
					paiementtypeclasse.ideleveanneescolaire as ideleveanneescolaire
					
			FROM eleveanneescolaire,paiementtypeclasse	
			WHERE 
			eleveanneescolaire.id=paiementtypeclasse.ideleveanneescolaire
			AND
			paiementtypeclasse.idpaiementtype=:idpaiementtype
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
	while($donnees = $stmt->fetch())
	{
	    $resultat = $resultat+($donnees['montant']-($donnees['montant']*$donnees['remise']/100));
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
			FROM eleveanneescolaire,elevesalle	
			WHERE 
			eleveanneescolaire.idanneescolaire=:idanneescolaire
			AND
			(eleveanneescolaire.boursier="Non" OR eleveanneescolaire.boursier is null)
			AND
			eleveanneescolaire.idclasse=:idclasse
			AND
			eleveanneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.statut=1');
			
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


function getAllAnneeScolaire($pdo)
{
	$req=(' SELECT  anneescolaire.id,
	                anneescolaire.libelle
					
			FROM anneescolaire ORDER BY anneescolaire.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idanneescolaire" required="required" style="font-size:12px;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			echo '<option value="'.$id.'">'.$libelle.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllAnneeScolaireSelected($anneescolaire,$pdo)
{
	$req=(' SELECT  anneescolaire.id,
	                anneescolaire.libelle
					
			FROM anneescolaire ORDER BY anneescolaire.id DESC');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idanneescolaire" required="required" style="font-size:12px;">
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

function getLibelleCompte($id,$pdo)
{
	$req=(' SELECT compte.libelle
			FROM compte
			WHERE
			compte.id=:idcompte
			AND
			compte.statut=1');
	$libelle="";
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idcompte',$id,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$libelle = $donnees['libelle'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $libelle;
}

function getAllPaiementType($pdo)
{
	$req=(' SELECT  paiementtype.id as idPaiementType,
	                paiementtype.libelle as libellePaiementType
					
			FROM paiementtype');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-2 col-xs-12" name="idPaiementType" required="required" style="font-size:12px;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idPaiementType = $donnees['idPaiementType'];
			$libellePaiementType = $donnees['libellePaiementType'];
			
			echo '<option value="'.$idPaiementType.'">'.$libellePaiementType.'</option>';	
		}		
	?>
	</select><?php
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
	<select class="form-control col-md-2 col-xs-12" name="idPaiementType" required="required" style="font-size:12px;">
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
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getEntreeCompteEtat($idAnneeScolaire,$idCompte,$datesaisie,$pdo)
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
	$stmt->bindParam(':datesaisie', $datesaisie, PDO::PARAM_STR);
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

function getSortieCompteEtat($idAnneeScolaire,$idCompte,$datesaisie,$pdo)
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
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
	$stmt->bindParam(':idAnneeScolaire', $idAnneeScolaire, PDO::PARAM_INT);
	$stmt->bindParam(':datesaisie', $datesaisie, PDO::PARAM_STR);
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

function getAllCompte($pdo)
{
	$req=(' SELECT 
					compte.id as idcompte,
	                compte.libelle as libellecompte

			FROM compte
			WHERE
			compte.statut=1');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control" name="idcompte" style="font-size:12px;">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idcompte = $donnees['idcompte'];
			$libellecompte = $donnees['libellecompte'];

			echo '<option value="'.$idcompte.'">'.$libellecompte.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllCompteSelected($idcompteselected,$pdo)
{
	$req=(' SELECT 
					compte.id as idcompte,
	                compte.libelle as libellecompte

			FROM compte
			WHERE
			compte.statut=1');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control" name="idcompte" style="font-size:12px;">
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

function getNbreEleveAbandonne($idanneescolaire,$statut,$idclasse,$pdo)
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
			AND
			salle.idclasse=:idclasse
			
			GROUP BY eleveanneescolaire.idanneescolaire,salle.idclasse');
			
	$stmt_ = $pdo->prepare($req);
	$stmt_->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt_->bindParam(':statut', $statut, PDO::PARAM_INT);
	$stmt_->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
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