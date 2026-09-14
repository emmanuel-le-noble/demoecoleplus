<?php

	$req=(" SELECT  
			
					professeurdonneepaie.id as id,
					professeurdonneepaie.idpers as idpers,
					professeurdonneepaie.cout_honoraire as cout_honoraire,
					professeurdonneepaie.idanneescolaire as idanneescolaire
					
			FROM professeurdonneepaie
			WHERE
			professeurdonneepaie.id=:id");
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt->execute();
	$cout_honoraire="";
	if($donnees = $stmt->fetch())
	{
		$id = $donnees['id'];
		$idpers = $donnees['idpers'];
		$idanneescolaire = $donnees['idanneescolaire'];
		$cout_honoraire = round(str_replace(" ","",$donnees['cout_honoraire']));
		if($cout_honoraire=="" || $cout_honoraire=="-")
		{
			$cout_honoraire=0;
		}
	}
	$stmt -> closeCursor();
	$stmt = NULL;
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
								Modifier les donn&eacute;es de paie de l'enseignant
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<br/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom de l'enseignant</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllProfesseurSelected($idpers,$pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Cout Honoraire (FCFA)
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="cout_honoraire" class="form-control col-md-7 col-xs-12" value="<?php echo $cout_honoraire;?>"/>
												<input type="hidden" name="id" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='ProfesseurDonneesPaie.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierProfesseurDonneesPaie" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>