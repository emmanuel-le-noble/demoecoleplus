<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Cr&eacute;er une fiche de paie
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
						    ::: Information(s) permettant de g&eacute;n&eacute;rer la paie ::: </span></div><br/>
							<table width="100%">
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire  : <?php echo $libelleanneescolaire;?></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>  : <?php echo $debutmois;?></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Fin <small style="font-size:10px;">P&eacute;riode</small>  : <?php echo $finmois;?></label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
							<div style="margin-bottom:5px;">
							<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
							::: Veuillez choisir l'employ&eacute; et saisir les donn&eacute;es de paie correspondantes ::: </span></div>
							<br/>
							<table width="100%">
								<tr>
									<td colspan="2"> 
										<div class="item form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-12" style="text-align:left;color:red;"> Nom employ&eacute; : </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllProfesseurPersonnel($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire base<span class="required">&nbsp;&nbsp;<font color="red">*</font>
											</span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="salairebase" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
												<input type="hidden" name="idmois" value=""/>
												<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
												<input type="hidden" name="debutmois" value="<?php echo $debutmois;?>"/>
												<input type="hidden" name="finmois" value="<?php echo $finmois;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Anciennet&eacute;
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="anciennete" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Sursalaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="sursalaire" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Caisse
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="primecaisse" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Indem. Logement
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="indemnitelogement" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Astreinte
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="allocationfami" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Transport
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="indemnitetransport" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire brute
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="salairebrute" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Fonction
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="indemnitefonction" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Cnss
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="cnss" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Sujestion
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="primesujetion" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Remboursement
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="remboursement" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Interim
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="primeinterim" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Irpp
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="irpp" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
													Salaire net
												</span>
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="salairenet" class="form-control col-md-7 col-xs-12" style="border-radius:5px;border:1px solid orange;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EditionFichePaie.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="AjouterEditionFichePaie" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
				<div class="col-md-3 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><span style="font-family:comic sans ms;color:red;font-size:10px;">::: Mois de paie enregistr&eacute; :::</span></h2>
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
											<?php ListFichePaieMois($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</div>
									</td>
								</tr>
							</table>				
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>