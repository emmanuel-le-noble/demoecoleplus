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
						        	Statistique d'&eacute;valuation
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
								<div>									
									<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
										Veuillez renseigner les information(s) pour avoir votre statistique</span></div>
									<hr style="border:1px dotted #000;"/>
									<table width="100%" align="center">
										<tr>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-7 col-sm-7 col-xs-12">
														<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">P&eacute;riode<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllPositionSelected($idposition,$pdo);?>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllSalleSelected($idsalle,$pdo);?>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Liste(s) des<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-8 col-sm-8 col-xs-12">
														<?php getAllEtatSelected($idetat,$pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Moyenne<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-4 col-sm-4 col-xs-12">
														<input type="text" name="moyenne" style="background-color:#A2C600;" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $moyenne;?>"/>
													</div>
												</div>
											</td>
											<td align="center">
												<div align="center" class="col-md-8 col-md-offset-3">
													&nbsp;&nbsp;&nbsp;&nbsp;
													<input type="submit" class="btn btn-round btn-danger" name="valider" value="Afficher le r&eacute;sultat"/> 
												</div>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>	
									<div>
										<span style="color:#000;font-weight:bold;font-size:16px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
											[&nbsp;R&eacute;sultat de la statistique d'&eacute;valuation&nbsp;]
										</span>
									</div><br/>	
									<?php 
										if($etat==1 OR $etat==2)
										{
											TauxReussite($idanneescolaire,$idposition,$idsalle,$idetat,$libelleetat,$moyenne,"",$pdo);
										}
										else
										{
											if($idposition==1 OR $idposition==2 OR $idposition==3)
											{
												
												EvaluationAnnuelleCollege($idanneescolaire,$idposition,$idsalle,$pdo);
											}
											else
											{
												EvaluationAnnuelleLycee($idanneescolaire,$idposition,$idsalle,$pdo);
											}
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