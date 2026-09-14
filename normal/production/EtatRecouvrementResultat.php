
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	Etat du recouvrement
						        	<small style="color:#000;font-weight:bold;">
										[Permet d'avoir l'&eacute;tat du recouvrement des paiements des &eacute;l&egrave;ves]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>
									<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
									Veuillez renseigner les information(s) pour avoir l'&eacute;tat du recouvrement</span></div><br/>
									<table width="100%" align="center">
										<tr>
											<td width="33%">
												<div class="form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;color:#000;">Type de frais<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllPaiementTypeSelected($idpaiementtype,$pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;color:#000;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div align="center" class="col-md-6 col-md-offset-3">
													<input type="submit" class="btn btn-round btn-success" name="AfficherSituation" value="Afficher l'&eacute;tat du recouvrement"/> 
												</div>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>
									<div style="text-align:left;">
										<h4 style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
											[R&eacute;sultat de l'&eacute;tat du recouvrement]
										</h4>
									</div>
									<table width="100%" align="center">
										<tr>
											<td width="50%">
												<div style="text-align:left;">
													<input type="submit" class="btn btn-round btn-info" name="Detail" value="Voir le d&eacute;tail"/> 
												</div>
											</td>
											<td width="50%">
												<div style="text-align:right;">
													<button class="btn btn-round" target="_blank" onclick='window.open("EtatRecouvrementResultatRapport.php?&idpaiementtype=<?php echo $idpaiementtype;?>&idanneescolaire=<?php echo $idanneescolaire;?>","", "fullscreen=yes, scrollbars=auto");'>
														<i class="fa fa-print"></i>&nbsp;<a style="color:#000;">Cliquer pour imprimer la liste</a>
													</button>
												</div>
											</td>
										</tr>
									</table>
									<?php 
									if($idpaiementtype==3)
									{
										EtatRecouvrementInscription($idpaiementtype,$paiementtypelibelle,$libelleanneescolaire,$idanneescolaire,$pdo);
									}
									else
									{
										EtatRecouvrement($idpaiementtype,$paiementtypelibelle,$libelleanneescolaire,$idanneescolaire,$pdo);
									}
									?>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>