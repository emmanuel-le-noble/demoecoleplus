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
					        	Ajouter un utilisateur
					        </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <br/>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Enseignant ?<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12" style="margin-top:10px;">
												<input type="radio" name="typeutilisateur" value="Oui"/> Oui
												&nbsp;&nbsp;&nbsp;
												<input type="radio" name="typeutilisateur" value="Non" checked="checked"/> Non
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;"></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <?php getAllProfesseur_($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="nom_user" name="nom_user" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Prenom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="prenom_user" name="prenom_user" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Identifiant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" id="login_user" name="login_user" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Mot de passe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="password" id="mtpass_user" name="mtpass_user" required="required" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Profil<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<select class="form-control col-md-7 col-xs-12" name="profil">
													<option></option>
														<option>Administrateur</option>
														<option>Proviseur</option>
														<option>Directeur</option>
														<option>Professeur</option>
														<option>Secretaire</option>
														<option>Surveillant</option>
												</select>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Utilisateur.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerUser" value="Enregistrer"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const radios = document.querySelectorAll('input[name="typeutilisateur"]');
			const listeProf = document.querySelector('.col-md-6.col-sm-6.col-xs-12 > select, .col-md-6.col-sm-6.col-xs-12 > div'); // détecte le contenu de getAllProfesseur
			const champs = document.querySelectorAll('#nom_user, #prenom_user');

			// Au chargement : cacher la liste et désactiver les champs
			if (listeProf) listeProf.style.display = 'none';
			champs.forEach(ch => ch.disabled = false);

			// Quand on clique sur Oui / Non
			radios.forEach(radio => {
				radio.addEventListener('change', function() {
					if (this.value === 'Oui') {
						// Afficher la liste
						if (listeProf) listeProf.style.display = 'block';
						// Activer les champs
						champs.forEach(ch => ch.disabled = true);
					} else if (this.value === 'Non') {
						// Cacher la liste
						if (listeProf) listeProf.style.display = 'none';
						// Désactiver les champs
						champs.forEach(ch => ch.disabled = false);
					}
				});
			});
		});
	</script>
    </body>
</html>