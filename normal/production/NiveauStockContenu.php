<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;">
						        	Niveau de stock des articles
						        	<small style="color:#000;font-weight:bold;">
										[ Permet d'avoir le niveau de stock d'article(s) ]
									</small>
								</h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
								<table width="100%">
									<tr>
										<td style="border-bottom:1px dotted #000;background-color:#DDF2FD">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;font-weight:bold;">Veuillez choisir la catégorie d'article(s)</label>
												<div class="col-md-8 col-sm-8 col-xs-12">
													<?php getAllArticleCategorieVente_($pdo);?>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div id="resultat">
												<?php 
													NiveauStockToAllArticle($pdo);
												?>
											</div>
										</td>
									</tr>
								</table>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>