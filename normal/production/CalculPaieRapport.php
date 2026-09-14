<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/finance.php");
	
	$idmois = $_REQUEST['idmois'];
	$idanneescolaire = $_REQUEST['idanneescolaire'];
	$debutmois = $_REQUEST['debutmois'];
	$finmois = $_REQUEST['finmois'];
	$corps = $_REQUEST['corps'];
	
	$corpslibelle="";
	if($corps==1)
	{
		$corpslibelle="Temporaire";
	}
	else
	{
		$corpslibelle="Permanent";
	}
	$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
	$libelle="";
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
					mois.observation as observation,
					compte.libelle as compte
					
			FROM mois left join compte on compte.id=mois.idcompte
			WHERE
			mois.id=:idmois");	
			$stmt = $pdo->prepare($req);
			$stmt ->bindParam(':idmois',$idmois,PDO::PARAM_INT);
			$stmt->execute();	
			if($donnees = $stmt->fetch())
			{
				$compte = trim($donnees['compte']);
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
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>EcolePlus|</title>
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
	<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet"/>
	<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>
	<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet"/>
	<link href="../vendors/starrr/dist/starrr.css" rel="stylesheet"/>
	<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
	<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
	<link href="../build/css/select.css" rel="stylesheet"/>
	<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
	<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
</head>
    <body style="font-size:11px;background-color:#fff;">
        <div>
            <div></div>
            <div>
                <div>
                    <div>
						<div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight:bold;color:red;">
								<img src="images/bordjo.jpeg" width="50%" height="110px"/>
								<br/><br/>
								Etat du calcul de paie : <?php echo $libelle;?>
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div>
						<div class="clearfix"></div>
						    <div><span style="color:#000;font-weight:bold;font-size:16px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
						    ::: Information(s) permettant de g&eacute;n&eacute;rer la paie :::</span></div><br/>							
							<table width="100%" align="center">
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> Ann&eacute;e scolaire  : <a style="font-weight:normal;color:green;"><?php echo $libelleanneescolaire;?></a></label>
											<input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
											<input type="hidden" name="idmois" value="<?php echo $idmois;?>"/>
											<input type="hidden" name="corps" value="<?php echo $corps;?>"/>
											<input type="hidden" name="debutmois" value="<?php echo $debutmois;?>"/>
											<input type="hidden" name="finmois" value="<?php echo $finmois;?>"/>
										</div>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>  : <a style="font-weight:normal;"><?php echo $debutmois;?></a></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> Fin <small style="font-size:10px;">(P&eacute;riode)</small> : <a style="font-weight:normal;"><?php echo $finmois;?></a></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;color:orange"> Corps : <span style="color:red;"><a style="font-weight:normal;"><?php echo $corpslibelle;?></a></span></label>
										</div>
									</td>
								</tr>
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> G&eacute;n&eacute;rateur : <a style="font-weight:normal;"><?php echo $nomusercreate;?></a></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> Date g&eacute;n&eacute;ration : <a style="font-weight:normal;"><?php echo $datecreate;?></a></label>
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> Approbateur : <a style="font-weight:normal;"><?php echo $nomuserapprouv;?></a></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> Date approbation : <a style="font-weight:normal;"><?php echo $dateapprouv;?></a></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;"> Compte d'op&eacute;ration : <a style="font-weight:bold;"><?php echo $compte;?></a></label>
										</div>
									</td>
								</tr>
							</table>
							<?php 
							if($corps==1)
							{
								?>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div><span style="color:#000;font-weight:bold;font-size:16px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
									::: Nombres d'heures effectu&eacute;es,la date d'approbation ainsi que l'observation :::</span></div>
								<br/>
								<table width="100%">
									<tr>
										<td colspan="2"> 
											<?php ApprouverPaieCorpsProfessorat($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</td>
									</tr>
									<tr>
										<td width="50%">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">Date d'approbation : <a style="font-weight:bold;color:#000;"><?php echo $dateapprouv;?></a></label>
											</div>
										</td>
										<td width="50%" align="right">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">Observation(s) effectu&eacute;e(s) lors de l'approbation : <a style="font-weight:bold;color:#000;"><?php echo $observation;?></a></label>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">Compte d'op&eacute;ration : <a style="font-weight:bold;color:#000;"><?php echo $compte;?></a></label>
											</div>
										</td>
										<td align="right">
											
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/>
								<?php
							}
							else
							{
								?>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
								<div><span style="color:#000;font-weight:bold;font-size:16px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
									::: les donn&eacute;es de paie trait&eacute;es,la date d'approbation ainsi que l'observation:::</span></div>
								<br/>
								<table width="100%">
									<tr>
										<td colspan="2">
											<?php ImprimerPaieCorpsAdministratif($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</td>
									</tr>
									<tr>
										<td width="50%">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">Date d'approbation : <a style="font-weight:bold;color:#000;"><?php echo $dateapprouv;?></a></label>
											</div>
										</td>
										<td width="50%" align="right">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">Observation(s) effectu&eacute;e(s) lors de l'approbation : <a style="font-weight:bold;color:#000;"><?php echo $observation;?></a></label>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">Compte d'op&eacute;ration : <a style="font-weight:bold;color:#000;"><?php echo $compte;?></a></label>
											</div>
										</td>
										<td align="right">
											
										</td>
									</tr>
								</table>
								<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/>
								<?php
							}
							?>
					    </div>
						<table style="width:100%;" align="center" class="design">
							<tr>
								<td style="padding-left:5px;border:1px solid #fff;"><span><u>Date d'&eacute;mission&nbsp;&nbsp;</u><?php echo date('d/m/y');?></span></td>
							</tr>
							<tr>
								<td width="50%" style="padding-left:5px;border:1px solid #fff;text-align:center;">
									<span style="font-weight:normal;text-align:right;">Nom & Signature du responsable N° 1</span>
								</td>
								<td width="50%" style="padding-left:5px;border:1px solid #fff;text-align:center;">
									<span style="font-weight:normal;text-align:right;">Nom & Signature du responsable N° 2</span>
								</td>
							</tr>
						</table>
						<br/>
                    </div>
                </div>
            </div>
        </div>
		<script>window.print();</script>
    </body>
</html>