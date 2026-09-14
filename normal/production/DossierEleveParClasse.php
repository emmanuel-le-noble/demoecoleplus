<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	Voir la liste des &eacute;l&egrave;ves par classe
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>	
								<label class="control-label col-md-8 col-sm-8 col-xs-12" 
									style="text-align:left;font-weight:bold;color:red;font-family:comic sans ms;font-size:14px;">
									<i> Veuillez renseigner les information(s) pour avoir la liste des &eacute;l&egrave;ves par classe</i></label><br/><br/><br/>		
									<table width="100%" align="center">
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaire($pdo);?>
													</div>
												</div>
											</td>
											<td width="25%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left">Niveau<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllClasse($pdo);?>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllSalle_3($pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left">Genre</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<select class="form-control col-md-7 col-xs-12" name="sexe">
															<option></option>
															<option>Masculin</option>
															<option>Feminin</option>
														</select>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left">Classe Statut</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getEleveStatutClasse_2($pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left">Etabliss. Statut</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getEleveStatutEtablissement_2($pdo);?>
													</div>
												</div>
											</td>
										</tr>
									</table><hr style="border:1px dotted #000;"/>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='DossierEleve.php'"/>
										<input type="submit" class="btn btn-round btn-success" name="AfficherListeParClasse" value="Afficher la liste"/> 
									</div>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>