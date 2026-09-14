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
								Vente(s) d'articles
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Nom & pr&eacute;nom(s) du client : <?php echo  $NomPrenomEleve;?></label>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Num. vente : <span style="color:#000;"><?php echo  $NumSortie;?></span></label>
											<input type="hidden" name="ideleve" required="required" value="<?php echo $ideleve;?>"/>
											<input type="hidden" name="NumSortie" required="required" value="<?php echo $NumSortie;?>"/>
											<input type="hidden" name="DateSortie" required="required" value="<?php echo $DateSortie;?>"/>
											<input type="hidden" name="idArticleSortie" required="required" value="<?php echo $idArticleSortie;?>"/>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Date vente : <?php echo  $DateSortie_;?></label>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align:left;">Vente effectuée par : <?php echo $_SESSION['nomuser'].' '.$_SESSION['prenom'];?></label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;color:#000;margin-bottom:10px;font-size:16px">
								[ Veuillez ajouter les articles &agrave; la liste ]
							</label><br/><br/>
							<table width="100%" style="border-bottom:1px dotted #000;background-color:#DDF2FD">
								<tr>
									<td width="48%">
										<label class="control-label col-md-9 col-sm-9 col-xs-12" style="text-align:left;">
											Article(s) (D&eacute;signation)
										</label>
									</td>
									<td width="16%">
										<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
											Prix Unitaire
										</label>
									</td>
									<td width="16%">
										<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;color:red;">
										Qt&eacute; vendue
										<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
									</td>
									<td width="16%">
										<div align="center" class="col-md-6 col-md-offset-3"></div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
										   <?php getAllArticleVente(8,$pdo);?>
										</div>
									</td>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input type="text" id="PrixUnitaire" name="PrixUnitaire"
										  class="form-control" style="background-color:#eee;" readonly="yes" required="required"/>
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
											<input id="send" type="submit" class="btn btn-round btn-primary" name="enregistrerDetailBD" value="Ajouter &agrave; la liste"/> 
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
									<td colspan="4">
										<?php 
											if($error!="")
											{
												?>
												<div class="alert alert-warning alert-dismissible fade in" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
													<strong style="font-size:16px;"><?php echo $error;?></strong>
												</div><?php
											}
											ListByVente($idArticleSortie,$pdo);
										?>
									</td>
								</tr>
							</table>
							<hr/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<a href="VenteArticle.php">
									<input type="button" class="btn btn-round btn-primary" style="font-weight:bold;" value="Fermer la vente et passer à l'impression du reçu"/>
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