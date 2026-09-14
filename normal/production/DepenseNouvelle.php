<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Ajouter une D&eacute;pense</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td align="left">
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Objet d&eacute;pense
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<textarea name="objet" required="required" class="form-control col-md-7 col-xs-12"></textarea>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Montant<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" data-inputmask="'mask':'999999'" name="montant"/></span>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date d&eacute;pense</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="datedepense"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">D&eacute;falquer sur <span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllModePaiement($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Autoris&eacute; par<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllUser($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Depense.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="EnregistrerDepense" 
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