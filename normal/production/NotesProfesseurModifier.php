<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div>
                <div class="col-md-9 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier les notes de <font color="red"> <?php echo $CodeMatiere;?> </font>
						    </h1>
							<div class="clearfix"></div>
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
												<b> &Eacute;valuation : </b> <?php echo $LibellePosition;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												<b> Classe : </b> <?php echo $CodeSalle;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												<b> Ann&eacute;e scolaire : </b> <?php echo $LibelleAnneeScolaire;?>
											</label>
										</div>
									</td>
								</tr>
								<tr>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												<b> Mati&egrave;re : </b> <?php echo $CodeMatiere;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												<b> Coef. : </b> <?php echo $Coefficient;?>
											</label>
										</div>
									</td>
									<td width="33%"> 
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
												<b> Prof : </b><?php echo $NomProf;?>
											</label>
										</div>
									</td>
								</tr>
							</table>
							<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
							<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
							<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
							<input type="hidden" class="form-control" readonly="yes" name="nomprofesseur" value="<?php echo $NomProf;?>"/>
							<input type="hidden" class="form-control" readonly="yes" name="idprofesseur" value="<?php echo $IdProf;?>"/>
							<input type="hidden" class="form-control" readonly="yes" name="idmatiere" value="<?php echo $idmatiere;?>"/>
							<input type="hidden" name="libelleposition" value="<?php echo $LibellePosition;?>"/>
							<input type="hidden" name="codesalle" value="<?php echo $CodeSalle;?>"/>
							<input type="hidden" name="libelleanneescolaire" value="<?php echo $LibelleAnneeScolaire;?>"/>
							<input type="hidden" name="action" value="update"/>
							<hr style="border:1px dotted #000;" />
							<div><span style="font-family:comic sans ms;color:#000;font-weight:bold;font-size:16px;">[ La liste des &eacute;l&egrave;ves ]</span></div>
							<br/>
							<div>
							<?php 
							if($idposition>=4)
							{
								echo ListEleveSalle_($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
							}
							else
							{
								echo ListEleveSalle($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
							}
							?>
							</div>
							<hr/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='NotesProfesseur.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="AjouterNoteEleve" value="Enregistrer"/> 
							</div>
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
											<input type="submit" style="width:80px;" class="btn btn-info btn-xs" name="Modifier" value="Consulter" title="Consulter les notes des matières" data-toggle="tooltip" data-placement="top"/>
											&nbsp;
											<?php SyntheseNotesByClasse($idmatiere,$idsalle,$idposition,$idanneescolaire,$pdo);?>
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