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
						        Ajouter un domaine
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Boîte postale<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="bp" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Logo<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Contact<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="tel" required="required" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '(999) 99999999'"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class=""></div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Domaine.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerDomaine" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>