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
                    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Affecter les salles de classe aux &eacute;l&egrave;ves
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Choisissez la Classe :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<?php getAllClasseFiliere_($pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<div id="affectation"></div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>