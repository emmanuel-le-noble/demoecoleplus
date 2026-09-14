<?php
   	$req=(' SELECT  distinct
	                professeursallemat.id as id,
					professeursallemat.idsalle as idsalle,
					professeursallemat.idprof as idprof,
                    professeursallemat.idmat as idmat,
                    professeursallemat.idanneescolaire as idanneescolaire,
                    professeursallemat.idtitre as idtitre

			FROM    professeursallemat
			WHERE
			professeursallemat.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$idsalle = $donnees['idsalle'];
			$idprof = $donnees['idprof'];
			$idmat = $donnees['idmat'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idtitre = $donnees['idtitre'];
        }
    $stmt->closeCursor();
	$stmt=NULL; 
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Lier les Professeurs aux Mati&egrave;res
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez mettre &agrave; jour les information(s) ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>	
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Professeur</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllProfesseurSelected($idprof,$pdo);?>
												<input type="hidden" name="id" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Mati&egrave;re</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllMatiereSelected_($idmat,1,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Classe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllSalleSelected($idsalle,$pdo);?>
											</div>
										</div>
									</td>	
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Titre</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllTitreSelect($idtitre,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='ProfesseurMatiere.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierMatiereCoefficient" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>