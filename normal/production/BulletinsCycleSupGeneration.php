<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						    <h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
					        	Bulletins de notes pour le cycle sup&eacute;rieur g&eacute;n&eacute;r&eacute;s 
					        </h1>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>							    
									<div class="clearfix"></div>
										<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms;">
						    			[ Information(s) sur la g&eacute;n&eacute;ration des bulletins ]</span></div><br/>
										<table width="100%">
											<tr>
												<td width="25%"> 
													<div class="item form-group">
														<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
															Bulletins du : <?php echo $LibellePosition;?>
														</label>
													</div>
												</td>
												<td width="25%"> 
													<div class="item form-group">
														<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
															Classe : <?php echo $CodeSalle;?>
														</label>
													</div>
												</td>
												<td width="25%"> 
													<div class="item form-group">
														<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
															Ann&eacute;e scolaire : <?php echo $LibelleAnneeScolaire;?>
														</label>
													</div>
												</td>
											</tr>
										</table><hr style="border:1px dotted #000;"/>
										<div><span style="font-family:comic sans ms;color:red;font-weight:bold;font-size:16px;">
						    			[ Liste des bulletins du <?php echo $LibellePosition;?> g&eacute;n&eacute;r&eacute;s ]</span></div><br/>
										<?php generation_bulletins_CycleSup($idanneescolaire,$idposition,$idsalle,$pdo);?>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>