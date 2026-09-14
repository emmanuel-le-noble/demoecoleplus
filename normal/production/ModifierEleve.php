<?php
   	$req=(' SELECT  distinct
	                eleve.id_eleve as id_eleve,
					eleve.nom_eleve as nom_eleve,
					eleve.sexe_eleve as sexe_eleve,
                    eleve.etat_eleve as etat_eleve,
                    eleve.matricule as matricule,
                    eleve.teltuteur as teltuteur,
                    eleve.mailtuteur as mailtuteur,
                    eleve.photo as photo,
                    eleve.datenaissance_eleve as datenaissance_eleve,					
					elevesalle.id as idelevesalle,
					anneescolaire.libelle as Libelle,
					salle.id as idsalle,
					salle.codesalle as CodeSalle,
					elevesalle.statut as statut

			FROM    eleve,eleveanneescolaire,anneescolaire,elevesalle,salle
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			eleveanneescolaire.id=elevesalle.ideleve
			AND
			elevesalle.idsalle=salle.id
            AND
			elevesalle.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$idelevesalle = $donnees['idelevesalle'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$idsalle = $donnees['idsalle'];
			$etat_eleve = $donnees['etat_eleve'];
			$photo = $donnees['photo'];
			$matricule = $donnees['matricule'];
			$teltuteur = $donnees['teltuteur'];
			$mailtuteur = $donnees['mailtuteur'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			if($datenaissance_eleve!="")
			{
				$tab = explode("-",trim($datenaissance_eleve));
				$annee = $tab[0];
				$mois = $tab[1];
				$jour = $tab[2];
				
				$datenaissance_eleve = $jour.'/'.$mois.'/'.$annee;
			}
			$Libelle = $donnees['Libelle'];
			$CodeSalle = $donnees['CodeSalle'];
			$statut = $donnees['statut'];
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
                    <div class="x_panel" style="background-color: #E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier le dossier d'un &eacute;l&egrave;ve
						    </h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Information de l'&eacute;l&egrave;ve :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Photo
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="file" name="image" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td rowspan="2" valign="top">									
										<div id="image_preview" class="">
											<div style="padding:5px;" class="">
												<?php
													if($photo=="")
													{
														?>
													<img src="elevephoto/user.png" alt="" width="80px;" height="80px;" 
														style="border:1px dotted #000;"/><?php
													}
													else
													{
														?>
													<img src="elevephoto/<?php echo $photo;?>" alt="" width="80px;" height="80px;" style="border:1px dotted #000;"/><?php
													}
												?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Num. Matricule</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											    <input type="text" name="num_matricule" class="form-control col-md-7 col-xs-12" value="<?php echo $matricule;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom & pr&eacute;nom</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											    <input type="text" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nom_eleve;?>">
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;" valign="top">Statut</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" name="etat">
													<option></option>
													<?php
													if($etat_eleve=="NN")
													{
										                ?>
										                <option></option>
                                                        <option selected="selected">NN</option>
														<option>AR</option>
														<option>NR</option>
														<option>AN</option>
														<option>NA</option>
                                                        <?php											
													}
													elseif($etat_eleve=="AR")
													{
														?>
														<option></option>
                                                        <option selected="selected">AR</option>
														<option>NN</option>
														<option>NR</option>
														<option>AN</option>
														<option>NA</option>
														<?php
													}
													elseif($etat_eleve=="NR")
													{
														?>
														<option></option>
                                                        <option selected="selected">NR</option>
														<option>NN</option>
														<option>AR</option>
														<option>AN</option>
														<option>NA</option>
														<?php
													}
													elseif($etat_eleve=="AN")
													{
														?>
														<option></option>
                                                        <option selected="selected">AN</option>
														<option>NN</option>
														<option>AR</option>
														<option>NR</option>
														<option>NA</option>
														<?php
													}
													elseif($etat_eleve=="NA")
													{
														?>
														<option></option>
                                                        <option selected="selected">NA</option>
														<option>NN</option>
														<option>AR</option>
														<option>NR</option>
														<option>AN</option>
														<?php
													}
													else
													{
														?>
														<option></option>
                                                        <option>NA</option>
														<option>NN</option>
														<option>AR</option>
														<option>NR</option>
														<option>AN</option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="date_naissance" value="<?php echo $datenaissance_eleve;?>"/>
										<input type="hidden" name="idelevesalle" value="<?php echo $idelevesalle;?>"/>
                                                <input type="hidden" name="id_eleve" value="<?php echo $id_eleve;?>"/>								
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;" valign="top">Sexe</label>
											<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:10px;">
												<select class="form-control col-md-7 col-xs-12" name="sexe">
													<option></option>
													<?php
													if($sexe_eleve=="Masculin")
													{
										                ?>
                                                        <option selected="selected">Masculin</option>
														<option>Feminin</option>
                                                        <?php											
													}
													elseif($sexe_eleve=="Feminin")
													{
														?>
														<option>Masculin</option>
														<option selected="selected">Feminin</option>
														<?php
													}
													else
													{
														?>
														<option>Masculin</option>
														<option>Feminin</option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">T&eacute;l. du tuteur
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" data-inputmask="'mask' : '(999) 99999999'" name="teltuteur" value="<?php echo $teltuteur;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Email. du tuteur
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" name="emailtuteur" value="<?php echo $mailtuteur;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<label class="control-label col-md-5 col-sm-5 col-xs-12" 
							style="text-align:left;color:#000;">
							<i>::: Choisissez la Classe :::</i></label><br/><br/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Classe</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<?php getAllSalleSelected($idsalle,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='DossierEleve.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierDossierEleve" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>