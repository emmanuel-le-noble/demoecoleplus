<?php

	/*$req=(" SELECT  distinct 
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
			}*/
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
							<h2 style="font-family:comic sans ms;font-weight:bold;color:red;">
								D&eacute;tail du calcul de paie : <?php echo $libelle;?>
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
						<form action="" method="POST">
							<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header text-center">
											<h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body mx-3">
											<div class="md-form mb-5">
												<i class="fas fa-envelope prefix grey-text"></i>
												<input type="email" id="defaultForm-email" class="form-control validate">
												<label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
											</div>
											<div class="md-form mb-4">
												<i class="fas fa-lock prefix grey-text"></i>
												<input type="password" id="defaultForm-pass" class="form-control validate">
												<label data-error="wrong" data-success="right" for="defaultForm-pass">Your password</label>
											</div>
										</div>
										<div class="modal-footer d-flex justify-content-center">
											<button class="btn btn-default">Login</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>