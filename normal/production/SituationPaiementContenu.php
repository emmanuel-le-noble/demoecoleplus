<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold">
						        	Fiche de contrôle des frais de scolarité
						        	<small style="color:#000;font-weight:bold;">
										[Permet d'avoir la situation des paiements des &eacute;l&egrave;ves]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
							<div class="x_content">
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
									<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">
												<h3>Voir fiche de contrôle</h3>
											</a>
										</li>
										<li role="presentation" class="">
											<a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">
												<h3>Taux de recouvrement</h3>
											</a>
										</li>
									</ul>
									<div id="myTabContent" class="tab-content">
										<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
											<div>	
												<span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms"><br/>
												[ Veuillez renseigner les information(s) pour avoir la situation des paiements ]</span><br/><br/>	
												<table width="100%" align="center">
													<tr>
														<td width="33%">
															<div class="form-group">
																<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left">Liste(s)</label>
																<div class="col-md-7 col-sm-7 col-xs-12">
																	<select class="form-control col-md-9 col-xs-12" name="Etat" required="required">
																		<option></option>
																		<option value="1">El&egrave;ve(s) en r&egrave;gle</option>
																		<option value="2">El&egrave;ve(s) non en r&egrave;gle</option>
																	</select>
																</div>
															</div>
														</td>
														<td width="33%">
															<div class="form-group">
																<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left">Frais</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<?php getAllPaiementType($pdo);?>
																</div>
															</div>
														</td>
														<td width="33%"></td>
													</tr>
													<tr>
														<td>
															<div class="item form-group">
																<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left">Ann&eacute;e scolaire</label>
																<div class="col-md-7 col-sm-7 col-xs-12">
																	<?php getAllAnneeScolaire($pdo);?>
																</div>
															</div>
														</td>
														<td>
															<div class="item form-group">
																<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left">Classe</label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<?php getAllClasse($pdo);?>
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
												</table><hr style="border:1px dotted #000;"/>
												<div align="center" class="col-md-6 col-md-offset-3">
													<input type="submit" class="btn btn-round btn-primary" name="AfficherSituation" value="Afficher la situation"/> 
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
											<br/>
											<table width="100%" align="center" style="border-bottom:1px dotted #000;padding:5px;">
												<tr style="margin:10px;">
													<td width="50%">
														<div style="text-align:left;"></div>
													</td>
													<td width="50%">
														<div style="text-align:right;">
															<button type="button" class="btn btn-round btn-warning" target="_blank"
																onclick='window.open("EtatRecouvrementResultatRapport.php?idpaiementtype=1&idanneescolaire=<?php echo $_SESSION['idanneescolaire'];?>","", "fullscreen=yes, scrollbars=auto");'>
																<i class="fa fa-print"></i> Cliquer pour imprimer la liste
															</button>
														</div>
													</td>
												</tr>
											</table>
											<?php 
												$idpaiementtype = 1;
												$paiementtypelibelle = getlibelletypepaiement($idpaiementtype,$pdo);
												EtatRecouvrement($idpaiementtype,$paiementtypelibelle,$_SESSION['libelleanneescolaire'],$_SESSION['idanneescolaire'],$pdo);
											?>
										</div>
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