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
					<input class="flat" type="checkbox" checked name="idpaiementtranche<?php echo $ligne;?>" value="<?php echo $idpaiementtranche;?>"/>					
				</td>
				<td>
					<a><?php echo $libellepaiementtranche;?></a>						
				</td>
				<td>
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<input type="number" class="form-control form-control-grand" name="montant<?php echo $ligne;?>" style="border-radius:6px;"/>
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
					<input class="flat" type="checkbox" name="idpaiementtranche<?php echo $ligne;?>" value="<?php echo $idpaiementtranche;?>"/>					
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

function ScolariteParametres($pdo)
{
	$req=(' SELECT  
					distinct
					paiementtypeclasse.id as id,
					paiementtype.libelle as paiementtype,
					paiementtypeclasse.montant as montant,
					classe.codeclasse as codeclasse,
					anneescolaire.libelle as anneescolaire,
					paiementtypeclasse.statut as statut
					
			FROM    paiementtype,paiementtypeclasse,classe,anneescolaire
			WHERE
			paiementtype.id=paiementtypeclasse.idpaiementtype
			AND
			paiementtypeclasse.idclasse=classe.idclasse
			AND
			paiementtypeclasse.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			paiementtypeclasse.ideleveanneescolaire is null
			
			ORDER BY paiementtypeclasse.id desc
		');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:20%;">Ann&eacute; scolaire</th>
		        <th style="width:20%;">Frais</th>  
				<th style="width:20%;">Classe</th>
				<th style="width:23%;">Montant &agrave; payer (FCFA)</th>
				<th style="width:10%;">Statut</th>
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
			$paiementtype = $donnees['paiementtype'];
			$montant = $donnees['montant'];
			$codeclasse = $donnees['codeclasse'];
			$anneescolaire = $donnees['anneescolaire'];
			$statut = $donnees['statut'];
			if($statut==1)
			{
				$etat="Actif";
			}
			else
			{
				$etat="<font color='red'>Desactiv&eacute;</font>";
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id.'*'.$statut;?>"/>
				</td>
				<td>
				    <a><?php echo $anneescolaire;?></a>
				</td>
				<td>
				    <a><?php echo $paiementtype;?></a>
				</td>
				<td>
					<a><?php echo $codeclasse;?></a>
				</td>
				<td>
					<a><?php echo number_format($montant,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo $etat;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrefraisscolarite" value="<?php echo $ligne;?>"/><?php
}