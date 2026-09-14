<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	Performance par discipline et par niveau
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>							    
									<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
									Veuillez renseigner les information(s)</span></div>
									<hr style="border:1px dotted #000;"/>
									<table width="100%" align="center">
										<tr>
											<td width="25%">
												<div class="form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
													P&eacute;riode
													<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllPositionSelected($idposition,$pdo);?>
													</div>
												</div>
											</td>
											<td width="25%">
												<div class="form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">
													Ann&eacute;e scolaire
													<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
													</div>
												</div>
											</td>
											<td width="25%">
												<div class="form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
													Domaine
													<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllDomaineSelected($iddomaine,$pdo);?>
													</div>
												</div>
											</td>
											<td width="25%">
												<div class="form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">
													Moy. reussite
													<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="moyenne" style="background-color:#A2C600" 
													required="required" value="<?php echo $moyenne;?>" class="form-control col-md-7 col-xs-12">
													</div>
												</div>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>
									<div align="center">
										<input type="submit" class="btn btn-round btn-danger" name="valider" value="Ex&eacute;cuter la requ&ecirc;te"/>
									</div>
									<hr style="border:1px dotted #000;"/>
									<button class="btn btn-round btn-warning">
										R&eacute;sultat de la requête
									</button>
									<br/>
									<?php EvaluationPerformance($idanneescolaire,$idposition,$iddomaine,$moyenne,$pdo);?>
									<hr style="border:1px dotted #000;"/>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>