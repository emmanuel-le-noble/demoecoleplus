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
						        	Analyse des r&eacute;sultats
						        	<small style="color:#000;font-weight:bold;">
										[ Permet d'analyser les r&eacute;sultats par discipline et par classe ]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>									
									<hr style="border:1px dotted #000;"/>
									<table width="100%" align="center">
										<tr>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">P&eacute;riode<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllPosition($pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaire($pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllClasse($pdo);?>
													</div>
												</div>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="submit" class="btn btn-round btn-primary" name="valider" value="Ex&eacute;cuter la requ&ecirc;te"/> 
									</div>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>