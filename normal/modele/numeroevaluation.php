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

function getCodePosition($id,$pdo)
{
	$req=(' SELECT position.codeposition
			FROM position
			WHERE
			position.idposition=:idposition');
	$codeposition = "";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idposition',$id,PDO::PARAM_INT);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$codeposition = $donnees['codeposition'];
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $codeposition;
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

function getMaxnumeroEvaluation($pdo)
{
	$req=(' SELECT max(numero_evaluation.id) as id FROM numero_evaluation');
	$stmt = $pdo->prepare($req);
	$stmt->execute();
	$resultat=0;
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['id']+1;
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function DeleteNumeroEvaluation($idanneescolaire,$idposition,$idsalle,$pdo)
{
	$req=(' DELETE FROM numero_evaluation WHERE numero_evaluation.idsalle=:idsalle AND numero_evaluation.idanneescolaire=:idanneescolaire AND numero_evaluation.idposition=:idposition');
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
	$stmt->bindParam(':idposition', $idposition, PDO::PARAM_INT);
	$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	$stmt=NULL;
}

function CreateNumeroEvaluation($idsalle,$idelevesalle,$idposition,$idanneescolaire,$numerotable,$numeroanonymat,$idusercreate,$pdo)
{
	//
	if(getnumeroEvaluation($idelevesalle,$idposition,$idanneescolaire,$numerotable,$numeroanonymat,$pdo)=="")
	{
		$requete="INSERT INTO numero_evaluation(idsalle,idelevesalle,idposition,idanneescolaire,numero_anonymat,numero_table,datecreate,idusercreate) 
					VALUES(:idsalle,:idelevesalle,:idposition,:idanneescolaire,:numero_anonymat,:numero_table,sysdate(),:idusercreate)";

		$stmt = $pdo->prepare($requete);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':idelevesalle', $idelevesalle, PDO::PARAM_INT);
		$stmt->bindParam(':idsalle', $idsalle, PDO::PARAM_INT);
		$stmt->bindParam(':numero_anonymat', $numeroanonymat, PDO::PARAM_STR);
		$stmt->bindParam(':idanneescolaire', $idanneescolaire, PDO::PARAM_INT);
		$stmt->bindParam(':idposition', $idposition, PDO::PARAM_STR);
		$stmt->bindParam(':numero_table', $numerotable, PDO::PARAM_STR);
		$stmt->bindParam(':idusercreate', $idusercreate, PDO::PARAM_INT);
		$stmt ->execute();		
		$stmt ->closeCursor();
		$stmt =NULL;
	}
}

function genererNumeroTable($pdo) {
	
  do {
        $numero = 'T' . str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM numero_evaluation WHERE numero_table = :num");
        $stmt->execute([':num' => $numero]);
        $existe = $stmt->fetchColumn() > 0;

    } while ($existe);

    return $numero;
}

function genererNumeroAnonymat($pdo) {
	
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    do {
        $code = '';
        for ($i = 0; $i < 3; $i++) {
            $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM numero_evaluation WHERE numero_anonymat = :code");
        $stmt->execute([':code' => $code]);
        $existe = $stmt->fetchColumn() > 0;

    } while ($existe);

    return $code;
}

function ListEleveSalle($idsalle,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.matricule as matricule,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
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
			anneescolaire.statut=1

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:25%;">Matricule</th>
				<th style="width:25%;">Nom</th>
				<th style="width:25%;">Pr&eacute;nom</th>
				<th style="width:25%;"> Sexe (F/M)</th>
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
			
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$matricule = $donnees['matricule'];
			$sexe_eleve = $donnees['sexe_eleve'];
			?>
			<tr>
				<td style="float:center">
					<input class="flat" type="checkbox" checked="checked" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $matricule;?></a>
				</td>	
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbreelevesalle" value="<?php echo $ligne;?>"/><?php
}
//
function ListEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$pdo)
{
	$req=(' SELECT  
	                distinct 
					eleve.id_eleve as id_eleve,
					eleve.matricule as matricule,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
					elevesalle.id as idelevesalle,
					numero_evaluation.numero_anonymat as numero_anonymat,
					numero_evaluation.numero_table as numero_table

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,numero_evaluation
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
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			numero_evaluation.idanneescolaire=:idanneescolaire
			AND
			numero_evaluation.idposition=:idposition
			AND
			elevesalle.idsalle=:idsalle
			
			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<table class="table table-striped projects" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th> 
				<th style="width:15%;">Matricule</th>
				<th style="width:15%;">Nom</th>
				<th style="width:15%;">Pr&eacute;nom</th>
				<th style="width:15%;"> Sexe (F/M)</th>
				<th style="width:15%;">N° Table</th>
				<th style="width:15%;">N° Anonymat</th>
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
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$matricule = $donnees['matricule'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$numero_anonymat = $donnees['numero_anonymat'];
			$numero_table = $donnees['numero_table'];
			?>
			<tr>
				<td style="float:center">
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idelevesalle;?>"/>
				</td>
				<td>
					<a><?php echo $matricule;?></a>
				</td>	
				<td>
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td>
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td>
					<a><?php echo $numero_table;?></a>
				</td>
				<td>
					<a><?php echo $numero_anonymat;?></a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table><?php
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
	<select class="form-control col-md-2 col-xs-12" name="idsalle" required="required" id="idsalle" onchange="makeRequest('NumeroEvaluationListEleve.php','idsalle','ListEleve')">
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

function ListNumeroEvaluation($pdo)
{
	$req=(' SELECT  
					anneescolaire.id as idanneescolaire,
					anneescolaire.libelle as libelleanneescolaire,
					classe.idclasse as idclasse,
					classe.codeclasse as codeclasse,
					salle.id as idsalle,
					salle.codesalle as codesalle,
					position.idposition as idposition,
					position.libposition as libposition,
					count(elevesalle.id) as nbreelevesalle,
					count(numero_evaluation.id) as nbrenumero

			FROM classe,salle,anneescolaire,elevesalle,eleveanneescolaire,numero_evaluation,position
			WHERE
			classe.idclasse=salle.idclasse
			AND
			salle.id=elevesalle.idsalle
			AND
			elevesalle.ideleve=eleveanneescolaire.id
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			elevesalle.statut=1
			AND
			eleveanneescolaire.statut=1
			AND
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			numero_evaluation.idposition=position.idposition
			
			GROUP BY anneescolaire.id,classe.idclasse,salle.id,position.idposition
			
			ORDER BY salle.id DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;font-size:15px;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000">#</th>
				<th style="width:13%;font-weight:bold;color:#000">Ann&eacute;e scol.</th>
				<th style="width:13%;font-weight:bold;color:#000">&Eacute;valuation</th>
				<th style="width:13%;font-weight:bold;color:#000">Niveau</th>
				<th style="width:10%;font-weight:bold;color:#000">Classe</th>
				<th style="width:13%;font-weight:bold;color:#000">Effectif</th>
				<th style="width:13%;font-weight:bold;color:#000">Numéro(s) généré(s)</th>
				<th style="width:16%;font-weight:bold;color:#000">Imprimer Fiche(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		$fichier ="";
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idclasse = $donnees['idclasse'];
			$codeclasse = $donnees['codeclasse'];
			$idsalle = $donnees['idsalle'];
			$codesalle = $donnees['codesalle'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$idposition = $donnees['idposition'];
			$libposition = $donnees['libposition'];
			$nbreelevesalle = $donnees['nbreelevesalle'];
			$nbrenumero = $donnees['nbrenumero'];
			?>
			<tr>
			    <td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idanneescolaire.'*'.$idposition.'*'.$idsalle;?>"/>
				</td>
				<td>
					<a><?php echo $libelleanneescolaire;?></a>
			    </td>
				<td>
					<a><?php echo $libposition;?></a>
			    </td>
				<td>
					<a><?php echo $codeclasse;?></a>
			    </td>
				<td>
					<a><?php echo $codesalle;?></a>
			    </td>
				<td>
					<a><?php echo $nbreelevesalle;?></a>
			    </td>
				<td>
					<a><?php echo $nbrenumero;?></a>
			    </td>
				<td>
					<a href="#" onclick='window.open("EtatFicheNumeroAnonymat.php?id=<?php echo $idanneescolaire.'*'.$idposition.'*'.$idsalle;?>","", "fullscreen=yes, scrollbars=auto");' class="btn btn-primary btn-xs"><i class="fa fa-folder"></i>Anonymisation</a>
					&nbsp;&nbsp;
					<a href="#" onclick='window.open("EtatFicheNumeroTable.php?id=<?php echo $idanneescolaire.'*'.$idposition.'*'.$idsalle;?>","", "fullscreen=yes, scrollbars=auto");' class="btn btn-primary btn-xs"><i class="fa fa-folder"></i>Désanonymisation</a>
					&nbsp;&nbsp;
					<a href="#" onclick='window.open("EtatFicheNumeroCandidat.php?id=<?php echo $idanneescolaire.'*'.$idposition.'*'.$idsalle;?>","", "fullscreen=yes, scrollbars=auto");' class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> Candidat</a>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
	    ?>
	</table>
	<input type="hidden" name="nbrenumerogenere" value="<?php echo $ligne;?>"/><?php
}

function ImprimeFicheNumeroTable($idsalle,$idanneescolaire,$idposition,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement,
					numero_evaluation.numero_anonymat as numero_anonymat,
					numero_evaluation.numero_table as numero_table

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement,numero_evaluation
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			numero_evaluation.idanneescolaire=:idanneescolaire
			AND
			numero_evaluation.idsalle=:idsalle
			AND
			numero_evaluation.idposition=:idposition

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:15%;">Matricule</th>
				<th style="width:15%;">N° Table</th>
				<th style="width:15%;">N° Anonymat</th>
				<th style="width:15%;">Nom</th>
				<th style="width:15%;">Pr&eacute;nom</th>
				<th style="width:15%;">Sexe</th>
				<th style="width:15%;">Note/20</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			$numero_anonymat = $donnees['numero_anonymat'];
			$numero_table = $donnees['numero_table'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $matricule;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $numero_table;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $numero_anonymat;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $sexe_eleve;?></a>
				</td>
				<td><a></a></td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ImprimeFicheNumeroCandidat($idsalle,$idanneescolaire,$idposition,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement,
					numero_evaluation.numero_anonymat as numero_anonymat,
					numero_evaluation.numero_table as numero_table

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement,numero_evaluation
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			numero_evaluation.idanneescolaire=:idanneescolaire
			AND
			numero_evaluation.idsalle=:idsalle
			AND
			numero_evaluation.idposition=:idposition

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:10%;">N° Table</th>
				<th style="width:28%;">Nom</th>
				<th style="width:28%;">Pr&eacute;nom</th>
				<th style="width:10%;">Classe</th>
				<th style="width:19%;">Observation</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			$numero_anonymat = $donnees['numero_anonymat'];
			$numero_table = $donnees['numero_table'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $numero_table;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $nom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $prenom_eleve;?></a>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $CodeSalle;?></a>
				</td>
				<td><a></a></td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ImprimeFicheNumeroAnonymat($idsalle,$idanneescolaire,$idposition,$pdo)
{
	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.prenom_eleve as prenom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,	
                    eleve.datenaissance_eleve as datenaissance_eleve,
					eleve.matricule as matricule,
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut,
					elevestatutclasse.libelle as elevestatutclasse,
					elevestatutetablissement.libelle as elevestatutetablissement,
					numero_evaluation.numero_anonymat as numero_anonymat,
					numero_evaluation.numero_table as numero_table

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle,elevestatutclasse,elevestatutetablissement,numero_evaluation
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
			AND
			eleveanneescolaire.etat=elevestatutclasse.id
			AND
			eleveanneescolaire.statut=elevestatutetablissement.id
			AND
			elevesalle.id=numero_evaluation.idelevesalle
			AND
			numero_evaluation.idanneescolaire=:idanneescolaire
			AND
			numero_evaluation.idsalle=:idsalle
			AND
			numero_evaluation.idposition=:idposition

			ORDER BY  eleve.nom_eleve asc');	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;">#</th>
				<th style="width:31%;">N° Anonymat</th>
				<th style="width:31%;">Note/20</th>
				<th style="width:31%;">Observation(s)</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
		$stmt->bindParam(':idposition',$idposition,PDO::PARAM_INT);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$elevestatutetablissement = $donnees['elevestatutetablissement'];
			$elevestatutclasse = $donnees['elevestatutclasse'];
			$nom_eleve = $donnees['nom_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$etat_eleve = $donnees['etat_eleve'];
			$matricule = $donnees['matricule'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
			$numero_anonymat = $donnees['numero_anonymat'];
			$numero_table = $donnees['numero_table'];
			?>
			<tr>
				<td align="center">
					<?php echo $ligne;?>
				</td>
				<td style="border:1px solid #000;">
					<a><?php echo $numero_anonymat;?></a>
				</td>
				<td><a></a></td>	
				<td><a></a></td>				
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?>
	</table><?php
}

function ImprimeReleveNotesPrimaire($idsalle,$idanneescolaire,$idposition,$pdo)
{	
    ?>
	<br/>
	<table class="table table-bordered" width="100%" style="background-color:#fff;">
	    <thead>
		    <tr style="background-color:#eee;">
				<th style="width:33%;">DISCIPLINES</th>
				<th style="width:33%;">NOTES</th>
				<th style="width:33%;">SUR</th>
		    </tr>
	    </thead>
		<tr>
			<td align="center">
				<a>Dictée</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="center">
				<a>Questions</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="center">
				<a>Etude de texte</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="center">
				<a>Rédaction</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="center">
				<a>Calcul mental</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="center">
				<a>Exercices écrits de calcul</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
		<tr>
			<td align="center">
				<a>Problème</a>
			</td>
			<td><a></a></td>
			<td><a></a></td>
		</tr>
	</table><?php
}