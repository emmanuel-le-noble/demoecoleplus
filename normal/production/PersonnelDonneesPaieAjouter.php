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
						        Ajouter les donn&eacute;es de paie au personnel permanent
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
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom employ&eacute;</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllPersonnel($pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
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
												Salaire base
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="salairebase" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Caisse
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="primecaisse" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Sursalaire
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="sursalaire" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Fonction
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="indemnitefonction" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Sujestion
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="primesujetion" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Interim
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="primeinterim" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Logement
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="indemnitelogement" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Transport
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="indemnitetransport" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PersonnelDonneesPaie.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerPersonnelDonneesPaie" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>