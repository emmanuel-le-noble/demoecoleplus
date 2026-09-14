<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
            <div class="clearfix"></div>
            <div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Ajouter les notes des &eacute;valutations
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms;">
						    [ Pr&eacute;ciser les information(s) sur l'&eacute;valutation ]</span></div><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">&Eacute;valutation<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllPosition($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<?php 
													if($_SESSION['titreprof']==2 OR $_SESSION['titreprof']==3 OR $_SESSION['titreprof']==4)
													{
														getAllSalleProf($_SESSION['iduser'],$idanneescolaire,$statutanneescolaire,$pdo);
													}
													else
													{
														getAllSalle($pdo);
													}
												?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='NotesProfesseur.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="suivant_note" value="Suivant"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>