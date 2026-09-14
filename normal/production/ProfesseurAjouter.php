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
						        Cr&eacute;er un professeur
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez renseigner les information(s) pour la cr&eacute;ation du professeur ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nomprof" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="prenomprof" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Contact</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="contact" name="contact" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Titre</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllTitre($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Signature</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class="">
												<img src="images/signatureIni.jpg" alt="" width="200px" height="50px" style="border:0px solid #000;"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Professeur.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerProfesseur" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>