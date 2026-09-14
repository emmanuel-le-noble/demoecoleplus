<?php
   	$req=(' SELECT  distinct
					paiementfrais.id as id,
					paiementfrais.montant as montant,
					paiementfrais.date as datepaiement,
					paiementfrais.telpayeur as telpayeur,
					paiementfrais.nompayeur as nompayeur,
					paiementtype.libelle as libellepaiementtype,
					classe.codeclasse as codeclasse,
					anneescolaire.libelle as libelleanneescolaire,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					paiementtypeclasse.montant as montantpaiementtypeclasse,
					paiementtypeclasse.remise as remise,
					paiementfrais.ideleveanneescolaire as ideleveanneescolaire,
					paiementfrais.idpaiementtypeclasse as idpaiementtypeclasse,
					eleveanneescolaire.boursier as boursier
					
			FROM paiementfrais,paiementtype,paiementtypeclasse,classe,eleve,eleveanneescolaire,anneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.id=paiementfrais.ideleveanneescolaire
			AND
			paiementfrais.idpaiementtypeclasse=paiementtypeclasse.id
			AND
			paiementtypeclasse.idpaiementtype=paiementtype.id
			AND
			paiementtypeclasse.idclasse=classe.idclasse
			AND
			paiementtypeclasse.idanneescolaire=anneescolaire.id
			AND
			paiementfrais.statut=1
			AND
			paiementfrais.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$idpaiementfrais,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$idpaiementtypeclasse = $donnees['idpaiementtypeclasse'];
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$montant = $donnees['montant'];
			$nompayeur = $donnees['nompayeur'];
			$telpayeur = $donnees['telpayeur'];
			$tab = explode('-',$donnees['datepaiement']);
			$datepaiement = $tab[2].'/'.$tab[1].'/'.$tab[0];
			$libellepaiementtype = $donnees['libellepaiementtype'];
			$codeclasse = $donnees['codeclasse'];
			$libelleanneescolaire = $donnees['libelleanneescolaire'];
			$nomeleve = $donnees['nomeleve'];
			$prenomeleve = $donnees['prenomeleve'];
			$boursier = $donnees['boursier'];
			$boursier = is_numeric($boursier) ? $boursier : 0;
			$montantpaiementtypeclasse = $donnees['montantpaiementtypeclasse']-$boursier;
			$remise = $donnees['remise'];
			$montanttotalpayer = getMontantEleveAnneeScolaire($idpaiementtypeclasse,$ideleveanneescolaire,$pdo);
			$montantrestant = $montantpaiementtypeclasse-$montanttotalpayer;
        }
    $stmt->closeCursor();
	$stmt=NULL; 
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="box-shadow: 8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold">
						        Consulter le paiement <span style="color:red">N° <?php echo $idpaiementfrais;?></span>
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ D&eacute;tail du paiement ]</span></div><br/>
							<table width="100%">
								<tr>
									<td width="33%">
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Date paiement&nbsp;&nbsp;&nbsp;&nbsp;<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $datepaiement;?>" disabled="disabled"/>
											</div>
										</div>
									</td>
									<td width="33%"></td>
									<td width="33%"></td>
								</tr>
								<tr>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Montant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $montant;?>" disabled="disabled"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"style="text-align:right;">Nom payeur<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $nompayeur;?>" disabled="disabled"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"style="text-align:right;">T&eacute;l. payeur<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $telpayeur;?>" disabled="disabled"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Informations suppl&eacute;mentaires sur le type de frais pay&eacute; ]</span></div><br/>
							<table width="100%">
								<tr>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom & pr&eacute;nom &eacute;l&egrave;ve<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $nomeleve.' '.$prenomeleve;?>" required="required" disabled="disabled"/>
												<input type="hidden" name="idpaiementfrais" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" disabled="disabled" value="<?php echo $libelleanneescolaire;?>"/>
											</div>
										</div>
									</td>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12"style="text-align:right;">Type Paiement<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $libellepaiementtype;?>" disabled="disabled"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo $codeclasse;?>" disabled="disabled"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Montant associ&eacute;<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo number_format($montantpaiementtypeclasse,0,""," ");?>" disabled="disabled"/>
											</div>
										</div>
									</td>
									<td>
									    <div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12"style="text-align:right;">Montant pay&eacute;<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" value="<?php echo number_format($montanttotalpayer,0,""," ");?>" disabled="disabled"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12"style="text-align:left;">Montant restant<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" class="form-control" value="<?php echo number_format($montantrestant,0,""," ");?>" disabled="disabled"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PaiementFrais.php'"/>
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>