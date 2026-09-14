<?php
	$req=(' SELECT  
					distinct 
					pret.id as idpret,
					pret.libelle as libelle,
					pret.idpersonnel as idpersonnel,
					pret.montantpret as montantpret,
					pret.montantpreleve as montantpreleve,
					pret.dateoperation as dateoperation,
					pret.debut as debut,
					pret.fin as fin,
					pret.fichier as fichier,
					pret.statut as statut,
					pret.create_id as create_id,
					pret.idcompte as idcompte
					
		FROM pret
		WHERE
		pret.id=:idpret');
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$libelle = $donnees['libelle'];
			$idpret = $donnees['idpret'];
			$idpersonnel = $donnees['idpersonnel'];
			$montantpret = $donnees['montantpret'];
			$montantpreleve = $donnees['montantpreleve'];
			$dateoperation = $donnees['dateoperation'];
			$tab = explode("-",$dateoperation);
			$dateoperation = $tab[2]."/".$tab[1]."/".$tab[0];
			$debut = $donnees['debut'];
			$tab_ = explode("-",$debut);
			$debut = $tab_[2]."/".$tab_[1]."/".$tab_[0];
			$fin = $donnees['fin'];
			$tab__ = explode("-",$fin);
			$fin = $tab__[2]."/".$tab__[1]."/".$tab__[0];
			$fichier = $donnees['fichier'];
			$statut = $donnees['statut'];
			$create_id = $donnees['create_id'];
			$idcompte = $donnees['idcompte'];
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
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier le dossier de pret
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:14px;font-family:comic sans ms">
							[ Veuillez mettre à jour les information(s) pour l'op&eacute;ration ]</span></div>
							<hr style="border:1px dotted #000;"/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Personnel</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllProfesseurPersonnelSelected($idpersonnel,$pdo);?>
											</div>
										</div>
									</td>
									<td width="50%"></td>
								</tr>
								<tr>
									<td align="left">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Objet du prêt
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="objetpret" required="required" class="form-control" autocomplete="off" value="<?php echo $libelle;?>"/>
												<input type="hidden" name="idpret" value="<?php echo $idpret;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date du prêt</label>
											<div class='col-sm-4'>
												<div class='input-group date' id='myDatepicker25' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="datepret" autocomplete="off" required="required" value="<?php echo $dateoperation;?>"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td align="left">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Montant prêt
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="montantpret" required="required" class="form-control" autocomplete="off" value="<?php echo $montantpret;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Montant pr&eacute;lev&eacute;</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" required="required" name="montantpreleve" autocomplete="off" value="<?php echo $montantpreleve;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">D&eacute;but <small>(p&eacute;riode)</small></label>
											<div class='col-sm-4'>
												<div class='input-group date' id='myDatepicker26' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="debut" autocomplete="off" required="required" value="<?php echo $debut;?>"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Fin <small>(p&eacute;riode)</small></label>
											<div class='col-sm-4'>
												<div class='input-group date' id='myDatepicker27' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="fin" autocomplete="off" required="required" value="<?php echo $fin;?>"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte op&eacute;ration<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllCompteSelected($idcompte,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Disponibilit&eacute;</label>
											<div class="col-md-5 col-sm-5 col-xs-12" id="disponibilite">
												<input type="text" class="form-control" name="disponibilite" autocomplete="off" required="required" readonly="yes"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pi&egrave;ce justificative<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%" required="required"/>
												<input type="hidden" name="fichierold" value="<?php echo $fichier;?>"/>
											</div>
										</div>
									</td>
									<td align="center" valign="middle">									
										<div class="form-group">
											<?php
											$dossier = 'pret/';
											if($fichier!="")
											{
												if(file_exists($dossier.$fichier)) 
												{
													?><a href="pret/<?php echo $fichier;?>" target="_blank" class="btn btn-info btn-xs">[T&eacute;l&eacute;charger la pi&egrave;ce justificative]</a><?php
												} 
												else 
												{
													?><a href="#" target="_blank"><span class="btn btn-danger btn-xs">[Pi&egrave;ce non disponible]</span></a><?php
												}
											}
											?>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PretOctroi.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierPret" value="Enregistrer"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>