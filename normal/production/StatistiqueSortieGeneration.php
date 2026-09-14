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
						        	Statistique sur les sorties
						        	<small style="color:#000;font-weight:bold;">
										[Permet d'avoir les sorties sur une p&eacute;riode]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">						    
								<div class="clearfix"></div>
								<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
								Veuillez renseigner les information(s) pour avoir votre statistique</span></div><br/>
								<table width="100%" align="center">
									<tr>
										<td width="33%">
											<div class="item form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">
													Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
												</label>
												<div class="col-md-7 col-sm-7 col-xs-12">
													<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
												</div>
											</div>
										</td>
										<td width="33%">
											<div class="item form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">
													Compte<span class="required">&nbsp;&nbsp;</span>
												</label>
												<div class="col-md-7 col-sm-7 col-xs-12">
													<?php getAllCompteSelected($idcompte,$pdo);?>
												</div>
											</div>
										</td>
										<td width="33%"></td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">
													Date d&eacute;but<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
												</label>
												<div class='col-sm-6'>
													<div class='input-group date' id='myDatepicker9' class="col-md-6 col-sm-6 col-xs-12">
														<input type='text' class="form-control" name="datedebut" autocomplete="off" required="required" value="<?php echo $_POST["datedebut"];?>"/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">
													Date fin<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
												</label>
												<div class='col-sm-6'>
													<div class='input-group date' id='myDatepicker10' class="col-md-6 col-sm-6 col-xs-12">
														<input type='text' class="form-control" name="datefin" autocomplete="off" required="required" value="<?php echo $_POST["datefin"];?>"/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
										</td>
										<td>
											<div align="center" class="col-md-6 col-md-offset-3">
												<input type="submit" class="btn btn-round btn-success" name="valider" value="Ex&eacute;cuter la requ&ecirc;te"/> 
											</div>
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;"/>
								<table width="100%" align="center">
									<tr>
										<td width="50%">
											<span style="color:#000;font-weight:bold;font-size:16px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
												[ R&eacute;sultat de la statistique des sorties effectu&eacute;es ]
											</span>
										</td>
										<td width="50%">
											<div style="float:right;">
												<a href="#" class="btn btn-warning btn-xs" onclick='window.open("StatistiqueSortieRapport.php?&idanneescolaire=<?php echo $idanneescolaire;?>&idcompte=<?php echo $idcompte;?>&datedebut=<?php echo $_POST["datedebut"];?>&datefin=<?php echo $_POST["datefin"];?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
													::: Cliquer pour imprimer la liste :::
												</a>
											</div>
										</td>
									</tr>
								</table><br/>
								<?php ListeSortie($datedebut,$datefin,$idanneescolaire,$idcompte,$pdo);?>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>