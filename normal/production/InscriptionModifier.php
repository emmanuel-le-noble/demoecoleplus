<?php
   	$req=(' SELECT  distinct
	                eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleve.sexe_eleve as sexeeleve,
                    eleve.etat_eleve as etateleve,
                    eleve.matricule as matricule,
                    eleve.teltuteur as teltuteur,
                    eleve.mailtuteur as mailtuteur,
                    eleve.photo as photo,
                    eleve.datenaissance_eleve as datenaissanceeleve,
                    eleve.lieunaissance_eleve as lieunaissanceeleve,
					anneescolaire.libelle as libelle,
					eleveanneescolaire.inscrit as inscrit,
					eleveanneescolaire.etat as etat,
					eleveanneescolaire.id as ideleveanneescolaire,
					eleveanneescolaire.commentaire as commentaire,
					eleveanneescolaire.dossierinscription as dossierinscription,
					eleveanneescolaire.boursier as boursier,
					eleveanneescolaire.idclasse as idclasse,
					eleveanneescolaire.etatremise as etatremise
					
			FROM    eleve,eleveanneescolaire,anneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			eleveanneescolaire.id=:ideleveanneescolaire');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':ideleveanneescolaire',$ideleveanneescolaire,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$ideleve = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'];
			$prenomeleve = $donnees['prenomeleve'];
			$sexeeleve = $donnees['sexeeleve'];
			$etateleve = $donnees['etateleve'];
			$photo = $donnees['photo'];
			$matricule = $donnees['matricule'];
			$teltuteur = $donnees['teltuteur'];
			$mailtuteur = $donnees['mailtuteur'];
			$datenaissanceeleve = $donnees['datenaissanceeleve'];
			$libelle = $donnees['libelle'];
			$etat = $donnees['etat'];
			$inscrit = $donnees['inscrit'];
			$commentaire = $donnees['commentaire'];
			$dossierinscription = $donnees['dossierinscription'];
			$boursier = $donnees['boursier'];
			$idclasse = $donnees['idclasse'];
			$etatremise = $donnees['etatremise'];
			$lieunaissanceeleve = $donnees['lieunaissanceeleve'];
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
                    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Modifier une inscription [ Matricule ] : <font color='#ff0000'><?php echo $matricule;?></font>
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							::: Veuillez mettre &agrave; jour les information(s) :::</span></div><br/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Photo
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" name="image" class="form-control col-md-7 col-xs-12"/>
												<input type="hidden" name="ideleveanneescolaire" value="<?php echo $ideleveanneescolaire;?>"/>
												<input type="hidden" name="ideleve" value="<?php echo $ideleve;?>"/>
												<input type="hidden" name="dossierinscriptionold" value="<?php echo $dossierinscription;?>"/>
												<input type="hidden" name="photoold" value="<?php echo $photo;?>"/>
											</div>
										</div>
									</td>
									<td rowspan="3" valign="top" width="50%" style="padding-left:200px;">									
										<div id="image_preview" class="">
											<div style="padding:5px;" class="col-md-6 col-sm-6 col-xs-12">
												<?php
												if($photo=="")
												{
													?><img src="images/user.png" alt="" width="110px" height="110px" style="border:1px dotted #000;"/><?php
												}
												else
												{
													?><img src="elevephoto/<?php echo $photo;?>" alt="" width="110px" height="110px" style="border:1px dotted #000;"/><?php
												}
												?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nomeleve;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="prenom_eleve" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $prenomeleve;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input class="form-control" required="required" type="date" name="date_naissance" value="<?php echo $datenaissanceeleve;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Statut dans la classe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getEleveStatutClasseSelected($etat,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Lieu naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input class="form-control" type="text" name="lieu_naissance" value="<?php echo $lieunaissanceeleve;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">T&eacute;l. du tuteur
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" class="form-control" data-inputmask="'mask' : '(999) 99999999'" required="required" name="teltuteur" value="<?php echo $teltuteur;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">
												Statut dans l'&eacute;tabliss.
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getEleveStatutEtablissementSelected($inscrit,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
							<tr>
								<td>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Email. du tuteur
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control"  name="emailtuteur" value="<?php echo $mailtuteur;?>"/>
										</div>
									</div>
								</td>
								<td>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Sexe</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select class="form-control col-md-7 col-xs-12" name="sexe" required="required">
												<option></option>
												<?php
												if($sexeeleve=="Masculin")
												{
									                ?>
                                                    <option selected="selected">Masculin</option>
													<option>Feminin</option>
                                                    <?php											
												}
												elseif($sexeeleve=="Feminin")
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
							<?php if(!empty($mailtuteur)): ?>
							<tr>
								<td colspan="2">
									<div class="form-group">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<button type="button" class="btn btn-sm btn-info btn-invitation" data-id="<?php echo $ideleve;?>">
												<i class="fa fa-envelope"></i> Renvoyer l'invitation portail parent
											</button>
											<span id="invitation-result-<?php echo $ideleve;?>" style="margin-left:10px;"></span>
										</div>
									</div>
								</td>
							</tr>
							<?php endif; ?>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Num. Matricule</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="num_matricule" class="form-control col-md-7 col-xs-12" value="<?php echo $matricule;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Remise sur frais scolarit&eacute; ?</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" id="remise_frais" name="remise_frais" required="required">
													<option value="">-- Sélectionnez une option --</option>
													<?php 
													if($etatremise=="Aucune")
													{
														?>
														<option value="Aucune" selected="selected">Aucune remise</option>
														<option value="Partielle">Remise partielle</option>
														<option value="Gratuite">Gratuité totale</option>
														<?php
													}
													elseif($etatremise=="Partielle")
													{
														?>
														<option value="Partielle" selected="selected">Remise partielle</option>
														<option value="Aucune">Aucune remise</option>
														<option value="Gratuite">Gratuité totale</option>
														<?php
													}
													elseif($etatremise=="Gratuite")
													{
														?>
														<option value="Gratuite" selected="selected">Gratuité totale</option>
														<option value="Partielle">Remise partielle</option>
														<option value="Aucune">Aucune remise</option>
														<?php
													}
													else
													{
														?>
														<option value="Aucune">Aucune remise</option>
														<option value="Partielle">Remise partielle</option>
														<option value="Gratuite">Gratuité totale</option>
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
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;bold;color:#000;">Niveau d'inscription
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllClasseFiliereSelected($idclasse,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Montant remise</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="number" class="form-control" name="remise" id="remise" value="<?php echo $boursier;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<?php InscriptionPieceSelected($ideleveanneescolaire,$pdo);?>
							<hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Inscription.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierInscription" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const remiseSelect = document.getElementById("remise_frais");
			const montantRemise = document.getElementById("remise");

			remiseSelect.addEventListener("change", function() {
				const choix = this.value;
				if (choix === "Partielle") {
					montantRemise.disabled = false;
				} else {
					montantRemise.disabled = true;
					montantRemise.value = ""; // on vide le champ si inactif
				}
			});

			document.querySelectorAll('.btn-invitation').forEach(function(btn) {
				btn.addEventListener('click', function() {
					var idEleve = this.getAttribute('data-id');
					var resultSpan = document.getElementById('invitation-result-' + idEleve);
					resultSpan.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Envoi en cours...';
					btn.disabled = true;

					var fd = new FormData();
					fd.append('id_eleve', idEleve);

					fetch('EnvoyerInvitation.php', { method: 'POST', body: fd })
						.then(function(r) { return r.json(); })
						.then(function(data) {
							btn.disabled = false;
							if (data.ok) {
								resultSpan.innerHTML = '<span style="color:green;"><i class="fa fa-check"></i> ' + data.message + '</span>';
							} else {
								resultSpan.innerHTML = '<span style="color:red;"><i class="fa fa-times"></i> ' + data.message + '</span>';
							}
						})
						.catch(function() {
							btn.disabled = false;
							resultSpan.innerHTML = '<span style="color:red;"><i class="fa fa-times"></i> Erreur réseau.</span>';
						});
				});
			});
		});
	</script>
    </body>
</html>