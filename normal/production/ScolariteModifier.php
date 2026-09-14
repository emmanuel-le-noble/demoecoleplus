<?php
   	$req=(' SELECT  
					distinct
					paiementtypeclasse.id as id,
					paiementtypeclasse.idpaiementtype as idpaiementtype,
					paiementtypeclasse.montant as montant,
					paiementtypeclasse.idanneescolaire as idanneescolaire,
					paiementtypeclasse.idclasse as idclasse

			FROM    paiementtypeclasse
			WHERE
			paiementtypeclasse.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$montant = floatval(trim($donnees['montant']));
			$idpaiementtype = $donnees['idpaiementtype'];
			$idanneescolaire = $donnees['idanneescolaire'];
			$idclasse = $donnees['idclasse'];
        }
    $stmt->closeCursor();
	$stmt=NULL; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un frais de scolarité</title>
    <style>
        .form-control-grand {
            height: 46px;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
        }

        label {
            font-weight: bold;
            font-size: 14px;
        }

        .x_panel {
            
            box-shadow: 8px 8px 0px #aaa;
            padding: 20px;
        }

        h2 {
            font-family: 'Comic Sans MS', cursive;
            font-weight: bold;
        }

        .info-text {
            color: #000;
            font-weight: bold;
            font-size: 16px;
            font-family: 'Comic Sans MS', cursive;
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
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier le param&egrave;tre frais de scolarit&eacute;
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllClasseSelected($idclasse,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Frais<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getTypePaiementSelected($idpaiementtype,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Montant</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="number" class="form-control form-control-grand" name="montant" value="<?php echo $montant;?>"/>
												<input type="hidden" name="id" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<div class="row">
                                <div class="col-md-12">
                                    <?php ListTrancheSelected($id,$pdo);?>
                                </div>
                            </div>
							<hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Scolarite.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierScolarite" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>