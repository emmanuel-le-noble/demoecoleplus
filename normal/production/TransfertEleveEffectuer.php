<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Effectuer le transfert des &eacute;l&egrave;ves d'une ann&eacute;e scolaire &agrave; une autre
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div style="border:1px dotted #cfcfcf;padding:10px;border-radius:10px;width:400px;background-color:#eee;">
								<span style="font-family:comic sans ms;font-weight:bold;font-size:16px;">
									[ Veuillez Choisir le niveau/fili&egrave;re ]
								</span>
							</div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Niveau<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<?php getAllClasseFiliere($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div id="afficherlist"></div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>