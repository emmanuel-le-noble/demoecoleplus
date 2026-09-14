<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	D&eacute;lib&eacute;ration des r&eacute;sultats
						        	<small style="color:#000;font-weight:bold;">
										[ Permet de g&eacute;rer les d&eacute;lib&eacute;rations des r&eacute;sultats d'une &eacute;valuation ]
									</small>
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
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaire($pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">P&eacute;riode<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllPosition($pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllSalle($pdo);?>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Liste(s) des <span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllEtat($pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Moyenne<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" name="moyenne" style="background-color:#A2C600;" required="required" class="form-control col-md-7 col-xs-12">
													</div>
												</div>
											</td>
											<td>
												<div align="center" class="col-md-8 col-md-offset-3">
													<input type="submit" class="btn btn-round btn-primary" name="valider" value="Afficher le r&eacute;sultat"/> 
												</div>
											</td>
										</tr>
									</table>
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