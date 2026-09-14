<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
            <div class="page-title">
				<div class="title_left">
					<h2>Finance - Pr&ecirc;ts</h2>
				</div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><font color="red">Ajouter un Pr&ecirc;t</font></h2>
							<ul class="nav navbar-right panel_toolbox">
							    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
									<ul class="dropdown-menu" role="menu">
									    <li><a href="#">Settings 1</a></li>
									    <li><a href="#">Settings 2</a></li>
									</ul>
							    </li>
							    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<!-- start project list -->
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nom" style="text-align:left;">Personnel<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php 
													getAllProfesseur($pdo);
												?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cout_beneficiaire" style="text-align:left;">Montant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" data-inputmask="'mask' : '999999'" name="montantpret"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="date_debut" style="text-align:left;">Date<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="datepret"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cout_beneficiaire" style="text-align:left;">D&eacute;but rembours.<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateremboursement"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="date_debut" style="text-align:left;">Fin rembours.<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="finremboursement"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cout_beneficiaire" style="text-align:left;">D&eacute;falquer sur <span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllModePaiement($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>							
						<!-- end project list -->
					    </div>
                    </div>
                </div>
				<div align="center" class="col-md-6 col-md-offset-3">
					<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Prets.php'"/>
					<input id="send" type="submit" class="btn btn-round btn-success" name="enregistrer_pret" value="Enregistrer"/> 
				</div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    </body>
</html>