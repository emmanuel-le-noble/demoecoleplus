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
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	Statistique sur les entr&eacute;es et sorties
						        	<small style="color:#000;font-weight:bold;">
										[Permet d'avoir les entr&eacute;es et sorties effectu&eacute;es sur une p&eacute;riode]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
							<div class="x_content">									
								<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
								[ Veuillez renseigner les information(s) ]</span></div><br/>
								<hr style="border:1px dotted #000;"/>
								<table width="100%" align="center">
									<tr>
										<td width="33%">
											<div class="item form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">
													Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
												</label>
												<div class="col-md-7 col-sm-7 col-xs-12">
													<?php getAllAnneeScolaire($pdo);?>
												</div>
											</div>
										</td>
										<td width="33%">
											<div class="item form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">
													Compte<span class="required">&nbsp;&nbsp;</span>
												</label>
												<div class="col-md-7 col-sm-7 col-xs-12">
													<?php getAllCompte($pdo);?>
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
													<input type='date' class="form-control" name="datedebut" autocomplete="off" required="required"/>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group">
												<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:right;">
													Date fin<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
												</label>
												<div class='col-sm-6'>
													<input type='date' class="form-control" name="datefin" autocomplete="off" required="required"/>
												</div>
											</div>
										</td>
										<td>
											<div align="center" class="col-md-6 col-md-offset-3">
												<input type="submit" class="btn btn-round btn-danger" name="valider" value="Ex&eacute;cuter la requ&ecirc;te"/> 
											</div>
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;"/>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>