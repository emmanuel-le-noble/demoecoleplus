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
						        Cr&eacute;er le coeffcient de la mati&egrave;re
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez renseigner les information(s) ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>	
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Code Mati&egrave;re</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
											    <?php getAllMatiere_(1,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Code Classe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllClasse($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>	
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Coefficient</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
											    <input type="text" name="coefficient" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Etat</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getEtatMatiere();?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='MatiereCoefficient.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="EnregistrerMatiereCoefficient" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>