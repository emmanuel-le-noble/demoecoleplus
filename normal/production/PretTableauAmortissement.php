<?php
	session_name("ecoleplus");
    session_start();
	                         
	include("../modele/connexion.php");
    include("../modele/pret.php");
	
	$idpret=$_REQUEST['val_sel'];
?>
<table width="100%">
	<tr>
		<td width="49%" valign="top" style="margin-left:5px;">
			<div class="form-group">
				<?php TableauAmortissment_2($idpret,$pdo);?>
			</div>
		</td>
		<td width="2%">&nbsp;&nbsp;&nbsp;</td>
		<td width="49%" valign="top" style="margin-left:5px;">
			<div class="form-group">
				<?php TableauAmortissment_3($idpret,$pdo);?>
			</div>
		</td>
	</tr>
</table>
<hr style="border:1px dotted #000;"/>
<div align="center" class="col-md-6 col-md-offset-3">
	<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='PretRemboursement.php'"/>
	<input type="submit" class="btn btn-round btn-success" name="EnregistrerPretRemboursement" value="Enregistrer"/> 
</div>	
