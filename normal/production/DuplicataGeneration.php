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
				    <div class="title_right">
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Rechercher...">
								<span class="input-group-btn">
								    <button class="btn btn-default" type="button">Rechercher!</button>
								</span>
							</div>
						</div>
				    </div>
                </div>
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms"><font color="red"><b>G&eacute;n&eacute;ration Bulletins</b></font></h2>
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
							    <div id="Bulletins">							    
									<div class="clearfix"></div>
										<table width="100%">
											<tr>
												<td width="25%"> 
													<div class="item form-group">
														<label class="control-label col-md-12 col-sm-12 col-xs-12" for="nom" style="text-align:left;">
															Bulletins du : <?php echo $LibellePosition;?>
														</label>
													</div>
												</td>
												<td width="25%"> 
													<div class="item form-group">
														<label class="control-label col-md-12 col-sm-12 col-xs-12" for="nom" style="text-align:left;">
															Classe : <?php echo $CodeSalle;?>
														</label>
													</div>
												</td>
												<td width="25%"> 
													<div class="item form-group">
														<label class="control-label col-md-12 col-sm-12 col-xs-12" for="nom" style="text-align:left;">
															Ann&eacute;e scolaire : <?php echo $LibelleAnneeScolaire;?>
														</label>
													</div>
												</td>
												<td width="25%"> 
													<div class="item form-group">
														<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Bulletins.php'"/>
													</div>
												</td>
											</tr>
										</table>
									<hr/>
									<div><?php generation_bulletins($idanneescolaire,$idposition,$idsalle,$pdo);?></div>
									<!-- end project list -->
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
        <!-- /page content -->
    </body>
</html>