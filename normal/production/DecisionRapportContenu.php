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
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:red;">
						        	Decision(s) Conseil Rapport(s)
						        	<small style="color:#000;font-weight:bold;">
										[Permet de g&eacute;rer tous les rapporrts des d&eacute;cisons prises lors des conseils des professeurs]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div id="">
									<div style="margin-bottom:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-success" name="Ajouter" value="Ajouter"/>
										&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-info" name="Modifier" value="Modifier"/>
										&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="Supprimer" value="Supprimer" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce(s) rapport(s) ?');"/>
									</div>
									<?php 
										if($error!="")
									    {
											?><div class="alert alert-warning alert-dismissible fade in" role="alert" style="width:100%;padding:5px;border:1px solid #ff0000;"><b><?php echo $error;?>
											</b></div><?php
	                                    }
	                                    elseif($success!="")
	                                    {
											?><div class="alert alert-success alert-dismissible fade in" role="alert" style="width:100%;padding:5px;border:1px solid #A2C600;"><b><?php echo $success;?>
											</b></div><?php
	                                    }
										ListDecisionRapport($_SESSION['idanneescolaire'],$pdo);
									?>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>