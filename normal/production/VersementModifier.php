<?php
   	$req=(' SELECT  
					versement.idcompte as idcompte,
					versement.dateversement as dateversement,
					versement.num as num,
					versement.iduserauto as iduserauto

			FROM versement
			WHERE
			versement.statut=1
			AND
			versement.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$idversement,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$num = $donnees['num'];
			$iduserauto = $donnees['iduserauto'];
			$idcompte = $donnees['idcompte'];
			$tab = explode('-',$donnees['dateversement']);
			$dateversement = $tab[2].'/'.$tab[1].'/'.$tab[0];
        }
    $stmt->closeCursor();
	$stmt=NULL; 
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier un versement</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Veuillez modifier les informations du versement :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Num. bordereau<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" name="NumBordereau" required="required" value="<?php echo $num;?>" />
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllModePaiementSelected($idcompte,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date versement
											</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateversement" required="required" value="<?php echo $dateversement;?>"/>
											<input type="hidden" name="idversement" value="<?php echo $idversement;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Vers&eacute; par
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllUserSelected($iduserauto,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Liste des paiements qui sont associ&eacute;s :::</i></label><br/><br/>
                            <?php ListePaiementFraisByVersement($idversement,$pdo);?>
                            <hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='versement.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierVersement" 
								value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>