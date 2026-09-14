<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/statistiquefinance.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$id = trim($_REQUEST["id"]);	
	$tab = explode("*",trim($id));
	$idclasse = $tab[0];
	$idpaiementtype = $tab[1];
	$idanneescolaire = $tab[2];
	$codeclasse = $tab[3];										
	$paiementtypelibelle = getlibelletypepaiement($idpaiementtype,$pdo);
	$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
?>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        	D&eacute;tail de l'&eacute;tat du recouvrement
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div>
									<div><span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
									Information(s) principale(s)</span></div><br/>
									<table width="100%" align="center">
										<tr>
											<td width="30%">
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12">
														Type de frais : <?php echo $paiementtypelibelle;?>
														<input name="idPaiementType" type="hidden" value="<?php echo $idpaiementtype;?>"/>
													</label>
												</div>
											</td>
											<td width="35%">
												<div class="item form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12">
														Ann&eacute;e scolaire : <?php echo $libelleanneescolaire;?>
														<input name="idanneescolaire" type="hidden" value="<?php echo $idanneescolaire;?>"/>
													</label>
												</div>
											</td>
											<td width="25%">
												<div class="item form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12">
														Classe : <?php echo $codeclasse;?>
													</label>
												</div>
											</td>
											<td width="10%">
												<button class="btn btn-round" target="_blank" onclick='window.open("EtatRecouvrementResultatDetailRapport.php?&idpaiementtype=<?php echo $idpaiementtype;?>&idanneescolaire=<?php echo $idanneescolaire;?>&idclasse=<?php echo $idclasse;?>&codeclasse=<?php echo $codeclasse;?>","", "fullscreen=yes, scrollbars=auto");'>
													<i class="fa fa-print"></i>&nbsp;<a style="color:#000;">Imprimer la liste</a>
												</button>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>
									<div style="text-align:left;">
										<h4 style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
											[D&eacute;sultat de l'&eacute;tat du recouvrement]
										</h4><br/>
									</div>
									<?php EtatRecouvrementDetail($idpaiementtype,$idanneescolaire,$idclasse,$pdo);?>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="submit" class="btn btn-round btn-info" name="AfficherSituation" value="Fermer"/> 
									</div>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>