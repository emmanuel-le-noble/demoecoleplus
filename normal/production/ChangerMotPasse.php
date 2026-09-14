<?php
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
    $id_user = $_GET["UtilisateurConnecte"];
	
   	$req=(' SELECT  
				utilisateur.id,
				utilisateur.profil,
				utilisateur.nom_user,
				utilisateur.prenom_user,
				utilisateur.login_user,
				utilisateur.mtpass_user
				
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
                    <div class="x_panel">
                        <div class="x_title">
							<h2><font color="red">Changement de mot de passe</font></h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nom_user" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" name="nom_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nom_user;?>" disabled="disabled" style="font-size:12px;"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="prenom_user" style="text-align:left;">Prenom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" name="prenom_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $prenom_user;?>" disabled="disabled" style="font-size:12px;"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="login_user" style="text-align:left;">Identifiant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="login_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $login_user;?>" disabled="disabled" style="font-size:12px;"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mtpass_user" style="text-align:left;">Mot de passe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="password" name="mtpass_user" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $mtpass_user;?>" style="font-size:12px;"/>
												<input type="hidden" value="<?php echo $id_user;?>" name="id_user"/>
											</div>
										</div>
									</td>
								</tr>
							</table>							
					    </div>
                    </div>
                </div>
				<div align="center" class="col-md-6 col-md-offset-3">
					<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Ecoleplus.php'"/>
					<input id="send" type="submit" class="btn btn-round btn-success" name="changer_mot_2" value="Enregistrer"/> 
				</div>
            </div>
        </div>
    </div>
    </body>
</html>