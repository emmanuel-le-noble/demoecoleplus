<?php
	$req=(' SELECT  
					entreesortie.id as idEntreeSortie,
					entreesortie.libelle as libelleEntreeSortie,
					entreesortie.montant as MontantEntreeSortie,
					entreesortie.dateoperation as dateEntreeSortie,
					entreesortie.idtypeentreesortie as idTypeEntreeSortie,
					entreesortie.comptemouvement as CompteMouvement,
					entreesortie.iduserajout as iduserAjout,
					entreesortie.iduserauto as idUserAuto,
					entreesortie.iduserdelete as idUserDelete,
					entreesortie.idanneescolaire as idAnneeScolaire,
					entreesortie.ficheattache as FicheAttache,
					entreesortie.idsoustypeentreesortie as idSousTypeEntreeSortie
					
			FROM entreesortie
			WHERE
			entreesortie.id=:idEntreeSortie
			AND
			entreesortie.statut=1');
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idEntreeSortie',$idEntreeSortie,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$idEntreeSortie = $donnees['idEntreeSortie'];
			$libelleEntreeSortie = $donnees['libelleEntreeSortie'];
			$MontantEntreeSortie = $donnees['MontantEntreeSortie'];
			$idTypeEntreeSortie = $donnees['idTypeEntreeSortie'];
			$CompteMouvement = $donnees['CompteMouvement'];
			$iduserAjout = $donnees['iduserAjout'];
			$idUserAuto = $donnees['idUserAuto'];
			$idUserDelete = $donnees['idUserDelete'];
			$idAnneeScolaire = $donnees['idAnneeScolaire'];
			$tab = explode('-',$donnees['dateEntreeSortie']);
			$dateEntreeSortie = $tab[2].'/'.$tab[1].'/'.$tab[0];
			$FicheAttache = $donnees['FicheAttache'];
			$idSousTypeEntreeSortie = $donnees['idSousTypeEntreeSortie'];
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
						        Modifier l'op&eacute;ration
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez mettre &agrave; jour les information(s) ]</span></div>
							<hr style="border:1px dotted orange"/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Type op&eacute;ration</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="hidden" name="idEntreeSortie" value="<?php echo $idEntreeSortie;?>"/>
												<select class="form-control col-md-7 col-xs-12" name="TypeOperation">
													<option></option>
													<?php 
													if($idTypeEntreeSortie==1)
													{
														?>
														<option value="1" selected="selected">Entr&eacute;e(s)</option>
														<option value="2">Sortie(s)</option>
														<?php
													}
													else
													{
														?>
														<option value="1">Entr&eacute;e(s)</option>
														<option value="2" selected="selected">Sortie(s)</option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</td>
									<td width="50%"></td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Sous type op&eacute;ration</label>
											<div class="col-md-6 col-sm-6 col-xs-12" id="SousTypeOperation">
												<?php getSousTypeOperationSelected($idTypeEntreeSortie,$idSousTypeEntreeSortie,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte op&eacute;ration <span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllCompteSelected($CompteMouvement,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaireSelected($idAnneeScolaire,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date Op&eacute;ration</label>
											<div class='col-sm-4'>
												<div class='input-group date' id='myDatepicker2' class="col-md-6 col-sm-6 col-xs-12">
													<input type='text' class="form-control" name="DateOperation" autocomplete="off" value="<?php echo $dateEntreeSortie;?>"/>
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Libell&eacute; Op&eacute;ration
											</label>
											<div class="col-md-7 col-sm-7 col-xs-12">
												<input type="text" name="LibelleOperation" required="required" class="form-control" value="<?php echo $libelleEntreeSortie;?>"/>
												
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Autoris&eacute; par<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllUserSelected($idUserAuto,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Montant Op&eacute;ration<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" required="required" name="Montant" value="<?php echo $MontantEntreeSortie;?>"/>
												<input type="hidden" class="form-control" name="MontantOld" value="<?php echo $MontantEntreeSortie;?>"/>
												<input type="hidden" class="form-control" name="FichierOld" value="<?php echo $FicheAttache;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pi&egrave;ce justificative<span class="required">&nbsp;&nbsp;</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%"/>
												<?php
												$dossier = 'entreesorties/';
												if($FicheAttache!="")
												{
													if(file_exists($dossier.$FicheAttache)) 
													{
														?><a href="entreesorties/<?php echo $FicheAttache;?>" target="_blank" class="btn btn-info btn-xs">[T&eacute;l&eacute;charger]</a><?php
													} 
													else 
													{
														?>
														<span class="btn btn-danger btn-xs" onclick="new PNotify({
															  title: 'Oh Non!',
															  text: 'La pi&egrave;ce justificative n a pas pu &ecirc;tre transf&eacute;r&eacute; sur le serveur. Veuillez la r&eacute; attacher',
															  type: 'info',
															  styling: 'bootstrap3',
															  addclass: 'dark'
														  });">[Pi&egrave;ce non disponible]</span><?php
													}
												}
												?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Tresorerie.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierOperation" value="Enregistrer"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>