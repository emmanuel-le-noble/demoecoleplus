<?php
   	$req=(' SELECT  
					distinct
					article.idcategorie AS idcategorie,
					article.id AS id,
					article.nom AS nom,
					article.prixunitaire AS prixunitaire,
					article.qtedispo AS qtedispo
					
			FROM    article
			WHERE
			article.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$idcategorie = $donnees['idcategorie'];
			$nom = $donnees['nom'];
			$prixunitaire = floatval($donnees['prixunitaire']);
			$qtedispo = $donnees['qtedispo'];
        }
    $stmt->closeCursor();
	$stmt=NULL; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un nouvel article</title>
    <style>
        .x_panel {
            
            box-shadow: 8px 8px 0px #aaa;
            padding: 20px;
        }

        h1 {
            font-family: 'Comic Sans MS', cursive;
            font-weight: bold;
			color:#000;
        }

        hr.dotted {
            border: 1px dotted orange;
        }
    </style>
</head>
<body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h1>
						        Modifier un nouvel article
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							::: Veuillez renseigner les information(s) :::</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>	
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Cat&eacute;gorie d'article</label>
											<div class="col-md-7 col-sm-7 col-xs-12">
												<?php getAllArticleCategorieVenteSelected($idcategorie,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">D&eacute;signation</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="nom" value="<?php echo $nom;?>"/>
												<input type="hidden" name="id" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Prix Unitaire (FCFA)</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="number" class="form-control" name="montant" value="<?php echo $prixunitaire;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Quantit&eacute; Initiale</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="number" class="form-control" name="qtedispo" value="<?php echo $qtedispo;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='CreationArticle.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierArticle" value="Enregistrer" onclick=""/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>