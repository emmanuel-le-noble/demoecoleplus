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
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier les absences des &eacute;l&egrave;ves
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div>
							<span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Information(s) ]</span></div><br/>
						    <table width="100%">
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Période : <?php echo $LibellePosition;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Classe : <?php echo $CodeSalle;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Ann&eacute;e scolaire : <?php echo $LibelleAnneeScolaire;?>
											</label>
										</div>
									</td>
								</tr>
							</table>
							<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
							<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
							<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
							<input type="hidden" name="libelleposition" value="<?php echo $LibellePosition;?>"/>
							<input type="hidden" name="codesalle" value="<?php echo $CodeSalle;?>"/>
							<input type="hidden" name="libelleanneescolaire" value="<?php echo $LibelleAnneeScolaire;?>"/>
							<input type="hidden" name="action" value="update"/>
							<hr style="border:1px dotted #000;" />
							<div><span style="font-family:comic sans ms;color:#000;font-weight:bold;">
							::: La liste des &eacute;l&egrave;ves : Veuillez donc modifier les absences des &eacute;l&egrave;ves :::</span></div>
							<br/>
							<div><?php ListEleveSalleForEnregistrementAbsences($idsalle,$idposition,$idanneescolaire,$pdo);?></div>
							<hr/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Absences.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="AjouterAbsencesEleve" value="Enregistrer"/> 
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
											<input type="submit" style="width:80px;" class="btn btn-info btn-xs" name="Modifier" value="Consulter" data-toggle="tooltip" data-placement="top"/>
											&nbsp;
											<?php ListeAbsencesParSalle($idsalle,$pdo);?>
										</div>
									</td>
								</tr>
							</table>				
					    </div>
                    </div>
                </div>
				
            </div>
        </div>
    </div>
    </body>
</html>