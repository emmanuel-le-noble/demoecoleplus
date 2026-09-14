<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">				
				<div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
						 <div class="x_title">
							<div class="alert alert-danger alert-dismissible fade in" role="alert" 
							style="width:100%;padding:5px;border:1px solid #A2C600;">
							<span style="color:#fff;font-weight:bold;font-size:18px;">
								Calcul de la paie effectu&eacute; avec succ&egrave;s !!!. Veuillez la consulter avant de continuer.</span>
							</div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
						    [ Information(s) permettant de g&eacute;n&eacute;rer la paie ]</span></div><br/>
							<table width="100%">
								<tr>
									<td> 
										<div class="form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire  : <?php echo $libelleanneescolaire;?></label>
											<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
											<input type="hidden" name="idmois" value="<?php echo $idmois;?>"/>
											<input type="hidden" name="corps" value="<?php echo $corps;?>"/>
										</div>
									</td>
									<td></td>
									<td><button class="btn btn-round btn-warning" onclick='window.print();'><i class="fa fa-print"></i>&nbsp;Imprimer la fiche</button></td>
								</tr>
								<tr>
									<td> 
										<div class="form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>  : <?php echo $debutmois;?></label>
										</div>
									</td>
									<td> 
										<div class="form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Fin <small style="font-size:10px;">(P&eacute;riode)</small>  : <?php echo $finmois;?></label>
										</div>
									</td>
									<td> 
										<div class="form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Corps : <?php echo $corpslibelle;?></label>
										</div>
									</td>
								</tr>
							</table>
							<?php 
							if($corps==1)
							{
								?>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
								[ Nombres d'heures effectu&eacute;es ]</span></div>
								<br/>
								<table width="100%">
									<tr>
										<td> 
											<?php ApprouverPaieCorpsProfessorat($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div align="center" class="col-md-6 col-md-offset-3">
									<input type="submit" class="btn btn-round btn-primary" name="Ajouter" value="Fermer"/> 
								</div><?php
							}
							else
							{
								?>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
								[ Donn&eacute;es de paie ]</span></div>
								<br/>
								<table width="100%">
									<tr>
										<td> 
											<?php ApprouverPaieCorpsAdministratif($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div align="center" class="col-md-6 col-md-offset-3">
									<input type="submit" class="btn btn-round btn-primary" name="Ajouter" value="Fermer"/> 
								</div><?php
							}
							?>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>