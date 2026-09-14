<?php
   	$req=(' SELECT  distinct 
					permission.idprod AS idprof,
					permission.datedemande AS datedemande,
					permission.motif AS motif,
					permission.statut AS statut,
					permission.datedebut AS datedebut,
					permission.datefin AS datefin,
					permission.id AS id,
					permission.fichier AS fichier
					
			FROM permission
			WHERE
			permission.id=:id');
			$stmt = $pdo->prepare($req);
			$stmt->bindParam(':id',$id,PDO::PARAM_INT);
			$stmt->execute();	
			if($donnees = $stmt->fetch())
			{
				$idprof = $donnees['idprof'];
				$datedemande = $donnees['datedemande'];
				$fichier = $donnees['fichier'];
				$statut = $donnees['statut'];
				$datedebut = $donnees['datedebut'];
				$datefin = $donnees['datefin'];
				$id = $donnees['id'];
				$motif = $donnees['motif'];
			}
    $stmt->closeCursor();
	$stmt=NULL; 
?>
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
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier la demande de permission
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
						    [ Veuillez renseigner les information(s) ]</span></div><br/>
							<table width="100%">
								<tr>
									<td width="50%">
									    <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom enseignant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllPersonnelSelected($idprof,$pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Fichier permission<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" class="form-control" name="image"/>
												<?php if (!empty($fichier)) : ?>
													<a href="decisiondocument/<?= rawurlencode($fichier) ?>" target="_blank">
														<em style="color:red;font-weight:bold;">T&eacute;l&eacute;charger le fichier</em>
													</a>
												<?php else : ?>
													<em>Aucun fichier</em>
												<?php endif; ?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date d&eacute;but<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="date" class="form-control" name="datedebut" required="required" value="<?php echo $datedebut;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date fin<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="date" class="form-control" name="datefin" required="required" value="<?php echo $datefin;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date demande<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="date" class="form-control" name="datedemande" required="required" value="<?php echo $datedemande;?>"/>
											</div>
										</div>
									</td>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Motif<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" name="motif" required="required" value="<?php echo $motif;?>"/>
												<input type="hidden" class="form-control" name="id" required="required" value="<?php $id;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Permission.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierPermission" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>