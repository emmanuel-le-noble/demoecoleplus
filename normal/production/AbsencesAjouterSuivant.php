<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Ajouter les absences des &eacute;l&egrave;ves
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
						    [ Information(s) sur l'enregistrement des absences ]</span></div><br/>
						    <table width="100%">
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">P&eacute;riode : <?php echo $libelleposition;?></label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Classe : <?php echo $codesalle;?></label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?></label>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
							<div style="margin-bottom:5px;"><span style="font-family:comic sans ms;color:#000;font-weight:bold">::: Veuillez enregistrer les absences des &eacute;l&egrave;ves :::</span></div>
							<br/>
							<div><?php ListEleveSalleForEnregistrementAbsences($idsalle,$idposition,$idanneescolaire,$pdo);?></div> 
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Absences.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="AjouterAbsencesEleve" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
				<div class="col-md-3 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><span style="font-family:comic sans ms;color:red;font-size:10px;">::: Absences enregistr&eacute;e(s) :::</span></h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <table width="100%">
								<tr>
									<td> 
										<div class="item form-group">
											<input type="submit" style="width:80px;" class="btn btn-info btn-xs" name="Modifier" value="Consulter"/>
											&nbsp;
											<?php ListeAbsencesParSalle($idsalle,$pdo);?>
										</div>
									</td>
								</tr>
							</table>				
					    </div>
                    </div>
                </div>
				<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
				<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
				<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
				<input type="hidden" name="libelleposition" value="<?php echo $libelleposition;?>"/>
				<input type="hidden" name="codesalle" value="<?php echo $codesalle;?>"/>
				<input type="hidden" name="libelleanneescolaire" value="<?php echo $libelleanneescolaire;?>"/>
				<input type="hidden" name="action" value="add"/>		
            </div>
        </div>
    </div>
    </body>
</html>