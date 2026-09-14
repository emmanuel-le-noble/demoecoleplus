<?php
function getNbreEntreeSortieSansFichierAttache($idanneescolaire,$pdo)
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
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nbreentreesortiesansfichierattache'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
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
	<select class="form-control col-md-2 col-xs-12" required="required" name="idanneescolaire" id="idanneescolaire" style="font-size:12px;" onchange="makeRequest('PaiementFraisListeEleve.php','idanneescolaire','paiementfraislisteeleve')">
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
	<select class="form-control col-md-2 col-xs-12" required="required" name="idanneescolaire" id="idanneescolaire"  style="font-size:12px;" onchange="makeRequest('PaiementFraisListeEleve.php','idanneescolaire','paiementfraislisteeleve')">
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

function getAllEleve($idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct
	                eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleveanneescolaire.boursier as boursier,
					eleveanneescolaire.idclasse as idclasse,
					eleveanneescolaire.id as ideleveanneescolaire,
					eleveanneescolaire.inscrit as inscrit
					
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
	<select class="select2_single form-control" tabindex="-1" name="ideleve" id="ideleve" onchange="makeRequest('PaiementFraisEleve.php','ideleve','paiementfraiseleve')">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$ideleve = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$boursier = $donnees['boursier'];
			$idclasse = $donnees['idclasse'];
			$inscrit = $donnees['inscrit'];
			
			echo '<option value="'.$ideleve.'*'.$ideleveanneescolaire.'*'.$boursier.'*'.$idclasse.'*'.$idanneescolaire.'*'.$inscrit.'">'.$nomeleve.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function verifCaissePaiement($idpaiementfrais,$type,$pdo)
{
	$req=(' SELECT count(*) as exist
			FROM caissepaiement
			WHERE 
			caissepaiement.idpaiementfrais=:idpaiementfrais
			AND
			caissepaiement.type=:type');
	$resultat=0;
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpaiementfrais',$idpaiementfrais,PDO::PARAM_INT);
	$stmt->bindParam(':type',$type,PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['exist'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getMontantEleveAnneeScolaire($idpaiementtypeclasse,$ideleveanneescolaire,$pdo)
{
	$req=(' SELECT sum(paiementfrais.montant)as montant
			FROM paiementfrais
			WHERE 
			paiementfrais.idpaiementtypeclasse=:idpaiementtypeclasse
			AND
			paiementfrais.ideleveanneescolaire=:ideleveanneescolaire
			AND
			paiementfrais.statut=1');
			
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':ideleveanneescolaire',$ideleveanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':idpaiementtypeclasse',$idpaiementtypeclasse,PDO::PARAM_INT);
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

/*function DeleteElevePaiement($idpaiementfrais,$iduser,$pdo)
{
	$req=' UPDATE paiementfrais SET statut=0,iduserdelete=:iduserdelete WHERE paiementfrais.id=:id';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id', $idpaiementfrais, PDO::PARAM_INT);
    $stmt ->bindParam(':iduserdelete', $iduser, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}*/

function DeleteElevePaiement($idpaiementfrais,$iduser,$pdo)
{
	$req=' DELETE FROM paiementfrais WHERE paiementfrais.id=:id';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id', $idpaiementfrais, PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function PaiementEleve($ideleveanneescolaire,$boursier,$inscrit,$idclasse,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct
					paiementtype.id as idpaiementtype,
					paiementtype.libelle as libellepaiementtype,
					paiementtypeclasse.id as idpaiementtypeclasse,
					paiementtypeclasse.montant as montantclasse,
					paiementtypeclasse.remise as remise,
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
			AND
			paiementtypeclasse.ideleveanneescolaire is null
			
			ORDER BY paiementtype.id ASC');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idclasse',$idclasse,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
    ?>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;font-size:14px;" class="btn btn-success btn-xs">
	::: Veuillez cocher les frais &agrave; payer :::</label><br/><br/>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th> 
				<th style="width:13%;">Classe</th>
				<th style="width:13%;">Frais</th>
				<th style="width:13%;">M. Associ&eacute;</th>
				<th style="width:13%;">M. R&eacute;gl&eacute;</th>
				<th style="width:13%;">M. Restant</th>
				<th style="width:26%;">Montant</th>
		    </tr>
	    </thead>
		<tbody>
		<?php		
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$idpaiementtype = $donnees['idpaiementtype'];
			$libellepaiementtype = $donnees['libellepaiementtype'];
			$idpaiementtypeclasse = $donnees['idpaiementtypeclasse'];
			$remise = $donnees['remise'];
			$boursier = is_numeric($boursier) ? $boursier : 0;
			$montantclasse = $donnees['montantclasse']-$boursier;
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$codeclasse = $donnees['codeclasse'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$montant = getMontantEleveAnneeScolaire($idpaiementtypeclasse,$ideleveanneescolaire,$pdo);
			?>
			<tr>
				<td align="center">
					<div>
						<?php 
						if($inscrit==2 AND $idpaiementtype==3)
						{
							?>
							<input class="flat" type="checkbox" disabled="disabled" name="idpaiementtypeclasse<?php echo $ligne;?>" value="<?php echo $idpaiementtypeclasse;?>"/>
							<input type="hidden" name="ideleveanneescolaire<?php echo $ligne;?>" value="<?php echo $ideleveanneescolaire;?>"/>
							<input type="hidden" name="idanneescolaire<?php echo $ligne;?>" value="<?php echo $idanneescolaire;?>"/>
							<?php
						}
						else
						{
							if($montantclasse==$montant)
							{
								?>
								<input class="flat" type="checkbox" disabled="disabled" name="idpaiementtypeclasse<?php echo $ligne;?>" value="<?php echo $idpaiementtypeclasse;?>"/>
								<input type="hidden" name="ideleveanneescolaire<?php echo $ligne;?>" value="<?php echo $ideleveanneescolaire;?>"/>
								<input type="hidden" name="idanneescolaire<?php echo $ligne;?>" value="<?php echo $idanneescolaire;?>"/>
								<?php
							}
							else
							{
								?>
								<input class="flat" type="checkbox" checked="checked" name="idpaiementtypeclasse<?php echo $ligne;?>" value="<?php echo $idpaiementtypeclasse;?>"/>
								<input type="hidden" name="ideleveanneescolaire<?php echo $ligne;?>" value="<?php echo $ideleveanneescolaire;?>"/>
								<input type="hidden" name="idanneescolaire<?php echo $ligne;?>" value="<?php echo $idanneescolaire;?>"/>
								<?php
							}
						}
						?>
					</div>
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
						if($inscrit==2 AND $idpaiementtype==3)
						{
							?><input type="number" class="form-control" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" readonly="yes"/><?php
						}
						else
						{
							if($montantclasse==$montant)
							{
								?><input type="number" class="form-control" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" readonly="yes"/><?php
							}
							else
							{
								?><input type="number" class="form-control" name="montant<?php echo $ligne;?>" style="background-color:#fbbc05;" autocomplete="off"/><?php
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

function ListeEtudiantPaiementFrais($ideleveanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as libellepaiementtype,
					paiementfrais.montant as montant,
					paiementfrais.date as date,
					paiementfrais.id as idpaiementfrais
					
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
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms" class="btn btn-info btn-xs">
	::: Historique de paiement pour l'ann&eacute;e en cours:::</label><br/><br/>
	<table class="table table-striped table-bordered" width="100%" style="font-size:12px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;">#</th> 
				<th>Montant Pay&eacute; (FCFA)</th>
				<th>Date paiement</th>
				<th>Type de Frais</th>
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
			$libellepaiementtype = $donnees['libellepaiementtype'];
			$montant = $donnees['montant'];
			$idpaiementfrais = $donnees['idpaiementfrais'];
			$date = $donnees['date'];
			$tab = explode("-",$date);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idpaiementfrais<?php echo $ligne;?>" value="<?php echo $idpaiementfrais;?>"/>						
				</td>
				<td>
					<a><?php echo number_format($montant,"0",""," ");?></a>
				</td>
				<td>
					<a><?php echo $tab[2]."/".$tab[1]."/".$tab[0];?></a>
				</td>
				<td>
					<a><?php echo $libellepaiementtype;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ListePaiementFrais($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct 
					paiementtype.libelle as libelle,
					paiementfrais.montant as montant,
					paiementfrais.date as datepaiement,
					paiementfrais.id as idpaiementfrais,
					utilisateur.nom_user as nomuser,
					utilisateur.prenom_user as prenomuser,
					classe.codeclasse as codeclasse,
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					anneescolaire.libelle as libelleanneescolaire
					
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
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
			    <th style="width:5%;font-weight:bold;color:#000">#</th> 
				<th style="width:15%;font-weight:bold;color:#000">Nom & Pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:12%;font-weight:bold;color:#000">Classe</th>
				<th style="width:15%;font-weight:bold;color:#000">Montant Pay&eacute; (FCFA)</th>
				<th style="width:10%;font-weight:bold;color:#000">Date Paiement</th>
				<th style="width:10%;font-weight:bold;color:#000">Type Frais</th>
				<th style="width:10%;font-weight:bold;color:#000">Ann&eacute;e Scolaire</th>
				<th style="width:10%;font-weight:bold;color:#000">Enregistr&eacute; Par</th>
				<th style="width:8%;font-weight:bold;color:#000">Action(s)</th>
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
			$nom = $donnees['nomuser'].' '.$donnees['prenomuser'];
			$libelle = $donnees['libelle'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$montant = $donnees['montant'];
			$idpaiementfrais = $donnees['idpaiementfrais'];
			$ideleve = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$codeclasse = $donnees['codeclasse'];
			$datepaiement = $donnees['datepaiement'];
			$tab = explode("-",$datepaiement);
			$verifcaissepaiement = verifCaissePaiement($idpaiementfrais,"PaiementFraisScolarite",$pdo);
			?>
			<tr>
				<td>
				    <?php 
						if($verifcaissepaiement==0)
						{
							?><input class="flat" type="checkbox" name="idpaiementfrais<?php echo $ligne;?>" value="<?php echo $idpaiementfrais;?>"/><?php
						}
						else
						{
							?><input class="flat" type="checkbox" disabled="disabled" name="idpaiementfrais<?php echo $ligne;?>" value="<?php echo $idpaiementfrais;?>"/><?php
						}
                    ?>						
				</td>
				<td>
					<a><?php echo $nomeleve;?></a>
			    </td>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo number_format($montant,"0",""," ");?></a>
			    </td>
				<td>
					<a><?php echo $tab[2]." ".$tab[1]." ".$tab[0];?></a>
			    </td>
				<td>
					<a><?php echo $libelle;?></a>
			    </td>
			    <td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $nom;?></a>
			    </td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs" onclick='window.open("ImprimeRecu.php?&idpaiementfrais=<?php echo $idpaiementfrais;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						---> Imprimer Recu.
					</a></div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrepaiement" value="<?php echo $ligne;?>"/>
	<?php
}

function ListPaiementFraisParTranche($ideleveanneescolaire,$idanneescolaire,$idclasse,$idpaiementtypeclasse,$boursier,$pdo)
{
	// Récupérer le montant total payé une seule fois
	$montantTotalPaye = getMontantEleveAnneeScolaire($idpaiementtypeclasse, $ideleveanneescolaire, $pdo);
	
	$req=(' SELECT 	DISTINCT 
					paiementtype.id AS idpaiementtype,
					paiementtype.libelle AS libellepaiementtype,
					paiementtypeclasse.id AS idpaiementtypeclasse,
					paiementtypeclasse.montant AS montantpaiementtypeclasse,
					paiementtypeclasse.remise AS remisemontantpaiementtypeclasse,
					paiementtypeclassetranche.montant AS montantpaiementtypeclassetranche,
					DATE_FORMAT(paiementtypeclassetranche.dateecheance, "%d/%m/%Y") AS datepaiementtypeclassetranche,
					paiementtranche.id AS idpaiementtranche,
					paiementtranche.libelle AS libellepaiementtranche
				
			FROM paiementtype
			JOIN paiementtypeclasse ON paiementtype.id = paiementtypeclasse.idpaiementtype
			JOIN paiementtypeclassetranche ON paiementtypeclasse.id = paiementtypeclassetranche.idpaiementtypeclasse
			JOIN paiementtranche ON paiementtypeclassetranche.idpaiementtranche = paiementtranche.id
			
			WHERE paiementtranche.statut = 1
			AND paiementtypeclassetranche.statut = 1
			AND paiementtypeclasse.statut = 1
			AND paiementtypeclasse.idanneescolaire = :idanneescolaire
			AND paiementtypeclasse.idclasse = :idclasse
			
			ORDER BY paiementtranche.id ASC');	
    ?>
	<label style="text-align:left;font-weight:bold;color:#000;font-family:comic sans ms;font-size:14px;" class="btn btn-info btn-xs">
	::: Paiement pour l'ann&eacute;e en cours :::</label><br/><br/>
	<table class="designnew" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;padding:5px;">#</th>
				<th>Tranche</th>
				<th>Montant total de la tranche</th>
				<th>Montant Pay&eacute;</th>
				<th>RAP</th>
				<th>Date Echéaence
				</tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt -> bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne = 0;
		$reste = 0;
		while($donnees = $stmt->fetch())
		{
			
			$ligne++;
			$montantTranche = $donnees['montantpaiementtypeclassetranche'];
			$idpaiementtranche = $donnees['idpaiementtranche'];
			if($idpaiementtranche==1)
			{
				$montantTranche = $montantTranche - $boursier;
			}
			//$montantTranche = $donnees['montantpaiementtypeclassetranche'];
			$dateEcheance = $donnees['datepaiementtypeclassetranche'];
			if($dateEcheance=="00/00/0000")
			{
				$dateEcheance = "";
			}
			$trancheLibelle = $donnees['libellepaiementtranche'];
			$idPaiementTypeClasse = $donnees['idpaiementtypeclasse'];

			//$montantPaye = getMontantEleveAnneeScolaire($idPaiementTypeClasse, $ideleveanneescolaire, $pdo);

			//Calcul du paiement tranche par tranche
			if ($montantTotalPaye >= $montantTranche) {
				$montantPayeTranche = $montantTranche;
				$rap = 0;
			} elseif ($montantTotalPaye > 0) {
				$montantPayeTranche = $montantTotalPaye;
				$rap = $montantTranche - $montantPayeTranche;
			} else {
				$montantPayeTranche = 0;
				$rap = $montantTranche;
			}

			// Soustraction du paiement déjà utilisé
			$montantTotalPaye -= $montantPayeTranche;
			?>
			<tr>
				<td style="width:5%;padding:5px;">
					<a>&nbsp;&nbsp;<?php echo $ligne;?></a>						
				</td>
				<td>
					<a>&nbsp;&nbsp;<?php echo $trancheLibelle;?></a>						
				</td>
				<td>
					<a>&nbsp;&nbsp;<?php echo number_format($montantTranche,"0",""," ");?></a>
				</td>
				<td>
					<a>&nbsp;&nbsp;<?php echo number_format($montantPayeTranche,"0",""," ");?></a>
				</td>
				<td>
					<a>&nbsp;&nbsp;<?php echo number_format($rap,"0",""," ");?></a>
				</td>
				<td>
					<a>&nbsp;&nbsp;<?php echo $dateEcheance;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
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
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
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

function genererNumeroRecu($libelleanneescolaire, $idanneescolaire, $pdo) {
	
    // Séparer l'année scolaire en deux parties
    list($anneeDebut, $anneeFin) = explode('-', $libelleanneescolaire);
    $shortDebut = substr(trim($anneeDebut), -2);
    $shortFin   = substr(trim($anneeFin), -2);
    // Préfixe attendu dans la base (exemple : /25-26)
    $suffixe = '/' . $shortDebut . '-' . $shortFin;
    // Récupérer le dernier numéro existant pour cette année scolaire
    $req=(' SELECT count(distinct paiementfrais.id) as nbre 
	        FROM paiementfrais 
			WHERE paiementfrais.idanneescolaire=:idanneescolaire');
	$resultat = "";		
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
	$stmt->execute();
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['nbre']+1;
	}
    // Formater le compteur sur 6 chiffres
    $numeroFormate = str_pad($resultat, 6, '0', STR_PAD_LEFT);

    // Retourner le numéro complet
    return $numeroFormate . $suffixe;
}