<?php
	include("../modele/connexion.php");
    include("../modele/inscription.php");
	
	$idclasse=$_REQUEST['val_sel'];
?>
<table width="100%">
	<tr>
		<td width="58%" valign="top" style="margin-left:5px;">
			<div class="form-group">
				<?php InscriptionDefinitionPaiement($idclasse,$pdo);?>
			</div>
		</td>
	</tr>
</table>