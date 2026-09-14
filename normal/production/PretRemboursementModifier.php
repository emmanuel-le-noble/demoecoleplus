<?php
	$req=(' SELECT  
					distinct 
					pretremboursement.id as idpretremboursement,
					pretremboursement.idcompte as idcompte,
					pretremboursement.idanneescolaire as idanneescolaire,
					pretremboursement.idpret as idpret,
					pretremboursement.dateoperation as dateoperation,
					pretremboursement.fichier as fichier
					
		FROM pretremboursement
		WHERE
		pretremboursement.id=:id');
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$idpretremboursement,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$idpretremboursement = $donnees['idpretremboursement'];
			$idcompte = $donnees['idcompte'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idpret = $donnees['idpret'];
			$dateoperation = $donnees['dateoperation'];
			$tab = explode("-",$dateoperation);
			$dateoperation = $tab[2]."/".$tab[1]."/".$tab[0];
			$fichier = $donnees['fichier'];
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
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier un remboursement de pret
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
								Veuillez renseigner les information(s) pour mettre &eagrave; jour l'op&eacute;ration</span></div>
							<hr style="border:1px dotted #000;"/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date op&eacute;ration</label>
											<div class='col-sm-4'>
												<div class='input-group date' id='myDatepicker28' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="datepretremboursement" autocomplete="off" required="required" value="<?php echo $dateoperation;?>"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Fiche scann&eacute;e<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td align="left">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Cr&eacute;ancier
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<?php getAllPretPersonnel($idpret,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte op&eacute;ration</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<?php getAllCompte($pdo,$pdo);?>
											</div>
										</div>
									</td>
								</tr>								
								<tr>
									<td colspan="2">
										<div id="tableauamortissement"></div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PretRemboursement.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="EnregistrerPretRemboursement" value="Enregistrer"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>