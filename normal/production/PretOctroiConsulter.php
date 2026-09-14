<?php
	$req=(' SELECT  
					distinct 
					professeur.nom as nom,
					pret.id as idpret,
					pret.libelle as libelle,
					pret.idpersonnel as idpersonnel,
					pret.montantpret as montantpret,
					pret.montantpreleve as montantpreleve,
					pret.dateoperation as dateoperation,
					pret.debut as debut,
					pret.fin as fin,
					pret.fichier as fichier,
					pret.statut as statut,
					pret.create_id as create_id,
					pret.idcompte as idcompte,
					compte.libelle as compte
					
		FROM pret,professeur,compte
		WHERE
		pret.idpersonnel=professeur.id
		AND
		pret.idcompte=compte.id
		AND
		pret.id=:idpret');
		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idpret',$idpret,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$nom = $donnees['nom'];
			$compte = $donnees['compte'];
			$libelle = $donnees['libelle'];
			$idpret = $donnees['idpret'];
			$idpersonnel = $donnees['idpersonnel'];
			$montantpret = $donnees['montantpret'];
			$montantpreleve = $donnees['montantpreleve'];
			$dateoperation = $donnees['dateoperation'];
			$tab = explode("-",$dateoperation);
			$dateoperation = $tab[2]."/".$tab[1]."/".$tab[0];
			$debut = $donnees['debut'];
			$tab_ = explode("-",$debut);
			$debut = $tab_[2]."/".$tab_[1]."/".$tab_[0];
			$fin = $donnees['fin'];
			$tab__ = explode("-",$fin);
			$fin = $tab__[2]."/".$tab__[1]."/".$tab__[0];
			$fichier = $donnees['fichier'];
			$statut = $donnees['statut'];
			$create_id = $donnees['create_id'];
			$idcompte = $donnees['idcompte'];
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
                    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Consulter un pret
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
								Information(s) g&eacute;n&eacute;rale du prêt</span></div>
							<hr style="border:1px dotted #000;"/>
							<table width="100%" align="center">
								<tr>
									<td width="50%">
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Personnel : <?php echo $nom;?>
											</label>
										</div>
									</td>
									<td width="50%"></td>
								</tr>
								<tr>
									<td align="left">
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Objet du prêt : <?php echo $libelle;?>
											</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Date du prêt : <?php echo $dateoperation;?>
											</label>
										</div>
									</td>
								</tr>
								<tr>
									<td align="left">
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Montant prêt : <?php echo $montantpret;?>
											</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Montant pr&eacute;lev&eacute; : <?php echo $montantpreleve;?>
											</label>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												D&eacute;but <small>(p&eacute;riode)</small> : <?php echo $debut;?>
											</label>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Fin <small>(p&eacute;riode)</small> : <?php echo $fin;?>
											</label>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">
												Compte op&eacute;ration<span class="required">&nbsp;&nbsp;</span> : <?php $compte;?>
											</label>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td align="center" valign="middle" colspan="2">									
										<div class="form-group">
											<?php
											if($fichier!="")
											{
												?><a href="pret/<?php echo $fichier;?>"><span style="color:red;">[T&eacute;l&eacute;charger la pi&egrave;ce justificative]</span></a><?php
											}
											?>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
								Tableau d'amortissement</span></div><br/>
							<?php TableauAmortissment_1($idpret,$pdo);?>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PretOctroi.php'"/> 
							</div>							
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>