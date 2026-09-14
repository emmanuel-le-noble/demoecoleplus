<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Importer une liste de classe
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<?php 
								if($error!="")
							    {
									?>
									<div class="alert alert-warning alert-dismissible fade in" role="alert" style="width:100%;padding:5px;">
										<b><?php echo $error;?></b>
									</div><?php
	                            }
	                            elseif($success!="")
							    {
									?>
									<div class="alert alert-success alert-dismissible fade in" role="alert" style="width:100%;padding:5px;">
										<b><?php echo $success;?></b>
									</div><?php
	                            }
							?>
							<br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Choisissez la classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllSalle($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">
											Fichier (csv) &agrave; importer
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="file" name="userfile" class="form-control col-md-7 col-xs-12" required="required"/>
											</div>
										</div>
									</td>
									<td>
										<div align="left">
										<input type="submit" class="btn btn-round btn-primary" name="ValiderImport" value="Enregistrer"/>
										</div>
									</td>
								</tr>
							</table><hr/>
							<div style="font-size:16px;text-align:center;">
								<a href="images/ModeleFichier.xlsx" class="btn btn-warning btn-xs"/>
							        Veuillez t&eacute;l&eacute;charger le mod&egrave;le du fichier d'import.
							    </a>
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>