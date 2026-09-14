<?php
function getAllPersonnel($pdo)
{
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			WHERE
			professeur.statut=1
			ORDER BY professeur.nom asc');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="idprof" required="required">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];

			echo '<OPTION value="'.$id.'">'.$nom.'</OPTION>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllPersonnelSelected($idpers,$pdo)
{
	$req=(' SELECT  professeur.id,
	                professeur.nom
					
			FROM professeur
			WHERE
			professeur.statut=1
			ORDER BY professeur.nom asc');
	$stmt = $pdo->prepare($req);
    $stmt->execute();	
    ?>	
	<select class="select2_single form-control" tabindex="-1" name="idprof" required="required">
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
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function createPermission($idprod, $datedemande, $datedebut, $datefin, $motif, $statut, $fichier, $idusercreate, $pdo) {

	if(verifUnicitePermission($idprod, $datedebut, $datefin, $motif, $pdo)==false)
	{
		$sql = "INSERT INTO permission (IDPROD, DATEDEMANDE, DATEDEBUT, DATEFIN, MOTIF, STATUT, FICHIER, IDUSERCREATE, DATECREATE)
				VALUES (:idprod, :datedemande, :datedebut, :datefin, :motif, :statut, :fichier, :idusercreate, NOW())";
		$stmt = $pdo->prepare($sql);
		$res = $stmt->execute([
			'idprod' => $idprod,
			'datedemande' => $datedemande,
			'datedebut' => $datedebut,
			'datefin' => $datefin,
			'motif' => $motif,
			'statut' => $statut,
			'fichier' => $fichier,
			'idusercreate' => $idusercreate
		]);
		
		if ($res) {
			$success="✅ Enregistrement effectu&eacute;e avec succ&egrave;s";
			$error="";
		 } else {
			$success = "";
			$error = "❌ Erreur lors de l'enregistrement.";
		}
	}
	else
	{
		$success = "";
		$error = "❌ Une permission identique existe déjà.";
	}
	return $success.'*'.$error;
}

function getAllPermissions($pdo) {
    $sql = "SELECT * FROM permission ORDER BY DATEDEMANDE DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPermissionById($id, $pdo) {
    $sql = "SELECT * FROM permission WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePermission($id, $idprod, $datedebut, $datefin, $motif, $statut, $fichier, $pdo) {
	
    $sql = "UPDATE permission 
            SET IDPROD = :idprod, DATEDEBUT = :datedebut, DATEFIN = :datefin, 
                MOTIF = :motif, STATUT = :statut, FICHIER = :fichier
            WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $res = $stmt->execute([
        'id' => $id,
        'idprod' => $idprod,
        'datedebut' => $datedebut,
        'datefin' => $datefin,
        'motif' => $motif,
        'statut' => $statut,
        'fichier' => $fichier
    ]);
	
	if ($res) 
	{
		$success = "✅ Permission modifi&eacute;e avec succ&egrave;s.";;
		$error = "";
	} 
	else 
	{
		$success = "";;
		$error = "❌ Erreur lors de la modification.";
	}
	return $success.'*'.$error;
}

function deletePermission($id, $pdo) {
    $sql = "DELETE FROM permission WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

function getPermissionsByProd($idprod, $pdo) {
    $sql = "SELECT * FROM permission WHERE IDPROD = :idprod ORDER BY DATEDEMANDE DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['idprod' => $idprod]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function verifUnicitePermission($idprod, $datedebut, $datefin, $motif, $pdo) {
    $sql = "SELECT ID FROM permission 
            WHERE IDPROD = :idprod 
              AND DATEDEBUT = :datedebut 
              AND DATEFIN = :datefin 
              AND MOTIF = :motif";
              
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'idprod' => $idprod,
        'datedebut' => $datedebut,
        'datefin' => $datefin,
        'motif' => $motif
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

function ListePermissions($pdo)
{
	$req=(' SELECT  distinct 
					professeur.id AS idprofesseur,
					professeur.nom AS nomprofesseur,
					DATE_FORMAT(permission.datedemande, "%d/%m/%Y") AS datedemande,
					permission.motif AS motif,
					permission.statut AS statut,
					DATE_FORMAT(permission.datedebut, "%d/%m/%Y") AS datedebut,
					DATE_FORMAT(permission.datefin, "%d/%m/%Y") AS datefin,
					permission.id AS id,
					permission.fichier AS fichier
					
			FROM professeur,permission
			WHERE
			professeur.id=permission.idprod
			
			ORDER BY permission.datedemande DESC');	
    ?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
	    <thead>
		    <tr style="background-color:#eee;">
		        <th style="width:5%;text-align:center;font-weight:bold;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">#</th> 
				<th style="width:10%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Date</th>
				<th style="width:10%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Fichier</th>
				<th style="width:15%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Nom & pr&eacute;nom professeur</th>
				<th style="width:15%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Date d&eacute;but</th>
				<th style="width:15%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Date fin</th>
				<th style="width:15%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Motif</th>
				<th style="width:10%;color:#000;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);">Statut</th>
		    </tr>
	    </thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->execute();	
		$ligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idprofesseur = $donnees['idprofesseur'];
			$nomprofesseur = $donnees['nomprofesseur'];
			$datedemande = $donnees['datedemande'];
			$motif = $donnees['motif'];
			$statut = $donnees['statut'];
			$datedebut = $donnees['datedebut'];
			$datefin = $donnees['datefin'];
			$id = $donnees['id'];
			$fichier = $donnees['fichier'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td>
					<a><?php echo $datedemande;?></a>
				</td>
				<td>
					<?php if (!empty($fichier)) : ?>
						<a href="decisiondocument/<?= rawurlencode($fichier) ?>" target="_blank">
							<em style="color:red;font-weight:bold;">T&eacute;l&eacute;charger le fichier</em>
						</a>
					<?php else : ?>
						<em>Aucun fichier</em>
					<?php endif; ?>
				</td>
				<td>
					<a><?php echo $nomprofesseur;?></a>
				</td>
				<td>
					<a><?php echo $datedebut;?></a>
				</td>
				<td>
					<a><?php echo $datefin;?></a>
				</td>
				<td>
					<a><?php echo $motif;?></a>
				</td>
				<td>
					<a>
					<?php
						if($statut="En attente")
						{
						   ?><span class="label label-info"><?php echo $statut;?></span><?php
						}
						else
						{
						   ?><span class="label label-success"><?php echo $statut;?></span><?php
						}
					?>
				</td>
			</tr><?php
        }
	    $stmt->closeCursor();
	    $stmt=NULL;
    ?> 
	</table>
	<input type="hidden" name="nbrepermission" value="<?php echo $ligne;?>"/><?php
}