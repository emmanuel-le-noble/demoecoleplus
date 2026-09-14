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
							<h1 style="font-family:comic sans ms;font-weight: bold;color:#000;">
								Nouvelle sortie d'articles en stock
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
							<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;color:#000;margin-bottom:10px;font-size:16px;">
								[ Veuillez renseigner les informations ]
							</label><br/>
							<hr style="border:1px dotted #000;"/>
							<table width="100%">
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Motif de sortie
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-7 col-sm-7 col-xs-6">
											  <input type="text" name="MotifSortie" required="required" class="form-control"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Num. sortie
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-6">
											  <input type="text" name="NumSortie" required="required"  
											  class="form-control" value="SORTIE/<?php echo getNumArticleEntree($pdo);?>" 
											  style="background-color:#A2c600;"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"
											style="text-align:left;">Date sortie
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-5 col-sm-5 col-xs-12">
											  <input type="date" id="DateSortie" name="DateSortie"  
											  class="form-control" required="required" value="<?php echo $DateDujour;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<a href="EntreeArticle.php">
									<input type="button" class="btn btn-round btn-primary" value="Fermer"/>
								</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input id="send" type="submit" class="btn btn-round btn-success" name="enregistrerBD" value="Ajouter les articles"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>