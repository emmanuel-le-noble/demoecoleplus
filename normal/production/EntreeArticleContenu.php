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
						        <h2 style="font-family:comic sans ms;font-weight:bold;">
						        	Entr&eacute;e d'article en stock
						        	<small style="color:#000;font-weight:bold;">
										[ Permet de g&eacute;rer les entr&eacute;e d'article(s) en stock ]
									</small>
								</h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>							    
									<div>
										<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
										Les types d'op&eacute;rations &agrave; effectuer : </span>
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-success" 
										name="Ajouter" value="Ajouter"/>
										&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;"class="btn btn-round btn-primary" 
										name="Modifier" value="Modifier"/>
										&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" 
										name="Supprimer" value="Supprimer" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir Supprimer cette entr&eacute;e ?');"/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</div><br/>
									<?php 
										if($error!="")
										{
											?><div class="alert alert-warning alert-dismissible fade in" role="alert" style="width:100%;padding:5px;"><b><?php echo $error;?>
											</b></div><?php
										}
										elseif($success!="")
										{
											?><div class="alert alert-success alert-dismissible fade in" role="alert" style="width:100%;padding:5px;"><b><?php echo $success;?>
											</b></div><?php
										}
										ListAllEntree($pdo);
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