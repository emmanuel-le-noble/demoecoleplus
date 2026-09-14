<?php
   	$req=(' SELECT  distinct
	                salle.id as id,
					salle.codesalle as codesalle,
					salle.nomsalle as nomsalle,
					salle.idclasse as idclasse

			FROM    salle
			WHERE
			salle.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$idclasse = $donnees['idclasse'];
			$idsalle = $donnees['id'];
			$codesalle = $donnees['codesalle'];
			$nomsalle = $donnees['nomsalle'];
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
						        Modifier une Classe/Fili&egrave;re
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
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Classe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllClasseSelected($idclasse,$pdo);?>
											    <input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Code</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="codesalle" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $codesalle;?>"/>
											</div>
										</div>
									</td>
									<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nomsalle" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nomsalle;?>"/>
											</div>
										</div>
									</td>
								</tr>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Classe.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierSalle" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>