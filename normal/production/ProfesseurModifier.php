<?php
   	$req=(' SELECT  distinct
	                professeur.id,
					professeur.nom,
					professeur.titre,
                    professeur.contact,
					professeur.signature

			FROM    professeur
			WHERE
			professeur.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$titre = $donnees['titre'];
			$contact = $donnees['contact'];
			$signature = $donnees['signature'];
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
						        Modifier un professeur
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez mettre &agrave; jour les information(s) ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom/Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="nomprof" name="nomprof" required="required" value="<?php echo $nom;?>" class="form-control col-md-7 col-xs-12">
												<input type="hidden" name="idprof" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Contact</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="contact" name="contact" required="required" value="<?php echo $contact;?>" class="form-control col-md-7 col-xs-12">
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Titre</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllTitreSelect($titre,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Signature</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%"/>
												<input type="hidden" name="imageold" value="<?php echo $signature;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class="">
											<?php
												if($signature!="")
												{
													?>
													<img src="photo_user/<?php echo $signature;?>" alt="" width="200px" height="50px" style="border:0px solid #000;"/>
													<?php
												}
												else
												{
													?>
													<img src="images/signatureIni.jpg" alt="" width="200px" height="50px" style="border:0px solid #000;"/>
													<?php
												}
											?>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Professeur.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierProfesseur_" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>