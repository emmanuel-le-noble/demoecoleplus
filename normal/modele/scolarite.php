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
	<select class="form-control col-md-2 col-xs-12" name="idposition">
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
	<select class="form-control col-md-2 col-xs-12" name="idposition">
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
	<select class="form-control col-md-2 col-xs-12" name="idsalle" required="required">
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
					
			FROM salle
			WHERE
			salle.statut=0');
			
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

function getAllMatiere($ligne,$pdo)
{

	$req=(' SELECT  distinct 
					matiere.id_matiere as idmatiere,
					matiere.code_matiere as codematiere
					
			FROM matiere,matierecoefficient,salle
			WHERE
			matiere.id_matiere=matierecoefficient.idmatiere
			AND
			matierecoefficient.idclasse=salle.idclasse
			AND
			matierecoefficient.statut=1
			AND
			matierecoefficient.coefficient<>0');
			
	$stmt = $pdo->prepare($req);	
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idmatiere<?php echo $ligne;?>">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idmatiere = $donnees['idmatiere'];
			$codematiere = $donnees['codematiere'];
			
			echo '<option value="'.$idmatiere.'">'.$codematiere.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllMatiereSelected($ligne,$idmatiereselected,$pdo)
{

	$req=(' SELECT  distinct 
					matiere.id_matiere as idmatiere,
					matiere.code_matiere as codematiere
					
			FROM matiere,matierecoefficient,salle
			WHERE
			matiere.id_matiere=matierecoefficient.idmatiere
			AND
			matierecoefficient.idclasse=salle.idclasse
			AND
			matierecoefficient.statut=1
			AND
			matierecoefficient.coefficient<>0');
			
	$stmt = $pdo->prepare($req);		
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idmatiere<?php echo $ligne;?>">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idmatiere = $donnees['idmatiere'];
			$codematiere = $donnees['codematiere'];
			if($idmatiere==$idmatiereselected)
			{
				echo '<option value="'.$idmatiere.'" selected="selected">'.$codematiere.'</option>';
			}
			else
			{
				echo '<option value="'.$idmatiere.'">'.$codematiere.'</option>';
			}	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllJour($ligne,$pdo)
{

	$req=(' SELECT  distinct 
					jour.id as idjour,
					jour.code as codejour
					
			FROM jour');
			
	$stmt = $pdo->prepare($req);	
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idjour<?php echo $ligne;?>">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idjour = $donnees['idjour'];
			$codejour = $donnees['codejour'];
			
			echo '<option value="'.$idjour.'">'.$codejour.'</option>';	
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllJourSelected($ligne,$idjourselected,$pdo)
{

	$req=(' SELECT  distinct 
					jour.id as idjour,
					jour.code as codejour
					
			FROM jour');
			
	$stmt = $pdo->prepare($req);	
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idjour<?php echo $ligne;?>">
	<option></option>
	<?php
		while($donnees = $stmt->fetch())
		{
			$idjour = $donnees['idjour'];
			$codejour = $donnees['codejour'];
			if($idjour==$idjourselected)
			{
				echo '<option value="'.$idjour.'" selected="selected">'.$codejour.'</option>';
			}
			else
			{
				echo '<option value="'.$idjour.'">'.$codejour.'</option>';
			}
		}		
	?>
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllProfesseur($ligne,$pdo)
{
	
	$req=(' SELECT  distinct 
					professeur.id as id,
	                professeur.nom as nom
					
			FROM professeur
			
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof<?php echo $ligne;?>">
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

function getAllProfesseurSelected($ligne,$idprof,$pdo)
{
	
	$req=(' SELECT  professeur.id as id,
	                professeur.nom as nom
					
			FROM professeur
			
			ORDER BY professeur.nom asc');
			
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof<?php echo $ligne;?>">
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
	</select><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function EmploiTemps($pdo)
{
	
    ?>
	<br/>
	<table class="table table-striped projects" width="100%" style="font-size:12px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;text-align:center">Jour</th>
				<th style="width:19%;text-align:center">Heure Debut</th>
				<th style="width:19%;text-align:center">Heure Fin</th>
				<th style="width:19%;text-align:center">Mati&egrave;re</th>
				<th style="width:19%;text-align:center">Professeur</th>
		    </tr>
	    </thead>
		<?php	
		$ligne=7;
		for($i=1;$i<=$ligne;$i++)
		{		
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $i;?>" value="<?php echo $i;?>" checked="checked"/>
				</td>
				<td>
					<?php getAllJour($i,$pdo);?>
				</td>
				<td>
					<input type="text" class="form-control" data-inputmask="'mask':'99:99'" name="heuredebut<?php echo $i;?>" autocomplete="off"/>
				</td>
				<td>					
					<input type="text" class="form-control" data-inputmask="'mask':'99:99'" name="heurefin<?php echo $i;?>" autocomplete="off"/>
				</td>
				<td>					
					<?php getAllMatiere($i,$pdo);?>
				</td>
				<td>
					<?php getAllProfesseur($i,$pdo);?>
				</td>
			</tr><?php
		}
    ?> 
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $i;?>"/><?php
}

function EmploiTempsSelected($idsalle,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  distinct 
					salleemploitemps.id as id,
					salleemploitemps.idsalle idsalle,
					salleemploitemps.idmatiere as idmatiere,
					salleemploitemps.idprof as idprof,
					salleemploitemps.heuredebut as heuredebut,
					salleemploitemps.heurefin as heurefin,
					salleemploitemps.idanneescolaire as idanneescolaire,
					salleemploitemps.idjour as idjour
					
			FROM salleemploitemps
			WHERE
			salleemploitemps.idanneescolaire=:idanneescolaire
			AND
			salleemploitemps.idsalle=:idsalle
			
			ORDER BY salleemploitemps.idjour ASC'
		);	
    ?>
	<table class="table table-striped table-bordered" width="100%" style="font-size:12px;">
	     <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:19%;text-align:center">Jour</th>
				<th style="width:19%;text-align:center">Heure Debut</th>
				<th style="width:19%;text-align:center">Heure Fin</th>
				<th style="width:19%;text-align:center">Mati&egrave;re</th>
				<th style="width:19%;text-align:center">Professeur</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$id = $donnees['id'];
			$idsalle = $donnees['idsalle'];
			$idmatiere = $donnees['idmatiere'];
			$idprof = $donnees['idprof'];
			$heuredebut = $donnees['heuredebut'];
			$heurefin = $donnees['heurefin'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idjour = $donnees['idjour'];
			?>
			<tr>
				<td style="text-align:center;">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>" checked="checked"/>
				</td>
				<td>
					<?php getAllJourSelected($ligne,$idjour,$pdo);?>
				</td>
				<td>
					<input type="text" class="form-control" data-inputmask="'mask':'99:99'" name="heuredebut<?php echo $ligne;?>" value="<?php echo $heuredebut;?>" autocomplete="off"/>
				</td>
				<td>
					<input type="text" class="form-control" data-inputmask="'mask':'99:99'" name="heurefin<?php echo $ligne;?>" value="<?php echo $heurefin;?>" autocomplete="off"/>
				</td>
				<td>
					<?php getAllMatiereSelected($ligne,$idmatiere,$pdo);?>
				</td>
				<td>
					<?php getAllProfesseurSelected($ligne,$idprof,$pdo);?>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"/><?php
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

function DeleteSalleEmploiTemps($idsalle,$idanneescolaire,$pdo)
{
	
	$req=(' DELETE FROM salleemploitemps WHERE salleemploitemps.idanneescolaire=:idanneescolaire AND salleemploitemps.idsalle=:idsalle');
	
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function CreateSalleEmploiTemps($idsalle,$idanneescolaire,$idjour,$idmatiere,$idprof,$heuredebut,$heurefin,$idusercreate,$pdo)
{
	
	if(getidSalleEmploiTemps($idjour,$idsalle,$idanneescolaire,$idmatiere,$idprof,$heuredebut,$heurefin,$pdo)==0)
	{
		
		$requete="INSERT INTO salleemploitemps(idjour,idsalle,idmatiere,idanneescolaire,idprof,heuredebut,heurefin,create_id) VALUES(:idjour,:idsalle,:idmatiere,:idanneescolaire,:idprof,:heuredebut,:heurefin,:create_id)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':heuredebut', $heuredebut, PDO::PARAM_STR);
		$stmt->bindParam(':heurefin', $heurefin, PDO::PARAM_STR);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':create_id', $idusercreate, PDO::PARAM_INT);
		$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt->bindParam(':idjour', $idjour, PDO::PARAM_INT);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
	}
}

function getidSalleEmploiTemps($idjour,$idsalle,$idanneescolaire,$idmatiere,$idprof,$heuredebut,$heurefin,$pdo)
{
	
	$req=(' SELECT salleemploitemps.id as id
			FROM salleemploitemps
			WHERE 
			salleemploitemps.idprof=:idprof
			AND
			salleemploitemps.heuredebut=:heuredebut
			AND
			salleemploitemps.heurefin=:heurefin
			AND
			salleemploitemps.idsalle=:idsalle
			AND
			salleemploitemps.idmatiere=:idmatiere
			AND
			salleemploitemps.idanneescolaire=:idanneescolaire
			AND
			salleemploitemps.idjour=:idjour');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':heuredebut', $heuredebut, PDO::PARAM_STR);
	$stmt->bindParam(':heurefin', $heurefin, PDO::PARAM_STR);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
	$stmt->bindParam(':idjour', $idjour, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function UpdateSalleEmploiTemps($id,$idsalle,$idanneescolaire,$idjour,$idmatiere,$idprof,$heuredebut,$heurefin,$idusercreate,$pdo)
{
			
	$requete=" 	UPDATE 
				salleemploitemps
				SET
				idjour=:idjour,
				idsalle=:idsalle,
				idmatiere=:idmatiere,
				idanneescolaire=:idanneescolaire,
				idprof=:idprof,
				heuredebut=:heuredebut,
				heurefin=:heurefin,
				create_id=:create_id
				WHERE
				salleemploitemps.id=:id";
				
	$stmt = $pdo->prepare($requete);
	$stmt->bindParam(':heuredebut',$heuredebut,PDO::PARAM_STR);
	$stmt->bindParam(':heurefin',$heurefin,PDO::PARAM_STR);
	$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->bindParam(':create_id',$idusercreate,PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere',$idmatiere,PDO::PARAM_INT);
	$stmt->bindParam(':idprof',$idprof,PDO::PARAM_INT);
	$stmt->bindParam(':idjour',$idjour,PDO::PARAM_INT);
	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt ->execute();		
	$stmt ->closeCursor();
	$stmt =NULL;
	
}

function ListEmploiTemps($pdo)
{
	
	$req=(' SELECT  distinct 
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					salle.id as idsalle,
					salle.codesalle as codesalle
					
			FROM anneescolaire,salle,salleemploitemps
			WHERE
			salleemploitemps.idanneescolaire=anneescolaire.id
			AND
			salleemploitemps.idsalle=salle.id
			
			ORDER BY anneescolaire.id DESC'
		);	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;">#</th> 
				<th style="width:26%;font-weight:bold;color:#000;">Ann&eacute;e scol.</th>
				<th style="width:26%;font-weight:bold;color:#000;">Code classe</th>
				<th style="width:15%;font-weight:bold;color:#000;">Action(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idsalle = $donnees['idsalle'];
			$codesalle = $donnees['codesalle'];
			?>
			<tr>
				<td style="float:center">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idanneescolaire.'*'.$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $codesalle;?></a>
				</td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs">
						---> Imprimer
					</a></div>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresalleemploitemps" value="<?php echo $ligne;?>"/><?php
}