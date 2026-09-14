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
						        	Cloturer une journ&eacute;e
						        </h1>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>
									<span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
									[ Veuillez renseigner les information(s) pour avoir la recette et cloturer la journ&eacute; correspondante ]
									</span><br/><br/>
									<table width="100%" align="center">
										<tr>
											<td width="33%">
												<div class="form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Date debut (journ&eacute;e)<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class='col-sm-6'>
														<div class='input-group date' id='myDatepicker5' class="col-md-6 col-sm-6 col-xs-12">
															<input type='text' class="form-control" name="DateDebut" autocomplete="off" value="<?php echo $DateDebut;?>"/>
															<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
														</div>
													</div>
												</div>
											</td>
											<td width="33%">
												<div class="form-group">
													<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Date fin (journ&eacute;e)<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class='col-sm-6'>
														<div class='input-group date' id='myDatepicker6' class="col-md-6 col-sm-6 col-xs-12">
															<input type='text' class="form-control" name="DateFin" autocomplete="off" value="<?php echo $DateFin;?>"/>
															<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
														</div>
													</div>
												</div>
											</td>
											<td width="33%">
												<div align="center" class="col-md-8 col-md-offset-3">
													<input type="submit" class="btn btn-round btn-primary" name="AfficherRecette" value="Afficher la recette"/>
												</div>
											</td>
										</tr>
									</table><hr style="border:1px dotted #000;"/>
									<div>
										<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;color:#000;font-weight:bold;font-family:comic sans ms;font-size:16px;">
										[ Recette des paiements effectu&eacute;s du : <?php echo $DateDebut;?> au <?php echo $DateFin;?> ]
										</label><br/><br/><br/>
										<?php RecetteJournalierePaiementFrais($DateDebut_,$DateFin_,$_SESSION['idanneescolaire'],$pdo);?>
									</div>
									<hr style="border:1px dotted #000;"/>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="submit" class="btn btn-round btn-danger" name="EnregistrerClotureJournee" value="Cloturer la journ&eacute;e" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir clôturer ces lignes financières ?');"/> 
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