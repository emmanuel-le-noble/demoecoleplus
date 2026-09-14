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
						        Ajouter une inscription
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Information de l'&eacute;l&egrave;ve :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Photo
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="file" name="image" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td rowspan="2" valign="top">									
										<div id="image_preview" class="">
											<div style="padding:5px;" class="">
												<img src="images/user.png" alt="" width="80px;" height="80px;" 
												style="border:1px dotted #000;"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="prenom_eleve" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Sexe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" name="sexe">
													<option></option>
													<option>Masculin</option>
													<option>Feminin</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="date_naissance"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Statut</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" name="etat">
													<option></option>
													<option>NA</option>
													<option>NN</option>
													<option>AR</option>
													<option>NR</option>
													<option>AN</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">T&eacute;l. du tuteur
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" data-inputmask="'mask' : '(999) 99999999'" name="teltuteur"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Email. du tuteur
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control"  name="emailtuteur"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom du tuteur
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control"  name="nomtuteur"/>
											</div>
										</div>
									</td>
									<td><div class="form-group"></div></td>
								</tr>
							</table>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Choisissez la Classe :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<?php getAllClasseFiliere($pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Montant inscript.<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12" id="AfficheMontantInscription">
												<input type="text" class="form-control"  name="montantinscript" readonly="yes"/>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Inscription.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="EnregistrerInscription" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>