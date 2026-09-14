<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	&Eacute;valuation(s) Mati&egrave;re
						        	<small style="color:#000;font-weight:bold;">
										[Permet de g&eacute;rer les &eacute;valuations des mati&egrave;res]
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
											<td width="25%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">P&eacute;riode<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllPosition($pdo);?>
													</div>
												</div>
											</td>
											<td width="30%">
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaire($pdo);?>
													</div>
												</div>
											</td>
											<td width="25%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllSalle($pdo);?>
													</div>
												</div>
											</td>
											<td width="25%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Mati&egrave;re<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllMatiere_(1,$pdo);?>
													</div>
												</div>
											</td>
										</tr>
									</table><hr style="border:1px dotted #000;"/>
									<div align="center" class="col-md-6 col-md-offset-3">
									<input type="submit" class="btn btn-round btn-primary" name="valider" 
									value="Ex&eacute;cuter la requ&ecirc;te"/> 
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