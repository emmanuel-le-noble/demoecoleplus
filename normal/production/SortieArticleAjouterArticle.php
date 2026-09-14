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
								Sortie des articles(s) en stock
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
							<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;color:#000;margin-bottom:10px;font-size:16px;">
								[ Informations sur la sortie ]
							</label><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Motif de sortie : <?php echo  $MotifSortie;?></label>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Num. sortie : <?php echo  $NumSortie;?></label>
											<input type="hidden" name="MotifSortie" required="required" value="<?php echo $MotifSortie;?>"/>
											<input type="hidden" name="NumSortie" required="required" value="<?php echo $NumSortie;?>"/>
											<input type="hidden" name="DateSortie" required="required" value="<?php echo $DateSortie_;?>"/>
											<input type="hidden" name="idArticleSortie" value="<?php echo $idArticleSortie;?>"/>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Date sortie : <?php echo  $DateSortie_;?></label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<label class="control-label col-md-6 col-sm-6 col-xs-12" style="font-size:12px;text-align:left;color:#000;margin-bottom:10px;">
								[ Veuillez ajouter les articles &agrave; la liste ]
							</label><br/><br/>
							<table width="100%" style="border-bottom:1px dotted #000;background-color:#DDF2FD">
								<tr>
									<td width="32%">
										<label class="control-label col-md-9 col-sm-9 col-xs-12" style="text-align:left;">
											Article(s) (D&eacute;signation)
										</label>
									</td>
									<td width="16%">
										<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
											Qt&eacute; (Dispo) 
										</label>
									</td>
									<td width="16%">
										<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
											PU
										</label>
									</td>
									<td width="16%">
										<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;color:red;">
										Qt&eacute; Sortie.
										<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
									</td>
									<td width="16%">
										<div align="center" class="col-md-6 col-md-offset-3"></div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
										   <?php getAllArticleEntree($pdo);?>
										</div>
									</td>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
										  <input type="hidde" id="QteDispo" name="QteDispo"
										  class="form-control" style="background-color:#eee;" readonly="yes" required="required"/>
										</div>  
									</td>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="text" id="PrixUnitaire" name="PrixUnitaire"
										  class="form-control" style="background-color:#eee;" readonly="yes"/>
										</div>
									</td>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
										  <input type="number" id="Qte" name="Qte" 
										  class="form-control" style="background-color:#99CC00;border:dotted"/>
										</div>
									</td>
									<td>
										<div align="center" class="col-md-6 col-md-offset-3">
											<input id="send" type="submit" class="btn btn-round btn-warning" name="enregistrerDetailBD" value="Ajouter &agrave; la liste"/> 
										</div>
									</td>
								</tr>
								<tr>
								   <td style="height:10px;"></td>
								</tr>
							</table>
							<br/>
							<table width="100%" style="border-bottom: 1px dotted #000;">
								<tr>
									<td>
										<div>
											<label class="control-label col-md-6 col-sm-6 col-xs-12" style="font-size:16px;text-align:left;color:#000;margin-bottom:10px;">
												[ Liste des articles sortie ]
											</label><br/><br/>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="4">
										<?php 
											if($error!="")
											{
												?>
												<div class="alert alert-warning alert-dismissible fade in" role="alert" style="width:100%;padding:5px;">
													<b><?php echo $error;?></b>
												</div><?php
											}
											ListBySortie($idArticleSortie,$pdo);
										?>
									</td>
								</tr>
							</table>
							<hr/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<a href="SortieArticle.php">
									<input type="button" class="btn btn-round btn-primary" value="Fermer"/>
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