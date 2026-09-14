<?php
	include("../modele/connexion.php");
    include("../modele/function.php");
	include("../modele/liste.php");
	
	$idclasse=$_REQUEST['val_sel'];
?>
<table width="100%">
	<tr>
		<td valign="top" style="margin-left:5px;">
			<div class="form-group">
				<?php ListeInscrit_($idclasse,$pdo);?>
			</div>
		</td>
	</tr>
</table>
<hr/>
<div align="center" class="col-md-6 col-md-offset-3">
	<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='AffectationSalle.php'"/>
	<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerAffectation" value="Enregistrer"/> 
</div>