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
						        Cr&eacute;er un nouvel employ&eacute;
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
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Fonction</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllTitre($pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom" class="form-control col-md-7 col-xs-12" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="prenom" class="form-control col-md-7 col-xs-12" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Contact</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="contact" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '(999) 99999999'" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Num CNSS</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="numcnss" class="form-control col-md-7 col-xs-12" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Date d'embauche</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateembauche"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Date Naissance</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="datenaissance"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Lieu naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="lieunaissance" class="form-control col-md-7 col-xs-12" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Personne &agrave; charge</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="perscharge" class="form-control col-md-7 col-xs-12" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">N° compte bancaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="numcomptebancaire" class="form-control col-md-7 col-xs-12" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Banque</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllBanque($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">D&eacute;but contrat</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="debutcontrat" class="form-control" data-inputmask="'mask': '99/99/9999'"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Fin contrat</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="fincontrat"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Signature</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Mode de paiement</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllModePaiement($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class="">
												<img src="images/signatureIni.jpg" alt="" width="200px" height="50px" style="border:0px solid #000;">
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Personnel.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerPersonnel" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>