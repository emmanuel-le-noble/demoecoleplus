<?php
	
	$tabdebutmois = explode("/",$debutmois);
	$debutmois_ = $tabdebutmois[2].'-'.$tabdebutmois[1].'-'.$tabdebutmois[0];
	
	$tabfinmois = explode("/",$finmois);
	$finmois_ = $tabfinmois[2].'-'.$tabfinmois[1].'-'.$tabfinmois[0];
	
	echo $idpers;
	
	$req=(" SELECT distinct professeur.nom as nom,mois.id as idmois,moissalaire.*			
			FROM moissalaire,professeur,mois
			WHERE
			professeur.id=:idpers
			AND
			moissalaire.idpers=professeur.id
			AND
			moissalaire.idmois=mois.id
			AND
			mois.debutmois=:debutmois
			AND
			mois.finmois=:finmois
			AND
			moissalaire.idanneescolaire=:idanneescolaire");
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':debutmois',$debutmois_,PDO::PARAM_STR);
	$stmt ->bindParam(':finmois',$finmois_,PDO::PARAM_STR);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_STR);
	$stmt ->bindParam(':idpers',$idpers,PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$idmois = $donnees['idmois'];
		$nom = $donnees['nom'];
		$salairebase = round(str_replace(" ","",$donnees['SALAIREBASE']));
		if($salairebase=="" || $salairebase=="-")
		{
			$salairebase=0;
		}
		$anciennete = round(str_replace(" ","",$donnees['ANCIENNETE']));
		if($anciennete=="" || $anciennete=="-")
		{
			$anciennete=0;
		}
		$sursalaire = round(str_replace(" ","",$donnees['SURSALAIRE']));
		if($sursalaire=="" || $sursalaire=="-")
		{
			$sursalaire=0;
		}
		$indemnitefonction = round(str_replace(" ","",$donnees['INDEMNITEFONCTION']));
		if($indemnitefonction=="" || $indemnitefonction=="-")
		{
			$indemnitefonction=0;
		}
		$primesujetion = round(str_replace(" ","",$donnees['PRIMESUJETION']));
		if($primesujetion=="" || $primesujetion=="-")
		{
			$primesujetion=0;
		}
		$primeinterim = round(str_replace(" ","",$donnees['PRIMEINTERIM']));
		if($primeinterim=="" || $primeinterim=="-")
		{
			$primeinterim=0;
		}
		$indemnitelogement = round(str_replace(" ","",$donnees['INDEMNITELOGEMENT']));
		if($indemnitelogement=="" || $indemnitelogement=="-")
		{
			$indemnitelogement=0;
		}
		$indemnitetransport = round(str_replace(" ","",$donnees['INDEMNITETRANSPORT']));
		if($indemnitetransport=="" || $indemnitetransport=="-")
		{
			$indemnitetransport=0;
		}
		$primecaisse = round(str_replace(" ","",$donnees['PRIMECAISSE']));
		if($primecaisse=="" || $primecaisse=="-")
		{
			$primecaisse=0;
		}
		$allocationfami = round(str_replace(" ","",$donnees['ALLOCATIONFAMI']));
		if($allocationfami=="" || $allocationfami=="-")
		{
			$allocationfami=0;
		}
		$salairebrute = round(str_replace(" ","",$donnees['SALAIREBRUTE']));
		if($salairebrute=="" || $salairebrute=="-")
		{
			$salairebrute=0;
		}
		$cnss = round(str_replace(" ","",$donnees['CNSS']));
		if($cnss=="" || $cnss=="-")
		{
			$cnss=0;
		}
		$inam = round(str_replace(" ","",$donnees['INAM']));
		if($inam=="" || $inam=="-")
		{
			$inam=0;
		}
		$crt = round(str_replace(" ","",$donnees['CRT']));
		if($crt=="" || $crt=="-")
		{
			$crt=0;
		}
		$tcs = round(str_replace(" ","",$donnees['TCS']));
		if($tcs=="" || $tcs=="-")
		{
			$tcs=0;
		}
		$irpp = round(str_replace(" ","",$donnees['IRPP']));
		if($irpp=="" || $irpp=="-")
		{
			$irpp=0;
		}
		$assurance = round(str_replace(" ","",$donnees['ASSURANCE']));
		if($assurance=="" || $assurance=="-")
		{
			$assurance=0;
		}
		$remboursement = round(str_replace(" ","",$donnees['REMBOURSEMENT']));
		if($remboursement=="" || $remboursement=="-")
		{
			$remboursement=0;
		}
		$autresretenues = round(str_replace(" ","",$donnees['AUTRESRETENUES']));
		if($autresretenues=="" || $autresretenues=="-")
		{
			$autresretenues=0;
		}
		$mutuelle = round(str_replace(" ","",$donnees['MUTUELLE']));
		if($mutuelle=="" || $mutuelle=="-")
		{
			$mutuelle=0;
		}
		$totalretenues = round(str_replace(" ","",$donnees['TOTALRETENUES']));
		if($totalretenues=="" || $totalretenues=="-")
		{
			$totalretenues=0;
		}
		$primeinstallation = round(str_replace(" ","",$donnees['PRIMEINSTALLATION']));
		if($primeinstallation=="" || $primeinstallation=="-")
		{
			$primeinstallation=0;  
		}
		$rappel = round(str_replace(" ","",$donnees['RAPPEL']));
		if($rappel=="" || $rappel=="-")
		{
			$rappel=0;
		}
		$salairenet = round(str_replace(" ","",$donnees['SALAIRENET']));

	}
	$stmt -> closeCursor();
	$stmt = NULL;
?>
<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="x_panel" style="background-color:#cfcfcf;box-shadow: 8px 8px 0px #aaa;">
						<div class="x_title">
							<div class="alert alert-danger alert-dismissible fade in" role="alert" 
							style="width:100%;padding:5px;border:1px solid #A2C600;">
							<span style="color:#fff;font-weight:bold;font-size:18px;">
								Enregistrement r&eacute;ussi de la fiche de paie!!!. Veuillez la consulter avant de continuer.</span>
							</div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <table width="100%">
								<tr>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire  : <?php echo $libelleanneescolaire;?></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">D&eacute;but <small style="font-size:10px;">(P&eacute;riode)</small>  : <?php echo $debutmois;?></label>
										</div>
									</td>
									<td> 
										<div class="item form-group">
											<label class="control-label col-md-10 col-sm-10 col-xs-12" style="text-align:left;">Fin <small style="font-size:10px;">P&eacute;riode</small>  : <?php echo $finmois;?></label>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
							<br/>
							<table width="100%">
								<tr>
									<td colspan="2"> 
										<div class="item form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-12" style="text-align:left;color:red;"> Nom employ&eacute; : </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											  <input type="text" name="nomemploye" class="form-control col-md-7 col-xs-12" value="<?php echo $nom;?>" readonly="yes" style="border-radius:5px;"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire base<span class="required">&nbsp;&nbsp;<font color="red">*</font>
											</span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="salairebase" class="form-control col-md-7 col-xs-12" value="<?php echo $salairebase;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											  <input type="hidden" name="idmois" value="<?php echo $idmois;?>"/>
											  <input type="hidden" name="idanneescolaire" value="<?php echo $idanneescolaire;?>"/>
											  <input type="hidden" name="debutmois" value="<?php echo $debutmois;?>"/>
											  <input type="hidden" name="finmois" value="<?php echo $finmois;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Anciennet&eacute;
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="anciennete" class="form-control col-md-7 col-xs-12" value="<?php echo $anciennete;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Sursalaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="sursalaire" class="form-control col-md-7 col-xs-12" value="<?php echo $sursalaire;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Caisse
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="primecaisse" class="form-control col-md-7 col-xs-12" value="<?php echo $primecaisse;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Indem. Logement
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="indemnitelogement" class="form-control col-md-7 col-xs-12" value="<?php echo $indemnitelogement;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Astreinte
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input type="text" name="allocationfami" class="form-control col-md-7 col-xs-12" value="<?php echo $allocationfami;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Transport
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="indemnitetransport" class="form-control col-md-7 col-xs-12" value="<?php echo $indemnitetransport;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire brute
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="salairebrute" class="form-control col-md-7 col-xs-12" value="<?php echo $salairebrute;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Fonction
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="indemnitefonction" class="form-control col-md-7 col-xs-12" value="<?php echo $indemnitefonction;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Cnss
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="cnss" class="form-control col-md-7 col-xs-12" value="<?php echo $cnss;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Sujestion
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="primesujetion" class="form-control col-md-7 col-xs-12" value="<?php echo $primesujetion;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Remboursement
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="remboursement" class="form-control col-md-7 col-xs-12" value="<?php echo $remboursement;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Interim
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="primeinterim" class="form-control col-md-7 col-xs-12" value="<?php echo $primeinterim;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Irpp
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="irpp" class="form-control col-md-7 col-xs-12" value="<?php echo $irpp;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
													Salaire net
												</span>
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span>
											</label>
											<div class="col-md-8 col-sm-8 col-xs-12">
											  <input type="text" name="salairenet" class="form-control col-md-7 col-xs-12" value="<?php echo $salairenet;?>" readonly="yes" style="border-radius:5px;" autocomplete="off"/>
											</div>
										</div>
									</td>
									<td></td>
								</tr>
							</table>
							<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/><br/>
							<div align="center" class="col-md-8 col-md-offset-3">
								<input type="submit" class="btn btn-round btn-warning" name="suivantEditionFichePaie" value="Ajouter de nouvelle(s) fiche(s) de paie"/>
								&nbsp;
								<button class="btn btn-round" onclick='window.open("imprimeBulletin.php?&idmois=<?php echo $idmois;?>&idanneescolaire=<?php echo $idanneescolaire;?>&idpers=<?php echo $idpers;?>&debutmois=<?php echo $debutmois;?>&finmois=<?php echo $finmois;?>&var=1","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'><i class="fa fa-print"></i>&nbsp;Imprimer la fiche de paie</button>
							</div>
					    </div>
                    </div>
                </div>
				<div class="col-md-3 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><span style="font-family:comic sans ms;color:red;font-size:10px;">::: Mois de paie enregistr&eacute;e :::</span></h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						    <table width="100%">
								<tr>
									<td> 
										<div class="item form-group">
											<input type="submit" style="width:80px;" class="btn btn-info btn-xs" name="Modifier" value="Consulter"/>
											&nbsp;
											<input type="submit" style="width:80px;" class="btn btn-danger btn-xs" name="Supprimer" value="Supprimer"/>
											&nbsp;
											<?php ListFichePaieMois($idanneescolaire,$debutmois,$finmois,$pdo);?>
										</div>
									</td>
								</tr>
							</table>				
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>