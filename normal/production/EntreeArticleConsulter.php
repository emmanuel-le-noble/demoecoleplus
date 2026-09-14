<?php
	$req=(' SELECT  
					sortie.id as id,
					sortie.datesortie as datesortie,
					sortie.num as num,
					vehicule.num as numvehicule,
					personnel.nom as nompersonnel,
					sortie.statut as statut,
					sortie.idfacture as idfacture,
					client.nom as nomclient

			FROM sortie left join vehicule on sortie.idvehicule=vehicule.id
				 		left join personnel on sortie.idchauffeur=personnel.id
			     		left join client on sortie.idclient=client.id

			WHERE
			sortie.id=:id');

	$resultat="";		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':id', $idSortie, PDO::PARAM_STR);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$idSortie = $donnees['id'];
		$tab = explode('-',$donnees['datesortie']);
		$DateSortie = $tab[2].'/'.$tab[1].'/'.$tab[0];
		$Num = $donnees['num'];
		$Vehicule = $donnees['numvehicule'];
		$Chauffeur = $donnees['nompersonnel'];
		$Statut = $donnees['statut'];
		$Nomclient = $donnees['nomclient'];
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
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h2 style="font-family:comic sans ms;font-weight: bold;color:#000;">
								Consulter le bordereau de livraison N&deg; <?php echo $Num;?> 
							</h2>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<table width="100%">
								<tr>
									<td colspan="2">
										<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;color:#000;">
											<i>::: Informations du bordereau de livraison :::</i>
										</label><br/><br/>
									</td>
								</tr>
								<tr>
									<td  width="50%">
										<label class="control-label col-md-6 col-sm-6 col-xs-12"style="text-align:left;">
											Num. Bordereau : <?php echo $Num;?>
										</label>
									</td>
								</tr>
								<tr>
									<td  width="50%">
										<label class="control-label col-md-6 col-sm-6 col-xs-12"style="text-align:left;">
											Date sortie : <?php echo $DateSortie;?>
										</label>
									</td>
									<td  width="50%">
										<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">
											Client : <?php echo $Nomclient;?>
										</label>
									</td>
								</tr>
								<tr>
									<td width="50%">
										<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">
											Vehicule : <?php echo $Vehicule;?>  
										</label>
									</td>
									<td width="50%">
										<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">
											Chauffeur(s) : <?php echo $Chauffeur;?> 
										</label>
									</td>
								</tr>
								<tr>
									<td colspan="2"><br/>
										<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;color:#000;">
											<i> ::: Liste des articles livr&eacute;s pour le bordereau de livraison ::: </i>
										</label><br/><br/><br/>
									</td>
								</tr>
								<tr>
									<td colspan="2"><?php BLSF_($idSortie,$pdo);?></td>
								</tr>
							</table>
							<input type="hidden" name="idsortie" value="<?php echo $idSortie;?>">						
					    </div>
                    </div>
                </div>
				<div align="center" class="col-md-6 col-md-offset-3">
					<a href="BordereauLivraisonSansFacture.php">
						<input type="button" class="btn btn-round btn-primary" value="Fermer"/>
					</a>
				</div>
            </div>
        </div>
    </div>
    </body>
</html>
                    		
						