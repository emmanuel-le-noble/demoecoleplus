<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
    include("../modele/dossiereleve.php");
	
	$tab = explode("*",$_REQUEST['val_sel']);
	$idsalle = $tab[0];
	$idclasse = $tab[1];
?>
<table width="100%">
	<tr>
		<td width="58%" valign="top" style="margin-left:5px;">
			<div class="form-group">
				<?php InscriptionDefinitionPaiement($idclasse,$_SESSION['idanneescolaire'],$pdo);?>
			</div>
		</td>
	</tr>
</table>