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
						        <h2><font color="red">Pr&ecirc;ts &nbsp;&nbsp;&nbsp;<small style="color:#000;">[Permet de g&eacute;rer tous les Pr&ecirc;ts du personnel]</small></font></h2>
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
							    <div id="Note">							    
									<div>
										<input type="submit" style="width:100px;" class="btn btn-success btn-xs" name="ajouterpret" value="Ajouter" title="Ajouter un Pr&ecirc;t" data-toggle="tooltip" data-placement="top"/>
										&nbsp;
										<input type="submit" style="width:100px;" class="btn btn-danger btn-xs" name="supprimerpret" value="Annuler" title="Annuler un Pr&ecirc;t" data-toggle="tooltip" data-placement="top"/>
										&nbsp;
									</div><hr/>
									<?php ListePret($pdo);?>
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