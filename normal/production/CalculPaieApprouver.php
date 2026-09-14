<?php

	$tab = explode("/",$finmois);
	if($tab[1]+0==1)
	{
		$libelle="Janvier ".$tab[2];
	}
	elseif($tab[1]+0==2)
	{
		$libelle="Fevrier ".$tab[2];
	}
	elseif($tab[1]+0==3)
	{
		$libelle="Mars ".$tab[2];
	}
	elseif($tab[1]+0==4)
	{
		$libelle="Avril ".$tab[2];
	}
	elseif($tab[1]+0==5)
	{
		$libelle="Mai ".$tab[2];
	}
	elseif($tab[1]+0==6)
	{
		$libelle="Juin ".$tab[2];
	}
	elseif($tab[1]+0==7)
	{
		$libelle="Juillet ".$tab[2];
	}elseif($tab[1]+0==8)
	{
		$libelle="Août ".$tab[2];
	}elseif($tab[1]+0==9)
	{
		$libelle="Septembre ".$tab[2];
	}elseif($tab[1]+0==10)
	{
		$libelle="Octobre ".$tab[2];
	}elseif($tab[1]+0==11)
	{
		$libelle="Novembre ".$tab[2];
	}
	elseif($tab[1]+0==12)
	{
		$libelle="Decembre ".$tab[2];
	}
	
	$req=(" SELECT  distinct 
					mois.create_id as create_id,
					mois.dateapprouv as dateapprouv,
					mois.datecreate as datecreate,
					mois.approuv_id as approuv_id,
					mois.observation as observation
				
		FROM mois
		WHERE
		mois.id=:idmois");	
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			
			$observation = trim($donnees['observation']);
			$create_id = trim($donnees['create_id']);
			$approuv_id = trim($donnees['approuv_id']);
			$nomusercreate = getnomprenom($create_id,$pdo);
			$nomuserapprouv = getnomprenom($approuv_id,$pdo);
			$datecreate = trim($donnees["datecreate"]);
			if($datecreate!="")
			{
				$tab = explode("-",trim($datecreate));
				$datecreate = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$datecreate="";
			}
			$dateapprouv = trim($donnees["dateapprouv"]);
			if($dateapprouv!="")
			{
				$tab = explode("-",trim($dateapprouv));
				$dateapprouv = $tab[2]."/".$tab[1]."/".$tab[0];
			}
			else
			{
				$dateapprouv="";
			}
		}
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
						 <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:red;">
								Approuver le calcul de paie : <?php echo $libelle;?>
							</h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
						    [ Information(s) permettant de g&eacute;n&eacute;rer la paie ]</span></div><br/>							
							<table width="100%">
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire  : <?php echo $libelleanneescolaire;?></label>
											<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
											<input type="hidden" name="idmois" value="<?php echo $idmois;?>"/>
											<input type="hidden" name="corps" value="<?php echo $corps;?>"/>
											<input type="hidden" name="debutmois" value="<?php echo $debutmois;?>"/>
											<input type="hidden" name="finmois" value="<?php echo $finmois;?>"/>
											<input type="hidden" name="mois" value="<?php echo $libelle;?>"/>
											<input type="hidden" name="corpslibelle" value="<?php echo $corpslibelle;?>"/>
										</div>
									</td>
								</tr>
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>  : <?php echo $debutmois;?></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Fin <small style="font-size:10px;">(P&eacute;riode)</small>  : <?php echo $finmois;?></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;color:orange">Corps : <span style="color:red;"><?php echo $corpslibelle;?></span></label>
										</div>
									</td>
								</tr>
							</table>
							<?php 
							if($corps==1)
							{
								?>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
								[ Veuillez approuver les nombres d'heures effectu&eacute;es et saisir la date d'approbation ainsi que l'observation ]</span></div>
								<br/>
								<table width="100%">
									<tr>
										<td colspan=2> 
											<?php ApprouverPaieCorpsProfessorat($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</td>
									</tr>
									<tr>
										<td width="50%">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">
													Date d'approbation :
												</label>
												<div class='col-sm-6'>
													<div class='input-group date' id='myDatepicker16' class="col-md-6 col-sm-6 col-xs-12">
														<input type='text' class="form-control" name="dateapprobation" autocomplete="off" required="required" style="border-radius:5px;border:1px solid #000;"/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
										</td>
										<td width="50%">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">
													Observation(s) : 
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" name="observation" required="required"  autocomplete="off" style="border:1px solid #000;"/>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte op&eacute;ration<span class="required">&nbsp;&nbsp;</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<?php getAllCompte($pdo);?>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Disponibilit&eacute;</label>
												<div class="col-md-5 col-sm-5 col-xs-12" id="disponibilite">
													<input type="text" class="form-control" name="disponibilite" autocomplete="off" required="required" style="border:1px solid #000;" readonly="yes"/>
												</div>
											</div>
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div align="center" class="col-md-6 col-md-offset-3">
									<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='CalculPaie.php'"/>
									<input type="submit" class="btn btn-round btn-success" name="ApprouverCalculPaie" value="Valider"/> 
								</div><?php
							}
							else
							{
								?>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
								[ Veuillez approuver le traitement des donn&eacute;es,saisir la date d'approbation ainsi que l'observation ]</span></div>
								<br/>
								<table width="100%" align="center">
									<tr>
										<td colspan=2>  
											<?php ApprouverPaieCorpsAdministratif($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</td>
									</tr>
									<tr>
										<td width="50%">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">
													Date d'approbation :
												</label>
												<div class='col-sm-6'>
													<div class='input-group date' id='myDatepicker16' class="col-md-6 col-sm-6 col-xs-12">
														<input type='text' class="form-control" name="dateapprobation" autocomplete="off" required="required" style="border-radius:5px;border:1px solid #000;"/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
										</td>
										<td width="50%">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">
													Observation(s) : 
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" class="form-control" name="observation" required="required"  autocomplete="off" style="border:1px solid #000;"/>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Compte op&eacute;ration<span class="required">&nbsp;&nbsp;</span></label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<?php getAllCompte($pdo);?>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Disponibilit&eacute;</label>
												<div class="col-md-5 col-sm-5 col-xs-12" id="disponibilite">
													<input type="text" class="form-control" name="disponibilite" autocomplete="off" required="required" style="border:1px solid #000;" readonly="yes"/>
												</div>
											</div>
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div align="center" class="col-md-6 col-md-offset-3">
									<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='CalculPaie.php'"/>
									<input type="submit" class="btn btn-round btn-success" name="ApprouverCalculPaie" value="Valider"/> 
								</div><?php
							}
							?>
					    </div>
                    </div>
                </div>
            </div>
			<form action="" method="POST">
				<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header text-center">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title w-100 font-weight-bold" style="color:red;font-weight:bold;font-size:16px;font-family:comic sans ms" align="left">
									D&eacute;tail de la paie  de : 
									<input type="email" name="nom" id="nom" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
								</h4>
							</div>
							<table width="100%" style="font-size:11px;">
								<tr>
									<td align="center" style="padding:10px;">
										<span style="color:#000;font-weight:bold;font-size:14px;font-family:comic sans ms;">
											El&eacute;ments du salaire brute
										</span>
									</td>
									<td align="center" style="padding:10px;">
										<span style="color:#000;font-weight:bold;font-size:14px;font-family:comic sans ms;">
											El&eacute;ments du retenue
										</span>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire base
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="salairebase" id="salairebase" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Cnss
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="cnss" id="cnss" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Anciennet&eacute;
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="anciennete" id="anciennete" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Irpp
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="irpp" id="irpp" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Sursalaire
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="sursalaire" id="sursalaire" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Remboursement
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="remboursement" id="remboursement" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Ind. Logement
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="indemnitelogement" id="indemnitelogement" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												<span style="color:#000;font-weight:bold;font-size:14px;font-family:comic sans ms">
													Salaire net
												</span>
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="salairenet" id="salairenet" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Ind. Transport
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="indemnitetransport" id="indemnitetransport" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Ind. Fonction
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="indemnitefonction" id="indemnitefonction" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Sujestion
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="primesujetion" id="primesujetion" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Interim
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="primeinterim" id="primeinterim" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Caisse
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="primecaisse" id="primecaisse" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire brute
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="email" name="salairebrute" id="salairebrute" class="form-control col-md-7 col-xs-12" style="border-radius:5px;text-align:center;" readonly="yes"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
						</div>
					</div>
				</div>
			</form>
        </div>
    </body>
</html>