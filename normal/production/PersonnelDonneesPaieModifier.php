<?php

	$req=(" SELECT  
			
					professeurdonneepaie.id as id,
					professeurdonneepaie.idpers as idpers,
					professeurdonneepaie.salairebase as salairebase,
					professeurdonneepaie.sursalaire as sursalaire,
					professeurdonneepaie.indemnitefonction as indemnitefonction,
					professeurdonneepaie.primesujetion as primesujetion,
					professeurdonneepaie.primeinterim as primeinterim,
					professeurdonneepaie.indemnitelogement as indemnitelogement,
					professeurdonneepaie.primecaisse as primecaisse,
					professeurdonneepaie.indemnitetransport as indemnitetransport,
					professeurdonneepaie.idanneescolaire as idanneescolaire
					
			FROM professeurdonneepaie
			WHERE
			professeurdonneepaie.id=:id");
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt->execute();
	$salairebase="";
	$sursalaire="";
	$indemnitefonction="";
	$primesujetion="";
	$primeinterim="";
	$indemnitelogement="";
	$indemnitetransport="";
	$primecaisse="";
	if($donnees = $stmt->fetch())
	{
		$idpers = $donnees['idpers'];
		$idanneescolaire = $donnees['idanneescolaire'];
		$salairebase = round(str_replace(" ","",$donnees['salairebase']));
		if($salairebase=="" || $salairebase=="-")
		{
			$salairebase=0;
		}
		$sursalaire = round(str_replace(" ","",$donnees['sursalaire']));
		if($sursalaire=="" || $sursalaire=="-")
		{
			$sursalaire=0;
		}
		$indemnitefonction = round(str_replace(" ","",$donnees['indemnitefonction']));
		if($indemnitefonction=="" || $indemnitefonction=="-")
		{
			$indemnitefonction=0;
		}
		$primesujetion = round(str_replace(" ","",$donnees['primesujetion']));
		if($primesujetion=="" || $primesujetion=="-")
		{
			$primesujetion=0;
		}
		$primeinterim = round(str_replace(" ","",$donnees['primeinterim']));
		if($primeinterim=="" || $primeinterim=="-")
		{
			$primeinterim=0;
		}
		$indemnitelogement = round(str_replace(" ","",$donnees['indemnitelogement']));
		if($indemnitelogement=="" || $indemnitelogement=="-")
		{
			$indemnitelogement=0;
		}
		$indemnitetransport = round(str_replace(" ","",$donnees['indemnitetransport']));
		if($indemnitetransport=="" || $indemnitetransport=="-")
		{
			$indemnitetransport=0;
		}
		$primecaisse = round(str_replace(" ","",$donnees['primecaisse']));
		if($primecaisse=="" || $primecaisse=="-")
		{
			$primecaisse=0;
		}
	}
	$stmt -> closeCursor();
	$stmt = NULL;
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
                    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
								Modifier les donn&eacute;es de paie de l'employ&eacute;
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
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Nom employ&eacute;</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllPersonnelSelected($idpers,$pdo);?>
											</div>
										</div>
									</td>
									<td width="50%">
										<div class="form-group">
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
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Salaire base
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="salairebase" class="form-control col-md-7 col-xs-12" value="<?php echo $salairebase;?>"/>
												<input type="hidden" name="id" class="form-control col-md-7 col-xs-12" value="<?php echo $id;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Caisse
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="primecaisse" class="form-control col-md-7 col-xs-12" value="<?php echo $primecaisse;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Sursalaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="sursalaire" class="form-control col-md-7 col-xs-12" value="<?php echo $sursalaire;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Fonction
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="indemnitefonction" class="form-control col-md-7 col-xs-12" value="<?php echo $indemnitefonction;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Sujestion
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="primesujetion" class="form-control col-md-7 col-xs-12" value="<?php echo $primesujetion;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Prime Interim
												<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="primeinterim" class="form-control col-md-7 col-xs-12" value="<?php echo $primeinterim;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Indem. Logement
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="indemnitelogement" class="form-control col-md-7 col-xs-12" value="<?php echo $indemnitelogement;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">
												Indem. Transport
											<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="indemnitetransport" class="form-control col-md-7 col-xs-12" value="<?php echo $indemnitetransport;?>"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PersonnelDonneesPaie.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="ModifierPersonnelDonneesPaie" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>