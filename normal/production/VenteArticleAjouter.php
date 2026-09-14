<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle vente</title>
</head>
    <body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Nouvelle vente
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
							<label class="control-label col-md-6 col-sm-6 col-xs-12" style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
								[ Veuillez renseigner les informations ]
							</label><br/>
							<hr style="border:1px dotted #000;"/>
								<div class="form-horizontal">
									<div class="form-group row">
										<!-- Édité par -->
										<label class="col-md-2 control-label" style="text-align:left;">Effectu&eacute; par <span class="text-danger">*</span></label>
										<div class="col-md-3">
											<input type="text" name="Personnel"  
												   class="form-control" 
												   value="<?php echo $_SESSION['nomuser'].' '.$_SESSION['prenom']; ?>" 
												   readonly>
										</div>
									</div>
									<div class="form-group row">
										<!-- Nom élève -->
										<label class="col-md-2 control-label" style="text-align:left;">Nom & pr&eacute;nom du client </label>
										<div class="col-md-3">
											<?php getAllEleve($_SESSION['idanneescolaire'], $pdo); ?>
										</div>

										<!-- Num Vente -->
										<label class="col-md-2 control-label">Num. Vente <span class="text-danger">*</span></label>
										<div class="col-md-2">
											<input type="text" name="NumSortie" required 
												   class="form-control bg-green" 
												   value="<?php echo getNumArticleSortie($_SESSION['libelleanneescolaire'],$pdo);?>">
										</div>

										<!-- Date Vente -->
										<label class="col-md-1 control-label">Date Vente<span class="text-danger">*</span></label>
										<div class="col-md-2">
											<input type="date" id="DateSortie" name="DateSortie"  
												   class="form-control" required 
												   value="<?php echo $DateDujour;?>">
										</div>
									</div>
									<hr style="border:1px dotted #000;"/>

									<div class="text-center">
										<a href="VenteArticle.php" class="btn btn-round btn-primary">Fermer</a>
										<input type="submit" class="btn btn-round btn-default" name="enregistrerBD" value="Ajouter les articles"> 
									</div>
									
								</div>

					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>