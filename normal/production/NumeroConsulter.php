<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Consulter les num&eacute;ros des &eacute;l&egrave;ves
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div>
							<span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Information(s) ]</span></div><br/>
						    <table width="100%">
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Ann&eacute;e scolaire : <?php echo $LibelleAnneeScolaire;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												P&eacute;riode : <?php echo $LibellePosition;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Classe : <?php echo $CodeSalle;?>
											</label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div><span style="color:#000;font-weight:bold;font-size:16px;">
						    [ Liste des &eacute;l&egrave;ves ]</span></div><br/>
							<div><?php ListEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$pdo);?></div>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Numero.php'"/>
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>