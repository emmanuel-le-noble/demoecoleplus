<?php
    $tab = explode("-",$id);
	$id = $tab[0];
   	$req=(' SELECT  
	                eleve.id_eleve,
					eleve.num_eleve,
					eleve.photo_eleve,
					eleve.nom_eleve,
					eleve.prenom_eleve,
					eleve.datenaissance_eleve,
					eleve.lieunaissance_eleve,
					eleve.tel_eleve,
					eleve.commentaire_eleve,
					eleve.sexe_eleve,
					eleve.pers_a_prevenir,
					eleve.adresse_eleve,
					eleve.idnationalite,
					exercice_eleve.droit_inscription,
					exercice_eleve.date_inscrit,
					exercice_eleve.idclasse

			FROM eleve,exercice_eleve
			WHERE
			eleve.id_eleve=exercice_eleve.id_eleve
			AND
			exercice_eleve.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$num_eleve = $donnees['num_eleve'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$photo_eleve = $donnees['photo_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			$tab = explode("-",$datenaissance_eleve);
			$datenaissance_eleve = $tab[2]."/".$tab[1]."/".$tab[0];
			
			$lieunaissance_eleve = $donnees['lieunaissance_eleve'];
			$tel_eleve = $donnees['tel_eleve'];
			$commentaire_eleve = $donnees['commentaire_eleve'];
			$droit_inscription = $donnees['droit_inscription'];
			$date_inscrit = $donnees['date_inscrit'];
			$tabinscrit = explode("-",$date_inscrit);
			$date_inscrit = $tabinscrit[2]."/".$tabinscrit[1]."/".$tabinscrit[0];
			
			$pers_a_prevenir = $donnees['pers_a_prevenir'];
			$idnationalite = $donnees['idnationalite'];
			$adresse_eleve = $donnees['adresse_eleve'];
			
			$idclasse = $donnees['idclasse'];
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
            <div class="page-title">
				<div class="title_left">
					<h2><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;Scolarit&eacute; - Inscription - Modifier</h2>
				</div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><font color="red">Modifier une inscription</font></h2>
							<ul class="nav navbar-right panel_toolbox">
							    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
									<ul class="dropdown-menu" role="menu">
									    <li><a href="#">Settings 1</a></li>
									    <li><a href="#">Settings 2</a></li>
									</ul>
							    </li>
							    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
							</ul>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<!-- start project list -->
						<div class="clearfix"></div>
						    <div style="margin:10px;"><span style="color:red;"><i>::: Date de l'inscription:::</i></span></div>
							<div class="item form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="dateinscrit" style="text-align:right;">Date d'inscrit<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" style="background-color:#A2C600;" name="dateinscrit" required="required" value="<?php echo $date_inscrit;?>"/>
								</div>
							</div><hr/>
							<div style="margin:10px;"><span style="color:red;"><i>::: Information de l'&eacute;l&egrave;ve :::</i></span></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" style="text-align:left;">Photo<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" name="image" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>									
										<div id="image_preview" class="">
											<div class="">
												<img src="photo/<?php echo $photo_eleve;?>" alt="" width="150px;" height="150px;" style="border:1px solid #000;">
												<div class="caption">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Photo membre</b>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="nom_eleve" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nom_eleve;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" style="text-align:left;">Pr&eacute;nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="prenom_eleve" name="prenom_eleve" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $prenom_eleve;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" style="text-align:left;">Date naissance<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="date_naissance" value="<?php echo $datenaissance_eleve;?>"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" style="text-align:left;" valign="top">Sexe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:10px;">
												<select class="form-control col-md-7 col-xs-12" name="sexe">
													<option></option>
													<?php
														if($sexe_eleve=="Masculin")
														{
															?>
															<option selected="selected">Masculin</option>
															<option>Feminin</option><?php
														}
														else
														{
															?>
															<option selected="selected">Feminin</option>
															<option>Masculin</option><?php
														}
													?>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lieu_naissance" style="text-align:left;">Lieu Naissance<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="lieu_naissance" name="lieu_naissance" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $lieunaissance_eleve;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone" style="text-align:left;">T&eacute;l&eacute;phone<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" data-inputmask="'mask' : '(999) 999-9999'" name="telephone" required="required" value="<?php echo $tel_eleve;?>"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lieu_naissance" style="text-align:left;">Nationalit&eacute;<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllNationaliteSelected($idnationalite,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="adresse" style="text-align:left;">Adresse<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" name="adresse" required="required" value="<?php echo $adresse_eleve;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="commentaire" style="text-align:left;">Commentaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<textarea id="commentaire" required="required" name="commentaire" class="form-control col-md-7 col-xs-12" placeHolder="Commentaire sur le dossier de l'inscription"><?php echo $commentaire_eleve;?></textarea>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pers_a_prevenir" style="text-align:left;">Pers. &agrave; pr&eacute;venir<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<textarea id="pers_a_prevenir" required="required" name="pers_a_prevenir" class="form-control col-md-7 col-xs-12" placeHolder="Personne &agrave; prevenir"><?php echo $pers_a_prevenir;?></textarea>
											</div>
										</div>
									</td>
								</tr>
							</table><hr/>
							<div style="margin:10px;"><span style="color:red;"><i>:::Choisissez la fili&egrave;re/Classe et le Droit d'inscription:::</i></span></div>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="name" style="text-align:left;">Fili&egrave;re/Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllClasseFiliereSelected($idclasse,$pdo);?>
												<input type="hidden" name="id_eleve" value="<?php echo $id_eleve;?>"/>
												<input type="hidden" name="photo_eleve" value="<?php echo $photo_eleve;?>"/>
												<input type="hidden" name="id" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td width="50%">									
										<div id="idresultat">
											<div class="item form-group">
												<label class="control-label col-md-4 col-sm-4 col-xs-12" for="name" style="text-align:left;">Droit d'inscription<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" readonly="yes" name="montantdroit" class="form-control" value="<?php echo $droit_inscription;?>"/>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						<!-- end project list -->
					    </div>
                    </div>
                </div>
				<div align="center" class="col-md-6 col-md-offset-3">
					<input type="button" class="btn btn-primary" value="Fermer" onclick="document.location='InscriptionEleve.php'"/>
					<input id="send" type="submit" class="btn btn-success" name="modifier_inscription_2" value="Enregistrer"/> 
				</div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    </body>
</html>