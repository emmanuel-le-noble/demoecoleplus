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
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Ajouter un nouveau rapport
								<small style="color:#000;font-weight:bold;">
									[Veuillez renseigner les informations pour enregistrer le rapport]
								</small>
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;color:#000;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;color:#000;">Trimestre<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllPosition($pdo);?>
											</div>
										</div>
									</td>
									<td>
									    <div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;color:#000;">Date conseil<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateconseil" required="required"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;color:#000;">Contenu<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											    <textarea class="form-control" name="contenurapport" required="required"></textarea>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;color:#000;">Fichier attach&eacute;<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="file" class="form-control" name="image"/>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='DecisionRapport.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerDecision" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>