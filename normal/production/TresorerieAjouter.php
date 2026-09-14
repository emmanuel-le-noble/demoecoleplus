<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Ajouter une nouvelle op&eacute;ration
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
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Type op&eacute;ration</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" required="required" name="TypeOperation" style="font-size:12px;" id="TypeOperation" onchange="makeRequest('SousTypeOperation.php','TypeOperation','SousTypeOperation')">
													<option></option>
													<option value="1">Entr&eacute;e(s)</option>
													<option value="2">Sortie(s)</option>
												</select>
											</div>
										</div>
									</td>
									<td width="50%"></td>
								</tr>
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Sous type op&eacute;ration</label>
											<div class="col-md-6 col-sm-6 col-xs-12" id="SousTypeOperation">
												<?php getSousTypeOperation(3,$pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte op&eacute;ration</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllCompte($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date Op&eacute;ration</label>
											<div class='col-sm-4'>
												<div class='input-group date' id='myDatepicker2' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="DateOperation" autocomplete="off"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td align="left">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Libell&eacute; Op&eacute;ration
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="LibelleOperation" required="required" style="font-size:12px;" class="form-control" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Autoris&eacute; par<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllUser($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
								    <td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Montant (FCFA)<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" required="required" style="font-size:12px;" name="Montant" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pi&egrave;ce justificative</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" required="required" class="form-control" name="image" width="50%"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EntreeSortie.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="EnregistrerOperation" value="Enregistrer"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>