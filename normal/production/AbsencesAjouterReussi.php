<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<div class="message"><h2><span style="font-family:comic sans ms;color:red;font-weight:bold;">Enregistrement r&eacute;ussi!!!</span></h2></div>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
							::: Information(s) avec lesquelles les absences sont enregistr&eacute;es :::</span></div>
						    <hr style="border:1px dotted #000;"/>
							<table width="100%">
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Période : <?php echo $libelleposition;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Classe : <?php echo $codesalle;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?>
											</label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
							<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
							<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
							<div><?php ListeAbsencesEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$pdo);?></div>					
					    </div>
						<div align="center" class="col-md-6 col-md-offset-3">
							<input type="submit" class="btn btn-round btn-success" name="Ajouter" value="Ajouter de nouvelle(s) ligne(s) d'absences"/> 
						</div>
                    </div>
                </div>
				
				<div class="col-md-3 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><span style="font-family:comic sans ms;color:red;font-size:10px;">::: Absences enregistr&eacute;e(s) :::</span></h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <table width="100%">
								<tr>
									<td> 
										<div class="item form-group">
										<input type="submit" style="width:80px;" class="btn btn-info btn-xs" name="Modifier" value="Consulter"/>
										&nbsp;
										<?php 
										ListeAbsencesParSalle($idsalle,$pdo);?>
										</div>
									</td>
								</tr>
							</table>				
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>