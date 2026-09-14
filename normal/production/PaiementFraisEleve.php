<?php
	include("../modele/connexion.php");
	include("../modele/droit.php");
    include("../modele/paiementfrais.php");

	$response=$_REQUEST['val_sel'];
	if($response!="")
	{
		$tab = explode("*",trim($response));
		$ideleve = $tab[0];
		$ideleveanneescolaire = $tab[1];
		$boursier = $tab[2];
		$idclasse = $tab[3];
		$idanneescolaire = $tab[4];
		$inscrit = $tab[5];
		?>
		<table width="100%">
			<tr>
				<td width="58%" valign="top" style="margin-left:5px;">
					<div class="form-group">
						<?php PaiementEleve($ideleveanneescolaire,$boursier,$inscrit,$idclasse,$idanneescolaire,$pdo);?>
					</div>
				</td>
				<td width="2%">&nbsp;&nbsp;&nbsp;</td>
				<td width="40%" valign="top" style="margin-left:5px;">
					<div class="form-group">
						<?php ListeEtudiantPaiementFrais($ideleveanneescolaire,$pdo);?>
					</div>
				</td>
			</tr>
		</table>
		<hr style="border:1px dotted #000;"/>
		<div align="center" class="col-md-6 col-md-offset-3">
			<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PaiementFrais.php'"/>
			<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerPaiement" value="Enregistrer"/> 
		</div><?php
	}
?>
