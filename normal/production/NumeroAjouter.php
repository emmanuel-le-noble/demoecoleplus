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
								Créer de nouveaux num&eacute;ros
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;">
						    [ Veuillez renseigner les information(s) ]</span></div><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Evaluation<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllPosition($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllSalle($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div><span style="color:#000;font-weight:bold;font-size:16px;">
						    [ Liste des &eacute;l&egrave;ves ]</span></div><br/>
							<div id="ListEleve"></div>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Numero.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="CreerNumero" value="Générer"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>