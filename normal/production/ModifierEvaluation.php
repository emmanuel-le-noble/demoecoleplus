<?php
	$req=(' SELECT  
				evaluationcalendrier.id,
				evaluationcalendrier.idmatiere,
				evaluationcalendrier.idevaluation,
				evaluationcalendrier.idsalle,
				evaluationcalendrier.dateevaluation

			FROM evaluationcalendrier
			WHERE
			evaluationcalendrier.id=:id');
		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();	
	$ligne=0;
	if($donnees = $stmt->fetch())
	{
		$ligne++;
		
		$id = $donnees['id'];
		$idmatiere = $donnees['idmatiere'];
		$idevaluation = $donnees['idevaluation'];
		$idsalle = $donnees['idsalle'];
		$dateevaluation = $donnees['dateevaluation'];
		$tab = explode("-",$dateevaluation);
		$dateevaluation=$tab[2]."/".$tab[1]."/".$tab[0];
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
					<h2>Administration - Evaluation - Modifier</h2>
				</div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2><font color="red"><b>Modifier une &eacute;valuation</b></font></h2>
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
							<table width="100%">
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nom" style="text-align:left;">Evaluation<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllEvaluationSelected($idevaluation,$pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nom" style="text-align:left;">Matiere<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllMatiereSelected($idmatiere,$pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="item form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nom" style="text-align:left;">Salle<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllSalleSelected($idsalle,$pdo);?>
												<input type="hidden" value="<?php echo $id;?>" name="idcalendrier"/>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="date_debut" style="text-align:left;">Date<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="date_evaluation" value="<?php echo $dateevaluation;?>"/>
												<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
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
					<input type="button" class="btn btn-primary" value="Fermer" onclick="document.location='Evaluation.php'"/>
					<input id="send" type="submit" class="btn btn-success" name="modifier2_evaluation" value="Enregistrer"/> 
				</div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    </body>
</html>