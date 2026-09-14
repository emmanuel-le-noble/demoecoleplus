<?php

   	$req=(' SELECT  distinct
	                professeur.id,
					professeur.nom,
					professeur.titre,
                    professeur.contact,
					professeur.signature,
					professeur.idanneescolaire,
					professeur.statut,
					professeur.dateembauche,
					professeur.datenaissance,
					professeur.personneacharge,
					professeur.lieunaissance,
					professeur.numcnss,
					professeur.numcomptebancaire,
					professeur.idbanque,
					professeur.debutcontrat,
					professeur.fincontrat,
					professeur.modepaiement

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
			$idanneescolaire = $donnees['idanneescolaire'];
			$statut = $donnees['statut'];
			$dateembauche = trim($donnees["dateembauche"]);
			if($dateembauche!="")
			{
				$tab = explode("-",trim($dateembauche));
				$dateembauche = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$dateembauche="";
			}
			$datenaissance = trim($donnees["datenaissance"]);
			if($datenaissance!="")
			{
				$tab = explode("-",trim($datenaissance));
				$datenaissance = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$datenaissance="";
			}
			$personnecharge = $donnees['personneacharge'];
			$lieunaissance = $donnees['lieunaissance'];
			$numcnss = $donnees['numcnss'];
			$numcomptebancaire = $donnees['numcomptebancaire'];
			$idbanque = $donnees['idbanque'];
			$debutcontrat = trim($donnees["debutcontrat"]);
			if($debutcontrat!="")
			{
				$tab = explode("-",trim($debutcontrat));
				$debutcontrat = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$debutcontrat="";
			}
			$fincontrat = trim($donnees["fincontrat"]);
			if($fincontrat!="")
			{
				$tab = explode("-",trim($fincontrat));
				$fincontrat = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$fincontrat="";
			}
			$modepaiement = $donnees['modepaiement'];
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
								Modifier un nouvel employ&eacute;
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
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Fonction</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllTitreSelect($titre,$pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllAnneeScolaireSelected($idanneescolaire,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nom;?>" style="font-size:12px;"/>
												<input type="hidden" name="id" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Pr&eacute;nom</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="prenom" class="form-control col-md-7 col-xs-12"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Contact</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="contact" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $contact;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="numcnss" style="text-align:left;">Num CNSS</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="numcnss" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $numcnss;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Date d'embauche</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dateembauche" value="<?php echo $dateembauche;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Date Naissance</label>
											<div class="col-md-4 col-sm-4 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="datenaissance" value="<?php echo $datenaissance;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Lieu naissance</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="lieunaissance" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $lieunaissance;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">personne &agrave; charge</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="perscharge" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $personnecharge;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">N° compte bancaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="numcomptebancaire" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $numcomptebancaire;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Banque</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllBanqueSelected($idbanque,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">D&eacute;but contrat</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="debutcontrat" required="required" class="form-control" data-inputmask="'mask': '99/99/9999'" value="<?php echo $debutcontrat;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Fin contrat</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="fincontrat" value="<?php echo $fincontrat;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Signature</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image" width="50%"/>
												<input type="hidden" name="imageold" value="<?php echo $signature;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Mode de paiement</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllModePaiementSelected($modepaiement,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class="">
												<img src="images/signatureIni.jpg" alt="" width="200px" height="50px" style="border:0px solid #000;">
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Personnel.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierPersonnel" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>