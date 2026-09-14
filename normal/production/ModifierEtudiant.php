<?php
   	$req=(' SELECT  
					eleve.nom_eleve,
					eleve.datenaissance_eleve,
					eleve.sexe_eleve,
					elevesalle.idsalle
					
			FROM eleve,elevesalle
			WHERE
			eleve.id_eleve=elevesalle.ideleve
			AND
			eleve.id_eleve=:id_eleve');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id_eleve',$id_eleve,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$nom_eleve = $donnees['nom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			/*if($datenaissance_eleve!="")
			{
				$tab = explode("-",$datenaissance_eleve);
				$datenaissance_eleve = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$datenaissance_eleve = "";
			}*/				
			
			$idsalle = $donnees['idsalle'];
        }
    $stmt->closeCursor();
	$stmt=NULL; 
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
				<div class="title_left">
					<h2><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;Scolarit&eacute; - Inscription - Ajouter</h2>
				</div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><font color="red">Ajouter une inscription</font></h2>
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
							<div style="margin:10px;"><span style="color:red;"><i>::: Information de l'&eacute;l&egrave;ve :::</i></span></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" id="nom_eleve" name="nom_eleve" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nom_eleve;?>">
											    <input type="hidden" name="id_eleve" value="<?php echo $id_eleve;?>"/>
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
							</table><hr/>
							<div style="margin:10px;"><span style="color:red;"><i>::: Choisissez la salle :::</i></span></div>
							<table width="100%">
								<tr>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="name" style="text-align:left;">Salle<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<?php getAllSalleSelected($idsalle,$pdo);?>
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
					<input type="button" class="btn btn-primary" value="Fermer" onclick="document.location='Etudiant.php'"/>
					<input id="send" type="submit" class="btn btn-success" name="modifier_etudiant" value="Enregistrer"/> 
				</div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    </body>
</html>