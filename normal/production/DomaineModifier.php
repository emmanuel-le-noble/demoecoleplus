<?php
   	$req=(' SELECT  
                    domaine.id as iddomaine,
					domaine.nom as nomdomaine,
					domaine.statut as statutdomaine,
					domaine.tel as teldomaine,
					domaine.bp as bpdomaine,
					domaine.logo as logodomaine
					
			FROM domaine
			WHERE
			domaine.id=:id');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();	
		if($donnees = $stmt->fetch())
		{
			$iddomaine = $donnees['iddomaine'];
			$nomdomaine = $donnees['nomdomaine'];
			$statutdomaine = $donnees['statutdomaine'];
			$teldomaine = $donnees['teldomaine'];
			$bpdomaine = $donnees['bpdomaine'];
			$logodomaine = $donnees['logodomaine'];
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
						        Modifier un domaine
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Nom<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="nom" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $nomdomaine;?>"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Boîte postale<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="bp" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $bpdomaine;?>"/>
												<input type="hidden" name="iddomaine" value="<?php echo $iddomaine;?>"/>
												<input type="hidden" name="logodomaine" value="<?php echo $logodomaine;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Logo<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="file" class="form-control" name="image"/>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left;">Contact<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <input type="text" name="tel" required="required" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '(999) 99999999'" value="<?php echo $teldomaine;?>"/>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="middle">									
										<hr/>
										<div id="image_preview" class="">
											<div class="">
												<img src="domainelogo/<?php echo $logodomaine;?>" alt="" width="200px" height="50px" style="border:0px solid #000;"/>
											</div>
										</div>
									</td>
								</tr>
							</table><hr style="border:1px dotted #000;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Domaine.php'"/>
								<input type="submit" class="btn btn-round btn-success" name="ModifierDomaine" value="Enregistrer"/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>