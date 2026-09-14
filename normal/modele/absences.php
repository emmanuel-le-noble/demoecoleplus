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
	<select class="form-control col-md-2 col-xs-12" required="required" name="idanneescolaire" style="font-size:12px;">
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
	<select class="form-control col-md-2 col-xs-12" required="required" name="idanneescolaire" style="font-size:12px;">
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
	<select class="form-control col-md-2 col-xs-12" name="idposition" style="font-size:12px;">
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
	<select class="form-control col-md-2 col-xs-12" name="idposition" style="font-size:12px;">
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
	<select class="form-control col-md-2 col-xs-12" name="idsalle" required="required" style="font-size:12px;">
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
	<select class="form-control col-md-2 col-xs-12" name="idsalle" style="font-size:12px;">
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
	<select class="form-control col-md-2 col-xs-12" name="idmatiere" id="idmatiere" onchange="makeRequest('ListeEleve.php','idmatiere','resultat')" style="font-size:12px;">
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

function getAllMatiere($idsalle,$idanneescolaire,$idposition,$statutanneescolaire,$pdo)
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
	<select class="form-control col-md-2 col-xs-12" name="idmatiere" id="idmatiere" onchange="makeRequest('ListeEleve.php','idmatiere','resultat')" style="font-size:12px;">
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

function AbsencesMatiereByProf($idmatieres,$idsalle,$idposition,$idanneescolaire,$idprof,$pdo)
{
	
	$req=(' SELECT  distinct 
					matiere.code_matiere as codematiere,
					matiere.id_matiere as idmatiere
					
			FROM matiere,absence
			WHERE
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idposition=:idposition
			AND
			absences.idsalle=:idsalle
			AND
			matiere.id_matiere=absences.idmatiere
            AND
			absences.idprofesseur=:idprof
			
			ORDER BY matiere.code_matiere'
		);	
    ?>
	<table class="table table-striped table-bordered" width="100%" style="font-size:12px;">
	    <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:95%;text-align:center">Mati&egrave;re</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$codematiere = $donnees['codematiere'];
			$idmatiere = $donnees['idmatiere'];
			?>
			<tr>
				<td>
				<?php
					if($idmatieres==$id_matiere)
					{
						?>
						<input class="flat" type="checkbox" checked="checked" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/><?php
					}
					else
					{
						?>
						<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/><?php
					}
                ?>				
				</td>
				<td>
					<a><?php echo $codematiere;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresalleabsences" value="<?php echo $ligne;?>"/><?php
}

function AbsencesMatiere($idmatieres,$idsalle,$idposition,$idanneescolaire,$pdo)
{
	
	$req=(' SELECT  distinct 
					matiere.code_matiere as codematiere,
					matiere.id_matiere as idmatiere
					
			FROM matiere,absences
			WHERE
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idposition=:idposition
			AND
			absences.idsalle=:idsalle
			AND
			matiere.id_matiere=absences.idmatiere

			ORDER BY matiere.code_matiere'
		);	
    ?>
	<table class="table table-striped table-bordered" width="100%" style="font-size:12px;">
	    <thead>
		    <tr>
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:95%;text-align:center">Mati&egrave;re</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$codematiere = $donnees['codematiere'];
			$idmatiere = $donnees['idmatiere'];
			?>
			<tr>
				<td>
				<?php
					if($idmatieres==$idmatiere)
					{
						?>
						<input class="flat" type="checkbox" checked="checked" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/><?php
					}
					else
					{
						?>
						<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle."-".$idmatiere;?>"/><?php
					}
                ?>				
				</td>
				<td>
					<a><?php echo $codematiere;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresalleabsences" value="<?php echo $ligne;?>"/><?php
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

function DeleteAbsencesMatiere($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	$req=(' DELETE FROM absences WHERE absences.idsalle=:idsalle AND absences.idposition=:idposition AND absences.idanneescolaire=:idanneescolaire AND absences.idmatiere=:idmatiere');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function CreateAbsencesMatiere($idelevesalle,$nbreabsence,$idposition,$idsalle,$idanneescolaire,$idmatiere,$idprof,$idusercreate,$pdo)
{
	if(getidAbsencesEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$idmatiere,$pdo)==0)
	{
		$requete="INSERT INTO absences(idelevesalle,idsalle,idanneescolaire,idposition,idmatiere,idprof,nbreabsence,dateenreg,idusercreate) VALUES(:idelevesalle,:idsalle,:idanneescolaire,:idposition,:idmatiere,:idprof,:nbreabsence,sysdate(),:idusercreate)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
		$stmt->bindParam(':nbreabsence', $nbreabsence, PDO::PARAM_INT);
		$stmt->bindParam(':idusercreate', $idusercreate, PDO::PARAM_INT);
		$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
		$stmt->bindParam(':idprof', $idprof, PDO::PARAM_INT);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
	}
}

function getidAbsencesMatiereEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$idmatiere,$pdo)
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
			absences.idsalle=:idsalle
			AND
			absences.idmatiere=:idmatiere');
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getNbreAbsencesMatiereEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$idmatiere,$pdo)
{
	$req=(' SELECT distinct absences.nbreabsence
			FROM absences
			WHERE 
			absences.idelevesalle=:idelevesalle
			AND
			absences.idposition=:idposition
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idsalle=:idsalle
			AND
			absences.idmatiere=:idmatiere');
			
	$resultat=0;		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->bindParam(':idmatiere', $idmatiere, PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nbreabsence'];
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

function ListEleveSalle($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo)
{
	
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped projects" width="50%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:25%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			?>
			<tr>
				<td style="float:center">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $nom_eleve.' '.$prenom_eleve;?></a>
				</td>			
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListeAbsencesMatiere($pdo)
{
	
	$req=(' SELECT  distinct 
					anneescolaire.id as idanneescolaire,
	                anneescolaire.libelle as anneescolaire,
					position.idposition as idposition,
	                position.libposition as position,
					salle.id as idsalle,
					salle.codesalle as salle,
					matiere.id_matiere as idmatiere,
					matiere.code_matiere as codematiere,
					sum(absences.nbreabsence) as nbreabsence
					
			FROM anneescolaire,salle,position,absences,matiere
			WHERE
			anneescolaire.id=absences.idanneescolaire
			AND
			salle.id=absences.idsalle
			AND
			position.idposition=absences.idposition
			AND
			anneescolaire.statut=1
			AND
			absences.idmatiere=matiere.id_matiere

			GROUP BY absences.idanneescolaire,absences.idsalle,absences.idposition,absences.idmatiere');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%" style="font-size:12px;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th> 
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">ANN&Eacute;E SCOLAIRE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">P&Eacute;RIODE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">CLASSE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">MATI&Egrave;RE</th>
				<th style="width:19%;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">TOTAL ABSENCE (en heure)</th>
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
			$anneescolaire = $donnees['anneescolaire'];
			$idposition = $donnees['idposition'];
			$position = $donnees['position'];
			$idsalle = $donnees['idsalle'];
			$salle = $donnees['salle'];
			$codematiere = $donnees['codematiere'];
			$idmatiere = $donnees['idmatiere'];
			$nbreabsence = $donnees['nbreabsence'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire.'*'.$idposition.'*'.$idsalle.'*'.$idmatiere;?>"/>
				</td>
				<td>
					<a><?php echo $anneescolaire;?></a>
				</td>
				<td>
					<a><?php echo $position;?></a>
				</td>
				<td>
					<a><?php echo $salle;?></a>
				</td>
				<td>
					<a><?php echo $codematiere;?></a>
				</td>
				<td>
					<a><?php echo $nbreabsence;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresabsences" value="<?php echo $ligne;?>"/><?php
}
//
function ListeAbsencesParSalle($idsalle,$pdo)
{
	$req=(' SELECT  distinct 
	                anneescolaire.libelle as Anneescolaire,
	                position.libposition as Position,
					salle.codesalle as Salle,
					count(absences.id) as nbreAbsences,
					absences.idanneescolaire as idanneescolaire,
					absences.idsalle as idsalle,
					absences.idposition as idposition
					
			FROM anneescolaire,salle,position,absences
			WHERE
			anneescolaire.id=absences.idanneescolaire
			AND
			salle.id=absences.idsalle
			AND
			position.idposition=absences.idposition
			AND
			anneescolaire.statut=1
			AND
			salle.id=:idsalle

			GROUP BY absences.idanneescolaire,absences.idsalle,absences.idposition');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:95%;">P&eacute;riode</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$Anneescolaire = $donnees['Anneescolaire'];
			$Position = $donnees['Position'];
			$Salle = $donnees['Salle'];
			$nbreAbsences = $donnees['nbreAbsences'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$idsalle = $donnees['idsalle'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id_<?php echo $ligne;?>" value="<?php echo $idanneescolaire."-".$idposition."-".$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $Position;?></a>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbresabsences" value="<?php echo $ligne;?>"/><?php
}
//
function ListEleveSalleForEnregistrementAbsences($idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=:idsalle
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			anneescolaire.id=:idanneescolaire
			AND
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:55%;">Nom & pr&eacute;nom(s) &eacute;l&egrave;ve</th>
				<th style="width:40%;">Nombre(s) absence(s) (en heure)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$nbreAbsence = getNbreAbsencesEleve($idelevesalle,$idposition,$idanneescolaire,$idsalle,$pdo);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<input type="text" autocomplete="off" id="nbreabsence<?php echo $ligne;?>" name="nbreabsence<?php echo $ligne;?>" value="<?php echo $nbreAbsence;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}

function ListeAbsencesEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					elevesalle.id as idelevesalle,
					absences.nbreAbsence

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,absences
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			elevesalle.id=absences.idelevesalle
			AND
			absences.idsalle=:idsalle
			AND
			absences.idanneescolaire=:idanneescolaire
			AND
			absences.idposition=:idposition

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:55%;">Nom & pr&eacute;nom &eacute;l&egrave;ve</th>
				<th style="width:40%;">Nombre(s) absence(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'].' '.$donnees['prenom_eleve'];
			$nbreAbsence = $donnees['nbreAbsence'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>" checked="checked"/>
				</td>
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<input type="text" readonly="yes" autocomplete="off" id="nbreabsence<?php echo $ligne;?>" name="nbreabsence<?php echo $ligne;?>" value="<?php echo $nbreAbsence;?>" class="form-control col-md-7 col-xs-12"/>
				</td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}