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
						        Ajouter les donn&eacute;es de paie de l'enseignant
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<br/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom de l'enseignant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllProfesseur($pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Cout Honoraire (FCFA)<span class="required">&nbsp;&nbsp;<font color="red">*</font>
											</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" name="cout_honoraire" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='ProfesseurDonneesPaie.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerProfesseurDonneesPaie" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>