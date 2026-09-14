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
						    <div style="margin:10px;"><span style="color:red;"><i>::: Date de l'inscription:::</i></span></div>
							<div class="item form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Date d'inscrit<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" style="background-color:#A2C600;" name="dateinscrit" required="required"/>
								</div>
							</div><hr/>
							<div style="margin:10px;"><span style="color:red;"><i>::: Information de l'&eacute;l&egrave;ve :::</i></span></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Photo<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" name="image" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>									
										<div id="image_preview" class="">
											<div class="">
												<img src="images/user.png" alt="" width="150px;" height="150px;" style="border:1px solid #000;">
												<div class="caption">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Photo membre</b>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="nom_eleve" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pr&eacute;nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="prenom_eleve" name="prenom_eleve" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date naissance<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="date_naissance"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;" valign="top">Sexe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:10px;">
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
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Lieu Naissance<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="lieu_naissance" name="lieu_naissance" required="required" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">T&eacute;l&eacute;phone<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" data-inputmask="'mask' : '(999) 999-9999'" name="telephone" required="required"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nationalit&eacute;<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllNationalite($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Adresse<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" name="adresse" required="required"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12"  style="text-align:left;">Commentaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<textarea id="commentaire" required="required" name="commentaire" class="form-control col-md-7 col-xs-12" placeHolder="Commentaire sur le dossier de l'inscription"></textarea>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pers_a_prevenir" style="text-align:left;">Pers. &agrave; pr&eacute;venir<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<textarea id="pers_a_prevenir" required="required" name="pers_a_prevenir" class="form-control col-md-7 col-xs-12" placeHolder="Personne &agrave; prevenir"></textarea>
											</div>
										</div>
									</td>
								</tr>
							</table><hr/>
							<div style="margin:10px;"><span style="color:red;"><i>:::Choisissez la fili&egrave;re/Classe et le Droit d'inscription:::</i></span></div>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="name" style="text-align:left;">Fili&egrave;re/Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllClasseFiliere($pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">									
										<div id="idresultat">
											<div class="item form-group">
												<label class="control-label col-md-4 col-sm-4 col-xs-12" for="name" style="text-align:left;">Droit d'inscription<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" readonly="yes" name="montantdroit" class="form-control"/>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						<!-- end project list -->
					    </div>
                    </div>
                </div>
				<div align="center" class="col-md-6 col-md-offset-3">
					<input type="button" class="btn btn-primary" value="Fermer" onclick="document.location='InscriptionEleve.php'"/>
					<input id="send" type="submit" class="btn btn-success" name="enregistrer_inscription" value="Enregistrer"/> 
				</div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    </body>
</html>