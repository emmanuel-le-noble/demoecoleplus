<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Ajouter un paiement
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez renseigner les information(s) pour enregistrer l'op&eacute;ration ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>
									<td>										
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Date paiement<span class="required">&nbsp;&nbsp;</span>
											</label>
											<div class='col-sm-6'>
												<div class='input-group date' id='myDatepicker13' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="datepaiement" autocomplete="off" required="required" value="<?php echo $toDay;?>"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom & pr&eacute;nom &eacute;l&egrave;ve</label>
											<div class="col-md-8 col-sm-8 col-xs-12" id="paiementfraislisteeleve">
												<?php getAllEleve($_SESSION['idanneescolaire'],$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom payeur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" name="nompayeur"/>
											</div>
										</div>
									</td>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">T&eacute;l payeur</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="number" class="form-control" name="tel"/>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted orange;"/>
                            <div id="paiementfraiseleve"></div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>