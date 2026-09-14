<?php
   	$req=(' SELECT  
				utilisateur.id as id,
				utilisateur.nom_user as nom_user,
				utilisateur.prenom_user as prenom_user,
				utilisateur.profil as profil,
				utilisateur.login_user as login_user,
				utilisateur.mtpass_user as mtpass_user,
				utilisateur.type as type
				
			FROM utilisateur
			WHERE
			utilisateur.id=:id_user');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id_user',$id_user,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom_user = $donnees['nom_user'];
			$prenom_user = $donnees['prenom_user'];
			$profil = $donnees['profil'];
			$login_user = $donnees['login_user'];
			$mtpass_user = $donnees['mtpass_user'];
			$type = $donnees['type'];
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
					        	Modifier un utilisateur
					        </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<?php
								if($type=="Oui")
								{
									?>
									<tr>
										<td>
											<div class="item form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Enseignant ?<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12" style="margin-top:10px;">
													<input type="radio" name="typeutilisateur" value="Oui" checked="checked"/> Oui
													&nbsp;&nbsp;&nbsp;
													<input type="radio" name="typeutilisateur" value="Non"/> Non
												</div>
											</div>
										</td>
										<td>
											<div class="item form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Liste<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <?php getAllProfesseurSelected_($id,$pdo);?>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="item form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="nom_user" name="nom_user" disabled="true" required="required" class="form-control col-md-7 col-xs-12"/>
												</div>
											</div>
										</td>
										<td>
											<div class="item form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Prenom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="prenom_user" name="prenom_user" disabled="true" required="required" class="form-control col-md-7 col-xs-12"/>
												</div>
											</div>
										</td>
									</tr>
									<?php
								}
								else
								{
									?>
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
												  <input type="text" id="nom_user" name="nom_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nom_user;?>"/>
												</div>
											</div>
										</td>
										<td>
											<div class="item form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Prenom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
												  <input type="text" id="prenom_user" name="prenom_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $prenom_user;?>"/>
												</div>
											</div>
										</td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="login_user" style="text-align:left;">Identifiant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" name="login_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $login_user;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mtpass_user" style="text-align:left;">Mot de passe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="password" name="mtpass_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $mtpass_user;?>"/>
											</div>
											<input type="hidden" name="id_user" value="<?php echo $id_user;?>"/>
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
													<?php
														if($profil=="Administrateur")
														{
															?>
															<option selected="selected">Administrateur</option>
															<option>Directeur</option>
															<option>Professeur</option>
															<option>Secretaire</option>
															<option>Surveillant</option>
															<option>Proviseur</option>
															<?php
														}
														elseif($profil=="Directeur")
														{
															?>
															<option>Administrateur</option>
															<option selected="selected">Directeur</option>
															<option>Professeur</option>
															<option>Secretaire</option>
															<option>Surveillant</option>
															<option>Proviseur</option>
															<?php
														}
														elseif($profil=="Professeur")
														{
															?>
															<option>Administrateur</option>
															<option>Directeur</option>
															<option selected="selected">Professeur</option>
															<option>Secretaire</option>
															<option>Surveillant</option>
															<option>Proviseur</option>
															<?php
														}
														elseif($profil=="Secretaire")
														{
															?>
															<option>Administrateur</option>
															<option>Directeur</option>
															<option>Professeur</option>
															<option selected="selected">Secretaire</option>
															<option>Surveillant</option>
															<option>Proviseur</option>
															<?php
														}
														elseif($profil=="Surveillant")
														{
															?>
															<option>Administrateur</option>
															<option>Directeur</option>
															<option>Professeur</option>
															<option>Secretaire</option>
															<option>Proviseur</option>
															<option selected="selected">Surveillant</option><?php
														}
														elseif($profil=="Proviseur")
														{
															?>
															<option>Administrateur</option>
															<option>Directeur</option>
															<option>Professeur</option>
															<option>Secretaire</option>
															<option>Surveillant</option>
															<option selected="selected">Proviseur</option><?php
														}
														else
														{
															?>
															<option>Administrateur</option>
															<option>Directeur</option>
															<option>Professeur</option>
															<option>Secretaire</option>
															<option>Surveillant</option>
															<option>Proviseur</option>
															<?php
														}
													?>
												</select>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;" />
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Utilisateur.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierUser" value="Enregistrer"/> 
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
			//if (listeProf) listeProf.style.display = 'none';
			//champs.forEach(ch => ch.disabled = false);

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