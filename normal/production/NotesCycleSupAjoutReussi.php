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
							<div class="alert alert-danger alert-dismissible fade in" role="alert" 
							style="width:100%;padding:5px;border:1px solid #A2C600;">
							<span style="color:#fff;font-weight:bold;font-size:18px;">
								Vos notes sont bien enregistr&eacute;es. Veuillez les consulter avant de continuer.</span>
							</div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div>
								<span style="font-family:comic sans ms;color:#000;font-size:16px;font-weight:bold;">[ Information(s) ]</span></div>
							<br/>
						    <table width="100%">
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Evaluation : <?php echo $libelleposition;?>
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
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Mati&egrave;re : <?php echo $CodeMatiere;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Coef. : <?php echo $Coef;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												Prof : <?php echo $nomprofesseur;?>
											</label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
							<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
							<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
							<div>
							<?php
							if($idposition>=6)
							{
								ListeNotesEleveAfterAdd_($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
							}
							elseif($idposition==4 OR $idposition==5)
							{
								ListeNotesEleveAfterAddCycleSup($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
							}
							else
							{
								ListeNotesEleveAfterAdd($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
							}
							?>
							</div>					
					    </div>
						<hr style="border:1px dotted #000;"/>
						<div align="center" class="col-md-6 col-md-offset-3">
							<input type="submit" class="btn btn-round btn-success" name="suivant_note" value="Ajouter de nouvelle note"/> 
						</div>
                    </div>
                </div>
				<div class="col-md-3 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><span style="font-family:comic sans ms;color:red;font-size:16px;font-weight:bold;">[ Mati&egrave;res Enregistr&eacute;e(s) ]</span></h2>
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
										SyntheseNotesByClasse($idmatiere,$idsalle,$idposition,$idanneescolaire,$pdo);?>
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