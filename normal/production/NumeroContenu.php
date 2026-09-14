<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold">
						        	N° table(s) et anonymat(s)
						        	<small style="color:#000;font-weight:bold;">
										[Permet de générer et d'historiser les numéros de tables & anonymats des &eacute;l&egrave;ves]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
							<div class="x_content">
							    <div id="Note">									
									<div style="margin-bottom:25px;"><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
										Les types d'op&eacute;rations &agrave; effectuer : </span>
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-success" name="Creer" value="Créer"/>
										&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-info" name="Consulter" value="Consulter"/>
										&nbsp;
										<input type="submit" style="width:90px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-danger" name="Supprimer" value="Supprimer" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer cette tranche de g&eacute;n&eacute;ration de num&eacute;ros ?');"/>
										&nbsp;
									</div>									
									<?php ListNumeroEvaluation($pdo);?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>