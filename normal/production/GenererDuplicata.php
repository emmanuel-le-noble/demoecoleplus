<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
            <div class="page-title">
				<div class="title_left">
					<h2 style="font-family:comic sans ms">Evaluation - Duplicata</h2>
				</div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms"><font color="red">G&eacute;n&eacute;rer les Duplicata</font></h2>
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
						    <div><span style="font-family:comic sans ms;color:#000;font-weight:bold;">::: Information(s) sur la classe :::</span></div><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="nom" style="text-align:right;">Trimestre<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllPosition($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="nom" style="text-align:right;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<?php getAllSalle($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="nom" style="text-align:right;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
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
					<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Duplicata.php'"/>
					<input id="send" type="submit" class="btn btn-round btn-success" name="genererDuplicatas" value="Suivant"/> 
				</div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    </body>
</html>