<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Ajouter un versement</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Veuillez renseigner les informations du versement :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Num. bordereau<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" name="NumBordereau" required="required"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllModePaiement($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date versement
											</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateversement" required="required"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Vers&eacute; par
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllUser($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Liste des paiements effectu&eacute;s non vers&eacute;s :::</i></label><br/><br/>
                            <?php ListePaiementFraisNonVerse($pdo);?>
                            <hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='versement.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="EnregistrerVersement" 
								value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>