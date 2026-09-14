<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="background-color:#E9F2DF;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								C&eacute;er une fiche de paie
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
							::: Veuillez renseigner les information(s) pour cr&eacute;er la fiche de paie :::</span><br/><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scol.<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>
											</label>
											<div class='col-sm-6'>
												<div class='input-group date' id='myDatepicker17' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="debutmois" autocomplete="off" required="required"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Fin <small style="font-size:10px;">(P&eacute;riode)</small>
											</label>
											<div class='col-sm-6'>
												<div class='input-group date' id='myDatepicker18' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="finmois" autocomplete="off" required="required"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EditionFichePaie.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="suivantEditionFichePaie" value="Suivant"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>