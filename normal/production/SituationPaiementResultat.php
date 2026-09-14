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
						        <h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	Fiche de contrôle des frais de scolarité
						        </h1>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>
									<span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
									::: Veuillez renseigner les information(s) pour avoir la situation des paiements :::</span><br/><br/>
									<table width="100%" align="center">
										<tr>
											<td width="33%">
												<div class="form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left">Liste(s)</label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<select class="form-control col-md-9 col-xs-12" name="Etat">
															<option></option>
															<?php 
															if($Etat==1)
															{
																?>
																<option value="1" selected="selected">El&egrave;ve(s) en r&egrave;gle</option>
																<option value="2">El&egrave;ve(s) non en r&egrave;gle</option><?php
															}
															else
															{
																?>
																<option value="1">El&egrave;ve(s) en r&egrave;gle</option>
																<option value="2" selected="selected">El&egrave;ve(s) non en r&egrave;gle</option><?php
															}
															?>
														</select>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left">Frais</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllPaiementTypeSelected($idPaiementType,$pdo);?>
													</div>
												</div>
											</td>
											<td width="33%"></td>
										</tr>
										<tr>
											<td>
												<div class="form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left">Ann&eacute;e scolaire</label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaireSelected($idAnneescolaire,$pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left">Classe</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllClasseSelected($idClasse,$pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12">Montant de la tranche</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<input type="text" name="Montant" class="form-control col-md-7 col-xs-12"/>
													</div>
												</div>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="submit" class="btn btn-round btn-success" name="AfficherSituation" value="Afficher la situation"/> 
									</div>
									<table width="100%" align="center">
										<tr>
											<td width="50%">
												<span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
													[ R&eacute;sultat de la situation des paiements ]
												</span>
											</td>
											<td width="50%"></td>
										</tr>
									</table><br/>
									<?php ReclamationPaiement($Etat,$idPaiementType,$idAnneescolaire,$idClasse,$Montant,$pdo);?>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>