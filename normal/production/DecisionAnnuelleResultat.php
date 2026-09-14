<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:red;">
						        	Statistique d'&eacute;valuation
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>							    
									<div class="clearfix"></div>
										<label class="control-label col-md-5 col-sm-5 col-xs-12" 
										style="text-align:left;color:#000;">
										<i>::: Veuillez renseigner les information(s) pour avoir votre statistique :::</i></label><br/><br/>
										<table width="100%" align="center">
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">P&eacute;riode<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllPositionSelected($idposition,$pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllSalleSelected($idsalle,$pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Statut<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllEtatSelected($etat,$pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:right;">Moyenne<span class="required">&nbsp;&nbsp;
														<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="moyenne" style="background-color:#A2C600;" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $moyenne;?>"/>
													</div>
												</div>
											</td>
										</tr>
									</table><hr style="margin-top:5px;margin-bottom:5px;"/>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="button" class="btn btn-round btn-primary" value="Fermer" 
										onclick="document.location='Inscription.php'"/>
										<input type="submit" class="btn btn-round btn-success" name="valider" value="Valider"/> 
									</div>
									<label class="control-label col-md-5 col-sm-5 col-xs-12" 
									style="text-align:left;color:red;font-weight:bold;">
									<a href="#" class="btn btn-warning btn-xs"/><i>::: R&eacute;sultat de la statistique d'&eacute;valuation :::</i></a></label><br/>
									<br/><br/><br/>
									<?php TauxReussite($idanneescolaire,$idposition,$idsalle,$etat,$moyenne,$pdo);?>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>