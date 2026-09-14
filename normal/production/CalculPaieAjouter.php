<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000">
								Calcul de paie
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
						    [ Veuillez renseigner les information(s) pour le traitement de la paie ]</span>
							</div>
							<hr style="border:1px dotted #000;"/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>
											</label>
											<div class='col-sm-6'>
												<div class='input-group date' id='myDatepicker14' class="col-md-6 col-sm-6 col-xs-12">
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
												<div class='input-group date' id='myDatepicker15' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="finmois" autocomplete="off" required="required"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Corps</label>
											<div class="col-md-7 col-sm-7 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" name="corps" style="font-size:12px;" required="required">
													<option></option>
													<option value="1">Temporaire</option>
													<option value="2">Permanent</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='CalculPaie.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="suivantCalculPaiePaie" value="Suivant"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>