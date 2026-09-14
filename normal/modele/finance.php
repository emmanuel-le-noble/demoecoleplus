<?php
//
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

function getAllMois($pdo)
{
	$req=(' SELECT  mois.id,
	                mois.libellemois,
					mois.anneemois
					
			FROM mois');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-7 col-xs-12" name="idmois" required="required">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libellemois = $donnees['libellemois'];
			$anneemois = $donnees['anneemois'];

			echo '<option value="'.$id.'">'.$libellemois.' '.$anneemois.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}
//
function getAllMoisSelect($idmois,$pdo)
{
	$req=(' SELECT  mois.id,
	                mois.libellemois,
					mois.anneemois
					
			FROM mois');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="form-control col-md-7 col-xs-12" name="id">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libellemois = $donnees['libellemois'];
			$anneemois = $donnees['anneemois'];

			if($id==$idmois)
			{
				echo '<option  selected="selected">'.$libellemois.' '.$anneemois.'</option>';
            }
            else	
			{
				echo '<option >'.$libellemois.' '.$anneemois.'</option>';
            }				
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getLibelleMois($idmois,$pdo)
{
	$req=(' SELECT mois.libellemois,mois.anneemois
				   
			FROM mois
			WHERE
			mois.id=:idmois');
	$mois="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idmois',$idmois,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$mois = $donnees['libellemois'].' '.$donnees['anneemois'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $mois;
}

function getAllProfesseur($pdo)
{
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			WHERE
			professeur.corps=1
			AND
			professeur.statut=1
			
			ORDER BY professeur.nom asc');
			
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

			echo '<option value="'.$id.'">'.$nom.'</option>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
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
	<select class="form-control col-md-2 col-xs-12" name="idanneescolaire" required="required">
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
	<select class="form-control col-md-2 col-xs-12" name="idanneescolaire" required="required">
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

function ListFichePaie($pdo)
{
	
	$req=(' SELECT  
					
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					mois.id as idmois,
					mois.create_id as create_id,
					mois.approuv_id as approuv_id,
					mois.debutmois as debutmois,
					mois.finmois as finmois,
					sum(moissalaire.salairenet) as salairenet
					
			FROM moissalaire,mois,anneescolaire
			WHERE
			moissalaire.idanneescolaire=anneescolaire.id
			AND
			moissalaire.idmois=mois.id
			AND
			moissalaire.corps=3

			GROUP BY anneescolaire.id,mois.id,moissalaire.corps DESC');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th> 
				<th style="width:15%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ANN&Eacute;E SCOLAIRE</th>
				<th style="width:15%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">MOIS DE PAIE</th>
				<th style="width:20%;text-align:left;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TOTAL NET</th>
				<th style="width:10%;text-align:center;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ACTION(S)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$corpsprofesseur="";
		while($donnees = $stmt->fetch())
		{
			
			$ligne++;
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idmois = $donnees['idmois'];
			$debutmois = $donnees['debutmois'];
			$tabdebutmois = explode("-",$debutmois);
			$debutmois = $tabdebutmois[2].'/'.$tabdebutmois[1].'/'.$tabdebutmois[0];
			$finmois = $donnees['finmois'];
			$tabfinmois = explode("-",$finmois);
			$finmois = $tabfinmois[2].'/'.$tabfinmois[1].'/'.$tabfinmois[0];
			$create_id = $donnees['create_id'];
			$approuv_id = $donnees['approuv_id'];
			$salairenet = $donnees['salairenet'];
			$nomusercreate = getnomprenom($create_id,$pdo);
			$nomuserapprouv = getnomprenom($approuv_id,$pdo);
			$corps = 3;
			$statut = 1;
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idmois.'*'.$idanneescolaire.'*'.$corps.'*'.$statut.'*'.$debutmois.'*'.$finmois.'*';?>"/>
				</td>
				<td align="left">
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td align="left">
					<a><?php echo $debutmois.' - '.$finmois;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
				<td align="center">
					<div align="center"><a href="#" class="btn btn-warning btn-xs" onclick='window.open("imprimeBulletin.php?&idmois=<?php echo $idmois;?>&idanneescolaire=<?php echo $idanneescolaire;?>&debutmois=<?php echo $debutmois;?>&finmois=<?php echo $finmois;?>&var=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						<i class="fa fa-pencil"></i>&nbsp;Imprimer les fiches de paie</a>
					</div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbrelignecalculpaie" value="<?php echo $ligne;?>"/><?php
}

function ListFichePaieMois_($idanneescolaire,$idmois,$pdo)
{
	$req=(' SELECT  
					professeur.id as id,
					professeur.nom as Nom,
					moissalaire.salairenet as SalaireNet
					
			FROM professeur,moissalaire
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.id=moissalaire.idpers
			AND
			moissalaire.idmois=:idmois
			
			GROUP BY moissalaire.id desc');
    ?>
	<table class="table table-striped table-bordered" style="font-size:10px;" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:47.5%;text-align:left;">Employ&eacute;</th>
				<th style="width:47.5%;text-align:left;">Salaire Net</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$Nom = $donnees['Nom'];
			$SalaireNet = $donnees['SalaireNet'];
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idmois.'*'.$idanneescolaire.'*'.$id;?>"/>
				</td>
				<td align="left">
					<a><?php echo $Nom;?></a>
				</td>
				<td align="left">
					<a><?php echo $SalaireNet;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbremois" value="<?php echo $ligne;?>"/><?php
}

function ListFichePaieMois($idanneescolaire,$debutmois,$finmois,$pdo)
{
	
	$corps = 3;
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	$req=(' SELECT  
					professeur.id as id,
					professeur.nom as nom,
					moissalaire.salairenet as salairenet,
					mois.debutmois as debutmois,
					mois.finmois as finmois,
					mois.id as idmois,
					mois.statut as statut
				
			FROM professeur,moissalaire,mois
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.id=moissalaire.idpers
			AND
			moissalaire.idmois=mois.id
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.statut=1
			AND
			moissalaire.corps=3
			
			ORDER BY professeur.nom ASC');
	
    ?>
	<table class="table table-striped table-bordered" style="font-size:10px;" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:50%;text-align:left;">Employ&eacute;</th>
				<th style="width:45%;text-align:left;">Salaire Net (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$statut = $donnees['statut'];
			$nom = $donnees['nom'];
			$idmois = $donnees['idmois'];
			$debutmois = $donnees['debutmois'];
			$tabdebutmois = explode("-",$debutmois);
			$debutmois = $tabdebutmois[2].'/'.$tabdebutmois[1].'/'.$tabdebutmois[0];
			$finmois = $donnees['finmois'];
			$tabfinmois = explode("-",$finmois);
			$finmois = $tabfinmois[2].'/'.$tabfinmois[1].'/'.$tabfinmois[0];
			$salairenet = $donnees['salairenet'];
			
			$total = $total + $salairenet;
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idmois.'*'.$idanneescolaire.'*'.$corps.'*'.$statut.'*'.$debutmois.'*'.$finmois.'*'.$id;?>"/>
				</td>
				<td align="left">
					<a><?php echo $nom;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
		<tr>
			<td style="text-align:left;" colspan="2">
				<a>Total (FCFA)</a>
			</td>
			<td align="left">
				<a><?php echo number_format($total,0,""," ");?></a>
			</td>
		</tr>
	</table>
	<input type="hidden" name="nbrelignecalculpaie" value="<?php echo $ligne;?>"/><?php
}

function CalculPaieMoisCorps($idanneescolaire,$debutmois,$finmois,$corps,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	if($corps==1)
	{
		$req=(' SELECT  
						professeur.id as id,
						professeur.nom as nom,
						moissalaire.vol_horaire as vol_horaire,
						moissalaire.cout_horaire as cout_horaire,
						mois.debutmois as debutmois,
						mois.finmois as finmois,
						mois.id as idmois,
						mois.statut as statut
						
			FROM professeur,moissalaire,mois
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.id=moissalaire.idpers
			AND
			moissalaire.idmois=mois.id
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.statut=1
			AND
			moissalaire.corps=1
			AND
			professeur.corps=1
			
			ORDER BY professeur.nom ASC');
	}
	else
	{
		$req=(' SELECT  
						professeur.id as id,
						professeur.nom as nom,
						moissalaire.salairenet as salairenet,
						mois.debutmois as debutmois,
						mois.finmois as finmois,
						mois.id as idmois,
						mois.statut as statut
					
			FROM professeur,moissalaire,mois
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.id=moissalaire.idpers
			AND
			moissalaire.idmois=mois.id
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.statut=1
			AND
			moissalaire.corps=2
			AND
			professeur.corps=2
			
			ORDER BY professeur.nom ASC');
	}
    ?>
	<table class="table table-striped table-bordered" style="font-size:10px;" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:50%;text-align:left;color:#000;">Employ&eacute;</th>
				<th style="width:45%;text-align:left;color:#000;">Salaire Net (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$statut = $donnees['statut'];
			$nom = $donnees['nom'];
			$idmois = $donnees['idmois'];
			$debutmois = $donnees['debutmois'];
			$tabdebutmois = explode("-",$debutmois);
			$debutmois = $tabdebutmois[2].'/'.$tabdebutmois[1].'/'.$tabdebutmois[0];
			$finmois = $donnees['finmois'];
			$tabfinmois = explode("-",$finmois);
			$finmois = $tabfinmois[2].'/'.$tabfinmois[1].'/'.$tabfinmois[0];
			if($corps==1)
			{
				$cout_horaire = $donnees['cout_horaire'];
				if($cout_horaire=="")
				{
					$cout_horaire=0;
				}
				$vol_horaire = $donnees['vol_horaire'];
				if($vol_horaire=="")
				{
					$vol_horaire=0;
				}
				$salairenet = $cout_horaire*$vol_horaire;
			}
			else
			{
				$salairenet = $donnees['salairenet'];
			}
			$total = $total + $salairenet;
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idmois.'*'.$idanneescolaire.'*'.$corps.'*'.$statut.'*'.$debutmois.'*'.$finmois;?>"/>
				</td>
				<td align="left">
					<a><?php echo $nom;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
		<tr>
			<td style="text-align:left;color:#000;" colspan="2">
				<a style="text-align:left;color:#000;">Total (FCFA)</a>
			</td>
			<td align="left">
				<a><?php echo number_format($total,0,""," ");?></a>
			</td>
		</tr>
	</table>
	<input type="hidden" name="nbrelignecalculpaie" value="<?php echo $ligne;?>"/><?php
}

function CalculPaieMoisCorps_($idanneescolaire,$debutmois,$finmois,$corps,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	if($corps==1)
	{
		$req=(' SELECT  
						professeur.id as id,
						professeur.nom as nom,
						moissalaire.vol_horaire as vol_horaire,
						moissalaire.cout_horaire as cout_horaire,
						mois.debutmois as debutmois,
						mois.finmois as finmois,
						mois.id as idmois,
						mois.statut as statut
						
			FROM professeur,moissalaire,mois
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.id=moissalaire.idpers
			AND
			moissalaire.idmois=mois.id
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.statut=1
			AND
			moissalaire.corps=1
			AND
			professeur.corps=1
			
			ORDER BY professeur.nom ASC');
	}
	else
	{
		$req=(' SELECT  
						professeur.id as id,
						professeur.nom as nom,
						moissalaire.salairenet as salairenet,
						mois.debutmois as debutmois,
						mois.finmois as finmois,
						mois.id as idmois,
						mois.statut as statut
					
			FROM professeur,moissalaire,mois
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.id=moissalaire.idpers
			AND
			moissalaire.idmois=mois.id
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.statut=1
			AND
			moissalaire.corps=2
			AND
			professeur.corps=2
			
			ORDER BY professeur.nom ASC');
	}
    ?>
	<table class="table table-striped table-bordered" style="font-size:10px;" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:50%;text-align:left;">Employ&eacute;</th>
				<th style="width:45%;text-align:left;">Salaire Net (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$statut = $donnees['statut'];
			$nom = $donnees['nom'];
			$idmois = $donnees['idmois'];
			$debutmois = $donnees['debutmois'];
			$tabdebutmois = explode("-",$debutmois);
			$debutmois = $tabdebutmois[2].'/'.$tabdebutmois[1].'/'.$tabdebutmois[0];
			$finmois = $donnees['finmois'];
			$tabfinmois = explode("-",$finmois);
			$finmois = $tabfinmois[2].'/'.$tabfinmois[1].'/'.$tabfinmois[0];
			if($corps==1)
			{
				$cout_horaire = $donnees['cout_horaire'];
				if($cout_horaire=="")
				{
					$cout_horaire=0;
				}
				$vol_horaire = $donnees['vol_horaire'];
				if($vol_horaire=="")
				{
					$vol_horaire=0;
				}
				$salairenet = $cout_horaire*$vol_horaire;
			}
			else
			{
				$salairenet = $donnees['salairenet'];
			}
			$total = $total + $salairenet;
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idmois.'*'.$idanneescolaire.'*'.$corps.'*'.$statut.'*'.$debutmois.'*'.$finmois;?>"/>
				</td>
				<td align="left">
					<a><?php echo $nom;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
		<tr>
			<td style="text-align:left;" colspan="2">
				<a>Total (FCFA)</a>
			</td>
			<td align="left">
				<a><?php echo number_format($total,0,""," ");?></a>
			</td>
		</tr>
	</table>
	<input type="hidden" name="nbrelignecalculpaie" value="<?php echo $ligne;?>"/><?php
}

function CreateFichePaie($debutmois,$finmois,$idpers,$salairebase,$anciennete,$sursalaire,$indemnitefonction,$primesujetion,
							$primeinterim,$indemnitelogement,$indemnitetransport,$primecaisse,$allocationfami,
							$salairebrute,$cnss,$cnss_employeur,$inam,$crt,$tcs,$irpp,$assurance,$remboursement,$mutuelle,$autresretenues,
							$totalretenues,$primeinstallation,$rappel,$salairenet,$volhoraire,$couthoraire,$idanneescolaire,$iduser,$corps,$personneacharge,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	try
	{
	
	    $pdo->beginTransaction();
		
		$requete=(' SELECT mois.id as idmois
					FROM mois
					WHERE 
					mois.debutmois=:debutmois
					AND
					mois.finmois=:finmois
					AND
					mois.corps=:corps');	
							
		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':debutmois', $debutmois, PDO::PARAM_STR);
		$stmt->bindParam(':finmois', $finmois, PDO::PARAM_STR);
		$stmt->bindParam(':corps', $corps, PDO::PARAM_STR);
		$stmt->execute();
		$idmois="";
		if ($donnees = $stmt->fetch())
		{
			$idmois = $donnees['idmois'];
		}	
		$stmt->closeCursor();

		if($idmois=="")
		{
			
			$requete="INSERT INTO mois(debutmois,finmois,statut,create_id,corps) VALUES(:debutmois,:finmois,1,:create_id,:corps)";

			$stmt = $pdo->prepare($requete);
			$stmt->bindParam(':debutmois', $debutmois, PDO::PARAM_STR);
			$stmt->bindParam(':finmois', $finmois, PDO::PARAM_STR);
			$stmt->bindParam(':create_id', $iduser, PDO::PARAM_STR);
			$stmt->bindParam(':corps', $corps, PDO::PARAM_STR);
			$stmt ->execute();		
			$stmt ->closeCursor();
			
			$requete=(' SELECT mois.id as idmois
						FROM mois
						WHERE 
						mois.debutmois=:debutmois
						AND
						mois.finmois=:finmois
						AND
						mois.corps=:corps');	
							
			$stmt = $pdo->prepare($requete);
			$stmt->bindParam(':debutmois', $debutmois, PDO::PARAM_STR);
			$stmt->bindParam(':finmois', $finmois, PDO::PARAM_STR);
			$stmt->bindParam(':corps', $corps, PDO::PARAM_STR);
			$stmt->execute();
			$idmois="";
			if ($donnees = $stmt->fetch())
			{
				$idmois = $donnees['idmois'];
			}	
			$stmt->closeCursor();
			
			$req='INSERT INTO moissalaire(idmois,idpers,salairebase,anciennete,sursalaire,indemnitefonction,primesujetion,primeinterim,indemnitelogement,
											indemnitetransport,primecaisse,allocationfami,salairebrute,cnss,cnss_employeur,inam,crt,tcs,irpp,assurance,remboursement,autresretenues,mutuelle,
											totalretenues,primeinstallation,rappel,salairenet,idanneescolaire,create_id,vol_horaire,cout_horaire,corps,personneacharge,statut) 
			
			VALUES(:idmois,:idpers,:salairebase,:anciennete,:sursalaire,:indemnitefonction,:primesujetion,:primeinterim,:indemnitelogement,
					:indemnitetransport,:primecaisse,:allocationfami,:salairebrute,:cnss,:cnss_employeur,:inam,:crt,:tcs,:irpp,:assurance,:remboursement,:autresretenues,:mutuelle,
					:totalretenues,:primeinstallation,:rappel,:salairenet,:idanneescolaire,:create_id,:vol_horaire,:cout_horaire,:corps,:personneacharge,1)';

			$stmt = $pdo->prepare($req);
			$stmt ->bindParam(':idmois', $idmois);
			$stmt ->bindParam(':idpers', $idpers);
			$stmt ->bindParam(':salairebase', $salairebase);
			$stmt ->bindParam(':anciennete', $anciennete);
			$stmt ->bindParam(':sursalaire', $sursalaire);
			$stmt ->bindParam(':indemnitefonction', $indemnitefonction);
			$stmt ->bindParam(':primesujetion', $primesujetion);
			$stmt ->bindParam(':primeinterim', $primeinterim);		
			$stmt ->bindParam(':indemnitelogement', $indemnitelogement);
			$stmt ->bindParam(':indemnitetransport', $indemnitetransport);
			$stmt ->bindParam(':primecaisse', $primecaisse);
			$stmt ->bindParam(':allocationfami', $allocationfami);
			$stmt ->bindParam(':salairebrute', $salairebrute);
			$stmt ->bindParam(':cnss', $cnss);
			$stmt ->bindParam(':cnss_employeur', $cnss_employeur);
			$stmt ->bindParam(':inam', $inam);
			$stmt ->bindParam(':crt', $crt);
			$stmt ->bindParam(':tcs', $tcs);
			$stmt ->bindParam(':irpp', $irpp);
			$stmt ->bindParam(':assurance', $assurance);
			$stmt ->bindParam(':remboursement', $remboursement);
			$stmt ->bindParam(':mutuelle', $mutuelle);
			$stmt ->bindParam(':autresretenues', $autresretenues);
			$stmt ->bindParam(':totalretenues', $totalretenues);
			$stmt ->bindParam(':primeinstallation', $primeinstallation);
			$stmt ->bindParam(':rappel', $rappel);
			$stmt ->bindParam(':salairenet', $salairenet);
			$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
			$stmt ->bindParam(':cout_horaire', $couthoraire);
			$stmt ->bindParam(':vol_horaire', $volhoraire);
			$stmt ->bindParam(':create_id', $iduser);
			$stmt ->bindParam(':corps', $corps);
			$stmt ->bindParam(':personneacharge', $personneacharge);
			$stmt ->execute();
			$stmt ->closeCursor();
		}
		else
		{
			$requete=(' SELECT moissalaire.id as idmoissalaire
						FROM moissalaire
						WHERE 
						moissalaire.idmois=:idmois
						AND
						moissalaire.idpers=:idpers');	
								
			$stmt = $pdo->prepare($requete);
			$stmt->bindParam(':idmois', $idmois, PDO::PARAM_STR);
			$stmt->bindParam(':idpers', $idpers, PDO::PARAM_STR);
			$stmt->execute();
			$idmoissalaire="";
			if ($donnees = $stmt->fetch())
			{
				$idmoissalaire = $donnees['idmoissalaire'];
			}	
			$stmt->closeCursor();
			
			if($idmoissalaire=="")
			{
				$req='INSERT INTO moissalaire(idmois,idpers,salairebase,anciennete,sursalaire,indemnitefonction,primesujetion,primeinterim,indemnitelogement,
												indemnitetransport,primecaisse,allocationfami,salairebrute,cnss,cnss_employeur,inam,crt,tcs,irpp,assurance,remboursement,autresretenues,mutuelle,
												totalretenues,primeinstallation,rappel,salairenet,idanneescolaire,create_id,vol_horaire,cout_horaire,corps,personneacharge,statut) 
				
				VALUES(:idmois,:idpers,:salairebase,:anciennete,:sursalaire,:indemnitefonction,:primesujetion,:primeinterim,:indemnitelogement,
						:indemnitetransport,:primecaisse,:allocationfami,:salairebrute,:cnss,:cnss_employeur,:inam,:crt,:tcs,:irpp,:assurance,:remboursement,:autresretenues,:mutuelle,
						:totalretenues,:primeinstallation,:rappel,:salairenet,:idanneescolaire,:create_id,:vol_horaire,:cout_horaire,:corps,:personneacharge,1)';

				$stmt = $pdo->prepare($req);
				$stmt ->bindParam(':idmois', $idmois);
				$stmt ->bindParam(':idpers', $idpers);
				$stmt ->bindParam(':salairebase', $salairebase);
				$stmt ->bindParam(':anciennete', $anciennete);
				$stmt ->bindParam(':sursalaire', $sursalaire);
				$stmt ->bindParam(':indemnitefonction', $indemnitefonction);
				$stmt ->bindParam(':primesujetion', $primesujetion);
				$stmt ->bindParam(':primeinterim', $primeinterim);		
				$stmt ->bindParam(':indemnitelogement', $indemnitelogement);
				$stmt ->bindParam(':indemnitetransport', $indemnitetransport);
				$stmt ->bindParam(':primecaisse', $primecaisse);
				$stmt ->bindParam(':allocationfami', $allocationfami);
				$stmt ->bindParam(':salairebrute', $salairebrute);
				$stmt ->bindParam(':cnss', $cnss);
				$stmt ->bindParam(':cnss_employeur', $cnss_employeur);
				$stmt ->bindParam(':inam', $inam);
				$stmt ->bindParam(':crt', $crt);
				$stmt ->bindParam(':tcs', $tcs);
				$stmt ->bindParam(':irpp', $irpp);
				$stmt ->bindParam(':assurance', $assurance);
				$stmt ->bindParam(':remboursement', $remboursement);
				$stmt ->bindParam(':mutuelle', $mutuelle);
				$stmt ->bindParam(':autresretenues', $autresretenues);
				$stmt ->bindParam(':totalretenues', $totalretenues);
				$stmt ->bindParam(':primeinstallation', $primeinstallation);
				$stmt ->bindParam(':rappel', $rappel);
				$stmt ->bindParam(':salairenet', $salairenet);
				$stmt ->bindParam(':idanneescolaire', $idanneescolaire);
				$stmt ->bindParam(':cout_horaire', $couthoraire);
				$stmt ->bindParam(':vol_horaire', $volhoraire);
				$stmt ->bindParam(':create_id', $iduser);
				$stmt ->bindParam(':corps', $corps);
				$stmt ->bindParam(':personneacharge', $personneacharge);
				$stmt ->execute();
				$stmt ->closeCursor();
			}
			
		}
	    $pdo->commit();
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
		exit();
	}
}

function DeleteMoisPaieByPers($idanneescolaire,$idmois,$idpers,$pdo)
{
	$req=' DELETE FROM moissalaire WHERE idanneescolaire=:idanneescolaire AND idmois=:idmois AND idpers=:idpers';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
	$stmt ->bindParam(':idpers',$idpers,PDO::PARAM_INT);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function DeleteMoisPaie($idanneescolaire,$idmois,$corps,$pdo)
{
	
	$req=' DELETE FROM moissalaire WHERE idanneescolaire=:idanneescolaire AND idmois=:idmois AND corps=:corps';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_STR);
	$stmt ->bindParam(':corps',$corps,PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function ListePrimeIndemniteImposable_($anciennete)
{
	//
	$baseimposable = $anciennete;
    ?>
	<table id="" class="table table-striped jambo_table bulk_action" width="100%" border="0">
	    <thead>
		    <tr>
		        <th style="width:100%;text-align:center;background-color:#cfcfcf;padding:10px;border-bottom:1px dashed #000;" colspan="3">IMPOSABLE(S)</th> 
		    </tr>
	    </thead>
		<?php	
        //
		if($anciennete!="" AND $anciennete!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Prime d'anciennete</a>
				</td>
				<td style="text-align:right;width:50%">
					<?php echo number_format(str_replace(" ","",$anciennete),0,""," ");?> &nbsp;FCFA
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		?>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%">
				<a> - </a>
			</td>
			<td style="text-align:right;width:50%">
				
			</td>
		</tr>
		<tr style="background-color:#eee;">
			<td style="text-align:left;width:50%">
				<a style="font-weight:bold;"> - Total </a>
			</td>
			<td style="text-align:right;width:50%">
				<a style="font-weight:bold;"> <?php echo number_format(str_replace(" ","",$baseimposable),0,""," ");?> &nbsp;FCFA</a>
			</td>
		</tr>
	</table><?php
}

function ListePrimeIndemniteNonImposable_($primecaisse,$primeinstallation,$allocationfami,$indemnitetransport,$primeinterim,$primesujetion,$indemnitefonction,$sursalaire,$indemnitelogement)
{
	//
	$basenonimposable = $primecaisse+$allocationfami+$primeinstallation+$indemnitetransport+$primeinterim+$primesujetion+$indemnitefonction+$sursalaire+$indemnitelogement;;
    ?>
	<table id="" class="table table-striped jambo_table bulk_action" width="100%" border="0">
	    <thead>
		    <tr>
		        <th style="width:100%;text-align:center;background-color:#cfcfcf;padding:10px;border-bottom:1px dashed #000;" colspan="3">NON IMPOSABLE(S)</th> 
		    </tr>
	    </thead>
		<?php
		//
		if($primecaisse!="" AND $primecaisse!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Prime Caisse</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$primecaisse),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}			
		//
		if($primeinstallation!="" AND $primeinstallation!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Prime d'installation</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$primeinstallation),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}			
		//
		if($allocationfami!="" AND $allocationfami!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Allocation</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$allocationfami),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		if($indemnitetransport!="" AND $indemnitetransport!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Indemnit&eacute; Transport</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$indemnitetransport),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		if($primeinterim!="" AND $primeinterim!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Prime Int&eacute;rim</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$primeinterim),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		if($primesujetion!="" AND $primesujetion!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Prime sujetion</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$primesujetion),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		if($indemnitefonction!="" AND $indemnitefonction!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Indemnit&eacute; fonction</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$indemnitefonction),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		if($sursalaire!="" AND $sursalaire!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Sursalaire</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$sursalaire),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		if($indemnitelogement!="" AND $indemnitelogement!="0")
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - Indemnit&eacute; logement</a>
				</td>
				<td style="text-align:right;width:50%">
					<a><?php echo number_format(str_replace(" ","",$indemnitelogement),0,""," ");?>&nbsp;FCFA </a>
				</td>
			</tr><?php
		}
		else
		{
			?>
			<tr>
				<td style="text-align:left;width:50%">
					<a> - </a>
				</td>
				<td style="text-align:right;width:50%">
					
				</td>
			</tr><?php
		}
		//
		?>
		<tr style="background-color:#eee;">
			<td style="text-align:left;width:50%">
				<a style="font-weight:bold;"> - Total </a>
			</td>
			<td style="text-align:right;width:50%">
				<a style="font-weight:bold;"> <?php echo number_format(str_replace(" ","",$basenonimposable),0,""," ");?> &nbsp;FCFA </a>
			</td>
		</tr>
	</table><?php
}

function ListeRetenues_($cnss,$irpp,$remboursement,$salairenet,$rappel)
{
    ?>
	<table id="" class="table table-striped jambo_table bulk_action" style="border:1px dashed #000;padding:10px;" width="100%" border="0">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:100%;text-align:center;border-bottom:1px dashed #000;background-color:#cfcfcf;" colspan="2">RETENUES</th> 
		    </tr>
	    </thead>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">CNSS</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a><?php echo number_format($cnss,0,""," ");?> &nbsp; FCFA</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">IRPP</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a>-</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">REMBOURSEMENT</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a><?php echo number_format($remboursement,0,""," ");?>&nbsp; FCFA</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>    
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:bold;">TOTAL RETENUES</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:bold;"><?php 
				$totalretenues=$remboursement+$irpp+$cnss;
				echo number_format($totalretenues,0,""," ");
				?>&nbsp; FCFA</a>
			</td>
		</tr>
	</table>
	<table class="table table-striped jambo_table bulk_action" style="border:0px dashed #000;padding:10px;" width="100%" border="0">
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;padding:2px;">
				<a style="font-weight:bold;font-size:15px;">RAPPEL</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;padding:2px;">
				<a><?php echo number_format(str_replace(" ","",$rappel),0,""," ");?>&nbsp; FCFA</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;background-color:#0066FF;padding:2px;">
				<a style="font-weight:bold;color:#fff;font-size:20px;">SALAIRE NET</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;background-color:#333300;padding:2px;">
				<a style="font-weight:bold;color:#fff;font-size:15px;"><?php echo number_format(round(str_replace(" ","",$salairenet)),0,""," ");?>&nbsp; FCFA</a>
			</td>
		</tr>
	</table><?php
}

function ListeRetenuesAnnuel($idpers,$idmois,$idanneescolaire,$banque,$compte,$modepaiement,$pdo)
{
	
	$req=(' SELECT  sum(replace( moissalaire.cnss," ","")) as cnss,
                	sum(replace( moissalaire.inam," ","")) as inam,   
                	sum(replace( moissalaire.crt," ","")) as crt,
					sum(replace( moissalaire.tcs," ","")) as tcs,
					sum(replace( moissalaire.irpp," ","")) as irpp,
					sum(replace( moissalaire.assurance," ","")) as assurance,
					sum(replace( moissalaire.mutuelle," ","")) as mutuelle,
					sum(replace( moissalaire.remboursement," ","")) as remboursement,
					sum(replace( moissalaire.autresretenues," ","")) as autresretenues,
					sum(replace( moissalaire.totalretenues," ","")) as totalretenues,
					sum(replace( moissalaire.salairebase," ","")) as salairebase,
					sum(replace( moissalaire.anciennete," ","")) as anciennete,
					sum(replace( moissalaire.sursalaire," ","")) as sursalaire,
					sum(replace( moissalaire.indemnitefonction," ","")) as indemnitefonction,
					sum(replace( moissalaire.primesujetion," ","")) as primesujetion,
					sum(replace( moissalaire.primeinterim," ","")) as primeinterim,
					sum(replace( moissalaire.indemnitelogement," ","")) as indemnitelogement,
					sum(replace( moissalaire.indemnitetransport," ","")) as indemnitetransport,
					sum(replace( moissalaire.primecaisse," ","")) as primecaisse,
					sum(replace( moissalaire.allocationfami," ","")) as allocationfami,
					sum(replace( moissalaire.salairebrute," ","")) as salairebrute,
					sum(replace( moissalaire.primeinstallation," ","")) as primeinstallation

		FROM  moissalaire
		WHERE
		moissalaire.idanneescolaire=:idanneescolaire
		AND
		moissalaire.idmois<=:idmois
		AND
		moissalaire.idpers=:idpers');
	
	$stmt = $pdo->prepare($req);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt ->bindParam(':idpers',$idpers,PDO::PARAM_INT);
	$stmt->execute();
	$sommecnss=0;
	$sommecrt=0;
	$sommetcs=0;
	$sommeirpp=0;
	$sommeassurance=0;
	$sommeremboursement=0;
	$sommeautresretenues=0;
	$sommemutuelle=0;
	$sommetotalretenues=0;
	if($donnees = $stmt->fetch())
	{
		$sommecnss = $sommecnss+$donnees['cnss'];
		$sommecrt = $sommecrt+$donnees['crt'];
		$sommetcs = $sommetcs+$donnees['tcs'];
		$sommeirpp = $sommeirpp+$donnees['irpp'];
		$sommeassurance = $sommeassurance+$donnees['assurance'];
		$sommeremboursement = $sommeremboursement+$donnees['remboursement'];
		$sommeautresretenues = $sommeautresretenues+$donnees['autresretenues'];
		$sommemutuelle = $sommemutuelle+$donnees['mutuelle'];
	}	
	$stmt->closeCursor();	
	$stmt=NULL;
	//
	$totalretenues=$sommecnss+$sommecrt+$sommetcs+$sommeirpp+$sommeassurance+$sommeremboursement+$sommeautresretenues+$sommemutuelle;
	$sommetotalretenues = $sommetotalretenues+$totalretenues;
    ?>
	<table class="table table-striped jambo_table bulk_action" style="border:1px dashed #000;padding:10px;" width="100%" border="0">
	    <thead>
		    <tr style="background-color:#cfcfcf;">
		        <th style="width:100%;text-align:center;border-bottom:1px dashed #000;" colspan="2">CUMUL<sup>1</sup></th> 
		    </tr>
	    </thead>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">CNSS</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a><?php echo number_format($sommecnss,0,""," ");?>&nbsp; FCFA</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">IRPP</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a><?php echo number_format($sommeirpp,0,""," ");?>&nbsp; FCFA</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">REMBOURSEMENT</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a><?php echo number_format($sommeremboursement,0,""," ");?>&nbsp; FCFA</a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:normal;">-</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a></a>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:bold;">TOTAL RETENUES</a>
			</td>
			<td style="text-align:right;width:50%;border-bottom:1px dashed #000;">
				<a style="font-weight:bold;">
					<?php echo number_format($sommetotalretenues,0,""," ");?>&nbsp; FCFA
				</a>
			</td>
		</tr>
	</table><?php
	if($modepaiement==2)
	{
		?>
		<table class="table table-striped jambo_table bulk_action" style="border:0px dashed #000;padding:10px;" width="100%" border="0">
			<tr>
				<td style="text-align:left;width:50%;border-bottom:1px dashed #000;padding:2px;">
					<a style="font-weight:bold;font-size:15px;">Banque</a>
				</td>
				<td style="text-align:right;width:50%;border-bottom:1px dashed #000;padding:5px;">
					<?php echo $banque;?>
				</td>
			</tr>
			<tr>
				<td style="text-align:left;width:50%;border-bottom:1px dashed #000;padding:5px;">
					<a style="font-weight:bold;font-size:20px;">Num. Compte</a>
				</td>
				<td style="text-align:right;width:50%;border-bottom:1px dashed #000;padding:5px;">
					<a style="font-weight:bold;"><?php echo $compte;?></a>
				</td>
			</tr>
		</table><?php
	}
}

function getCumulChargePatronale($idpers,$idmois,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
					replace(moissalaire.salairebase," ","") as salairebase,
					replace(moissalaire.anciennete," ","") as anciennete,
					replace(moissalaire.sursalaire," ","") as sursalaire,
					replace(moissalaire.indemnitefonction," ","") as indemnitefonction,
					replace(moissalaire.primesujetion," ","") as primesujetion,
					replace(moissalaire.primeinterim," ","") as primeinterim,
					replace(moissalaire.indemnitelogement," ","") as indemnitelogement,
					replace(moissalaire.indemnitetransport," ","") as indemnitetransport

			FROM  moissalaire
			WHERE
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			moissalaire.idmois<=:idmois
			AND
			moissalaire.idpers=:idpers');
    $stmt = $pdo->prepare($req);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt ->bindParam(':idpers',$idpers,PDO::PARAM_INT);
	$stmt->execute();
	$somme=0;
	$salairebase=0;
	$anciennete=0;
	$sursalaire=0;
	$indemnitefonction=0;
	$primesujetion=0;
	$primeinterim=0;
	$indemnitelogement=0;
	$indemnitetransport=0;
	while($donnees = $stmt->fetch())
	{
		$salairebase = $donnees['salairebase'];
		if($salairebase=="")
		{
			$salairebase=0;
		}
		$anciennete = $donnees['anciennete'];
		if($anciennete=="")
		{
			$anciennete=0;
		}
		$sursalaire = $donnees['sursalaire'];
		if($sursalaire=="")
		{
			$sursalaire=0;
		}
		$indemnitefonction = $donnees['indemnitefonction'];
		if($indemnitefonction=="")
		{
			$indemnitefonction=0;
		}
		$primesujetion = $donnees['primesujetion'];
		if($primesujetion=="")
		{
			$primesujetion=0;
		}
		$primeinterim = $donnees['primeinterim'];
		if($primeinterim=="")
		{
			$primeinterim=0;
		}
		$indemnitelogement = $donnees['indemnitelogement'];
		if($indemnitelogement=="")
		{
			$indemnitelogement=0;
		}
		$indemnitetransport = $donnees['indemnitetransport'];
		if($indemnitetransport=="")
		{
			$indemnitetransport=0;
		}
		$baseimposable = $salairebase+$anciennete+$sursalaire+$indemnitefonction+$primesujetion+$primeinterim+$indemnitetransport;
		$somme=$somme+($baseimposable*0.175);
	}	
	$stmt->closeCursor();	
	$stmt=NULL;
	
	return $somme; 
}

function getLibelleMoisSalaire($idmois,$pdo)
{
	$req=(' SELECT  mois.id,
	                mois.libellemois,
                    mois.anneemois,
                    mois.statut					
			FROM mois
			WHERE
			mois.id=:idmois');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
    $stmt ->execute();	
	$libellemois="";
	$anneemois="";
	if($donnees = $stmt->fetch())
	{
		$idmois = $donnees['id'];
		$libellemois = $donnees['libellemois'];
		$anneemois = $donnees['anneemois'];
		$statut = $donnees['statut'];
	}		
	$stmt->closeCursor();	
	$stmt=NULL;
	
	return $libellemois.' '.$anneemois; 
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
	<select class="select2_single form-control" tabindex="-1" name="idpers" style="font-size:12px;">
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

function getAllProfesseurPersonnel($pdo)
{
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur

			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idpers" style="font-size:12px;">
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
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idpers" style="font-size:12px;">
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
	<select class="select2_single form-control" tabindex="-1" name="idpers" style="font-size:12px;">
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

function ListCorpsProfessorat($idanneescolaire,$pdo)
{
	
	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom
					
			FROM    professeur,professeurdonneepaie
			
			WHERE
			professeur.id=professeurdonneepaie.idpers
			AND
			professeurdonneepaie.statut=1
			AND
			professeur.corps=1

			ORDER BY professeur.nom asc');	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;">Nom enseignant</th>
				<th style="width:19%;"> Cout honoraire (FCFA/h) </th>
				<th style="width:19%;"> Vol. heure(s) attribu&eacute; </th>
				<th style="width:19%;"> Vol. heure(s) effectu&eacute; </th>
				<th style="width:19%;"> Retenue(s) (FCFA)</th>
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
			$nom = $donnees['nom'];
			$tab = explode('*',getCoutHonoraire($idanneescolaire,$id,$pdo));
			$cout_horaire = $tab[0];
			$vol_horaire_ = $tab[1];
			if($vol_horaire_=="")
			{
				$vol_horaire_=0;
			}
			if($cout_horaire=="")
			{
				$cout_horaire=0;
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($cout_horaire,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($vol_horaire_,0,""," ");?></a>
				</td>
				<td>
					<input type="text" autocomplete="off" name="vol_horaire<?php echo $ligne;?>" class="form-control col-md-6 col-xs-12" style="border-radius:5px;border:1px solid orange;" required="required"/>
					<input type="hidden" name="cout_horaire<?php echo $ligne;?>" value="<?php echo $cout_horaire;?>"/>
				</td>
				<td>
					<input type="text" autocomplete="off" name="remboursement<?php echo $ligne;?>" class="form-control col-md-6 col-xs-12" style="border-radius:5px;border:1px solid orange;" required="required"/>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrelignecorpsprofessorat" value="<?php echo $ligne;?>"/><?php
}

function UpdatePaieCorpsProfessorat($idanneescolaire,$debutmois,$finmois,$etat,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];

	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom,
					moissalaire.id as idmoissalaire,
					moissalaire.vol_horaire as vol_horaire,
					moissalaire.cout_horaire as cout_horaire,
					moissalaire.remboursement as remboursement
					
			FROM    professeur,moissalaire,mois
			
			WHERE
			professeur.id=moissalaire.idpers
			AND
			moissalaire.statut=1
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.corps=1
			AND
			moissalaire.corps=1
			AND
			mois.id=moissalaire.idmois
			
			ORDER BY professeur.nom asc');	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;"> Nom enseignant </th>
				<th style="width:19%;"> Cout honoraire (FCFA/h) </th>
				<th style="width:19%;"> Vol. heure(s) attribu&eacute; </th>
				<th style="width:19%;"> Vol. heure(s) effectu&eacute; </th>
				<th style="width:19%;"> Retenue(s) (FCFA) </th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$tab = explode('*',getCoutHonoraire($idanneescolaire,$id,$pdo));
			$cout_horaire = $tab[0];
			$vol_horaire_attr = $tab[1];
			if($vol_horaire_attr=="")
			{
				$vol_horaire_attr=0;
			}
			$idmoissalaire = $donnees['idmoissalaire'];
			$vol_horaire = $donnees['vol_horaire'];
			if($vol_horaire=="")
			{
				$vol_horaire=0;
			}
			/*$cout_horaire = $donnees['cout_horaire'];
			if($cout_horaire=="")
			{
				$cout_horaire=0;
			}
			$vol_horaire_attr = $donnees['vol_horaire_attr'];
			if($vol_horaire_attr=="")
			{
				$vol_horaire_attr=0;
			}*/
			$remboursement = $donnees['remboursement'];
			if($remboursement=="")
			{
				$remboursement=0;
			}
			$salairenet = ($vol_horaire*$cout_horaire)-$remboursement;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($cout_horaire,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($vol_horaire_attr,0,""," ");?></a>
				</td>
				<td>
					<input type="text" autocomplete="off" name="vol_horaire<?php echo $ligne;?>" value="<?php echo $vol_horaire;?>" class="form-control col-md-6 col-xs-12" style="border-radius:5px;border:1px solid orange;" required="required"/>
					<input type="hidden" name="cout_horaire<?php echo $ligne;?>" value="<?php echo $cout_horaire;?>"/>
				</td>
				<td>
					<input type="text" autocomplete="off" name="remboursement<?php echo $ligne;?>" value="<?php echo $remboursement;?>" class="form-control col-md-6 col-xs-12" style="border-radius:5px;border:1px solid orange;" required="required"/>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrelignecorpsprofessorat" value="<?php echo $ligne;?>"/><?php
}

function ListCorpsAdministratif($pdo)
{
	
	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom,
					professeur.titre as titre,
					professeurdonneepaie.id as idprofesseurdonneepaie,
					professeurdonneepaie.salairebase as salairebase,
					professeurdonneepaie.sursalaire as sursalaire,
					professeurdonneepaie.indemnitefonction as indemnitefonction,
					professeurdonneepaie.indemnitelogement as indemnitelogement,
					professeurdonneepaie.indemnitetransport as indemnitetransport,
					professeurdonneepaie.primecaisse as primecaisse,
					professeurdonneepaie.primeinterim as primeinterim,
					professeurdonneepaie.primesujetion as primesujetion,
					professeurdonneepaie.salairebrute as salairebrute,
					professeur.personneacharge as personneacharge
					
			FROM    professeur,professeurdonneepaie
			
			WHERE
			professeur.id=professeurdonneepaie.idpers
			AND
			professeur.statut=1
			AND
			professeurdonneepaie.statut=1
			AND
			professeur.corps=2');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:15%;">Nom & pr&eacute;nom(s)</th>
				<th style="width:15%;">Salaire base <sup><small style="font-size:10px;color:red;">&nbsp;(Assiette imposable)</small></sup></th>
				<th style="width:15%;">Salaire brute</th>
				<th style="width:15%;">Cnss<sup><small style="font-size:10px;color:red;">&nbsp;(g&eacute;n&eacute;r&eacute; automatiquement)</small></sup></th>
				<th style="width:15%;">Irpp<sup><small style="font-size:10px;color:red;">&nbsp;(g&eacute;n&eacute;r&eacute; automatiquement)</small></sup></th>
				<th style="width:15%;">Remboursement</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$cnss=0;
		$cnss_employeur=0;
		$irpp=0;
		$salairebrute=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$titre = $donnees['titre'];
			$idprofesseurdonneepaie = $donnees['idprofesseurdonneepaie'];
			$nom = $donnees['nom'];
			$salairebase = $donnees['salairebase'];
			if($salairebase=="")
			{
				$salairebase=0;
			}
			$sursalaire = $donnees['sursalaire'];
			if($sursalaire=="")
			{
				$sursalaire=0;
			}
			$indemnitefonction = $donnees['indemnitefonction'];
			if($indemnitefonction=="")
			{
				$indemnitefonction=0;
			}
			$indemnitelogement = $donnees['indemnitelogement'];
			if($indemnitelogement=="")
			{
				$indemnitelogement=0;
			}
			$indemnitetransport = $donnees['indemnitetransport'];
			if($indemnitetransport=="")
			{
				$indemnitetransport=0;
			}
			$primecaisse = $donnees['primecaisse'];
			if($primecaisse=="")
			{
				$primecaisse=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$primesujetion = $donnees['primesujetion'];
			if($primesujetion=="")
			{
				$primesujetion=0;
			}
			$salairebrute = $donnees['salairebrute'];
			if($salairebrute=="")
			{
				$salairebrute=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$personneacharge = $donnees['personneacharge'];
			if($personneacharge=="")
			{
				$personneacharge=0;
			}
			//PAIE DE CSBORDJO
			$salairebrute = $salairebase+$sursalaire+$indemnitefonction+$indemnitelogement+$indemnitetransport+$primecaisse+$primeinterim+$primesujetion;
			if($titre!=6)
			{
				$cnss = $salairebase*0.04;
				$cnss_employeur = $salairebase*0.175;
				$salaireimposable = $salairebase-$cnss;
				$abattement = 0;
				if($salaireimposable<=(10000000/12))
				{
					$abattement = $salaireimposable*0.72;
				}
				else
				{
					$abattement = (10000000*0.72/12)+($salaireimposable-(10000000/12));
				}
				
				if($personneacharge<=6)
				{
					$personneacharge = $personneacharge*10000;
				}
				else
				{
					$personneacharge = 6*10000;
				}
				$basearrondi = abs (floor(($abattement-$personneacharge)/1000)*1000);
				$irpp = getIrpp($basearrondi,$pdo);
				$salairenet = $salairebrute - ($irpp+$cnss);
			}
			else
			{
				$cnss=0;
				$cnss_employeur=0;
				$irpp=0;
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
					<input type="hidden" id="nbrepersonneacharge<?php echo $ligne;?>" name="nbrepersonneacharge<?php echo $ligne;?>" value="<?php echo $personneacharge;?>"/>
					<input type="hidden" id="salairebase<?php echo $ligne;?>" name="salairebase<?php echo $ligne;?>" value="<?php echo $salairebase;?>"/>
					<input type="hidden" id="sursalaire<?php echo $ligne;?>" name="sursalaire<?php echo $ligne;?>" value="<?php echo $sursalaire;?>"/>
					<input type="hidden" id="indemnitefonction<?php echo $ligne;?>" name="indemnitefonction<?php echo $ligne;?>" value="<?php echo $indemnitefonction;?>"/>
					<input type="hidden" id="primesujetion<?php echo $ligne;?>" name="primesujetion<?php echo $ligne;?>" value="<?php echo $primesujetion;?>"/>
					<input type="hidden" id="primeinterim<?php echo $ligne;?>" name="primeinterim<?php echo $ligne;?>" value="<?php echo $primeinterim;?>"/>
					<input type="hidden" id="indemnitelogement<?php echo $ligne;?>" name="indemnitelogement<?php echo $ligne;?>" value="<?php echo $indemnitelogement;?>"/>
					<input type="hidden" id="indemnitetransport<?php echo $ligne;?>" name="indemnitetransport<?php echo $ligne;?>" value="<?php echo $indemnitetransport;?>"/>
					<input type="hidden" id="primecaisse<?php echo $ligne;?>" name="primecaisse<?php echo $ligne;?>" value="<?php echo $primecaisse;?>"/>
					<input type="hidden" id="allocationfami<?php echo $ligne;?>" name="allocationfami<?php echo $ligne;?>" value="<?php echo $allocationfami;?>"/>
					<input type="hidden" id="cnss<?php echo $ligne;?>" name="cnss<?php echo $ligne;?>" value="<?php echo $cnss;?>"/>
					<input type="hidden" id="irpp<?php echo $ligne;?>" name="irpp<?php echo $ligne;?>" value="<?php echo $irpp;?>"/>
					<input type="hidden" id="cnss_employeur<?php echo $ligne;?>" name="cnss_employeur<?php echo $ligne;?>" value="<?php echo $cnss_employeur;?>"/>
					<input type="hidden" id="salairebrute<?php echo $ligne;?>" name="salairebrute<?php echo $ligne;?>" value="<?php echo $salairebrute;?>"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebase,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebrute,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($cnss,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($irpp,0,""," ");?></a>
				</td>
				<td>
					<input type="text" autocomplete="off" id="remboursement<?php echo $ligne;?>" name="remboursement<?php echo $ligne;?>" class="form-control col-md-6 col-xs-12" style="border-radius:5px;border:1px solid orange;"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrelignecorpsadministratif" value="<?php echo $ligne;?>"/><?php
}

function UpdatePaieCorpsAdministratif($idanneescolaire,$debutmois,$finmois,$etat,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom,
					professeur.titre as titre,
					moissalaire.id as idmoissalaire,
					moissalaire.salairebase as salairebase,
					moissalaire.sursalaire as sursalaire,
					moissalaire.indemnitefonction as indemnitefonction,
					moissalaire.indemnitelogement as indemnitelogement,
					moissalaire.indemnitetransport as indemnitetransport,
					moissalaire.primecaisse as primecaisse,
					moissalaire.primeinterim as primeinterim,
					moissalaire.primesujetion as primesujetion,
					moissalaire.salairebrute as salairebrute,
					moissalaire.personneacharge as personneacharge,
					moissalaire.cnss as cnss,
					moissalaire.irpp as irpp,
					moissalaire.remboursement as remboursement,
					moissalaire.salairenet as salairenet
					
			FROM    professeur,moissalaire,mois
			
			WHERE
			professeur.id=moissalaire.idpers
			AND
			professeur.statut=1
			AND
			moissalaire.statut=1
			AND
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			mois.id=moissalaire.idmois
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			professeur.corps=2

			ORDER BY professeur.nom ASC');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:15%;">Nom & pr&eacute;nom(s)</th>
				<th style="width:15%;">Salaire base <sup><small style="font-size:10px;color:red;">&nbsp;(Assiette imposable)</small></sup></th>
				<th style="width:15%;">Salaire brute</th>
				<th style="width:15%;">Cnss<sup><small style="font-size:10px;color:red;">&nbsp;(g&eacute;n&eacute;r&eacute; automatiquement)</small></sup></th>
				<th style="width:15%;">Irpp<sup><small style="font-size:10px;color:red;">&nbsp;(g&eacute;n&eacute;r&eacute; automatiquement)</small></sup></th>
				<th style="width:15%;">Remboursement</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$titre = $donnees['titre'];
			$nom = $donnees['nom'];
			$salairebase = $donnees['salairebase'];
			if($salairebase=="")
			{
				$salairebase=0;
			}
			$sursalaire = $donnees['sursalaire'];
			if($sursalaire=="")
			{
				$sursalaire=0;
			}
			$indemnitefonction = $donnees['indemnitefonction'];
			if($indemnitefonction=="")
			{
				$indemnitefonction=0;
			}
			$indemnitelogement = $donnees['indemnitelogement'];
			if($indemnitelogement=="")
			{
				$indemnitelogement=0;
			}
			$indemnitetransport = $donnees['indemnitetransport'];
			if($indemnitetransport=="")
			{
				$indemnitetransport=0;
			}
			$primecaisse = $donnees['primecaisse'];
			if($primecaisse=="")
			{
				$primecaisse=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$primesujetion = $donnees['primesujetion'];
			if($primesujetion=="")
			{
				$primesujetion=0;
			}
			$salairebrute = $donnees['salairebrute'];
			if($salairebrute=="")
			{
				$salairebrute=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$personneacharge = $donnees['personneacharge'];
			if($personneacharge=="")
			{
				$personneacharge=0;
			}
			$remboursement = $donnees['remboursement'];
			if($remboursement=="")
			{
				$remboursement=0;
			}
			//PAIE DE CSBORDJO
			$salairebrute = $salairebase+$sursalaire+$indemnitefonction+$indemnitelogement+$indemnitetransport+$primecaisse+$primeinterim+$primesujetion;
			if($titre!=6)
			{
				$cnss = $salairebase*0.04;
				$cnss_employeur = $salairebase*0.175;
				$salaireimposable = $salairebase-$cnss;
				$abattement = 0;
				if($salaireimposable<=(10000000/12))
				{
					$abattement = $salaireimposable*0.72;
				}
				else
				{
					$abattement = (10000000*0.72/12)+($salaireimposable-(10000000/12));
				}
				
				if($personneacharge<=6)
				{
					$personneacharge = $personneacharge*10000;
				}
				else
				{
					$personneacharge = 6*10000;
				}
				$basearrondi = abs (floor(($abattement-$personneacharge)/1000)*1000);

				$irpp = getIrpp($basearrondi,$pdo);
				$salairenet = $salairebrute - ($irpp+$cnss);
			}
			else
			{
				$cnss=0;
				$cnss_employeur=0;
				$irpp=0;
			}
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
					<input type="hidden" id="nbrepersonneacharge<?php echo $ligne;?>" name="nbrepersonneacharge<?php echo $ligne;?>" value="<?php echo $personneacharge;?>"/>
					<input type="hidden" id="salairebase<?php echo $ligne;?>" name="salairebase<?php echo $ligne;?>" value="<?php echo $salairebase;?>"/>
					<input type="hidden" id="sursalaire<?php echo $ligne;?>" name="sursalaire<?php echo $ligne;?>" value="<?php echo $sursalaire;?>"/>
					<input type="hidden" id="indemnitefonction<?php echo $ligne;?>" name="indemnitefonction<?php echo $ligne;?>" value="<?php echo $indemnitefonction;?>"/>
					<input type="hidden" id="primesujetion<?php echo $ligne;?>" name="primesujetion<?php echo $ligne;?>" value="<?php echo $primesujetion;?>"/>
					<input type="hidden" id="primeinterim<?php echo $ligne;?>" name="primeinterim<?php echo $ligne;?>" value="<?php echo $primeinterim;?>"/>
					<input type="hidden" id="indemnitelogement<?php echo $ligne;?>" name="indemnitelogement<?php echo $ligne;?>" value="<?php echo $indemnitelogement;?>"/>
					<input type="hidden" id="indemnitetransport<?php echo $ligne;?>" name="indemnitetransport<?php echo $ligne;?>" value="<?php echo $indemnitetransport;?>"/>
					<input type="hidden" id="primecaisse<?php echo $ligne;?>" name="primecaisse<?php echo $ligne;?>" value="<?php echo $primecaisse;?>"/>
					<input type="hidden" id="allocationfami<?php echo $ligne;?>" name="allocationfami<?php echo $ligne;?>" value="<?php echo $allocationfami;?>"/>
					<input type="hidden" id="cnss<?php echo $ligne;?>" name="cnss<?php echo $ligne;?>" value="<?php echo $cnss;?>"/>
					<input type="hidden" id="cnss_employeur<?php echo $ligne;?>" name="cnss_employeur<?php echo $ligne;?>" value="<?php echo $cnss_employeur;?>"/>
					<input type="hidden" id="irpp<?php echo $ligne;?>" name="irpp<?php echo $ligne;?>" value="<?php echo $irpp;?>"/>
					<input type="hidden" id="salairebrute<?php echo $ligne;?>" name="salairebrute<?php echo $ligne;?>" value="<?php echo $salairebrute;?>"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebase,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebrute,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($cnss,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($irpp,0,""," ");?></a>
				</td>
				<td>
				<?php
					if($etat=="")
					{
						?>
						<input type="text" autocomplete="off" name="remboursement<?php echo $ligne;?>" value="<?php echo $remboursement;?>" class="form-control col-md-6 col-xs-12" style="border-radius:5px;border:1px solid orange;"/><?php
					}
					else
					{
						?>
						<a><?php echo number_format($remboursement,0,""," ");?></a><?php
					}
				?>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrelignecorpsadministratif" value="<?php echo $ligne;?>"/><?php
}

function ApprouverPaieCorpsAdministratif($idanneescolaire,$debutmois,$finmois,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom,
					moissalaire.id as idmoissalaire,
					moissalaire.salairebase as salairebase,
					moissalaire.sursalaire as sursalaire,
					moissalaire.indemnitefonction as indemnitefonction,
					moissalaire.indemnitelogement as indemnitelogement,
					moissalaire.indemnitetransport as indemnitetransport,
					moissalaire.primecaisse as primecaisse,
					moissalaire.primeinterim as primeinterim,
					moissalaire.primesujetion as primesujetion,
					moissalaire.salairebrute as salairebrute,
					moissalaire.personneacharge as personneacharge,
					moissalaire.cnss as cnss,
					moissalaire.cnss_employeur as cnss_employeur,
					moissalaire.irpp as irpp,
					moissalaire.remboursement as remboursement,
					moissalaire.salairenet as salairenet
					
			FROM    professeur,moissalaire,mois
			
			WHERE
			professeur.id=moissalaire.idpers
			AND
			professeur.statut=1
			AND
			moissalaire.statut=1
			AND
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			mois.id=moissalaire.idmois
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			professeur.corps=2
			
			ORDER BY professeur.nom ASC');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:20%;">Nom & pr&eacute;nom(s)</th>
				<th style="width:12%;">Salaire base</th>
				<th style="width:12%;">Salaire brute</th>
				<th style="width:12%;">Cnss</th>
				<th style="width:14%;">Irpp</th>
				<th style="width:14%;">Remboursement</th>
				<th style="width:14%;">Total Net(FCFA)</th>
				<th style="width:5%;text-align:center;">D&eacute;tail(s)</th> 
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		$totalnet=0;
		$totalirpp=0;
		$totalcnss=0;
		$totalcnss_employeur=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$idmoissalaire = $donnees['idmoissalaire'];
			$nom = $donnees['nom'];
			$salairebase = $donnees['salairebase'];
			if($salairebase=="")
			{
				$salairebase=0;
			}
			$sursalaire = $donnees['sursalaire'];
			if($sursalaire=="")
			{
				$sursalaire=0;
			}
			$indemnitefonction = $donnees['indemnitefonction'];
			if($indemnitefonction=="")
			{
				$indemnitefonction=0;
			}
			$indemnitelogement = $donnees['indemnitelogement'];
			if($indemnitelogement=="")
			{
				$indemnitelogement=0;
			}
			$indemnitetransport = $donnees['indemnitetransport'];
			if($indemnitetransport=="")
			{
				$indemnitetransport=0;
			}
			$primecaisse = $donnees['primecaisse'];
			if($primecaisse=="")
			{
				$primecaisse=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$primesujetion = $donnees['primesujetion'];
			if($primesujetion=="")
			{
				$primesujetion=0;
			}
			$salairebrute = $donnees['salairebrute'];
			if($salairebrute=="")
			{
				$salairebrute=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$personneacharge = $donnees['personneacharge'];
			if($personneacharge=="")
			{
				$personneacharge=0;
			}
			$cnss_employeur = $donnees['cnss_employeur'];
			if($cnss_employeur=="")
			{
				$cnss_employeur=0;
			}
			$cnss = $donnees['cnss'];
			if($cnss=="")
			{
				$cnss=0;
			}
			$irpp = $donnees['irpp'];
			if($irpp=="")
			{
				$irpp=0;
			}
			$salairenet = $donnees['salairenet'];
			if($salairenet=="")
			{
				$salairenet=0;
			}
			$remboursement = $donnees['remboursement'];
			if($remboursement=="")
			{
				$remboursement=0;
			}
			$totalnet=$totalnet+$salairenet;
			$totalirpp=$totalirpp+$irpp;
			$totalcnss=$totalcnss+$cnss;
			$totalcnss_employeur=$totalcnss_employeur+$cnss_employeur;
			$total=$total+$totalnet;
			?>
			<tr>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebase,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($sursalaire,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($indemnitefonction,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($primesujetion,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($primeinterim,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($indemnitelogement,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($indemnitetransport,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($primecaisse,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($allocationfami,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebrute,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($cnss,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($irpp,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($remboursement,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
				<td>
					<a href="javascript:;" class="edit"> 
						<i class="fa fa-pencil"></i> <span style="font-weight:bold;color:red;">Voir</span>
					</a>
					<input type="hidden" id="nbrepersonneacharge<?php echo $ligne;?>" name="nbrepersonneacharge<?php echo $ligne;?>" value="<?php echo $personneacharge;?>"/>
					<input type="hidden" id="salairebase<?php echo $ligne;?>" name="salairebase<?php echo $ligne;?>" value="<?php echo $salairebase;?>"/>
					<input type="hidden" id="sursalaire<?php echo $ligne;?>" name="sursalaire<?php echo $ligne;?>" value="<?php echo $sursalaire;?>"/>
					<input type="hidden" id="indemnitefonction<?php echo $ligne;?>" name="indemnitefonction<?php echo $ligne;?>" value="<?php echo $indemnitefonction;?>"/>
					<input type="hidden" id="primesujetion<?php echo $ligne;?>" name="primesujetion<?php echo $ligne;?>" value="<?php echo $primesujetion;?>"/>
					<input type="hidden" id="primeinterim<?php echo $ligne;?>" name="primeinterim<?php echo $ligne;?>" value="<?php echo $primeinterim;?>"/>
					<input type="hidden" id="indemnitelogement<?php echo $ligne;?>" name="indemnitelogement<?php echo $ligne;?>" value="<?php echo $indemnitelogement;?>"/>
					<input type="hidden" id="indemnitetransport<?php echo $ligne;?>" name="indemnitetransport<?php echo $ligne;?>" value="<?php echo $indemnitetransport;?>"/>
					<input type="hidden" id="primecaisse<?php echo $ligne;?>" name="primecaisse<?php echo $ligne;?>" value="<?php echo $primecaisse;?>"/>
					<input type="hidden" id="allocationfami<?php echo $ligne;?>" name="allocationfami<?php echo $ligne;?>" value="<?php echo $allocationfami;?>"/>
					<input type="hidden" id="cnss<?php echo $ligne;?>" name="cnss<?php echo $ligne;?>" value="<?php echo $cnss;?>"/>
					<input type="hidden" id="irpp<?php echo $ligne;?>" name="irpp<?php echo $ligne;?>" value="<?php echo $irpp;?>"/>
					<input type="hidden" id="salairebrute<?php echo $ligne;?>" name="salairebrute<?php echo $ligne;?>" value="<?php echo $salairebrute;?>"/>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
    	<tr>
			<td colspan="2">
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow:0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					::: Total (FCFA) :::
				</span>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow:0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					<?php echo number_format($total,0,""," ");?>
					<input type="hidden" name="montanttotal" value="<?php echo $totalnet+$totalirpp+$totalcnss+$totalcnss_employeur;?>"/>
				</span>
			</td>
			<td></td>
		</tr>
	</table><?php
}

function ImprimerPaieCorpsAdministratif($idanneescolaire,$debutmois,$finmois,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom,
					moissalaire.id as idmoissalaire,
					moissalaire.salairebase as salairebase,
					moissalaire.sursalaire as sursalaire,
					moissalaire.indemnitefonction as indemnitefonction,
					moissalaire.indemnitelogement as indemnitelogement,
					moissalaire.indemnitetransport as indemnitetransport,
					moissalaire.primecaisse as primecaisse,
					moissalaire.primeinterim as primeinterim,
					moissalaire.primesujetion as primesujetion,
					moissalaire.salairebrute as salairebrute,
					moissalaire.personneacharge as personneacharge,
					moissalaire.cnss as cnss,
					moissalaire.irpp as irpp,
					moissalaire.remboursement as remboursement,
					moissalaire.salairenet as salairenet
					
			FROM    professeur,moissalaire,mois
			
			WHERE
			professeur.id=moissalaire.idpers
			AND
			professeur.statut=1
			AND
			moissalaire.statut=1
			AND
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			mois.id=moissalaire.idmois
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			professeur.corps=2
			
			ORDER BY professeur.nom ASC');
			
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:22%;">Nom & pr&eacute;nom(s)</th>
				<th style="width:12%;">Salaire base</th>
				<th style="width:12%;">Salaire brut</th>
				<th style="width:12%;">Cnss</th>
				<th style="width:14%;">Irpp</th>
				<th style="width:14%;">Rembour.</th>
				<th style="width:17%;">Total Net</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$idmoissalaire = $donnees['idmoissalaire'];
			$nom = $donnees['nom'];
			$salairebase = $donnees['salairebase'];
			if($salairebase=="")
			{
				$salairebase=0;
			}
			$sursalaire = $donnees['sursalaire'];
			if($sursalaire=="")
			{
				$sursalaire=0;
			}
			$indemnitefonction = $donnees['indemnitefonction'];
			if($indemnitefonction=="")
			{
				$indemnitefonction=0;
			}
			$indemnitelogement = $donnees['indemnitelogement'];
			if($indemnitelogement=="")
			{
				$indemnitelogement=0;
			}
			$indemnitetransport = $donnees['indemnitetransport'];
			if($indemnitetransport=="")
			{
				$indemnitetransport=0;
			}
			$primecaisse = $donnees['primecaisse'];
			if($primecaisse=="")
			{
				$primecaisse=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$primesujetion = $donnees['primesujetion'];
			if($primesujetion=="")
			{
				$primesujetion=0;
			}
			$salairebrute = $donnees['salairebrute'];
			if($salairebrute=="")
			{
				$salairebrute=0;
			}
			$primeinterim = $donnees['primeinterim'];
			if($primeinterim=="")
			{
				$primeinterim=0;
			}
			$personneacharge = $donnees['personneacharge'];
			if($personneacharge=="")
			{
				$personneacharge=0;
			}
			$cnss = $donnees['cnss'];
			if($cnss=="")
			{
				$cnss=0;
			}
			$irpp = $donnees['irpp'];
			if($irpp=="")
			{
				$irpp=0;
			}
			$salairenet = $donnees['salairenet'];
			if($salairenet=="")
			{
				$salairenet=0;
			}
			$remboursement = $donnees['remboursement'];
			if($remboursement=="")
			{
				$remboursement=0;
			}
			$total=$total+$salairenet;
			?>
			<tr>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebase,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($sursalaire,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($indemnitefonction,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($primesujetion,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($primeinterim,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($indemnitelogement,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($indemnitetransport,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($primecaisse,0,""," ");?></a>
				</td>
				<td style="display:none;">
					<a><?php echo number_format($allocationfami,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($salairebrute,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($cnss,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($irpp,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($remboursement,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
    	<tr>
			<td colspan="2">
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow:0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					::: Total (FCFA) :::
				</span>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow:0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					<?php echo number_format($total,0,""," ");?>
				</span>
			</td>
		</tr>
	</table><?php
}

function getCoutHonoraire($idanneescolaire,$idprof,$pdo)
{

	$req=(' SELECT 
	                professeurdonneepaie.vol_horaire as vol_horaire,
					professeurdonneepaie.cout_honoraire as cout_honoraire
	
			FROM professeurdonneepaie
			WHERE 
			professeurdonneepaie.idpers=:idpers
			AND
			professeurdonneepaie.idanneescolaire=:idanneescolaire
			
			ORDER BY professeurdonneepaie.id DESC LIMIT 0,1');
			
	$resultat = '*';		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idpers', $idprof, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['cout_honoraire'].'*'.$donnees['vol_horaire'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getIrpp($montant,$pdo)
{
	
	$req=(' SELECT 
					baremeirpp.valeur_1 as valeur_1,
					baremeirpp.valeur_2 as valeur_2,
					baremeirpp.taux as taux
					
			FROM baremeirpp
			WHERE 
			baremeirpp.statut=1
			
			ORDER BY baremeirpp.id ASC');
					
	$stmt = $pdo->prepare($req);
	$stmt->execute();
	$irpp = 0;
	$somme = 0;
	while($donnees = $stmt->fetch())
	{
	    $valeur_1 = $donnees['valeur_1']/12;
		$valeur_2 = $donnees['valeur_2']/12;
		$taux = $donnees['taux']/100;
		
		if($montant>=$valeur_1 AND $montant<=$valeur_2)
		{
			$irpp = (($montant-$valeur_1)*$taux)+($somme);
			break;
		}
		else
		{
			$somme = $somme + ($valeur_2*$taux);
		}		
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $irpp;
}

function CalculPaie($pdo)
{
	$req=(' SELECT  
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					mois.id as idmois,
					mois.create_id as create_id,
					mois.approuv_id as approuv_id,
					mois.debutmois as debutmois,
					mois.finmois as finmois,
					mois.statut as statut,
					moissalaire.corps as corps,
					sum(moissalaire.vol_horaire*moissalaire.cout_horaire) as total,
					sum(moissalaire.salairenet) as salairenet,
					sum(moissalaire.cnss) as cnss,
					sum(moissalaire.cnss_employeur) as cnss_employeur,
					sum(moissalaire.irpp) as irpp
					
			FROM moissalaire,mois,anneescolaire
			WHERE
			moissalaire.idanneescolaire=anneescolaire.id
			AND
			moissalaire.idmois=mois.id
			AND
			moissalaire.corps<>3

			GROUP BY anneescolaire.id,mois.id,moissalaire.corps DESC 
			
			ORDER BY mois.id DESC');
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-family:Microsoft sans serif;">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px">
		        <th style="width:5%;font-weight:bold;color:#000">#</th> 
				<th style="width:8%;font-weight:bold;color:#000">Statut</th>
				<th style="width:10%;font-weight:bold;color:#000">Ann&eacute;e Scol.</th>
				<th style="width:10%;font-weight:bold;color:#000">Mois salaire</th>
				<th style="width:10%;font-weight:bold;color:#000">Corps</th>
				<th style="width:10%;font-weight:bold;color:#000">Total net</th>
				<th style="width:10%;font-weight:bold;color:#000">Total IRPP</th>
				<th style="width:10%;font-weight:bold;color:#000">Cnss employ&eacute;</th>
				<th style="width:12%;font-weight:bold;color:#000">Cnss employeur</th>
				<th style="width:10%;font-weight:bold;color:#000">M. salariale</th>
				<th style="width:8%;font-weight:bold;color:#000">Action(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$corpsprofesseur="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idmois = $donnees['idmois'];
			$debutmois = $donnees['debutmois'];
			$tabdebutmois = explode("-",$debutmois);
			$debutmois = $tabdebutmois[2].'/'.$tabdebutmois[1].'/'.$tabdebutmois[0];
			$finmois = $donnees['finmois'];
			$tabfinmois = explode("-",$finmois);
			$finmois = $tabfinmois[2].'/'.$tabfinmois[1].'/'.$tabfinmois[0];
			$corps = $donnees['corps'];
			$statut = $donnees['statut'];
			$create_id = $donnees['create_id'];
			$approuv_id = $donnees['approuv_id'];
			$nomusercreate = getnomprenom($create_id,$pdo);
			$nomuserapprouv = getnomprenom($approuv_id,$pdo);
			if($corps==1)
			{
				$corpsprofesseur="Temporaire";
				$salairenet = $donnees['total'];
			}
			else
			{
				$corpsprofesseur="Permanent";
				$salairenet = $donnees['salairenet'];
			}
			$cnss = $donnees['cnss'];
			if($cnss=="")
			{
				$cnss=0;
			}
			$irpp = $donnees['irpp'];
			if($irpp=="")
			{
				$irpp=0;
			}
			$cnss_employeur = $donnees['cnss_employeur'];
			if($cnss_employeur=="")
			{
				$cnss_employeur=0;
			}
			$masse_salariale = $salairenet+$cnss+$irpp+$cnss_employeur;
			$tab = explode("/",$finmois);
			if($tab[1]+0==1)
			{
				$libelle="Janvier ".$tab[2];
			}
			elseif($tab[1]+0==2)
			{
				$libelle="Fevrier ".$tab[2];
			}
			elseif($tab[1]+0==3)
			{
				$libelle="Mars ".$tab[2];
			}
			elseif($tab[1]+0==4)
			{
				$libelle="Avril ".$tab[2];
			}
			elseif($tab[1]+0==5)
			{
				$libelle="Mai ".$tab[2];
			}
			elseif($tab[1]+0==6)
			{
				$libelle="Juin ".$tab[2];
			}
			elseif($tab[1]+0==7)
			{
				$libelle="Juillet ".$tab[2];
			}elseif($tab[1]+0==8)
			{
				$libelle="Août ".$tab[2];
			}elseif($tab[1]+0==9)
			{
				$libelle="Septembre ".$tab[2];
			}elseif($tab[1]+0==10)
			{
				$libelle="Octobre ".$tab[2];
			}elseif($tab[1]+0==11)
			{
				$libelle="Novembre ".$tab[2];
			}
			elseif($tab[1]+0==12)
			{
				$libelle="Decembre ".$tab[2];
			}
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idmois.'*'.$idanneescolaire.'*'.$corps.'*'.$statut.'*'.$debutmois.'*'.$finmois;?>"/>
				</td>
				<td align="left">
					<?php 
						if($statut==1)
						{
							?>
							<div align="center">
								<a href="#" class="btn btn-default btn-xs">
									<i class="fa fa-check"></i>&nbsp;A approuver
								</a>
							</div><?php
						}
						elseif($statut==2)
						{
							?>
							<div align="center">
								<a href="#" class="btn btn-info btn-xs">
									<i class="fa fa-check"></i>&nbsp;Paie approuv&eacute;e
								</a>
							</div><?php
						}
					?>
					</a>
				</td>
				<td align="left">
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td align="left">
					<a><?php echo $libelle;?></a>
				</td>
				<td align="left">
					<a><?php echo $corpsprofesseur;?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($salairenet,0,""," ");?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($irpp,0,""," ");?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($cnss,0,""," ");?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($cnss_employeur,0,""," ");?></a>
				</td>
				<td align="left">
					<a><?php echo number_format($masse_salariale,0,""," ");?></a>
				</td>
				<td align="center">
				    <?php
					if($statut==2 AND $corps==2)
					{
						?>
						<div align="center"><a href="#" class="btn btn-warning btn-xs" onclick='window.open("ImprimeBulletin.php?&idmois=<?php echo $idmois;?>&idanneescolaire=<?php echo $idanneescolaire;?>&debutmois=<?php echo $debutmois;?>&finmois=<?php echo $finmois;?>&var=0","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
							<i class="fa fa-pencil"></i>&nbsp;Fiche(s) de paie</a>
						</div><?php
					}
					?>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table>
	<input type="hidden" name="nbrelignecalculpaie" value="<?php echo $ligne;?>"/><?php
}

function ChangeMoisPaie($idmois,$corps,$dateapprobation,$observation,$iduser,$pdo)
{
	$req=' UPDATE mois SET statut=2,approuv_id=:approuv_id,dateapprouv=:dateapprobation,observation=:observation WHERE id=:idmois AND corps=:corps';
			
    $stmt = $pdo->prepare($req);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
	$stmt ->bindParam(':corps',$corps,PDO::PARAM_INT);
	$stmt ->bindParam(':approuv_id',$iduser,PDO::PARAM_INT);
	$stmt ->bindParam(':observation',$observation,PDO::PARAM_STR);
	$stmt ->bindParam(':dateapprobation',$dateapprobation,PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function ApprouverMoisPaie($idmois,$corps,$dateapprobation,$observation,$mois,$montanttotal,$corpslibelle,$idcompte,$idanneescolaire,$iduser,$pdo)
{
	$req=' UPDATE mois SET idcompte=:idcompte,statut=2,approuv_id=:approuv_id,dateapprouv=:dateapprobation,observation=:observation WHERE id=:idmois AND corps=:corps';
			
    $stmt = $pdo->prepare($req);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
	$stmt ->bindParam(':corps',$corps,PDO::PARAM_INT);
	$stmt ->bindParam(':approuv_id',$iduser,PDO::PARAM_INT);
	$stmt ->bindParam(':idcompte',$idcompte,PDO::PARAM_INT);
	$stmt ->bindParam(':observation',$observation,PDO::PARAM_STR);
	$stmt ->bindParam(':dateapprobation',$dateapprobation,PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
	
	$libelleoperation="Paiement salaire -> Mois : ".$mois." Corps : ".$corpslibelle;
	AjouterEntreeSortie($idmois,$idanneescolaire,2,$libelleoperation,$montanttotal,$dateapprobation,$idcompte,$iduser,$iduser,"",$pdo);
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

function AjouterEntreeSortie($idsalaire,$idanneescolaire,$typeoperation,$libelleoperation,$montant,$dateoperation,$idcompte,$iduser,$iduser2,$fichier,$pdo)
{

	$etatcompteacuel=(getentreecompte($idanneescolaire,$idcompte,$pdo)-getsortiecompte($idanneescolaire,$idcompte,$pdo))-$montant;
	
	$requete="INSERT INTO entreesortie(idsalaire,idanneescolaire,montant,libelle,statut,iduserajout,iduserauto,dateoperation,comptemouvement,idtypeentreesortie,montantcompte,ficheattache,datesaisie) 
				VALUES(:idsalaire,:idanneescolaire,:montant,:libelle,1,:iduserajout,:iduserauto,:dateoperation,:comptemouvement,:idtypeentreesortie,:montantcompte,:ficheattache,sysdate())";
	
	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':idsalaire', $idsalaire, PDO::PARAM_INT);
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

function SupprimerMoisPaie($idanneescolaire,$idmois,$corps,$pdo)
{
	$req=' DELETE FROM moissalaire WHERE idanneescolaire=:idanneescolaire AND idmois=:idmois AND corps=:corps';
			
    $stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
    $stmt ->bindParam(':idmois',$idmois,PDO::PARAM_STR);
	$stmt ->bindParam(':corps',$corps,PDO::PARAM_STR);
    $stmt->execute();			
	$stmt->closeCursor();
	$stmt=NULL;	
}

function getnomprenom($id,$pdo)
{
	$req="  SELECT 	nom_user,
					prenom_user
					
		    FROM utilisateur
			WHERE
			utilisateur.id=:id";
    $stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
	$nom="";
	if ($donnees = $stmt->fetch())
	{
		$nom = $donnees['nom_user'].' '.$donnees['prenom_user'];
	}	
	$stmt->closeCursor();
	$pdo=NULL;

	return $nom;
}

function ApprouverPaieCorpsProfessorat($idanneescolaire,$debutmois,$finmois,$pdo)
{
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];

	$req=(' SELECT  
	                distinct 
					professeur.id as id,
					professeur.nom as nom,
					moissalaire.id as idmoissalaire,
					moissalaire.vol_horaire as vol_horaire,
					moissalaire.cout_horaire as cout_horaire,
					moissalaire.remboursement as remboursement
					
			FROM    professeur,moissalaire,mois
			
			WHERE
			professeur.id=moissalaire.idpers
			AND
			moissalaire.statut=1
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.idanneescolaire=:idanneescolaire
			AND
			professeur.corps=1
			AND
			moissalaire.corps=1
			AND
			mois.id=moissalaire.idmois

			ORDER BY professeur.nom asc');	
    ?>
	<table class="table table-striped projects" width="100%" style="font-size:13px;">
	    <thead>
		    <tr style="background-color:#E5F3FF;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:15%;"> Nom de l'enseignant</th>
				<th style="width:15%;"> Cout honoraire (FCFA/h) </th>
				<th style="width:15%;"> Vol. heure(s) attribu&eacute; </th>
				<th style="width:15%;"> Vol. heure(s) effectu&eacute; </th>
				<th style="width:15%;"> Retenue(s) (FCFA)</th>
				<th style="width:15%;"> Total Net (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':debutmois',$debutmois,PDO::PARAM_STR);
		$stmt ->bindParam(':finmois',$finmois,PDO::PARAM_STR);
		$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$idmoissalaire = $donnees['idmoissalaire'];
			$vol_horaire = $donnees['vol_horaire'];
			if($vol_horaire=="")
			{
				$vol_horaire=0;
			}
			$tab = explode('*',getCoutHonoraire($idanneescolaire,$id,$pdo));
			$cout_horaire = $tab[0];
			$vol_horaire_ = $tab[1];
			if($cout_horaire=="")
			{
				$cout_horaire=0;
			}
			if($vol_horaire_=="")
			{
				$vol_horaire_=0;
			}
			$remboursement = $donnees['remboursement'];
			if($remboursement=="")
			{
				$remboursement=0;
			}
			$totalnet = ($vol_horaire*$cout_horaire)-$remboursement;
			$total=$total+$totalnet;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom;?></a>
				</td>
				<td>
					<a><?php echo number_format($cout_horaire,0,""," ");?></a>
				</td>
				<td>
					<a>
					<?php 
						if($vol_horaire_!="")
						{
							echo number_format($vol_horaire_,0,""," ");
						}
					?>
					</a>
				</td>
				<td>
					<a><?php echo number_format($vol_horaire,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($remboursement,0,""," ");?></a>
				</td>
				<td>
					<a><?php echo number_format($totalnet,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
		<tr>
			<td colspan="2">
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					::: Total (FCFA) :::
				</span>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					<?php echo number_format($total,0,""," ");?>
					<input type="hidden" name="montanttotal" value="<?php echo $total;?>"/>
				</span>
			</td>
		</tr>
	</table><?php
}

function DisponibiliteCaisse($idanneescolaire,$pdo)
{
	
	$req=(' SELECT distinct compte.id as idcompte,compte.libelle as libellecompte FROM compte ORDER BY compte.id DESC');
    ?>
	<table class="table table-striped projects" width="100%" style="font-size:13px;">
	    <thead>
		    <tr style="background-color:#E5F3FF;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:15%;">Compte</th>
				<th style="width:15%;">Disponibilit&eacute; (FCFA)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$total=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idcompte = $donnees['idcompte'];
			$libellecompte = $donnees['libellecompte'];
			$disponibilite = getentreecompte($idanneescolaire,$idcompte,$pdo)-getsortiecompte($idanneescolaire,$idcompte,$pdo);
			$total = $total + $disponibilite;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idcompte<?php echo $ligne;?>" value="<?php echo $idcompte;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $libellecompte;?></a>
				</td>
				<td>
					<a><?php echo number_format($disponibilite,0,""," ");?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
		?>
		<tr>
			<td>
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					::: Total (FCFA) :::
				</span>
			</td>
			<td></td>
			<td>
				<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
					<?php echo number_format($total,0,""," ");?>
					<input type="hidden" name="montanttotal" value="<?php echo $total;?>"/>
				</span>
			</td>
		</tr>
	</table><?php
}