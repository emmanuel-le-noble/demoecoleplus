<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Effectif de la classe
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">
												Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?>
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="hidden" name="idsalle" value="<?php echo $idsalle;?>"/>
												<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
											</div>
										</div>
									</td>
									<td width="50%">
										<div style="text-align:right;">
											<!-- Bouton Imprimer -->
											
											<button type="button" class="btn btn-round btn-info" target="_blank"
												onclick='window.open("ImprimeFicheNotes.php?codesalle=<?php echo $codesalle;?>&idsalle=<?php echo $idsalle;?>&idanneescolaire=<?php echo $_SESSION['idanneescolaire'];?>","", "fullscreen=yes, scrollbars=auto");'>
												<i class="fa fa-print"></i> Imprimer la fiche de notes
											</button>
											&nbsp;&nbsp;
											|
											&nbsp;&nbsp;
											<button type="button" class="btn btn-round btn-primary" target="_blank"
												onclick='window.open("ImprimeFicheClasse.php?codesalle=<?php echo $codesalle;?>&idsalle=<?php echo $idsalle;?>&idanneescolaire=<?php echo $_SESSION['idanneescolaire'];?>","", "fullscreen=yes, scrollbars=auto");'>
												<i class="fa fa-print"></i> Imprimer la fiche de classe
											</button>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">
											    Classe : <?php echo $codesalle;?>
											</label>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><?php EffectifParClasse($idsalle,$idanneescolaire,$pdo);?></td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EffectifClasse.php'"/>
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>