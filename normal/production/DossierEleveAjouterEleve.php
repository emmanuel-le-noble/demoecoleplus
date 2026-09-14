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
						        Ajouter un &eacute;l&egrave;ve dans une classe [ Matricule ] : <font color='#ff0000'><?php echo $matricule;?></font>
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms;text-align:left;">
							[ Veuillez renseigner les information(s) ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%" style="border-bottom:1px dotted orange;">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Photo
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="file" name="image" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td rowspan="3" valign="top" width="50%" style="padding-left:200px;">									
										<div id="image_preview" class="">
											<div style="padding:5px;" class="">
												<img src="images/user.png" alt="" width="110px;" height="110px;" style="border:1px dotted #000;"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="prenom_eleve" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Date naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="date_naissance"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Statut dans la classe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getEleveStatutClasse($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Lieu naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input class="form-control" type="text" name="lieu_naissance"/>
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
											<input type="text" class="form-control" data-inputmask="'mask' : '(999) 99999999'" name="teltuteur"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">
												Statut dans l'&eacute;tabliss.
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getEleveStatutEtablissement($pdo);?>
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
											<input type="text" class="form-control"  name="emailtuteur"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Sexe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" name="sexe" required="required"/>
													<option></option>
													<option>Masculin</option>
													<option>Feminin</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
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
													<option value="Aucune">Aucune remise</option>
													<option value="Partielle">Remise partielle</option>
													<option value="Gratuite">Gratuité totale</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;bold;color:#000;">
											Classe affect&eacute;e
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllSalle($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Montant remise</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="number" class="form-control" id="remise" name="remise"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<?php InscriptionPiece($pdo);?>
							<hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='DossierEleve.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="AjouterDossierEleve" value="Enregistrer"/> 
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
		});
	</script>
    </body>
</html>