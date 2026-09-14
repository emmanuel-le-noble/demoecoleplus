<?php

   	$req=(' SELECT  distinct
					decision.id as iddecision,
					decision.idanneescolaire as idanneescolaire,
					decision.idposition as idposition,
					decision.datedecision as datedecision,
					decision.idclasse as idclasse,
					decision.moyenne as moyenne,
					decision.fichier as fichier
					
			FROM decision
			WHERE
			decision.id=:iddecision');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':iddecision',$iddecision,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$iddecision = $donnees['iddecision'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idposition = $donnees['idposition'];
			$datedecision = $donnees['datedecision'];
			$tab = explode("-",trim($datedecision));
			$datedecision = $tab[2]."/".$tab[1]."/".$tab[0];
			$idclasse = $donnees['idclasse'];
			$moyenne = $donnees['moyenne'];
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
						        Modifier la d&eacute;cision
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
						    [ Veuillez mettre &agrave; jour les information(s) ]</span></div><br/>
							<table width="100%">
								<tr>
									<td width="50%">
									    <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date conseil<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateconseil" required="required" value="<?php echo $datedecision;?>"/>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Fichier d&eacute;cision<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" class="form-control" name="image"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Trimestre<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllPositionSelected($idposition,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllClasseFiliereSelected($idclasse,$pdo);?>
											</div>
										</div>
									</td>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Moyenne r&eacute;ussite<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="moyenne" required="required" style="font-size:12px;" value="<?php echo $moyenne;?>"/>
												<input type="hidden" class="form-control" name="fichierold" value="<?php echo $fichier;?>"/>
												<input type="hidden" name="iddecision" value="<?php echo $iddecision;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class="">
												<?php
												if($fichier!="")
												{
													?><a href="decisiondocument/<?php echo $fichier;?>"><span style="color:red;">T&eacute;l&eacute;charger</span></a><?php
												}
												?>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='DecisionConseil.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierDecision" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>