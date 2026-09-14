<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="x_panel" style="background-color:#E9F2DF;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Ajouter les absences par mati&egrave;re
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
							Information(s) s&eacute;lectionn&eacute;e(s)</span></div><br/>
						    <table width="100%">
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">P&eacute;riode : <?php echo $libelleposition;?></label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Classe : <?php echo $codesalle;?></label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?></label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/>
							<div><span style="color:#000;font-weight:bold;font-size:14px;font-family:comic sans ms">
							Choisissez la mati&egrave;re auxquelle vous voulez enregistrer les absences et saisir la date de l'op&eacute;ration</span></div><br/>
							<table width="100%" style="margin-bottom:5px;">
								<tr>
									<td width="33%"> 
										<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Mati&egrave;re<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
										<div class="col-md-8 col-sm-8 col-xs-12">
											<?php 
												if($_SESSION['titreprof']==2 OR $_SESSION['titreprof']==3 OR $_SESSION['titreprof']==4)
												{
												    getAllMatiereProf($idsalle,$idanneescolaire,$statutanneescolaire,$idposition,$_SESSION['iduser'],$pdo);
												}
												else
												{
													getAllMatiere($idsalle,$idanneescolaire,$idposition,$statutanneescolaire,$pdo);
												}
											?>
										</div>
									</td>
									<td width="33%">
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Date absence</label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<input type="text" class="form-control" required="required" data-inputmask="'mask': '99/99/9999'" style="font-size:12px;" name="dateabsences" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td width="33%"></td>
								</tr>
							</table>
							<div id="resultat"></div>					
					    </div>
                    </div>
                </div>
				<div class="col-md-3 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><span style="font-family:comic sans ms;color:red;font-size:10px;">::: Mati&egrave;res Enregistr&eacute;e(s) :::</span></h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <table width="100%">
								<tr>
									<td> 
										<div class="form-group">
											<input type="submit" style="width:80px;" class="btn btn-info btn-xs" name="Modifier" value="Consulter"/>
											&nbsp;
											<?php 
												if($_SESSION['titreprof']==2 OR $_SESSION['titreprof']==3 OR $_SESSION['titreprof']==4)
												{
													AbsencesMatiereByProf($idsalle,$idposition,$idanneescolaire,$statutanneescolaire,$_SESSION['iduser'],$pdo);
												}
												else
												{
													AbsencesMatiere($idsalle,$idposition,$idanneescolaire,$statutanneescolaire,$pdo);
												}
											?>
										</div>
									</td>
								</tr>
							</table>				
					    </div>
                    </div>
                </div>
				<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
				<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
				<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
				<input type="hidden" name="libelleposition" value="<?php echo $libelleposition;?>"/>
				<input type="hidden" name="codesalle" value="<?php echo $codesalle;?>"/>
				<input type="hidden" name="libelleanneescolaire" value="<?php echo $libelleanneescolaire;?>"/>
				<input type="hidden" name="action" value="add"/>		
            </div>
        </div>
    </div>
    </body>
</html>